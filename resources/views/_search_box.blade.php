<form method="get" action="{{ route('search_rooms') }}" class="search-box">
  <div class="form-row">
    <div class="col-md-4 mb-2 mb-md-0">
      <label for="where">{{ $t["where_to"] }}</label>
      <input type="text" name="where" id="where" class="form-control" placeholder="Bali, Cox's Bazar..." value="{{ $where ?? '' }}" />
    </div>
    <div class="col-6 col-md-3 mb-2 mb-md-0">
      <label for="check_in">{{ $t["check_in"] }}</label>
      <input type="date" name="check_in" id="check_in" class="form-control" min="{{ now()->toDateString() }}" value="{{ $check_in ?? now()->addDays(7)->toDateString() }}" required />
    </div>
    <div class="col-6 col-md-3 mb-2 mb-md-0">
      <label for="check_out">{{ $t["check_out"] }}</label>
      <input type="date" name="check_out" id="check_out" class="form-control" min="{{ now()->addDay()->toDateString() }}" value="{{ $check_out ?? now()->addDays(10)->toDateString() }}" required />
    </div>
    <div class="col-6 col-md-1 mb-2 mb-md-0">
      <label for="guests">{{ $t["guests"] }}</label>
      <input type="number" name="guests" id="guests" class="form-control" min="1" max="10" value="{{ $guests ?? 2 }}" />
    </div>
    <div class="col-6 col-md-1 d-flex align-items-end">
      <button type="submit" class="btn btn-primary btn-block">{{ $t["search"] }}</button>
    </div>
  </div>
</form>
