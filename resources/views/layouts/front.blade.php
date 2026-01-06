<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Cloud Nest Store')</title>

	{{-- Bootstrap 5 CSS --}}
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	{{-- Font Awesome --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	{{-- Google Fonts --}}
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

	<style>
		/* =========================================
		1. THEME CONFIGURATION (Variables)
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

			/* Efek Salju Light Mode (Biru Es) */
			--snow-color: #0ea5e9;
			--snow-glow: 0 0 5px rgba(14, 165, 233, 0.5);
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
			/* Gradient Dark Blue Malam Hari */
			--bg-gradient: radial-gradient(circle at 10% 20%, rgb(18, 26, 56) 0%, rgb(11, 15, 25) 90.2%);

			/* Efek Salju Dark Mode (Neon Glowing) */
			--snow-color: #a5f3fc;
			--snow-glow:
			0 0 5px #22d3ee,
			0 0 15px #0ea5e9,
			0 0 30px #2563eb;
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
			transition: color 0.3s ease, background 0.3s ease;
		}

		/* =========================================
		2. SNOW ANIMATION
		========================================= */
		#snow-container {
			position: fixed;
			top: 0; left: 0; width: 100%; height: 100%;
			overflow: hidden;
			z-index: 1; /* Di bawah konten utama */
			pointer-events: none; /* Agar bisa diklik tembus */
		}

		.snowflake {
			position: absolute;
			top: -50px;
			color: var(--snow-color);
			text-shadow: var(--snow-glow);
			opacity: 0.9;
			z-index: 1;
			transition: color 0.5s ease, text-shadow 0.5s ease;
		}

		.snowflake::before {
			content: '\f2dc'; /* Icon Salju FontAwesome */
			font-family: 'Font Awesome 6 Free';
			font-weight: 900;
		}

		@keyframes fall {
			0% { transform: translateY(-10vh) translateX(0) rotate(0deg); opacity: 0; }
			10% { opacity: 1; }
			100% { transform: translateY(110vh) translateX(50px) rotate(360deg); opacity: 0.3; }
		}

		/* =========================================
		3. GENERAL STYLING
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

		main { position: relative; z-index: 2; padding-top: 20px; padding-bottom: 40px; }

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

		/* --- TAMBAHAN CSS UNTUK OVERRIDE BOOTSTRAP --- */
		.card {
			background-color: var(--card-bg);
			border-color: var(--card-border);
			color: var(--text-main);
			transition: background-color 0.3s, border-color 0.3s, color 0.3s;
		}

		/* Header Card transparan agar mengikuti warna card */
		.card-header {
			background-color: transparent;
			border-bottom: 1px solid var(--card-border);
		}

		/* Tabel di Dark Mode */
		.table {
			color: var(--text-main);
			border-color: var(--card-border);
		}
		.table thead th {
			background-color: rgba(0,0,0,0.1); /* Header tabel sedikit lebih gelap */
			color: var(--text-main);
			border-bottom: 1px solid var(--card-border);
		}
		.table td {
			border-bottom: 1px solid var(--card-border);
		}

		/* Memperbaiki warna teks yang dipaksa hitam oleh Bootstrap */
		.text-dark {
			color: var(--text-main) !important;
		}
		a.text-dark:hover {
			color: var(--primary-color) !important;
		}

		/* Icon Box agar lebih terang di dark mode */
		[data-bs-theme="dark"] .icon-box {
			background-opacity: 0.2 !important; /* Tambah opacity biar lebih kelihatan */
		}

		#snow-container {
			/* ... css lainnya ... */
			z-index: 1;           /* Layer paling bawah */
			pointer-events: none; /* PENTING: Agar klik tembus ke belakang */
		}

		.btn-soft-danger {
			background-color: rgba(220, 53, 69, 0.1);
			color: #dc3545;
			border: 1px solid rgba(220, 53, 69, 0.2);
			transition: all 0.2s;
		}

		.btn-soft-danger:hover {
			background-color: #dc3545;
			color: white;
			transform: translateY(-2px);
		}

		/* Penyesuaian Dark Mode agar tetap kontras */
		[data-bs-theme="dark"] .btn-soft-danger {
			background-color: rgba(239, 68, 68, 0.2);
			color: #fca5a5;
			border-color: rgba(239, 68, 68, 0.3);
		}
		[data-bs-theme="dark"] .btn-soft-danger:hover {
			background-color: #ef4444;
			color: white;
		}
	</style>

	@stack('styles')
</head>
<body>

	{{-- Container Animasi Salju --}}
	<div id="snow-container"></div>

	{{-- Memanggil Komponen Navbar --}}
	@include('components.navbar')

	{{-- Konten Utama Halaman --}}
	<main>
		@yield('content')
	</main>

	{{-- Memanggil Komponen Footer --}}
	@include('components.footer')
	{{-- @include('components.member.footer') --}}

	{{-- Scripts --}}
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		$(document).ready(function() {
			// =========================================
			// 1. THEME TOGGLE LOGIC (DARK/LIGHT)
			// =========================================
			const currentTheme = localStorage.getItem('theme') || 'dark';
			setTheme(currentTheme);

			// Event saat tombol toggle di Navbar diklik
			// Pastikan ID tombol di Navbar adalah 'themeToggle'
			$(document).on('click', '#themeToggle', function() {
				let activeTheme = $('html').attr('data-bs-theme');
				let newTheme = activeTheme === 'dark' ? 'light' : 'dark';
				setTheme(newTheme);
			});

			function setTheme(theme) {
				$('html').attr('data-bs-theme', theme);
				localStorage.setItem('theme', theme);

				// Ubah Icon Matahari/Bulan
				if (theme === 'dark') {
					$('#themeToggle i').attr('class', 'fas fa-moon');
				} else {
					$('#themeToggle i').attr('class', 'fas fa-sun text-warning');
				}
			}

			// =========================================
			// 2. SMART SNOWFALL SYSTEM
			// =========================================
			const snowContainer = document.getElementById('snow-container');

			function createSnowflake(isRefill = false) {
				if (!snowContainer) return;

				const snowflake = document.createElement('div');
				snowflake.classList.add('snowflake');

				// Posisi Horizontal Acak
				snowflake.style.left = Math.random() * 100 + 'vw';

				// Ukuran Acak (Variasi 10px - 25px)
				const size = Math.random() * 15 + 10;
				snowflake.style.fontSize = size + 'px';

				// Durasi Jatuh & Goyangan
				const duration = Math.random() * 10 + 8; // 8-18 detik
				snowflake.style.animationName = 'fall';
				snowflake.style.animationTimingFunction = 'linear';
				snowflake.style.animationFillMode = 'forwards';
				snowflake.style.animationDuration = duration + 's';

				// Delay Logic
				if (isRefill) {
					// Jika refill (saat user balik ke tab), seolah-olah salju sudah turun setengah jalan
					const randomPastTime = Math.random() * duration;
					snowflake.style.animationDelay = `-${randomPastTime}s`;
				} else {
					snowflake.style.animationDelay = Math.random() * 5 + 's';
				}

				snowContainer.appendChild(snowflake);

				// Hapus elemen DOM setelah animasi selesai agar tidak memberatkan browser
				setTimeout(() => {
					snowflake.remove();
				}, (duration * 1000) + 2000);
			}

			// Loop Utama: Buat salju baru setiap 350ms
			let snowInterval = setInterval(() => createSnowflake(false), 350);

			// Refill System: Saat user pindah tab lalu kembali, isi layar dengan salju seketika
			document.addEventListener("visibilitychange", function() {
				if (document.visibilityState === 'visible') {
					for(let i = 0; i < 30; i++) {
						createSnowflake(true);
					}
				}
			});

			// Inisialisasi Awal: Isi layar saat pertama load
			for(let i = 0; i < 20; i++) {
				createSnowflake(true);
			}
		});
	</script>

	@stack('scripts')
</body>
</html>
