{{-- <!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Daftar - ID Cloud Store</title>

	<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='60' fill='%234f46e5'/%3E%3Ctext x='50' y='66' font-size='55' font-family='Arial Black, Roboto Black, sans-serif-black' font-weight='900' text-anchor='middle' fill='white'%3EICS%3C/text%3E%3C/svg%3E">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style>
		body {
			background-color: #f3f4f6;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Segoe UI', sans-serif;
			padding: 20px 0; /* Padding agar tidak mentok saat scroll di HP */
		}
		.card-login {
			border: none;
			border-radius: 16px;
			box-shadow: 0 10px 40px rgba(0,0,0,0.08);
			overflow: hidden;
			width: 100%;
			max-width: 600px; /* Lebih lebar dari login karena 2 kolom */
		}
		.login-header {
			background: #1e1e2d;
			padding: 25px 20px;
			text-align: center;
			color: white;
		}
		.btn-primary {
			background-color: #667eea;
			border: none;
			padding: 12px;
			font-weight: 600;
		}
		.btn-primary:hover {
			background-color: #5a6fd6;
		}
		/* .form-control:focus {
			border-color: #667eea;
			box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
		} */
		.form-label { font-size: 0.85rem; font-weight: 700; color: #6c757d; }

		/* =========================================
		CUSTOM INPUT GROUP (Seamless Style)
		========================================= */

		/* 1. Definisi Variabel Warna Input (Adaptif) */
		:root {
			--input-bg: #f8f9fa;       /* Abu terang untuk Light Mode */
			--input-border: #e9ecef;
		}

		[data-bs-theme="dark"] {
			--input-bg: rgba(0, 0, 0, 0.3); /* Hitam transparan untuk Dark Mode */
			--input-border: rgba(255, 255, 255, 0.1);
		}

		/* 2. Styling Container Utama */
		.custom-input-single:focus-within {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1); /* Ganti var(...) sesuai config warna Anda */
			/* Jika error var, pakai manual: rgba(79, 70, 229, 0.1) */
		}
		.custom-input-group {
			background-color: var(--input-bg);
			border: 1px solid var(--input-border);
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
		}

		/* 3. Efek Fokus (Glow) pada Container saat Input diklik */
		.custom-input-group:focus-within {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1); /* Ganti var(...) sesuai config warna Anda */
			/* Jika error var, pakai manual: rgba(79, 70, 229, 0.1) */
		}

		/* 4. Pastikan teks input mengikuti tema */
		.form-control {
			color: var(--text-main) !important;
		}

		/* 5. Placeholder color */
		.form-control::placeholder {
			color: var(--text-muted);
			opacity: 0.7;
		}
	</style>
</head>
<body>

	<div class="card card-login">
		<div class="login-header">
			<h4 class="mb-1 fw-bold"><i class="fas fa-users me-2 text-warning"></i> Registrasi</h4>
			<small class="text-white-50">Bergabunglah dan mulai bertransaksi</small>
		</div>

		<div class="card-body p-4 bg-white">

			@if ($errors->any())
				<div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
					<ul class="mb-0 small ps-3">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form action="{{ route('auth.register.process') }}" method="POST">
				@csrf

				<div class="mb-3">
					<label class="form-label">NAMA LENGKAP / NAMA COUNTER</label>
					<input type="text" name="name" class="form-control custom-input-single shadow-none" placeholder="Contoh: ID Celluler" value="{{ old('name') }}" required autocomplete="off">
				</div>

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
								<i class="fas fa-envelope"></i>
							</span>
							<input type="email" name="email" class="form-control border-0 bg-transparent shadow-none text-body"
								placeholder="email@anda.com" value="{{ old('email') }}" required autocomplete="off">
						</div>
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">NOMOR WHATSAPP</label>

						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<div class="input-group-text border-0 bg-transparent pe-0 ps-3">
								<div class="d-flex align-items-center gap-2 border-end border-secondary border-opacity-25 pe-2">
									<img src="https://flagcdn.com/w40/id.png" width="20" alt="ID" class="rounded-1">

									<i class="fas fa-chevron-down text-muted" style="font-size: 0.6rem;"></i>
								</div>
								<span class="fw-bold text-body ms-2">+62</span>
							</div>

							<input type="tel" name="phone"
								class="form-control border-0 bg-transparent shadow-none text-body fw-bold"
								placeholder="8123456xxxx"
								value="{{ old('phone') }}"
								oninput="this.value = this.value.replace(/[^0-9]/g, '')"
								required autocomplete="off">
						</div>
						<div class="form-text small text-muted">Masukkan nomor tanpa angka 0 di depan.</div>
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">USERNAME (LOGIN)</label>
					<div class="input-group custom-input-group rounded-3 overflow-hidden">
						<span class="input-group-text">@</span>
						<input type="text" name="username" class="form-control border-0 bg-transparent shadow-none text-body" placeholder="username_anda" value="{{ old('username') }}" required autocomplete="off">
					</div>
					<div class="form-text small">Tanpa spasi, maksimal 20 karakter.</div>
				</div>

				<hr class="my-4 opacity-10">

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">PASSWORD LOGIN</label>
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<input type="password" name="password" id="regPassword"
							class="form-control border-0 bg-transparent shadow-none text-body" required>

							<button class="btn border-0 text-muted pe-3" type="button"
								onclick="togglePassword('regPassword', this)" tabindex="-1">
								<i class="fas fa-eye-slash"></i>
							</button>
						</div>
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">ULANGI PASSWORD</label>
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<input type="password" name="password_confirmation" id="regConfirm"
							class="form-control border-0 bg-transparent shadow-none text-body" required>

							<button class="btn border-0 text-muted pe-3" type="button"
								onclick="togglePassword('regConfirm', this)" tabindex="-1">
								<i class="fas fa-eye-slash"></i>
							</button>
						</div>
					</div>
				</div>

				<button type="submit" class="btn btn-primary w-100 rounded-3 mb-3">
					<i class="fas fa-user-plus me-2"></i> DAFTAR SEKARANG
				</button>

				<div class="text-center">
					<small class="text-muted">Sudah punya akun? <a href="{{ route('auth.login') }}" class="text-decoration-none fw-bold" style="color: #667eea;">Login Disini</a></small>
				</div>

			</form>
		</div>
	</div>

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
</body>
</html> --}}


@extends('layouts.auth')

@section('title', 'Register - IDCloudStore')

@section('content')
	<div class="card card-login">
		{{-- Header --}}
		<div class="login-header">
			<h4 class="mb-1 fw-bold"><i class="fas fa-users me-2 text-warning"></i> Registrasi</h4>
			<small class="text-white-50">Bergabunglah dan mulai bertransaksi</small>
		</div>

		<div class="card-body p-4 bg-white">

			{{-- Error Global (jika ada) --}}
			@if ($errors->any())
				<div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
					<ul class="mb-0 small ps-3">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form action="{{ route('auth.register.process') }}" method="POST">
				@csrf

				{{-- Nama Lengkap --}}
				<div class="mb-3">
					<label class="form-label small fw-bold text-muted">NAMA LENGKAP / NAMA COUNTER</label>
					<div class="input-group custom-input-group rounded-3 overflow-hidden">
						<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
							<i class="fas fa-user-tag"></i>
						</span>
						<input type="text" name="name" class="form-control border-0 bg-transparent shadow-none text-body"
							   placeholder="Contoh: ID Celluler" value="{{ old('name') }}" required autocomplete="off">
					</div>
				</div>

				<div class="row">
					{{-- Email --}}
					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
								<i class="fas fa-envelope"></i>
							</span>
							<input type="email" name="email" class="form-control border-0 bg-transparent shadow-none text-body"
								placeholder="email@anda.com" value="{{ old('email') }}" required autocomplete="off">
						</div>
					</div>

					{{-- No HP (WhatsApp Style) --}}
					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">NOMOR WHATSAPP</label>

						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<div class="input-group-text border-0 bg-transparent pe-0 ps-3">
								<div class="d-flex align-items-center gap-2 border-end border-secondary border-opacity-25 pe-2">
									{{-- Bendera Indonesia (CDN) --}}
									<img src="https://flagcdn.com/w40/id.png" width="20" alt="ID" class="rounded-1">
								</div>
								<span class="fw-bold text-body ms-2">+62</span>
							</div>

							{{-- Input Nomor --}}
							<input type="tel" name="phone"
								class="form-control border-0 bg-transparent shadow-none text-body fw-bold"
								placeholder="8123456xxxx"
								value="{{ old('phone') }}"
								oninput="this.value = this.value.replace(/[^0-9]/g, '')"
								required autocomplete="off">
						</div>
						<div class="form-text small text-muted">Masukkan nomor tanpa angka 0 di depan.</div>
					</div>
				</div>

				{{-- Username --}}
				<div class="mb-3">
					<label class="form-label small fw-bold text-muted">USERNAME (LOGIN)</label>
					<div class="input-group custom-input-group rounded-3 overflow-hidden">
						<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">@</span>
						<input type="text" name="username" class="form-control border-0 bg-transparent shadow-none text-body"
							   placeholder="username_anda" value="{{ old('username') }}" required autocomplete="off">
					</div>
					<div class="form-text small text-muted">Tanpa spasi, maksimal 20 karakter.</div>
				</div>

				<hr class="my-4 opacity-10">

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">PASSWORD LOGIN</label>
						{{-- Container Group dengan Background & Border sendiri --}}
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							{{-- Input Transparan --}}
							<input type="password" name="password" id="regPassword"
							class="form-control border-0 bg-transparent shadow-none text-body" required>

							{{-- Tombol Transparan --}}
							<button class="btn border-0 text-muted pe-3" type="button"
								onclick="togglePassword('regPassword', this)" tabindex="-1">
								<i class="fas fa-eye-slash"></i>
							</button>
						</div>
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label small fw-bold text-muted">ULANGI PASSWORD</label>
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<input type="password" name="password_confirmation" id="regConfirm"
							class="form-control border-0 bg-transparent shadow-none text-body" required>

							<button class="btn border-0 text-muted pe-3" type="button"
								onclick="togglePassword('regConfirm', this)" tabindex="-1">
								<i class="fas fa-eye-slash"></i>
							</button>
						</div>
					</div>
				</div>

				{{-- =========================================
					 CHECKBOX SYARAT & KETENTUAN (BARU)
					 ========================================= --}}
				<div class="mb-4">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="terms" id="termsCheck">
						<label class="form-check-label small text-muted lh-sm" for="termsCheck">
							Saya menyetujui <a href="{{ route('pages.terms') }}" class="text-decoration-none fw-bold text-primary">Syarat & Ketentuan</a>
							serta <a href="{{ route('pages.privacyPolicy') }}" class="text-decoration-none fw-bold text-primary">Kebijakan Privasi</a> yang berlaku.
						</label>
					</div>
				</div>

				{{-- Tombol Daftar --}}
				<button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 mb-3 shadow-primary btn-anim">
					<i class="fas fa-user-plus me-2"></i> DAFTAR SEKARANG
				</button>

				<div class="text-center">
					<small class="text-muted">Sudah punya akun? <a href="{{ route('auth.login') }}" class="text-decoration-none fw-bold text-primary">Login Disini</a></small>
				</div>

			</form>
		</div>
	</div>
@endsection

@push('styles')
<style>
/* FIX: Agar Checkbox bisa diklik (Masalah Z-Index) */
	.form-check {
		position: relative;
		z-index: 10; /* Pastikan di atas background */
	}

	.form-check-input {
		position: relative;
		z-index: 20; /* Lebih tinggi dari container */
		cursor: pointer;
	}

	.form-check-label {
		position: relative;
		z-index: 20;
		cursor: pointer;
	}

	/* Styling saat dicentang agar sesuai tema */
	.form-check-input:checked {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}

	/* Style Border default */
	.form-check-input {
		border-color: var(--text-muted);
		background-color: transparent; /* Agar terlihat menyatu */
	}

	/* Efek Hover pada Link */
	.form-check-label a:hover {
		text-decoration: underline !important;
	}
</style>
@endpush
