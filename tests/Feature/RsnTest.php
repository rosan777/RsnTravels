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
        foreach ([["owner", "/owner/dashboard"], ["agent", "/agent/dashboard"], ["staff", "/staff/dashboard"], ["customer", "/customer/dashboard"]] as [$type, $url]) {
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

    public function test_setup_route_is_closed_without_token()
    {
        $this->get("/setup-demo/anything")->assertStatus(404);
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

    // ---- booking flow: cart -> request -> owner approval -> staff -> review ----

    private function cart_room($customer, $room, $in, $out)
    {
        return $this->actingAs($customer)->post(route("customer_add_to_cart"), [
            "type" => "hotel", "room_type_id" => $room->room_type_id, "check_in" => $in, "check_out" => $out, "guests" => 2,
        ]);
    }

    public function test_full_booking_journey()
    {
        $room = $this->make_hotel();
        $customer = $this->make_user("customer@example.com", "customer");
        $owner = $this->make_user("owner@example.com", "owner");
        $staff = $this->make_user("staff@example.com", "staff");
        $in = Carbon::today()->addDays(10)->toDateString();
        $out = Carbon::today()->addDays(12)->toDateString();

        $this->cart_room($customer, $room, $in, $out)->assertRedirect(route("customer_cart"));
        $this->actingAs($customer)->get(route("customer_cart"))->assertOk()->assertSee("Test Resort")->assertSee("10,000");
        $this->actingAs($customer)->post(route("customer_checkout"), ["payment_method" => "bKash"])->assertRedirect(route("customer_bookings"));

        $booking = Booking::first();
        $this->assertSame("Pending", $booking->status);
        $this->assertSame("PAID", $booking->payment_status);
        $this->assertEquals(10000, $booking->total);

        // staff cannot see or check in a booking before approval
        $this->actingAs($staff)->post(route("staff_check_in", $booking->id))->assertSessionHas("error");
        $this->actingAs($staff)->post(route("owner_approve_booking", $booking->id))->assertStatus(403);

        $this->actingAs($owner)->post(route("owner_approve_booking", $booking->id))->assertSessionHas("success");
        $this->assertSame("Confirmed", $booking->fresh()->status);

        $this->actingAs($staff)->get(route("staff_bookings"))->assertOk()->assertSee("Check in");
        $this->actingAs($staff)->post(route("staff_check_in", $booking->id));
        $this->assertSame("CheckedIn", $booking->fresh()->status);
        $this->assertSame("Occupied", $room->fresh()->status);

        $this->actingAs($staff)->post(route("staff_check_out", $booking->id));
        $this->assertSame("Completed", $booking->fresh()->status);
        $this->assertSame("Cleaning", $room->fresh()->status);

        $this->actingAs($customer)->post(route("customer_review", $booking->id), ["rating" => 5, "comment" => "Lovely"])->assertSessionHas("success");
        $this->assertSame(1, \App\Models\Review::count());
        $this->actingAs($customer)->get(route("customer_invoice", $booking->id))->assertOk()->assertSee("TD-000001");
    }

    public function test_checkout_blocks_a_room_taken_in_the_meantime()
    {
        $room = $this->make_hotel();
        $first = $this->make_user("customer@example.com", "customer");
        $second = $this->make_user("two@example.com", "customer");
        $in = Carbon::today()->addDays(5)->toDateString();
        $out = Carbon::today()->addDays(7)->toDateString();

        // - both customers hold the same room in their carts
        $this->cart_room($first, $room, $in, $out);
        $cart_one = session("cart");
        $this->flushSession();
        $this->cart_room($second, $room, $in, $out);
        $cart_two = session("cart");
        $this->flushSession();

        $this->withSession(["cart" => $cart_one])->actingAs($first)->post(route("customer_checkout"), ["payment_method" => "Card"]);
        $this->flushSession();
        $this->withSession(["cart" => $cart_two])->actingAs($second)->post(route("customer_checkout"), ["payment_method" => "Card"])->assertSessionHas("error");
        $this->assertSame(1, Booking::count());
    }

    public function test_one_cart_never_double_books_a_single_room()
    {
        $room = $this->make_hotel();
        $customer = $this->make_user("customer@example.com", "customer");
        $in = Carbon::today()->addDays(5)->toDateString();
        $out = Carbon::today()->addDays(7)->toDateString();
        $this->cart_room($customer, $room, $in, $out);
        $this->cart_room($customer, $room, $in, $out);
        $this->actingAs($customer)->post(route("customer_checkout"), ["payment_method" => "Card"])->assertSessionHas("error");
        $this->assertSame(0, Booking::count());
    }

    public function test_reject_and_cancel_free_the_room()
    {
        $room = $this->make_hotel();
        $customer = $this->make_user("customer@example.com", "customer");
        $owner = $this->make_user("owner@example.com", "owner");
        $in = Carbon::today()->addDays(5)->toDateString();
        $out = Carbon::today()->addDays(7)->toDateString();

        $this->cart_room($customer, $room, $in, $out);
        $this->actingAs($customer)->post(route("customer_checkout"), ["payment_method" => "Nagad"]);
        $booking = Booking::first();
        $this->actingAs($owner)->post(route("owner_reject_booking", $booking->id));
        $this->assertSame("Cancelled", $booking->fresh()->status);
        $this->assertSame("UNPAID", $booking->fresh()->payment_status);
        $this->assertTrue($room->isFree($in, $out));

        // a customer cannot cancel someone else's booking
        $other = $this->make_user("other@example.com", "customer");
        $this->actingAs($other)->post(route("customer_cancel_booking", $booking->id))->assertStatus(404);
    }

    public function test_package_in_cart_and_checkout()
    {
        $this->make_hotel();
        $customer = $this->make_user("customer@example.com", "customer");
        $package = TourPackage::first();
        $this->actingAs($customer)->post(route("customer_add_to_cart"), ["type" => "package", "package_id" => $package->id, "travel_date" => Carbon::today()->addDays(20)->toDateString(), "guests" => 2]);
        $this->actingAs($customer)->post(route("customer_checkout"), ["payment_method" => "PayAtHotel"]);
        $booking = Booking::first();
        $this->assertSame("Package", $booking->booking_type);
        $this->assertEquals(120000, $booking->total);
        $this->assertSame("UNPAID", $booking->payment_status);
    }

    public function test_favorites_and_cart_need_login()
    {
        $room = $this->make_hotel();
        $this->get(route("customer_cart"))->assertRedirect(route("flogin"));
        $this->post(route("toggle_favorite"), ["type" => "hotel", "id" => 1])->assertRedirect(route("flogin"));

        $customer = $this->make_user("customer@example.com", "customer");
        $hotel_id = $room->roomType->hotel_id;
        $this->actingAs($customer)->post(route("toggle_favorite"), ["type" => "hotel", "id" => $hotel_id]);
        $this->actingAs($customer)->get(route("customer_favorites"))->assertOk()->assertSee("Test Resort");
        $this->actingAs($customer)->post(route("toggle_favorite"), ["type" => "hotel", "id" => $hotel_id]);
        $this->assertSame(0, \App\Models\Favorite::count());
    }

    public function test_new_pages_render_for_each_role()
    {
        $this->make_hotel();
        $this->get(route("project_profile"))->assertOk()->assertSee("Technology used in this project")->assertSee("Contact");
        $this->get(route("index"))->assertOk()->assertSee("Tanah-Lot", false);
        foreach ([["owner", ["owner_dashboard", "owner_bookings", "owner_catalog"]], ["staff", ["staff_bookings", "staff_rooms"]], ["customer", ["customer_bookings", "customer_cart"]]] as [$role, $routes]) {
            $user = $this->make_user("{$role}@example.com", $role);
            foreach ($routes as $name) {
                $this->actingAs($user)->get(route($name))->assertOk();
            }
        }
    }

    // - the work of each role: tasks are created and given out by themselves
    public function test_booking_gives_work_to_agent_then_staff()
    {
        $room = $this->make_hotel();
        $customer = $this->make_user("customer@example.com", "customer");
        $owner = $this->make_user("owner@example.com", "owner");
        $agent = $this->make_user("agent@example.com", "agent");
        $staff = $this->make_user("staff@example.com", "staff");
        $in = Carbon::today()->addDays(10)->toDateString();
        $out = Carbon::today()->addDays(12)->toDateString();

        $this->cart_room($customer, $room, $in, $out);
        $this->actingAs($customer)->post(route("customer_checkout"), ["payment_method" => "bKash"]);
        $booking = Booking::first();

        // checkout: the agent gets a call task
        $call = \App\Models\WorkTask::where("kind", "follow_up")->first();
        $this->assertSame($agent->id, $call->assigned_to);
        $this->actingAs($agent)->get(route("agent_dashboard"))->assertOk()->assertSee("Call customer@example.com");

        // approval: the staff member gets an arrival task
        $this->actingAs($owner)->post(route("owner_approve_booking", $booking->id));
        $prep = \App\Models\WorkTask::where("kind", "arrival_prep")->first();
        $this->assertSame($staff->id, $prep->assigned_to);

        // only the person it belongs to can finish a task, the owner can finish any
        $this->actingAs($agent)->post(route("task_done", $prep->id))->assertSessionHas("error");
        $this->assertSame("Open", $prep->fresh()->status);
        $this->actingAs($staff)->post(route("task_done", $prep->id));
        $this->assertSame("Done", $prep->fresh()->status);

        // check-out: a housekeeping task appears and ends when the room is set Available
        $this->actingAs($staff)->post(route("staff_check_in", $booking->id));
        $this->actingAs($staff)->post(route("staff_check_out", $booking->id));
        $this->assertSame(1, \App\Models\WorkTask::where("kind", "housekeeping")->where("status", "Open")->count());
        $this->actingAs($staff)->post(route("staff_set_room", $room->id), ["status" => "Available"]);
        $this->assertSame(0, \App\Models\WorkTask::where("kind", "housekeeping")->where("status", "Open")->count());
    }

    public function test_rejecting_a_booking_closes_its_tasks()
    {
        $room = $this->make_hotel();
        $customer = $this->make_user("customer@example.com", "customer");
        $owner = $this->make_user("owner@example.com", "owner");
        $this->make_user("agent@example.com", "agent");
        $in = Carbon::today()->addDays(10)->toDateString();
        $this->cart_room($customer, $room, $in, Carbon::today()->addDays(12)->toDateString());
        $this->actingAs($customer)->post(route("customer_checkout"), ["payment_method" => "Card"]);
        $this->assertSame(1, \App\Models\WorkTask::where("status", "Open")->count());
        $this->actingAs($owner)->post(route("owner_reject_booking", Booking::first()->id));
        $this->assertSame(0, \App\Models\WorkTask::where("status", "Open")->count());
    }

    public function test_owner_gives_and_moves_tasks()
    {
        $owner = $this->make_user("owner@example.com", "owner");
        $a1 = $this->make_user("agent@example.com", "agent");
        $s1 = $this->make_user("staff@example.com", "staff");
        $customer = $this->make_user("customer@example.com", "customer");
        $this->actingAs($owner)->post(route("owner_create_task"), ["title" => "Order towels", "assigned_to" => $s1->id])->assertSessionHas("success");
        $task = \App\Models\WorkTask::first();
        $this->assertSame($s1->id, $task->assigned_to);
        $this->actingAs($owner)->post(route("owner_reassign_task", $task->id), ["assigned_to" => $a1->id]);
        $this->assertSame($a1->id, $task->fresh()->assigned_to);
        // a customer cannot be given a task
        $this->actingAs($owner)->post(route("owner_create_task"), ["title" => "x", "assigned_to" => $customer->id])->assertSessionHas("error");
        $this->actingAs($owner)->get(route("owner_team"))->assertOk()->assertSee("Order towels");
        $this->actingAs($a1)->get(route("owner_team"))->assertStatus(403);
    }

    public function test_customer_asks_and_agent_replies()
    {
        $customer = $this->make_user("customer@example.com", "customer");
        $agent = $this->make_user("agent@example.com", "agent");
        $this->actingAs($customer)->post(route("customer_inquiries"), ["subject" => "Visa?", "message" => "Do I need a visa for Bali?"])->assertRedirect(route("customer_inquiries"));
        $inquiry = \App\Models\Inquiry::first();
        $this->assertSame($agent->id, $inquiry->agent_id);
        $this->actingAs($customer)->post(route("customer_inquiries"), ["subject" => "", "message" => ""])->assertSessionHas("error");
        $this->actingAs($agent)->get(route("agent_inquiries"))->assertOk()->assertSee("Visa?");
        $this->actingAs($agent)->post(route("agent_reply_inquiry", $inquiry->id), ["reply" => "Yes, on arrival."])->assertSessionHas("success");
        $this->actingAs($customer)->get(route("customer_inquiries"))->assertSee("Yes, on arrival.");
        $this->actingAs($customer)->get(route("agent_inquiries"))->assertStatus(403);
    }
}
