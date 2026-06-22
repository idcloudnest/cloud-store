@extends('layouts.admin')

@section('title', 'Transaksi Manual')

@section('content')
	<div class="row justify-content-center">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-start mb-4">
				<div>
					<h4 class="mb-1 fw-bold text-dark">Form Transaksi</h4>
					<p class="text-muted small mb-0">Input transaksi pelanggan secara manual.</p>
				</div>

				<div class="d-none d-md-flex align-items-center gap-2 small text-muted">
					<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">1. Mode</span>
					<span>›</span>
					<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">2. Kategori</span>
					<span>›</span>
					<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">3. Produk</span>
					<span>›</span>
					<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">4. Detail</span>
				</div>
			</div>

			<form id="form-transaction" action="{{ route('admin.transactions.store') }}" method="POST">
				@csrf

				<input type="hidden" name="category_id" id="hidden_category_id">
				<input type="hidden" name="category" id="category-slug">
				<input type="hidden" name="transaction_type" id="transaction_type" value="prabayar">
				<input type="hidden" name="product_code" id="product-code" required>
				<input type="hidden" name="inquiry_ref_id" id="inquiry_ref_id">
				<input type="hidden" id="bill_amount_value">
				<input type="hidden" id="bill_admin_fee_value">
				<input type="hidden" id="bill_total_pay_value">
				<input id="customer-name-input" name="customer_name" type="hidden">

				<div class="row g-4">
					<div class="col-lg-8">
						<div class="card border-0 shadow-sm product-browser-card">
							<div class="card-body p-4">

								<div class="mode-switch mb-4">
									<button type="button" class="mode-btn active" id="tab-prabayar" onclick="switchMode('prabayar')">
										<i class="fas fa-bolt me-2"></i> Prabayar
									</button>
									<button type="button" class="mode-btn" id="tab-pascabayar" onclick="switchMode('pascabayar')">
										<i class="fas fa-receipt me-2"></i> Pascabayar
									</button>
								</div>

								<div class="section-block mb-4">
									<div class="d-flex align-items-center justify-content-between mb-3">
										<div>
											<h6 class="fw-bold text-dark mb-1">Pilih Kategori</h6>
											<p class="text-muted small mb-0" id="category-helper-text">Pilih kategori layanan yang ingin ditransaksikan.</p>
										</div>
									</div>

									<div class="category-scroll">
										<div class="d-flex gap-2 flex-nowrap p-1" id="category-grid">
											@foreach($categories as $cat)
												@php
													$parentSlug = $cat->parent->slug ?? '';
													$slug = $cat->slug;

													$icon = $cat->icon ?? 'fa-cube';

													if(str_contains($slug, 'pln')) $icon = 'fa-bolt';
													elseif(str_contains($slug, 'game')) $icon = 'fa-gamepad';
													elseif(str_contains($slug, 'operator-seluler')) $icon = 'fa-mobile-alt';
													elseif(str_contains($slug, 'data')) $icon = 'fa-wifi';
													elseif(str_contains($slug, 'money')) $icon = 'fa-wallet';
													elseif(str_contains($slug, 'voucher')) $icon = 'fa-ticket-alt';
													elseif(str_contains($slug, 'tagihan')) $icon = 'fa-receipt';
												@endphp

												<div class="category-item"
													data-name="{{ strtolower($cat->name) }}"
													data-slug="{{ $cat->slug }}"
													data-parent="{{ $parentSlug }}">
													<button type="button"
														class="category-card"
														onclick="selectCategory(this, {{json_encode([
															'id' => $cat->id,
															'name' => strtolower($cat->name),
															'slug' => $cat->slug,
															'parent' => optional($cat->parent)->slug
														])}})">
														<span class="category-icon">
															<i class="fas {{ $icon }}"></i>
														</span>
														<span class="category-name">{{ strtolower($cat->name) == 'e-money' ? $cat->name . " / Wallet" : $cat->name }}</span>
													</button>
												</div>
											@endforeach
										</div>
									</div>
								</div>

								<div class="section-block">
									<div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-end mb-3">
										<div>
											<h6 class="fw-bold text-dark mb-1" id="label-product">Pilih Produk</h6>
											<p class="text-muted small mb-0" id="product-helper-text">Produk akan muncul setelah kategori dipilih.</p>
										</div>

										<div class="d-flex gap-2">
											<div class="input-group input-group-sm product-search">
												<span class="input-group-text bg-white border-end-0 text-muted">
													<i class="fas fa-search"></i>
												</span>
												<input type="text" id="searchProduct" class="form-control border-start-0 shadow-none" placeholder="Cari produk...">
											</div>

											<select id="sortProduct" class="form-select form-select-sm shadow-none product-sort">
												{{-- <option value="nominal_asc">Nominal Terkecil</option> --}}
												<option value="price_asc">Termurah</option>
												<option value="price_desc">Termahal</option>
												<option value="name_asc">Nama A-Z</option>
											</select>
										</div>
									</div>

									<div id="container-filter" class="filter-panel d-none">
										<div class="filter-row d-none" id="categories-filter-wrapper">
											<div class="filter-label">Tipe</div>
											<div class="filter-chips" id="categories-filter-list"></div>
										</div>

										<div class="filter-row d-none" id="brands-filter-wrapper">
											<div class="filter-label">Brand</div>
											<div class="filter-chips" id="brands-filter-list"></div>
										</div>
									</div>

									<div class="product-scroll-wrapper mt-3">
										<div id="product-grid-container" class="row g-3 p-2">
											<div class="col-12">
												<div class="empty-state">
													<i class="fas fa-hand-pointer"></i>
													<h6>Pilih kategori dulu</h6>
													<p>Produk akan ditampilkan berdasarkan kategori dan mode transaksi.</p>
												</div>
											</div>
										</div>
									</div>

									<div id="product-pagination" class="mt-3"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="card border-0 shadow-sm transaction-summary-card">
							<div class="card-body p-4">
								<div id="summary-empty">
									<div class="summary-empty-state">
										<div class="summary-empty-icon">
											<i class="fas fa-shopping-basket"></i>
										</div>
										<h6 class="fw-bold mb-2">Belum ada produk dipilih</h6>
										<p class="text-muted small mb-0">
											Pilih mode, kategori, lalu klik salah satu produk untuk mulai transaksi.
										</p>
									</div>
								</div>

								<div id="summary-content" class="d-none">
									<div class="d-flex align-items-start justify-content-between mb-3">
										<div>
											<h6 class="fw-bold mb-1">Produk Dipilih</h6>
											<p class="text-muted small mb-0">Pastikan produk dan data pelanggan sudah benar.</p>
										</div>
										<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" id="summary-mode">Prabayar</span>
									</div>

									<div class="selected-product-box mb-4">
										<div class="d-flex align-items-start gap-3">
											<div class="selected-product-icon">
												<i class="fas fa-box"></i>
											</div>
											<div class="flex-grow-1">
												<div class="fw-bold text-dark" id="summary-product-name">-</div>
												<div class="small text-muted" id="summary-product-meta">-</div>
												<div class="fw-bold text-success mt-2" id="summary-product-price">-</div>
											</div>
										</div>
									</div>

									<div class="mb-4">
										<label class="form-label text-muted small fw-bold text-uppercase">Pelanggan</label>
										<select class="form-select" id="user-id" name="user_id" required>
											<option value="{{ auth()->id() }}"
												data-role="{{ auth()->user()->role }}"
												data-balance="{{ auth()->user()->balance_formatted }}"
												data-self="1">
												SAYA SENDIRI
											</option>

											@foreach($users as $user)
												@if($user->id != auth()->id())
													<option value="{{ $user->id }}"
														data-role="{{ $user->role }}"
														data-balance="{{ $user->balance_formatted }}">
														{{ $user->username }}
													</option>
												@endif
											@endforeach
										</select>
									</div>

									<div class="mb-4">
										<label id="label-target" class="form-label text-muted small fw-bold text-uppercase">Nomor / ID Tujuan</label>

										<div class="input-group clean-input" id="standard-input-box">
											<span class="input-group-text">
												<i id="icon-target" class="fas fa-phone-alt"></i>
											</span>
											<input type="text"
												class="form-control"
												id="target"
												name="target"
												placeholder="Masukan nomor..."
												autocomplete="off"
												oninput="getUsername(this)">
										</div>

										<div class="row g-2 d-none" id="game-input-box">
											<div class="col-7">
												<div class="input-group clean-input">
													<span class="input-group-text"><i class="fas fa-user"></i></span>
													<input type="text"
														class="form-control"
														id="game_user_id"
														name="game_user_id"
														placeholder="User ID"
														data-type="user-id"
														oninput="getUsername(this)"
														disabled>
												</div>
											</div>
											<div class="col-5">
												<div class="input-group clean-input">
													<span class="input-group-text"><i class="fas fa-server"></i></span>
													<input type="text"
														class="form-control"
														id="game_server_id"
														name="game_server_id"
														placeholder="Zone ID"
														data-type="server-id"
														oninput="getUsername(this)"
														disabled>
												</div>
											</div>
										</div>

										<div id="customer-name-result" class="form-text fw-bold mt-2 ps-1" style="display: none;"></div>
									</div>

									<div class="mb-4" id="box-custom-price">
										<label class="form-label text-muted small fw-bold text-uppercase">Harga Jual Override</label>
										<div class="input-group clean-input">
											<span class="input-group-text">Rp</span>
											<input type="number" class="form-control" name="custom_price" placeholder="Default">
										</div>
										<div class="form-text">Kosongkan kalau ingin memakai harga default produk.</div>
									</div>

									<div id="bill-details" class="bill-box d-none mb-4">
										<div class="d-flex align-items-center gap-2 mb-3">
											<i class="fas fa-receipt text-primary"></i>
											<div>
												<div class="fw-bold">Rincian Tagihan</div>
												<div class="small text-muted">Pastikan data sesuai sebelum bayar.</div>
											</div>
										</div>

										<div class="bill-row">
											<span>Nama Pelanggan</span>
											<strong id="bill-name">-</strong>
										</div>
										<div class="bill-row">
											<span>Periode / Detail</span>
											<strong id="bill-period">-</strong>
										</div>
										<div class="bill-row">
											<span>Jumlah Tagihan</span>
											<strong id="bill-amount">-</strong>
										</div>
										<div class="bill-row">
											<span>Biaya Admin</span>
											<strong id="bill-admin">-</strong>
										</div>
										<div class="bill-total">
											<span>Total Bayar</span>
											<strong id="bill-total">-</strong>
										</div>
									</div>

									<div class="d-grid gap-2">
										<button type="button" onclick="inquiryBill()" id="btn-check-bill" class="btn btn-info text-white fw-bold py-2 d-none">
											<i class="fas fa-search me-2"></i> Cek Tagihan
										</button>

										<button type="button" onclick="storeTransaction()" id="btn-submit" class="btn btn-primary fw-bold py-2">
											<i class="fas fa-paper-plane me-2"></i> Proses Transaksi
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</form>
		</div>
	</div>
@endsection

@push('styles')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

	<style>
		.product-browser-card,
		.transaction-summary-card {
			border-radius: 18px;
		}

		.transaction-summary-card {
			position: sticky;
			top: 90px;
		}

		.mode-switch {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			background: #f1f5f9;
			padding: 6px;
			border-radius: 14px;
			gap: 6px;
		}

		.mode-btn {
			border: 0;
			background: transparent;
			color: #64748b;
			font-weight: 700;
			border-radius: 10px;
			padding: 12px;
			transition: .2s ease;
		}

		.mode-btn.active {
			background: #2563eb;
			color: #fff;
			box-shadow: 0 8px 18px rgba(37, 99, 235, .24);
		}

		.section-block {
			border: 1px solid #eef2f7;
			border-radius: 16px;
			padding: 18px;
			background: #fff;
		}

		.category-scroll {
			overflow-x: auto;
			padding-bottom: 4px;
		}

		.category-scroll::-webkit-scrollbar {
			height: 5px;
		}

		.category-scroll::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 999px;
		}

		.category-item {
			min-width: 128px;
		}

		.category-card {
			width: 100%;
			min-height: 92px;
			border: 1px solid #e2e8f0;
			background: #f8fafc;
			border-radius: 14px;
			padding: 14px 10px;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 8px;
			transition: .2s ease;
		}

		.category-card:hover {
			border-color: #2563eb;
			background: #eff6ff;
			transform: translateY(-2px);
		}

		.category-card.active {
			border-color: #2563eb;
			background: #eff6ff;
			box-shadow: 0 0 0 2px rgba(37, 99, 235, .14);
		}

		.category-icon {
			width: 34px;
			height: 34px;
			border-radius: 12px;
			background: #fff;
			color: #2563eb;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 10px rgba(15, 23, 42, .06);
		}

		.category-name {
			font-size: .76rem;
			font-weight: 800;
			text-transform: uppercase;
			color: #1f2937;
			text-align: center;
			line-height: 1.25;
		}

		.product-search {
			width: 230px;
		}

		.product-sort {
			width: 130px;
		}

		.filter-panel {
			background: #f8fafc;
			border: 1px solid #eef2f7;
			border-radius: 14px;
			padding: 12px;
		}

		.filter-row {
			display: flex;
			gap: 12px;
			align-items: flex-start;
			padding: 8px 0;
		}

		.filter-row + .filter-row {
			border-top: 1px solid #e2e8f0;
		}

		.filter-label {
			width: 52px;
			font-size: .75rem;
			font-weight: 800;
			color: #64748b;
			text-transform: uppercase;
			padding-top: 6px;
		}

		.filter-chips {
			display: flex;
			flex-wrap: wrap;
			gap: 8px;
			flex: 1;
		}

		.filter-chip {
			border: 1px solid #e2e8f0;
			background: #fff;
			color: #334155;
			border-radius: 999px;
			padding: 7px 12px;
			font-size: .78rem;
			font-weight: 700;
			cursor: pointer;
			transition: .2s ease;
		}

		.filter-chip:hover,
		.filter-chip.active {
			background: #10b981;
			border-color: #10b981;
			color: #fff;
		}

		.product-scroll-wrapper {
			max-height: 650px;
			overflow-y: auto;
			overflow-x: hidden;
			padding-right: 6px;
		}

		.product-scroll-wrapper::-webkit-scrollbar {
			width: 6px;
		}

		.product-scroll-wrapper::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 999px;
		}

		.product-card {
			height: 100%;
			border: 1px solid #e2e8f0;
			border-radius: 16px;
			background: #fff;
			padding: 14px;
			cursor: pointer;
			transition: .2s ease;
			display: flex;
			flex-direction: column;
			min-height: 168px;
		}

		.product-card:hover {
			border-color: #2563eb;
			box-shadow: 0 12px 24px rgba(15, 23, 42, .06);
			transform: translateY(-2px);
		}

		.product-card.selected {
			border-color: #2563eb;
			background: #eff6ff;
			box-shadow: 0 0 0 2px rgba(37, 99, 235, .12);
		}

		.product-card.locked {
			opacity: .55;
			cursor: not-allowed;
			filter: grayscale(.8);
		}

		.product-status {
			font-size: .72rem;
			font-weight: 800;
			border-radius: 999px;
			padding: 4px 8px;
		}

		.product-status.ready {
			background: rgba(16, 185, 129, .12);
			color: #059669;
		}

		.product-status.down {
			background: rgba(239, 68, 68, .12);
			color: #dc2626;
		}

		.product-name {
			font-size: .92rem;
			font-weight: 800;
			color: #1e293b;
			line-height: 1.3;
			margin-bottom: 6px;
		}

		.product-meta {
			font-size: .75rem;
			color: #64748b;
			line-height: 1.3;
		}

		.product-price {
			margin-top: auto;
			background: #f8fafc;
			border-radius: 12px;
			padding: 10px 12px;
			font-weight: 900;
			color: #10b981;
		}

		.empty-state,
		.summary-empty-state {
			border: 1px dashed #cbd5e1;
			border-radius: 16px;
			background: #f8fafc;
			padding: 42px 20px;
			text-align: center;
			color: #64748b;
		}

		.empty-state i,
		.summary-empty-icon {
			font-size: 2rem;
			color: #94a3b8;
			margin-bottom: 12px;
		}

		.summary-empty-icon {
			width: 58px;
			height: 58px;
			margin: 0 auto 14px;
			border-radius: 18px;
			background: #eef2ff;
			color: #2563eb;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.selected-product-box {
			background: #f8fafc;
			border: 1px solid #eef2f7;
			border-radius: 16px;
			padding: 14px;
		}

		.selected-product-icon {
			width: 42px;
			height: 42px;
			border-radius: 14px;
			background: #eff6ff;
			color: #2563eb;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.clean-input {
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			overflow: hidden;
			background: #fff;
		}

		.clean-input .input-group-text {
			border: 0;
			background: #f8fafc;
			color: #64748b;
		}

		.clean-input .form-control {
			border: 0;
			box-shadow: none;
		}

		.bill-box {
			background: #eff6ff;
			border: 1px solid #bfdbfe;
			border-radius: 16px;
			padding: 14px;
		}

		.bill-row {
			display: flex;
			justify-content: space-between;
			gap: 12px;
			padding: 8px 0;
			font-size: .85rem;
			border-bottom: 1px solid rgba(37, 99, 235, .12);
		}

		.bill-row span {
			color: #64748b;
		}

		.bill-row strong {
			text-align: right;
			color: #1e293b;
		}

		.bill-total {
			display: flex;
			justify-content: space-between;
			gap: 12px;
			padding-top: 12px;
			font-weight: 900;
			color: #2563eb;
		}

		#product-pagination .btn {
			min-width: 36px;
			border-radius: 10px;
		}

		@media (max-width: 767px) {
			.product-search,
			.product-sort {
				width: 100%;
			}

			.category-item {
				min-width: 118px;
			}

			.transaction-summary-card {
				position: static;
			}
		}
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		const state = {
			mode: 'prabayar',
			category: null,
			categoryId: null,
			categorySlug: null,
			typeCategoryId: null,
			brandId: null,
			product: null,
			page: 1,
			search: '',
			sort: 'price_asc',
			inquiryRefId: null,
		};

		let typingTimer;
		let searchTimer;
		const doneTypingInterval = 800;

		const config = {
			'operator-seluler': { label: 'Nomor Handphone', icon: 'fa-mobile-alt', placeholder: '0812xxxx' },
			'token-pln': { label: 'Nomor Meter / ID Pel', icon: 'fa-bolt', placeholder: 'Masukan No Meter / ID Pel' },
			'pln': { label: 'ID Pelanggan PLN', icon: 'fa-bolt', placeholder: 'Masukan ID Pelanggan PLN' },
			'games': { label: 'ID Player', icon: 'fa-gamepad', placeholder: 'User ID' },
			'e-money': { label: 'Nomor Handphone', icon: 'fa-wallet', placeholder: '0812xxxx' },
			'voucher': { label: 'Nomor', icon: 'fa-ticket-alt', placeholder: '0812xxxx' },
			'streaming': { label: 'Nomor HP / Akun', icon: 'fa-play-circle', placeholder: '0812xxxx' },
			'tagihan-ppob': { label: 'ID Pelanggan', icon: 'fa-receipt', placeholder: 'Masukan ID Pelanggan' },
		};

		$(document).ready(async function () {
			if (typeof initModul === 'function') {
				try {
					const module = await initModul();
					window.$formatter = module.formatter;
					window.$swal = module.swal;
				} catch (e) {}
			}

			$('#user-id').select2({
				theme: 'bootstrap-5',
				width: '100%',
				templateResult: formatUser,
				templateSelection: formatUser,
				escapeMarkup: m => m
			});

			$('#user-id').on('change', function () {
				if (state.categoryId) {
					state.page = 1;
					loadProducts();
				}
			});

			$('#searchProduct').on('input', function () {
				clearTimeout(searchTimer);
				state.search = $(this).val();

				searchTimer = setTimeout(function () {
					state.page = 1;
					if (state.categoryId) loadProducts();
				}, 350);
			});

			$('#sortProduct').on('change', function () {
				state.sort = $(this).val();
				state.page = 1;
				if (state.categoryId) loadProducts();
			});

			switchMode('prabayar');
		});

		function switchMode(mode) {
			state.mode = mode;
			state.page = 1;

			$('#transaction_type').val(mode);

			$('#tab-prabayar').toggleClass('active', mode === 'prabayar');
			$('#tab-pascabayar').toggleClass('active', mode === 'pascabayar');

			$('#category-helper-text').text(
				mode === 'pascabayar'
					? 'Pilih kategori tagihan seperti PLN, PDAM, BPJS, dan sejenisnya.'
					: 'Pilih kategori produk prabayar seperti games, pulsa, token PLN, e-money, dan voucher.'
			);

			$('.category-item').each(function () {
				const category = {
					name: $(this).data('name') ?? '',
					slug: $(this).data('slug') ?? '',
					parent: $(this).data('parent') ?? '',
				};

				const matchPasca = isPascaCategory(category);
				$(this).toggleClass('d-none', mode === 'pascabayar' ? !matchPasca : matchPasca);
			});

			resetSelection();
			renderInitialProductState();
		}

		function isPascaCategory(category = {}) {
			const name = String(category.name ?? '').toLowerCase();
			const slug = String(category.slug ?? '').toLowerCase();
			const parent = String(category.parent ?? '').toLowerCase();

			const prepaidSlugs = [
				'token-pln',
				'pulsa',
				'operator-seluler',
				'data',
				'games',
				'e-money',
				'voucher',
				'streaming'
			];

			const pascaSlugs = [
				'pln',
				'pdam',
				'bpjs',
				'telkom',
				'internet',
				'tv-kabel',
				'multifinance',
				'pbb',
				'pgn',
				'samsat',
				'tagihan-ppob',
				'pln-pascabayar',
				'tagihan-pln',
				'listrik-pasca',
			];

			if (prepaidSlugs.includes(slug)) return false;
			if (pascaSlugs.includes(slug)) return true;

			return name.includes('tagihan') ||
				name.includes('pascabayar') ||
				slug.includes('tagihan') ||
				slug.includes('pascabayar') ||
				slug.includes('pasca') ||
				parent.includes('tagihan') ||
				parent.includes('pascabayar') ||
				parent.includes('pasca');
		}

		function selectCategory(el, category) {
			state.category = category;
			state.categoryId = category.id;
			state.categorySlug = category.slug;
			state.typeCategoryId = null;
			state.brandId = null;
			state.product = null;
			state.page = 1;
			state.inquiryRefId = null;

			$('#hidden_category_id').val(category.id);
			$('#category-slug').val(category.slug);
			$('#product-code').val('');
			$('#inquiry_ref_id').val('');

			$('.category-card').removeClass('active');
			$(el).addClass('active');

			applyTargetConfig(category);
			resetCustomerFields();
			resetBill();
			renderSummaryEmpty();
			loadProducts();
			generateProviderFilter(category.id);
		}

		function applyTargetConfig(category) {
			// const conf = config[slug] || {
			// 	label: 'Nomor / ID Tujuan',
			// 	icon: 'fa-phone-alt',
			// 	placeholder: 'Masukan nomor atau ID tujuan'
			// };

			// $('#label-target').text(conf.label);
			// $('#target').attr('placeholder', conf.placeholder);
			// $('#icon-target').attr('class', 'fas ' + conf.icon);

			// if (slug === 'games') {
			// 	$('#standard-input-box').addClass('d-none');
			// 	$('#game-input-box').removeClass('d-none');
			// 	$('#game_user_id, #game_server_id').prop('disabled', false);
			// } else {
			// 	$('#standard-input-box').removeClass('d-none');
			// 	$('#game-input-box').addClass('d-none');
			// 	$('#game_user_id, #game_server_id').prop('disabled', true);
			// }

			const slug = String(category?.slug ?? '').toLowerCase();

			const conf = config[slug] || {
				label: 'Nomor / ID Tujuan',
				icon: 'fa-phone-alt',
				placeholder: 'Masukan nomor atau ID tujuan'
			};

			$('#label-target').text(conf.label);
			$('#target').attr('placeholder', conf.placeholder);
			$('#icon-target').attr('class', 'fas ' + conf.icon);

			if (isGameCategory(category)) {
				$('#label-target').text('ID Player');

				$('#standard-input-box').addClass('d-none');
				$('#target').prop('disabled', true);

				$('#game-input-box').removeClass('d-none');
				$('#game_user_id').prop('disabled', false);
				$('#game_server_id').prop('disabled', false);

				$('#game_user_id').attr('placeholder', 'User ID');
				$('#game_server_id').attr('placeholder', 'Zone ID');
				return;
			}

			$('#standard-input-box').removeClass('d-none');
			$('#target').prop('disabled', false);

			$('#game-input-box').addClass('d-none');
			$('#game_user_id').prop('disabled', true);
			$('#game_server_id').prop('disabled', true);
		}

		function loadProducts() {
			const grid = $('#product-grid-container');
			const role = $('#user-id').find(':selected').data('role');

			$('#product-pagination').empty();
			grid.html(loadingHTML());

			$.ajax({
				url: "{{ route('admin.products.items.getProductsByCategory') }}",
				type: "POST",
				data: {
					mode: state.mode,
					category_id: state.categoryId,
					categories: state.typeCategoryId,
					brands: state.brandId,
					search: state.search,
					sort: state.sort,
					page: state.page
				},
				success: function(res) {
					const payload = res.data;
					const products = payload.data ?? [];

					if (!products.length) {
						grid.html(emptyHTML('Produk tidak tersedia untuk filter ini.'));
						return;
					}

					grid.empty();

					products.forEach(product => {
						grid.append(renderProductCard(product, role));
					});

					renderPagination(payload);
				},
				error: function () {
					grid.html(emptyHTML('Gagal memuat produk.'));
				}
			});
		}

		function renderProductCard(product, role) {
			const provider = product.brand?.name ?? '-';
			const isReady = Number(product.status) === 1;
			const priceValue = role === 'admin' ? product.price : product.selling_price;
			const priceText = state.mode === 'pascabayar' ? 'Cek Tagihan' : money(priceValue);
			const cleanName = cleanProductName(product.product_name, provider);
			const selected = Number(state.product?.id) === Number(product.id);

						// onclick='selectProduct(${JSON.stringify(product).replace(/'/g, '&#39;')})'>
							// <i class="fas ${selected ? 'fa-check-circle text-primary' : 'fa-circle text-light'}"></i>
			return `
				<div class="col-6 col-md-4 col-xl-3 product-item">
					<button type="button"
						class="product-card w-100 text-start ${selected ? 'selected' : ''} ${!isReady ? 'locked' : ''}"
						${!isReady ? 'disabled' : ''}
						onclick='selectProduct(this, ${JSON.stringify(product).replace(/'/g, '&#39;')})'>

						<div class="d-flex align-items-start justify-content-between gap-2 mb-3">
							<span class="product-status ${isReady ? 'ready' : 'down'}">
								${isReady ? 'Ready' : 'Gangguan'}
							</span>
							<i data-selected-icon class="fas ${selected ? 'fa-check-circle text-primary' : 'fa-circle text-light'}"></i>
						</div>

						<div class="product-name">${escapeHtml(cleanName)}</div>
						<div class="product-meta">${escapeHtml(provider)}${state.category?.name ? ' • ' + escapeHtml(state.category.name) : ''}</div>

						<div class="product-price">${priceText}</div>
					</button>
				</div>
			`;
		}

		function selectProduct(el, product) {
			state.product = product;
			state.inquiryRefId = null;

			$('#product-code').val(product.id);
			$('#inquiry_ref_id').val('');

			resetBill();
			renderSelectedProduct();
			markSelectedProductCard(el);
			applyGameProductInput(product);
			// $('#product-code').val(product.id);
			// $('#inquiry_ref_id').val('');

			// resetBill();
			// renderSelectedProduct();
			// loadProducts();
		}

		function applyGameProductInput(product) {
			if (!isGameCategory()) return;

			$('#standard-input-box').addClass('d-none');
			$('#target').prop('disabled', true);

			$('#game-input-box').removeClass('d-none');
			$('#game_user_id').prop('disabled', false);
			$('#game_server_id').prop('disabled', false);

			$('#game_server_id').closest('.col-5').removeClass('d-none');
			$('#game_user_id').closest('.col-7').removeClass('col-7').addClass('col-7');

			$('#label-target').text('ID Player & Zone ID');


			// if (gameNeedsServer(product)) {
			// 	$('#game_server_id').prop('disabled', false);
			// 	$('#game_server_id').closest('.col-5').removeClass('d-none');
			// 	$('#game_user_id').closest('.col-7').removeClass('col-12').addClass('col-7');
			// 	$('#label-target').text('ID Player & Zone ID');
			// } else {
			// 	$('#game_server_id').val('').prop('disabled', true);
			// 	$('#game_server_id').closest('.col-5').addClass('d-none');
			// 	$('#game_user_id').closest('.col-7').removeClass('col-7').addClass('col-12');
			// 	$('#label-target').text('ID Player');
			// }
		}

		function markSelectedProductCard(el) {
			$('.product-card').removeClass('selected');

			$('.product-card [data-selected-icon]')
				.removeClass('fa-check-circle text-primary')
				.addClass('fa-circle text-light');

			$(el).addClass('selected');

			$(el).find('[data-selected-icon]')
				.removeClass('fa-circle text-light')
				.addClass('fa-check-circle text-primary');
		}

		function renderSelectedProduct() {
			const role = $('#user-id').find(':selected').data('role');
			const product = state.product;
			const provider = product.brand?.name ?? '-';
			const price = state.mode === 'pascabayar'
				? 'Cek tagihan dahulu'
				: money(role === 'admin' ? product.price : product.selling_price);

			$('#summary-empty').addClass('d-none');
			$('#summary-content').removeClass('d-none');

			$('#summary-mode').text(state.mode === 'pascabayar' ? 'Pascabayar' : 'Prabayar');
			$('#summary-product-name').text(cleanProductName(product.product_name, provider));
			$('#summary-product-meta').text(`${provider} • ${state.category?.name ?? '-'}`);
			$('#summary-product-price').text(price);

			$('#box-custom-price').toggleClass('d-none', state.mode === 'pascabayar');

			if (state.mode === 'pascabayar') {
				$('#btn-check-bill').removeClass('d-none');
				$('#btn-submit').addClass('d-none');
			} else {
				$('#btn-check-bill').addClass('d-none');
				$('#btn-submit')
					.removeClass('d-none')
					.html('<i class="fas fa-paper-plane me-2"></i> Proses Transaksi');
			}
		}

		function renderSummaryEmpty() {
			$('#summary-empty').removeClass('d-none');
			$('#summary-content').addClass('d-none');
		}

		function generateProviderFilter(parentId) {
			const container = $('#container-filter');
			const categoriesWrapper = $('#categories-filter-wrapper');
			const brandsWrapper = $('#brands-filter-wrapper');
			const categoriesList = $('#categories-filter-list');
			const brandsList = $('#brands-filter-list');

			container.addClass('d-none');
			categoriesWrapper.addClass('d-none');
			brandsWrapper.addClass('d-none');
			categoriesList.empty();
			brandsList.empty();

			$.ajax({
				url: "{{ route('admin.products.categories.category-by-parent') }}",
				data: { parent_id: parentId },
				success: function(res) {
					const data = res.data ?? {};
					const categories = data.categories ?? [];
					const brands = data.brands ?? [];

					if (!categories.length && !brands.length) return;

					container.removeClass('d-none');

					if (categories.length > 1) {
						categoriesWrapper.removeClass('d-none');
						categoriesList.append(`<button type="button" class="filter-chip active" data-id="">Semua</button>`);

						categories.forEach(item => {
							categoriesList.append(`<button type="button" class="filter-chip" data-id="${item.id}">${escapeHtml(item.name)}</button>`);
						});
					}

					if (brands.length > 0) {
						brandsWrapper.removeClass('d-none');
						brandsList.append(`<button type="button" class="filter-chip active" data-id="">Semua</button>`);

						brands.forEach(item => {
							brandsList.append(`<button type="button" class="filter-chip" data-id="${item.id}">${escapeHtml(item.name)}</button>`);
						});
					}
				}
			});
		}

		$(document).on('click', '#categories-filter-list .filter-chip', function () {
			$('#categories-filter-list .filter-chip').removeClass('active');
			$(this).addClass('active');

			state.typeCategoryId = $(this).data('id') || null;
			state.page = 1;
			state.product = null;
			$('#product-code').val('');
			renderSummaryEmpty();
			resetBill();
			loadProducts();
		});

		$(document).on('click', '#brands-filter-list .filter-chip', function () {
			$('#brands-filter-list .filter-chip').removeClass('active');
			$(this).addClass('active');

			state.brandId = $(this).data('id') || null;
			state.page = 1;
			state.product = null;
			$('#product-code').val('');
			renderSummaryEmpty();
			resetBill();
			loadProducts();
		});

		function inquiryBill() {
			const productCode = $('#product-code').val();
			const target = $('#target').val();
			const userId = $('#user-id').val();

			if (!productCode) {
				return Swal.fire('Error', 'Silahkan pilih produk tagihan terlebih dahulu.', 'error');
			}

			if (!target) {
				return Swal.fire('Error', 'Nomor pelanggan wajib diisi.', 'error');
			}

			$.ajax({
				url: "{{ route('admin.transactions.inquiry') }}",
				type: "POST",
				data: {
					product_code: productCode,
					target: target,
					user_id: userId
				},
				beforeSend: function() {
					$('#btn-check-bill')
						.prop('disabled', true)
						.html('<i class="fas fa-spinner fa-spin me-2"></i> Mengecek...');

					resetBill();
				},
				success: function(res) {
					if (res.meta.code !== 200) {
						return Swal.fire('Gagal', res.meta.message ?? 'Tagihan tidak ditemukan.', 'error');
					}

					const data = res.data ?? {};

					$('#bill-name').text(data.customer_name ?? '-');
					$('#bill-period').text(normalizeBillDesc(data.desc));
					$('#bill-amount').text(money(data.amount ?? 0));
					$('#bill-admin').text(money(data.admin_fee ?? 0));
					$('#bill-total').text(money(data.total_pay ?? 0));

					$('#inquiry_ref_id').val(data.ref_id);
					$('#bill_amount_value').val(data.amount ?? 0);
					$('#bill_admin_fee_value').val(data.admin_fee ?? 0);
					$('#bill_total_pay_value').val(data.total_pay ?? 0);

					state.inquiryRefId = data.ref_id;

					$('#bill-details').removeClass('d-none');
					$('#btn-check-bill').addClass('d-none');
					$('#btn-submit')
						.removeClass('d-none')
						.html('<i class="fas fa-money-bill-wave me-2"></i> Bayar Tagihan');
				},
				error: function(xhr) {
					const msg = xhr.responseJSON?.meta?.message || xhr.responseJSON?.message || 'Gagal mengecek tagihan.';
					Swal.fire('Error', msg, 'error');
				},
				complete: function() {
					$('#btn-check-bill')
						.prop('disabled', false)
						.html('<i class="fas fa-search me-2"></i> Cek Tagihan');
				}
			});
		}

		function storeTransaction() {
			const form = $('#form-transaction');

			if (!state.category) {
				return Swal.fire('Peringatan', 'Silahkan pilih kategori terlebih dahulu.', 'warning');
			}

			if (!$('#product-code').val()) {
				return Swal.fire('Peringatan', 'Silahkan pilih produk terlebih dahulu.', 'warning');
			}

			if (state.mode === 'pascabayar' && !$('#inquiry_ref_id').val()) {
				return Swal.fire('Peringatan', 'Silahkan lakukan Cek Tagihan terlebih dahulu.', 'warning');
			}

			Swal.fire({
				title: 'Konfirmasi',
				text: state.mode === 'pascabayar'
					? 'Pastikan data tagihan sudah sesuai. Saldo akan langsung terpotong.'
					: 'Proses transaksi sekarang?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Proses',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (!result.isConfirmed) return;

				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					data: form.serialize(),
					beforeSend: function() {
						$('#btn-submit')
							.prop('disabled', true)
							.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');
					},
					success: function(response) {
						Swal.fire('Sukses', response?.meta?.message || 'Transaksi berhasil.', 'success')
							.then(() => window.location.reload());
					},
					error: function(xhr) {
						const msg = xhr.responseJSON?.meta?.message || xhr.responseJSON?.message || 'Terjadi kesalahan.';
						Swal.fire('Gagal', msg, 'error');
					},
					complete: function() {
						$('#btn-submit').prop('disabled', false);

						if (state.mode === 'pascabayar') {
							$('#btn-submit').html('<i class="fas fa-money-bill-wave me-2"></i> Bayar Tagihan');
						} else {
							$('#btn-submit').html('<i class="fas fa-paper-plane me-2"></i> Proses Transaksi');
						}
					}
				});
			});
		}

		function getUsername(self) {
			const input = $(self);
			const type = input.data('type');
			let currentVal = input.val();

			if (/[^0-9]/.test(currentVal)) {
				currentVal = currentVal.replace(/[^0-9]/g, '');
				input.val(currentVal);
			}

			clearTimeout(typingTimer);

			const category = state.categorySlug ?? '';
			const isPln = category === 'token-pln';
			const isGames = category === 'games';

			if (isPln && currentVal.length >= 11) {
				typingTimer = setTimeout(function () {
					checkUsername({ category, target: currentVal });
				}, doneTypingInterval);
			}

			if (isGames) {
				const userId = $('#game_user_id').val();
				const serverId = $('#game_server_id').val();

				if (userId.length >= 5 && serverId.length >= 3) {
					typingTimer = setTimeout(function () {
						checkUsername({
							category,
							code_game: state.product?.brand?.name?.toLowerCase().replace(/\s+/g, '-'),
							user_id: userId,
							server_id: serverId,
						});
					}, doneTypingInterval);
				}
			}
		}

		function checkUsername(data) {
			const resultDiv = $('#customer-name-result');

			$.ajax({
				url: "{{ route('api.provider.check-username') }}",
				type: "POST",
				data: data,
				beforeSend: function() {
					resultDiv
						.html('<i class="fas fa-spinner fa-spin me-1"></i> Mengecek data...')
						.removeClass('text-success text-danger')
						.addClass('text-muted')
						.slideDown();
				},
				success: function(response) {
					if (response?.meta?.code != 200) {
						return resultDiv
							.html('<i class="fas fa-times-circle me-1"></i> ' + (response?.data?.message ?? 'Data tidak ditemukan.'))
							.removeClass('text-success text-muted')
							.addClass('text-danger');
					}

					const dataResponse = response.data;
					let name = dataResponse.name ?? dataResponse;

					if (dataResponse.segment_power) {
						name += ` | ${dataResponse.segment_power}`;
					}

					resultDiv
						.html('<i class="fas fa-check-circle me-1"></i> ' + name)
						.removeClass('text-danger text-muted')
						.addClass('text-success');

					$('#customer-name-input').val(name);
				},
				error: function(xhr) {
					const msg = xhr.responseJSON?.meta?.message || 'User tidak ditemukan.';
					resultDiv
						.html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg)
						.removeClass('text-success text-muted')
						.addClass('text-danger')
						.slideDown();
				}
			});
		}

		function renderPagination(meta) {
			const container = $('#product-pagination');

			if (!meta || meta.last_page <= 1) {
				container.empty();
				return;
			}

			const current = meta.current_page;
			const last = meta.last_page;
			const delta = 2;
			let range = [];
			let rangeWithDots = [];
			let previous;

			for (let i = 1; i <= last; i++) {
				if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
					range.push(i);
				}
			}

			for (let i of range) {
				if (previous) {
					if (i - previous === 2) rangeWithDots.push(previous + 1);
					else if (i - previous !== 1) rangeWithDots.push('...');
				}
				rangeWithDots.push(i);
				previous = i;
			}

			let html = `<div class="d-flex justify-content-center gap-2 flex-wrap">`;

			if (current > 1) {
				html += `<button type="button" class="btn btn-sm btn-light" onclick="changePage(${current - 1})">«</button>`;
			}

			rangeWithDots.forEach(i => {
				if (i === '...') {
					html += `<span class="px-2 text-muted">...</span>`;
				} else {
					html += `
						<button type="button"
							class="btn btn-sm ${i === current ? 'btn-primary' : 'btn-light'}"
							onclick="changePage(${i})">
							${i}
						</button>
					`;
				}
			});

			if (current < last) {
				html += `<button type="button" class="btn btn-sm btn-light" onclick="changePage(${current + 1})">»</button>`;
			}

			html += `</div>`;

			container.html(html);
		}

		function changePage(page) {
			if (state.page === page) return;

			state.page = page;
			loadProducts();
		}

		function resetSelection() {
			state.category = null;
			state.categoryId = null;
			state.categorySlug = null;
			state.typeCategoryId = null;
			state.brandId = null;
			state.product = null;
			state.inquiryRefId = null;

			$('#hidden_category_id').val('');
			$('#category-slug').val('');
			$('#product-code').val('');
			$('#inquiry_ref_id').val('');
			$('.category-card').removeClass('active');
			$('#container-filter').addClass('d-none');
			$('#categories-filter-list, #brands-filter-list').empty();

			resetCustomerFields();
			resetBill();
			renderSummaryEmpty();
		}

		function resetCustomerFields() {
			$('#target').val('');
			$('#game_user_id').val('').prop('disabled', true);
			$('#game_server_id').val('').prop('disabled', true);
			$('#customer-name-input').val('');
			$('#customer-name-result').hide().html('');
			$('#standard-input-box').removeClass('d-none');
			$('#game-input-box').addClass('d-none');
		}

		function resetBill() {
			$('#bill-details').addClass('d-none');
			$('#bill-name, #bill-period, #bill-amount, #bill-admin, #bill-total').text('-');
			$('#inquiry_ref_id').val('');
			$('#bill_amount_value, #bill_admin_fee_value, #bill_total_pay_value').val('');
		}

		function renderInitialProductState() {
			$('#product-grid-container').html(`
				<div class="col-12">
					<div class="empty-state">
						<i class="fas fa-hand-pointer"></i>
						<h6>Pilih kategori dulu</h6>
						<p>Produk akan ditampilkan berdasarkan kategori dan mode transaksi.</p>
					</div>
				</div>
			`);

			$('#product-pagination').empty();
		}

		function loadingHTML() {
			return `
				<div class="col-12">
					<div class="empty-state">
						<i class="fas fa-spinner fa-spin"></i>
						<h6>Memuat produk...</h6>
						<p>Tunggu sebentar ya.</p>
					</div>
				</div>
			`;
		}

		function emptyHTML(message) {
			return `
				<div class="col-12">
					<div class="empty-state">
						<i class="fas fa-box-open"></i>
						<h6>Produk kosong</h6>
						<p>${escapeHtml(message)}</p>
					</div>
				</div>
			`;
		}

		function formatUser(user) {
			if (!user.id) return user.text;

			const balance = $(user.element).data('balance') ?? '-';
			const isSelf = $(user.element).data('self');

			return `
				<div class="d-flex justify-content-between align-items-center">
					<span class="${isSelf ? 'fw-bold text-info' : ''}">
						${isSelf ? '★ ' : ''}${user.text}
					</span>
					<span class="badge bg-success bg-opacity-10 text-success rounded-pill">${balance}</span>
				</div>
			`;
		}

		function cleanProductName(name, provider) {
			let result = String(name ?? '').trim();
			let brand = String(provider ?? '').trim();

			if (!brand || brand === '-') return result;

			const pattern = new RegExp('^' + brand.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*[-:]?\\s*', 'i');
			result = result.replace(pattern, '');

			result = result.replace(/^mobilelegend\s*[-:]?\s*/i, '');
			result = result.replace(/^mobile legends\s*[-:]?\s*/i, '');

			return result.trim() || name;
		}

		function normalizeBillDesc(desc) {
			if (!desc) return '-';

			if (typeof desc === 'string') return desc;

			if (Array.isArray(desc)) {
				return desc.map(item => {
					if (typeof item === 'object') return Object.values(item).join(' ');
					return item;
				}).join(' | ');
			}

			if (typeof desc === 'object') {
				return Object.entries(desc)
					.map(([key, value]) => `${key}: ${typeof value === 'object' ? JSON.stringify(value) : value}`)
					.join(' | ');
			}

			return String(desc);
		}

		function money(value) {
			const number = Number(value || 0);

			if (number <= 0) return 'Rp -';

			if (typeof formatRupiah === 'function') {
				return formatRupiah(number);
			}

			return new Intl.NumberFormat('id-ID', {
				style: 'currency',
				currency: 'IDR',
				maximumFractionDigits: 0
			}).format(number);
		}

		function escapeHtml(value) {
			return String(value ?? '')
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		function isGameCategory(category = state.category) {
			const name = String(category?.name ?? '').toLowerCase();
			const slug = String(category?.slug ?? '').toLowerCase();
			const parent = String(category?.parent ?? '').toLowerCase();

			return slug === 'games' ||
			slug === 'game' ||
			slug.includes('game') ||
			name.includes('game') ||
			parent.includes('game');
		}

		function gameNeedsServer(product = state.product) {
			const brand = String(product?.brand?.name ?? '').toLowerCase();
			const productName = String(product?.product_name ?? '').toLowerCase();
			const sku = String(product?.buyer_sku_code ?? '').toLowerCase();

			return brand.includes('mobile legends') ||
			brand.includes('mobilelegend') ||
			brand === 'ml' ||
			productName.includes('mobile legends') ||
			productName.includes('mobilelegend') ||
			sku.includes('ml');
		}
	</script>
@endpush
