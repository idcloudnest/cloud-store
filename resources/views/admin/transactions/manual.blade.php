@extends('layouts.admin')

@section('title', 'Transaksi Manual')

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-12">

			{{-- Header --}}
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h4 class="mb-1 fw-bold text-dark">Form Transaksi</h4>
					<p class="text-muted small mb-0">Input transaksi pelanggan secara manual.</p>
				</div>
			</div>

			<form id="form-transaction" action="{{ route('admin.transactions.store') }}" method="POST">
				{{-- Hidden Inputs --}}
				<input type="hidden" name="category_id" id="hidden_category_id">
				<input type="hidden" name="transaction_type" id="transaction_type" value="prabayar">
				{{-- Hidden input pengganti select2 product --}}
				<input type="hidden" name="product_code" id="product-code" required>

				<div class="row g-4">
					{{-- SISI KIRI: INPUT PELANGGAN & PRODUK --}}
					<div class="col-lg-8">
						<div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
							<div class="card-body p-4">
								{{-- Pilihan Produk (Menggunakan Grid seperti gambar) --}}
								<div class="pt-2">
									<div class="d-flex justify-content-between align-items-end mb-3">
										<label class="form-label text-muted small fw-bold text-uppercase mb-0" id="label-product">Pilih Layanan</label>
										{{-- Search Box --}}
										<div class="input-group input-group-sm" style="max-width: 220px;">
											<span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
											<input type="text" id="searchProduct" class="form-control shadow-none border-start-0" placeholder="Cari Produk...">
										</div>
									</div>

									{{-- WRAPPER SCROLL BARU (Scroll diletakkan di sini) --}}


									{{-- ==================================================================================================== --}}
									<div id="container-filter" class="mb-3 d-none">
										<div class="d-flex flex-wrap gap-2 p-2 border-bottom" id="categories-filter-list"></div>
										<div class="d-flex flex-wrap gap-2 p-2" id="brands-filter-list"></div>
									</div>

									<div class="product-scroll-wrapper pe-2">
										{{-- Container List Grid Produk (Bungkus di dalamnya) --}}
										<div id="product-grid-container" class="row g-3 p-2">
											{{-- State Kosong Default --}}
											<div class="col-12 text-center text-muted py-5 border rounded-3 bg-light" id="empty-product-state">
												<i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
												<p class="mb-0 small">Silahkan pilih Kategori / Pelanggan di sebelah kanan terlebih dahulu.</p>
											</div>
										</div>
									</div>
									<div id="product-pagination" class="mt-3"></div>
									{{-- ==================================================================================================== --}}
								</div>

								{{-- Rincian Tagihan (Pascabayar) --}}
								<div id="bill-details" class="alert alert-info border-0 shadow-sm mt-4 d-none" style="border-radius: 8px;">
									<div class="d-flex align-items-center mb-2">
										<i class="fas fa-receipt fa-2x me-3 opacity-50"></i>
										<div>
											<h6 class="fw-bold mb-0">Rincian Tagihan</h6>
											<small class="text-muted">Pastikan data sesuai sebelum membayar</small>
										</div>
									</div>
									<hr class="my-2 opacity-25">
									<div class="row g-2 small">
										<div class="col-6 text-muted">Nama Pelanggan</div>
										<div class="col-6 fw-bold text-end" id="bill-name">-</div>
										<div class="col-6 text-muted">Periode / Lembar</div>
										<div class="col-6 fw-bold text-end" id="bill-period">-</div>
										<div class="col-6 text-muted">Jumlah Tagihan</div>
										<div class="col-6 fw-bold text-end" id="bill-amount">-</div>
										<div class="col-6 text-muted">Biaya Admin</div>
										<div class="col-6 fw-bold text-end" id="bill-admin">-</div>
										<div class="col-12"><hr class="my-1 border-dashed"></div>
										<div class="col-6 fw-bold text-primary fs-6">TOTAL BAYAR</div>
										<div class="col-6 fw-bold text-primary text-end fs-6" id="bill-total">-</div>
									</div>
									<input type="hidden" name="inquiry_ref_id" id="inquiry_ref_id">
								</div>
							</div>
						</div>
					</div>

					{{-- SISI KANAN: PILIH KATEGORI --}}
					<div class="col-lg-4">
						<div class="card border-0 shadow-sm" style="border-radius: 12px;">
							<div class="card-body p-4">
								<h6 class="card-title fw-bold text-dark mb-4">Pilih Kategori</h6>

								{{-- Tabs (Pascabayar/Prabayar) --}}
								{{-- <div class="card border-0 shadow-sm mb-3 overflow-hidden bg-light" style="border-radius: 8px;">
									<div class="card-body p-0">
										<div class="row g-0 text-center">
											<div class="col-6">
												<button type="button" class="btn btn-sm w-100 rounded-0 fw-bold py-2 active-mode-tab"
														id="tab-prabayar" onclick="switchMode('prabayar')">
													PRABAYAR
												</button>
											</div>
											<div class="col-6">
												<button type="button" class="btn btn-sm w-100 rounded-0 fw-bold py-2 text-muted"
														id="tab-pascabayar" onclick="switchMode('pascabayar')">
													PASCABAYAR
												</button>
											</div>
										</div>
									</div>
								</div> --}}

								{{-- Category List / Grid --}}
								<div class="row g-2 pb-4 border-bottom" id="category-grid">
									@foreach($categories as $cat)
										{{-- @if($cat->parent_id) --}}
											@php
												$parentSlug = $cat->parent->slug ?? 'unknown';
												$icon = $cat->icon ?? 'fa-cube';
												$color = 'primary';
												$arrColor = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'muted'];

												if(str_contains($cat->slug, 'pln')) { $icon = 'fa-bolt'; $color = $arrColor[array_rand($arrColor)]; }
												elseif(str_contains($cat->slug, 'game')) { $icon = 'fa-gamepad'; $color = $arrColor[array_rand($arrColor)]; }
												elseif(str_contains($cat->slug, 'operator-seluler')) { $icon = 'fa-mobile-alt'; $color = $arrColor[array_rand($arrColor)]; }
												elseif(str_contains($cat->slug, 'data')) { $icon = 'fa-wifi'; $color = $arrColor[array_rand($arrColor)]; }
												elseif(str_contains($cat->slug, 'wallet')) { $icon = 'fa-wallet'; $color = $arrColor[array_rand($arrColor)]; }
												elseif(str_contains($cat->slug, 'voucher')) { $icon = 'fa-ticket-alt'; $color = $arrColor[array_rand($arrColor)]; }
												elseif(str_contains($cat->slug, 'tagihan')) { $icon = 'fa-receipt'; $color = $arrColor[array_rand($arrColor)]; }
											@endphp

											<div class="col-6 category-item"
												 data-slug="{{ $cat->slug }}"
												 data-parent="{{ $parentSlug }}">

												<div class="card border-0 shadow-sm h-100 category-card cursor-pointer btn-anim bg-light"
													data-slug="{{ $cat->slug }}"
													{{-- onclick="selectCategory(this, '{{ $cat->id }}', '{{ $cat->name }}')" --}}
													onclick="selectCategory(this, {{ json_encode([
														'id' => $cat->id,
														'name' => strtolower($cat->name),
														'slug' => $cat->slug,
														'parent' => optional($cat->parent)->slug ?? null
													]) }})"
													{{-- onclick='selectCategory(this, @json([
														"id" => $cat->id,
														"name" => $cat->name,
														"slug" => $cat->slug,
														"parent" => optional($cat->parent)->slug
													]))' --}}
													style="border-radius: 8px;">
													<div class="card-body text-center p-3">
														<div class="icon-wrapper mb-2 text-{{ $color }}">
															<i class="fas {{ $icon }} fa-lg"></i>
														</div>
														<span class="small fw-bold text-uppercase d-block" style="font-size: 0.75rem;">{{ $cat->name }}</span>
													</div>
												</div>
											</div>
										{{-- @endif --}}
									@endforeach
								</div>

								<h6 class="card-title fw-bold text-dark mb-4 pt-4">Detail Pelanggan & Produk</h6>

								<div class="row g-4 mb-4">
									{{-- User Select --}}
									<div class="col-md-12">
										<label class="form-label text-muted small fw-bold text-uppercase">Pelanggan</label>
										<select class="form-select" id="user-id" name="user_id" required>
											{{-- <option value="#" data-role="anonim" data-balance="Rp. 0">ANONIM</option> --}}
											<option value="{{ auth()->id() }}" data-role="{{ auth()->user()->role }}" class="fw-bold bg-light" data-balance="{{ auth()->user()->balance_formatted }}" data-self="1">
												SAYA SENDIRI
											</option>
											@foreach($users as $user)
												@if($user->id != auth()->id())
													<option value="{{ $user->id }}" data-role="{{ $user->role }}" data-balance="{{ $user->balance_formatted }}">{{ $user->username }}</option>
												@endif
											@endforeach
										</select>
									</div>

									{{-- Input Nomor Tujuan / ID --}}
									<div class="col-md-12">
										<label id="label-target" class="form-label text-muted small fw-bold text-uppercase">Nomor / ID Tujuan</label>

										{{-- A. INPUT STANDARD --}}
										<div class="input-group custom-input-group rounded-3 overflow-hidden border" id="standard-input-box">
											<span class="input-group-text border-0 bg-light text-muted border-end">
												<i id="icon-target" class="fas fa-phone-alt text-muted"></i>
											</span>
											<input type="text" class="form-control border-0 bg-transparent shadow-none text-body"
											id="target" name="target" placeholder="Masukan Nomor..." autocomplete="off" oninput="getUsername(this)">
											{{-- <span class="input-group-text border-0 bg-transparent text-primary" id="loading-icon" style="display: none;">
												<i class="fas fa-circle-notch fa-spin"></i>
											</span> --}}
										</div>

										{{-- B. INPUT KHUSUS GAMES --}}
										<div class="row g-2 d-none" id="game-input-box">
											<div class="col-7">
												<div class="input-group rounded-3 overflow-hidden border">
													<span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-user"></i></span>
													<input type="text" class="form-control border-0 shadow-none bg-white cursor-disabled"
													id="game_user_id" name="game_user_id" placeholder="User ID" data-type="user-id" oninput="getUsername(this)" disabled value="790075827">
												</div>
											</div>
											<div class="col-5">
												<div class="input-group rounded-3 overflow-hidden border">
													<span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-server"></i></span>
													<input type="text" class="form-control border-0 shadow-none bg-white cursor-disabled"
													id="game_server_id" name="game_server_id" placeholder="Zone ID" data-type="server-id" oninput="getUsername(this)" disabled value="12157">
												</div>
											</div>
										</div>
										<div id="customer-name-result" class="form-text fw-bold mt-2 ps-1" style="display: none;"></div>
										<input id="customer-name-input" name="customer_name" type="hidden">
									</div>

									{{-- Custom Price (Hanya untuk Prabayar) --}}
									<div class="col-md-12" id="box-custom-price">
										<label class="form-label text-muted small fw-bold text-uppercase">Harga Jual (Override)</label>
										<div class="input-group border rounded-3 overflow-hidden">
											<span class="input-group-text bg-light border-0">Rp</span>
											<input type="number" class="form-control border-0 shadow-none" name="custom_price" placeholder="Default">
										</div>
									</div>
								</div>

								{{-- Special Case: Game Brands --}}
								{{-- <div class="col-md-12 d-none mt-3" id="game-brand-box">
									<label class="form-label text-muted small fw-bold text-uppercase">Pilih Game</label>
									<select class="form-select" id="game-brand-select" data-placeholder="-- PILIH GAME --"></select>
								</div> --}}

								{{-- Action Buttons --}}
								<div class="mt-4">
									<button type="button" onclick="inquiryBill()" id="btn-check-bill" class="btn btn-info text-white w-100 py-2 fw-bold d-none" style="border-radius: 8px;">
										<i class="fas fa-search me-2"></i> CEK TAGIHAN
									</button>

									<button type="button" onclick="storeTransaction()" id="btn-submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 8px;">
										<i class="fas fa-paper-plane me-2"></i> PROSES TRANSAKSI
									</button>
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
		.cursor-pointer { cursor: pointer; }

		/* Style Card Kategori saat Active */
		.category-card { transition: all 0.2s ease; border: 2px solid transparent !important;}
		.category-card:hover { transform: translateY(-3px); }
		.category-card.active {
			border-color: var(--primary-color) !important;
			background-color: rgba(102, 126, 234, 0.05) !important;
		}

		/* Tab Switch Style */
		.active-mode-tab {
			background-color: var(--primary-color) !important;
			color: white !important;
			box-shadow: inset 0 -3px 0 rgba(0,0,0,0.1);
		}

		/* ==================================================================================================== */
		.categories-chip {
			border: 1px solid #e2e8f0;
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 0.75rem;
			cursor: pointer;
			background: #f8fafc;
			transition: all 0.2s ease;
		}
		.categories-chip:hover {
			background: #e2e8f0;
		}
		.categories-chip.active {
			background: #10b981;
			color: white;
			border-color: #10b981;
		}
		.brands-chip {
			border: 1px solid #e2e8f0;
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 0.75rem;
			cursor: pointer;
			background: #f8fafc;
			transition: all 0.2s ease;
		}
		.brands-chip:hover {
			background: #e2e8f0;
		}
		.brands-chip.active {
			background: #10b981;
			color: white;
			border-color: #10b981;
		}


		#product-pagination .btn {
			min-width: 36px;
			border-radius: 8px;
		}
		/* --- STYLE BARU UNTUK KARTU PRODUK (GRID) --- */
		/* Kunci Tinggi Wrapper Agar Tidak Molor */
		#product-grid-container {
			min-height: 120px;
		}
		.product-scroll-wrapper {
			position: relative;
		}


		.product-card {
			height: 100%;
			display: flex;
			flex-direction: column;
		}

		.product-name {
			min-height: 38px; /* biar sejajar */
		}
		/* #product-grid-container {
			display: flex;
			flex-wrap: wrap;
		} */
		.product-scroll-wrapper {
			max-height: 650px !important;
			overflow-y: auto !important;
			overflow-x: hidden;
			display: block !important;
		}

		/* Style Scrollbar */
		.product-scroll-wrapper::-webkit-scrollbar { width: 6px; }
		.product-scroll-wrapper::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
		.product-scroll-wrapper::-webkit-scrollbar-track { background-color: transparent; }
		/* .product-scroll-wrapper::-webkit-scrollbar { width: 6px; }
		.product-scroll-wrapper::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
		.product-scroll-wrapper::-webkit-scrollbar-track { background-color: transparent; } */
		/* ==================================================================================================== */


		.product-card {
			border: 1px solid #e2e8f0;
			border-radius: 10px;
			transition: all 0.2s ease-in-out;
			cursor: pointer;
			user-select: none;
		}
		.product-card:hover {
			border-color: #10b981;
			transform: translateY(-2px);
			box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
		}
		/* Style saat Radio Box terpilih */
		.btn-check:checked + .product-card {
			border-color: #10b981 !important;
			background-color: rgba(16, 185, 129, 0.05) !important;
			box-shadow: 0 0 0 1.5px #10b981;
		}
		.btn-check:checked + .product-card .btn-beli {
			background-color: #059669;
			color: #fff;
		}
		.btn-check:checked + .product-card .price-text {
			color: #10b981 !important;
		}

		/* Box Harga */
		.price-box {
			background-color: #f8fafc;
			border: 1px solid #f1f5f9;
		}
		.text-success { color: #10b981 !important; }

		/* Tombol Beli Hijau */
		.btn-beli {
			background-color: #10b981;
			color: #fff;
			transition: all 0.2s ease;
		}
		.btn-beli:hover { background-color: #059669; color: #fff; }

		/* Disable State */
		.locked-item {
			opacity: 0.8;
			filter: grayscale(50%);
			cursor: not-allowed !important;
			/* background-color: #f8f9fa !important; */
		}
		.locked-item:hover { transform: none; box-shadow: none; border-color: #e2e8f0; }
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script>
		let currentMode = 'prabayar';
		let currentCategory = null;
		let currentSlug = null;
		let currentCategories = null;
		let currentBrands = null;
		let currentCategoryId = null;
		let typingTimer;
		const doneTypingInterval = 1000;
		let currentPage = 1;

		const $billDetail  = $('#bill-details');
		const $targetInput = $('#target');
		// const $loadingIcon = $('#loading-icon');
		const $resultDiv   = $('#customer-name-result');

		const config = {
			'operator-seluler': { label: 'Nomor Handphone', icon: 'fa-mobile-alt', placeholder: '0812xxxx' },
			'token-pln':    { label: 'Nomor Meter / ID Pel', icon: 'fa-bolt',  placeholder: 'Masukan No Meter / ID Pel' },
			'games':            { label: 'ID Player',       icon: 'fa-gamepad',    placeholder: '123456 (1234)' },
			'e-wallet':         { label: 'Nomor Handphone', icon: 'fa-wallet',     placeholder: '0812xxxx' },
			'voucher':          { label: 'Nomor', icon: 'fa-ticket-alt', placeholder: '0812xxxx' },
			'streaming':        { label: 'Nomor HP / Akun', icon: 'fa-play-circle',placeholder: '0812xxxx' },
			'tagihan-ppob':     { label: 'ID Pelanggan',    icon: 'fa-receipt',    placeholder: 'Masukan ID Pelanggan' },
		};

		let $formatter, $swal;

		$(document).ready(async () => {
			module = await initModul();
			$formatter = module.formatter;
			$swal = module.swal;

			// $('#user-id').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#transaction-card'), templateResult: formatUser, templateSelection: formatUser, escapeMarkup: m => m });
			$('#user-id').select2({ theme: 'bootstrap-5', width: '100%', templateResult: formatUser, templateSelection: formatUser, escapeMarkup: m => m });
			$('#game-brand-select').select2({ theme: 'bootstrap-5', width: '100%' });

			// $('#game-brand-select').on('change', function() {
			// 	var brandName = $(this).val();
			// 	toggleGameInputs(!!brandName);
			// 	// if(brandName) loadProducts(brandName, true);
			// 	if(brandName) loadProducts({ identifier: brandName, page: currentPage })
			// });

			$('#user-id').on('change', function () {
				// if (currentCategory.includes('games')) {
				// 	var brandName = $('#game-brand-select').val();
				// 	toggleGameInputs(!!brandName);
				// 	if(brandName) loadProducts(brandName, true);
				// } else {
				// 	if(currentCategoryId) loadProducts(currentCategoryId, false);
				// }
				// if (currentCategory) loadProducts(currentCategory.id, false);
				if (currentCategory) loadProducts({ identifier: currentCategoryId, page: currentPage, user_id: $(this).val() });
			});

			// LOGIC PENCARIAN PRODUK GRID
			$('#searchProduct').on('input', function() {
				let value = $(this).val().toLowerCase();
				$('.product-item').filter(function() {
					let name = $(this).data('name');
					$(this).toggle(name.includes(value));
				});
			});
		});

		function resetFormUI() {
			// $('#target').val('');
			// $('#customer-name-result').hide();
			// $('#bill-details').addClass('d-none');
			// $('.category-card').removeClass('active');

			// Reset state grid product
			$('#product-code').val('');
			$('#product-grid-container').html(`
				<div class="col-12 text-center text-muted py-5 border rounded-3 bg-light" id="empty-product-state">
					<i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
					<p class="mb-0 small">Silahkan pilih Kategori / Pelanggan di sebelah kanan terlebih dahulu.</p>
				</div>
			`);

			if (currentMode.includes('pascabayar')) {
				$('#box-custom-price').hide();
				$('#btn-submit').addClass('d-none');
				$('#btn-check-bill').removeClass('d-none');
				$('#game-brand-box').addClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#standard-input-box').removeClass('d-none');
			} else {
				$('#box-custom-price').show();
				$('#btn-submit').removeClass('d-none').text('PROSES TRANSAKSI');
				$('#btn-check-bill').addClass('d-none');
				$('#game-brand-box').addClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#standard-input-box').removeClass('d-none');
			}
		}

		function selectCategory(el, category) {
			currentCategoryId = category.id;
			currentCategory = category;

			if($(el).data('slug') == currentSlug) return;

			$('#hidden_category_id').val(category.id);

			$('.category-card').removeClass('active');
			$(el).addClass('active');

			// 🔥 pakai slug, bukan id
			let conf = config[category.slug] || {
				label: 'Nomor Tujuan',
				icon: 'fa-phone',
				placeholder: 'Masukan Nomor'
			};

			$('#label-target').text(conf.label);
			$('#target').attr('placeholder', conf.placeholder);
			$('#icon-target').attr('class', 'fas ' + conf.icon);

			currentCategories = null
			currentBrands = null

			toggleInputType(category.slug)

			loadProducts({ identifier: category.id, page: currentPage })
			generateProviderFilter(category.id)
			// resetFormUI()
			// $('#game_user_id, #game_server_id').val('');
			currentSlug = category?.slug
			$resultDiv.slideUp().html('')
			$('#customer-name-input').val('')
		}

		function toggleInputType(type) {
			const icon = $('#icon-target');

			if (type === 'games') {
				$('#standard-input-box').addClass('d-none');
				$('#game-input-box').removeClass('d-none');
				$('#game-brand-box').removeClass('d-none');
			} else {
				$('#standard-input-box').removeClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#game-brand-box').addClass('d-none');

				// 🔥 Default icon
				icon.removeClass()

				// ⚡ Kalau PLN
				if (type === 'token-pln') {
					icon.addClass('fas fa-bolt text-muted')
				} else if (type === 'voucher') {
					icon.addClass('fas fa-ticket-alt text-muted')
				} else if (type === 'e-wallet') {
					icon.addClass('fas fa-wallet text-muted')
				} else {
					icon.addClass('fas fa-phone-alt text-muted')
				}
			}
		}

		function formatUser(user) {
			if (!user.id) return user.text;
			const balance = $(user.element).data('balance') ?? '-';
			const isSelf  = $(user.element).data('self');
			return `<div class="d-flex justify-content-between align-items-center"><span class="${isSelf ? 'fw-bold text-info' : ''}">${isSelf ? '★ ' : ''}${user.text}</span><span class="badge bg-success bg-opacity-10 text-success rounded-pill">${balance}</span></div>`;
		}

		$(document).on('click', '.product-radio', function () {
			if ($(this).hasClass('was-checked')) {
				$(this).prop('checked', false);
				$(this).removeClass('was-checked');
			} else {
				$('.product-radio').removeClass('was-checked'); // reset semua
				$(this).addClass('was-checked');
			}
		});

		// ================================================================================================
		// FUNGSI LOAD PRODUCTS (DIUBAH MENJADI RENDER GRID)
		// function loadProducts(identifier, isBrandMode = false, page = 1) {
		function loadProducts(json) {
			$('#product-pagination').empty()

			const grid = $('#product-grid-container');
			grid.html(loadingHTML());

			page = json.page ?? 1;
			const role = $('#user-id').find(':selected').data('role')

			let payload = {
				mode: currentMode,
				category_id: json.identifier,
				categories: currentCategories,
				brands: currentBrands
			};

			$.ajax({
				url: "{{ route('admin.products.items.getProductsByCategory') }}",
				type: "POST",
				data: { ...payload, page },
				success: function(res) {
					const products = res.data.data;


					if (!products.length) return grid.html(emptyHTML('Produk tidak tersedia'));

					grid.empty();

					products.forEach(p => {
						// let price = currentMode === 'prabayar'
						// 	? formatRupiah(p.selling_price)
						// 	: 'Cek Tagihan';
						let price = formatRupiah(role == 'admin' ? p.price : p.selling_price)

						let provider = p.brand?.name ?? '-';

						let categories = (p.categories || [])
							.map(c => c.name)
							.join(', ');

						let disabled = (p.status == 0) ? 'locked-item' : '';

						grid.append(`
							<div class="col-6 col-md-4 col-lg-3 product-item"
								data-name="${p.product_name.toLowerCase()}"
								data-categories="${provider.toLowerCase()}">

								<input type="radio" class="btn-check product-radio"
									name="product_sku_radio"
									id="prod_${p.id}"
									value="${p.id}"
									data-type="${p.brand.name.toLowerCase().replace(/\s+/g, '-')}"
									${disabled ? 'disabled' : ''}>

								<label class="product-card p-3 h-100 ${disabled}" for="prod_${p.id}">

									<div class="d-flex justify-content-between align-items-start mb-2">
										<small class="text-muted">${provider}</small>
										<small class="${p.status ? 'text-success' : 'text-danger'}">
											${p.status ? 'Ready' : 'Gangguan'}
										</small>
									</div>

									<div class="fw-bold mb-3 product-name" style="font-size: 0.85rem; color: #334155; line-height: 1.3;">${p.product_name}</div>

									<div class="mt-auto">
										<div class="price-box p-2 mb-2 price-box">
											<span class="fw-bold text-success price-text" style="font-size: 0.85rem;">${price}</span>
										</div>
									</div>

								</label>
							</div>
						`);
					});

					// pilih produk
					// $('.product-radio').change(function () {
					// 	$('#product-code').val($(this).val());
					// });

					// generateProviderFilter(products)
					renderPagination(res.data);

					setTimeout(() => {
						document.querySelector('#product-grid-container')
							.scrollIntoView({ behavior: 'smooth', block: 'start' });
					}, 100)
				}
			});
		}

		$(document).on('change', '.product-item .product-radio', function () {
			$('#product-code').val($(this).val());
			toggleGameInputs(!!(currentCategory?.slug == 'games'))
			// if (currentCategory?.slug == 'games') {
			// }
		});

		function generateProviderFilter(parentId) {
			let $categories = $('#categories-filter-list');
			let $brands = $('#brands-filter-list');
			let container = $('#container-filter');

			$categories.empty();
			$brands.empty()

			console.log(parentId);

			$.ajax({
				url: "{{ route('admin.products.categories.category-by-parent') }}",
				data: { parent_id: parentId},
				success: function(r) {
					console.log(r);

					if (!r) return container.addClass('d-none');

					const { data: { categories, brands } } = r

					container.removeClass('d-none');

					const categoriesIsOne = categories.length == 1

					if (!categoriesIsOne) $categories.append(`<div class="categories-chip active" data-categories="all">SEMUA</div>`);

					categories.forEach(c => {
						$categories.append(`<div class="categories-chip ${categoriesIsOne ? 'active' : ''}" data-categories="${c.name.toLowerCase()}" data-categories-id="${c.id}">${c.name}</div>`);
					});

					if (brands.length > 0) {
						if (brands.length > 1) $brands.append(`<div class="brands-chip active" data-brands="all">SEMUA</div>`);

						brands.forEach(b => {
							$brands.append(`<div class="brands-chip ${brands.length == 1 ? 'active' : ''}" data-brands="${b.name.toLowerCase()}" data-brands-id="${b.id}">${b.name}</div>`);
						});
					}

					$('.categories-chip').click(function () {
						let val = $(this).data('categories');
						let id = $(this).data('categories-id')
						currentCategories = id ?? null

						$('.categories-chip').removeClass('active');
						$(this).addClass('active');

						currentPage = 1
						loadProducts({ identifier: parentId, page: currentPage })

						// $('.product-item').each(function () {
						// 	let p = $(this).data('categories');

						// 	$(this).toggle(val === 'all' || p === val);
						// });
					});
					$('.brands-chip').click(function () {
						let val = $(this).data('brands');
						let id = $(this).data('brands-id')
						currentBrands = id ?? null

						$('.brands-chip').removeClass('active');
						$(this).addClass('active');

						currentPage = 1
						loadProducts({ identifier: parentId, page: currentPage })

						// $('.product-item').each(function () {
						// 	let p = $(this).data('brands');

						// 	$(this).toggle(val === 'all' || p === val);
						// });
					});
				}
			})


			// let providers = [...new Set(products.map(p => p.brand?.name).filter(Boolean))];

			// if (!providers.length) return container.addClass('d-none');

			// container.removeClass('d-none');

			// list.append(`<div class="provider-chip active" data-provider="all">SEMUA</div>`);

			// providers.forEach(p => {
			// 	list.append(`<div class="provider-chip" data-provider="${p.toLowerCase()}">${p}</div>`);
			// });

			// $('.provider-chip').click(function () {
			// 	let val = $(this).data('provider');

			// 	$('.provider-chip').removeClass('active');
			// 	$(this).addClass('active');

			// 	$('.product-item').each(function () {
			// 		let p = $(this).data('provider');

			// 		$(this).toggle(val === 'all' || p === val);
			// 	});
			// });
		}

		function renderPagination(meta) {
			let container = $('#product-pagination')

			if (!meta || meta.last_page <= 1) return;

			let current = meta.current_page;
			let last = meta.last_page;
			let delta = 2; // jumlah halaman di sekitar current

			let range = [];
			let rangeWithDots = [];
			let l;

			for (let i = 1; i <= last; i++) {
				if (
					i === 1 ||
					i === last ||
					(i >= current - delta && i <= current + delta)
				) {
					range.push(i);
				}
			}

			for (let i of range) {
				if (l) {
					if (i - l === 2) {
						rangeWithDots.push(l + 1);
					} else if (i - l !== 1) {
						rangeWithDots.push('...');
					}
				}
				rangeWithDots.push(i);
				l = i;
			}

			let html = `<div class="d-flex justify-content-center gap-2 flex-wrap">`;

			// Prev
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

			// Next
			if (current < last) {
				html += `<button type="button" class="btn btn-sm btn-light" onclick="changePage(${current + 1})">»</button>`;
			}

			html += `</div>`;

			container.html(html);
		}

		function changePage(page) {
			if (currentPage == page) return;
			currentPage = page;

			// if (currentCategory.includes('games')) {
			// 	let brand = $('#game-brand-select').val();
			// 	loadProducts(brand, true, page);
			// } else {
				// loadProducts(currentCategoryId, false, page);
				loadProducts({ identifier: currentCategoryId, page: page })
			// }
		}
		// ================================================================================================

		async function loadGameBrands(category) {
			var brandSelect = $('#game-brand-select');
			brandSelect.empty().append('<option value="##" selected disabled>LOADING GAME...</option>');
			brandSelect.prop('disabled', true);

			const {status, data: {data}, data: {meta}} = await postRequest("{{ route('admin.products.items.get-brands-by-category') }}", { category: category });

			if (status !== 200) return brandSelect.empty().append('<option value="###" selected disabled>-- INTERNAL SERVER ERROR --</option>');
			if (data.length == 0) return brandSelect.empty().append('<option value="###" selected disabled>-- GAME BELUM TERSEDIA --</option>');

			brandSelect.empty().append('<option value="#" selected disabled>-- PILIH GAME --</option>');
			$.each(data, function(key, value) {
				brandSelect.append(`<option value="${value.brand.id}" data-type="${value.brand.name.toLowerCase().replace(/\s+/g, '-')}">${value.brand.name}</option>`);
			});
			brandSelect.prop('disabled', false);
			brandSelect.trigger('change');
		}

		function inquiryBill() {
			let code = $('#product-code').val(); // Mengambil id dari hidden input grid
			let target = $('#target').val();
			let userId = $('#user-id').val();

			if(!code) return Swal.fire('Error', 'Silahkan pilih layanan/produk terlebih dahulu.', 'error');

			$.ajax({
				url: "{{ route('admin.transactions.inquiry') }}",
				type: "POST",
				data: { product_code: code, target: target, user_id: userId },
				beforeSend: function() {
					$('#btn-check-bill').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cek Tagihan...');
					$billDetail.addClass('d-none');
				},
				success: function(res) {
					if(res.meta.code === 200) {
						let data = res.data;
						$('#bill-name').text(data.customer_name);
						$('#bill-period').text(data.desc || '-');
						$('#bill-amount').text(formatRupiah(data.amount));
						$('#bill-admin').text(formatRupiah(data.admin_fee));
						$('#bill-total').text(formatRupiah(data.total_pay));
						$('#inquiry_ref_id').val(data.ref_id);

						$billDetail.removeClass('d-none');
						$('#btn-check-bill').addClass('d-none');
						$('#btn-submit').removeClass('d-none').text('Bayar Tagihan Sekarang');
					} else {
						Swal.fire('Gagal', res.message, 'error');
					}
				},
				error: function(xhr) {
					let msg = xhr.responseJSON?.message || 'Gagal mengecek tagihan';
					Swal.fire('Error', msg, 'error');
				},
				complete: function() {
					$('#btn-check-bill').prop('disabled', false).html('<i class="fas fa-search me-2"></i> CEK TAGIHAN');
				}
			});
		}

		function storeTransaction() {
			var form = $('#form-transaction');
			let code = $('#product-code').val();

			if(!code) return Swal.fire('Peringatan', 'Silahkan pilih layanan/produk terlebih dahulu.', 'warning');

			if (currentMode === 'pascabayar' && $('#inquiry_ref_id').val() === '') {
				return Swal.fire('Peringatan', 'Silahkan lakukan Cek Tagihan terlebih dahulu!', 'warning');
			}

			Swal.fire({
				title: 'Konfirmasi',
				text: (currentMode === 'prabayar') ? "Proses transaksi sekarang?" : "Pastikan data tagihan sudah sesuai. Saldo akan terpotong.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Proses',
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: form.attr('action'),
						type: "POST",
						data: form.serialize() + '&category=' + encodeURIComponent(currentCategory.slug),
						beforeSend: function() {
							// $('#btn-submit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
						},
						success: function(response) {
							Swal.fire('Sukses', response?.meta?.message || 'Transaksi Berhasil', 'success')
							.then(() => window.location.reload());
						},
						error: function(xhr) {
							$('#btn-submit').prop('disabled', false).html('PROSES TRANSAKSI');
							let msg = xhr.responseJSON?.meta?.message || 'Terjadi Kesalahan';
							Swal.fire('Gagal', msg, 'error');
						}
					});
				}
			});
		}

		function toggleGameInputs(show) {
			if(show) {
				$('#game_user_id, #game_server_id').attr('disabled', false).removeClass('cursor-disabled');
			} else {
				$('#game_user_id, #game_server_id').attr('disabled', true).addClass('cursor-disabled');
			}
		}

		function getUsername(self) {
			const $this = $(self);
			const type = $this.data('type');
			let currentVal = $this.val();

			if (/[^0-9]/.test(currentVal)) return self.value = currentVal.replace(/[^0-9]/g, '');

			clearTimeout(typingTimer);
			// $resultDiv.slideUp();


			const category = currentCategory?.slug ?? ''


			const isPln = category == 'token-pln'
			// let isPln = currentCategory.includes('token') || currentCategory.includes('listrik');
			if (isPln && currentVal.length >= 11) {

				typingTimer = setTimeout(function () {
					checkUsername({ category, target: currentVal });
				}, doneTypingInterval);
			}

			let isGames = category == 'games'
			const isUser = type == 'user-id';
			const isServer = type == 'server-id';


			if (isGames && ( (isUser && currentVal >= 5 && $('#game_server_id').val().length >= 3) || (isServer && currentVal >= 3 && $('#game_user_id').val().length >= 5) )) {
				typingTimer = setTimeout(function () {
					checkUsername({
						category,
						// code_game: $('#game-brand-select').find(':selected').data('type'),
						code_game: $('.product-radio:checked').data('type'),
						user_id: $('#game_user_id').val(),
						server_id: $('#game_server_id').val(),
					});
				}, doneTypingInterval);
			}
		}

		function checkUsername(data) {
			$.ajax({
				url: "{{ route('api.provider.check-username') }}",
				type: "POST",
				data: data,
				beforeSend: function() {
					// $loadingIcon.fadeIn();
					$targetInput.addClass('text-muted');

					$resultDiv
					.html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...')
					.removeClass('text-success text-danger')
					.addClass('text-muted')
					.slideDown();
				},
				success: function(response) {
					setTimeout(() => {
						if (response?.meta?.code != 200) {
							return $resultDiv
								.html('<i class="fas fa-times-circle me-1"></i> ' + response?.data?.message)
								.removeClass('text-success text-muted')
								.addClass('text-danger');
						}

						if (response?.data?.rc === '00' || (data.category == 'games')) {
							const data = response.data;
							let string = data.name ?? data
							let info = `<i class="fas fa-check-circle me-1"></i> ${string}`;
							if (data.segment_power) {
								string += ` | ${data.segment_power}`
								info += ` <span class="badge bg-info text-dark ms-1">${string}</span>`;
							}

							$resultDiv
								.html(info)
								.removeClass('text-danger text-muted')
								.addClass('text-success');

							$('#customer-name-input').val(string)
						} else {
							$resultDiv
								.html('<i class="fas fa-times-circle me-1"></i> ' + response?.data?.message)
								.removeClass('text-success text-muted')
								.addClass('text-danger');
						}
					}, 300); // ⏱️ delay kecil biar smooth

					// if (response?.meta?.code != 200) {
					// 	return $resultDiv.html('<i class="fas fa-times-circle me-1"></i> ' + response?.data?.message).removeClass('text-success').addClass('text-danger').slideDown();
					// }

					// if (response?.data?.rc === '00' || (data.category == 'games')) {
					// 	const data = response.data;
					// 	let info = `<i class="fas fa-check-circle me-1"></i> ${data.name ?? data}`;
					// 	if (data.segment_power) info += ` <span class="badge bg-info text-dark ms-1">${data.segment_power}</span>`;
					// 	$resultDiv.html(info).removeClass('text-danger').addClass('text-success').slideDown();
					// } else {
					// 	$resultDiv.html('<i class="fas fa-times-circle me-1"></i> ' + response?.data?.message).removeClass('text-success').addClass('text-danger').slideDown();
					// }
				},
				error: function(xhr) {
					let msg = xhr.responseJSON && xhr.responseJSON.meta.message ? xhr.responseJSON.meta.message : 'User tidak ditemukan.';
					$resultDiv.html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg).removeClass('text-success').addClass('text-danger').slideDown();
				},
				complete: function() {
					// $loadingIcon.fadeOut();
					$targetInput.removeClass('text-muted');
				}
			});
		}

		function loadingHTML() {
			return `
				<div class="col-12 text-center py-5 text-muted">
					<i class="fas fa-circle-notch fa-spin fa-2x mb-2"></i>
					<p class="small">Memuat produk...</p>
				</div>
			`;
		}

		function emptyHTML(msg) {
			return `
				<div class="col-12 text-center py-5 text-muted border rounded bg-light">
					<i class="fas fa-box-open fa-2x mb-2"></i>
					<p>${msg}</p>
				</div>
			`;
		}
	</script>
@endpush
