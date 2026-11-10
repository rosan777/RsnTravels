<!DOCTYPE html>
<html lang="{{ $lang }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? "TravelDemo" }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  </head>

  <body>
    @yield("before_nav")
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary justify-content-between">
      <a class="navbar-brand" href="{{ route('index') }}">TravelDemo</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav" id="nav-items">
          @if ($userEx)
            @if ($userEx->user_type == "owner")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('owner_dashboard') }}">{{ $t["dashboard"] }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('staff_dashboard') }}">{{ $t["bookings"] }}</a>
              </li>
            @elseif ($userEx->user_type == "staff")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('staff_dashboard') }}">{{ $t["dashboard"] }}</a>
              </li>
            @else
              <li class="nav-item">
                <a class="nav-link" href="{{ route('customer_dashboard') }}">{{ $t["dashboard"] }}</a>
              </li>
            @endif
          @endif

          <li class="nav-item">
            <a class="nav-link" href="{{ route('view_destinations') }}">{{ $t["destinations"] }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('view_packages') }}">{{ $t["packages"] }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('view_hotels') }}">{{ $t["hotels"] }}</a>
          </li>
        </ul>
      </div>
      <div class="nav-actions">
        <a href="{{ route('set_language', $lang == 'en' ? 'bn' : 'en') }}" class="btn btn-outline-light">{{ $t["other_language"] }}</a>
        @auth
          <a href="{{ route('flogout') }}" class="btn btn-outline-light">{{ $t["logout"] }}</a>
        @else
          <a href="{{ route('flogin') }}" class="btn btn-outline-light">{{ $t["login"] }}</a>
          <a href="{{ route('fregister') }}" class="btn btn-outline-light">{{ $t["register"] }}</a>
        @endauth
      </div>
    </nav>

    @yield("after_nav")

    @if (session("success") || session("error"))
      <div class="container w-full p-3">
        @if (session("success"))
          <div class="alert alert-success">{{ session("success") }}</div>
        @endif
        @if (session("error"))
          <div class="alert alert-error">{{ session("error") }}</div>
        @endif
      </div>
    @endif

    @yield("content")

    <footer class="site-footer">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="text-center text-md-left mb-2 mb-md-0">
          <span class="footer-brand">TravelDemo</span>
          <div>{{ $t["footer"] }}</div>
        </div>
        <div class="footer-links mb-2 mb-md-0">
          <a href="{{ route('index') }}">{{ $t["home"] }}</a>
          <a href="{{ route('view_packages') }}">{{ $t["packages"] }}</a>
          <a href="{{ route('view_hotels') }}">{{ $t["hotels"] }}</a>
          @auth
            <a href="{{ route('dashboard') }}">{{ $t["dashboard"] }}</a>
          @else
            <a href="{{ route('flogin') }}">{{ $t["login"] }}</a>
          @endauth
        </div>
        <div>&copy; 2024 OANRO. All rights reserved.</div>
      </div>
    </footer>
  </body>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <style>
    :root {
      --rsn-accent: #2a9d8f;
      --rsn-dark: #1f2d3d;
    }
    body {
      background: #f5f8f9;
      color: #2d3748;
    }
    .navbar.bg-primary { background: var(--rsn-dark) !important; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); border-bottom: 3px solid var(--rsn-accent); }
    .navbar-brand { font-size: 1.35rem; font-weight: 700; letter-spacing: 0.02em; }
    .navbar-brand::before { content: '\2708  '; }
    .navbar .nav-link { padding-left: 1rem; padding-right: 1rem; border-radius: 6px; }
    .navbar .nav-item.active .nav-link, .navbar .nav-link:hover { color: #fff; background: rgba(255, 255, 255, 0.12); }
    .nav-actions .btn { margin-left: 4px; }
    .btn { border-radius: 8px; }
    .btn-primary { background: var(--rsn-accent); border-color: var(--rsn-accent); }
    .btn-primary:hover, .btn-primary:focus { background: #23867a; border-color: #23867a; }
    .btn-outline-primary { color: var(--rsn-accent); border-color: var(--rsn-accent); }
    .btn-outline-primary:hover { background: var(--rsn-accent); border-color: var(--rsn-accent); }
    .text-primary { color: var(--rsn-accent) !important; }
    a { color: var(--rsn-accent); }
    a:hover { color: #23867a; }
    .card { border: 0; border-radius: 12px; box-shadow: 0 2px 10px rgba(27, 35, 54, 0.05); }
    .card-header { background: #fff; border-bottom: 1px solid #eef0f6; border-radius: 12px 12px 0 0 !important; }
    .card-header h3 { font-size: 1.15rem; font-weight: 600; margin: 0; }
    .table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(27, 35, 54, 0.05); }
    .table thead th { background: #eef3f4; border: 0; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #5b6477; }
    .table td { vertical-align: middle; }
    .form-control { border-radius: 8px; }
    .form-control:focus { border-color: var(--rsn-accent); box-shadow: 0 0 0 0.15rem rgba(42, 157, 143, 0.18); }
    .alert { border: 0; border-radius: 10px; box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06); }
    .alert-error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    .page-hero { background: linear-gradient(120deg, var(--rsn-dark), #2c4a5e); color: #fff; padding: 1.75rem 2rem; border-radius: 14px; }
    .page-hero p { color: #c9d6e2; }
    .stat-card .stat-number { font-size: 2.2rem; font-weight: 700; color: var(--rsn-accent); line-height: 1.1; }
    .stat-card .stat-label { color: #5b6477; }
    .badge-status { font-weight: 600; padding: 0.4em 0.8em; border-radius: 999px; }
    .section-title { color: var(--rsn-accent); text-transform: uppercase; letter-spacing: 0.12em; font-size: 0.85rem; font-weight: 700; }
    /* travel cards: photo on top, text below; on phones a compact row */
    .trip-card { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(27, 35, 54, 0.07); height: 100%; transition: transform 0.2s, box-shadow 0.2s; display: block; color: inherit; }
    .trip-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(27, 35, 54, 0.12); text-decoration: none; color: inherit; }
    .photo-frame { position: relative; aspect-ratio: 4 / 3; background: linear-gradient(135deg, #2a9d8f, #264653); overflow: hidden; }
    .photo-frame.wide { aspect-ratio: 16 / 9; }
    .photo-frame img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .photo-frame .photo-name { position: absolute; left: 12px; bottom: 10px; color: #fff; font-weight: 700; text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4); }
    .price-tag { position: absolute; right: 10px; top: 10px; background: rgba(255, 255, 255, 0.95); color: var(--rsn-dark); font-weight: 700; border-radius: 999px; padding: 2px 12px; font-size: 0.85rem; }
    .trip-body { padding: 14px 16px; }
    .trip-body h5 { font-size: 1.05rem; font-weight: 700; margin-bottom: 4px; }
    .trip-body p { font-size: 0.9rem; }
    .meta { color: #6b7385; font-size: 0.85rem; }
    .stars { color: #f4a261; letter-spacing: 1px; }
    .hero-carousel .carousel-item { height: 520px; background: linear-gradient(135deg, #264653, #2a9d8f); }
    .hero-carousel .carousel-item img { width: 100%; height: 100%; object-fit: cover; }
    .hero-carousel .carousel-item::after { content: ''; position: absolute; inset: 0; background: linear-gradient(rgba(15, 30, 43, 0.35), rgba(15, 30, 43, 0.65)); }
    .hero-carousel .slide-name { position: absolute; right: 24px; bottom: 18px; color: #fff; z-index: 2; font-size: 0.9rem; opacity: 0.9; }
    .hero-text { position: absolute; inset: 0; z-index: 3; display: flex; flex-direction: column; justify-content: center; color: #fff; pointer-events: none; }
    .hero-text h1 { font-weight: 800; text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35); }
    .hero-text form { pointer-events: auto; }
    .search-box { background: rgba(255, 255, 255, 0.97); border-radius: 14px; padding: 14px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25); color: #2d3748; }
    .search-box label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #5b6477; margin-bottom: 2px; }
    .chip { display: inline-block; background: #e7f4f2; color: #23867a; border-radius: 999px; padding: 2px 10px; font-size: 0.8rem; margin: 0 4px 4px 0; }
    @media (max-width: 767px) {
      body { overflow-x: hidden; }
      .table { display: block; overflow-x: auto; white-space: nowrap; }
      .navbar { padding: 0.5rem 0.9rem; }
      .navbar-brand { order: 1; font-size: 1.2rem; margin-right: 0; }
      .navbar-toggler { order: 2; border-color: rgba(255, 255, 255, 0.55); padding: 0.25rem 0.55rem; }
      .navbar .nav-actions { order: 3; width: 100%; display: flex; align-items: center; gap: 6px; margin-top: 8px; }
      .navbar .nav-actions .btn { flex: 1; padding: 0.3rem 0.4rem; font-size: 0.85rem; margin: 0; }
      .navbar .navbar-collapse { order: 4; }
      .site-footer .footer-links a { display: inline-block; margin: 0 0.5rem 0.3rem; }
      .container { padding-left: 16px; padding-right: 16px; }
      .hero-carousel .carousel-item { height: 560px; }
      .hero-text h1 { font-size: 1.9rem; }
      /* compact row cards on phones: small photo on the left, text on the right */
      .trip-card { display: flex; align-items: stretch; }
      .trip-card:hover { transform: none; }
      .trip-card .photo-frame, .trip-card .photo-frame.wide { flex: none; width: 112px; aspect-ratio: auto; min-height: 112px; }
      .trip-card .photo-name { display: none; }
      .trip-card .trip-body { flex: 1; min-width: 0; padding: 10px 12px; }
      .trip-card .trip-body h5 { font-size: 0.98rem; }
      .trip-card .trip-body p { font-size: 0.83rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 4px; }
      .trip-card .price-tag { right: auto; left: 6px; top: auto; bottom: 6px; padding: 0 8px; font-size: 0.75rem; }
    }
    .site-footer { margin-top: 3rem; padding: 1.75rem 0; background: var(--rsn-dark); border-top: 3px solid var(--rsn-accent); color: #aeb9c9; font-size: 0.85rem; }
    .site-footer .footer-brand { color: #fff; font-weight: 700; font-size: 1.1rem; }
    .site-footer .footer-brand::before { content: '\2708  '; }
    .site-footer .footer-links a { color: #c9d6e2; margin: 0 0.6rem; }
    .site-footer .footer-links a:hover { color: var(--rsn-accent); text-decoration: none; }
  </style>
  <script>
    // turn plain status words inside table cells into coloured badges
    document.addEventListener('DOMContentLoaded', function () {
      const colors = { Pending: 'warning', Confirmed: 'info', Cancelled: 'danger', CheckedIn: 'primary', Completed: 'success', PAID: 'success', UNPAID: 'danger', Available: 'success', Occupied: 'danger', Cleaning: 'warning' }
      document.querySelectorAll('td').forEach(function (td) {
        const text = td.textContent.trim()
        if (td.children.length === 0 && colors[text]) {
          td.innerHTML = '<span class="badge badge-status badge-' + colors[text] + '">' + text + '</span>'
        }
      })
      document.querySelectorAll('span.badge:not(.badge-status)').forEach(function (b) {
        const text = b.textContent.trim()
        if (colors[text]) b.className = 'badge badge-status badge-' + colors[text]
      })
    })
  </script>
  @yield("scripts")
</html>
