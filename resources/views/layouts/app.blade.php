<!DOCTYPE html>
{{-- <html lang="id" data-bs-theme="dark"> --}}
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@php $appName = config('app.name'); @endphp
	<title>@yield('title', $appName)</title>

	{{-- =======================================================
		 FIX FLICKER / DELAY TEMA (Script Wajib di Head)
		 ======================================================= --}}
	<script>
		(function() {
			// Ambil tema dari storage atau default ke 'dark'
			const storedTheme = localStorage.getItem('theme') || 'dark';
			// Set atribut langsung ke HTML sebelum konten dirender
			document.documentElement.setAttribute('data-bs-theme', storedTheme);
		})();
	</script>

	{{-- Favicon --}}
	{{-- <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='60' fill='%234f46e5'/%3E%3Ctext x='50' y='66' font-size='55' font-family='Arial Black, Roboto Black, sans-serif-black' font-weight='900' text-anchor='middle' fill='white'%3ECNS%3C/text%3E%3C/svg%3E"> --}}
	<link rel="icon" type="image/png" href="{{ asset('cloudnest.png') }}" sizes="32x32">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

	<style>
		/* Menggunakan Variable CSS Anda */
		:root {
			--primary-color: #4f46e5; --secondary-color: #0ea5e9;
			--bg-body: #f8fafc; --text-main: #1e293b; --text-muted: #64748b;
			--card-bg: #ffffff; --card-border: #e2e8f0; --navbar-bg: rgba(255, 255, 255, 0.9);
			--bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
		}
		[data-bs-theme="dark"] {
			--primary-color: #6366f1; --secondary-color: #38bdf8;
			--bg-body: #0f172a; --text-main: #f1f5f9; --text-muted: #94a3b8;
			--card-bg: #1e293b; --card-border: #334155; --navbar-bg: rgba(15, 23, 42, 0.95);
			--bg-gradient: radial-gradient(circle at 10% 20%, rgb(18, 26, 56) 0%, rgb(11, 15, 25) 90.2%);
		}
		body {
			font-family: 'Poppins', sans-serif;
			background: var(--bg-gradient); background-attachment: fixed;
			color: var(--text-main); min-height: 100vh;
			display: flex; flex-direction: column;
			/* Hapus transition background saat load awal agar tidak kedip */
		}

		/* Tambahkan transition hanya setelah halaman dimuat (Opsional, agar toggle halus tapi reload instan) */
		body.loaded {
			transition: color 0.3s ease, background 0.3s ease;
		}

		/* --- UI COMPONENTS --- */
		.navbar { background: var(--navbar-bg); backdrop-filter: blur(10px); border-bottom: 1px solid var(--card-border); position: sticky; top: 0; z-index: 1000; }
		.glass-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; transition: 0.3s; }
		.hover-scale:hover { transform: translateY(-5px); border-color: var(--secondary-color); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

		/* --- LAZY LOAD & SKELETON --- */
		.lazy-img { opacity: 0; transition: opacity 0.5s ease-in-out; }
		.lazy-img.loaded { opacity: 1; }

		.skeleton {
			background-color: var(--card-border);
			background-image: linear-gradient(90deg, var(--card-border), var(--bg-body), var(--card-border));
			background-size: 200px 100%;
			background-repeat: no-repeat;
			border-radius: 4px;
			display: inline-block;
			line-height: 1;
			width: 100%;
			animation: skeleton-loading 1.5s ease-in-out infinite;
		}
		@keyframes skeleton-loading {
			0% { background-position: -200px 0; }
			100% { background-position: calc(200px + 100%) 0; }
		}

		/* Footer */
		footer { background: var(--card-bg); border-top: 3px solid var(--primary-color); padding-top: 40px; margin-top: auto; }
	</style>
	@stack('styles')
</head>
<body>

	@include('components.navbar')

	<main class="py-4">
		@yield('content')
	</main>

	@include('components.footer')

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script src="{{ asset('requestor/axios.min.js') }}"></script>
	<script src="{{ asset('requestor/axios.js') }}"></script>

	<script>
		let module
		async function initModul() {
			const master = await import("{{ url('/custom/js/master.js') }}")
			return master
		}

		// Fungsi Update Ikon (Dipisahkan agar bisa dipanggil berulang)
		function updateThemeIcon(theme) {
			const $icon = $('.themeToggle i');

			// Reset class ikon
			$icon.removeClass('fa-moon fa-sun');

			if (theme === 'dark') {
				// Jika Dark Mode -> Tampilkan Bulan
				$icon.addClass('fa-moon');
			} else {
				// Jika Light Mode -> Tampilkan Matahari
				$icon.addClass('fa-sun');
			}
		}

		$(document).ready(function() {
			// 1. Tampilkan Body setelah transisi aman (Opsional untuk smooth)
			$('body').addClass('loaded');

			// 2. Ambil tema saat ini dari HTML attribute (yang sudah diset oleh script di head)
			let currentTheme = $('html').attr('data-bs-theme');

			// 3. Set Ikon Awal
			updateThemeIcon(currentTheme);

			// 4. Event Listener Click
			$('.themeToggle').click(function() {
				// Ambil tema aktif
				let activeTheme = $('html').attr('data-bs-theme');
				// Tentukan tema baru (Toggle)
				let newTheme = activeTheme === 'dark' ? 'light' : 'dark';

				// Terapkan ke HTML & LocalStorage
				$('html').attr('data-bs-theme', newTheme);
				localStorage.setItem('theme', newTheme);

				// Update Ikon sesuai tema baru
				updateThemeIcon(newTheme);
			});

			// 5. Native Lazy Load (Tetap pertahankan ini)
			const observer = new IntersectionObserver((entries, observer) => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						const img = entry.target;
						if(img.dataset.src) {
							img.src = img.dataset.src;
							img.onload = () => img.classList.add('loaded');
						}
						observer.unobserve(img);
					}
				});
			});
			document.querySelectorAll('.lazy-img').forEach(img => observer.observe(img));
		});
	</script>
	@stack('scripts')
</body>
</html>
