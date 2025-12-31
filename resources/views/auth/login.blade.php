{{-- <!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - ID Cloud Store</title>

	<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='60' fill='%234f46e5'/%3E%3Ctext x='50' y='66' font-size='55' font-family='Arial Black, Roboto Black, sans-serif-black' font-weight='900' text-anchor='middle' fill='white'%3EICS%3C/text%3E%3C/svg%3E">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style>
		body {
			background-color: #f3f4f6;
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Segoe UI', sans-serif;
		}
		.card-login {
			border: none;
			border-radius: 16px;
			box-shadow: 0 10px 40px rgba(0,0,0,0.08);
			overflow: hidden;
			width: 100%;
			max-width: 400px;
		}
		.login-header {
			background: #1e1e2d; /* Warna Sidebar Anda */
			padding: 30px 20px;
			text-align: center;
			color: white;
		}
		.btn-primary {
			background-color: #667eea; /* Warna Primary Anda */
			border: none;
			padding: 12px;
			font-weight: 600;
			transition: all 0.2s;
		}
		.btn-primary:hover {
			background-color: #5a6fd6;
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
		}
		.form-control:focus {
			border-color: #667eea;
			box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
		}
	</style>
</head>
<body>

	<div class="card card-login">
		<div class="login-header">
			<a class="navbar-brand fw-bold d-flex align-items-center gap-2 justify-content-center" href="{{ route('pages.home') }}">
				<div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
					<i class="fa-solid fa-cloud-bolt"></i>
				</div>
				<h4 class="mb-0 fw-bold" style="color: var(--text-main);"> IDCloud<span class="text-primary">Store</span></h4>
			</a>
			<small class="text-white-50">Silakan login untuk melanjutkan</small>
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

			@if (session('success'))
				<div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 small">
					{{ session('success') }}
				</div>
			@endif

			<form action="{{ route('auth.login.process') }}" method="POST">
				@csrf

				<div class="mb-3">
					<label class="form-label small text-muted fw-bold">USERNAME / EMAIL</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
						<input type="text" name="identity" class="form-control border-start-0 ps-0" placeholder="Masukan email atau username" value="{{ old('identity') }}" required autofocus autocomplete="disable">
					</div>
				</div>

				<div class="mb-4">
					<label class="form-label small text-muted fw-bold">PASSWORD</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
						<input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukan kata sandi" required>
					</div>
				</div>

				<div class="d-flex justify-content-between align-items-center mb-4">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="remember" id="remember">
						<label class="form-check-label small text-secondary" for="remember">
							Ingat Saya
						</label>
					</div>
					<a href="#" class="small text-decoration-none text-muted">Lupa Password?</a>
				</div>

				<button type="submit" class="btn btn-primary w-100 rounded-3">
					MASUK <i class="fas fa-arrow-right ms-2"></i>
				</button>

			</form>
		</div>

		<div class="card-footer bg-light text-center py-3 border-top-0">
			<small class="text-muted">Belum punya akun? <a href="{{ route('auth.register') }}" class="text-decoration-none fw-bold" style="color: #667eea;">Daftar</a></small>
		</div>
	</div>

</body>
</html> --}}







@extends('layouts.auth')

@section('title', 'Login - IDCloudStore')

@push('styles')
<style>
	body {
		background-color: #f3f4f6;
		height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		font-family: 'Segoe UI', sans-serif;
	}
	.card-login { max-width: 400px; }
	.login-header {
		background: #1e1e2d; /* Warna Sidebar Anda */
		padding: 30px 20px;
		text-align: center;
		color: white;
	}
	.btn-primary {
		background-color: #667eea; /* Warna Primary Anda */
		border: none;
		padding: 12px;
		font-weight: 600;
		transition: all 0.2s;
	}
	.btn-primary:hover {
		background-color: #5a6fd6;
		transform: translateY(-2px);
		box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
	}
	.form-control:focus {
		border-color: #667eea;
		box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
	}
</style>
@endpush

@section('content')
	<div class="card card-login">
		<div class="login-header">
			<a class="navbar-brand fw-bold d-flex align-items-center gap-2 justify-content-center" href="{{ route('pages.home') }}">
				<div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
					<i class="fa-solid fa-cloud-bolt"></i>
				</div>
				<h4 class="mb-0 fw-bold"> IDCloud<span class="text-primary">Store</span></h4>
			</a>
			<small class="text-white-50">Silakan login untuk melanjutkan</small>
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

			@if (session('success'))
				<div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 small">
					{{ session('success') }}
				</div>
			@endif

			<form action="{{ route('auth.login.process') }}" method="POST">
				@csrf

				{{-- <div class="mb-3">
					<label class="form-label small text-muted fw-bold">USERNAME / EMAIL</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
						<input type="text" name="identity" class="form-control border-start-0 ps-0" placeholder="Masukan email atau username" value="{{ old('identity') }}" required autofocus autocomplete="disable">
					</div>
				</div> --}}

				{{-- <div class="mb-4">
					<label class="form-label small text-muted fw-bold">PASSWORD</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
						<input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukan kata sandi" required>
					</div>
				</div> --}}

				<div class="mb-3">
					<label class="form-label small fw-bold text-muted">USERNAME / EMAIL</label>
					<div class="input-group custom-input-group rounded-3 overflow-hidden">
						<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
							<i class="fas fa-user text-muted"></i>
						</span>
						<input type="text" name="identity" class="form-control border-0 bg-transparent shadow-none text-body"
							placeholder="Masukan email atau username" value="{{ old('identity') }}" required autocomplete="off">
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label small fw-bold text-muted">PASSWORD LOGIN</label>
					<div class="input-group custom-input-group rounded-3 overflow-hidden">
						<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
							<i class="fas fa-lock text-muted"></i>
						</span>
						<input type="password" name="password" class="form-control border-0 bg-transparent shadow-none text-body" id="login-password"
							placeholder="Masukan kata sandi" value="{{ old('password') }}" required autocomplete="off">

						<button class="btn border-0 text-muted pe-3" type="button"
							onclick="togglePassword('login-password', this)" tabindex="-1">
							<i class="fas fa-eye-slash"></i>
						</button>
					</div>
				</div>

				<div class="d-flex justify-content-between align-items-center mb-4">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="remember" id="remember">
						<label class="form-check-label small text-secondary" for="remember">
							Ingat Saya
						</label>
					</div>
					<a href="{{ route('auth.forgot.password') }}" class="small text-decoration-none text-muted">Lupa Password?</a>
				</div>

				<button type="submit" class="btn btn-primary w-100 rounded-3">
					MASUK <i class="fas fa-arrow-right ms-2"></i>
				</button>

			</form>
		</div>

		<div class="card-footer bg-light text-center py-3 border-top-0">
			<small class="text-muted">Belum punya akun? <a href="{{ route('auth.register') }}" class="text-decoration-none fw-bold" style="color: #667eea;">Daftar</a></small>
		</div>
	</div>
@endsection

@push('scripts')

@endpush
