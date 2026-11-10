@extends("base")

@section("content")
  <div class="container py-4">
    <div class="page-hero mb-4">
      <h2 class="mb-1">{{ $t["packages"] }}</h2>
    </div>
    <form method="get" class="card card-body mb-4">
      <div class="form-row">
        <div class="col-md-3 mb-2">
          <select name="destination" class="form-control">
            <option value="">{{ $t["destinations"] }}: {{ $t["any"] }}</option>
            @foreach ($destinations as $destination)
              <option value="{{ $destination->id }}" {{ request('destination') == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <input type="number" name="budget" class="form-control" placeholder="{{ $t['budget'] }} (৳)" value="{{ request('budget') }}" min="0" />
        </div>
        <div class="col-6 col-md-2 mb-2">
          <input type="number" name="days" class="form-control" placeholder="Max {{ $t['days'] }}" value="{{ request('days') }}" min="1" />
        </div>
        <div class="col-6 col-md-2 mb-2">
          <select name="interest" class="form-control">
            <option value="">{{ $t["any"] }}</option>
            @foreach ($interests as $interest)
              <option value="{{ $interest[0] }}" {{ request('interest') == $interest[0] ? 'selected' : '' }}>{{ $interest[1] }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <button class="btn btn-primary" type="submit">{{ $t["filter"] }}</button>
          <a href="{{ route('view_packages') }}" class="btn btn-link">{{ $t["reset"] }}</a>
        </div>

        <div class="col-6 col-md-2 mb-2">
          <select name="sort" class="form-control" onchange="this.form.submit()">
            <option value="">{{ $t["sort"] }}</option>
            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ $t["price_high"] }}</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ $t["top_rated"] }}</option>
          </select>
        </div>
      </div>
    </form>
    <div class="row">
      @forelse ($items as $package)
        <div class="col-lg-4 col-md-6 mb-4 reveal">
          @include("_package_card")
        </div>
      @empty
        <p class="col text-muted">{{ $t["nothing_found"] }}</p>
      @endforelse
    </div>
  </div>
@endsection
