@php
  $row1 = ["PHP 8.3", "Laravel 13", "Blade Templates", "Eloquent ORM", "PostgreSQL", "Neon", "SQLite"];
  $row2 = ["Bootstrap 4", "HTML", "CSS", "JavaScript", "jQuery", "Chart.js", "PHPUnit", "Vercel", "GitHub"];
@endphp
<div class="tech-section">
  <div class="container">
    <h2 class="tech-title mb-4">{{ $t["tech_title"] }}</h2>
    @foreach ([$row1, $row2] as $row)
      <div class="tech-row">
        @foreach ($row as $tech)<span class="tech-pill">{{ $tech }}</span>@endforeach
      </div>
    @endforeach
  </div>
</div>
<style>
  .tech-section { background: #0b0d12; margin-top: 3rem; padding: 3rem 0 2.5rem; }
  .tech-title { color: #fff; font-weight: 700; font-size: 1.6rem; }
  .tech-row { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 12px; }
  .tech-pill { padding: 9px 26px; border-radius: 999px; background: #1f2630; border: 1px solid #333c4a; color: #c9d1e6; font-size: .95rem; transition: color .2s, border-color .2s; }
  .tech-pill:hover { color: #fff; border-color: #ff6b57; }
</style>
