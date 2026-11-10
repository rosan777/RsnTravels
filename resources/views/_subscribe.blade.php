<section class="subscribe" style="background-image: url('{{ \App\Support\Photo::url(\App\Support\Photo::BANDS["bali"], 2000) }}')">
  <div class="container"><div class="subscribe-in reveal">
    <div class="band-kicker">{{ $lang == "bn" ? "নিউজলেটার" : "Newsletter" }}</div>
    <h2>{{ $lang == "bn" ? "নতুন অফার আগে জানুন" : "Hear about new trips first" }}</h2>
    <form class="form-inline justify-content-center" onsubmit="event.preventDefault(); this.innerHTML = '<span class=sub-ok><i class=\'fas fa-check-circle\'></i> {{ $lang == "bn" ? "ধন্যবাদ! (ডেমো, কিছু সংরক্ষণ হয়নি)" : "Thank you! (demo, nothing is stored)" }}</span>'">
      <input type="email" class="form-control mr-2 mb-2" placeholder="you@example.com" required />
      <button class="btn btn-cta mb-2">{{ $lang == "bn" ? "সাবস্ক্রাইব" : "Subscribe" }}</button>
    </form>
  </div></div>
</section>
