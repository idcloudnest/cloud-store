@extends('layouts.front')

@section('title', 'Beli ' . $categoryTitle)

@section('content')
<div class="container" style="max-width: 800px;">

	{{-- HEADER: Tombol Kembali & Judul --}}
	<div class="d-flex align-items-center mb-4">
		<a href="{{ route('member.dashboard') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
			<i class="fas fa-arrow-left"></i>
		</a>
		<div>
			<h5 class="fw-bold mb-0">Beli {{ $categoryTitle }}</h5>
			<small class="text-muted">Silakan masukkan nomor tujuan</small>
		</div>
	</div>

	<div class="row g-4">

		{{-- SECTION 1: INPUT NOMOR / ID --}}
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-4">
					<label class="form-label fw-bold text-muted small text-uppercase">Nomor HP / ID Pelanggan</label>

					<div class="input-group input-group-lg bg-light rounded-3 overflow-hidden border">
						<span class="input-group-text border-0 bg-transparent ps-3">
							<i class="fas fa-phone-alt text-muted"></i>
						</span>
						<input type="tel" id="targetInput" class="form-control border-0 bg-transparent fw-bold fs-5 shadow-none" placeholder="08xxxxxxxxxx" autocomplete="off">

						{{-- Logo Operator (Otomatis muncul via JS) --}}
						<span class="input-group-text border-0 bg-transparent pe-3" id="providerLogo">
							<i class="fas fa-sim-card text-muted opacity-25"></i>
						</span>
					</div>
					<div class="form-text mt-2" id="providerName">Menunggu input nomor...</div>
				</div>
			</div>
		</div>

		{{-- SECTION 2: PILIH PRODUK --}}
		<div class="col-12">
			<h6 class="fw-bold mb-3"><i class="fas fa-tags me-2 text-primary"></i> Pilih Nominal</h6>

			<div class="row g-3" id="productList">
				{{-- Loop Produk --}}
				@foreach($products as $product)
				<div class="col-6 col-md-4 product-item" data-provider="{{ $product['type'] }}">
					<div class="card h-100 border-0 shadow-sm product-card cursor-pointer"
						 onclick="selectProduct(this, '{{ $product['code'] }}', '{{ $product['name'] }}', {{ $product['price'] }})">
						<div class="card-body p-3 text-center position-relative">

							{{-- Checkmark saat dipilih --}}
							<div class="selected-check position-absolute top-0 end-0 m-2 text-primary d-none">
								<i class="fas fa-check-circle"></i>
							</div>

							<small class="text-muted d-block mb-1">{{ $product['type'] }}</small>
							<h6 class="fw-bold text-primary mb-2">{{ str_replace($product['type'], '', $product['name']) }}</h6>

							<hr class="border-secondary opacity-10 my-2">

							<div class="fw-bold text-dark price-tag">
								Rp {{ number_format($product['price'], 0, ',', '.') }}
							</div>
						</div>
					</div>
				</div>
				@endforeach

				{{-- State Kosong (Jika tidak ada produk cocok) --}}
				<div id="emptyState" class="col-12 text-center py-5 d-none">
					<i class="fas fa-search fa-3x text-muted opacity-25 mb-3"></i>
					<p class="text-muted">Produk tidak ditemukan untuk provider ini.</p>
				</div>
			</div>
		</div>

	</div>

	{{-- JARAK EXTRA AGAR TIDAK KETUTUP BOTTOM SHEET --}}
	<div style="height: 100px;"></div>

</div>

{{-- BOTTOM SHEET / MODAL KONFIRMASI (Sticky di Bawah) --}}
<div class="fixed-bottom p-3 transition-all" id="confirmationSheet" style="transform: translateY(100%); z-index: 1050;">
	<div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: var(--card-bg); border-top: 1px solid var(--primary-color);">
		<div class="card-body p-3">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<div>
					<small class="text-muted d-block">Total Pembayaran</small>
					<h4 class="fw-bold text-primary mb-0" id="totalDisplay">Rp 0</h4>
				</div>
				<div class="text-end">
					<small class="text-muted d-block mb-0">Produk</small>
					<span class="fw-bold text-body" id="productNameDisplay">-</span>
				</div>
			</div>

			<form action="{{ route('admin.transactions.store') }}" method="POST">
				@csrf
				<input type="hidden" name="product_code" id="inputProductCode">
				<input type="hidden" name="target" id="inputTarget">
				{{-- PIN (Opsional, bisa pakai modal lagi) --}}

				<button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm btn-anim" data-bs-toggle="modal" data-bs-target="#pinModal">
					Lanjut Pembayaran <i class="fas fa-arrow-right ms-2"></i>
				</button>
			</form>
		</div>
	</div>
</div>

{{-- MODAL PIN (Password Confirm) --}}
<div class="modal fade" id="pinModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content border-0 shadow-lg rounded-4">
			<div class="modal-body p-4 text-center">
				<h5 class="fw-bold mb-1">Konfirmasi PIN</h5>
				<small class="text-muted mb-4 d-block">Masukkan 6 digit PIN transaksi Anda</small>

				<form action="#" method="POST" id="formCheckout"> {{-- Ganti action ke route store --}}
					<div class="mb-3">
						<input type="password" name="pin" class="form-control form-control-lg text-center fw-bold letter-spacing-2" maxlength="6" inputmode="numeric" placeholder="••••••" required>
					</div>
					<button type="submit" class="btn btn-primary w-100 rounded-3">Proses Transaksi</button>
					<button type="button" class="btn btn-link text-muted w-100 mt-2 text-decoration-none" data-bs-dismiss="modal">Batal</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@push('styles')
<style>
	/* Styling Input Besar */
	.input-group-lg .form-control {
		height: 60px;
	}

	/* Product Card Active State */
	.product-card {
		border: 2px solid transparent;
		transition: all 0.2s;
	}
	.product-card:hover {
		transform: translateY(-5px);
	}
	.product-card.active {
		border-color: var(--primary-color);
		background-color: rgba(var(--primary-color), 0.05);
	}
	.product-card.active .selected-check {
		display: block !important;
	}

	/* Bottom Sheet Animation */
	.transition-all {
		transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
	}

	/* Dark Mode Adjustments */
	[data-bs-theme="dark"] .bg-light {
		background-color: rgba(255,255,255, 0.05) !important;
	}
	[data-bs-theme="dark"] .form-control {
		color: white;
	}
	[data-bs-theme="dark"] .form-control::placeholder {
		color: rgba(255,255,255, 0.3);
	}
</style>
@endpush

@push('scripts')
<script>
	// Variable Global
	let selectedProductCode = null;

	// 1. Logic Pilih Produk
	function selectProduct(element, code, name, price) {
		// Cek apakah nomor HP sudah diisi
		let target = $('#targetInput').val();
		if(target.length < 4) {
			alert('Mohon masukkan nomor HP terlebih dahulu.');
			$('#targetInput').focus();
			return;
		}

		// Reset visual semua card
		$('.product-card').removeClass('active');

		// Set visual card yang dipilih
		$(element).addClass('active');

		// Update Data
		selectedProductCode = code;
		$('#totalDisplay').text('Rp ' + new Intl.NumberFormat('id-ID').format(price));
		$('#productNameDisplay').text(name);

		$('#inputProductCode').val(code);
		$('#inputTarget').val(target);

		// Munculkan Bottom Sheet
		$('#confirmationSheet').css('transform', 'translateY(0)');
	}

	// 2. Logic Deteksi Provider (Sederhana)
	$('#targetInput').on('input', function() {
		let number = $(this).val();
		let provider = 'Unknown';

		// Hide bottom sheet jika user ubah nomor
		$('#confirmationSheet').css('transform', 'translateY(100%)');
		$('.product-card').removeClass('active');

		// Logic Prefix Sederhana (Bisa diperlengkap)
		if (/^081[1-3]/.test(number) || /^085[2-3]/.test(number)) {
			provider = 'Telkomsel';
		} else if (/^081[4-6]/.test(number) || /^085[5-8]/.test(number)) {
			provider = 'Indosat';
		} else if (/^08(1[7-9]|59|7[7-8])/.test(number)) {
			provider = 'XL';
		} else if (/^08(3[1-3]|38)/.test(number)) {
			provider = 'Axis';
		} else if (/^08(9[5-9])/.test(number)) {
			provider = 'Tri';
		}

		// Update UI Text Provider
		if (number.length >= 4) {
			$('#providerName').text(provider).addClass('text-primary fw-bold');
			// Filter Produk di Layar
			filterProducts(provider);
		} else {
			$('#providerName').text('Menunggu input nomor...').removeClass('text-primary fw-bold');
			$('.product-item').show(); // Show all jika nomor pendek
		}
	});

	// 3. Filter Produk Cards
	function filterProducts(providerName) {
		if(providerName === 'Unknown') return;

		let found = 0;
		$('.product-item').each(function() {
			let itemProvider = $(this).data('provider');

			// Logic filter: Tampilkan jika provider cocok (Case insensitive)
			// Di sini saya pakai includes karena data dummy kadang tidak rapi
			if (itemProvider.toLowerCase().includes(providerName.toLowerCase()) ||
				providerName.toLowerCase().includes(itemProvider.toLowerCase())) {
				$(this).show();
				found++;
			} else {
				$(this).hide();
			}
		});

		if(found === 0) {
			$('#emptyState').removeClass('d-none');
		} else {
			$('#emptyState').addClass('d-none');
		}
	}
</script>
@endpush
