@extends("base")

@section("no_tech", "1")

@section("content")
  @php
    $bn = $lang == "bn";
    // one place for the page text: [English, Bangla]
    $pick = fn ($en, $bnText) => $bn ? $bnText : $en;
    $roles = [
      ["fa-crown", $pick("Agency owner", "এজেন্সি মালিক"), $bn
        ? ["প্রতিটি বুকিং অনুরোধ অনুমোদন বা বাতিল", "এজেন্ট ও হোটেল স্টাফকে হাতে কাজ দেওয়া বা সরানো", "আয়, মাসওয়ারি বুকিং ও টিমের কাজের চাপ দেখা", "কোন হোটেল ও প্যাকেজ সাইটে দেখাবে তা ঠিক করা", "সব বুকিং স্ট্যাটাস অনুযায়ী ফিল্টার"]
        : ["Approve or reject every booking request", "Give tasks to agents and staff, or move them", "Revenue, monthly bookings and team workload", "Choose which hotels and packages show on the site", "Filter all bookings by status"]],
      ["fa-headset", $pick("Travel agent", "ট্রাভেল এজেন্ট"), $bn
        ? ["নতুন বুকিংয়ের পর কাস্টমারকে ফোন (কাজ নিজে থেকে আসে)", "\"এজেন্টকে জিজ্ঞাসা করুন\" থেকে আসা প্রশ্নের উত্তর", "প্যাকেজ বুকিংয়ে ভ্রমণের বিস্তারিত পাঠানো", "কাস্টমারের ফোন নম্বরসহ বুকিং তালিকা"]
        : ["Call every new customer (the task arrives by itself)", "Answer questions sent with Ask an Agent", "Send trip details for package bookings", "Booking list with the customer's phone number"]],
      ["fa-concierge-bell", $pick("Hotel staff", "হোটেল স্টাফ"), $bn
        ? ["অতিথি আসার আগে রুম প্রস্তুত করা", "অনুমোদিত অতিথির চেক-ইন ও চেক-আউট", "রুম বোর্ড: ফাঁকা, ব্যবহৃত, পরিষ্কার, রক্ষণাবেক্ষণ", "চেক-আউটের পর রুম পরিষ্কার করে ফাঁকা করা"]
        : ["Prepare the room before the guest arrives", "Check approved guests in and out", "Room board: available, occupied, cleaning, maintenance", "Clean the room after check-out and free it"]],
      ["fa-suitcase-rolling", $pick("Customer", "কাস্টমার"), $bn
        ? ["হোটেল, প্যাকেজ ও গন্তব্য খোঁজা", "হার্ট দিয়ে পছন্দ সেভ করা", "কার্টে যোগ করে একসাথে চেকআউট", "বুকিং ট্র্যাক, বাতিল, ইনভয়েস, রিভিউ", "এজেন্টকে প্রশ্ন পাঠানো"]
        : ["Search hotels, packages and destinations", "Save favourites with the heart", "Add to cart and check out together", "Track, cancel, print invoices and review", "Ask an agent a question"]],
    ];
    $steps = $bn
      ? ["কাস্টমার কার্টে যোগ করে চেকআউট করে", "এজেন্টের কাছে \"ফোন করো\" কাজ যায়", "মালিক অনুমোদন দেন", "স্টাফের কাছে \"রুম প্রস্তুত করো\" কাজ যায়", "স্টাফ চেক-ইন ও চেক-আউট করায়", "রুম পরিষ্কার হয়, কাস্টমার রিভিউ লেখে"]
      : ["Customer adds to the cart and checks out", "A call task goes to an agent", "The owner approves", "A prepare-room task goes to staff", "Staff check the guest in and out", "The room is cleaned, the customer reviews"];
    $features = $bn
      ? [["চার ভূমিকার লগইন", "মালিক, এজেন্ট, স্টাফ ও কাস্টমার। অন্যের পেজ খুললে \"অনুমতি নেই\" দেখায়।"], ["তারিখ ধরে রুম খোঁজা", "একই রুম একই রাতে দুইবার বুক হয় না। চেক-আউটের দিন রুম আবার ফাঁকা।"], ["কার্ট ও চেকআউট", "হোটেল ও প্যাকেজ একসাথে। বিকাশ, নগদ, কার্ড বা হোটেলে পেমেন্ট (ডেমো)।"], ["মালিকের অনুমোদন", "অনুমোদন ছাড়া চেক-ইন হয় না। বাতিলে টাকা ফেরত (ডেমো)।"], ["নিজে নিজে কাজ ভাগ", "প্রতিটি ধাপে সবচেয়ে কম কাজওয়ালা এজেন্ট বা স্টাফ কাজ পায়।"], ["এজেন্টকে জিজ্ঞাসা", "কাস্টমার প্রশ্ন পাঠায়, এজেন্ট উত্তর দেয়, উত্তর কাস্টমারের পেজে দেখা যায়।"], ["রুম বোর্ড", "ফাঁকা, ব্যবহৃত, পরিষ্কার, রক্ষণাবেক্ষণ। চেক-আউটে রুম নিজে \"পরিষ্কার\" হয়।"], ["ইনভয়েস, রিভিউ, পছন্দ", "প্রিন্টযোগ্য ইনভয়েস, শেষ হওয়া ভ্রমণে রিভিউ, হার্ট দিয়ে পছন্দ সেভ।"], ["চার্ট ও দুই ভাষা", "মালিকের আয় ও বুকিং চার্ট। ইংরেজি ও বাংলা।"]]
      : [["Four role logins", "Owner, agent, staff and customer. Opening another role's page shows \"not allowed\"."], ["Search rooms by date", "The same room is never booked twice for the same nights. The check-out day is free again."], ["Cart and checkout", "Hotels and packages together. bKash, Nagad, card or pay at hotel (demo)."], ["Owner approval", "No check-in without approval. A rejection refunds the payment (demo)."], ["Automatic task sharing", "At each step the least busy agent or staff member gets the work."], ["Ask an agent", "A customer sends a question, an agent replies, the answer shows on the customer's page."], ["Room board", "Available, occupied, cleaning, maintenance. Check-out puts the room in cleaning by itself."], ["Invoices, reviews, favourites", "Printable invoices, reviews after a finished trip, favourites with a heart."], ["Charts and two languages", "Owner revenue and booking charts. English and Bangla."]];
    $roadmap = $bn
      ? [["পেমেন্ট", ["আসল পেমেন্ট গেটওয়ে (বিকাশ, নগদ, কার্ড)", "আংশিক অগ্রিম ও পুরো টাকা ফেরতের নিয়ম", "কর ও ডিসকাউন্ট কোড"]], ["কাস্টমারের অভিজ্ঞতা", "ইমেইল ও এসএমএসে বুকিং নিশ্চিতকরণ", "ভিসা ও ডকুমেন্টের তালিকা", "ছবিসহ রিভিউ ও ভ্রমণ গ্যালারি"], ["এজেন্সি ও হোটেলের টুল", "মৌসুম অনুযায়ী দাম ও গ্রুপ বুকিং", "একাধিক হোটেল ও শাখা", "গাইড, গাড়ি ও ফ্লাইটের বুকিং"], ["সংযোগ", "চ্যানেল ম্যানেজার (Booking.com, Airbnb)", "হিসাবের সফটওয়্যার", "WhatsApp ও চ্যাট সহায়ক"]]
      : [["Payments", "A real payment gateway (bKash, Nagad, card)", "Part-payment deposits and refund rules", "Taxes and discount codes"], ["Customer experience", "Booking confirmation by email and SMS", "Visa and document checklist", "Photo reviews and a trip gallery"], ["Agency and hotel tools", "Season pricing and group bookings", "Several hotels and branches", "Guide, car and flight bookings"], ["Connections", "Channel manager (Booking.com, Airbnb)", "Accounting software", "WhatsApp and a chat helper"]];
  @endphp

  <div class="demo-ticker" aria-label="Demo notice">
    <div class="ticker-track">
      @for ($i = 0; $i < 2; $i++)
        <span>{{ $pick("Demo project", "ডেমো প্রজেক্ট") }}</span><span>&#9679;</span><span>{{ $pick("Sample data only", "শুধু নমুনা তথ্য") }}</span><span>&#9679;</span><span>{{ $pick("Payments are simulated", "পেমেন্ট নকল") }}</span><span>&#9679;</span><span>{{ $pick("Built with Laravel", "Laravel দিয়ে তৈরি") }}</span><span>&#9679;</span>
      @endfor
    </div>
  </div>

  <header class="pp-hero">
    <div class="pp-blob pp-blob-a"></div>
    <div class="pp-blob pp-blob-b"></div>
    <div class="container text-center position-relative">
      <p class="pp-eyebrow">{{ $t["footer"] }}</p>
      <h1 class="pp-title">{{ $pick("Project", "প্রজেক্ট") }} <em>{{ $pick("Profile", "প্রোফাইল") }}</em></h1>
      <p class="pp-tagline">{{ $pick("A travel agency and hotel platform where customers, travel agents, hotel staff and the agency owner all work in one place.", "একটি ট্রাভেল এজেন্সি ও হোটেল প্ল্যাটফর্ম, যেখানে কাস্টমার, ট্রাভেল এজেন্ট, হোটেল স্টাফ ও এজেন্সি মালিক এক জায়গায় কাজ করেন।") }}</p>
      <p class="pp-lines">{{ $pick("Book", "বুক") }} &nbsp;&middot;&nbsp; {{ $pick("Approve", "অনুমোদন") }} &nbsp;&middot;&nbsp; {{ $pick("Welcome", "স্বাগত") }} &nbsp;&middot;&nbsp; {{ $pick("Review", "রিভিউ") }}</p>
      <div class="mt-4">
        <a href="{{ route('view_packages') }}" class="btn btn-cta btn-lg mr-2 mb-2">{{ $pick("Browse packages", "প্যাকেজ দেখুন") }}</a>
        <a href="{{ route('flogin') }}" class="btn btn-outline-light btn-lg mb-2">{{ $pick("Try a demo login", "ডেমো লগইন") }}</a>
      </div>
    </div>
  </header>

  <section class="pp-section reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("Read this first", "আগে এটা পড়ুন") }}</p>
      <h2 class="pp-h2">{{ $pick("This is a demo, not a real agency", "এটি ডেমো, আসল এজেন্সি নয়") }}</h2>
      <div class="row">
        <div class="col-lg-7">
          <p>{{ $pick("TravelDemo is a demo project built to show how a complete travel agency and hotel system is put together. Every destination, hotel, package, price and booking you see is sample data. Nothing here is sold or reserved, and no real money moves.", "TravelDemo একটি ডেমো প্রজেক্ট, যা দেখায় একটি পূর্ণ ট্রাভেল এজেন্সি ও হোটেল সিস্টেম কীভাবে গড়ে ওঠে। এখানকার প্রতিটি গন্তব্য, হোটেল, প্যাকেজ, দাম ও বুকিং নমুনা তথ্য। কিছুই বিক্রি বা সংরক্ষিত হয় না, আসল টাকাও কাটে না।") }}</p>
          <p>{{ $pick("The structure is real though: separate logins and screens for each role, a working cart, bookings that move through stages, work that is handed out to the team, invoices and an owner report. The same base can grow into a real service by adding the pieces listed further down this page.", "কাঠামোটা কিন্তু আসল: প্রতি ভূমিকার আলাদা লগইন ও পেজ, কাজ করা কার্ট, ধাপে ধাপে বুকিং, টিমকে কাজ ভাগ, ইনভয়েস ও মালিকের রিপোর্ট। নিচে লেখা অংশগুলো যোগ করলে এটাই আসল সার্ভিসে বড় হতে পারে।") }}</p>
        </div>
        <div class="col-lg-5">
          <ul class="pp-keys">
            <li><span>{{ $pick("Type", "ধরন") }}</span>{{ $pick("Demo project (v0.0.0)", "ডেমো প্রজেক্ট (v0.0.0)") }}</li>
            <li><span>{{ $pick("Stack", "প্রযুক্তি") }}</span>Laravel, PostgreSQL, Bootstrap</li>
            <li><span>{{ $pick("Data", "তথ্য") }}</span>{{ $pick("Sample destinations, hotels, packages", "নমুনা গন্তব্য, হোটেল, প্যাকেজ") }}</li>
            <li><span>{{ $pick("Payments", "পেমেন্ট") }}</span>{{ $pick("Simulated (bKash, Nagad, card, pay at hotel)", "নকল (বিকাশ, নগদ, কার্ড, হোটেলে)") }}</li>
            <li><span>{{ $pick("Photos", "ছবি") }}</span>Wikimedia Commons</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="pp-section pp-alt reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("Who uses it", "কারা ব্যবহার করেন") }}</p>
      <h2 class="pp-h2">{{ $pick("Four roles, each with its own side", "চার ভূমিকা, প্রত্যেকের নিজস্ব দিক") }}</h2>
      <div class="row">
        @foreach ($roles as $i => $role)
          <div class="col-md-6 col-lg-3 mb-4">
            <div class="pp-card" style="border-top-color: var(--c-{{ ["owner", "agent", "staff", "customer"][$i] }})"><span class="pp-num">{{ sprintf("%02d", $i + 1) }}</span>
              <h3><i class="fas {{ $role[0] }}" style="color: var(--c-{{ ["owner", "agent", "staff", "customer"][$i] }})"></i> {{ $role[1] }}</h3>
              <ul>@foreach ($role[2] as $li)<li>{{ $li }}</li>@endforeach</ul>
              <div class="meta mt-2"><code>{{ ["owner", "agent", "staff", "customer"][$i] }}@example.com</code></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="pp-section reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("How a booking moves", "বুকিং যেভাবে চলে") }}</p>
      <h2 class="pp-h2">{{ $pick("One booking, four people", "একটি বুকিং, চারজন মানুষ") }}</h2>
      <div class="pp-flow">
        @foreach ($steps as $i => $step)
          <div class="pp-step"><b>{{ $i + 1 }}</b><span>{{ $step }}</span></div>
        @endforeach
      </div>
      <p class="mt-4 text-muted">{{ $pick("Work is shared automatically: at every step the agent or staff member with the fewest open tasks gets the next one, so nobody is overloaded. The owner can also give a task by hand or move one to someone else.", "কাজ নিজে নিজে ভাগ হয়: প্রতিটি ধাপে সবচেয়ে কম খোলা কাজওয়ালা এজেন্ট বা স্টাফ পরের কাজটি পান, তাই কারও ওপর চাপ বেশি পড়ে না। মালিক চাইলে হাতে কাজ দিতে বা অন্যজনকে সরাতে পারেন।") }}</p>
    </div>
  </section>

  <section class="pp-section pp-alt reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("What works today", "আজ যা কাজ করে") }}</p>
      <h2 class="pp-h2">{{ $pick("Built and running in this demo", "এই ডেমোতে তৈরি ও চালু") }}</h2>
      <div class="row">
        @foreach ($features as $i => $f)
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="pp-card small-card"><span class="pp-num">{{ sprintf("%02d", $i + 1) }}</span><h3>{{ $f[0] }}</h3><p>{{ $f[1] }}</p></div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="pp-section reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("What we can offer next", "পরের ধাপে যা দিতে পারি") }}</p>
      <h2 class="pp-h2">{{ $pick("From demo to a real service", "ডেমো থেকে আসল সার্ভিসে") }}</h2>
      <p class="mb-4">{{ $pick("None of the items below are built yet. They show where the project can go, and each one plugs into the existing structure without a rewrite.", "নিচের কোনোটিই এখনও তৈরি হয়নি। এগুলো দেখায় প্রজেক্ট কোথায় যেতে পারে, আর প্রতিটি বর্তমান কাঠামোতে নতুন করে না লিখেই যুক্ত হয়।") }}</p>
      <div class="row">
        @foreach ($roadmap as $i => $group)
          <div class="col-md-6 mb-4">
            <div class="pp-card"><span class="pp-num">{{ sprintf("%02d", $i + 1) }}</span><h3>{{ $group[0] }}</h3>
              <ul>@foreach (array_slice($group, 1) as $item)<li>{{ is_array($item) ? implode(", ", $item) : $item }}</li>@endforeach</ul>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="pp-section pp-alt reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("Admin side", "অ্যাডমিন দিক") }}</p>
      <h2 class="pp-h2">{{ $pick("Rooms, accounts and AI", "রুম, হিসাব ও এআই") }}</h2>
      <div class="row">
        <div class="col-md-4 mb-4"><div class="pp-card small-card"><h3>{{ $pick("Rooms", "রুম") }}</h3><p>{{ $pick("Season-based prices, room blocking for maintenance, overbooking alerts and a calendar view of every room.", "মৌসুম অনুযায়ী দাম, রক্ষণাবেক্ষণের জন্য রুম বন্ধ, ওভারবুকিং সতর্কতা এবং প্রতিটি রুমের ক্যালেন্ডার।") }}</p></div></div>
        <div class="col-md-4 mb-4"><div class="pp-card small-card"><h3>{{ $pick("Accounts", "হিসাব") }}</h3><p>{{ $pick("Daily income, expenses, agent commission and a profit view, built on the same booking data the owner dashboard already reads.", "দৈনিক আয়, খরচ, এজেন্টের কমিশন ও লাভের হিসাব, যা মালিকের ড্যাশবোর্ডের একই বুকিং তথ্য থেকে আসে।") }}</p></div></div>
        <div class="col-md-4 mb-4"><div class="pp-card small-card"><h3>{{ $pick("AI", "এআই") }}</h3><p>{{ $pick("A trip planner from budget and interests, demand and price forecasts for peak months, and a chat helper that answers customers.", "বাজেট ও আগ্রহ থেকে ট্রিপ পরিকল্পনা, ব্যস্ত মাসের চাহিদা ও দামের পূর্বাভাস এবং কাস্টমারকে উত্তর দেওয়া চ্যাট সহায়ক।") }}</p></div></div>
      </div>
    </div>
  </section>

  @include("_tech_strip")

  <section class="pp-section reveal">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("Mobile and browsers", "মোবাইল ও ব্রাউজার") }}</p>
      <h2 class="pp-h2">{{ $pick("Made to fit every screen", "প্রতিটি স্ক্রিনের জন্য তৈরি") }}</h2>
      <p>{{ $pick("The site uses a responsive layout, so pages reflow for phones, tablets and desktops on Windows, macOS, Linux, iPhone and Android. Text and headings scale with the screen, long tables scroll sideways instead of breaking the page, and the layout still works when a visitor makes the text bigger.", "সাইটের লেআউট রেসপনসিভ, তাই Windows, macOS, Linux, iPhone ও Android-এর ফোন, ট্যাবলেট ও ডেস্কটপে পেজ নিজে সাজিয়ে নেয়। লেখা ও শিরোনাম স্ক্রিন অনুযায়ী ছোট-বড় হয়, লম্বা টেবিল পেজ না ভেঙে পাশে স্ক্রল হয়, আর ব্যবহারকারী লেখা বড় করলেও লেআউট ঠিক থাকে।") }}</p>
      <div class="pp-chips">
        @foreach (["iPhone", "Android", "iPad / Tablet", "Windows", "macOS", "Linux"] as $chip)<span>{{ $chip }}</span>@endforeach
      </div>
    </div>
  </section>

  <section class="pp-section pp-alt reveal" id="contact">
    <div class="container">
      <p class="pp-eyebrow dark">{{ $pick("Let's talk", "কথা বলি") }}</p>
      <h2 class="pp-h2">{{ $pick("Tell us what you need", "আপনার কী দরকার জানান") }}</h2>
      <div class="row align-items-center">
        <div class="col-12 mb-4">
          <p>{{ $pick("Every project starts with a conversation. What gets built depends on your requirement or ours: from the first design idea to a full professional website, we sit down together and decide what the site needs.", "প্রতিটি প্রজেক্ট শুরু হয় কথোপকথনে। কী তৈরি হবে তা আপনার বা আমাদের প্রয়োজনের ওপর নির্ভর করে: প্রথম ডিজাইন আইডিয়া থেকে পূর্ণ পেশাদার ওয়েবসাইট পর্যন্ত, আমরা একসাথে বসে ঠিক করি সাইটের কী দরকার।") }}</p>
          <ul class="pp-talk">
            @foreach ($bn ? ["আপনার ব্যবসার সাথে মানানসই ডিজাইন, ব্র্যান্ড ও রঙ", "ফিচার, ইউজার রোল ও দরকারি পেজ", "যেখানে কাজে লাগে সেখানে ডেটা, রিপোর্ট ও এআই", "হোস্টিং, নিরাপত্তা ও দীর্ঘমেয়াদি সাপোর্ট", "সময়সীমা ও বাজেট"] : ["Design, branding and colors that fit your business", "Features, user roles and the pages you need", "Data, reports and AI where they help", "Hosting, security and long-term support", "Timeline and budget"] as $li)
              <li>{{ $li }}</li>
            @endforeach
          </ul>
          <a href="https://oanro.com/contact" target="_blank" rel="noopener noreferrer" class="btn btn-contact btn-lg mt-2">{{ $pick("Contact us", "যোগাযোগ করুন") }}</a>
          <p class="small text-muted mt-2 mb-0">{{ $pick("Opens the OANRO contact page in a new tab.", "OANRO-র যোগাযোগ পেজ নতুন ট্যাবে খুলবে।") }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="pp-cta reveal">
    <div class="container text-center">
      <h2>{{ $pick("Take a look around", "ঘুরে দেখুন") }}</h2>
      <p>{{ $pick("Log in as an owner, an agent, a staff member or a customer from the login page and try each side.", "লগইন পেজ থেকে মালিক, এজেন্ট, স্টাফ বা কাস্টমার হিসেবে ঢুকে প্রতিটি দিক ঘুরে দেখুন।") }}</p>
      <a href="{{ route('flogin') }}" class="btn btn-cta btn-lg mr-2 mb-2">{{ $pick("Open demo login", "ডেমো লগইন খুলুন") }}</a>
      <a href="{{ route('index') }}" class="btn btn-outline-light btn-lg mb-2">{{ $pick("Back to home", "হোমে ফিরুন") }}</a>
    </div>
  </section>
@endsection
