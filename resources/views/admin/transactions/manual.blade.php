@extends('layouts.admin')

@section('title', 'Input Transaksi Manual')

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-10">

			{{-- Header --}}
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h4 class="mb-1 fw-bold text-dark">Transaksi Manual</h4>
					<p class="text-muted small mb-0">Input transaksi pelanggan secara manual.</p>
				</div>
			</div>

			{{-- NEW: Main Tabs (Pemisah Utama Berdasarkan Parent Category) --}}
			<div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 12px;">
				<div class="card-body p-0">
					<div class="row g-0 text-center">
						{{-- Ambil slug parent dari DB, biasanya 'prabayar' dan 'pascabayar' --}}
						{{-- Pastikan di Controller Anda mengirim variable $parentCategories --}}
						<div class="col-6">
							<button class="btn btn-lg w-100 h-100 rounded-0 fw-bold py-3 active-mode-tab"
									id="tab-prabayar" onclick="switchMode('prabayar')">
								<i class="fas fa-bolt me-2"></i> PRABAYAR
							</button>
						</div>
						<div class="col-6">
							<button class="btn btn-lg w-100 h-100 rounded-0 fw-bold py-3 text-muted"
									id="tab-pascabayar" onclick="switchMode('pascabayar')">
								<i class="fas fa-file-invoice-dollar me-2"></i> PASCABAYAR
							</button>
						</div>
					</div>
				</div>
			</div>

			{{-- Category Grid (Akan difilter via JS) --}}
			<div class="row g-3 mb-4" id="category-grid">
				@foreach($categories as $cat)
					{{-- Hanya tampilkan Child Category (yang punya parent_id) --}}
					@if($cat->parent_id)
						{{-- Ambil slug parent-nya untuk filtering JS --}}
						@php
							$parentSlug = $cat->parent->slug ?? 'unknown'; // Pastikan relasi 'parent' ada di model Category
							// Mapping icon manual atau dari DB jika ada kolom icon
							$icon = $cat->icon ?? 'fa-cube';
							$color = 'primary'; // Default color

							// Custom Icon Mapping (Opsional, jika di DB kosong)
							if(str_contains($cat->slug, 'pln')) { $icon = 'fa-bolt'; $color = 'warning'; }
							elseif(str_contains($cat->slug, 'game')) { $icon = 'fa-gamepad'; $color = 'danger'; }
							elseif(str_contains($cat->slug, 'pulsa')) { $icon = 'fa-mobile-alt'; $color = 'success'; }
							elseif(str_contains($cat->slug, 'data')) { $icon = 'fa-wifi'; $color = 'info'; }
							elseif(str_contains($cat->slug, 'wallet')) { $icon = 'fa-wallet'; $color = 'primary'; }
							elseif(str_contains($cat->slug, 'tagihan')) { $icon = 'fa-receipt'; $color = 'secondary'; }
						@endphp

						<div class="col-6 col-md-3 col-lg category-item"
							 data-slug="{{ $cat->slug }}"
							 data-parent="{{ $parentSlug }}">  {{-- Kunci Filter Baru --}}

							<div class="card border-0 shadow-sm h-100 category-card cursor-pointer btn-anim"
								 onclick="selectCategory(this, '{{ $cat->id }}', '{{ $cat->name }}')">
								<div class="card-body text-center p-3">
									<div class="icon-wrapper mb-2 text-{{ $color }}">
										<i class="fas {{ $icon }} fa-2x"></i>
									</div>
									<span class="small fw-bold text-uppercase d-block">{{ $cat->name }}</span>
								</div>
							</div>
						</div>
					@endif
				@endforeach
			</div>

			{{-- Transaction Form Card --}}
			<div class="card border-0 shadow-sm d-none" id="transaction-card" style="border-radius: 16px;">
				<div class="card-header bg-white border-bottom py-3">
					<div class="d-flex justify-content-between align-items-center">
						<h6 class="mb-0 fw-bold text-primary">
							<i class="fas fa-keyboard me-2"></i> INPUT <span id="selected-category-title">TRANSAKSI</span>
						</h6>
						<span class="badge bg-primary rounded-pill text-uppercase" id="badge-mode">Prabayar</span>
					</div>
				</div>

				<div class="card-body p-4">
					<form id="form-transaction" action="{{ route('admin.transactions.store') }}" method="POST">
						{{-- Hidden Input --}}
						<input type="hidden" name="category_id" id="hidden_category_id">
						<input type="hidden" name="transaction_type" id="transaction_type" value="prabayar">

						<div class="row g-4">
							{{-- User Select --}}
							<div class="col-md-6">
								<label class="form-label text-muted small fw-bold text-uppercase">Pelanggan</label>
								<select class="form-select" id="user-id" name="user_id" required>
									<option value="" disabled>-- Cari Pengguna --</option>
									<option value="{{ auth()->id() }}" class="fw-bold bg-light" data-balance="{{ auth()->user()->balance_formatted }}" data-self="1">
										SAYA SENDIRI
									</option>
									@foreach($users as $user)
										@if($user->id != auth()->id())
											<option value="{{ $user->id }}" data-balance="{{ $user->balance_formatted }}">{{ $user->username }}</option>
										@endif
									@endforeach
								</select>
							</div>

							{{-- Input Nomor Tujuan --}}
							<div class="col-md-6">
								<label id="label-target" class="form-label text-muted small fw-bold text-uppercase">Nomor Tujuan</label>

								{{-- A. INPUT STANDARD --}}
								<div class="input-group custom-input-group rounded-3 overflow-hidden" id="standard-input-box">
									<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
										<i id="icon-target" class="fas fa-phone-alt text-muted"></i>
									</span>
									<input type="text" class="form-control border-0 bg-transparent shadow-none text-body"
									id="target" name="target" placeholder="Masukan Nomor..." autocomplete="off" oninput="getUsername(this)">
									<span class="input-group-text border-0 bg-transparent text-primary" id="loading-icon" style="display: none;">
										<i class="fas fa-circle-notch fa-spin"></i>
									</span>
								</div>

								{{-- B. INPUT KHUSUS GAMES --}}
								<div class="row g-2 d-none" id="game-input-box">
									<div class="col-7">
										<div class="input-group rounded-3 overflow-hidden border">
											<span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-user"></i></span>
											<input type="text" class="form-control border-0 shadow-none bg-white cursor-disabled"
											id="game_user_id" name="game_user_id" placeholder="User ID" data-type="user-id" oninput="getUsername(this)" disabled>
										</div>
									</div>
									<div class="col-5">
										<div class="input-group rounded-3 overflow-hidden border">
											<span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-server"></i></span>
											<input type="text" class="form-control border-0 shadow-none bg-white cursor-disabled"
											id="game_server_id" name="game_server_id" placeholder="Zone ID" data-type="server-id" oninput="getUsername(this)" disabled>
										</div>
									</div>
								</div>
								<div id="customer-name-result" class="form-text fw-bold mt-2 ps-1" style="display: none;"></div>
							</div>

							{{-- Dropdown Khusus Game --}}
							<div class="col-md-12 d-none" id="game-brand-box">
								<label class="form-label text-muted small fw-bold text-uppercase">Pilih Game</label>
								<select class="form-select" id="game-brand-select" data-placeholder="-- PILIH GAME --"></select>
							</div>

							{{-- Product Select --}}
							<div class="col-md-12">
								<label class="form-label text-muted small fw-bold text-uppercase" id="label-product">Pilih Produk</label>
								<select class="form-select" id="product-code" name="product_code" required disabled data-placeholder="-- SILAHKAN PILIH PRODUK --">
								</select>
							</div>

							{{-- Custom Price (Hanya untuk Prabayar) --}}
							<div class="col-md-6" id="box-custom-price">
								<label class="form-label text-muted small fw-bold text-uppercase">Harga Jual (Override)</label>
								<div class="input-group">
									<span class="input-group-text bg-light">Rp</span>
									<input type="number" class="form-control" name="custom_price" placeholder="Default">
								</div>
							</div>
						</div>

						{{-- Detail Tagihan (Pascabayar) --}}
						<div id="bill-details" class="alert alert-info border-0 shadow-sm mt-4 d-none">
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

						<div class="mt-4 text-end">
							{{-- Tombol Action --}}
							<button type="button" onclick="inquiryBill()" id="btn-check-bill" class="btn btn-info text-white w-100 py-2 fw-bold d-none">
								<i class="fas fa-search me-2"></i> CEK TAGIHAN
							</button>

							<button type="button" onclick="storeTransaction()" id="btn-submit" class="btn btn-primary w-100 py-2 fw-bold">
								<i class="fas fa-paper-plane me-2"></i> PROSES TRANSAKSI
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('styles')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<style>
		.cursor-pointer { cursor: pointer; }

		/* Style untuk Card Kategori saat Active */
		.category-card { transition: all 0.2s ease; border: 2px solid transparent !important;}
		.category-card:hover { transform: translateY(-5px); }

		.category-card.active {
			border-color: var(--primary-color) !important;
			background-color: rgba(102, 126, 234, 0.05);
		}
		.category-card.active .icon-wrapper { transform: scale(1.1); }

		/* Tab Switch Style */
		.active-mode-tab {
			background-color: var(--primary-color) !important;
			color: white !important;
			box-shadow: inset 0 -4px 0 rgba(0,0,0,0.1);
		}
		.btn:focus { box-shadow: none; }

		#category-grid { transition: all 0.3s ease; }
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		// --- VARIABLES GLOBAL ---
		let currentMode = 'prabayar'; // Default mode, nanti disesuaikan dengan slug parent
		let currentCategory = '';
		let currentCategoryId = '';
		let typingTimer;
		const doneTypingInterval = 1000;

		// Elemen Cache
		const $billDetail  = $('#bill-details');
		const $targetInput = $('#target');
		const $loadingIcon = $('#loading-icon');
		const $resultDiv   = $('#customer-name-result');
		const $categoryId  = $('#category-id');

		// Config UI per kategori (Label, Icon, Placeholder)
		// Kunci object ini HARUS sesuai dengan 'slug' category di database Anda
		const config = {
			'operator-seluler': { label: 'Nomor Handphone', icon: 'fa-mobile-alt', placeholder: '0812xxxx' },
			'token-listrik':    { label: 'Nomor Meter / ID Pel', icon: 'fa-bolt',  placeholder: 'Masukan No Meter / ID Pel' },
			'games':            { label: 'ID Player',       icon: 'fa-gamepad',    placeholder: '123456 (1234)' },
			'e-wallet':         { label: 'Nomor Handphone', icon: 'fa-wallet',     placeholder: '0812xxxx' },
			'voucher':          { label: 'Nomor Handphone', icon: 'fa-ticket-alt', placeholder: '0812xxxx' },
			'streaming':        { label: 'Nomor HP / Akun', icon: 'fa-play-circle',placeholder: '0812xxxx' },

			// Pascabayar Slugs (Sesuaikan DB)
			'tagihan-ppob':     { label: 'ID Pelanggan',    icon: 'fa-receipt',    placeholder: 'Masukan ID Pelanggan' },
		};

		let $formatter
		let $swal
		$(document).ready(async () => {
			module = await initModul()
			$formatter = module.formatter
			$swal = module.swal

			// Init Select2
			$('#user-id').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#transaction-card'), templateResult: formatUser, templateSelection: formatUser, escapeMarkup: m => m });
			$('#product-code').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#transaction-card'), templateResult: formatProductOption, templateSelection: formatProductOption, escapeMarkup: m => m });
			$('#game-brand-select').select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $('#transaction-card'), templateResult: formatProductOption, templateSelection: formatProductOption, escapeMarkup: m => m });

			// Init Mode Awal (Prabayar)
			// Cek tombol tab mana yang aktif secara default di HTML
			let defaultMode = $('.active-mode-tab').attr('id').replace('tab-', '');
			switchMode(defaultMode);

			// Event Listeners
			$('#game-brand-select').on('change', function() {
				var brandName = $(this).val();
				toggleGameInputs(!!brandName);
				if(brandName) loadProducts(brandName, true);
			})

			// Reset mode jika user ganti (biar harga update)
			$('#user-id').on('change', _ => {
				if (currentCategory.includes('games')) {
					var brandName = $('#game-brand-select').val();
					toggleGameInputs(!!brandName);
					if(brandName) loadProducts(brandName, true);
				} else {
					if(currentCategoryId) loadProducts(currentCategoryId, false);
				}

			});

			// Input Logic
			setupInputLogic();
		});

		// --- LOGIC GANTI MODE UTAMA (PARENT CATEGORY) ---
		function switchMode(mode) {
			currentMode = mode;
			$('#transaction_type').val(mode); // prabayar / pascabayar
			$('#badge-mode').text(mode.toUpperCase());
			$('#transaction-card').addClass('d-none');

			// 1. Update UI Tab Button
			if (mode === 'prabayar') {
				$('#tab-prabayar').addClass('active-mode-tab text-white').removeClass('text-muted bg-white');
				$('#tab-pascabayar').removeClass('active-mode-tab text-white').addClass('text-muted bg-white');
			} else {
				$('#tab-pascabayar').addClass('active-mode-tab text-white').removeClass('text-muted bg-white');
				$('#tab-prabayar').removeClass('active-mode-tab text-white').addClass('text-muted bg-white');
			}

			// 2. Filter Kategori Grid (LOGIC BARU: BASED ON DATA-PARENT)
			// Kita filter elemen .category-item berdasarkan atribut data-parent yang sesuai dengan slug mode
			let visibleCount = 0;
			$('.category-item').each(function() {
				let parentSlug = $(this).data('parent'); // Ambil slug parent dari HTML

				// Flexible matching: misal slug di DB "prabayar-P5yuk", kita cocokkan prefix "prabayar"
				if (parentSlug.includes(mode)) {
					$(this).removeClass('d-none');
					visibleCount++;
				} else {
					$(this).addClass('d-none');
				}
			});

			// 3. Reset Form UI Element
			resetFormUI();
		}

		function resetFormUI() {
			$('#target').val('');
			$('#product-code').empty().trigger('change');
			$('#customer-name-result').hide();
			$('#bill-details').addClass('d-none');
			$('.category-card').removeClass('active');

			// Tentukan tampilan berdasarkan mode parent
			// Asumsi slug parent mengandung kata 'pascabayar'
			if (currentMode.includes('pascabayar')) {
				$('#box-custom-price').hide();
				$('#btn-submit').addClass('d-none');
				$('#btn-check-bill').removeClass('d-none');
				$('#label-product').text('Pilih Layanan / Provider');

				// Hide Game Inputs
				$('#game-brand-box').addClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#standard-input-box').removeClass('d-none');
			} else {
				// Prabayar
				$('#box-custom-price').show();
				$('#btn-submit').removeClass('d-none').text('PROSES TRANSAKSI');
				$('#btn-check-bill').addClass('d-none');
				$('#label-product').text('Pilih Produk');

				// Reset Game Box
				$('#game-brand-box').addClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#standard-input-box').removeClass('d-none');
			}
		}

		function selectCategory(el, catId, catName) {
			// currentCategory = catId;
			currentCategoryId = catId
			currentCategory = catName.toLowerCase();
			$('#hidden_category_id').val(catId); // Kirim slug atau ID, sesuaikan controller

			// Highlight Card
			$('.category-card').removeClass('active');
			$(el).addClass('active');

			$('#transaction-card').removeClass('d-none');
			$('#selected-category-title').text(catName.toUpperCase());

			// --- CONFIG LABEL ---
			// Cari config berdasarkan slug (flexible check)
			let conf = null;
			for (const [key, value] of Object.entries(config)) {
				if (catId.includes(key)) {
					conf = value;
					break;
				}
			}
			// Fallback default
			if (!conf) conf = { label: 'Nomor Tujuan', icon: 'fa-phone', placeholder: 'Masukan Nomor Tujuan' };

			$('#label-target').text(conf.label);
			$('#target').attr('placeholder', conf.placeholder);
			$('#icon-target').attr('class', 'fas ' + conf.icon + ' text-muted');

			// --- SPECIAL CASE: GAMES ---
			if (catId == 6 && !currentMode.includes('pascabayar')) {
				toggleInputType('game');
				loadGameBrands(catId);
			} else {
				toggleInputType('standard');
				loadProducts(catId, false);
			}
		}

		function toggleInputType(type) {
			if (type === 'game') {
				$('#standard-input-box').addClass('d-none');
				$('#game-input-box').removeClass('d-none');
				$('#game-brand-box').removeClass('d-none');
			} else {
				$('#standard-input-box').removeClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#game-brand-box').addClass('d-none');
			}
		}

		// --- HELPER FUNCTIONS LAINNYA (Sama seperti sebelumnya) ---
		function formatUser(user) {
			if (!user.id) return user.text;

			const balance = $(user.element).data('balance') ?? '-';
			const isSelf  = $(user.element).data('self');

			return `
				<div class="d-flex justify-content-between align-items-center">
					<span class="${isSelf ? 'fw-bold text-info' : ''}">
						${isSelf ? '★ ' : ''}${user.text}
					</span>
					<span class="badge bg-success bg-opacity-10 text-success rounded-pill">
						${balance}
					</span>
				</div>
			`;
			// if (!user.id) return user.text;
			// const balance = $(user.element).data('balance') ?? '-';
			// const isSelf  = $(user.element).data('self');
			// return `<div class="d-flex justify-content-between align-items-center"><span class="${isSelf ? 'fw-bold text-info' : ''}">${isSelf ? '★ ' : ''}${user.text}</span><span class="badge bg-success bg-opacity-10 text-success rounded-pill">${balance}</span></div>`;
		}
		function formatProductOption(state) {
			if (!state.id) return state.text; // Return text default untuk placeholder

			if (state.id === '#') return $('<span class="text-success fw-bold"><i class="fas fa-circle-check me-1"></i> ' + state.text + '</span>');
			if (state.id === '##') return $('<span class="text-primary fw-bold"><i class="fas fa-exclamation-circle me-1"></i> ' + state.text + '</span>');
			if (state.id === '###') return $('<span class="text-danger fw-bold"><i class="fas fa-circle-xmark me-1"></i> ' + state.text + '</span>');

			// Ambil data harga dari attribut data-price (nanti kita set saat AJAX)
			var price = $(state.element).data('price');
			var formattedPrice = price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(price) : '';

			// ['#000000', '#00b8dd', '#00b30f']
			// Return HTML custom (Nama Kiri - Harga Kanan)
			var $state = $(
				'<div class="d-flex justify-content-between align-items-center w-100">' +
					'<span class="fw-bold">' + state.text + '</span>' +
					'<span class="badge bg-success bg-opacity-10 text-success rounded-pill">' + formattedPrice + '</span>' +
					// '<span style="color: #00b8dd !important; border-color: #00b8dd !important;">' + formattedPrice + '</span>' +
				'</div>'
			);
			return $state;
			// if (!state.id) return state.text;
			// var price = $(state.element).data('price');
			// var formattedPrice = price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(price) : '';
			// return $('<div class="d-flex justify-content-between align-items-center w-100"><span class="fw-bold">' + state.text + '</span><span class="badge bg-success bg-opacity-10 text-success rounded-pill">' + formattedPrice + '</span></div>');
		}

		// Logic Input & Username Check (Disingkat, logika sama)
		function setupInputLogic() {
			let typingTimer;

			// A. Listener untuk Input Standard (Pulsa/PLN)
			// $('#target').on('input', function() {
			// 	if(currentCategory === 'games') return; // Abaikan jika sedang mode game
			// 	let val = $(this).val();
			// 	let isPln = currentCategory.includes('token') || currentCategory.includes('listrik'); // Flexible check slug

			// 	clearTimeout(typingTimer);
			// 	$('#customer-name-result').hide();

			// 	// Auto Check PLN
			// 	if (isPln) {
			// 		this.value = val.replace(/[^0-9]/g, '');
			// 		if (this.value.length >= 11) {
			// 			typingTimer = setTimeout(() => checkUsername({ target: this.value }), 1000);
			// 		}
			// 	}
			// });
		}

		function checkUsername(data) {
			// check-username
			$.ajax({
				url: "{{ route('api.provider.check-username') }}",
				type: "POST",
				data: data,
				beforeSend: function() {
					$loadingIcon.fadeIn()
					$targetInput.addClass('text-muted')
				},
				success: function(response) {

					if (response?.data?.rc === '00' || response?.meta?.code == 200) {
						const data = response.data

						let info = `<i class="fas fa-check-circle me-1"></i> ${data.name ?? data}`;

						if (data.segment_power) info += ` <span class="badge bg-info text-dark ms-1">${data.segment_power}</span>`;

						$resultDiv.html(info)
								.removeClass('text-danger').addClass('text-success')
								.slideDown()
					} else {
						$resultDiv.html('<i class="fas fa-times-circle me-1"></i> ' + response?.data?.message)
								.removeClass('text-success').addClass('text-danger')
								.slideDown()
					}
				},
				error: function(xhr) {
					let msg = xhr.responseJSON && xhr.responseJSON.meta.message ? xhr.responseJSON.meta.message : 'ID Pelanggan tidak ditemukan.';
					$resultDiv.html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg)
							.removeClass('text-success').addClass('text-danger')
							.slideDown()
				},
				complete: function() {
					$loadingIcon.fadeOut()
					$targetInput.removeClass('text-muted')
				}
			})
		}

		function loadProducts(identifier, isBrandMode = false) {
			var productSelect = $('#product-code');
			productSelect.empty().append('<option selected disabled>Loading...</option>').prop('disabled', true);

			var payload = { mode: currentMode };
			if (isBrandMode) payload.brand = identifier;
			else payload.category = identifier;

			const uid = $('#user-id').val();

			$.ajax({
				url: "{{ route('admin.products.items.getProductsByCategory') }}",
				type: "POST",
				data: payload,
				dataType: "json",
				success: function(data) {
					productSelect.empty();
					if (data.data.length > 0) {
						productSelect.append('<option value="#" selected disabled>-- PILIH --</option>');
						$.each(data.data, function(key, value) {
							let displayName = value.product_name;
							// Harga prabayar dinamis based on user type (opsional)
							let priceData = (!currentMode.includes('pascabayar')) ? (uid == 1 ? value.price : value.selling_price) : 0;
							productSelect.append(`<option value="${value.id}" data-price="${priceData}">${displayName}</option>`);
						});
						productSelect.prop('disabled', false);
					} else {
						productSelect.append('<option disabled selected value="###">-- PRODUK BELUM TERSEDIA --</option>');
					}
				}
			});
		}
		// Load Game Brand, Inquiry Bill, Store Transaction (Sama seperti sebelumnya)
		async function loadGameBrands(category) {
			var brandSelect = $('#game-brand-select');
			brandSelect.empty().append('<option value="##" selected disabled>LOADING GAME...</option>');
			brandSelect.prop('disabled', true);

			$('#product-code').empty().append('<option value="##" selected disabled>-- SILAHKAN PILIH GAME DULU --</option>')

			const {status, data: {data}, data: {meta}} = await postRequest("{{ route('admin.products.items.get-brands-by-category') }}", { category: category });

			if (status !== 200) return brandSelect.empty().append('<option value="###" selected disabled>-- INTERNAL SERVER ERROR --</option>');
			if (data.length == 0) return brandSelect.empty().append('<option value="###" selected disabled>-- GAME BELUM TERSEDIA --</option>');

			brandSelect.empty().append('<option value="#" selected disabled>-- PILIH GAME --</option>');

			$.each(data, function(key, value) {
				// Value.brand adalah nama game (Mobile Legends, Free Fire)
				// toLowerCase().replace(/\s+/g, '-')
				brandSelect.append(`<option value="${value.brand.id}" data-type="${value.brand.name.toLowerCase().replace(/\s+/g, '-')}">${value.brand.name}</option>`);
			});

			brandSelect.prop('disabled', false);
			brandSelect.trigger('change');


			// $.ajax({
			// 	url: "{{ route('admin.products.items.get-brands-by-category') }}", // Sesuaikan route Anda
			// 	type: "POST",
			// 	data: { category: category },
			// 	dataType: "json",
			// 	success: function(data) {

			// 		brandSelect.empty().append('<option value="#" selected disabled>-- PILIH GAME --</option>');

			// 		$.each(data?.data, function(key, value) {
			// 			// Value.brand adalah nama game (Mobile Legends, Free Fire)
			// 			// toLowerCase().replace(/\s+/g, '-')
			// 			brandSelect.append(`<option value="${value.brand}" data-type="${value.brand.toLowerCase().replace(/\s+/g, '-')}">${value.brand}</option>`);
			// 		});

			// 		brandSelect.prop('disabled', false);
			// 		brandSelect.trigger('change');
			// 	}
			// });
		}

		function inquiryBill() {
			let code = $('#product-code').val();
			let target = $('#target').val();
			let userId = $('#user-id').val();

			// if(!code || !target || !userId) return Swal.fire('Error', 'Lengkapi data pelanggan, produk, dan nomor tujuan', 'error');

			$.ajax({
				url: "{{ route('admin.transactions.inquiry') }}", // Route Baru untuk Inquiry
				type: "POST",
				data: {
					product_code: code,
					target: target,
					user_id: userId
				},
				beforeSend: function() {
					$('#btn-check-bill').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cek Tagihan...');
					$billDetail.addClass('d-none');
				},
				success: function(res) {
					if(res.meta.code === 200) {
						// Tampilkan Hasil Inquiry
						let data = res.data;
						$('#bill-name').text(data.customer_name);
						$('#bill-period').text(data.desc || '-'); // Periode/Lembar
						$('#bill-amount').text(formatRupiah(data.amount));
						$('#bill-admin').text(formatRupiah(data.admin_fee));
						$('#bill-total').text(formatRupiah(data.total_pay));

						$('#inquiry_ref_id').val(data.ref_id); // Penting untuk step bayar

						$billDetail.removeClass('d-none');
						$('#btn-check-bill').addClass('d-none'); // Sembunyikan tombol cek
						$('#btn-submit').removeClass('d-none').text('Bayar Tagihan Sekarang'); // Munculkan tombol bayar
					} else {
						Swal.fire('Gagal', res.message, 'error');
					}
				},
				error: function(xhr) {
					let msg = xhr.responseJSON?.message || 'Gagal mengecek tagihan';
					Swal.fire('Error', msg, 'error');
				},
				complete: function() {
					$('#btn-check-bill').prop('disabled', false).html('<i class="fas fa-search me-2"></i> Cek Tagihan');
				}
			});
		}

		function storeTransaction() {
			var form = $('#form-transaction');

			// Validasi tambahan untuk Pascabayar
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
						data: form.serialize(),
						beforeSend: function() {
							$('#btn-submit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
						},
						success: function(response) {
							Swal.fire('Sukses', response?.meta?.message || 'Transaksi Berhasil', 'success')
							.then(() => window.location.reload());
						},
						error: function(xhr) {
							$('#btn-submit').prop('disabled', false).html('Proses Transaksi');
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
				$('#product-code').attr('disabled', false);
			} else {
				$('#game_user_id, #game_server_id').attr('disabled', true).addClass('cursor-disabled');
				$('#product-code').attr('disabled', true);
			}
		}

		function getUsername(self) {
			const $this = $(self)
			const type = $this.data('type')

			let currentVal = $this.val()
			if (/[^0-9]/.test(currentVal)) return self.value = currentVal.replace(/[^0-9]/g, '');;

			// Bersihkan state sebelumnya
			clearTimeout(typingTimer)
			$resultDiv.slideUp()

			// let isPln = currentCategory.includes('pln')

			let isPln = currentCategory.includes('token') || currentCategory.includes('listrik'); // Flexible check slug

			if (isPln && currentVal.length >= 11) {
				typingTimer = setTimeout(function () {
					checkUsername({
						category: currentCategory,
						target: currentVal,
					});
				}, doneTypingInterval);
			}

			let isGames = currentCategory.includes('games')

			const isUser = type == 'user-id'
			const isServer = type == 'server-id'
			if (isGames && ( (isUser && currentVal >= 5 && $('#game_server_id').val().length >= 3) || (isServer && currentVal >= 3 && $('#game_user_id').val().length >= 5) )) {
				typingTimer = setTimeout(function () {
					checkUsername({
						category: currentCategory,
						code_game: $('#game-brand-select').find(':selected').data('type'),
						user_id: $('#game_user_id').val(),
						server_id: $('#game_server_id').val(),
					});
				}, doneTypingInterval);
			}
		}
	</script>
@endpush
