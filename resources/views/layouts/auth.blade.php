<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'Cloud Nest Store')</title>

	<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='60' fill='%234f46e5'/%3E%3Ctext x='50' y='66' font-size='55' font-family='Arial Black, Roboto Black, sans-serif-black' font-weight='900' text-anchor='middle' fill='white'%3EICS%3C/text%3E%3C/svg%3E">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style>
		/* =========================================
		CUSTOM INPUT GROUP (Seamless Style)
		========================================= */

		/* 1. Definisi Variabel Warna Input (Adaptif) */
		:root {
			--primary-color: #667eea;
			--text-main: #333333;
			/* --text-main: #f1f5f9; */
			--text-muted: #6c757d;

			/* Setting Input */
			--input-bg: #f8f9fa; /* Abu terang untuk Light Mode */
			--input-border: #e9ecef;

			/* Layout */
			--max-width-card: 600px;
		}

		/* Dark Mode Support (Persiapan) */
		[data-bs-theme="dark"] {
			--primary-color: #667eea;
			--text-main: #f1f5f9;
			--text-muted: #adb5bd;
			--input-bg: rgba(0, 0, 0, 0.3);
			--input-border: rgba(255, 255, 255, 0.1);
		}

		/* =========================================
		   2. LAYOUT BODY & CARD
		   ========================================= */
		body {
			background-color: #f3f4f6;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Segoe UI', sans-serif;
			padding: 20px 0;
		}

		.card-login {
			border: none;
			border-radius: 16px;
			box-shadow: 0 10px 40px rgba(0,0,0,0.08);
			overflow: hidden;
			width: 100%;
			max-width: var(--max-width-card);
			position: relative;
			z-index: 5; /* Agar di atas elemen background/salju */
		}

		.login-header {
			background: #1e1e2d;
			padding: 25px 20px;
			text-align: center;
			color: white;
		}

		/* =========================================
		   3. CUSTOM FORM STYLES
		   ========================================= */
		.form-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); }

		/* Input Group (Seamless) */
		.custom-input-group {
			background-color: var(--input-bg);
			border: 1px solid var(--input-border);
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
		}

		.custom-input-group:focus-within {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25); /* Warna primary transparan */
		}

		/* Input Standard (Single) - Agar sama dengan Group */
		.form-control {
			color: var(--text-main) !important;
		}
		.form-control::placeholder {
			color: var(--text-muted);
			opacity: 0.7;
		}

		/* =========================================
		   4. FIX CHECKBOX (PENTING)
		   ========================================= */
		.form-check {
			position: relative;
			z-index: 10;
		}
		.form-check-input {
			cursor: pointer;
		}
		.form-check-label {
			cursor: pointer;
		}

		/* =========================================
		   5. BUTTONS
		   ========================================= */
		.btn-primary {
			background-color: var(--primary-color);
			border: none;
			padding: 12px;
			font-weight: 600;
		}
		.btn-primary:hover {
			background-color: #5a6fd6; /* Sedikit lebih gelap */
		}

		/* 2. Styling Container Utama */
		.custom-input-single:focus-within {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1); /* Ganti var(...) sesuai config warna Anda */
			/* Jika error var, pakai manual: rgba(79, 70, 229, 0.1) */
		}
	</style>

	@stack('styles')
</head>
<body>

	@yield('content')

	<script>
		function togglePassword(inputId, btn) {
			const input = document.getElementById(inputId);
			const icon = btn.querySelector('i');

			if (input.type === "password") {
				input.type = "text";
				icon.classList.remove('fa-eye-slash');
				icon.classList.add('fa-eye');
			} else {
				input.type = "password";
				icon.classList.remove('fa-eye');
				icon.classList.add('fa-eye-slash');
			}
		}
	</script>

	@stack('scripts')
</body>
</html>
