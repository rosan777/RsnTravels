<section class="band {{ $right ?? false ? 'right' : '' }}" style="background-image: url('{{ \App\Support\Photo::url(\App\Support\Photo::BANDS[$photo], 2000) }}')">
  <div class="container"><div class="band-in reveal">
    <div class="band-kicker">{{ $kicker }}</div>
    <h2>{{ $title }}</h2>
    <p>{{ $text }}</p>
    <a class="btn btn-cta" href="{{ $href }}" @if (str_starts_with($href, "http")) target="_blank" rel="noopener" @endif>{{ $cta }}</a>
  </div></div>
</section>
