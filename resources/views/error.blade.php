@extends("base")

@section("content")
  <div class="container py-5 text-center">
    <h1 class="display-4 text-primary">&#9992;</h1>
    @if ($error ?? null)
      <div class="alert alert-danger d-inline-block" role="alert">{{ $error }}</div>
    @else
      <div class="alert alert-success d-inline-block" role="alert">You have successfully registered!</div>
    @endif
    <div class="mt-3">
      <a href="{{ route('index') }}" class="btn btn-primary">{{ $t["home"] }}</a>
    </div>
  </div>
@endsection
