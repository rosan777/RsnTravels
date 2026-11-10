@extends("base")

@section("content")
  @php $bn = $lang == "bn"; @endphp
  <section class="page-banner" style="background-image: url('{{ \App\Support\Photo::url(\App\Support\Photo::BANDS["kyoto"], 2000) }}')">
    <div class="container reveal">
      <div class="band-kicker">{{ $bn ? "আমাদের কথা" : "About us" }}</div>
      <h1>{{ $bn ? "এক জায়গায় পুরো ভ্রমণ" : "The whole trip in one place" }}</h1>
      <p>{{ $bn ? "প্যাকেজ, হোটেল, রুম আর সাহায্য - সব একসাথে।" : "Packages, hotels, rooms and help, together." }}</p>
    </div>
  </section>

  <div class="container py-5">
    <div class="row text-center">
      @foreach ([[$bn ? "ভ্রমণকারী" : "Traveller", "fa-suitcase-rolling", $bn ? "খুঁজে, বুক করে, রিভিউ দেয়" : "Searches, books and reviews"], [$bn ? "এজেন্ট" : "Agent", "fa-headset", $bn ? "প্রশ্নের উত্তর ও ফলো-আপ" : "Answers questions and follows up"], [$bn ? "হোটেল স্টাফ" : "Hotel staff", "fa-concierge-bell", $bn ? "চেক-ইন, রুম, হাউসকিপিং" : "Check-in, rooms, housekeeping"], [$bn ? "মালিক" : "Owner", "fa-user-tie", $bn ? "অনুমোদন, টিম, আয়" : "Approvals, team and revenue"]] as $r)
        <div class="col-6 col-md-3 mb-3 reveal"><div class="offer"><i class="fas {{ $r[1] }}"></i><h5>{{ $r[0] }}</h5><p>{{ $r[2] }}</p></div></div>
      @endforeach
    </div>
  </div>

  @include("_band", ["photo" => "machu", "right" => true, "kicker" => $bn ? "ডেমো থেকে বাস্তবে" : "From demo to real", "title" => $bn ? "আপনার ট্রাভেল ব্যবসার জন্য তৈরি" : "Ready for your travel business", "text" => $bn ? "আপনার নাম, দাম আর ছবি দিয়ে সাজিয়ে দিই।" : "We can fit it with your name, prices and photos.", "href" => "https://oanro.com/contact", "cta" => $bn ? "যোগাযোগ করুন" : "Contact us"])
@endsection
