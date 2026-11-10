@php
  $row1 = [["fab fa-php", "PHP 8.3"], ["fab fa-laravel", "Laravel 13"], ["fas fa-database", "PostgreSQL (Neon)"], ["fab fa-bootstrap", "Bootstrap 4"], ["fab fa-html5", "Blade templates"], ["fas fa-chart-bar", "Chart.js"], ["fas fa-vial", "PHPUnit"], ["fas fa-cloud", "Vercel"]];
  $row2 = [["fas fa-user-shield", "Role based access"], ["fas fa-calendar-check", "Date overlap booking"], ["fas fa-language", "English / বাংলা"], ["fas fa-heart", "Wishlist"], ["fas fa-shopping-cart", "Cart and checkout"], ["fas fa-file-invoice", "Invoices"], ["fas fa-camera", "Wikimedia photos"], ["fab fa-git-alt", "Git"]];
@endphp
<section class="tech-section">
  <div class="container text-center mb-3 reveal">
    <h3>{{ $t["tech_title"] }}</h3>
    <p class="mb-0">{{ $t["tech_text"] }}</p>
  </div>
  @foreach ([[$row1, ""], [$row2, "reverse"]] as $strip)
    <div class="strip mb-2">
      <div class="strip-track {{ $strip[1] }}">
        @for ($i = 0; $i < 2; $i++)
          @foreach ($strip[0] as $tech)
            <span class="tech-pill"><i class="{{ $tech[0] }}"></i>{{ $tech[1] }}</span>
          @endforeach
        @endfor
      </div>
    </div>
  @endforeach
</section>
