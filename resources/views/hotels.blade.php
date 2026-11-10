@extends("base")

@section("content")
  <div class="container py-4">
    <div class="page-hero mb-4">
      <h2 class="mb-1">{{ $t["hotels"] }}</h2>
    </div>
    <form method="get" class="card card-body mb-4">
      <div class="form-row">
        <div class="col-md-4 mb-2">
          <select name="city" class="form-control">
            <option value="">{{ $t["city"] }}: {{ $t["any"] }}</option>
            @foreach ($cities as $city)
              <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-2 mb-2">
          <select name="stars" class="form-control">
            <option value="">{{ $t["stars"] }}: {{ $t["any"] }}</option>
            @foreach ([3, 4, 5] as $s)
              <option value="{{ $s }}" {{ request('stars') == $s ? 'selected' : '' }}>{{ $s }}+ ★</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <input type="number" name="budget" class="form-control" placeholder="{{ $t['budget'] }} / {{ $t['per_night'] }}" value="{{ request('budget') }}" min="0" />
        </div>
        <div class="col-md-3 mb-2">
          <button class="btn btn-primary" type="submit">{{ $t["filter"] }}</button>
          <a href="{{ route('view_hotels') }}" class="btn btn-link">{{ $t["reset"] }}</a>
        </div>

        <div class="col-6 col-md-2 mb-2">
          <select name="sort" class="form-control" onchange="this.form.submit()">
            <option value="">{{ $t["sort"] }}</option>
            
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ $t["top_rated"] }}</option>
          </select>
        </div>
      </div>
    </form>
    <div class="row">
      @forelse ($items as $hotel)
        <div class="col-lg-4 col-md-6 mb-4 reveal">
          @include("_hotel_card")
        </div>
      @empty
        <p class="col text-muted">{{ $t["nothing_found"] }}</p>
      @endforelse
    </div>
  </div>
@endsection
