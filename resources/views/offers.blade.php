@extends("base")

@section("content")
  @php $bn = $lang == "bn"; @endphp
  <section class="page-banner" style="background-image: url('{{ \App\Support\Photo::url(\App\Support\Photo::BANDS["maldives"], 2000) }}')">
    <div class="container reveal">
      <div class="band-kicker">{{ $bn ? "বিশেষ অফার" : "Limited offers" }}</div>
      <h1>{{ $bn ? "ভ্রমণ আরও সাশ্রয়ী" : "Travel more, pay less" }}</h1>
      <p>{{ $bn ? "বুক করুন আগে, বাঁচান বেশি। ডেমো অফার।" : "Book early and save. Demo offers shown for the portfolio." }}</p>
    </div>
  </section>

  <div class="container py-5">
    <div class="row">
      @foreach ([["fa-percent", $bn ? "আর্লি বার্ড ১৫%" : "Early bird 15% off", $bn ? "৬০ দিন আগে বুক করলে" : "Book 60 days ahead"], ["fa-users", $bn ? "পরিবারে ফ্রি রুম আপগ্রেড" : "Free family room upgrade", $bn ? "৪ জন বা বেশি হলে" : "For groups of four or more"], ["fa-heart", $bn ? "হানিমুন সারপ্রাইজ" : "Honeymoon surprise", $bn ? "ফুল ও কেক সহ ডিনার" : "Flowers, cake and a dinner"]] as $o)
        <div class="col-md-4 mb-3 reveal"><div class="offer"><i class="fas {{ $o[0] }}"></i><h5>{{ $o[1] }}</h5><p>{{ $o[2] }}</p></div></div>
      @endforeach
    </div>

    <h2 class="mt-4 mb-3 reveal">{{ $bn ? "সবচেয়ে কম দামের প্যাকেজ" : "Best value packages" }}</h2>
    <div class="row">
      @foreach ($packages as $package)
        <div class="col-lg-3 col-md-4 col-6 mb-3 reveal">@include("_package_card")</div>
      @endforeach
    </div>
  </div>

  @include("_band", ["photo" => "iceland", "kicker" => $bn ? "এই মাসের পছন্দ" : "Pick of the month", "title" => $bn ? "আইসল্যান্ডে ঝর্না আর নর্দার্ন লাইটস" : "Waterfalls and northern lights in Iceland", "text" => $bn ? "তিন দিনের গোল্ডেন সার্কেল ট্রিপ, গাইড সহ।" : "A three day Golden Circle trip with a local guide.", "href" => route("view_packages"), "cta" => $t["view_all"]])
  @include("_subscribe")
@endsection
