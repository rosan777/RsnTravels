{{-- Coloured header for every role area: who you are, your job, and where you are in a booking's journey --}}
@php
  $role = $userEx->user_type;
  $info = $t["roles"][$role];
  $step = ["customer" => 0, "agent" => 1, "owner" => 2, "staff" => 3][$role];
  $icon = ["owner" => "fa-crown", "agent" => "fa-headset", "staff" => "fa-concierge-bell", "customer" => "fa-suitcase-rolling"][$role];
@endphp
<div class="container pt-4">
  <div class="role-hero mb-4">
    <div class="row">
      <div class="col-lg-7 mb-3 mb-lg-0" style="position: relative; z-index: 1">
        <span class="role-badge"><i class="fas {{ $icon }}"></i> {{ $info[0] }}</span>
        <h2>@yield("area_title")</h2>
        <div class="tag">{{ $info[1] }}</div>
        <div class="flow-title mt-3">{{ $t["your_job"] }}</div>
        <ul class="job-list">
          @foreach ($info[2] as $job)
            <li>{{ $job }}</li>
          @endforeach
        </ul>
      </div>
      <div class="col-lg-5">
        <div class="flow">
          <div class="flow-title">{{ $t["how_flow"] }}</div>
          @foreach ($t["flow_steps"] as $i => $label)
            <div class="fs {{ $i == $step || ($role == "staff" && $i == 4) ? "me" : "" }}"><span class="n">{{ $i + 1 }}</span>{{ $label }}</div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
