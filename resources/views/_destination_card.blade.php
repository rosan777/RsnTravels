<div class="trip-card">
  <a class="card-link" href="{{ route('view_destination', $destination->id) }}">
    <div class="photo-frame {{ $wide ?? false ? 'wide' : '' }}">
      @if ($destination->picture_url)
        <img src="{{ $destination->picture_url }}" alt="{{ $destination->name }}" loading="lazy" />
      @endif
      <span class="photo-name">{{ $destination->name }}, {{ $destination->country }}</span>
    </div>
    <div class="trip-body">
      <h5>{{ $destination->name }}</h5>
      <p class="text-muted mb-1">{{ $destination->description }}</p>
      <div class="meta"><i class="far fa-calendar-alt"></i> {{ $destination->best_time }}</div>
    </div>
  </a>
  @include("_heart", ["type" => "destination", "id" => $destination->id])
</div>
