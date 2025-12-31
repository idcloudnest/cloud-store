<div id="sidebar-wrapper" class="d-flex flex-column" style="overflow: hidden;">
	<div class="sidebar-heading d-flex align-items-center justify-content-center">
		<div class="bg-primary text-white rounded p-1 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
			<i class="fa-solid fa-cloud-bolt fa-lg"></i>
		</div>
		<div class="text-start" style="line-height: 1;">
			<div style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.5px;">
				ID<span class="text-primary">Cloud</span>Store
			</div>
			<div class="fw-bold" style="font-size: 0.6rem; letter-spacing: 2px; color: #6c757d;">ADMIN PANEL</div>
		</div>
	</div>

	<div class="list-group list-group-flush flex-grow-1 pb-4" style="overflow-y: auto; overflow-x: hidden;">
		<a href="{{ route('admin.dashboard') }}"
		class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
			<span class="menu-text"><i class="fas fa-tachometer-alt me-3 text-primary"></i>Dashboard</span>
		</a>

		<a href="{{ route('admin.transactions.index') }}"
		class="list-group-item list-group-item-action {{ Request::is('admin/transactions') ? 'active' : '' }}">
			<span class="menu-text"><i class="fas fa-shopping-cart me-3 text-success"></i>Transaksi</span>
		</a>

		@php $isProdukActive = Request::is('admin/games*', 'admin/voucher*'); @endphp

		{{-- Manajemen Produk --}}
		{{-- LOGIC PHP: Menentukan mana yang sedang aktif --}}
		@php
			// 1. Cek Sub-kategori
			$isMasterActive  = Request::is('admin/products/brands*', 'admin/products*');
			$isGameActive    = Request::is('admin/products/games*');
			$isVoucherActive = Request::is('admin/products/pulsa*'); // Asumsi URL: admin/pulsa/telkomsel, dll
			$isTokenActive   = Request::is('admin/products/pln*');

			// 2. Cek Induk (Manajemen Produk Aktif jika SALAH SATU anak aktif)
			$isProdukActive  = $isMasterActive || $isGameActive || $isVoucherActive || $isTokenActive;
		@endphp

		{{-- LINK UTAMA: MANAJEMEN PRODUK --}}
		<a href="#submenuProduk" data-bs-toggle="collapse"
			class="list-group-item list-group-item-action sidebar-dropdown {{ $isProdukActive ? 'active-parent' : '' }}"
			aria-expanded="{{ $isProdukActive ? 'true' : 'false' }}">
			<span class="menu-text"><i class="fas fa-box-open me-3 text-warning"></i>Manajemen Produk</span>
			<i class="fas fa-chevron-down dropdown-icon"></i>
		</a>

		{{-- CONTAINER DROPDOWN --}}
		<div class="collapse sidebar-submenu {{ $isProdukActive ? 'show' : '' }}" id="submenuProduk">

			<div class="fw-bold px-3 py-2 text-white-50" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 4px;">
				Data Master
			</div>
			<a href="{{ route('admin.products.brands.index') }}"
			class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/products/brands*') ? 'active' : '' }}">
				<i class="fas fa-tags me-2 text-white-50" style="font-size: 0.8rem;"></i> Data Brand
			</a>
			<a href="{{ route('admin.products.items.index') }}"
			class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/products/items') ? 'active' : '' }}">
				<i class="fas fa-cubes me-2 text-white-50" style="font-size: 0.8rem;"></i> Data Item / SKU
			</a>


			<div class="fw-bold px-3 py-2 mt-2 text-white-50" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 4px;">
				Kategori
			</div>
			<a href="#submenuGameMobile" data-bs-toggle="collapse"
			class="list-group-item list-group-item-action sidebar-dropdown {{ $isGameActive ? 'active-parent' : '' }}"
			aria-expanded="{{ $isGameActive ? 'true' : 'false' }}">
				<span class="menu-text">Game</span> <i class="fas fa-chevron-down dropdown-icon"></i>
			</a>
			<div class="collapse sidebar-sub-submenu {{ $isGameActive ? 'show' : '' }}" id="submenuGameMobile">
				<a href="{{ url('admin/games/mobile-legends') }}"
				class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/games/mobile-legends*') ? 'active' : '' }}">
					Mobile Legends
				</a>
				<a href="{{ url('admin/games/free-fire') }}"
				class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/games/free-fire*') ? 'active' : '' }}">
					Free Fire
				</a>
			</div>


			<a href="#submenuVoucher" data-bs-toggle="collapse"
			class="list-group-item list-group-item-action sidebar-dropdown {{ $isVoucherActive ? 'active-parent' : '' }}"
			aria-expanded="{{ $isVoucherActive ? 'true' : 'false' }}">
				<span class="menu-text">Voucher & Pulsa</span>
				<i class="fas fa-chevron-down dropdown-icon"></i>
			</a>
			<div class="collapse sidebar-sub-submenu {{ $isVoucherActive ? 'show' : '' }}" id="submenuVoucher">
				<a href="{{ url('admin/pulsa/indosat') }}"
				class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/pulsa/indosat*') ? 'active' : '' }}">
					Indosat (IM3)
				</a>
				<a href="{{ url('admin/pulsa/telkomsel') }}"
				class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/pulsa/telkomsel*') ? 'active' : '' }}">
					Telkomsel
				</a>
				<a href="{{ url('admin/pulsa/tri') }}"
				class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/pulsa/tri*') ? 'active' : '' }}">
					Tri (3)
				</a>
			</div>


			<a href="{{ url('admin/pln') }}"
			class="list-group-item list-group-item-action sidebar-link {{ Request::is('admin/pln*') ? 'active' : '' }}">
				Token PLN
			</a>
		</div>


		<a href="{{ route('admin.members.index') }}" class="list-group-item list-group-item-action {{ Request::is('admin/members') ? 'active' : '' }}">
			<span class="menu-text"><i class="fas fa-users me-3 text-info"></i>Member Area</span>
		</a>


		<a href="#" class="list-group-item list-group-item-action {{ Request::is('admin/laporan') ? 'active' : '' }}">
			<span class="menu-text"><i class="fas fa-chart-line me-3 text-default"></i>Laporan</span>
		</a>

	</div>
	<div class="list-group list-group-flush border-top border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
        <a href="{{ route('auth.logout') }}" class="list-group-item list-group-item-action sidebar-link text-danger py-3">
            <span class="menu-text"><i class="fas fa-power-off me-3"></i>Logout</span>
        </a>
    </div>
</div>
