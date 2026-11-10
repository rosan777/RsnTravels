@extends("owner.owner-base")

@section("area_title", $t["catalog"])

@section("owner_content")
  <div class="container">
    <p class="text-muted">{{ $t["catalog_note"] }}</p>
    @foreach ([["destinations", $destinations, "destination"], ["hotels", $hotels, "hotel"], ["packages", $packages, "package"]] as $group)
      <h4 class="mt-3">{{ $t[$group[0]] }}</h4>
      <div class="table-responsive"><table class="table">
        <tbody>
          @foreach ($group[1] as $item)
            <tr>
              <td>{{ $item->name }}</td>
              <td>{{ $item->show_on_site ? $t["shown"] : $t["hidden"] }}</td>
              <td class="text-right">
                <form method="post" action="{{ route('owner_toggle_item') }}">@csrf
                  <input type="hidden" name="type" value="{{ $group[2] }}" /><input type="hidden" name="id" value="{{ $item->id }}" />
                  <button class="btn btn-sm {{ $item->show_on_site ? 'btn-outline-secondary' : 'btn-primary' }}">{{ $item->show_on_site ? $t["hide"] : $t["show"] }}</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table></div>
    @endforeach
  </div>
@endsection
