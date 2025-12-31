{{-- <nav class="navbar navbar-expand-lg fixed-top">
	<div class="container">
		<a class="navbar-brand fw-bold text-light" href="{{ route('pages.home') }}">
			<i class="fas fa-cloud text-primary me-2"></i><span style="color: var(--text-main);">ID Cloud</span><span class="text-primary">Store</span>
		</a>

		<div class="d-flex align-items-center gap-3 order-lg-last">
			<button class="btn btn-outline-secondary rounded-circle" id="themeToggle" style="width:40px; height:40px;">
				<i class="fas fa-moon"></i>
			</button>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ms-auto me-4">
				<li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Beranda</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ url('/#products') }}">Produk</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ url('/order') }}">Lacak Pesanan</a></li>
			</ul>
		</div>
	</div>
</nav> --}}


@php $route = Route::currentRouteName(); @endphp

<nav class="navbar navbar-expand-lg fixed-top transition-all">
	<div class="container">

		{{-- LOGO (Tetap di Kiri) --}}
		<a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
			<div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
				<i class="fa-solid fa-cloud-bolt"></i>
			</div>
			{{-- <span class="d-none d-sm-inline" style="color: var(--text-main);">ID<span class="text-primary">Cloud</span></span> --}}
			<span style="color: var(--text-main);">ID Cloud</span><span class="text-primary">Store</span>
		</a>

		{{-- TOGGLER MOBILE --}}
		<div class="d-flex align-items-center gap-2 order-lg-last">
			<button class="btn btn-link nav-link me-2 d-lg-none themeToggle">
				<i class="fas fa-moon"></i>
			</button>

			<button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="fas fa-bars fa-lg" style="color: var(--text-main);"></span>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbarNav">

			{{-- 1. MENU UTAMA (KIRI - Sesuai Foto) --}}
			<ul class="navbar-nav me-auto ms-lg-4 gap-lg-4 my-3 my-lg-0">

				<li class="nav-item">
					{{-- Active State: Topup (Contoh) --}}
					<a class="nav-link custom-nav-link {{ $route == 'pages.home' ? 'active' : '' }}" href="{{ url('/') }}">
						<i class="fas fa-briefcase me-2"></i> Topup
					</a>
				</li>

				<li class="nav-item">
					<a class="nav-link custom-nav-link {{ $route == 'pages.invoices' ? 'active' : '' }}" href="{{ route('pages.invoices') }}">
						<i class="fas fa-search me-2"></i> Lacak Pesanan
					</a>
				</li>

			</ul>

			{{-- 2. MENU AUTH (KANAN - Sesuai Foto) --}}
			<div class="d-flex align-items-lg-center flex-column flex-lg-row gap-3 ms-auto">

				{{-- Theme Toggle (Desktop Only) --}}
				<button class="btn btn-link nav-link d-none d-lg-block me-2 themeToggle" id="themeToggle">
					<i class="fas fa-moon"></i>
				</button>

				{{-- Separator Tipis --}}
				<div class="d-none d-lg-block border-end mx-2" style="height: 20px; border-color: var(--text-muted) !important; opacity: 0.3;"></div>

				@guest
					<a href="{{ route('auth.login') }}" class="nav-link custom-nav-link">
						<i class="fas fa-sign-in-alt me-2"></i> Masuk
					</a>
					<a href="{{ route('auth.register') }}" class="nav-link custom-nav-link">
						<i class="fas fa-user-plus me-2"></i> Daftar
					</a>
				@else
					{{-- Jika sudah login --}}
					<a href="{{ route('member.dashboard') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold text-white">
						<i class="fas fa-user-circle me-1"></i> Dashboard
					</a>
				@endguest

			</div>
		</div>
	</div>
</nav>


@push('styles')
<style>
	/* --- CUSTOM NAVBAR STYLE (Sesuai Referensi) --- */

	.navbar {
		/* Pastikan navbar punya background (transparan/solid sesuai selera) */
		/* background-color: var(--navbar-bg);
		border-bottom: 1px solid var(--card-border);
		padding-top: 0.8rem;
		padding-bottom: 0.8rem; */
	}

	/* Style Link Default */
	.custom-nav-link {
		color: var(--text-muted) !important; /* Warna abu-abu default */
		font-weight: 500;
		font-size: 0.95rem;
		padding-bottom: 5px; /* Jarak text ke garis bawah */
		position: relative;
		transition: all 0.3s ease;
	}

	/* Hover State */
	.custom-nav-link:hover {
		color: var(--text-main) !important;
	}

	/* ACTIVE STATE (Penting: Warna Orange + Garis Bawah) */
	.custom-nav-link.active {
		color: #f97316 !important; /* Warna Orange terang */
		font-weight: 600;
	}

	/* Membuat Garis Bawah Oranye */
	.custom-nav-link.active::after {
		content: '';
		position: absolute;
		bottom: -14px; /* Sesuaikan agar menempel di border navbar */
		left: 0;
		width: 100%;
		height: 3px;
		background-color: #f97316; /* Garis Orange */
		border-radius: 2px 2px 0 0;
	}

	/* Penyesuaian Mobile: Hilangkan garis bawah saat di HP agar tidak aneh */
	@media (max-width: 991px) {
		.custom-nav-link.active::after {
			display: none;
		}
		.custom-nav-link.active {
			border-left: 3px solid #f97316;
			padding-left: 10px;
			background: rgba(249, 115, 22, 0.05);
		}
	}
</style>
@endpush
