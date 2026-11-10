@extends("base")

@section("content")
  <section class="hero">
    @foreach ($slides as $slide)
      <div class="hero-bg {{ $loop->first ? 'on' : '' }}" style="background-image: url('{{ $slide[2] }}')" data-name="{{ $slide[0] }}"></div>
    @endforeach
    <div class="container py-5">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <div class="section-title text-white-50 mb-2">{{ $t["hero_kicker"] }}</div>
          <h1>{{ $t["hero_title"] }}<br /><span id="typed-word"></span><span class="type-cursor">|</span></h1>
          <p class="lead mb-4">{{ $t["hero_text"] }}</p>
          @include("_search_box")
        </div>
        <div class="col-lg-4">
          <div class="hero-round"><img id="hero-round-img" src="{{ $slides[0][2] }}" alt="Bali" /></div>
        </div>
      </div>
    </div>
    <div class="hero-caption" id="hero-caption"><i class="fas fa-map-marker-alt"></i> {{ $slides[0][0] }}</div>
    <div class="hero-dots">
      @foreach ($slides as $slide)
        <span class="{{ $loop->first ? 'on' : '' }}"></span>
      @endforeach
    </div>
  </section>

  <div class="strip">
    <div class="strip-track">
      @for ($i = 0; $i < 2; $i++)
        @foreach ($destinations as $destination)
          <a class="chip-pill" href="{{ route('view_destination', $destination->id) }}">
            <img src="{{ $destination->picture_url }}" alt="" loading="lazy" />{{ $destination->name }}
          </a>
        @endforeach
      @endfor
    </div>
  </div>

  <div class="container py-5">
    <div class="row mb-5">
      @foreach ([["fa-shield-alt", "trust_1", "trust_1t"], ["fa-tags", "trust_2", "trust_2t"], ["fa-star", "trust_3", "trust_3t"], ["fa-headset", "trust_4", "trust_4t"]] as $trust)
        <div class="col-6 col-lg-3 mb-3 reveal">
          <div class="trust-item">
            <i class="fas {{ $trust[0] }}"></i>
            <h6>{{ $t[$trust[1]] }}</h6>
            <p>{{ $t[$trust[2]] }}</p>
          </div>
        </div>
      @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-end mb-3 reveal">
      <div>
        <div class="section-title">{{ $t["destinations"] }}</div>
        <h2 class="mb-0">{{ $t["popular_destinations"] }}</h2>
      </div>
      <a href="{{ route('view_destinations') }}">{{ $t["view_all"] }} &rarr;</a>
    </div>
    <div class="bento">
      @foreach ($destinations->take(6) as $destination)
        <a class="bento-item b{{ $loop->index }} reveal" href="{{ route('view_destination', $destination->id) }}">
          <img src="{{ $destination->picture_url }}" alt="{{ $destination->name }}" loading="lazy" />
          <span><b>{{ $destination->name }}</b><small>{{ $destination->country }}</small></span>
        </a>
      @endforeach
    </div>
  </div>

  @include("_band", ["photo" => "santorini", "kicker" => $lang == "bn" ? "সীমিত অফার" : "Limited offer", "title" => $lang == "bn" ? "আর্লি বার্ডে ১৫% ছাড়" : "Book early, save 15%", "text" => $lang == "bn" ? "৬০ দিন আগে বুক করুন যেকোনো প্যাকেজ।" : "Reserve any package 60 days ahead and keep more for the trip.", "href" => route("offers"), "cta" => $t["offers"]])

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-3 mt-4 reveal">
      <div>
        <div class="section-title">{{ $t["packages"] }}</div>
        <h2 class="mb-0">{{ $t["featured_packages"] }}</h2>
      </div>
      <a href="{{ route('view_packages') }}">{{ $t["view_all"] }} &rarr;</a>
    </div>
    <div class="row">
      @foreach ($packages->take(4) as $package)
        <div class="col-lg-3 col-md-4 col-6 mb-3 reveal">
          @include("_package_card")
        </div>
      @endforeach
    </div>

    <div class="counter-band text-center my-5 reveal">
      <div class="row no-gutters">
        @foreach ([[$stats["bookings"], "", "stat_bookings"], [$stats["hotels"], "", "hotels"], [$stats["packages"], "", "packages"], [$stats["reviews"], "", "reviews"]] as $s)
          <div class="col-6 col-md-3 py-2">
            <div class="big" data-count="{{ $s[0] }}">0</div>
            <div>{{ $t[$s[2]] }}</div>
          </div>
        @endforeach
      </div>
    </div>

  </div>

  @include("_band", ["photo" => "iceland", "right" => true, "kicker" => $lang == "bn" ? "এই মাসের পছন্দ" : "Pick of the month", "title" => $lang == "bn" ? "আইসল্যান্ডের ঝর্না আর নর্দার্ন লাইটস" : "Iceland: waterfalls and northern lights", "text" => $lang == "bn" ? "তিন দিনের গোল্ডেন সার্কেল ট্রিপ গাইড সহ।" : "Three days on the Golden Circle with a local guide.", "href" => route("view_packages"), "cta" => $t["packages"]])

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-3 reveal">
      <div>
        <div class="section-title">{{ $t["hotels"] }}</div>
        <h2 class="mb-0">{{ $t["featured_hotels"] }}</h2>
      </div>
      <a href="{{ route('view_hotels') }}">{{ $t["view_all"] }} &rarr;</a>
    </div>
    <div class="row">
      @foreach ($hotels->take(4) as $hotel)
        <div class="col-lg-3 col-md-4 col-6 mb-3 reveal">
          @include("_hotel_card")
        </div>
      @endforeach
    </div>

    <div class="text-center mt-5 mb-4 reveal">
      <div class="section-title">{{ $t["how_title"] }}</div>
      <h2>{{ $t["how_sub"] }}</h2>
    </div>
    <div class="row">
      @foreach ([1, 2, 3, 4] as $n)
        <div class="col-6 col-md-3 reveal">
          <div class="step">
            <div class="num">{{ $n }}</div>
            <h6>{{ $t["step_" . $n] }}</h6>
            <p class="small text-muted">{{ $t["step_" . $n . "t"] }}</p>
          </div>
        </div>
      @endforeach
    </div>

    @if ($quotes->count())
      <div class="text-center mt-5 mb-4 reveal">
        <div class="section-title">{{ $t["reviews"] }}</div>
        <h2>{{ $t["guests_say"] }}</h2>
      </div>
      <div class="row">
        @foreach ($quotes as $quote)
          <div class="col-md-4 mb-3 reveal">
            <div class="quote-card">
              <i class="fas fa-quote-left"></i>
              <p class="mt-2">{{ $quote->comment }}</p>
              <div class="stars">{{ str_repeat("★", $quote->rating) }}</div>
              <strong>{{ $quote->user->name }}</strong>
              <div class="meta">{{ $quote->hotel ? $quote->hotel->name : ($quote->tourPackage ? $quote->tourPackage->name : "") }}</div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="text-center mt-5 mb-3 reveal">
      <div class="section-title">FAQ</div>
      <h2>{{ $t["faq_title"] }}</h2>
    </div>
    <div class="faq mx-auto reveal" style="max-width: 760px" id="faq">
      @foreach ([1, 2, 3, 4] as $n)
        <div class="card">
          <div class="card-header" data-toggle="collapse" data-target="#faq{{ $n }}">{{ $t["faq_" . $n] }} <i class="fas fa-chevron-down float-right small mt-1"></i></div>
          <div id="faq{{ $n }}" class="collapse" data-parent="#faq"><div class="card-body">{{ $t["faq_" . $n . "a"] }}</div></div>
        </div>
      @endforeach
    </div>
  </div>
  @include("_subscribe")
@endsection

@section("scripts")
  <script>
    (function () {
      var slides = document.querySelectorAll('.hero-bg'), dots = document.querySelectorAll('.hero-dots span')
      var caption = document.getElementById('hero-caption'), round = document.getElementById('hero-round-img')
      var i = 0
      function show(n) {
        slides.forEach(function (s, k) { s.classList.toggle('on', k === n) })
        dots.forEach(function (d, k) { d.classList.toggle('on', k === n) })
        var bg = slides[n].style.backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '')
        round.src = bg
        caption.innerHTML = '<i class="fas fa-map-marker-alt"></i> ' + slides[n].getAttribute('data-name')
      }
      setInterval(function () { i = (i + 1) % slides.length; show(i) }, 6000)

      // typed place names
      var words = @json($typed), w = 0, c = 0, del = false, el = document.getElementById('typed-word')
      function tick() {
        var word = words[w]
        el.textContent = word.substring(0, c)
        if (!del && c === word.length) { del = true; return setTimeout(tick, 1400) }
        if (del && c === 0) { del = false; w = (w + 1) % words.length }
        c += del ? -1 : 1
        setTimeout(tick, del ? 45 : 95)
      }
      tick()
    })()
  </script>
@endsection
