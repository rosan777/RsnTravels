<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\TourPackage;
use App\Models\User;
use App\Models\UserEx;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RsnController extends Controller
{
    // - Language toggle (English / Bangla), kept in the session
    public function set_language(Request $request, $code)
    {
        if (in_array($code, ["en", "bn"])) {
            $request->session()->put("lang", $code);
        }
        return redirect()->back(fallback: route("index"));
    }

    // - same "Not found" page for every wrong id
    private function not_found($error = "Not found.")
    {
        return response()->view("error", ["error" => $error], 404);
    }

    // - checks dates and guests; returns an error text or null
    private function check_dates($check_in, $check_out, $guests = 1)
    {
        try {
            $in = Carbon::parse($check_in)->startOfDay();
            $out = Carbon::parse($check_out)->startOfDay();
        } catch (\Exception $e) {
            return "Please pick valid dates";
        }
        if ($in->lt(Carbon::today())) {
            return "Check-in date cannot be in the past";
        }
        if ($out->lte($in)) {
            return "Check-out must be after check-in";
        }
        if ($in->diffInDays($out) > 30) {
            return "You can book at most 30 nights";
        }
        if ((int) $guests < 1 || (int) $guests > 10) {
            return "Guests must be between 1 and 10";
        }
        return null;
    }

    // - free rooms of one room type for the dates
    private function free_rooms($room_type, $check_in, $check_out)
    {
        return $room_type->rooms->filter(fn ($room) => $room->isFree($check_in, $check_out))->values();
    }

    // Public Area
    public function index(Request $request)
    {
        $packages_show_on_site = TourPackage::with("destination")->where("show_on_site", true)->take(6)->get();
        $hotels_show_on_site = Hotel::with("roomTypes")->where("show_on_site", true)->take(6)->get();
        $destinations = Destination::where("show_on_site", true)->get();

        return view("index", [
            "packages" => $packages_show_on_site,
            "hotels" => $hotels_show_on_site,
            "destinations" => $destinations,
            "slides" => $destinations->filter(fn ($d) => $d->image_url)->take(5),
        ]);
    }

    public function view_destinations(Request $request)
    {
        $destinations = Destination::where("show_on_site", true)->withCount("packages", "hotels")->get();
        return view("destinations", ["destinations" => $destinations]);
    }

    public function view_destination(Request $request, $id)
    {
        $destination = Destination::where("id", $id)->where("show_on_site", true)->first();
        if (!$destination) {
            return $this->not_found("Destination not found.");
        }
        return view("destination", [
            "destination" => $destination,
            "packages" => $destination->packages()->where("show_on_site", true)->get(),
            "hotels" => $destination->hotels()->where("show_on_site", true)->get(),
        ]);
    }

    public function view_packages(Request $request)
    {
        $items = TourPackage::with("destination")->where("show_on_site", true);

        if ($request->input("destination")) {
            $items->where("destination_id", $request->input("destination"));
        }
        if ($request->input("budget")) {
            $items->where("price", "<=", (float) $request->input("budget"));
        }
        if ($request->input("days")) {
            $items->where("days", "<=", (int) $request->input("days"));
        }
        if ($request->input("interest")) {
            $items->where("interest", $request->input("interest"));
        }

        return view("packages", [
            "items" => $items->orderBy("price")->get(),
            "destinations" => Destination::where("show_on_site", true)->orderBy("name")->get(),
            "interests" => TourPackage::INTERESTS,
        ]);
    }

    public function view_package(Request $request, $id)
    {
        $package = TourPackage::with("destination")->where("id", $id)->where("show_on_site", true)->first();
        if (!$package) {
            return $this->not_found("Package not found.");
        }
        $reviews = Review::with("user")->where("tour_package_id", $package->id)->latest()->get();
        return view("package", ["package" => $package, "reviews" => $reviews]);
    }

    public function view_hotels(Request $request)
    {
        $items = Hotel::with("roomTypes")->where("show_on_site", true);

        if ($request->input("city")) {
            $items->where("city", $request->input("city"));
        }
        if ($request->input("stars")) {
            $items->where("stars", ">=", (int) $request->input("stars"));
        }
        if ($request->input("budget")) {
            $budget = (float) $request->input("budget");
            $items->whereHas("roomTypes", fn ($q) => $q->where("base_price", "<=", $budget));
        }

        return view("hotels", [
            "items" => $items->orderBy("name")->get(),
            "cities" => Hotel::where("show_on_site", true)->orderBy("city")->distinct()->pluck("city"),
        ]);
    }

    public function view_hotel(Request $request, $id)
    {
        $hotel = Hotel::with("roomTypes.rooms")->where("id", $id)->where("show_on_site", true)->first();
        if (!$hotel) {
            return $this->not_found("Hotel not found.");
        }

        // - when dates are given, show how many rooms of each type are free
        $check_in = $request->input("check_in");
        $check_out = $request->input("check_out");
        $guests = $request->input("guests", 1);
        $error = null;
        $free = [];
        if ($check_in && $check_out) {
            $error = $this->check_dates($check_in, $check_out, $guests);
            if (!$error) {
                foreach ($hotel->roomTypes as $room_type) {
                    $free[$room_type->id] = $this->free_rooms($room_type, $check_in, $check_out)->count();
                }
            }
        }

        $reviews = Review::with("user")->where("hotel_id", $hotel->id)->latest()->get();

        return view("hotel", [
            "hotel" => $hotel,
            "reviews" => $reviews,
            "free" => $free,
            "error" => $error,
            "check_in" => $check_in,
            "check_out" => $check_out,
            "guests" => $guests,
        ]);
    }

    public function search_rooms(Request $request)
    {
        $where = trim((string) $request->input("where"));
        $check_in = $request->input("check_in");
        $check_out = $request->input("check_out");
        $guests = (int) $request->input("guests", 1);

        $results = [];
        $error = null;
        if ($check_in || $check_out) {
            $error = $this->check_dates($check_in, $check_out, $guests);
        }

        if ($check_in && $check_out && !$error) {
            $room_types = RoomType::with("hotel", "rooms")
                ->where("capacity", ">=", $guests)
                ->whereHas("hotel", function ($q) use ($where) {
                    $q->where("show_on_site", true);
                    if ($where) {
                        $q->where(function ($q2) use ($where) {
                            $q2->where("city", "like", "%{$where}%")
                                ->orWhere("name", "like", "%{$where}%")
                                ->orWhereHas("destination", fn ($q3) => $q3->where("name", "like", "%{$where}%"));
                        });
                    }
                })
                ->orderBy("base_price")
                ->get();

            $nights = Carbon::parse($check_in)->diffInDays(Carbon::parse($check_out));
            foreach ($room_types as $room_type) {
                $free_count = $this->free_rooms($room_type, $check_in, $check_out)->count();
                if ($free_count > 0) {
                    $results[] = [
                        "room_type" => $room_type,
                        "free" => $free_count,
                        "total" => $room_type->base_price * $nights,
                        "nights" => $nights,
                    ];
                }
            }
        }

        return view("search_rooms", [
            "results" => $results,
            "error" => $error,
            "where" => $where,
            "check_in" => $check_in,
            "check_out" => $check_out,
            "guests" => $guests,
        ]);
    }

    // - live server only: runs migrate + seed:demo once after a deploy (needs SETUP_TOKEN in env)
    public function setup_demo(Request $request, $token)
    {
        $secret = (string) env("SETUP_TOKEN", "");
        if ($secret == "" || !hash_equals($secret, (string) $token)) {
            return $this->not_found();
        }
        Artisan::call("migrate", ["--force" => true]);
        $output = Artisan::output();
        Artisan::call("seed:demo");
        return response($output . Artisan::output(), 200)->header("Content-Type", "text/plain");
    }

    public function dashboard(Request $request)
    {
        if (!Auth::check() || !$request->userEx) {
            return redirect()->route("index");
        }
        return $this->send_to_dashboard($request->userEx);
    }

    private function send_to_dashboard($userEx)
    {
        if ($userEx->user_type == "owner") {
            return redirect()->route("owner_dashboard");
        } elseif ($userEx->user_type == "staff") {
            return redirect()->route("staff_dashboard");
        }
        return redirect()->route("customer_dashboard");
    }

    public function flogin(Request $request)
    {
        if ($request->isMethod("post")) {
            if ($request->input("email") && $request->input("password")) {
                $user = User::where("email", $request->input("email"))->first();
                if (!$user) {
                    return back()->with("error", "User with email does not exists");
                }

                if (Auth::attempt(["email" => $user->email, "password" => $request->input("password")])) {
                    $request->session()->regenerate();
                    // return to corresponding dashboard
                    $userEx = UserEx::where("user_id", $user->id)->first();
                    if ($userEx) {
                        return $this->send_to_dashboard($userEx);
                    }
                    return redirect()->route("index");
                }
                return back()->with("error", "Invalid password");
            }
            return back()->with("error", "Please fill in all fields");
        }

        return view("auth.login");
    }

    public function fregister(Request $request)
    {
        if ($request->isMethod("post")) {
            // check for password match
            if (!$request->input("password") || $request->input("password") != $request->input("password2")) {
                return back()->withInput()->with("error", "Passwords do not match");
            }

            if (!filter_var($request->input("email"), FILTER_VALIDATE_EMAIL)) {
                return back()->withInput()->with("error", "Please enter a valid email");
            }

            // check for user's existence with email
            if (User::where("email", $request->input("email"))->exists()) {
                return back()->withInput()->with("error", "User with email already exists");
            }

            // check for userEx's existence with phone
            if ($request->input("phone") && UserEx::where("phone", $request->input("phone"))->exists()) {
                return back()->withInput()->with("error", "User with phone already exists");
            }

            // - demo: role is picked on the form, but owner is never offered here
            $user_type = $request->input("user_type");
            if (!in_array($user_type, ["staff", "customer"])) {
                $user_type = "customer";
            }

            // Register User
            $user = User::create([
                "name" => trim($request->input("first_name") . " " . $request->input("last_name")),
                "email" => $request->input("email"),
                "password" => Hash::make($request->input("password")),
            ]);

            $userEx = UserEx::create([
                "user_id" => $user->id,
                "phone" => $request->input("phone"),
                "user_type" => $user_type,
            ]);

            Auth::login($user);
            $request->session()->regenerate();
            return $this->send_to_dashboard($userEx)->with("success", "Account created successfully");
        }

        return view("auth.register");
    }

    public function flogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("index");
    }

    // Agency Owner Area
    public function owner_dashboard(Request $request)
    {
        $bookings = Booking::whereNotIn("status", ["Cancelled"]);
        return view("owner.dashboard", [
            "bookings_count" => (clone $bookings)->count(),
            "revenue" => (clone $bookings)->sum("total"),
            "hotels_count" => Hotel::count(),
            "packages_count" => TourPackage::count(),
            "pending_count" => Booking::where("status", "Pending")->count(),
        ]);
    }

    // Hotel Staff Area
    public function staff_dashboard(Request $request)
    {
        $today = Carbon::today()->toDateString();
        return view("staff.dashboard", [
            "arrivals" => Booking::with("user", "room.roomType.hotel")
                ->where("booking_type", "Hotel")
                ->whereDate("check_in", $today)
                ->whereIn("status", ["Pending", "Confirmed"])
                ->get(),
            "departures" => Booking::with("user", "room.roomType.hotel")
                ->where("booking_type", "Hotel")
                ->whereDate("check_out", $today)
                ->where("status", "CheckedIn")
                ->get(),
            "pending_count" => Booking::where("booking_type", "Hotel")->where("status", "Pending")->count(),
            "occupied_count" => Room::where("status", "Occupied")->count(),
            "rooms_count" => Room::count(),
        ]);
    }

    // Customer Area
    public function customer_dashboard(Request $request)
    {
        $bookings = Booking::where("user_id", Auth::id());
        return view("customer.dashboard", [
            "bookings_count" => (clone $bookings)->count(),
            "upcoming_count" => (clone $bookings)
                ->whereIn("status", ["Pending", "Confirmed"])
                ->whereDate("check_in", ">=", Carbon::today())
                ->count(),
            "spent" => (clone $bookings)->whereNotIn("status", ["Cancelled"])->sum("total"),
        ]);
    }
}
