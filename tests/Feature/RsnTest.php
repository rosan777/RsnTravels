<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\TourPackage;
use App\Models\User;
use App\Models\UserEx;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RsnTest extends TestCase
{
    use RefreshDatabase;

    // - small fixture: 3 roles, one destination, one package, one hotel with one room
    private function make_user($email, $user_type)
    {
        $user = User::create(["name" => $email, "email" => $email, "password" => Hash::make($email)]);
        UserEx::create(["user_id" => $user->id, "user_type" => $user_type]);
        return $user;
    }

    private function make_hotel()
    {
        $destination = Destination::create(["name" => "Bali", "country" => "Indonesia", "show_on_site" => true]);
        $hotel = Hotel::create(["destination_id" => $destination->id, "name" => "Test Resort", "city" => "Ubud", "stars" => 4, "show_on_site" => true]);
        $room_type = RoomType::create(["hotel_id" => $hotel->id, "name" => "Deluxe", "capacity" => 2, "base_price" => 5000]);
        $room = Room::create(["room_type_id" => $room_type->id, "number" => "101"]);
        TourPackage::create(["destination_id" => $destination->id, "name" => "Bali Trip", "days" => 5, "price" => 60000, "show_on_site" => true]);
        return $room;
    }

    private function book($room, $user, $in, $out, $status = "Confirmed")
    {
        return Booking::create([
            "user_id" => $user->id,
            "booking_type" => "Hotel",
            "room_id" => $room->id,
            "check_in" => $in,
            "check_out" => $out,
            "guests" => 2,
            "total" => 0,
            "status" => $status,
        ]);
    }

    private function day($n)
    {
        return Carbon::today()->addDays($n)->toDateString();
    }

    public function test_public_pages_open_without_login()
    {
        $this->make_hotel();
        foreach (["/", "/destinations", "/destinations/1", "/packages", "/packages/1", "/hotels", "/hotels/1", "/search-rooms", "/login", "/register"] as $url) {
            $this->get($url)->assertStatus(200);
        }
    }

    public function test_wrong_ids_show_not_found_page()
    {
        $this->make_hotel();
        foreach (["/destinations/999", "/packages/999", "/hotels/999", "/hotels/abc", "/no-such-page"] as $url) {
            $this->get($url)->assertStatus(404)->assertSee("ot found");
        }
    }

    public function test_hidden_hotel_is_not_shown()
    {
        $room = $this->make_hotel();
        $room->roomType->hotel->update(["show_on_site" => false]);
        $this->get("/hotels/" . $room->roomType->hotel_id)->assertStatus(404);
    }

    public function test_demo_login_goes_to_each_dashboard()
    {
        foreach ([["owner", "/owner/dashboard"], ["staff", "/staff/dashboard"], ["customer", "/customer/dashboard"]] as [$type, $url]) {
            $email = "{$type}@example.com";
            $this->make_user($email, $type);
            $this->post("/login", ["email" => $email, "password" => $email])->assertRedirect($url);
            $this->get($url)->assertStatus(200);
            $this->get("/logout");
        }
    }

    public function test_wrong_password_and_unknown_email()
    {
        $this->make_user("customer@example.com", "customer");
        $this->post("/login", ["email" => "customer@example.com", "password" => "nope"])->assertSessionHas("error", "Invalid password");
        $this->post("/login", ["email" => "x@example.com", "password" => "nope"])->assertSessionHas("error", "User with email does not exists");
        $this->assertGuest();
    }

    public function test_guest_is_sent_to_login_from_areas()
    {
        foreach (["/owner/dashboard", "/staff/dashboard", "/customer/dashboard"] as $url) {
            $this->get($url)->assertRedirect("/login");
        }
    }

    public function test_roles_cannot_open_other_areas()
    {
        $customer = $this->make_user("customer@example.com", "customer");
        $staff = $this->make_user("staff@example.com", "staff");
        $this->actingAs($customer)->get("/owner/dashboard")->assertStatus(403);
        $this->actingAs($customer)->get("/staff/dashboard")->assertStatus(403);
        $this->actingAs($staff)->get("/owner/dashboard")->assertStatus(403);
        $this->actingAs($staff)->get("/customer/dashboard")->assertStatus(403);
    }

    public function test_owner_can_open_staff_area()
    {
        $owner = $this->make_user("owner@example.com", "owner");
        $this->actingAs($owner)->get("/staff/dashboard")->assertStatus(200);
    }

    public function test_register_never_creates_owner()
    {
        $this->post("/register", [
            "first_name" => "New",
            "last_name" => "User",
            "email" => "new@example.com",
            "phone" => "01800000000",
            "user_type" => "owner",
            "password" => "secret123",
            "password2" => "secret123",
        ])->assertRedirect("/customer/dashboard");
        $this->assertEquals("customer", User::where("email", "new@example.com")->first()->userEx->user_type);
    }

    public function test_register_checks_password_and_duplicate_email()
    {
        $this->make_user("customer@example.com", "customer");
        $this->post("/register", ["email" => "a@example.com", "password" => "a", "password2" => "b"])->assertSessionHas("error", "Passwords do not match");
        $this->post("/register", ["email" => "customer@example.com", "password" => "a", "password2" => "a"])->assertSessionHas("error", "User with email already exists");
    }

    public function test_room_overlap_rules()
    {
        $room = $this->make_hotel();
        $user = $this->make_user("customer@example.com", "customer");
        $this->book($room, $user, $this->day(10), $this->day(13));
        $this->assertFalse($room->isFree($this->day(11), $this->day(12)));
        $this->assertFalse($room->isFree($this->day(8), $this->day(11)));
        $this->assertFalse($room->isFree($this->day(9), $this->day(20)));
        // - check-out day is free for the next guest
        $this->assertTrue($room->isFree($this->day(13), $this->day(15)));
        $this->assertTrue($room->isFree($this->day(7), $this->day(10)));
    }

    public function test_cancelled_booking_frees_the_room()
    {
        $room = $this->make_hotel();
        $user = $this->make_user("customer@example.com", "customer");
        $this->book($room, $user, $this->day(10), $this->day(13), "Cancelled");
        $this->assertTrue($room->isFree($this->day(10), $this->day(13)));
    }

    public function test_search_shows_only_free_rooms()
    {
        $room = $this->make_hotel();
        $user = $this->make_user("customer@example.com", "customer");
        $url = "/search-rooms?where=Bali&check_in=" . $this->day(10) . "&check_out=" . $this->day(12) . "&guests=2";
        $this->get($url)->assertSee("Test Resort");
        $this->book($room, $user, $this->day(9), $this->day(11));
        $this->get($url)->assertDontSee("Test Resort");
    }

    public function test_search_checks_capacity()
    {
        $this->make_hotel();
        $this->get("/search-rooms?check_in=" . $this->day(10) . "&check_out=" . $this->day(12) . "&guests=4")->assertDontSee("Test Resort");
    }

    public function test_search_date_validation()
    {
        $this->make_hotel();
        $this->get("/search-rooms?check_in=" . $this->day(-2) . "&check_out=" . $this->day(1))->assertSee("cannot be in the past");
        $this->get("/search-rooms?check_in=" . $this->day(5) . "&check_out=" . $this->day(5))->assertSee("Check-out must be after check-in");
        $this->get("/search-rooms?check_in=" . $this->day(5) . "&check_out=" . $this->day(6) . "&guests=0")->assertSee("Guests must be between 1 and 10");
        $this->get("/search-rooms?check_in=nonsense&check_out=" . $this->day(6))->assertSee("Please pick valid dates");
    }

    public function test_search_price_is_nights_times_rate()
    {
        $this->make_hotel();
        $this->get("/search-rooms?check_in=" . $this->day(10) . "&check_out=" . $this->day(13) . "&guests=1")->assertSee("৳15,000");
    }

    public function test_package_filters()
    {
        $this->make_hotel();
        $this->get("/packages?budget=50000")->assertDontSee("Bali Trip");
        $this->get("/packages?budget=70000")->assertSee("Bali Trip");
        $this->get("/packages?days=3")->assertDontSee("Bali Trip");
    }

    public function test_language_toggle()
    {
        $this->get("/language/bn");
        $this->get("/")->assertSee("হোম");
        $this->get("/language/en");
        $this->get("/")->assertSee("Home");
    }

    public function test_footer_text()
    {
        $this->get("/")->assertSee("&copy; 2024 OANRO. All rights reserved.", false)->assertSee("Basic Laravel Project - Demo");
    }

    public function test_seed_demo_twice_does_not_duplicate()
    {
        $this->assertEquals(0, Artisan::call("seed:demo"));
        $counts = [User::count(), Hotel::count(), Room::count(), TourPackage::count(), Booking::count()];
        $this->assertEquals(0, Artisan::call("seed:demo"));
        $this->assertEquals($counts, [User::count(), Hotel::count(), Room::count(), TourPackage::count(), Booking::count()]);
        $this->assertGreaterThan(300, Booking::count());
    }

    public function test_seeded_bookings_never_overlap()
    {
        Artisan::call("seed:demo");
        $clash = Booking::from("booking as a")
            ->join("booking as b", function ($j) {
                $j->on("a.room_id", "=", "b.room_id")->on("a.id", "<", "b.id");
            })
            ->where("a.status", "!=", "Cancelled")
            ->where("b.status", "!=", "Cancelled")
            ->whereColumn("a.check_in", "<", "b.check_out")
            ->whereColumn("a.check_out", ">", "b.check_in")
            ->count();
        $this->assertEquals(0, $clash);
    }
}
