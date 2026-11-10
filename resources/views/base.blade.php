<!DOCTYPE html>
<html lang="{{ $lang }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? "TravelDemo" }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" />
    @include("_styles")
  </head>

  <body class="role-{{ $userEx ? $userEx->user_type : 'guest' }}">
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary justify-content-between">
      <a class="navbar-brand" href="{{ route('index') }}"><i class="fas fa-plane-departure"></i>TravelDemo</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav" id="nav-items">
          @if ($userEx)
            <li class="nav-item">
              <a class="nav-link" href="{{ route('dashboard') }}">{{ $t["dashboard"] }}</a>
            </li>
            @if ($userEx->user_type == "owner")
              <li class="nav-item"><a class="nav-link" href="{{ route('owner_bookings') }}">{{ $t["bookings"] }}@if ($pendingApprovals ?? 0) <span class="badge badge-warning">{{ $pendingApprovals }}</span>@endif</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('owner_team') }}">{{ $t["team"] }}</a></li>
            @elseif ($userEx->user_type == "agent")
              <li class="nav-item"><a class="nav-link" href="{{ route('agent_dashboard') }}">{{ $t["my_tasks"] }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('agent_inquiries') }}">{{ $t["inquiries"] }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('agent_bookings') }}">{{ $t["bookings"] }}</a></li>
            @elseif ($userEx->user_type == "staff")
              <li class="nav-item"><a class="nav-link" href="{{ route('staff_tasks') }}">{{ $t["my_tasks"] }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('staff_bookings') }}">{{ $t["bookings"] }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('staff_rooms') }}">{{ $t["room_board"] }}</a></li>
            @else
              <li class="nav-item"><a class="nav-link" href="{{ route('customer_bookings') }}">{{ $t["my_bookings"] }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('customer_inquiries') }}">{{ $t["ask_agent"] }}</a></li>
            @endif
          @endif
          <li class="nav-item"><a class="nav-link" href="{{ route('view_destinations') }}">{{ $t["destinations"] }}</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('view_packages') }}">{{ $t["packages"] }}</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('view_hotels') }}">{{ $t["hotels"] }}</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('offers') }}">{{ $t["offers"] }}</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('project_profile') }}">{{ $t["project_profile"] }}</a></li>
        </ul>
      </div>
      <div class="nav-actions">
        @if (!$userEx || $userEx->user_type == "customer")
          <a href="{{ route('customer_favorites') }}" class="btn btn-outline-light icon-btn" title="{{ $t['favorites'] }}"><i class="fas fa-heart"></i><span class="count">{{ count($favKeys) }}</span></a>
          <a href="{{ route('customer_cart') }}" class="btn btn-outline-light icon-btn" title="{{ $t['cart'] }}"><i class="fas fa-shopping-cart"></i><span class="count">{{ $cartCount }}</span></a>
        @endif
        <a href="{{ route('set_language', $lang == 'en' ? 'bn' : 'en') }}" class="btn btn-outline-light">{{ $t["other_language"] }}</a>
        @auth
          <a href="{{ route('flogout') }}" class="btn btn-outline-light">{{ $t["logout"] }}</a>
        @else
          <a href="{{ route('flogin') }}" class="btn btn-outline-light">{{ $t["login"] }}</a>
          <a href="{{ route('fregister') }}" class="btn btn-outline-light">{{ $t["register"] }}</a>
        @endauth
      </div>
    </nav>

    @if (session("success") || session("error"))
      <div class="container w-full pt-3">
        @if (session("success"))
          <div class="alert alert-success mb-0"><i class="fas fa-check-circle"></i> {{ session("success") }}</div>
        @endif
        @if (session("error"))
          <div class="alert alert-error mb-0"><i class="fas fa-exclamation-circle"></i> {{ session("error") }}</div>
        @endif
      </div>
    @endif

    @yield("content")

    @hasSection("no_tech")
    @else
      @include("_tech_strip")
    @endif

    <footer class="site-footer">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="text-center text-md-left mb-2 mb-md-0">
          <span class="footer-brand"><i class="fas fa-plane-departure"></i> TravelDemo</span>
          <div>{{ $t["footer"] }}</div>
        </div>
        <div class="footer-links mb-2 mb-md-0 text-center">
          <a href="{{ route('index') }}">{{ $t["home"] }}</a>
          <a href="{{ route('view_destinations') }}">{{ $t["destinations"] }}</a>
          <a href="{{ route('project_profile') }}">{{ $t["project_profile"] }}</a>
          @auth
            <a href="{{ route('dashboard') }}">{{ $t["dashboard"] }}</a>
          @else
            <a href="{{ route('flogin') }}">{{ $t["login"] }}</a>
          @endauth
        </div>
        <div class="text-center text-md-right">© 2024 OANRO. All rights reserved.</div>
      </div>
    </footer>
  </body>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  @include("_scripts")
  @yield("scripts")
</html>
