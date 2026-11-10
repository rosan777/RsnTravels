<?php

namespace App\Console\Commands;

use App\Support\Photo;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Inquiry;
use App\Models\Review;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Season;
use App\Models\TourPackage;
use App\Models\User;
use App\Models\UserEx;
use App\Models\WorkTask;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// php artisan seed:demo  (safe to run again: nothing is duplicated)
class SeedDemo extends Command
{
    protected $signature = "seed:demo";

    protected $description = "Create demo users, destinations, packages, hotels, rooms and bookings";

    // email, user type, name, phone; the password is the same as the email (matches the login page buttons)
    const DEMO_USERS = [
        ["owner@example.com", "owner", "Agency Owner", "01700000001"],
        ["agent@example.com", "agent", "Rina Das", "01700000004"],
        ["agent2@example.com", "agent", "Karim Uddin", "01700000005"],
        ["staff@example.com", "staff", "Hasan Front Desk", "01700000002"],
        ["staff2@example.com", "staff", "Mim Housekeeping", "01700000006"],
        ["customer@example.com", "customer", "Demo Customer", "01700000003"],
    ];

    // extra guests so the dashboard and forecast have enough bookings
    const DEMO_GUESTS = ["Nadia Rahman", "Arif Hossain", "Sara Khan", "Tanvir Ahmed", "Mitu Akter", "Rafi Islam"];

    // name, country, best time, description
    const DESTINATIONS = [
        ["Bali", "Indonesia", "April - October", "Rice terraces, temples, surf beaches and calm villas."],
        ["Swiss Alps", "Switzerland", "June - September, December - March", "Snow peaks, lakes and mountain trains around Interlaken."],
        ["Cliffs of Moher", "Ireland", "May - September", "Green hills and tall sea cliffs on Ireland's wild west coast."],
        ["Everest Region", "Nepal", "March - May, October - November", "Himalayan trails, Sherpa villages and the road to Everest Base Camp."],
        ["Maldives", "Maldives", "November - April", "Clear lagoons, coral reefs and water villas."],
        ["Santorini", "Greece", "May - October", "White villages on a volcanic caldera with famous sunsets."],
        ["Banff", "Canada", "June - September", "Turquoise lakes and the Rocky Mountains."],
        ["Kyoto", "Japan", "March - May, October - November", "Temples, bamboo groves and old tea streets in Japan's former capital."],
        ["Iceland", "Iceland", "June - August, September - March", "Waterfalls, glaciers, black sand beaches and the northern lights."],
        ["Machu Picchu", "Peru", "May - September", "The Inca citadel in the clouds, with the Sacred Valley and Cusco."],
        ["Dubai", "United Arab Emirates", "November - March", "A glass skyline, desert dunes, souks and long sandy beaches."],
    ];

    // destination, name, days, price, interest, description, itinerary, includes, excludes
    const PACKAGES = [
        ["Bali", "Bali Island Escape", 5, 68000, "calm", "Ubud rice terraces, temples and a beach day.", ["Arrive in Denpasar, transfer to Ubud", "Tegallalang rice terraces and monkey forest", "Temple tour and traditional dance", "Beach day at Nusa Dua", "Shopping and fly home"], ["Hotel (4 nights)", "Breakfast", "Airport transfer", "Day tours with guide"], ["Air tickets", "Lunch and dinner", "Visa fee"]],
        ["Bali", "Bali Adventure Week", 7, 92000, "adventure", "Volcano sunrise trek, rafting and snorkelling.", ["Arrive and rest", "Mount Batur sunrise trek", "White water rafting", "Snorkelling at Nusa Penida", "Waterfall hike", "Free day", "Fly home"], ["Hotel (6 nights)", "Breakfast", "All activities", "Guide"], ["Air tickets", "Travel insurance"]],
        ["Swiss Alps", "Swiss Alps Classic", 6, 185000, "nature", "Interlaken, Jungfraujoch and Lake Lucerne by train.", ["Arrive in Zurich, train to Interlaken", "Jungfraujoch - Top of Europe", "Grindelwald and lakes", "Lucerne old town", "Mount Titlis", "Fly home"], ["Hotel (5 nights)", "Breakfast", "Swiss travel pass"], ["Air tickets", "Visa fee", "Dinner"]],
        ["Cliffs of Moher", "Ireland Wild Atlantic", 5, 145000, "nature", "Cliffs of Moher, Galway and the green countryside.", ["Arrive in Dublin", "Drive to Galway", "Cliffs of Moher and the Burren", "Connemara hills", "Back to Dublin and fly home"], ["Hotel (4 nights)", "Breakfast", "Car with driver"], ["Air tickets", "Visa fee"]],
        ["Everest Region", "Everest Base Camp Trek", 12, 125000, "adventure", "The classic trek to Everest Base Camp with a local guide.", ["Kathmandu arrival", "Fly to Lukla, trek to Phakding", "Namche Bazaar", "Acclimatisation day", "Tengboche monastery", "Dingboche", "Rest day", "Lobuche", "Everest Base Camp", "Back to Pheriche", "Namche to Lukla", "Fly to Kathmandu"], ["Tea houses", "All meals on trek", "Guide and porter", "Permits"], ["Air tickets to Nepal", "Personal gear", "Tips"]],
        ["Everest Region", "Kathmandu and Nagarkot Short Trip", 4, 42000, "family", "Old temples of Kathmandu and a Himalayan sunrise.", ["Kathmandu Durbar Square", "Bhaktapur and Nagarkot", "Nagarkot sunrise view", "Fly home"], ["Hotel (3 nights)", "Breakfast", "Private car"], ["Air tickets", "Lunch and dinner"]],
        ["Maldives", "Maldives Water Villa Stay", 4, 155000, "calm", "Three nights over the lagoon with sea plane transfer.", ["Arrive in Male, sea plane to resort", "Snorkelling and spa", "Sandbank picnic", "Fly home"], ["Water villa (3 nights)", "All meals", "Sea plane transfer"], ["Air tickets", "Diving courses"]],
        ["Santorini", "Santorini Sunset Holiday", 5, 135000, "calm", "Oia sunsets, black sand beaches and a caldera cruise.", ["Arrive in Fira", "Oia village and sunset", "Caldera boat cruise", "Beach day at Perissa", "Fly home"], ["Hotel (4 nights)", "Breakfast", "Boat cruise"], ["Air tickets", "Visa fee"]],
        ["Banff", "Banff and Lake Louise", 6, 175000, "nature", "Lake Louise, Moraine Lake and the Icefields Parkway.", ["Arrive in Calgary, drive to Banff", "Lake Louise and Moraine Lake", "Icefields Parkway", "Banff gondola", "Johnston Canyon walk", "Fly home"], ["Hotel (5 nights)", "Breakfast", "Car rental"], ["Air tickets", "Visa fee", "Park pass"]],
        ["Kyoto", "Kyoto Temples Weekend", 3, 78000, "calm", "Golden Pavilion, Fushimi Inari and the Arashiyama bamboo grove.", ["Arrive in Osaka, train to Kyoto", "Fushimi Inari, Kiyomizu-dera and Gion evening walk", "Kinkaku-ji, Arashiyama bamboo grove and fly home"], ["Ryokan (2 nights)", "Breakfast", "Rail pass", "Guide for one day"], ["Air tickets", "Visa fee", "Lunch and dinner"]],
        ["Kyoto", "Kyoto and Nara Culture Trip", 4, 98000, "family", "Temples, deer park and a tea ceremony.", ["Arrive and settle in Kyoto", "Nara deer park and Todai-ji temple", "Tea ceremony and Kiyomizu-dera", "Free morning and fly home"], ["Ryokan (3 nights)", "Breakfast", "Rail pass", "Tea ceremony"], ["Air tickets", "Visa fee"]],
        ["Iceland", "Iceland Golden Circle Escape", 3, 118000, "nature", "Gullfoss, Geysir, two waterfalls and the Blue Lagoon.", ["Arrive in Reykjavik, Golden Circle: Gullfoss and Geysir", "Seljalandsfoss, Skogafoss and the black sand beach", "Blue Lagoon and fly home"], ["Lodge (2 nights)", "Breakfast", "Car with local guide", "Blue Lagoon entry"], ["Air tickets", "Lunch and dinner"]],
        ["Machu Picchu", "Machu Picchu and Rainbow Mountain", 6, 165000, "adventure", "Cusco, the Sacred Valley, Machu Picchu by train and Rainbow Mountain.", ["Arrive in Cusco and rest", "Sacred Valley and Ollantaytambo", "Train to Aguas Calientes", "Machu Picchu guided tour", "Rainbow Mountain hike", "Fly home"], ["Hotel (5 nights)", "Breakfast", "Train tickets", "Machu Picchu entry and guide"], ["Air tickets", "Travel insurance", "Lunch and dinner"]],
        ["Dubai", "Dubai City and Desert Break", 4, 88000, "family", "Burj Khalifa, the old souks and an evening desert safari.", ["Arrive in Dubai, marina walk", "Burj Khalifa and Dubai Mall", "Old Dubai, souks and desert safari", "Beach morning and fly home"], ["Hotel (3 nights)", "Breakfast", "Desert safari", "Burj Khalifa tickets"], ["Air tickets", "Visa fee", "Lunch and dinner"]],
    ];

    // destination, hotel name, city, stars, amenities, description, room types (name, capacity, price, room count)
    const HOTELS = [
        ["Bali", "Ubud Garden Resort", "Ubud", 4, "Free Wi-Fi, Pool, Breakfast, Spa, Airport transfer", "Quiet villas between rice fields.", [["Deluxe Room", 2, 9500, 3], ["Garden Villa", 4, 16000, 2]]],
        ["Swiss Alps", "Alpine Lake Lodge", "Interlaken", 4, "Free Wi-Fi, Breakfast, Mountain view, Heating", "A wooden lodge with lake and mountain views.", [["Standard Double", 2, 21000, 3], ["Family Suite", 4, 34000, 2]]],
        ["Maldives", "Blue Lagoon Water Villas", "Maafushi", 5, "Free Wi-Fi, All meals, Private deck, Snorkelling, Spa", "Villas on stilts above a calm lagoon.", [["Beach Villa", 2, 38000, 2], ["Water Villa", 2, 52000, 3]]],
        ["Santorini", "Caldera View Suites", "Oia", 4, "Free Wi-Fi, Breakfast, Plunge pool, Sea view", "Cave style suites facing the sunset.", [["Cave Room", 2, 19000, 2], ["Caldera Suite", 3, 29000, 2]]],
        ["Kyoto", "Gion Garden Ryokan", "Kyoto", 4, "Free Wi-Fi, Breakfast, Onsen bath, Garden view, Tea room", "A quiet wooden ryokan a short walk from Gion.", [["Tatami Double", 2, 18000, 3], ["Family Room", 4, 26000, 2], ["Standard Single", 1, 11000, 2]]],
        ["Everest Region", "Himalayan View Hotel", "Kathmandu", 3, "Free Wi-Fi, Breakfast, Roof terrace, Trek desk", "A friendly base in Thamel for trekkers.", [["Twin Room", 2, 4800, 3], ["Deluxe Room", 3, 7200, 2]]],
        ["Banff", "Pine Peak Lodge", "Banff", 4, "Free Wi-Fi, Hot tub, Breakfast, Fireplace, Parking", "A log lodge at the foot of the Rockies.", [["Queen Room", 2, 24000, 2], ["Loft Suite", 4, 36000, 2]]],
        ["Iceland", "Northern Lights Lodge", "Reykjavik", 4, "Free Wi-Fi, Breakfast, Hot tub, Aurora wake-up call", "Warm cabins with a hot tub and wide night skies.", [["Aurora Cabin", 2, 24000, 3], ["Family Cabin", 4, 38000, 2]]],
        ["Machu Picchu", "Inca Trail Boutique Hotel", "Cusco", 4, "Free Wi-Fi, Breakfast, Mountain view, Tour desk", "A stone courtyard hotel in the old town of Cusco.", [["Andean Double", 2, 11000, 3], ["Family Suite", 4, 18000, 2]]],
        ["Dubai", "Marina Sky Hotel", "Dubai Marina", 5, "Free Wi-Fi, Pool, Breakfast, Gym, Skyline view", "A tower hotel above the marina with a rooftop pool.", [["Skyline Room", 2, 22000, 3], ["Executive Suite", 3, 36000, 2]]],
    ];

    // month => season
    const SEASONS = [1 => "Peak", 2 => "Peak", 3 => "Mid", 4 => "Off", 5 => "Off", 6 => "Off", 7 => "Mid", 8 => "Mid", 9 => "Off", 10 => "Mid", 11 => "Mid", 12 => "Peak"];

    public function handle()
    {
        mt_srand(2024);

        foreach (self::DEMO_USERS as [$email, $user_type, $name, $phone]) {
            $user = User::updateOrCreate(["email" => $email], ["name" => $name, "password" => Hash::make($email)]);
            UserEx::updateOrCreate(["user_id" => $user->id], ["user_type" => $user_type, "phone" => $phone]);
        }
        $guests = [User::where("email", "customer@example.com")->first()];
        foreach (self::DEMO_GUESTS as $i => $name) {
            $email = "guest" . ($i + 1) . "@example.com";
            $user = User::updateOrCreate(["email" => $email], ["name" => $name, "password" => Hash::make($email)]);
            UserEx::updateOrCreate(["user_id" => $user->id], ["user_type" => "customer", "phone" => "0171000000" . ($i + 1)]);
            $guests[] = $user;
        }

        foreach (self::SEASONS as $month => $season) {
            Season::updateOrCreate(["month" => $month], ["season" => $season]);
        }

        $destinations = [];
        foreach (self::DESTINATIONS as [$name, $country, $best_time, $description]) {
            $destinations[$name] = Destination::updateOrCreate(
                ["name" => $name],
                ["country" => $country, "best_time" => $best_time, "description" => $description, "show_on_site" => true, "image_url" => Photo::url(Photo::DESTINATIONS[$name])],
            );
        }

        foreach (self::PACKAGES as [$dest, $name, $days, $price, $interest, $description, $itinerary, $includes, $excludes]) {
            $lines = [];
            foreach ($itinerary as $i => $text) {
                $lines[] = "Day " . ($i + 1) . ": " . $text;
            }
            TourPackage::updateOrCreate(
                ["name" => $name],
                [
                    "destination_id" => $destinations[$dest]->id,
                    "days" => $days,
                    "price" => $price,
                    "interest" => $interest,
                    "description" => $description,
                    "itinerary" => implode("\n", $lines),
                    "includes" => implode("\n", $includes),
                    "excludes" => implode("\n", $excludes),
                    "show_on_site" => true,
                    "image_url" => Photo::url(Photo::PACKAGES[$name] ?? Photo::DESTINATIONS[$dest]),
                ],
            );
        }

        foreach (self::HOTELS as $h => [$dest, $name, $city, $stars, $amenities, $description, $room_types]) {
            $hotel = Hotel::updateOrCreate(
                ["name" => $name],
                [
                    "destination_id" => $destinations[$dest]->id,
                    "city" => $city,
                    "stars" => $stars,
                    "amenities" => $amenities,
                    "description" => $description,
                    "show_on_site" => true,
                    "image_url" => Photo::url(Photo::HOTELS[$name]),
                ],
            );
            foreach ($room_types as $r => [$type_name, $capacity, $price, $count]) {
                $room_type = RoomType::updateOrCreate(
                    ["hotel_id" => $hotel->id, "name" => $type_name],
                    ["capacity" => $capacity, "base_price" => $price, "description" => "{$type_name} for up to {$capacity} guests."],
                );
                for ($n = 1; $n <= $count; $n++) {
                    Room::firstOrCreate(["room_type_id" => $room_type->id, "number" => ($r + 1) . "0" . $n]);
                }
            }
        }

        if (Booking::count() == 0) {
            // - one transaction keeps it quick on the live database
            DB::transaction(fn () => $this->seed_bookings($guests));
        }

        $this->seed_team();

        $this->info("Demo data ready: " . Booking::count() . " bookings, " . Room::count() . " rooms.");
        return 0;
    }

    // - open work for the agents and the hotel staff, and one customer question (only when there is none yet)
    private function seed_team()
    {
        if (WorkTask::count() > 0) {
            return;
        }
        $owner = User::where("email", "owner@example.com")->first();
        $agents = User::whereIn("email", ["agent@example.com", "agent2@example.com"])->orderBy("id")->get();
        $staff = User::whereIn("email", ["staff@example.com", "staff2@example.com"])->orderBy("id")->get();
        $today = Carbon::today();

        // - every pending booking has a call task for an agent
        foreach (Booking::with("user")->where("status", "Pending")->orderBy("check_in")->take(8)->get() as $i => $b) {
            WorkTask::create(["title" => "Call {$b->user->name} about booking #{$b->id}", "detail" => $b->title() . " · " . $b->check_in->format("d M") . " - " . $b->check_out->format("d M Y"), "kind" => "follow_up", "booking_id" => $b->id, "assigned_to" => $agents[$i % 2]->id, "due_date" => $today->copy()->addDay()]);
        }
        // - coming arrivals: staff prepare the room
        foreach (Booking::with("user", "room")->where("booking_type", "Hotel")->where("status", "Confirmed")->whereDate("check_in", ">=", $today)->orderBy("check_in")->take(6)->get() as $i => $b) {
            WorkTask::create(["title" => "Prepare room {$b->room->number} for {$b->user->name}", "detail" => $b->title() . " · arrives " . $b->check_in->format("d M Y"), "kind" => "arrival_prep", "booking_id" => $b->id, "assigned_to" => $staff[$i % 2]->id, "due_date" => $b->check_in]);
        }
        // - rooms waiting for cleaning
        foreach (Room::where("status", "Cleaning")->take(3)->get() as $i => $room) {
            WorkTask::create(["title" => "Clean room {$room->number}", "detail" => "Guest left, then mark the room Available on the room board", "kind" => "housekeeping", "assigned_to" => $staff[$i % 2]->id, "due_date" => $today]);
        }
        // - one task from the owner, and some finished work so the numbers are not empty
        WorkTask::create(["title" => "Update the Kyoto package photos", "detail" => "Ask the hotel for new pictures", "kind" => "general", "assigned_to" => $agents[0]->id, "created_by" => $owner->id, "due_date" => $today->copy()->addDays(3)]);
        foreach ([[$agents[0], "Send trip details to a Bali guest"], [$agents[1], "Call a Maldives customer"], [$staff[0], "Clean room 201"], [$staff[1], "Prepare room 302 for arrival"]] as [$who, $title]) {
            WorkTask::create(["title" => $title, "kind" => "general", "assigned_to" => $who->id, "created_by" => $owner->id, "status" => "Done", "done_at" => now()->subDays(1)]);
        }
        $customer = User::where("email", "customer@example.com")->first();
        Inquiry::create(["user_id" => $customer->id, "agent_id" => $agents[0]->id, "subject" => "Is Bali good in November?", "message" => "We want a 5 day trip with a beach and temples. Which package would you pick?"]);
    }

    // - 6 months back and 3 months ahead, busier in peak months, so the forecast has a story
    private function seed_bookings($guests)
    {
        $today = Carbon::today();
        $chance = ["Peak" => 0.32, "Mid" => 0.2, "Off" => 0.1];
        $methods = ["bKash", "Nagad", "Card", "PayAtHotel"];
        $comments = ["Lovely stay, very clean.", "Great view and friendly staff.", "Good value for money.", "Breakfast could be better.", "Would come again!"];

        foreach (Room::with("roomType")->get() as $room) {
            $day = $today->copy()->subDays(180);
            $end = $today->copy()->addDays(90);
            while ($day->lt($end)) {
                // - future days fill less (bookings are still coming in)
                $p = $chance[self::SEASONS[$day->month]];
                if ($day->gt($today)) {
                    $p *= max(0.25, 1 - $today->diffInDays($day) / 100);
                }
                if (mt_rand() / mt_getrandmax() > $p) {
                    $day->addDay();
                    continue;
                }
                $nights = mt_rand(1, 4);
                $check_in = $day->copy();
                $check_out = $day->copy()->addDays($nights);
                $status = $this->status_for($check_in, $check_out, $today);
                $method = $methods[mt_rand(0, 3)];
                $booking = Booking::create([
                    "user_id" => $guests[mt_rand(0, count($guests) - 1)]->id,
                    "booking_type" => "Hotel",
                    "room_id" => $room->id,
                    "check_in" => $check_in,
                    "check_out" => $check_out,
                    "guests" => mt_rand(1, $room->roomType->capacity),
                    "total" => $room->roomType->base_price * $nights,
                    "status" => $status,
                    "payment_method" => $method,
                    "payment_status" => ($method != "PayAtHotel" || $status == "Completed") && $status != "Cancelled" ? "PAID" : "UNPAID",
                    "created_at" => $check_in->copy()->subDays(mt_rand(3, 45)),
                ]);
                if ($status == "Completed" && mt_rand(1, 4) == 1) {
                    Review::create([
                        "user_id" => $booking->user_id,
                        "booking_id" => $booking->id,
                        "hotel_id" => $room->roomType->hotel_id,
                        "rating" => mt_rand(3, 5),
                        "comment" => $comments[mt_rand(0, 4)],
                    ]);
                }
                // - cancelled nights stay free, so the next booking may start right away
                $day = $status == "Cancelled" ? $check_in->addDay() : $check_out;
            }
            // - room board: who is in the room today
            $in_now = Booking::where("room_id", $room->id)->where("status", "CheckedIn")->exists();
            $room->status = $in_now ? "Occupied" : (mt_rand(1, 8) == 1 ? "Cleaning" : "Available");
            $room->save();
        }

        $packages = TourPackage::all();
        for ($i = 0; $i < 45; $i++) {
            $package = $packages[mt_rand(0, count($packages) - 1)];
            $check_in = $today->copy()->addDays(mt_rand(-180, 90));
            $check_out = $check_in->copy()->addDays($package->days);
            $people = mt_rand(1, 4);
            $status = $this->status_for($check_in, $check_out, $today);
            $booking = Booking::create([
                "user_id" => $guests[mt_rand(0, count($guests) - 1)]->id,
                "booking_type" => "Package",
                "tour_package_id" => $package->id,
                "check_in" => $check_in,
                "check_out" => $check_out,
                "guests" => $people,
                "total" => $package->price * $people,
                "status" => $status,
                "payment_method" => $methods[mt_rand(0, 2)],
                "payment_status" => $status == "Cancelled" || $status == "Pending" ? "UNPAID" : "PAID",
                "created_at" => $check_in->copy()->subDays(mt_rand(7, 60)),
            ]);
            if ($status == "Completed" && mt_rand(1, 2) == 1) {
                Review::create([
                    "user_id" => $booking->user_id,
                    "booking_id" => $booking->id,
                    "tour_package_id" => $package->id,
                    "rating" => mt_rand(4, 5),
                    "comment" => $comments[mt_rand(0, 4)],
                ]);
            }
        }
    }

    private function status_for($check_in, $check_out, $today)
    {
        if (mt_rand(1, 100) <= 7) {
            return "Cancelled";
        }
        if ($check_out->lte($today)) {
            return "Completed";
        }
        if ($check_in->lte($today)) {
            return "CheckedIn";
        }
        return $check_in->diffInDays($today) < 10 && mt_rand(1, 3) == 1 ? "Pending" : "Confirmed";
    }
}
