{{-- <nav class="navbar navbar-expand-lg navbar-dark transition-all">
	<div class="container">

		<a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ url('/') }}">
			<div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
				<i class="fa-solid fa-cloud-bolt"></i>
			</div>
			<span style="color: var(--text-main);">ID<span class="text-primary">Cloud</span></span>
		</a>


		<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
			<span class="fa-solid fa-bars" style="color: var(--text-main);"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ms-auto align-items-center gap-3">


				<li class="nav-item">
					<a class="nav-link fw-semibold" style="color: var(--text-main);" href="#">Beranda</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" style="color: var(--text-main);" href="#">Cek Pesanan</a>
				</li>
				<li class="nav-item">
					<a class="nav-link fw-semibold" style="color: var(--text-main);" href="#">Daftar Harga</a>
				</li>


				<li class="nav-item">
					<button class="btn rounded-circle border-0" id="themeToggle" style="background: var(--bg-body); color: var(--text-main); width:40px; height:40px;">
						<i class="fas fa-moon"></i>
					</button>
				</li>

				<li class="nav-item border-start ps-3 d-none d-lg-block" style="border-color: var(--card-border) !important;"></li>


				@auth
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown" style="color: var(--text-main);">
							Halo, {{ Auth::user()->name }}
						</a>
						<ul class="dropdown-menu dropdown-menu-end shadow border-0" style="background: var(--card-bg);">
							<li><a class="dropdown-item" style="color: var(--text-main);" href="{{ route('member.dashboard') }}">Dashboard</a></li>
							<li><hr class="dropdown-divider" style="border-color: var(--card-border);"></li>
							<li>
								<form action="{{ route('logout') }}" method="POST">
									@csrf
									<button type="submit" class="dropdown-item text-danger">Logout</button>
								</form>
							</li>
						</ul>
					</li>
				@else
					<li class="nav-item">
						<a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-4 rounded-pill">Masuk</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('register') }}" class="btn btn-primary fw-bold px-4 rounded-pill text-white shadow-sm">Daftar</a>
					</li>
				@endauth

			</ul>
		</div>
	</div>
</nav> --}}


<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-0">
        {{-- Profile Mini --}}
        <div class="p-4 text-center border-bottom border-secondary border-opacity-10">
            <div class="mb-3 position-relative d-inline-block">
                {{-- Avatar --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff"
                     class="rounded-circle border border-2 border-primary p-1" width="80" height="80">

                {{-- Badge Status --}}
                <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-light rounded-circle">
                    <span class="visually-hidden">Online</span>
                </span>
            </div>
            <h6 class="fw-bold mb-0 text-body">{{ Auth::user()->name }}</h6>
            <small class="text-muted">{{ Auth::user()->email }}</small>
        </div>

        {{-- Menu List --}}
        <div class="list-group list-group-flush py-2">

            {{-- Helper untuk cek active state --}}
            @php $route = Route::currentRouteName(); @endphp

            <a href="{{ route('member.dashboard') }}"
               class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 {{ $route == 'member.dashboard' ? 'active-menu' : '' }}">
                <i class="fas fa-home fa-fw"></i> Dashboard
            </a>

            <a href="{{ route('member.riwayat') }}"
               class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 {{ $route == 'member.riwayat' ? 'active-menu' : '' }}">
                <i class="fas fa-history fa-fw"></i> Riwayat Transaksi
            </a>

            <a href="#"
               class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3">
                <i class="fas fa-wallet fa-fw"></i> Deposit Saldo
            </a>

            <a href="#"
               class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3">
                <i class="fas fa-cog fa-fw"></i> Pengaturan Akun
            </a>

            {{-- Logout --}}
            <a href="{{ route('auth.logout') }}"
               class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center gap-3 text-danger mt-2">
                <i class="fas fa-sign-out-alt fa-fw"></i> Keluar
            </a>
			<div class="px-4 mt-4 mb-3 d-lg-none"> {{-- d-lg-none: Agar tidak muncul di Desktop --}}
                <button type="button"
                        class="btn btn-soft-danger w-100 py-2 fw-bold rounded-3"
                        data-bs-dismiss="offcanvas">
                    <i class="fas fa-times me-2"></i> TUTUP MENU
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Style Khusus Sidebar --}}
<style>
    .list-group-item {
        background: transparent;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    .list-group-item:hover {
        background-color: rgba(var(--primary-color), 0.05);
        color: var(--primary-color);
        transform: translateX(5px);
    }
    /* State Aktif dengan Efek Neon */
    .active-menu {
        background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
        color: var(--primary-color) !important;
        border-left: 4px solid var(--primary-color);
        font-weight: 600;
    }
    /* Dark Mode Adjustment untuk text */
    [data-bs-theme="dark"] .text-body { color: #fff !important; }
</style>
