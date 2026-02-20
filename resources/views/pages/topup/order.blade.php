@extends('layouts.app')

@section('title', 'Top Up ' . $brand->name)

@section('content')
<div class="container mt-4 mb-5">
	<div class="row g-4">

		{{-- SIDEBAR: Info Brand (Sticky di Desktop) --}}
		<div class="col-lg-4">
			<div class="glass-card shadow-sm sticky-top p-4 text-center" style="top: 90px; z-index: 10;">
				@php $image = $brand->image ? config('app.asset_url').assetParse($brand->image) : 'https://via.placeholder.com/150'; @endphp
				<img src="{{ $image }}" class="rounded mb-3" width="80" alt="{{ $brand->name }}">
				<h4 class="fw-bold mb-1" style="color: var(--text-main)">{{ $brand->name }}</h4>
				<p class="small mb-3" style="color: var(--text-muted)">Layanan Top Up & Pembelian Paket Otomatis.</p>

				<div class="alert alert-info small text-start border-0 bg-opacity-10 bg-info mb-0">
					<i class="fas fa-info-circle me-1"></i>
					Pastikan nomor/ID yang dimasukkan benar. Kesalahan input bukan tanggung jawab kami.
				</div>
			</div>
		</div>

		{{-- MAIN CONTENT: Form Order --}}
		<div class="col-lg-8">
			<form id="form-order-prabayar">
				<div class="glass-card shadow-sm mb-4 p-4">
					<h5 class="fw-bold mb-3" style="color: var(--text-main)">
						<span class="badge bg-primary rounded-circle me-2" style="width: 28px; height: 28px; line-height: 20px;">1</span>
						Masukkan Tujuan
					</h5>

					@php $category = $brand?->category?->name; @endphp
					<input type="hidden" id="category" value="{{ $category }}">
					{{-- @if(str_contains(strtolower($brand?->category?->name), 'game'))
						<div class="row g-3">
							<div class="col-7">
								<label class="form-label small fw-bold" style="color: var(--text-muted)">User ID</label>
								<input type="tel" class="form-control form-control-lg custom-input" placeholder="Contoh: 12345678">
							</div>
							<div class="col-5">
								<label class="form-label small fw-bold" style="color: var(--text-muted)">Zone ID</label>
								<input type="tel" class="form-control form-control-lg custom-input" placeholder="Contoh: 1234">
							</div>
						</div>
					@else
						<div class="form-group">
							<label class="form-label small fw-bold" style="color: var(--text-muted)">Nomor Pelanggan</label>
							<input type="tel" class="form-control form-control-lg custom-input" placeholder="Masukkan nomor pelanggan">
						</div>
					@endif --}}
					@php $category = $brand?->category?->name; @endphp
					<input type="hidden" id="category_name" value="{{ $category }}">

					{{-- Container Utama Input --}}
						@if(str_contains(strtolower($brand?->category?->name), 'game'))
						{{-- SKENARIO A: GAME (User ID + Zone ID) --}}
						<div class="row g-3">
							<div class="col-7">
								<label class="form-label small fw-bold" style="color: var(--text-muted)">User ID</label>
								<div class="position-relative">
									<input type="tel" id="game_user_id" class="form-control form-control-lg custom-input input-check" placeholder="12345678">
									<div id="status-icon-container" class="status-icon-container position-absolute top-50 end-0 translate-middle-y me-3 d-none"></div>
								</div>
							</div>

							<div class="col-5">
								<label class="form-label small fw-bold" style="color: var(--text-muted)">Zone ID</label>
								<div class="position-relative">
									<input type="tel" id="game_zone_id" class="form-control form-control-lg custom-input input-check pe-5" placeholder="1234">

									{{-- Icon Loading Muncul di Kolom Zone ID --}}
									<div id="status-icon-container" class="status-icon-container position-absolute top-50 end-0 translate-middle-y me-3 d-none"></div>
								</div>
							</div>
						</div>
						@else
						{{-- SKENARIO B: NON-GAME (Satu Kolom) --}}
						<div class="form-group">
							<label class="form-label small fw-bold" style="color: var(--text-muted)">Nomor Pelanggan</label>
							<div class="position-relative">
								<input type="tel" id="single_target" class="form-control form-control-lg custom-input input-check pe-5" placeholder="Masukkan nomor pelanggan">

								{{-- Icon Loading Muncul Disini --}}
								<div id="status-icon-container" class="status-icon-container position-absolute top-50 end-0 translate-middle-y me-3 d-none"></div>
							</div>
						</div>
						@endif

						{{-- TEMPAT NAMA USER MUNCUL (Di bawah semua input) --}}
						<small id="username-display" class="fw-bold mt-2 d-block ms-1"></small>
				</div>

				<div class="glass-card shadow-sm mb-4 p-4">
					<h5 class="fw-bold mb-3" style="color: var(--text-main)">
						<span class="badge bg-primary rounded-circle me-2" style="width: 28px; height: 28px; line-height: 20px;">2</span>
						Pilih Nominal
					</h5>

					{{-- TAB NAVIGASI --}}
					@if($groupedProducts->count() > 1)
						<ul class="nav nav-pills mb-3 gap-2" id="pills-tab" role="tablist">
							@foreach($groupedProducts as $type => $items)
								<li class="nav-item" role="presentation">
									<button class="nav-link rounded-pill {{ $loop->first ? 'active' : '' }} btn-sm px-3 custom-tab-btn"
											id="pills-{{ Str::slug($type) }}-tab"
											data-bs-toggle="pill"
											data-bs-target="#pills-{{ Str::slug($type) }}"
											type="button" role="tab">
										@if($type == 'umum' || $type == 'pulsa') Pulsa Reguler
										@elseif($type == 'data') Paket Data
										@elseif($type == 'masa-aktif') Masa Aktif
										@elseif($type == 'game') Diamonds
										@else {{Str::title(str_replace('-', ' ', $type))}}
										@endif
									</button>
								</li>
							@endforeach
						</ul>
					@endif

					{{-- TAB CONTENT --}}
					<div class="tab-content" id="pills-tabContent">
						@foreach($groupedProducts as $type => $items)
							<div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
								id="pills-{{ Str::slug($type) }}" role="tabpanel">

								<div class="row g-2 g-md-3">
									@foreach($items as $product)
										@php $isGangguan = $product->buyer_product_status == 0 || $product->status === 0; @endphp

										<div class="col-6 col-md-4">
											<input type="radio"
												class="btn-check"
												name="product_sku"
												id="sku_{{ $product->buyer_sku_code }}"
												value="{{ $product->id }}"
												data-price="{{ $product->selling_price }}"
												{{ $isGangguan ? 'disabled' : '' }}>

											<label class="btn w-100 h-100 p-3 text-start product-card-label position-relative d-flex flex-column justify-content-center {{ $isGangguan ? 'card-disabled' : '' }}"
												for="sku_{{ $product->buyer_sku_code }}">

												<div class="check-icon position-absolute top-0 end-0 m-2 opacity-0">
													<i class="fas fa-check-circle fa-lg"></i>
												</div>

												{{-- <div class="fw-bold text-truncate mb-1 product-name" style="font-size: 0.9rem;"> --}}
												<div class="fw-bold mb-1 product-name" style="font-size: 0.9rem;">
													{{ $product->product_name }}
												</div>
												<div class="product-price fw-bold">
													{{ $product->selling_price_rupiah }}
												</div>

												{{-- Overlay Visual Gangguan --}}
												@if($isGangguan)
													<div class="overlay-disabled d-flex align-items-center justify-content-center small rounded fw-bold">
														<i class="fas fa-ban me-1"></i> GANGGUAN
													</div>
												@endif
											</label>
										</div>
									@endforeach
								</div>

							</div>
						@endforeach
					</div>
				</div>


				{{-- BAGIAN 3: METODE PEMBAYARAN --}}
				<div class="glass-card shadow-sm mb-4 p-4">
					<h5 class="fw-bold mb-3" style="color: var(--text-main)">
						<span class="badge bg-primary rounded-circle me-2" style="width: 28px; height: 28px; line-height: 20px;">3</span>
						Metode Pembayaran
					</h5>
					<div class="mb-3">
						<input type="radio" class="btn-check" name="payment_method" id="pay-qris" value="QRIS"
							data-fee-percent="0" data-fee-flat="0" disabled>

						<label class="payment-special-card w-100 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center position-relative overflow-hidden" for="pay-qris">
							<div class="ribbon-best">BEST PRICE</div>
							<div class="mb-3 mb-md-0 position-relative z-1">
								<div class="fw-bold text-white fs-6 mb-2">QRIS {{ config('app.name') }} (All Payment)</div>
								<div class="d-flex align-items-center flex-wrap gap-1">
									<img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" height="50" class="bg-white rounded px-1 me-1">

									<img src="https://www.pointstar-consulting.com/wp-content/uploads/2022/02/gopay-integration.png" height="25" class="bg-white rounded p-1">
									<img src="https://play-lh.googleusercontent.com/Nufh4t_SYdTi5vyO-ShyrPnmha28KK2o5-VQYfZbkgrtX4VLQRGXqkK4an9waaAFf-FrBv_qLkzhxbBh-n-y" height="25" class="bg-white rounded p-1">
									<img src="https://1000logos.net/wp-content/uploads/2021/03/Dana-logo.png" height="25" class="bg-white rounded p-1">
									<img src="https://ovo.zendesk.com/hc/theming_assets/01HZH2DGCD8NM02JGTES5PC9ZT" height="25" class="bg-white rounded p-1">
									<img src="https://upload.wikimedia.org/wikipedia/commons/8/85/LinkAja.svg" height="25" class="bg-white rounded p-1">
									<span class="text-white">|</span>
									<img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiEShyphenhyphenecrz4Lyrs3a8-h3oG-6Hqh5FMdYhVba8_4NMy_60IXDS6stwE6cSp_LL9TfhfpLM4I6IyGZTZUL5ZfTOHAsTKTYx8FqW3xVPM0_RiXRRBgoajU6OT-G5BXtKPFzMsfrnBgmTq2OCD/s1000/logo+bank+bca-01.png" height="25" class="bg-white rounded p-1">
									<img src="https://upload.wikimedia.org/wikipedia/commons/5/5b/Logo_Bank_Rakyat_Indonesia.svg" height="25" class="bg-white rounded p-1">
									<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" height="25" class="bg-white rounded p-1">
								</div>
								<small class="text-white-50 mt-1 d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">SATU QR CODE UNTUK SEMUA PAYMENT</small>
							</div>

							{{-- TAMPILAN 1: MOBILE (Dashed Box) --}}
							{{-- Hanya muncul di layar kecil (d-md-none) --}}
							<div class="price-dashed-wrapper w-100 text-center position-relative z-1 d-md-none">
								<div class="fw-bold text-danger fs-5 payment-price-display">Min. Rp 100</div>
							</div>

							<div class="text-end z-1 pe-4 me-3 d-none d-md-block">
								<div class="fw-bold text-danger fs-5 payment-price-display">Min. Rp 100</div>
							</div>

						</label>
					</div>

					<div class="accordion custom-accordion" id="paymentAccordion">
						{{-- <div class="accordion-item mb-2 border-0 overflow-hidden rounded-3">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEwallet">
									<div class="d-flex w-100 justify-content-between align-items-center pe-3">
										<span class="fw-bold">QRIS</span>
									</div>
								</button>
							</h2>
							<div id="collapseEwallet" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
								<div class="accordion-body p-2">
									<div class="row g-2">
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay_DANA" value="DANA"
												data-fee-percent="0" data-fee-flat="0" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between" for="pay_DANA">
												<div class="mb-3">
													<img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" height="25" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display">Rp 4.750</div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay_OVO" value="OVO"
												data-fee-percent="1.50" data-fee-flat="0" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between" for="pay_OVO">
												<div class="mb-3">
													<img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" height="25" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display">Rp 4.750</div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div> --}}

						<div class="accordion-item mb-2 border-0 overflow-hidden rounded-3">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVA" id="btn-virtual-account" disabled>
									<div class="d-flex w-100 justify-content-between align-items-center pe-3">
										<span class="fw-bold">Virtual Account</span>
									</div>
								</button>
							</h2>
							<div id="collapseVA" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
								<div class="accordion-body p-2">
									<div class="row g-2">
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-bca" value="BCA_VIRTUAL_ACCOUNT"
												data-fee-percent="0" data-fee-flat="5000" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center align-items-md-start text-md-start" for="pay-bca">
												<div class="mb-2">
													<img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" height="35" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display"></div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-mandiri" value="MANDIRI_VIRTUAL_ACCOUNT"
												data-fee-percent="0" data-fee-flat="3000" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center align-items-md-start text-md-start" for="pay-mandiri">
												<div class="mb-2">
													<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" height="35" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display"></div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-bni" value="BNI_VIRTUAL_ACCOUNT"
												data-fee-percent="0" data-fee-flat="3000" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center align-items-md-start text-md-start" for="pay-bni">
												<div class="mb-2">
													<img src="https://upload.wikimedia.org/wikipedia/commons/f/f0/Bank_Negara_Indonesia_logo_%282004%29.svg" height="35" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display"></div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-bri" value="BRI_VIRTUAL_ACCOUNT"
												data-fee-percent="0" data-fee-flat="3000" disabled>
											{{-- <label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center text-center align-items-md-start text-md-start" for="pay-alfamart"> --}}
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center align-items-md-start text-md-start" for="pay-bri">
												<div class="mb-2">
													<img src="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg" height="35" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display"></div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-permata-bank" value="PERMATA_BANK_VIRTUAL_ACCOUNT"
												data-fee-percent="0" data-fee-flat="0" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center align-items-md-start text-md-start" for="pay-permata-bank">
												<div class="mb-2">
													<img src="https://indofarm.id/public/permata.png" height="35" class="bg-white rounded p-1">
												</div>
												<div>
													<div class="text-price fw-bold mb-1 payment-price-display"></div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>

						{{-- <div class="accordion-item mb-0 border-0 overflow-hidden rounded-3">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStore">
									<div class="d-flex w-100 justify-content-between align-items-center pe-3">
										<span class="fw-bold">Convenience Store</span>
									</div>
								</button>
							</h2>
							<div id="collapseStore" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
								<div class="accordion-body p-2">
									<div class="row g-2">
										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-alfamart" value="ALFAMART"
												data-fee-percent="0" data-fee-flat="0" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center text-center align-items-md-start text-md-start" for="pay-alfamart">
												<div class="mb-3">
													<img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Alfamart_logo.svg" height="40" class="bg-white rounded p-1">
												</div>
												<div class="w-100">
													<div class="text-price fw-bold mb-1 payment-price-display">Rp 5.000</div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>

										<div class="col-6 col-md-4">
											<input type="radio" class="btn-check" name="payment_method" id="pay-indomaret" value="INDOMARET"
												data-fee-percent="0" data-fee-flat="0" disabled>
											<label class="payment-grid-card w-100 h-100 p-3 d-flex flex-column justify-content-between align-items-center text-center align-items-md-start text-md-start" for="pay-indomaret">
												<div class="mb-3">
													<img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Indomaret.svg" height="40" class="bg-white rounded p-1">
												</div>
												<div class="w-100">
													<div class="text-price fw-bold mb-1 payment-price-display">Rp 5.000</div>
													<small class="text-desc-payment d-block" style="font-size: 0.7rem;">Dicek Otomatis</small>
												</div>
											</label>
										</div>

									</div>
								</div>
							</div>
						</div> --}}

					</div>
				</div>

				<div class="glass-card shadow-sm mb-4 p-4" style="background: var(--card-bg);">
					<h5 class="fw-bold mb-3" style="color: var(--text-main)">
						<span class="badge bg-primary rounded-circle me-2" style="width: 28px; height: 28px; line-height: 20px;">4</span>
						Bukti Pembayaran
					</h5>
					<div class="form-floating mb-3">
						<input type="number" class="form-control bg-body text-body" id="whatsappNumber" placeholder="08xx">
						<label for="whatsappNumber">Nomor WhatsApp (Opsional)</label>
					</div>
					<p class="small text-muted">*Bukti transaksi akan dikirimkan ke nomor WhatsApp di atas jika diisi.</p>
				</div>

				{{-- <button type="button" id="order-button" class="btn btn-primary w-100 py-3 fw-bold fs-5 rounded-pill shadow hover-scale">
					<i class="fas fa-shopping-cart me-2"></i> Pesan Sekarang
				</button> --}}
			</form>

		</div>
	</div>
</div>

<div id="sticky-footer" class="fixed-bottom d-none transition-all">
	<div class="container py-2">
		<div class="d-flex align-items-center justify-content-between gap-3">

			{{-- BAGIAN KIRI: Info Produk & Harga (Dibuat Melebar dengan flex-grow-1) --}}
			<div class="d-flex align-items-center gap-3 overflow-hidden sticky-summary-box">

				{{-- Icon (Hanya di Tablet/PC) --}}
				<div class="rounded-3 align-items-center justify-content-center flex-shrink-0 sticky-icon-box d-none d-sm-flex">
					<img src="{{ asset('cloudnest.png') }}" id="sticky-img" width="30" alt="Icon">
				</div>

				{{-- Wrapper Teks (Full Width) --}}
				<div class="d-flex flex-column justify-content-center w-100">

					{{-- Nama Produk --}}
					<div class="sticky-sub text-truncate mb-1 fw-bold" id="sticky-product" style="font-size: 0.8rem; color: var(--sticky-text-sub);">
						Pilih Produk
					</div>

					{{-- KONTAINER RINCIAN (Hidden Default) --}}
					<div id="sticky-detail-container" class="d-none">

						{{-- Baris 1: Harga Produk --}}
						<div class="d-flex justify-content-between w-100 mb-0" style="font-size: 0.75rem; color: var(--sticky-text-sub);">
							<span class="opacity-75">Harga</span>
							<span class="fw-bold" id="sticky-base-price">Rp 0</span>
						</div>

						{{-- Baris 2: Biaya Layanan --}}
						<div class="d-flex justify-content-between w-100 mb-0" style="font-size: 0.75rem; color: var(--sticky-text-sub);">
							<span class="opacity-75">Biaya</span>
							<span class="fw-bold" id="sticky-admin-fee">Rp 0</span>
						</div>
					</div>

					{{-- Baris 3: TOTAL (Besar & Lebar) --}}
					<div class="d-flex justify-content-between w-100 align-items-end mt-1">
						<span class="fw-bold text-warning lh-1" style="font-size: 1.1rem;">Total</span>
						<span class="fw-bold text-warning lh-1" id="sticky-total-display" style="font-size: 1.1rem;">Rp 0</span>
					</div>

				</div>
			</div>

			{{-- BAGIAN KANAN: Tombol --}}
			<div class="flex-shrink-0 ms-2">
				<button type="button" id="sticky-btn-submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-lg d-flex align-items-center gap-2" style="height: 48px;">
					<i class="fas fa-shopping-cart"></i>
					<span>Bayar</span>
				</button>
			</div>

		</div>
	</div>
</div>

@endsection

@push('styles')
<style>
	.sticky-summary-box {
		/* [MOBILE DEFAULT] */
		flex-grow: 1;       /* Mengisi sisa ruang kosong sebelah tombol */
		min-width: 0;       /* Mencegah overflow text truncate */
		margin-right: 10px; /* Jarak sedikit dengan tombol */
	}

	/* [DESKTOP ONLY] - Mulai layar Medium (iPad/Laptop) */
	@media (min-width: 768px) {
		.sticky-summary-box {
			flex-grow: 0;        /* Stop melebar otomatis */
			margin-left: auto;   /* Dorong ke kanan (dekat tombol) */
			width: 500px;        /* Lebar fix mirip struk belanja */
			margin-right: 15px;  /* Jarak lebih lega di desktop */
		}
	}

	/* --- CUSTOM INPUT STYLE --- */
	/* Agar input background mengikuti tema (tidak putih silau di dark mode) */
	.custom-input {
		background-color: var(--bg-body);
		border: 1px solid var(--card-border);
		color: var(--text-main);
	}
	.custom-input:focus {
		background-color: var(--bg-body);
		border-color: var(--primary-color);
		color: var(--text-main);
		box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.25);
	}

	/* --- TAB BUTTON STYLE --- */
	.custom-tab-btn {
		color: var(--text-muted);
		border: 1px solid var(--card-border);
		background: var(--bg-body);
		transition: all 0.3s ease;
	}
	.custom-tab-btn.active {
		background-color: var(--primary-color) !important;
		border-color: var(--primary-color) !important;
		color: #fff !important;
	}

	/* --- PRODUCT CARD RADIO STYLE --- */
	.product-card-label {
		background-color: var(--bg-body); /* Ikuti background body/card */
		border: 1px solid var(--card-border);
		color: var(--text-main);
		transition: all 0.2s ease;
		border-radius: 12px;
	}

	/* Hover State (Agar tulisan tidak hilang) */
	.product-card-label:hover {
		border-color: var(--secondary-color);
		transform: translateY(-2px);
		color: var(--text-main); /* Pastikan text tetap terlihat */
	}

	/* Checked/Selected State */
	.btn-check:checked + .product-card-label {
		background-color: rgba(79, 70, 229, 0.1); /* Primary color low opacity */
		border-color: var(--primary-color);
		box-shadow: 0 0 0 1px var(--primary-color) inset;
	}

	/* Icon Check Warna */
	.btn-check:checked + .product-card-label .check-icon {
		opacity: 1 !important;
		color: var(--primary-color);
	}

	/* Text Warna saat Selected */
	.btn-check:checked + .product-card-label .product-name {
		color: var(--primary-color);
	}
	.product-price {
		color: var(--secondary-color); /* Warna harga selalu secondary */
	}

	/* --- OVERLAY GANGGUAN --- */


	/* --- DISABLED STATE (GANGGUAN) --- */
	/* Logic: Jika input disabled, label tetangganya berubah style */
	.btn-check:disabled + .product-card-label {
		cursor: not-allowed !important; /* Cursor tanda larang */
		opacity: 0.6; /* Membuat kartu terlihat redup */
		filter: grayscale(0.1); /* Membuat warna jadi agak abu-abu */
		border-color: var(--card-border) !important;
		background-color: rgba(0,0,0,0.05); /* Sedikit gelap */
	}

	/* Pastikan overlay gangguan tetap terlihat jelas meski opacity card turun */
	.overlay-disabled {
		position: absolute; top:0; left:0; right:0; bottom:0;
		background: rgba(20, 20, 20, 0.85); /* Background gelap transparan */
		color: #ff6b6b; /* Text Merah */
		z-index: 100;
		backdrop-filter: blur(1px);
		border-radius: 12px !important;
		cursor: not-allowed;

		/* Centering Text */
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 800;
		letter-spacing: 1px;
	}


	/* --- ACCORDION CUSTOM STYLE --- */
	.accordion-item {
		background-color: var(--bg-body); /* Warna background item saat collapsed */
		border: 1px solid var(--card-border);
	}

	.accordion-button {
		background-color: var(--bg-body);
		color: var(--text-main);
		box-shadow: none !important; /* Hapus shadow biru default */
	}

	.accordion-button:not(.collapsed) {
		background-color: rgba(79, 70, 229, 0.1); /* Primary color tipis saat dibuka */
		color: var(--primary-color);
		border-bottom: 1px solid var(--card-border);
	}

	/* Icon Panah Accordion */
	.accordion-button::after {
		filter: grayscale(1); /* Ubah panah jadi abu */
	}
	.accordion-button:not(.collapsed)::after {
		filter: hue-rotate(240deg); /* Ubah jadi biru saat aktif (hack css filter) */
	}




	 /* --- THEME COLORS (Sesuaikan dengan screenshot gelap) --- */
	:root {
		--pay-bg-dark: #1f2937; /* Warna dasar panel */
		--pay-card-dark: #293548; /* Warna kartu item */
		--pay-border: #374151;
	}


	/* Tambahkan icon centang kecil saat dipilih (Optional) */
	.btn-check:checked + .payment-grid-card::after {
		content: '\f00c'; /* FontAwesome Check */
		font-family: "Font Awesome 6 Free";
		font-weight: 900;
		position: absolute;
		top: 8px; right: 8px;
		color: var(--pay-ribbon);
		font-size: 0.8rem;
	}


	:root, [data-bs-theme="light"] {
		--pay-accordion-bg: #ffffff;
		--pay-accordion-header-bg: #f8fafc;
		--pay-accordion-header-hover: #f1f5f9;
		--pay-accordion-text: #1e293b; /* Teks Gelap */
		--pay-card-bg: #ffffff;
		--pay-card-border: #e2e8f0;
		--pay-card-hover: #f8fafc;
		--pay-price-text: #1e293b; /* Harga Gelap */
		--pay-muted-text: #64748b; /* Deskripsi Abu */
		--pay-highlight: #4f46e5;
		--accordion-arrow-filter: none; /* Panah default gelap */
		--pay-ribbon: #f59e0b;
		--pay-ribbon-light: #fbbf24; /* Oranye lebih terang untuk highlight atas */
		--pay-ribbon-dark: #d97706;  /* Oranye lebih gelap untuk gradient bawah */
		--pay-ribbon-shadow: #92400e; /* Oranye sangat gelap untuk border bawah */
		--sticky-bg: #ffffff;
		--sticky-border: #e2e8f0;
		--sticky-text-title: #1e293b; /* Slate 800 */
		--sticky-text-sub: #64748b;   /* Slate 500 */
		--sticky-icon-box-bg: #f1f5f9;
		--sticky-price-color: #d97706; /* Amber 600 (Gelap dikit biar kontras di putih) */
		--sticky-shadow: 0 -4px 20px rgba(0,0,0,0.08); /* Shadow halus */

		--special-card-bg: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%); /* Biru Muda Halus */
		--special-card-border: #bfdbfe;
		--special-card-text-title: #1e3a8a; /* Biru Gelap */
		--special-card-text-muted: #64748b; /* Abu-abu */
		--special-card-price-active: #1e40af; /* Biru Vivid untuk harga aktif */
		--special-card-divider: #cbd5e1;
		--dashed-border-color: rgba(0,0,0, 0.2);
	}

	/* Dark Mode Overrides */
	[data-bs-theme="dark"] {
		--pay-accordion-bg: #1f2937;
		--pay-accordion-header-bg: #2d3748;
		--pay-accordion-header-hover: #374151;
		--pay-accordion-text: #ffffff; /* Teks Putih */
		--pay-card-bg: #293548;
		--pay-card-border: #374151;
		--pay-card-hover: #374151;
		--pay-price-text: #e5e7eb; /* Harga Putih Terang */
		--pay-muted-text: #9ca3af; /* Deskripsi Abu Terang */
		--accordion-arrow-filter: brightness(0) invert(1); /* Panah jadi putih */
		--pay-ribbon: #f59e0b;
		--pay-ribbon-light: #fbbf24;
		--pay-ribbon-dark: #d97706;
		--pay-ribbon-shadow: #92400e;

		--sticky-bg: #111827; /* Dark Navy */
		--sticky-border: rgba(255, 255, 255, 0.05);
		--sticky-text-title: #ffffff;
		--sticky-text-sub: rgba(255, 255, 255, 0.5);
		--sticky-icon-box-bg: #1f2937;
		--sticky-price-color: #fbbf24; /* Amber 400 (Terang) */
		--sticky-shadow: 0 -4px 20px rgba(0,0,0,0.4);

		--special-card-bg: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); /* Deep Navy/Purple */
		--special-card-border: #4f46e5;
		--special-card-text-title: #ffffff;
		--special-card-text-muted: rgba(255, 255, 255, 0.6);
		--special-card-price-active: #ffffff; /* Putih untuk harga aktif */
		--special-card-divider: rgba(255, 255, 255, 0.3);
		--dashed-border-color: rgba(255, 255, 255, 0.3);
	}


	/* Dashed Box Wrapper */
	.price-dashed-wrapper {
		border: 2px dashed var(--dashed-border-color);
		background-color: rgba(0, 0, 0, 0.05); /* Tint transparan aman di kedua mode */
		border-radius: 50px;
		padding: 10px 0;
		margin-top: 5px;
	}



	.payment-special-card {
		background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
		border: 1px solid var(--pay-highlight);
		border-radius: 12px;
		cursor: pointer;
		transition: transform 0.2s;
	}
	.payment-special-card:hover {
		transform: translateY(-3px);
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
	}
	.ribbon-best {
		position: absolute;
		top: 20px;
		right: -35px;
		width: 130px;
		text-align: center;
		transform: rotate(45deg);
		font-size: 0.7rem;
		font-weight: 800;
		letter-spacing: 0.5px;
		color: #fff;
		z-index: 100; /* Pastikan berada di atas elemen lain */

		/* --- EFEK 3D DIMULAI DISINI --- */

		/* 1. Gunakan Gradient alih-alih warna solid */
		/* Ini menciptakan ilusi cahaya datang dari atas */
		background: linear-gradient(to bottom, var(--pay-ribbon) 0%, var(--pay-ribbon-dark) 100%);

		/* 2. Tambahkan Border Tipis untuk Highlight dan Shadow */
		/* Border atas terang seolah terkena cahaya */
		border-top: 1px solid var(--pay-ribbon-light);
		/* Border bawah gelap untuk mempertegas bentuk */
		border-bottom: 1px solid var(--pay-ribbon-shadow);

		/* 3. Box Shadow yang lebih kuat */
		/* Membuat pita terlihat "mengambang" di atas kartu */
		box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);

		/* 4. (Opsional) Text Shadow halus agar tulisan lebih "pop" */
		text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
	}

	/* --- 2. ACCORDION STYLE (ADAPTIF) --- */
	.custom-accordion .accordion-item {
		background-color: var(--pay-accordion-bg);
		border: 1px solid var(--pay-card-border);
		color: var(--pay-accordion-text);
	}

	.custom-accordion .accordion-button {
		background-color: var(--pay-accordion-header-bg);
		color: var(--pay-accordion-text); /* Teks mengikuti tema */
		box-shadow: none !important;
		font-weight: 600;
	}

	.custom-accordion .accordion-button:hover {
		background-color: var(--pay-accordion-header-hover);
	}

	/* Filter warna panah accordion */
	.custom-accordion .accordion-button::after {
		filter: var(--accordion-arrow-filter);
	}

	/* Saat Accordion Dibuka */
	.custom-accordion .accordion-button:not(.collapsed) {
		background-color: var(--pay-accordion-header-hover);
		color: var(--pay-accordion-text);
		border-bottom: 1px solid var(--pay-card-border);
	}

	/* --- 3. GRID ITEM CARD (ADAPTIF) --- */
	.payment-grid-card {
		background-color: var(--pay-card-bg);
		border: 1px solid var(--pay-card-border);
		color: var(--pay-accordion-text); /* Teks mengikuti tema */
		border-radius: 10px;
		cursor: pointer;
		transition: all 0.2s;
		min-height: 100px;
	}

	/* Hover State */
	.payment-grid-card:hover {
		background-color: var(--pay-card-hover);
		border-color: var(--primary-color);
	}

	/* Selected State */
	.btn-check:checked + .payment-grid-card,
	.btn-check:checked + .payment-special-card {
		/* border-color: var(--pay-ribbon) !important; */

		border: 2px solid var(--pay-ribbon) !important;

		/* Background tint orange tipis */
		background-color: rgba(245, 158, 11, 0.1);

		/* Pastikan posisi relative agar z-index main */
		position: relative;

		/* Opsional: Tambahkan sedikit shadow agar makin menonjol */
		/* box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2); */
		box-shadow: 0px 0px 10px 3px rgba(245, 158, 11, 0.2);
	}
	.btn-check:checked + .payment-grid-card .text-price,
	.btn-check:checked + .payment-grid-card .text-desc-payment {
		color: var(--text-main) !important; /* Pastikan teks tetap terbaca saat dipilih */
	}

	/* --- TEXT COLORS --- */
	.text-price {
		color: var(--pay-price-text);
	}
	.text-desc-payment {
		color: var(--pay-muted-text);
		font-style: italic;
	}


	/* Checkout Footer */
	#sticky-footer {
		background-color: var(--sticky-bg);
		border-top: 1px solid var(--sticky-border);
		box-shadow: var(--sticky-shadow);
		z-index: 1050;

		/* Animasi Slide Up */
		transform: translateY(100%);
		transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s ease;
	}

	#sticky-footer.show {
		transform: translateY(0);
	}

	/* Icon Box di Kiri */
	.sticky-icon-box {
		width: 48px;
		height: 48px;
		background-color: var(--sticky-icon-box-bg);
		border: 1px solid var(--sticky-border);
	}

	.sticky-sub {
		color: var(--sticky-text-sub);
		/* Pastikan teks rincian tidak terlalu tebal agar tidak rebutan fokus */
		font-weight: 400;
		letter-spacing: 0.3px;
	}

	/* Tombol Pesan */
	#sticky-btn-submit {
		background-color: #2563eb; /* Royal Blue (Aman di kedua tema) */
		border: none;
		padding-top: 10px;
		padding-bottom: 10px;
		color: #fff;
	}
	#sticky-btn-submit:hover {
		background-color: #1d4ed8;
	}

	/* Warna Total agar menonjol (seperti referensi) */
	.text-warning {
		color: #f59e0b !important; /* Amber-500 */
	}

	/* Pastikan di dark mode tetap terang */
	[data-bs-theme="dark"] .text-warning {
		color: #fbbf24 !important; /* Amber-400 */
	}

	/* Agar garis rincian rapi */
	#sticky-detail-container {
		border-bottom: 1px dashed rgba(255,255,255,0.1);
		padding-bottom: 4px;
		margin-bottom: 4px;
	}

	/* Input Valid/Invalid States */
	.input-valid {
		border-color: #10b981 !important; /* Hijau */
		background-image: none !important; /* Hapus icon bawaan browser jika ada */
		box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
	}
	.input-invalid {
		border-color: #ef4444 !important; /* Merah */
		background-image: none !important;
		box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
	}

	/* EFEK PRODUK TERKUNCI */
	.locked-item {
		opacity: 0.5;                /* Membuat transparan */
		filter: grayscale(100%);     /* Membuat jadi hitam putih */
		cursor: not-allowed !important; /* Kursor tanda larang */
		pointer-events: auto;        /* Tetap bisa diklik untuk memicu alert */
	}

	/* Animasi getar jika dipaksa klik (Opsional) */
	@keyframes shake {
		0% { transform: translateX(0); }
		25% { transform: translateX(-5px); }
		50% { transform: translateX(5px); }
		75% { transform: translateX(-5px); }
		100% { transform: translateX(0); }
	}
	.shake-input {
		animation: shake 0.3s ease-in-out;
		border-color: #ef4444 !important; /* Merah */
		box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
	}
</style>
@endpush

@push('scripts')
	<script>
		$(() => {
			// --- 1. DEFINISI VARIABEL ---
			const $statusIcon = $('.status-icon-container');
			const $usernameDisplay = $('#username-display');

			// Input Elements
			const $gameUser = $('#game_user_id');
			const $gameZone = $('#game_zone_id');
			const $singleTarget = $('#single_target');

			// Product Elements
			const $productRadios = $('input[name="product_sku"]');
			const $productCards = $productRadios.next('label'); // Asumsi label adalah cardnya

			let typingTimer;
			const doneTypingInterval = 800; // Delay 0.8 detik

			// Cek apakah ini mode GAME atau BUKAN
			const isGameMode = $gameUser.length > 0;

			// --- 2. FUNGSI LOGIKA UTAMA ---

			function checkInput($this) {
				clearTimeout(typingTimer);
				resetVisuals();

				let fullTarget = "";
				let isValidLength = false;

				if (isGameMode) {
					// Logic Game: Gabungkan UserID + ZoneID
					let uVal = $gameUser.val().trim();
					let zVal = $gameZone.val().trim();

					// Baru proses cek kalau keduanya terisi
					if (uVal.length > 0 && zVal.length > 0) {
						fullTarget = uVal + "|" + zVal; // Format kirim ke server (sesuaikan)
						isValidLength = true;
					}
				} else {
					// Logic Single: Cek Nomor HP/PLN
					let val = $singleTarget.val().trim();
					if (val.length >= 4) { // Minimal 4 digit
						fullTarget = val;
						isValidLength = true;
					}
				}

				// Jika input memenuhi syarat panjang minimal
				if (isValidLength) {
					// Tampilkan Spinner Loading
					$statusIcon.removeClass('d-none').html('<i class="fas fa-circle-notch fa-spin text-secondary fs-5"></i>');

					// Lock Produk sementara loading
					lockProducts();

					// console.log(isGameMode);

					// Tunggu user berhenti ngetik
					typingTimer = setTimeout(function() {
						performServerCheck(fullTarget);
					}, doneTypingInterval);
				} else {
					// Jika kosong/belum lengkap, pastikan produk terkunci
					lockProducts();
				}
			}

			// --- 3. SIMULASI API CHECK ---
			function performServerCheck(targetID) {
				// Ganti ini dengan AJAX $.post ke backend Anda
				// console.log("Checking ID: " + targetID);

				setTimeout(() => {
					// SIMULASI RESPONSE SUKSES
					// Logic: Anggap sukses jika targetID tidak mengandung angka '666'
					let isSuccess = !targetID.includes('666');

					if (isSuccess) {
						showSuccess("CloudNest Player (" + targetID + ")");
					} else {
						showError("ID Tidak Ditemukan");
					}
				}, 1000);
			}

			// --- 4. FUNGSI VISUAL ---

			function showSuccess(name) {
				$statusIcon.html('<i class="fas fa-check-circle text-success fs-4"></i>');
				$usernameDisplay.removeClass('text-danger').addClass('text-success').text(name);

				// Beri border hijau ke input aktif
				if(isGameMode) {
					$gameUser.addClass('input-valid');
					$gameZone.addClass('input-valid');
				} else {
					$singleTarget.addClass('input-valid');
				}

				unlockProducts(); // BUKA KUNCI PRODUK
			}

			function showError(msg) {
				$statusIcon.html('<i class="fas fa-times-circle text-danger fs-4"></i>');
				$usernameDisplay.removeClass('text-success').addClass('text-danger').text(msg);

				if(isGameMode) {
					$gameUser.addClass('input-invalid');
					$gameZone.addClass('input-invalid');
				} else {
					$singleTarget.addClass('input-invalid');
				}

				lockProducts(); // TETAP KUNCI
			}

			function resetVisuals() {
				$statusIcon.empty(); // Hapus icon
				$usernameDisplay.text('');
				$('.input-check').removeClass('input-valid input-invalid');
			}

			function lockProducts() {
				$productRadios.prop('disabled', true).prop('checked', false);
				$productCards.addClass('locked-item');

				// Reset Sticky Footer
				$('input[name="payment_method"]:checked').prop('checked', false); // Reset payment juga
				$('#sticky-footer').removeClass('show').addClass('d-none');
			}

			function unlockProducts() {
				$productRadios.prop('disabled', false);
				$productCards.removeClass('locked-item');
			}

			// --- 5. EVENT LISTENERS ---

			// Pasang listener di semua input yang punya class .input-check
			$('.input-check').on('input paste', function() {
				checkInput($(this));
			});

			// Handler Klik Produk Terkunci (Efek Shake/Scroll)
			$(document).on('click', '.locked-item', function(e) {
				// Cari input pertama yang kosong untuk difokuskan
				let $targetFocus = isGameMode ? $gameUser : $singleTarget;

				$('html, body').animate({ scrollTop: $targetFocus.offset().top - 150 }, 300);
				$targetFocus.focus();

				// Tampilkan alert kecil atau shake visual (opsional)
			});

			// Inisialisasi Awal
			lockProducts();
		})

		let $formatter
		let $swal
		let $parse
		let table
		let typingTimer;
		const doneTypingInterval = 1000;

		// Variabel Global untuk menyimpan harga sementara
		let currentProductPrice = 0;
		let currentAdminFee = 0;

		const $inputTarget = $('.custom-input'); // <--- GANTI dengan ID input user anda (contoh: #target, #id_player)
		const $productRadios = $('input[name="product_sku"]');
		const $productCards = $productRadios.next('label');

		function checkInputStatus() {
			let value = $inputTarget.val();

			// Jika Kosong atau hanya spasi
			if (!value || value.trim().length === 0) {
				// LOCK
				$productRadios.prop('disabled', true);   // Matikan Radio
				$productRadios.prop('checked', false);   // Uncheck pilihan (Reset)
				$productCards.addClass('locked-item');   // Tambah visual gelap

				// Sembunyikan Sticky Footer (Reset Totalan)
				$('#sticky-footer').removeClass('show').addClass('d-none');
				$('body').css('padding-bottom', '0');
			} else {
				// UNLOCK
				$productRadios.prop('disabled', false);  // Hidupkan Radio
				$productCards.removeClass('locked-item'); // Hapus visual gelap
			}
		}

		// 3. JALANKAN SAAT LOAD & SAAT KETIK
		checkInputStatus(); // Cek awal saat halaman dimuat
		$inputTarget.on('input keyup paste', function() {
			checkInputStatus();
			const currentVal = $(this).val()

			// console.log($(this).val().length);

			clearTimeout(typingTimer)

			if ($(this).val().length > 5) {
				// typingTimer = setTimeout(function () {
				// 	checkUsername({
				// 		category: $('#category').val(),
				// 		target: currentVal,
				// 	});
				// }, doneTypingInterval);
			}
		});

		$(document).on('click', '.locked-item', function(e) {
			e.preventDefault(); // Cegah aksi klik

			// Scroll ke Input ID (supaya user sadar)
			$('html, body').animate({
				scrollTop: $inputTarget.offset().top - 100
			}, 300);

			// Fokus & Animasi Shake pada Input ID
			$inputTarget.focus().addClass('shake-input');

			// Hapus animasi shake setelah selesai
			setTimeout(() => {
				$inputTarget.removeClass('shake-input');
			}, 500);

			// Opsional: Tampilkan Pesan Toast/Alert
			// Swal.fire('Mohon Maaf', 'Silakan masukkan User ID / Nomor Tujuan terlebih dahulu.', 'warning');
		});

		async function checkUsername(payload) {
			const {status, data: {data}, data: {meta}} = await postRequest("{{ route('api.provider.check-username') }}", payload);
			console.log(meta);
			console.log(data);

			// if (status !== 200) {
			// 	await $swal.warning({
			// 		text: meta?.message,
			// 		hideClass: module.var_swal.fadeOutUp,
			// 	})

			// 	return $(this).attr('disabled', false).html(originalText)
			// }

			// check-username
			// $.ajax({
			// 	url: "{{ route('api.provider.check-username') }}",
			// 	type: "POST",
			// 	data: data,
			// 	beforeSend: function() {
			// 		$loadingIcon.fadeIn()
			// 		$targetInput.addClass('text-muted')
			// 	},
			// 	success: function(response) {
			// 		if (response?.data?.rc === '00') {
			// 			const data = response.data

			// 			let info = `<i class="fas fa-check-circle me-1"></i> ${data.name ?? data}`;

			// 			if (data.segment_power) info += ` <span class="badge bg-info text-dark ms-1">${data.segment_power}</span>`;

			// 			$resultDiv.html(info)
			// 			.removeClass('text-danger').addClass('text-success')
			// 			.slideDown()
			// 		} else {
			// 			$resultDiv.html('<i class="fas fa-times-circle me-1"></i> ' + response?.data?.message)
			// 			.removeClass('text-success').addClass('text-danger')
			// 			.slideDown()
			// 		}
			// 	},
			// 	error: function(xhr) {
			// 		let msg = xhr.responseJSON && xhr.responseJSON.meta.message ? xhr.responseJSON.meta.message : 'ID Pelanggan tidak ditemukan.';
			// 		$resultDiv.html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg)
			// 		.removeClass('text-success').addClass('text-danger')
			// 		.slideDown()
			// 	},
			// 	complete: function() {
			// 		$loadingIcon.fadeOut()
			// 		$targetInput.removeClass('text-muted')
			// 	}
			// })
		}

		// Fungsi Formatter Rupiah
		const formatRupiah = (number) => {
			return new Intl.NumberFormat('id-ID', {
				style: 'currency',
				currency: 'IDR',
				minimumFractionDigits: 0
			}).format(number);
		}

		// Fungsi Update Total di Sticky Footer
		function updateStickyCalculation() {
			const total = parseFloat(currentProductPrice) + parseFloat(currentAdminFee);

			// Update Teks Total
			$('#sticky-total-display').text(formatRupiah(total));

			// Update Teks Rincian
			$('#sticky-base-price').text(formatRupiah(currentProductPrice));
			$('#sticky-admin-fee').text(formatRupiah(currentAdminFee));

			// Tampilkan/Sembunyikan Rincian jika ada fee
			if (currentProductPrice > 0) {
				// $('#sticky-breakdown').removeClass('d-none');
				$('#sticky-detail-container').removeClass('d-none');
			} else {
				// $('#sticky-breakdown').addClass('d-none');
				$('#sticky-detail-container').addClass('d-none');
			}
		}

		$(async () => {
			module = await initModul()
			$formatter = module.formatter
			$swal = module.swal
			$parse = module.parse

			$('input[name="product_sku"]').change(function() {
				let priceRaw = $(this).data('price') || 0; // Pastikan input produk punya data-price
				let currentPrice = parseFloat(priceRaw);

				// Panggil fungsi update masal
				updateAllPaymentChannels(currentPrice);

				let sticky = $('#sticky-footer');
				if (sticky.hasClass('d-none')) {
					sticky.removeClass('d-none');
					setTimeout(() => { sticky.addClass('show'); }, 10);
					$('body').css('padding-bottom', '99px');
				}

				let label = $(this).next('label');
				let name = label.find('.product-name').text().trim();


				currentProductPrice = currentPrice;

				$('#sticky-product').text(name);

				// Update Sticky & Show
				updateStickyCalculation();
				// ... (Kode sticky footer Anda yang lama tetap disini) ...
				// Trigger change pembayaran agar sticky footer juga ke-update angkanya
				$('input[name="payment_method"]:checked').trigger('change');
				$('#btn-virtual-account').attr('disabled', false)
				$('.btn-check').removeAttr('disabled');
			});

			$('input[name="payment_method"]').change(function() {
				let method = $(this).val();
				let methodName = method; // Mapping nama method jika perlu
				let $input = $(this)
				let feeFlat = parseFloat($input.data('fee-flat')) || 0;
				let feePercent = parseFloat($input.data('fee-percent')) || 0;

				let totalFee = feeFlat;

				if (feePercent > 0) totalFee += Math.ceil(currentProductPrice * (feePercent / 100)); // Pembulatan ke atas

				// NOTE: Jika fee berupa persen (misal QRIS 0.7%), Anda harus hitung manual disini:
				// if(method == 'QRIS') feeRaw = currentProductPrice * 0.007;

				currentAdminFee = totalFee;

				$('#sticky-payment').text(methodName.replace(/_/g, ' '));

				// Recalculate Total
				updateStickyCalculation();
			});

			// 3. TOMBOL KONFIRMASI (Memicu logika submit yang sudah ada)
			$('#sticky-btn-submit').click(async function() {
				// return
				// Efek Loading
				const originalText = $(this).text();
				$(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

				// const payload = new FormData($('#form-order-prabayar')[0])
				const payload = $('#form-order-prabayar').serialize()
				// $(this).attr('disabled', false).html(currentText)

				try {
					const {status, data: {data}, data: {meta}} = await postRequest("{{ route('checkout.store') }}", payload);
					console.log(meta);
					console.log(data);

					if (status !== 200) {
						await $swal.warning({
							text: meta?.message,
							hideClass: module.var_swal.fadeOutUp,
						})

						return $(this).attr('disabled', false).html(originalText)
					}

					// // Pastikan route checkout.store benar
					// const {status, data} = await postRequest("{{ route('checkout.store') }}", payload);

					// if (status !== 200 || !data?.meta || data.meta.code !== 200) {
					// 	await $swal.fire({
					// 		icon: 'warning',
					// 		title: 'Gagal',
					// 		text: data?.meta?.message || 'Terjadi kesalahan sistem',
					// 	});
					// } else {
					// 	// Redirect Sukses
					// 	window.location.href = data.data.redirect_url;
					// 	return; // Stop disini agar loading tidak hilang
					// }
				} catch (error) {
					console.error(error);
					await $swal.fire({ text: 'Terjadi kesalahan jaringan' });
				}

				// Reset tombol jika gagal
				$(this).attr('disabled', false).text(originalText);
			});
		})

		function updateAllPaymentChannels(productPrice) {
			// Loop semua input radio pembayaran
			$('input[name="payment_method"]').each(function() {
				let $input = $(this);
				let $label = $input.next('label');
				let $priceDisplay = $label.find('.payment-price-display');

				// 1. Ambil Fee Config dari atribut HTML
				let feeFlat = parseFloat($input.data('fee-flat')) || 0;
				let feePercent = parseFloat($input.data('fee-percent')) || 0;

				// 2. Hitung Total untuk channel ini
				let totalFee = feeFlat;

				if (feePercent > 0) {
					// Rumus Persen: (Harga Produk * Persen) / 100
					totalFee += Math.ceil(productPrice * (feePercent / 100));
				}

				let finalPrice = productPrice + totalFee;

				// console.log($formatter.formatRupiah(finalPrice));

				// 3. Update Teks di HTML
				// Jika produk belum dipilih (harga 0), tampilkan fee-nya saja atau "-"
				if (productPrice > 0) {
					$priceDisplay.text($formatter.formatRupiah(finalPrice));

					if ($priceDisplay.hasClass('text-danger')) {
						$priceDisplay
						.removeClass('text-danger')
						.addClass('text-white')
						.addClass('was-danger'); // <--- KUNCI: Tandai bahwa dia dulunya merah
					}
					// (Opsional) Highlight jika ini Best Price (Fee terendah)
					// Logic tambahan bisa ditaruh disini
				} else {
					$priceDisplay.text('Rp -');
				}
			});
		}

		// $('#order-button').click(async function (e) {
		// 	const currentText = $(this).text()
		// 	$(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...')

		// 	// const payload = new FormData($('#form-order-prabayar')[0])
		// 	const payload = $('#form-order-prabayar').serialize()
		// 	const {status, data: {data}, data: {meta}} = await postRequest("{{ route('checkout.store') }}", payload);

		// 	if (status !== 200) {
		// 		await $swal.warning({
		// 			text: meta?.message,
		// 			hideClass: module.var_swal.fadeOutUp,
		// 		})

		// 		return $(this).attr('disabled', false).html(currentText)
		// 	}
		// 	console.log(meta);
		// 	console.log(data);
		// 	$(this).attr('disabled', false).html(currentText)
		// })
	</script>
@endpush
