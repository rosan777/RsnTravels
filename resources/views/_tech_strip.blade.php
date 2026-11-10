@php
  $row1 = ["PHP 8.3", "Laravel 13", "Blade Templates", "Eloquent ORM", "PostgreSQL", "Neon", "SQLite"];
  $row2 = ["Bootstrap 4", "HTML", "CSS", "JavaScript", "jQuery", "Chart.js", "PHPUnit", "Vercel", "GitHub"];
@endphp
<div class="tech-section py-5">
  <div class="container">
    <h2 class="tech-title mb-4">{{ $t["tech_title"] }}</h2>
    @foreach ([[$row1, ""], [$row2, "reverse"]] as $i => $strip)
      <div class="marquee {{ $i ? 'mt-3' : '' }}">
        <div class="marquee-track {{ $strip[1] }}">
          @for ($k = 0; $k < 2; $k++)@foreach ($strip[0] as $tech)<span class="tech-pill">{{ $tech }}</span>@endforeach @endfor
        </div>
      </div>
    @endforeach
  </div>
</div>
<style>
  /* Technology strip: two rows of pills that slide in opposite directions */
  .tech-section { background: #0b0d12; margin-top: 3rem; }
  .tech-title { color: #fff; font-weight: 700; font-size: 1.6rem; }
  .marquee { overflow: hidden; -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); }
  .marquee-track { display: flex; width: max-content; animation: slideLeft 28s linear infinite; }
  .marquee-track.reverse { animation-name: slideRight; animation-duration: 34s; }
  .marquee:hover .marquee-track { animation-play-state: paused; }
  .tech-pill { flex: none; margin-right: 14px; padding: 9px 26px; border-radius: 999px; background: #1f2630; border: 1px solid #333c4a; color: #c9d1e6; font-size: .95rem; transition: color .2s, border-color .2s, transform .2s; }
  .tech-pill:hover { color: #fff; border-color: #ff6b57; transform: translateY(-2px); }
  @keyframes slideLeft { from { transform: translateX(0); } to { transform: translateX(-50%); } }
  @keyframes slideRight { from { transform: translateX(-50%); } to { transform: translateX(0); } }
</style>
