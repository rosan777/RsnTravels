{{-- Heart button: $type = hotel | package | destination --}}
@php($is_fav = in_array($type . ":" . $id, $favKeys))
@auth
  <form method="post" action="{{ route('toggle_favorite') }}" class="heart-form">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}" />
    <input type="hidden" name="id" value="{{ $id }}" />
    <button type="submit" class="heart-btn {{ $is_fav ? 'on' : '' }}" title="{{ $t['favorites'] }}"><i class="{{ $is_fav ? 'fas' : 'far' }} fa-heart"></i></button>
  </form>
@else
  <a href="{{ route('flogin') }}" class="heart-btn heart-link" title="{{ $t['login_to_save'] }}"><i class="far fa-heart"></i></a>
@endauth
