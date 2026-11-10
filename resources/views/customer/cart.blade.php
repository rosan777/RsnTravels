@extends("customer.customer-base")

@section("area_title", $t["cart"])

@section("customer_content")
  <div class="container">
    @if (!$lines)
      <div class="card card-body text-center py-5">
        <div class="display-4 text-muted"><i class="fas fa-shopping-cart"></i></div>
        <h4 class="mt-2">{{ $t["cart_empty"] }}</h4>
        <div class="mt-2">
          <a href="{{ route('view_hotels') }}" class="btn btn-primary">{{ $t["hotels"] }}</a>
          <a href="{{ route('view_packages') }}" class="btn btn-outline-primary">{{ $t["packages"] }}</a>
        </div>
      </div>
    @else
      <div class="row">
        <div class="col-lg-8 mb-3">
          @foreach ($lines as $line)
            <div class="card mb-3">
              <div class="card-body d-flex align-items-center">
                <div style="width: 96px; height: 80px; flex: none; border-radius: 10px; overflow: hidden; background: #dfe5ec" class="mr-3">
                  @if ($line["picture"])<img src="{{ $line['picture'] }}" alt="" style="width:100%;height:100%;object-fit:cover" />@endif
                </div>
                <div class="flex-grow-1">
                  <span class="badge badge-info">{{ $line["type"] == "hotel" ? $t["hotel"] : $t["package"] }}</span>
                  <h6 class="mb-0 mt-1">{{ $line["title"] }}</h6>
                  <div class="meta">{{ $line["detail"] }}</div>
                </div>
                <div class="text-right ml-2">
                  <div class="font-weight-bold">৳{{ number_format($line["total"]) }}</div>
                  <form method="post" action="{{ route('customer_remove_from_cart', $line['key']) }}">
                    @csrf
                    <button class="btn btn-sm btn-link text-danger p-0"><i class="fas fa-trash"></i> {{ $t["remove"] }}</button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="col-lg-4">
          <form method="post" action="{{ route('customer_checkout') }}" class="card card-body booking-box">
            @csrf
            <h5>{{ $t["summary"] }}</h5>
            <div class="d-flex justify-content-between"><span>{{ $t["items"] }}</span><span>{{ count($lines) }}</span></div>
            <div class="d-flex justify-content-between font-weight-bold h5 mt-2"><span>{{ $t["total"] }}</span><span>৳{{ number_format($total) }}</span></div>
            <hr />
            <label class="small font-weight-bold">{{ $t["payment_method"] }}</label>
            @foreach (App\Models\Booking::PAYMENT_METHODS as $m)
              <div class="custom-control custom-radio">
                <input type="radio" id="pm{{ $m[0] }}" name="payment_method" value="{{ $m[0] }}" class="custom-control-input" {{ $loop->first ? "checked" : "" }} />
                <label class="custom-control-label" for="pm{{ $m[0] }}">{{ $m[1] }}</label>
              </div>
            @endforeach
            <button class="btn btn-cta btn-block mt-3" type="submit"><i class="fas fa-lock"></i> {{ $t["place_booking"] }}</button>
            <div class="small text-muted mt-2"><i class="fas fa-info-circle"></i> {{ $t["checkout_note"] }}</div>
          </form>
        </div>
      </div>
    @endif
  </div>
@endsection
