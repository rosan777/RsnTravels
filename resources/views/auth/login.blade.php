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
        <div class="card">
          <div class="card-header">
            <h3>{{ $t["demo_logins"] }}</h3>
          </div>
          <div class="card-body">
            <p class="text-muted small">{{ $t["demo_note"] }}</p>
            @foreach ([["owner@example.com", "Agency Owner"], ["staff@example.com", "Hotel Staff"], ["customer@example.com", "Customer"]] as $demo)
              <button type="button" class="btn btn-info btn-block text-left demo-login" data-email="{{ $demo[0] }}">
                <strong>{{ $demo[1] }}</strong> <span class="float-right small">{{ $demo[0] }}</span>
              </button>
            @endforeach
          </div>
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
