@extends("base")

@section("content")
  <div class="container p-4">
    <div class="row">
      <div class="col-md-7">
        <h2>{{ $t["register"] }}</h2>
        <form method="post" action="{{ route('fregister') }}">
          @csrf
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="first_name">First name</label>
              <input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('first_name') }}" required />
            </div>
            <div class="form-group col-md-6">
              <label for="last_name">Last name</label>
              <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" />
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}" />
          </div>
          <div class="form-group">
            <label for="user_type">Account type (demo)</label>
            <select name="user_type" id="user_type" class="form-control">
              <option value="customer">Customer</option>
              <option value="staff">Hotel Staff</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control" id="password" required />
            </div>
            <div class="form-group col-md-6">
              <label for="password2">Confirm password</label>
              <input type="password" name="password2" class="form-control" id="password2" required />
            </div>
          </div>
          <button type="submit" class="btn btn-primary">{{ $t["register"] }}</button>
        </form>
      </div>
    </div>
  </div>
@endsection
