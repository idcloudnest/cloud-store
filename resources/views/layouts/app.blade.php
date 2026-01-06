<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Cloud Nest Store')</title>

	{{-- =========================================
         FAVICON / ICON TITLE
         ========================================= --}}
    {{-- OPSI 1: Generate Logo Otomatis (Biru Bulat text ID) --}}
	{{-- <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2250%22 fill=%22%234f46e5%22/><text x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22sans-serif%22 font-weight=%22bold%22>ICS</text></svg>"> --}}
	{{-- Bentuk Awan Biru dengan tulisan putih "ICS" di tengah --}}

	{{-- <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512'%3E%3C!-- Base Icon Cloud Bolt --%3E%3Cpath fill='%234f46e5' d='M256 32c-88.4 0-160 71.6-160 160 0 2.7 .1 5.3 .2 8C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4 4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32zm0 128l96 96h-48v80H192v-80h-48l96-96z'/%3E%3C!-- Text ICS --%3E%3Ctext x='280' y='400' font-size='350' fill='white' font-family='Arial Black, Arial, sans-serif' font-weight='900' text-anchor='middle'%3EICS%3C/text%3E%3C/svg%3E"> --}}
	{{-- <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cdefs%3E%3ClinearGradient id='grad1' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%234f46e5;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%230ea5e9;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Ctext x='50' y='70' font-size='60' font-family='Arial Black, Roboto Black, sans-serif-black' font-weight='900' text-anchor='middle' fill='url(%23grad1)'%3EICS%3C/text%3E%3C/svg%3E"> --}}
	<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='60' fill='%234f46e5'/%3E%3Ctext x='50' y='66' font-size='55' font-family='Arial Black, Roboto Black, sans-serif-black' font-weight='900' text-anchor='middle' fill='white'%3ECNS%3C/text%3E%3C/svg%3E">

	{{-- OPSI 2: Jika nanti punya file logo sendiri (misal: public/logo.png), pakai ini: --}}
    {{-- <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon"> --}}

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

	<style>
		/* =========================================
		1. DEFINISI VARIABEL WARNA (THEME CONFIG)
		========================================= */
		:root {
			/* --- Tema Light (Default) --- */
			--primary-color: #4f46e5;
			--secondary-color: #0ea5e9;
			--bg-body: #f8fafc;
			--text-main: #1e293b;
			--text-muted: #64748b;
			--card-bg: #ffffff;
			--card-border: #e2e8f0;
			--navbar-bg: rgba(255, 255, 255, 0.9);
			--bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);

			/* Warna Salju untuk Light Mode (Biru Es agar terlihat di background putih) */
			--snow-color: #0ea5e9;
			--snow-glow: 0 0 5px rgba(14, 165, 233, 0.5); /* Glow tipis */
		}

		[data-bs-theme="dark"] {
			/* --- Tema Dark --- */
			--primary-color: #6366f1;
			--secondary-color: #38bdf8;
			--bg-body: #0f172a;
			--text-main: #f1f5f9;
			--text-muted: #94a3b8;
			--card-bg: #1e293b;
			--card-border: #334155;
			--navbar-bg: rgba(15, 23, 42, 0.95);
			--bg-gradient: radial-gradient(circle at 10% 20%, rgb(18, 26, 56) 0%, rgb(11, 15, 25) 90.2%);

			/* Warna Salju untuk Dark Mode (Putih Neon Glowing) */
			--snow-color: #a5f3fc;
			--snow-glow:
			0 0 5px #22d3ee,
			0 0 15px #0ea5e9,
			0 0 30px #2563eb;  /* Glow tebal */
		}

		body {
			font-family: 'Poppins', sans-serif;
			background: var(--bg-gradient);
			background-attachment: fixed;
			color: var(--text-main);
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			position: relative;
			transition: color 0.3s ease, background 0.3s ease; /* Transisi halus saat ganti tema */
		}

		/* =========================================
		2. ANIMASI SALJU (ICONS)
		========================================= */
		#snow-container {
			position: fixed;
			top: 0; left: 0; width: 100%; height: 100%;
			overflow: hidden;
			z-index: 1;
			pointer-events: none;
		}

		.snowflake {
			position: absolute;
			top: -50px;

			/* Menggunakan Variable agar berubah otomatis */
			color: var(--snow-color);
			text-shadow: var(--snow-glow);

			opacity: 0.9;
			z-index: 1;
			transition: color 0.5s ease, text-shadow 0.5s ease; /* Efek transisi warna salju */
		}

		.snowflake::before {
			content: '\f2dc'; /* FontAwesome Snowflake Icon */
			font-family: 'Font Awesome 6 Free';
			font-weight: 900;
		}

		@keyframes fall {
			0% { transform: translateY(-10vh) translateX(0) rotate(0deg); opacity: 0; }
			10% { opacity: 1; }
			100% { transform: translateY(110vh) translateX(50px) rotate(360deg); opacity: 0.3; }
		}

		/* =========================================
		3. KOMPONEN LAINNYA
		========================================= */
		.navbar {
			background-color: var(--navbar-bg);
			backdrop-filter: blur(10px);
			border-bottom: 1px solid var(--card-border);
			z-index: 1000;
			position: sticky;
			top: 0;
			transition: background-color 0.3s ease, border-color 0.3s ease;
		}

		main { position: relative; z-index: 2; }

		.game-card {
			background-color: var(--card-bg);
			border: 1px solid var(--card-border);
			border-radius: 16px;
			overflow: hidden;
			transition: transform 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
			cursor: pointer;
		}
		.game-card:hover { transform: translateY(-8px); border-color: var(--secondary-color); }
		.card-title { color: var(--text-main); font-weight: 600; }

		footer {
			margin-top: auto;
			background-color: var(--card-bg);
			border-top: 3px solid var(--primary-color);
			padding-top: 40px;
			color: var(--text-muted);
			position: relative;
			z-index: 2;
			transition: background-color 0.3s ease, color 0.3s ease;
		}
		footer h5 { color: var(--text-main); font-weight: 700; }

		.footer-bottom {
			background-color: var(--bg-body);
			padding: 20px 0;
			margin-top: 30px;
			border-top: 1px solid var(--card-border);
			transition: background-color 0.3s ease, border-color 0.3s ease;
		}

		.text-primary-gradient {
			background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
	</style>

	@stack('styles')
</head>
<body>

	<div id="snow-container"></div>

	@include('components.navbar')

	<main>
		@yield('content')
	</main>

	@include('components.footer')

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		$(document).ready(function() {
			// --- Theme Logic ---
			const currentTheme = localStorage.getItem('theme') || 'dark';
			setTheme(currentTheme);

			$('.themeToggle').click(function() {
				let activeTheme = $('html').attr('data-bs-theme');
				let newTheme = activeTheme === 'dark' ? 'light' : 'dark';
				setTheme(newTheme);
			});

			function setTheme(theme) {
				$('html').attr('data-bs-theme', theme);
				localStorage.setItem('theme', theme);
				// Ganti icon toggle
				$('.themeToggle i').attr('class', theme === 'dark' ? 'fas fa-moon' : 'fas fa-sun');
			}

			// --- SMART SNOW ANIMATION LOGIC (Refill System) ---
			const snowContainer = document.getElementById('snow-container');

			function createSnowflake(isRefill = false) {
				if (!snowContainer) return;

				const snowflake = document.createElement('div');
				snowflake.classList.add('snowflake');

				// Posisi Horizontal Acak
				snowflake.style.left = Math.random() * 100 + 'vw';

				// Ukuran Acak (10px - 25px)
				const size = Math.random() * 15 + 10;
				snowflake.style.fontSize = size + 'px';

				// Durasi Jatuh Acak
				const duration = Math.random() * 10 + 8;
				snowflake.style.animationName = 'fall';
				snowflake.style.animationTimingFunction = 'linear';
				snowflake.style.animationFillMode = 'forwards';
				snowflake.style.animationDuration = duration + 's';

				// Logika Refill
				if (isRefill) {
					const randomPastTime = Math.random() * duration;
					snowflake.style.animationDelay = `-${randomPastTime}s`;
				} else {
					snowflake.style.animationDelay = Math.random() * 5 + 's';
				}

				snowContainer.appendChild(snowflake);

				// Hapus elemen setelah selesai
				setTimeout(() => {
					snowflake.remove();
				}, (duration * 1000) + 6000);
			}

			// Loop Normal
			let snowInterval = setInterval(() => createSnowflake(false), 350);

			// Refill saat user kembali ke tab
			document.addEventListener("visibilitychange", function() {
				if (document.visibilityState === 'visible') {
					for(let i = 0; i < 30; i++) {
						createSnowflake(true);
					}
				}
			});

			// Inisialisasi Awal
			for(let i = 0; i < 20; i++) {
				createSnowflake(true);
			}
		});
	</script>

	@stack('scripts')
</body>
</html>
