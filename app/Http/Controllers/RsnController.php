<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Favorite;
use App\Models\Hotel;
use App\Models\Inquiry;
use App\Models\Review;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\TourPackage;
use App\Models\User;
use App\Models\UserEx;
use App\Models\WorkTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Support\Photo;

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

    // - the team member of one role who has the fewest open tasks
    private function least_busy($user_type)
    {
        $ids = UserEx::where("user_type", $user_type)->pluck("user_id");
        if ($ids->isEmpty()) {
            return null;
        }
        $open = WorkTask::whereIn("assigned_to", $ids)->where("status", "Open")
            ->select("assigned_to", DB::raw("count(*) as n"))->groupBy("assigned_to")->pluck("n", "assigned_to");
        return User::whereIn("id", $ids)->orderBy("id")->get()->sortBy(fn ($u) => $open[$u->id] ?? 0)->first();
    }

    // - creates a task and gives it to the least busy member of the role (or to a chosen person)
    private function give_task($user_type, $kind, $title, $detail = null, $booking = null, $due = null, $assignee = null, $creator = null)
    {
        $assignee = $assignee ?: $this->least_busy($user_type);
        return WorkTask::create([
            "title" => $title,
            "detail" => $detail,
            "kind" => $kind,
            "booking_id" => $booking ? $booking->id : null,
            "assigned_to" => $assignee ? $assignee->id : null,
            "created_by" => $creator,
            "due_date" => $due,
        ]);
    }

    // - closes the open tasks of a booking (cancelled or rejected)
    private function close_booking_tasks($booking)
    {
        WorkTask::where("booking_id", $booking->id)->where("status", "Open")->update(["status" => "Done", "done_at" => now()]);
    }

    // Public Area
    public function index(Request $request)
    {
        $destinations = Destination::where("show_on_site", true)->get();
        $slides = array_map(fn ($h) => [$h[0], $h[1], Photo::url($h[1], 2000)], Photo::HERO);

        return view("index", [
            "packages" => TourPackage::with("destination")->withAvg("reviews", "rating")->withCount("reviews")->where("show_on_site", true)->take(6)->get(),
            "hotels" => Hotel::with("roomTypes")->withAvg("reviews", "rating")->withCount("reviews")->where("show_on_site", true)->take(6)->get(),
            "destinations" => $destinations,
            "slides" => $slides,
            "typed" => ["Bali", "the Maldives", "Santorini", "Cox's Bazar", "the Swiss Alps"],
            "quotes" => Review::with("user", "hotel", "tourPackage")->where("rating", ">=", 4)->where("comment", "!=", "")->latest()->take(3)->get(),
            "stats" => [
                "bookings" => Booking::count(),
                "hotels" => Hotel::count(),
                "packages" => TourPackage::count(),
                "reviews" => Review::count(),
            ],
        ]);
    }

    public function project_profile(Request $request)
    {
        return view("project_profile", ["title" => "Project Profile - TravelDemo"]);
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
        $items = TourPackage::with("destination")->withAvg("reviews", "rating")->withCount("reviews")->where("show_on_site", true);

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
            "items" => $this->sort_items($items, $request, "price"),
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
        $reviews = Review::with("user")->where("tour_package_id", $package->id)->latest()->take(10)->get();
        return view("package", ["package" => $package, "reviews" => $reviews]);
    }

    public function view_hotels(Request $request)
    {
        $items = Hotel::with("roomTypes")->withAvg("reviews", "rating")->withCount("reviews")->where("show_on_site", true);

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
            "items" => $this->sort_items($items, $request, "name"),
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
        $check_in = $request->input("check_in", Carbon::today()->addDays(7)->toDateString());
        $check_out = $request->input("check_out", Carbon::today()->addDays(9)->toDateString());
        $guests = $request->input("guests", 2);
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

        $reviews = Review::with("user")->where("hotel_id", $hotel->id)->latest()->take(10)->get();

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
        } elseif ($userEx->user_type == "agent") {
            return redirect()->route("agent_dashboard");
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

        // - bookings per month for the last 6 months (grouped here so it works on any database)
        $from = Carbon::today()->startOfMonth()->subMonths(5);
        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $months[$from->copy()->addMonths($i)->format("Y-m")] = 0;
        }
        foreach (Booking::where("check_in", ">=", $from)->whereNotIn("status", ["Cancelled"])->get(["check_in"]) as $b) {
            $key = $b->check_in->format("Y-m");
            if (isset($months[$key])) {
                $months[$key]++;
            }
        }

        return view("owner.dashboard", [
            "bookings_count" => (clone $bookings)->count(),
            "revenue" => (clone $bookings)->sum("total"),
            "hotels_count" => Hotel::count(),
            "packages_count" => TourPackage::count(),
            "pending_count" => Booking::where("status", "Pending")->count(),
            "pending" => Booking::with("user", "room.roomType.hotel", "tourPackage")->where("status", "Pending")->orderBy("check_in")->take(6)->get(),
            "month_labels" => array_map(fn ($m) => Carbon::parse($m . "-01")->format("M"), array_keys($months)),
            "month_values" => array_values($months),
            "status_counts" => Booking::select("status", DB::raw("count(*) as n"))->groupBy("status")->pluck("n", "status"),
            "team" => $this->team_workload(),
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
            "my_tasks" => $this->my_open_tasks(),
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

    // - sort and return a list query (sort=price_low|price_high|rating|name)
    private function sort_items($query, Request $request, $default)
    {
        $sort = $request->input("sort");
        if ($sort == "rating") {
            return $query->orderByDesc("reviews_avg_rating")->get();
        }
        $price = $default == "price" ? "price" : null;
        if ($sort == "price_high" && $price) {
            return $query->orderByDesc("price")->get();
        }
        return $query->orderBy($default)->get();
    }

    // Favorites (any logged in user)
    public function toggle_favorite(Request $request)
    {
        $type = $request->input("type");
        $id = (int) $request->input("id");
        if (!in_array($type, Favorite::TYPES) || $id < 1) {
            return back()->with("error", "Nothing to save");
        }
        $row = Favorite::where(["user_id" => Auth::id(), "item_type" => $type, "item_id" => $id])->first();
        if ($row) {
            $row->delete();
            return back()->with("success", "Removed from your favorites");
        }
        Favorite::create(["user_id" => Auth::id(), "item_type" => $type, "item_id" => $id]);
        return back()->with("success", "Saved to your favorites");
    }

    public function customer_favorites(Request $request)
    {
        $rows = Favorite::where("user_id", Auth::id())->get();
        $ids = fn ($type) => $rows->where("item_type", $type)->pluck("item_id");
        return view("customer.favorites", [
            "destinations" => Destination::whereIn("id", $ids("destination"))->get(),
            "packages" => TourPackage::with("destination")->withAvg("reviews", "rating")->withCount("reviews")->whereIn("id", $ids("package"))->get(),
            "hotels" => Hotel::with("roomTypes")->withAvg("reviews", "rating")->withCount("reviews")->whereIn("id", $ids("hotel"))->get(),
        ]);
    }

    // Customer cart: items live in the session until checkout
    private function cart_lines(Request $request)
    {
        $lines = [];
        foreach ($request->session()->get("cart", []) as $key => $item) {
            if ($item["type"] == "hotel") {
                $room_type = RoomType::with("hotel", "rooms")->find($item["room_type_id"]);
                if (!$room_type) {
                    continue;
                }
                $nights = max(1, Carbon::parse($item["check_in"])->diffInDays(Carbon::parse($item["check_out"])));
                $lines[] = $item + [
                    "key" => $key,
                    "title" => $room_type->hotel->name . " - " . $room_type->name,
                    "picture" => $room_type->picture_url,
                    "detail" => Carbon::parse($item["check_in"])->format("d M") . " - " . Carbon::parse($item["check_out"])->format("d M Y") . " · {$nights} night(s) · {$item['guests']} guest(s)",
                    "total" => $room_type->base_price * $nights,
                    "room_type" => $room_type,
                ];
            } else {
                $package = TourPackage::with("destination")->find($item["package_id"]);
                if (!$package) {
                    continue;
                }
                $lines[] = $item + [
                    "key" => $key,
                    "title" => $package->name,
                    "picture" => $package->picture_url,
                    "detail" => Carbon::parse($item["check_in"])->format("d M Y") . " · {$package->days} days · {$item['guests']} traveler(s)",
                    "total" => $package->price * $item["guests"],
                    "package" => $package,
                ];
            }
        }
        return $lines;
    }

    public function customer_cart(Request $request)
    {
        $lines = $this->cart_lines($request);
        return view("customer.cart", ["lines" => $lines, "total" => array_sum(array_column($lines, "total"))]);
    }

    public function customer_add_to_cart(Request $request)
    {
        $guests = (int) $request->input("guests", 1);
        if ($request->input("type") == "package") {
            $package = TourPackage::where("id", $request->input("package_id"))->where("show_on_site", true)->first();
            if (!$package) {
                return $this->not_found("Package not found.");
            }
            $date = $request->input("travel_date");
            $error = $this->check_dates($date, Carbon::parse($date ?: "today")->addDays($package->days), $guests);
            if ($error) {
                return back()->with("error", $error);
            }
            $item = ["type" => "package", "package_id" => $package->id, "check_in" => Carbon::parse($date)->toDateString(), "check_out" => Carbon::parse($date)->addDays($package->days)->toDateString(), "guests" => $guests];
        } else {
            $room_type = RoomType::with("rooms")->find($request->input("room_type_id"));
            if (!$room_type) {
                return $this->not_found("Room type not found.");
            }
            $error = $this->check_dates($request->input("check_in"), $request->input("check_out"), $guests);
            if (!$error && $room_type->capacity < $guests) {
                $error = "This room takes up to {$room_type->capacity} guests";
            }
            if (!$error && $this->free_rooms($room_type, $request->input("check_in"), $request->input("check_out"))->isEmpty()) {
                $error = "Sorry, no free room of this type for those dates";
            }
            if ($error) {
                return back()->with("error", $error);
            }
            $item = ["type" => "hotel", "room_type_id" => $room_type->id, "check_in" => Carbon::parse($request->input("check_in"))->toDateString(), "check_out" => Carbon::parse($request->input("check_out"))->toDateString(), "guests" => $guests];
        }
        $cart = $request->session()->get("cart", []);
        $cart[uniqid()] = $item;
        $request->session()->put("cart", $cart);
        return redirect()->route("customer_cart")->with("success", "Added to your cart");
    }

    public function customer_remove_from_cart(Request $request, $key)
    {
        $cart = $request->session()->get("cart", []);
        unset($cart[$key]);
        $request->session()->put("cart", $cart);
        return back()->with("success", "Removed from your cart");
    }

    // - checkout: one Pending booking per cart line; the agency owner approves them next
    public function customer_checkout(Request $request)
    {
        $lines = $this->cart_lines($request);
        if (!$lines) {
            return back()->with("error", "Your cart is empty");
        }
        $method = $request->input("payment_method");
        if (!in_array($method, array_column(Booking::PAYMENT_METHODS, 0))) {
            return back()->with("error", "Please choose a payment method");
        }
        $paid = $method != "PayAtHotel";

        // - re-check every room first so nothing is half booked
        $rooms = [];
        $taken = [];
        foreach ($lines as $line) {
            if ($line["type"] == "hotel") {
                // - two cart lines for the same dates must get two different rooms
                $free = $this->free_rooms($line["room_type"], $line["check_in"], $line["check_out"])
                    ->reject(fn ($room) => in_array($room->id, $taken))
                    ->first();
                if (!$free) {
                    return redirect()->route("customer_cart")->with("error", "Sorry, " . $line["title"] . " was just taken for those dates. Please remove it.");
                }
                $rooms[$line["key"]] = $free;
                $taken[] = $free->id;
            }
        }

        $ids = [];
        DB::transaction(function () use ($lines, $rooms, $method, $paid, &$ids) {
            foreach ($lines as $line) {
                $booking = Booking::create([
                    "user_id" => Auth::id(),
                    "booking_type" => $line["type"] == "hotel" ? "Hotel" : "Package",
                    "room_id" => $line["type"] == "hotel" ? $rooms[$line["key"]]->id : null,
                    "tour_package_id" => $line["type"] == "package" ? $line["package_id"] : null,
                    "check_in" => $line["check_in"],
                    "check_out" => $line["check_out"],
                    "guests" => $line["guests"],
                    "total" => $line["total"],
                    "status" => "Pending",
                    "payment_method" => $method,
                    "payment_status" => $paid ? "PAID" : "UNPAID",
                ]);
                $ids[] = $booking->id;
                // - the agents call every new customer (the task goes to the least busy agent)
                $this->give_task("agent", "follow_up", "Call " . Auth::user()->name . " about booking #{$booking->id}", $booking->title() . " · " . $booking->check_in->format("d M") . " - " . $booking->check_out->format("d M Y"), $booking, Carbon::today()->addDay());
            }
        });
        $request->session()->forget("cart");
        return redirect()->route("customer_bookings")->with("success", count($ids) . " booking request(s) sent. The agency owner will approve them soon.");
    }

    public function customer_bookings(Request $request)
    {
        return view("customer.bookings", [
            "bookings" => Booking::with("room.roomType.hotel", "tourPackage", "review")->where("user_id", Auth::id())->latest("id")->get(),
        ]);
    }

    private function own_booking($id)
    {
        return Booking::with("room.roomType.hotel", "tourPackage.destination", "user")->where("id", $id)->where("user_id", Auth::id())->first();
    }

    public function customer_cancel_booking(Request $request, $id)
    {
        $booking = $this->own_booking($id);
        if (!$booking) {
            return $this->not_found("Booking not found.");
        }
        if (!in_array($booking->status, ["Pending", "Confirmed"])) {
            return back()->with("error", "This booking can no longer be cancelled");
        }
        $booking->status = "Cancelled";
        if ($booking->payment_status == "PAID") {
            $booking->payment_status = "UNPAID";
        }
        $booking->save();
        $this->close_booking_tasks($booking);
        return back()->with("success", "Booking cancelled" . ($booking->payment_method != "PayAtHotel" ? ". Your payment will be refunded (demo)." : ""));
    }

    public function customer_invoice(Request $request, $id)
    {
        $booking = $this->own_booking($id);
        if (!$booking) {
            return $this->not_found("Booking not found.");
        }
        return view("customer.invoice", ["booking" => $booking]);
    }

    public function customer_review(Request $request, $id)
    {
        $booking = $this->own_booking($id);
        if (!$booking) {
            return $this->not_found("Booking not found.");
        }
        if ($booking->status != "Completed" || $booking->review) {
            return back()->with("error", "You can review a completed trip once");
        }
        $rating = (int) $request->input("rating");
        if ($rating < 1 || $rating > 5) {
            return back()->with("error", "Please pick 1 to 5 stars");
        }
        Review::create([
            "user_id" => Auth::id(),
            "booking_id" => $booking->id,
            "hotel_id" => $booking->booking_type == "Hotel" ? $booking->room->roomType->hotel_id : null,
            "tour_package_id" => $booking->booking_type == "Package" ? $booking->tour_package_id : null,
            "rating" => $rating,
            "comment" => trim((string) $request->input("comment")),
        ]);
        return back()->with("success", "Thank you for your review");
    }

    // Agency Owner: approve or reject booking requests
    public function owner_bookings(Request $request)
    {
        $status = $request->input("status");
        $query = Booking::with("user", "room.roomType.hotel", "tourPackage")->latest("id");
        if ($status) {
            $query->where("status", $status);
        }
        return view("owner.bookings", ["bookings" => $query->paginate(20)->withQueryString(), "status" => $status]);
    }

    public function owner_approve_booking(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking || $booking->status != "Pending") {
            return back()->with("error", "This booking is not waiting for approval");
        }
        $booking->status = "Confirmed";
        $booking->save();
        // - hotel bookings: staff prepare the room; package bookings: an agent sends the trip details
        if ($booking->booking_type == "Hotel") {
            $this->give_task("staff", "arrival_prep", "Prepare room {$booking->room->number} for " . $booking->user->name, $booking->title() . " · arrives " . $booking->check_in->format("d M Y"), $booking, $booking->check_in);
        } else {
            $this->give_task("agent", "follow_up", "Send trip details to " . $booking->user->name, $booking->title() . " · starts " . $booking->check_in->format("d M Y"), $booking, $booking->check_in->copy()->subDays(2));
        }
        return back()->with("success", "Booking #{$booking->id} approved and a task was given to the team.");
    }

    public function owner_reject_booking(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking || $booking->status != "Pending") {
            return back()->with("error", "This booking is not waiting for approval");
        }
        $booking->status = "Cancelled";
        if ($booking->payment_status == "PAID") {
            $booking->payment_status = "UNPAID";
        }
        $booking->save();
        $this->close_booking_tasks($booking);
        return back()->with("success", "Booking #{$booking->id} rejected" . ($booking->payment_method != "PayAtHotel" ? " and the payment refunded (demo)" : ""));
    }

    // - show or hide hotels and packages on the public site
    public function owner_catalog(Request $request)
    {
        return view("owner.catalog", [
            "hotels" => Hotel::orderBy("name")->get(),
            "packages" => TourPackage::with("destination")->orderBy("name")->get(),
            "destinations" => Destination::orderBy("name")->get(),
        ]);
    }

    public function owner_toggle_item(Request $request)
    {
        $model = ["hotel" => Hotel::class, "package" => TourPackage::class, "destination" => Destination::class][$request->input("type")] ?? null;
        $item = $model ? $model::find($request->input("id")) : null;
        if (!$item) {
            return back()->with("error", "Item not found");
        }
        $item->show_on_site = !$item->show_on_site;
        $item->save();
        return back()->with("success", $item->name . ($item->show_on_site ? " is now shown on the site" : " is now hidden from the site"));
    }

    // Hotel Staff: arrivals, check-in, check-out and the room board
    public function staff_bookings(Request $request)
    {
        $with = ["user", "room.roomType.hotel"];
        return view("staff.bookings", [
            "arrivals" => Booking::with($with)->where("booking_type", "Hotel")->where("status", "Confirmed")->orderBy("check_in")->take(40)->get(),
            "in_house" => Booking::with($with)->where("booking_type", "Hotel")->where("status", "CheckedIn")->orderBy("check_out")->get(),
            "waiting" => Booking::where("booking_type", "Hotel")->where("status", "Pending")->count(),
        ]);
    }

    public function staff_check_in(Request $request, $id)
    {
        $booking = Booking::with("room")->where("booking_type", "Hotel")->find($id);
        if (!$booking || $booking->status != "Confirmed") {
            return back()->with("error", "Only approved bookings can be checked in");
        }
        $booking->status = "CheckedIn";
        $booking->save();
        $booking->room->update(["status" => "Occupied"]);
        return back()->with("success", "{$booking->user->name} checked in to room {$booking->room->number}");
    }

    public function staff_check_out(Request $request, $id)
    {
        $booking = Booking::with("room")->where("booking_type", "Hotel")->find($id);
        if (!$booking || $booking->status != "CheckedIn") {
            return back()->with("error", "This guest is not checked in");
        }
        $booking->status = "Completed";
        if ($booking->payment_status == "UNPAID") {
            $booking->payment_status = "PAID";
        }
        $booking->save();
        $booking->room->update(["status" => "Cleaning"]);
        $this->give_task("staff", "housekeeping", "Clean room {$booking->room->number}", $booking->title() . " · guest left today", $booking, Carbon::today(), $request->userEx->user_type == "staff" ? Auth::user() : null);
        return back()->with("success", "Checked out. Room {$booking->room->number} is marked for cleaning.");
    }

    public function staff_rooms(Request $request)
    {
        return view("staff.rooms", ["hotels" => Hotel::with("roomTypes.rooms")->orderBy("name")->get()]);
    }

    public function staff_set_room(Request $request, $id)
    {
        $room = Room::find($id);
        $status = $request->input("status");
        if (!$room || !in_array($status, array_column(Room::STATUSES, 0))) {
            return back()->with("error", "Could not update the room");
        }
        $room->status = $status;
        $room->save();
        // - a clean room finishes its housekeeping task
        if ($status == "Available") {
            $ids = Booking::where("room_id", $room->id)->pluck("id");
            WorkTask::where("kind", "housekeeping")->where("status", "Open")->whereIn("booking_id", $ids)->update(["status" => "Done", "done_at" => now()]);
        }
        return back()->with("success", "Room {$room->number} is now {$status}");
    }

    // Team: tasks, workload and questions
    private function my_open_tasks()
    {
        return WorkTask::with("booking")->where("assigned_to", Auth::id())->where("status", "Open")->orderByRaw("due_date is null")->orderBy("due_date")->orderBy("id")->get();
    }

    // - one row per agent and staff member: open and done tasks
    private function team_workload()
    {
        $members = UserEx::with("user")->whereIn("user_type", ["agent", "staff"])->get();
        $rows = [];
        foreach ($members as $m) {
            $rows[] = [
                "user" => $m->user,
                "type" => $m->user_type,
                "open" => WorkTask::where("assigned_to", $m->user_id)->where("status", "Open")->count(),
                "done" => WorkTask::where("assigned_to", $m->user_id)->where("status", "Done")->count(),
            ];
        }
        return $rows;
    }

    // - agent, staff or owner finishes a task (only their own, the owner can finish any)
    public function task_done(Request $request, $id)
    {
        $task = WorkTask::find($id);
        if (!$task || $task->status != "Open") {
            return back()->with("error", "This task is already finished");
        }
        if ($request->userEx->user_type != "owner" && $task->assigned_to != Auth::id()) {
            return back()->with("error", "This task belongs to someone else");
        }
        $task->update(["status" => "Done", "done_at" => now()]);
        return back()->with("success", "Task done: " . $task->title);
    }

    public function owner_team(Request $request)
    {
        $status = $request->input("status", "Open");
        $query = WorkTask::with("assignee", "booking")->latest("id");
        if (in_array($status, ["Open", "Done"])) {
            $query->where("status", $status);
        }
        return view("owner.team", [
            "team" => $this->team_workload(),
            "tasks" => $query->take(60)->get(),
            "status" => $status,
            "members" => User::whereIn("id", UserEx::whereIn("user_type", ["agent", "staff"])->pluck("user_id"))->orderBy("name")->get(),
        ]);
    }

    public function owner_create_task(Request $request)
    {
        $title = trim((string) $request->input("title"));
        $assignee = User::find($request->input("assigned_to"));
        $ex = $assignee ? UserEx::where("user_id", $assignee->id)->first() : null;
        if ($title == "" || !$ex || !in_array($ex->user_type, ["agent", "staff"])) {
            return back()->with("error", "Write the task and choose an agent or a staff member");
        }
        $due = $request->input("due_date") ?: null;
        $this->give_task($ex->user_type, "general", mb_substr($title, 0, 160), trim((string) $request->input("detail")) ?: null, null, $due, $assignee, Auth::id());
        return back()->with("success", "Task given to " . $assignee->name);
    }

    public function owner_reassign_task(Request $request, $id)
    {
        $task = WorkTask::find($id);
        $assignee = User::find($request->input("assigned_to"));
        $ex = $assignee ? UserEx::where("user_id", $assignee->id)->first() : null;
        if (!$task || !$ex || !in_array($ex->user_type, ["agent", "staff"])) {
            return back()->with("error", "Could not move the task");
        }
        $task->update(["assigned_to" => $assignee->id]);
        return back()->with("success", "Task moved to " . $assignee->name);
    }

    // Travel Agent Area
    public function agent_dashboard(Request $request)
    {
        $mine = Auth::id();
        return view("agent.dashboard", [
            "my_tasks" => $this->my_open_tasks(),
            "done_count" => WorkTask::where("assigned_to", $mine)->where("status", "Done")->count(),
            "new_questions" => Inquiry::where("status", "New")->where(fn ($q) => $q->whereNull("agent_id")->orWhere("agent_id", $mine))->count(),
            "waiting_owner" => Booking::where("status", "Pending")->count(),
            "latest_questions" => Inquiry::with("user")->where("status", "New")->where(fn ($q) => $q->whereNull("agent_id")->orWhere("agent_id", $mine))->latest("id")->take(4)->get(),
        ]);
    }

    public function agent_bookings(Request $request)
    {
        return view("agent.bookings", [
            "bookings" => Booking::with("user.userEx", "room.roomType.hotel", "tourPackage")->whereIn("status", ["Pending", "Confirmed"])->orderBy("check_in")->take(40)->get(),
        ]);
    }

    public function agent_inquiries(Request $request)
    {
        $mine = Auth::id();
        return view("agent.inquiries", [
            "new" => Inquiry::with("user")->where("status", "New")->where(fn ($q) => $q->whereNull("agent_id")->orWhere("agent_id", $mine))->orderBy("id")->get(),
            "answered" => Inquiry::with("user")->where("status", "Answered")->where("agent_id", $mine)->latest("id")->take(15)->get(),
        ]);
    }

    public function agent_reply_inquiry(Request $request, $id)
    {
        $inquiry = Inquiry::find($id);
        $reply = trim((string) $request->input("reply"));
        if (!$inquiry || $inquiry->status != "New" || $reply == "") {
            return back()->with("error", "Write a reply first");
        }
        $inquiry->update(["reply" => mb_substr($reply, 0, 2000), "agent_id" => Auth::id(), "status" => "Answered"]);
        return back()->with("success", "Reply sent to " . $inquiry->user->name);
    }

    // Hotel Staff: tasks
    public function staff_tasks(Request $request)
    {
        return view("staff.tasks", [
            "my_tasks" => $this->my_open_tasks(),
            "done" => WorkTask::where("assigned_to", Auth::id())->where("status", "Done")->latest("done_at")->take(10)->get(),
        ]);
    }

    // Customer: ask an agent
    public function customer_inquiries(Request $request)
    {
        if ($request->isMethod("post")) {
            $subject = trim((string) $request->input("subject"));
            $message = trim((string) $request->input("message"));
            if ($subject == "" || $message == "") {
                return back()->withInput()->with("error", "Please write a subject and your question");
            }
            $agent = $this->least_busy("agent");
            Inquiry::create(["user_id" => Auth::id(), "agent_id" => $agent ? $agent->id : null, "subject" => mb_substr($subject, 0, 160), "message" => mb_substr($message, 0, 2000)]);
            return redirect()->route("customer_inquiries")->with("success", "Your question was sent to our travel agents.");
        }
        return view("customer.inquiries", ["inquiries" => Inquiry::with("agent")->where("user_id", Auth::id())->latest("id")->get()]);
    }
}
