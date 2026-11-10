@extends("customer.customer-base")

@section("area_title", $t["favorites"])

@section("customer_content")
  <div class="container">
    @if ($destinations->isEmpty() && $packages->isEmpty() && $hotels->isEmpty())
      <div class="card card-body text-center py-5"><div class="display-4 text-muted"><i class="far fa-heart"></i></div><h5 class="mt-2">{{ $t["no_favorites"] }}</h5></div>
    @endif
    @if ($destinations->count())
      <h4>{{ $t["destinations"] }}</h4>
      <div class="row">@foreach ($destinations as $destination)<div class="col-lg-4 col-md-6 mb-4">@include("_destination_card", ["wide" => true])</div>@endforeach</div>
    @endif
    @if ($packages->count())
      <h4>{{ $t["packages"] }}</h4>
      <div class="row">@foreach ($packages as $package)<div class="col-lg-4 col-md-6 mb-4">@include("_package_card")</div>@endforeach</div>
    @endif
    @if ($hotels->count())
      <h4>{{ $t["hotels"] }}</h4>
      <div class="row">@foreach ($hotels as $hotel)<div class="col-lg-4 col-md-6 mb-4">@include("_hotel_card")</div>@endforeach</div>
    @endif
  </div>
@endsection
