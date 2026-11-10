<style>
  :root { --navy: #0b2a43; --nav: #0a3d62; --accent: #0b83b5; --accent-dark: #086a93; --cta: #ff7f50; --gold: #f6c667; --sand: #fbf8f3; --c-owner: #13213c; --c-agent: #e8603c; --c-staff: #138a72; --c-customer: #0b6e99; }
  body { background: var(--sand); color: #2d3748; }
  a { color: var(--accent-dark); }
  a:hover { color: var(--accent); }
  .btn { border-radius: 8px; }
  .btn-primary { background: var(--accent); border-color: var(--accent); }
  .btn-primary:hover, .btn-primary:focus { background: var(--accent-dark); border-color: var(--accent-dark); }
  .btn-outline-primary { color: var(--accent-dark); border-color: var(--accent); }
  .btn-outline-primary:hover { background: var(--accent); border-color: var(--accent); color: #fff; }
  .btn-cta { background: var(--cta); border-color: var(--cta); color: #fff; font-weight: 600; }
  .btn-cta:hover { background: #f0683a; border-color: #f0683a; color: #fff; }
  .text-primary { color: var(--accent-dark) !important; }
  .form-control { border-radius: 8px; }
  .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 0.15rem rgba(11, 131, 181, 0.2); }


  /* role areas: each role has its own colour */
  body.role-owner { --role: var(--c-owner); --role-soft: #e8ebf3; }
  body.role-agent { --role: var(--c-agent); --role-soft: #fdebe5; }
  body.role-staff { --role: var(--c-staff); --role-soft: #e0f3ee; }
  body.role-customer, body.role-guest { --role: var(--c-customer); --role-soft: #e1f0f7; }
  .role-hero { position: relative; overflow: hidden; border-radius: 18px; padding: 1.6rem 1.8rem; color: #fff; background: linear-gradient(120deg, var(--role), color-mix(in srgb, var(--role) 55%, #0b2a43)); box-shadow: 0 8px 26px rgba(11, 42, 67, 0.18); }
  .role-hero::after { content: ""; position: absolute; right: -60px; top: -60px; width: 260px; height: 260px; border-radius: 50%; background: rgba(255, 255, 255, 0.08); }
  .role-hero h2 { font-weight: 800; margin: 0.4rem 0 0.2rem; }
  .role-hero .tag { color: rgba(255, 255, 255, 0.85); }
  .role-badge { display: inline-block; background: rgba(255, 255, 255, 0.18); border-radius: 999px; padding: 3px 14px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; }
  .job-list { list-style: none; padding: 0; margin: 0.8rem 0 0; }
  .job-list li { position: relative; padding-left: 26px; margin-bottom: 4px; font-size: 0.92rem; }
  .job-list li::before { content: "\f00c"; font-family: "Font Awesome 5 Free"; font-weight: 900; position: absolute; left: 0; top: 1px; font-size: 0.75rem; width: 18px; height: 18px; line-height: 18px; text-align: center; border-radius: 50%; background: rgba(255, 255, 255, 0.22); }
  .flow { position: relative; z-index: 1; display: flex; flex-direction: column; gap: 6px; }
  .flow .fs { display: flex; align-items: center; padding: 6px 12px; border-radius: 10px; background: rgba(255, 255, 255, 0.1); font-size: 0.85rem; }
  .flow .fs .n { flex: none; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; text-align: center; font-weight: 800; font-size: 0.78rem; background: rgba(255, 255, 255, 0.25); margin-right: 10px; }
  .flow .fs.me { background: #fff; color: var(--role); font-weight: 700; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2); }
  .flow .fs.me .n { background: var(--role); color: #fff; }
  .flow-title { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255, 255, 255, 0.75); margin-bottom: 6px; }
  body[class*="role-"] .stat-card .stat-number { color: var(--role); }
  body[class*="role-"] .stat-card { border-top: 4px solid var(--role); }
  body.role-owner .btn-primary, body.role-agent .btn-primary, body.role-staff .btn-primary { background: var(--role); border-color: var(--role); }
  body.role-owner .btn-outline-primary, body.role-agent .btn-outline-primary, body.role-staff .btn-outline-primary { color: var(--role); border-color: var(--role); }
  body.role-owner .btn-outline-primary:hover, body.role-agent .btn-outline-primary:hover, body.role-staff .btn-outline-primary:hover { background: var(--role); color: #fff; }
  .task-card { display: flex; align-items: flex-start; background: #fff; border-radius: 14px; padding: 12px 14px; margin-bottom: 10px; box-shadow: 0 2px 10px rgba(11, 42, 67, 0.06); border-left: 5px solid var(--role, var(--accent)); }
  .task-card .ticon { flex: none; width: 40px; height: 40px; line-height: 40px; text-align: center; border-radius: 12px; background: var(--role-soft, #e1f0f7); color: var(--role, var(--accent)); margin-right: 12px; }
  .task-card .tbody { flex: 1; min-width: 0; }
  .task-card.late { border-left-color: #e5484d; }
  .task-card.done { opacity: 0.6; }
  .bar { height: 10px; border-radius: 999px; background: #e9f1f6; overflow: hidden; }
  .bar > span { display: block; height: 100%; border-radius: 999px; }
  .member { display: flex; align-items: center; margin-bottom: 14px; }
  .avatar { flex: none; width: 42px; height: 42px; line-height: 42px; text-align: center; border-radius: 50%; color: #fff; font-weight: 800; margin-right: 12px; }
  .avatar.agent { background: var(--c-agent); }
  .avatar.staff { background: var(--c-staff); }
  .role-cards .rc { display: block; width: 100%; text-align: left; border: 0; border-radius: 16px; color: #fff; padding: 14px 16px; margin-bottom: 12px; box-shadow: 0 4px 16px rgba(11, 42, 67, 0.16); transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; }
  .role-cards .rc:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(11, 42, 67, 0.25); }
  .role-cards .rc .rt { font-weight: 800; font-size: 1.05rem; }
  .role-cards .rc .rs { font-size: 0.82rem; opacity: 0.9; }
  .role-cards .rc ul { margin: 8px 0 0; padding-left: 18px; font-size: 0.8rem; opacity: 0.95; }
  .rc.owner { background: linear-gradient(120deg, #13213c, #2a3f6b); }
  .rc.agent { background: linear-gradient(120deg, #e8603c, #f08a55); }
  .rc.staff { background: linear-gradient(120deg, #138a72, #35b496); }
  .rc.customer { background: linear-gradient(120deg, #0b6e99, #2ba0cf); }
  .bubble { border-radius: 14px; padding: 10px 14px; margin-bottom: 8px; max-width: 92%; }
  .bubble.me { background: #e1f0f7; }
  .bubble.agent { background: #fdebe5; margin-left: auto; }

  /* top bar */
  .navbar.bg-primary { background: var(--nav) !important; border-bottom: 3px solid var(--cta); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2); }
  .navbar-brand { font-weight: 800; font-size: 1.35rem; letter-spacing: 0.02em; }
  .navbar-brand i { color: var(--accent); margin-right: 6px; }
  .navbar .nav-link { border-radius: 6px; padding-left: 0.9rem; padding-right: 0.9rem; }
  .navbar .nav-link:hover, .navbar .nav-link.active { color: #fff; background: rgba(255, 255, 255, 0.1); }
  .nav-actions { display: flex; align-items: center; flex-wrap: wrap; }
  .nav-actions .btn { margin-left: 6px; }
  .icon-btn { position: relative; min-width: 44px; }
  .icon-btn .count { display: inline-block; margin-left: 4px; font-weight: 700; }

  /* cards, alerts, tables */
  .card { border: 0; border-radius: 14px; box-shadow: 0 3px 14px rgba(11, 42, 67, 0.06); }
  .alert { border: 0; border-radius: 10px; box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06); }
  .alert-error { color: #721c24; background: #f8d7da; }
  .table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(11, 42, 67, 0.05); }
  .table thead th { background: #e9f1f6; border: 0; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #5b6477; }
  .table td { vertical-align: middle; }
  .page-hero { background: linear-gradient(120deg, var(--navy), #145a7e); color: #fff; padding: 1.75rem 2rem; border-radius: 16px; position: relative; overflow: hidden; }
  .page-hero::after { content: "\f072"; font-family: "Font Awesome 5 Free"; font-weight: 900; position: absolute; right: 24px; top: -10px; font-size: 7rem; color: rgba(255, 255, 255, 0.06); transform: rotate(-12deg); }
  .page-hero p { color: #c9d6e2; }
  .stat-card .stat-number { white-space: nowrap; font-size: clamp(1.4rem, 2.1vw, 2.1rem); font-weight: 800; color: var(--accent-dark); line-height: 1.1; }
  .stat-card .stat-label { color: #5b6477; }
  .badge-status { font-weight: 600; padding: 0.4em 0.8em; border-radius: 999px; }
  .section-title { color: var(--accent-dark); text-transform: uppercase; letter-spacing: 0.14em; font-size: 0.8rem; font-weight: 700; }
  .chip { display: inline-block; background: #e1f0f7; color: #086a93; border-radius: 999px; padding: 2px 10px; font-size: 0.8rem; margin: 0 4px 4px 0; }

  /* travel cards */
  .trip-card { position: relative; background: #fff; border-radius: 16px; overflow: hidden; height: 100%; box-shadow: 0 3px 14px rgba(11, 42, 67, 0.07); transition: transform 0.25s ease, box-shadow 0.25s ease; }
  .trip-card:hover { transform: translateY(-6px); box-shadow: 0 14px 30px rgba(11, 42, 67, 0.16); }
  .trip-card .card-link { display: block; color: inherit; text-decoration: none; height: 100%; }
  .photo-frame { position: relative; aspect-ratio: 4 / 3; overflow: hidden; background: linear-gradient(135deg, #0b83b5, #145a7e); }
  .photo-frame.wide { aspect-ratio: 16 / 9; }
  .photo-frame img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.6s ease; }
  .trip-card:hover .photo-frame img { transform: scale(1.08); }
  .photo-frame::after { content: ""; position: absolute; inset: 0; background: linear-gradient(transparent 55%, rgba(11, 42, 67, 0.55)); pointer-events: none; }
  .photo-frame .photo-name { position: absolute; left: 12px; bottom: 10px; color: #fff; font-weight: 700; z-index: 2; text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5); }
  .price-tag { position: absolute; right: 10px; top: 10px; z-index: 2; background: var(--gold); color: #4a3a12; font-weight: 700; border-radius: 999px; padding: 2px 12px; font-size: 0.85rem; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2); }
  .trip-body { padding: 14px 16px 16px; }
  .trip-body h5 { font-size: 1.05rem; font-weight: 700; margin-bottom: 4px; }
  .trip-body p { font-size: 0.9rem; }
  .meta { color: #6b7385; font-size: 0.85rem; }
  .stars { color: #f4a640; letter-spacing: 1px; }
  .rating-pill { background: var(--accent); color: #fff; font-weight: 700; font-size: 0.78rem; border-radius: 6px; padding: 1px 7px; }

  /* heart */
  .heart-form { position: absolute; left: 10px; top: 10px; z-index: 5; margin: 0; }
  .heart-link { position: absolute; left: 10px; top: 10px; z-index: 5; }
  .heart-btn { width: 36px; height: 36px; border-radius: 50%; border: 0; background: rgba(255, 255, 255, 0.92); color: #6b7385; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25); transition: transform 0.2s, color 0.2s; cursor: pointer; }
  .heart-btn:hover { transform: scale(1.15); color: #e5484d; text-decoration: none; }
  .heart-btn.on { color: #e5484d; animation: pop 0.4s ease; }
  @keyframes pop { 50% { transform: scale(1.35); } }

  /* home hero: photo slideshow, typed place name, spinning round photo */
  .hero { position: relative; min-height: 640px; color: #fff; display: flex; align-items: center; overflow: hidden; background: var(--navy); }
  .hero-bg { position: absolute; inset: 0; background-size: cover; background-position: center; opacity: 0; transform: scale(1.08); transition: opacity 1.6s ease; }
  .hero-bg.on { opacity: 1; animation: kenburns 9s ease-out both; }
  @keyframes kenburns { from { transform: scale(1.08); } to { transform: scale(1); } }
  .hero::before { content: ""; position: absolute; inset: 0; z-index: 1; background: linear-gradient(100deg, rgba(11, 42, 67, 0.88) 15%, rgba(11, 42, 67, 0.35)); }
  .hero .container { position: relative; z-index: 2; }
  .hero h1 { font-weight: 800; font-size: 3.2rem; line-height: 1.15; }
  #typed-word { color: var(--gold); }
  .type-cursor { color: var(--gold); font-weight: 300; animation: blink 1s steps(1) infinite; }
  @keyframes blink { 50% { opacity: 0; } }
  .hero-round { width: 330px; height: 330px; border-radius: 50%; border: 6px solid rgba(255, 255, 255, 0.85); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45); overflow: hidden; margin-left: auto; animation: spin 60s linear infinite; }
  .hero-round img { width: 100%; height: 100%; object-fit: cover; }
  @keyframes spin { to { transform: rotate(360deg); } }
  .hero-caption { position: absolute; right: 24px; bottom: 16px; z-index: 3; font-size: 0.85rem; opacity: 0.85; }
  .hero-dots { position: absolute; left: 50%; bottom: 14px; transform: translateX(-50%); z-index: 3; }
  .hero-dots span { display: inline-block; width: 9px; height: 9px; border-radius: 50%; background: rgba(255, 255, 255, 0.4); margin: 0 4px; transition: background 0.3s, width 0.3s; }
  .hero-dots span.on { background: var(--gold); width: 24px; border-radius: 6px; }
  .search-box { background: rgba(255, 255, 255, 0.97); border-radius: 14px; padding: 14px; box-shadow: 0 10px 34px rgba(0, 0, 0, 0.3); color: #2d3748; }
  .search-box label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #5b6477; margin-bottom: 2px; }

  /* sliding strips */
  .strip { overflow: hidden; background: #0b0d12; padding: 14px 0; -webkit-mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); }
  .strip-track { display: flex; width: max-content; animation: slideLeft 45s linear infinite; }
  .strip-track.reverse { animation-name: slideRight; animation-duration: 38s; }
  .strip:hover .strip-track { animation-play-state: paused; }
  .chip-pill { flex: none; display: flex; align-items: center; margin-right: 14px; padding: 6px 20px 6px 6px; border-radius: 999px; background: #1f2630; border: 1px solid #333c4a; color: #c9d1e6; transition: border-color 0.2s, transform 0.2s, color 0.2s; }
  .chip-pill img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; }
  .chip-pill:hover { color: #fff; border-color: var(--accent); transform: translateY(-2px); text-decoration: none; }
  .tech-pill { flex: none; margin-right: 14px; padding: 9px 26px; border-radius: 999px; background: #1f2630; border: 1px solid #333c4a; color: #c9d1e6; transition: color 0.2s, border-color 0.2s, transform 0.2s; }
  .tech-pill i { color: var(--accent); margin-right: 8px; }
  .tech-pill:hover { color: #fff; border-color: var(--accent); transform: translateY(-2px); }
  .tech-section { background: #0b0d12; padding: 2.5rem 0 1.5rem; margin-top: 3rem; }
  .tech-section h3 { color: #fff; font-weight: 700; }
  .tech-section p { color: #9aa6c0; }
  @keyframes slideLeft { from { transform: translateX(0); } to { transform: translateX(-50%); } }
  @keyframes slideRight { from { transform: translateX(-50%); } to { transform: translateX(0); } }

  /* trust, steps, testimonials, faq */
  .trust-item { background: #fff; border-radius: 16px; padding: 22px 18px; text-align: center; box-shadow: 0 3px 14px rgba(11, 42, 67, 0.06); height: 100%; transition: transform 0.3s, background 0.3s; }
  .trust-item i { font-size: 2rem; color: var(--accent); margin-bottom: 10px; transition: color 0.3s; }
  .trust-item:hover { transform: translateY(-5px); background: var(--nav); color: #fff; }
  .trust-item:hover i { color: var(--gold); }
  .trust-item h6 { font-weight: 700; }
  .trust-item p { font-size: 0.85rem; margin: 0; opacity: 0.8; }
  .step { text-align: center; padding: 10px; }
  .step .num { width: 62px; height: 62px; line-height: 62px; border-radius: 50%; background: var(--accent); color: #fff; font-size: 1.5rem; font-weight: 800; margin: 0 auto 10px; box-shadow: 0 0 0 8px rgba(11, 131, 181, 0.15); }
  .quote-card { background: #fff; border-radius: 16px; padding: 20px; height: 100%; box-shadow: 0 3px 14px rgba(11, 42, 67, 0.06); }
  .quote-card .fa-quote-left { color: var(--gold); font-size: 1.6rem; }
  .counter-band { background: linear-gradient(120deg, var(--navy), #145a7e); color: #fff; border-radius: 18px; padding: 26px 10px; }
  .counter-band .big { font-size: 2.2rem; font-weight: 800; color: var(--gold); }
  .faq .card-header { background: #fff; cursor: pointer; font-weight: 600; border-radius: 12px; }
  .faq .card { margin-bottom: 8px; }

  /* scroll reveal */
  .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.7s ease, transform 0.7s ease; }
  .reveal.in { opacity: 1; transform: none; }

  /* detail pages */
  .gallery-main { border-radius: 16px; overflow: hidden; box-shadow: 0 6px 24px rgba(11, 42, 67, 0.15); }
  .booking-box { position: sticky; top: 80px; }
  .timeline { display: flex; justify-content: space-between; position: relative; margin: 12px 0; }
  .timeline::before { content: ""; position: absolute; left: 8%; right: 8%; top: 13px; height: 3px; background: #dfe5ec; }
  .timeline .tl { position: relative; text-align: center; flex: 1; font-size: 0.75rem; color: #8a93a6; }
  .timeline .tl .dot { width: 28px; height: 28px; border-radius: 50%; background: #dfe5ec; color: #fff; line-height: 28px; margin: 0 auto 4px; position: relative; z-index: 1; font-size: 0.8rem; }
  .timeline .tl.done { color: #086a93; font-weight: 600; }
  .timeline .tl.done .dot { background: var(--accent); }
  .timeline .tl.bad .dot { background: #e5484d; }

  .site-footer { margin-top: 0; padding: 2rem 0 1.5rem; background: var(--nav); border-top: 3px solid var(--cta); color: #b9cadb; font-size: 0.85rem; }
  .site-footer .footer-brand { color: #fff; font-weight: 800; font-size: 1.15rem; }
  .site-footer .footer-links a { color: #c9d6e2; margin: 0 0.6rem; }
  .site-footer .footer-links a:hover { color: var(--accent); text-decoration: none; }

  @media (max-width: 991px) { .hero h1 { font-size: 2.4rem; } .hero-round { display: none; } .hero { min-height: 600px; } }
  @media (max-width: 767px) {
    body { overflow-x: hidden; }
    .container { padding-left: 16px; padding-right: 16px; }
    .table { display: block; overflow-x: auto; white-space: nowrap; }
    .navbar { padding: 0.5rem 0.9rem; }
    .navbar-brand { order: 1; font-size: 1.2rem; margin-right: 0; }
    .navbar-toggler { order: 2; border-color: rgba(255, 255, 255, 0.55); padding: 0.25rem 0.55rem; }
    .navbar .nav-actions { order: 3; width: 100%; margin-top: 8px; flex-wrap: nowrap; gap: 6px; }
    .navbar .nav-actions .btn { flex: 1; padding: 0.3rem 0.3rem; font-size: 0.82rem; margin: 0; }
    .navbar .navbar-collapse { order: 4; }
    .site-footer .footer-links a { display: inline-block; margin: 0 0.5rem 0.3rem; }
    .hero h1 { font-size: 1.9rem; }
    .hero { min-height: 680px; }
    .booking-box { position: static; }
    /* compact row cards on phones: small photo on the left, text on the right */
    .trip-card:hover { transform: none; }
    .trip-card .card-link { display: flex; align-items: stretch; }
    .trip-card .photo-frame, .trip-card .photo-frame.wide { flex: none; width: 118px; aspect-ratio: auto; min-height: 118px; }
    .trip-card .photo-name { display: none; }
    .trip-card .trip-body { flex: 1; min-width: 0; padding: 10px 12px; }
    .trip-card .trip-body h5 { font-size: 0.98rem; padding-right: 0; }
    .trip-card .trip-body p { font-size: 0.83rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 4px; }
    .trip-card .price-tag { right: auto; left: auto; top: auto; bottom: 6px; left: 6px; padding: 0 8px; font-size: 0.75rem; }
    .heart-form, .heart-link { left: auto; right: 8px; top: 8px; }
    .heart-btn { width: 30px; height: 30px; font-size: 0.85rem; }
  }
  @media (prefers-reduced-motion: reduce) { .strip-track, .hero-round, .type-cursor, .hero-bg.on { animation: none; } .reveal { opacity: 1; transform: none; } }
</style>
