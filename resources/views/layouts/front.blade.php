<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@php $appName = config('app.name', 'Cloud Nest Store'); @endphp
	<title>@yield('title', $appName)</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<style>
		:root {
			--gs-bg: #062f6d;
			--gs-bg-deep: #04275d;
			--gs-topbar: #073e89;
			--gs-menubar: #0d4ca8;
			--gs-card: #0e4d7d;
			--gs-card-dark: #083b67;
			--gs-border: rgba(255, 255, 255, .14);
			--gs-text: #ffffff;
			--gs-muted: rgba(255, 255, 255, .72);
			--gs-orange: #ff7a00;
			--gs-red: #ff4248;
			--gs-button: #ff4545;
			--gs-shadow: 0 18px 40px rgba(0, 0, 0, .18);
			--gs-radius: 14px;
		}

		* { box-sizing: border-box; }

		html { scroll-behavior: smooth; }

		body {
			min-height: 100vh;
			margin: 0;
			font-family: 'Poppins', sans-serif;
			background: var(--gs-bg);
			color: var(--gs-text);
			display: flex;
			flex-direction: column;
			overflow-x: hidden;
		}

		a { color: inherit; }
		img { max-width: 100%; }

		.container {
			width: min(100% - 32px, 1180px);
			max-width: 1180px;
			padding-left: 0;
			padding-right: 0;
		}

		.gs-main {
			flex: 1;
			padding: 34px 0 86px;
			background:
				radial-gradient(circle at 20% 0%, rgba(0, 138, 255, .12), transparent 34%),
				linear-gradient(180deg, var(--gs-bg) 0%, var(--gs-bg-deep) 100%);
		}

		/* Header */
		.gs-header {
			position: sticky;
			top: 0;
			z-index: 1040;
			box-shadow: 0 10px 25px rgba(0, 0, 0, .12);
		}

		.gs-header-top {
			background:
				radial-gradient(circle at 18% 10%, rgba(0, 188, 212, .18), transparent 28%),
				linear-gradient(180deg, #073f8d 0%, var(--gs-topbar) 100%);
		}
		.gs-header-menu { background: var(--gs-menubar); }

		.gs-header-inner {
			height: 78px;
			display: flex;
			align-items: center;
			gap: 22px;
		}

		.gs-logo {
			flex: 0 0 218px;
			min-width: 218px;
			display: inline-flex;
			align-items: center;
			gap: 12px;
			text-decoration: none;
			color: #fff;
		}

		.gs-logo-mark {
			/* width: 70px;
			height: 54px; */
			padding: 10px 8px;
			border-radius: 18px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			position: relative;
			overflow: hidden;
			background: linear-gradient(145deg, rgba(255,255,255,.98) 0%, rgba(224,246,255,.94) 62%, rgba(175,226,255,.88) 100%);
			border: 1px solid rgba(125, 211, 252, .55);
			box-shadow:
				0 12px 28px rgba(0, 0, 0, .20),
				inset 0 1px 0 rgba(255, 255, 255, .86);
		}

		.gs-logo-mark:before {
			content: '';
			position: absolute;
			inset: -42% -18% auto auto;
			width: 54px;
			height: 54px;
			border-radius: 999px;
			background: rgba(0, 190, 214, .24);
		}

		.gs-logo-mark img {
			max-height: 42px;
			width: auto;
			object-fit: contain;
			position: relative;
			z-index: 1;
			filter: drop-shadow(0 4px 7px rgba(3, 22, 54, .16));
		}

		.gs-logo-fallback {
			align-items: center;
			justify-content: center;
			position: relative;
			z-index: 1;
			font-size: 1rem;
			font-weight: 900;
			line-height: 1;
			letter-spacing: -.04em;
			color: #073e89;
		}

		.gs-logo-copy {
			display: flex;
			flex-direction: column;
			line-height: 1.05;
			text-transform: uppercase;
			letter-spacing: -.02em;
			text-shadow: 0 2px 10px rgba(0, 0, 0, .18);
		}

		.gs-logo-copy strong {
			font-size: .98rem;
			font-weight: 900;
			color: #fff;
		}

		.gs-logo-copy small {
			margin-top: 2px;
			font-size: .74rem;
			font-weight: 800;
			letter-spacing: .08em;
			color: #7dd3fc;
		}

		.gs-search {
			flex: 1 1 520px;
			position: relative;
			max-width: 745px;
		}

		.gs-search input {
			width: 100%;
			height: 50px;
			border: 0;
			outline: none;
			border-radius: 10px;
			background: #fff;
			color: #0d1b2a;
			font-size: .94rem;
			padding: 0 52px 0 24px;
			box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .04);
		}

		.gs-search button {
			position: absolute;
			top: 50%;
			right: 14px;
			transform: translateY(-50%);
			border: 0;
			background: transparent;
			color: #000;
			font-size: 1.35rem;
			line-height: 1;
		}

		.gs-actions {
			display: flex;
			align-items: center;
			gap: 14px;
			margin-left: auto;
		}

		.gs-icon-btn,
		.gs-coin-btn {
			height: 50px;
			border-radius: 11px;
			border: 1px solid rgba(255, 255, 255, .4);
			background: rgba(255, 255, 255, .06);
			color: #fff;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			text-decoration: none;
			transition: transform .2s ease, background .2s ease;
		}

		.gs-icon-btn { width: 50px; border: 0; background: transparent; font-size: 2.15rem; }
		.gs-coin-btn { min-width: 72px; gap: 8px; padding: 0 13px; font-weight: 700; }
		.gs-coin-dot { width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(145deg, #ffd55a, #f39b16); display: inline-block; box-shadow: inset 0 0 0 2px rgba(255,255,255,.25); }

		.gs-icon-btn:hover,
		.gs-coin-btn:hover { background: rgba(255, 255, 255, .12); transform: translateY(-1px); color: #fff; }

		.gs-reseller-btn {
			height: 50px;
			border: 0;
			border-radius: 10px;
			background: var(--gs-button);
			color: #fff;
			font-weight: 800;
			padding: 0 25px;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			white-space: nowrap;
			box-shadow: 0 10px 20px rgba(255, 66, 72, .24);
		}

		.gs-reseller-btn:hover { color: #fff; transform: translateY(-1px); }

		.gs-menu-list {
			height: 50px;
			display: flex;
			align-items: center;
			gap: 54px;
			margin: 0;
			padding: 0;
			list-style: none;
			overflow-x: auto;
			scrollbar-width: none;
		}
		.gs-menu-list::-webkit-scrollbar { display: none; }

		.gs-menu-link {
			display: inline-flex;
			align-items: center;
			gap: 10px;
			text-decoration: none;
			white-space: nowrap;
			font-size: .96rem;
			font-weight: 500;
			color: rgba(255, 255, 255, .94);
			transition: color .2s ease, transform .2s ease;
		}

		.gs-menu-link i { width: 18px; text-align: center; }
		.gs-menu-link:hover { color: #fff; transform: translateY(-1px); }
		.gs-menu-link.active { color: var(--gs-orange); font-weight: 700; }

		/* Hero */
		.gs-hero-wrap { max-width: 1000px; margin: 0 auto; }

		.gs-hero {
			min-height: 250px;
			border-radius: 15px;
			overflow: hidden;
			position: relative;
			padding: 48px 58px;
			background:
				linear-gradient(90deg, rgba(0, 142, 231, .92) 0%, rgba(42, 205, 221, .74) 54%, rgba(255, 255, 255, .12) 100%),
				radial-gradient(circle at 83% 32%, rgba(255, 255, 255, .58), transparent 12%),
				linear-gradient(135deg, #0aa4ef, #73ebd8);
			background-size: cover;
			background-position: center;
			box-shadow: var(--gs-shadow);
		}

		.gs-hero:before {
			content: '';
			position: absolute;
			inset: 0;
			background:
				linear-gradient(110deg, rgba(0, 0, 0, .08), transparent 48%),
				radial-gradient(circle at 88% 74%, rgba(255, 193, 7, .45), transparent 2.5%),
				radial-gradient(circle at 75% 82%, rgba(255, 193, 7, .45), transparent 2%);
			pointer-events: none;
		}

		.gs-hero-content { position: relative; z-index: 2; max-width: 620px; }
		.gs-hero-kicker { font-size: 1.42rem; line-height: 1.35; font-weight: 800; color: #fff; text-shadow: 2px 3px 0 #3023c7; }
		.gs-hero-title { margin: 10px 0 8px; font-size: clamp(2rem, 4vw, 3rem); line-height: 1; font-weight: 900; color: #ffe752; text-transform: uppercase; text-shadow: 3px 4px 0 #3023c7; letter-spacing: -.02em; }
		.gs-hero-subtitle { font-size: 1.55rem; line-height: 1.15; font-weight: 900; color: #fff; text-shadow: 2px 3px 0 #3023c7; }

		.gs-payment-badge {
			position: absolute;
			right: 22px;
			bottom: 24px;
			z-index: 3;
			background: #fff;
			color: #10356b;
			border: 4px solid #1b77df;
			border-radius: 12px;
			padding: 9px 14px 8px;
			font-size: .75rem;
			font-weight: 900;
			text-align: center;
			box-shadow: 0 12px 25px rgba(0,0,0,.16);
		}
		.gs-payment-badge span { display: block; margin-bottom: 4px; font-size: .72rem; color: #1b56d4; }
		.gs-payment-logos { display: flex; gap: 10px; align-items: center; color: #0f172a; font-size: .9rem; }

		.carousel-indicators { position: static; margin: 14px 0 0; gap: 5px; }
		.carousel-indicators [data-bs-target] { width: 8px; height: 8px; border-radius: 50%; border: 0; opacity: .45; background: #fff; }
		.carousel-indicators .active { opacity: 1; background: #000; }

		.gs-section { margin-top: 42px; }
		.gs-section-title { display: flex; align-items: center; gap: 8px; margin: 0 0 22px; font-size: 1.1rem; font-weight: 800; }

		.gs-popular-row {
			display: grid;
			grid-template-columns: repeat(5, minmax(0, 1fr));
			gap: 16px;
		}

		.gs-product-grid {
			display: grid;
			grid-template-columns: repeat(6, minmax(0, 1fr));
			gap: 18px;
		}

		.gs-product-card {
			display: block;
			text-decoration: none;
			border-radius: 10px;
			background: var(--gs-card);
			padding: 8px;
			box-shadow: 0 10px 22px rgba(0,0,0,.14);
			transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
			border: 1px solid rgba(255,255,255,.04);
		}

		.gs-product-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 18px 28px rgba(0,0,0,.24);
			background: #105b93;
			color: #fff;
		}

		.gs-product-thumb {
			position: relative;
			width: 100%;
			aspect-ratio: 1 / 1;
			border-radius: 8px;
			overflow: hidden;
			background: var(--gs-card-dark);
		}

		.gs-product-thumb img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.gs-product-name {
			display: block;
			min-height: 42px;
			padding: 12px 7px 4px;
			font-size: .82rem;
			font-weight: 800;
			line-height: 1.35;
			color: #fff;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.gs-product-card--popular {
			padding: 0;
			border-radius: 12px;
			overflow: hidden;
			background: #0c3b68;
		}
		.gs-product-card--popular .gs-product-thumb { border-radius: 12px; aspect-ratio: 16 / 13; }
		.gs-product-card--popular .gs-product-thumb:after {
			content: '';
			position: absolute;
			inset: 42% 0 0;
			background: linear-gradient(180deg, transparent, rgba(0,0,0,.78));
		}
		.gs-popular-meta {
			position: absolute;
			left: 14px;
			right: 14px;
			bottom: 14px;
			z-index: 2;
		}
		.gs-popular-title { display: block; color: #fff; font-weight: 800; font-size: .95rem; text-shadow: 0 2px 8px rgba(0,0,0,.45); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
		.gs-popular-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 105px;
			margin-top: 8px;
			padding: 8px 15px;
			border-radius: 12px;
			border: 1px solid rgba(255, 66, 72, .8);
			background: rgba(17, 20, 40, .62);
			color: #ff4248;
			font-size: .8rem;
			font-weight: 800;
		}

		.gs-red-divider {
			position: relative;
			height: 60px;
			margin: 40px calc(50% - 50vw) 38px;
		}
		.gs-red-divider:before {
			content: '';
			position: absolute;
			left: 0;
			right: 0;
			top: 18px;
			height: 32px;
			background: var(--gs-red);
			clip-path: polygon(0 0, 31.3% 0, 33.8% 75%, 66.2% 75%, 68.7% 0, 100% 0, 100% 10%, 69.2% 10%, 66.8% 85%, 33.2% 85%, 30.8% 10%, 0 10%);
		}

		.gs-filter-pills {
			display: flex;
			align-items: center;
			gap: 16px;
			margin-bottom: 34px;
			overflow-x: auto;
			scrollbar-width: none;
		}
		.gs-filter-pills::-webkit-scrollbar { display: none; }

		.gs-filter-btn {
			border: 1px solid rgba(255,255,255,.55);
			background: rgba(255,255,255,.04);
			color: #fff;
			border-radius: 5px;
			min-width: 82px;
			padding: 9px 17px;
			font-size: .82rem;
			font-weight: 800;
			white-space: nowrap;
			transition: background .18s ease, border-color .18s ease;
		}
		.gs-filter-btn.active,
		.gs-filter-btn:hover {
			background: rgba(255, 66, 128, .42);
			border-color: rgba(255, 66, 128, .88);
			color: #fff;
		}

		.gs-wa-button {
			position: fixed;
			right: 26px;
			bottom: 24px;
			z-index: 1050;
			width: 58px;
			height: 58px;
			border-radius: 50%;
			background: #22c55e;
			color: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1.78rem;
			text-decoration: none;
			box-shadow: 0 14px 30px rgba(0,0,0,.28);
			border: 4px solid rgba(255,255,255,.08);
		}
		.gs-wa-button:hover { color: #fff; transform: translateY(-2px); }

		.gs-footer {
			background: #052659;
			border-top: 1px solid rgba(255, 66, 72, .7);
			color: var(--gs-muted);
			padding: 34px 0 28px;
			margin-top: auto;
		}
		.gs-footer-title { color: #fff; font-weight: 800; margin-bottom: 10px; }
		.gs-footer-link { color: var(--gs-muted); text-decoration: none; font-size: .9rem; }
		.gs-footer-link:hover { color: #fff; }
		.gs-footer-logo { max-height: 42px; width: auto; }
		.gs-payment-chip { display: inline-flex; align-items: center; justify-content: center; min-width: 58px; min-height: 28px; padding: 4px 8px; border-radius: 6px; background: #fff; color: #0f172a; font-size: .72rem; font-weight: 900; }
		.gs-footer-bottom { border-top: 1px solid rgba(255,255,255,.1); margin-top: 24px; padding-top: 18px; font-size: .82rem; }

		/* Utility / Bootstrap override */
		.card,
		.table { color: var(--gs-text); }
		.text-muted { color: var(--gs-muted) !important; }
		.btn-danger { background-color: var(--gs-button); border-color: var(--gs-button); }
		.btn-danger:hover { filter: brightness(.96); }

		@media (max-width: 1199.98px) {
			.gs-product-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
			.gs-menu-list { gap: 34px; }
		}

		@media (max-width: 991.98px) {
			.container { width: min(100% - 24px, 1180px); }
			.gs-header-inner { height: auto; padding: 12px 0; flex-wrap: wrap; gap: 12px; }
			.gs-logo { flex: 0 0 auto; min-width: 0; gap: 8px; }
			.gs-logo-mark { width: 52px; height: 42px; border-radius: 14px; }
			.gs-logo-mark img { max-height: 32px; }
			.gs-logo-copy { display: none; }
			.gs-actions { gap: 8px; }
			.gs-search { order: 5; flex: 1 0 100%; max-width: none; }
			.gs-search input { height: 44px; font-size: .86rem; }
			.gs-reseller-btn { display: none; }
			.gs-icon-btn { width: 42px; height: 42px; font-size: 1.85rem; }
			.gs-coin-btn { height: 42px; min-width: 62px; font-size: .84rem; }
			.gs-menu-list { height: 48px; gap: 24px; padding-bottom: 1px; }
			.gs-main { padding-top: 22px; }
			.gs-hero { min-height: 210px; padding: 34px 30px; }
			.gs-payment-badge { position: relative; right: auto; bottom: auto; display: inline-block; margin-top: 18px; }
			.gs-popular-row { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 6px; }
			.gs-popular-row .gs-product-card { min-width: 185px; scroll-snap-align: start; }
			.gs-product-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
		}

		@media (max-width: 767.98px) {
			.gs-product-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
			.gs-hero-kicker { font-size: 1.1rem; }
			.gs-hero-title { font-size: 2rem; }
			.gs-hero-subtitle { font-size: 1.15rem; }
			.gs-red-divider { margin-top: 26px; margin-bottom: 28px; height: 44px; }
			.gs-filter-pills { margin-bottom: 24px; gap: 10px; }
		}

		@media (max-width: 575.98px) {
			.container { width: min(100% - 20px, 1180px); }
			.gs-main { padding-bottom: 76px; }
			.gs-product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
			.gs-section { margin-top: 32px; }
			.gs-section-title { font-size: 1rem; margin-bottom: 16px; }
			.gs-hero { min-height: 188px; padding: 26px 20px; border-radius: 12px; }
			.gs-payment-badge { padding: 8px 10px; font-size: .68rem; }
			.gs-payment-logos { font-size: .78rem; gap: 8px; }
			.gs-product-name { font-size: .78rem; }
			.gs-wa-button { width: 54px; height: 54px; right: 16px; bottom: 16px; }
		}
	</style>

	@stack('styles')
</head>
<body>
	@include('components.navbar')

	<main class="gs-main">
		@yield('content')
	</main>

	@include('components.footer')

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const filterButtons = document.querySelectorAll('[data-filter-target]');
			const productCards = document.querySelectorAll('.js-product-card');
			const sections = document.querySelectorAll('.js-product-section');

			filterButtons.forEach(function (button) {
				button.addEventListener('click', function () {
					const target = button.getAttribute('data-filter-target');

					filterButtons.forEach(function (btn) { btn.classList.remove('active'); });
					button.classList.add('active');

					productCards.forEach(function (card) {
						const type = card.getAttribute('data-product-type');
						card.style.display = (target === 'all' || type === target) ? '' : 'none';
					});

					sections.forEach(function (section) {
						const visibleCards = section.querySelectorAll('.js-product-card:not([style*="display: none"])').length;
						section.style.display = visibleCards > 0 ? '' : 'none';
					});
				});
			});
		});
	</script>
	@stack('scripts')
</body>
</html>
