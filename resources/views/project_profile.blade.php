@extends("base")

@section("content")
  @php($bn = $lang == "bn")
  <div class="container py-4">
    <div class="page-hero mb-4 reveal in">
      <span class="badge badge-warning mb-2">{{ $t["demo_badge"] }}</span>
      <h1 class="h2 mb-1">RsnTravel</h1>
      <p class="mb-0" style="max-width: 720px">{{ $bn ? "ট্যুর প্যাকেজ বিক্রি, হোটেল রুম বুকিং, এজেন্সি অনুমোদন আর হোটেল ফ্রন্ট ডেস্ক এক সিস্টেমে। কাস্টমার বুক করে, এজেন্সি মালিক অনুমোদন দেয়, হোটেল স্টাফ চেক-ইন ও চেক-আউট করায়।" : "Tour packages, hotel room booking, agency approval and the hotel front desk in one system. The customer books, the agency owner approves, and hotel staff check guests in and out." }}</p>
    </div>

    <div class="row mb-4">
      <div class="col-lg-7 mb-3 reveal">
        <div class="card card-body h-100">
          <h4>{{ $bn ? "এই প্রজেক্ট কী সমস্যা সমাধান করে" : "The problem it solves" }}</h4>
          <p>{{ $bn ? "ছোট ট্রাভেল এজেন্সি আর হোটেল সাধারণত ফোন, চ্যাট আর খাতায় বুকিং রাখে। এতে একই রুম দুইবার বুক হয়, কে অনুমোদন দিল বোঝা যায় না, আর আজ কে আসছে সেটা স্টাফ জানে না।" : "Small travel agencies and hotels often keep bookings in phone calls, chats and notebooks. Rooms get double booked, nobody knows who approved what, and the front desk does not know who arrives today." }}</p>
          <p class="mb-0">{{ $bn ? "RsnTravel প্রতিটি বুকিংকে একটি স্পষ্ট পথে চালায়: অনুরোধ, অনুমোদন, চেক-ইন, সম্পন্ন। প্রতিটি ধাপ ঠিক একজন দায়িত্বশীল ব্যক্তির হাতে।" : "RsnTravel moves every booking along one clear path: request, approval, check-in, done. Each step belongs to exactly one responsible person." }}</p>
        </div>
      </div>
      <div class="col-lg-5 mb-3 reveal">
        <div class="card card-body h-100">
          <h4>{{ $bn ? "বুকিংয়ের যাত্রা" : "Booking journey" }}</h4>
          <div class="timeline">
            @foreach ([["tl_pending", "fa-paper-plane"], ["tl_confirmed", "fa-user-check"], ["tl_checkedin", "fa-key"], ["tl_completed", "fa-flag-checkered"]] as $step)
              <div class="tl done"><div class="dot"><i class="fas {{ $step[1] }}"></i></div>{{ $t[$step[0]] }}</div>
            @endforeach
          </div>
          <div class="small text-muted">{{ $bn ? "কাস্টমার অনুরোধ পাঠায় → মালিক অনুমোদন দেয় → স্টাফ চেক-ইন করায় → চেক-আউটে সম্পন্ন, তারপর রিভিউ।" : "Customer sends a request → owner approves → staff check the guest in → checkout completes the trip, then a review." }}</div>
        </div>
      </div>
    </div>

    <div class="text-center mb-3 reveal"><div class="section-title">{{ $bn ? "তিন ধরনের ব্যবহারকারী" : "Three kinds of users" }}</div></div>
    <div class="row mb-4">
      @foreach ([
        ["fa-user-tie", $bn ? "এজেন্সি মালিক" : "Agency Owner", "owner@example.com", $bn ? ["বুকিং অনুরোধ অনুমোদন বা বাতিল", "আয় ও মাসওয়ারি বুকিংয়ের চার্ট", "হোটেল, প্যাকেজ ও গন্তব্য সাইটে দেখানো বা লুকানো", "সব বুকিং স্ট্যাটাস অনুযায়ী ফিল্টার"] : ["Approve or reject booking requests", "Revenue and monthly booking charts", "Show or hide hotels, packages and destinations", "Filter all bookings by status"]],
        ["fa-concierge-bell", $bn ? "হোটেল স্টাফ" : "Hotel Staff", "staff@example.com", $bn ? ["অনুমোদিত আগমনের তালিকা দেখা", "চেক-ইন ও চেক-আউট", "রুম বোর্ড: ফাঁকা, ব্যবহৃত, পরিষ্কার, রক্ষণাবেক্ষণ", "মালিকের অনুমোদন ছাড়া চেক-ইন নয়"] : ["See the list of approved arrivals", "Check guests in and out", "Room board: available, occupied, cleaning, maintenance", "No check-in without the owner's approval"]],
        ["fa-suitcase-rolling", $bn ? "কাস্টমার" : "Customer", "customer@example.com", $bn ? ["হোটেল খোঁজা, ফাঁকা রুম দেখা", "কার্টে যোগ করে একসাথে চেকআউট", "বুকিং ট্র্যাক, বাতিল, ইনভয়েস, রিভিউ", "প্রিয় তালিকায় হার্ট দিয়ে সেভ"] : ["Search hotels and see free rooms", "Add to cart and check out together", "Track, cancel, print invoices and review", "Save favourites with the heart"]],
      ] as $role)
        <div class="col-md-4 mb-3 reveal">
          <div class="card card-body h-100">
            <h5><i class="fas {{ $role[0] }} text-primary"></i> {{ $role[1] }}</h5>
            <ul class="pl-3 small mb-2">@foreach ($role[3] as $li)<li>{{ $li }}</li>@endforeach</ul>
            <div class="meta mt-auto">{{ $t["demo_login"] }}: <code>{{ $role[2] }}</code></div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="row mb-4">
      <div class="col-lg-6 mb-3 reveal">
        <div class="card card-body h-100">
          <h4>{{ $bn ? "গুরুত্বপূর্ণ নিয়ম" : "Rules the system enforces" }}</h4>
          <ul class="mb-0">
            @foreach ($bn ? ["একই রুম একই সময়ে দুইবার বুক হয় না। চেক-আউটের দিন রুম আবার ফাঁকা।", "ক্যানসেল করা বুকিং রুম আটকে রাখে না।", "সর্বোচ্চ ৩০ রাত ও ১০ জন অতিথি, রুমের ধারণক্ষমতার বেশি নয়।", "চেকআউটের আগে রুম আবার যাচাই হয়, তাই ঠিক সময়ে অন্যজন নিলে আপনি সতর্কবার্তা পান।", "অনুমোদনের আগে ও পরে ক্যানসেল করা যায়, চেক-ইনের পর নয়।", "পেমেন্ট শুধু ডেমো। কোনো আসল টাকা কাটে না।"] : ["The same room cannot be booked twice for the same nights. The check-out day is free again.", "Cancelled bookings do not hold a room.", "Maximum 30 nights and 10 guests, never above the room's capacity.", "Rooms are checked again at checkout, so if someone else just took it you get a clear message.", "A booking can be cancelled before and after approval, not after check-in.", "Payment is a demo. No real money moves."] as $rule)
              <li>{{ $rule }}</li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="col-lg-6 mb-3 reveal">
        <div class="card card-body h-100">
          <h4>{{ $bn ? "কী কী আছে" : "What is inside" }}</h4>
          <div>
            @foreach (["9 destinations", "12 tour packages", "8 hotels, 41 rooms", "Hotel availability search", "Cart and checkout", "bKash, Nagad, Card, Pay at hotel (demo)", "Invoice and print", "Reviews and ratings", "Favourites (heart)", "Owner approval queue", "Staff check-in / check-out", "Room board", "Charts (Chart.js)", "English and বাংলা", "Mobile friendly", "Role based access", "PHPUnit tests"] as $f)
              <span class="chip">{{ $f }}</span>
            @endforeach
          </div>
          <h6 class="mt-3">{{ $bn ? "ডেটা টেবিল" : "Database tables" }}</h6>
          <div class="small text-muted">users, user_ex, destination, tour_package, hotel, room_type, room, booking, review, favorite, season</div>
        </div>
      </div>
    </div>

    <div class="text-center mb-3 reveal"><div class="section-title">{{ $t["tech_title"] }}</div></div>
    <div class="row mb-4">
      @foreach ([["PHP 8.3 + Laravel 13", $bn ? "ব্যাকএন্ড, রাউটিং, মডেল, মিডলওয়্যার" : "Backend, routing, models, middleware"], ["Blade + Bootstrap 4", $bn ? "সব পেজ সার্ভার থেকে রেন্ডার, মোবাইল ফ্রেন্ডলি" : "Server rendered pages, mobile friendly"], ["PostgreSQL on Neon", $bn ? "লাইভ ডেটাবেস (লোকালি SQLite)" : "Live database (SQLite locally)"], ["Vercel", $bn ? "হোস্টিং, প্রতিটি পুশে ডিপ্লয়" : "Hosting, deploy on every push"], ["Chart.js", $bn ? "মালিকের ড্যাশবোর্ড চার্ট" : "Owner dashboard charts"], ["PHPUnit", $bn ? "বুকিং নিয়ম ও রোল অ্যাক্সেস পরীক্ষা" : "Tests for booking rules and role access"], ["Font Awesome", $bn ? "আইকন" : "Icons"], ["Wikimedia Commons", $bn ? "আসল ক্যামেরা-তোলা ছবি (ফ্রি লাইসেন্স)" : "Real photographs (free licence)"]] as $tech)
        <div class="col-6 col-md-3 mb-3 reveal">
          <div class="card card-body h-100 py-3"><strong>{{ $tech[0] }}</strong><span class="small text-muted">{{ $tech[1] }}</span></div>
        </div>
      @endforeach
    </div>

    <div class="row mb-4">
      <div class="col-lg-6 mb-3 reveal">
        <div class="card card-body h-100">
          <h4>{{ $bn ? "এখনো নেই" : "Not built yet" }}</h4>
          <ul class="mb-0">
            @foreach ($bn ? ["আসল পেমেন্ট গেটওয়ে", "ইমেইল ও এসএমএস নোটিফিকেশন", "হোটেল স্টাফকে নির্দিষ্ট হোটেলে বরাদ্দ", "AI ট্রিপ প্ল্যানার ও দাম পূর্বাভাস"] : ["A real payment gateway", "Email and SMS notifications", "Assigning staff to one specific hotel", "AI trip planner and price forecast"] as $todo)<li>{{ $todo }}</li>@endforeach
          </ul>
        </div>
      </div>
      <div class="col-lg-6 mb-3 reveal" id="contact">
        <div class="card card-body h-100">
          <h4>{{ $t["contact"] }}</h4>
          <p class="mb-2">{{ $bn ? "এই ডেমো OANRO পোর্টফোলিওর অংশ। প্রশ্ন, ফিডব্যাক বা বাগ জানাতে যোগাযোগ করুন।" : "This demo is part of the OANRO portfolio. Get in touch for questions, feedback or bug reports." }}</p>
          <div><i class="fas fa-user text-primary"></i> Tasnim Hasari Rosan</div>
          <div><i class="fas fa-map-marker-alt text-primary"></i> Pabna, Bangladesh</div>
          <div><i class="fab fa-github text-primary"></i> <a href="https://github.com/rosan777" target="_blank" rel="noopener">github.com/rosan777</a></div>
          <div><i class="fas fa-building text-primary"></i> OANRO</div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <a href="{{ route('flogin') }}" class="btn btn-cta btn-lg"><i class="fas fa-play"></i> {{ $t["try_demo"] }}</a>
    </div>
  </div>
@endsection
