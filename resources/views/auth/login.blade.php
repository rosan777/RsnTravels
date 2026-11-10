@extends("base")

@section("content")
  <!-- login form -->
  <div class="container p-4">
    <div class="row">
      <div class="col-md-6">
        <h2>{{ $t["login"] }}</h2>
        <form method="post" action="{{ route('flogin') }}" name="loginForm">
          @csrf
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password" />
          </div>
          <button type="submit" class="btn btn-primary">{{ $t["login"] }}</button>
        </form>
      </div>
      <div class="col-md-6 mt-4 mt-md-0">
        <h5 class="mb-3">{{ $t["demo_roles_title"] }}</h5>
        <p class="text-muted small">{{ $t["demo_note"] }}</p>
        <div class="role-cards">
          @foreach ([["owner", "owner@example.com"], ["agent", "agent@example.com"], ["staff", "staff@example.com"], ["customer", "customer@example.com"]] as $demo)
            <button type="button" class="rc {{ $demo[0] }} demo-login" data-email="{{ $demo[1] }}">
              <span class="rt">{{ $t["roles"][$demo[0]][0] }}</span> <span class="rs">· {{ $t["roles"][$demo[0]][1] }}</span>
              <ul>@foreach ($t["roles"][$demo[0]][2] as $job)<li>{{ $job }}</li>@endforeach</ul>
            </button>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <script>
    // one click: fill the demo email and password, then log in
    document.querySelectorAll('.demo-login').forEach(function (b) {
      b.addEventListener('click', function () {
        document.loginForm.email.value = b.dataset.email
        document.loginForm.password.value = b.dataset.email
        document.loginForm.submit()
      })
    })
  </script>
@endsection
