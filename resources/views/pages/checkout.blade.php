@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mt-4 mb-5">
	<div class="row justify-content-center">
		<div class="col-lg-8">

			<h4 class="fw-bold mb-4"><i class="fas fa-wallet me-2 text-primary"></i> Checkout Pembayaran</h4>

			<form action="{{ route('payment.process') }}" method="POST" id="checkoutForm">
				@csrf
				<input type="hidden" name="product_sku" value="{{ $product->buyer_sku_code }}">
				<input type="hidden" name="target" value="{{ $target }}">

				{{-- DETAIL PESANAN --}}
				<div class="glass-card shadow-sm p-4 mb-4">
					<h5 class="fw-bold mb-3 border-bottom pb-2">Rincian Pesanan</h5>
					<div class="d-flex justify-content-between mb-2">
						<span class="text-muted">Produk</span>
						<span class="fw-bold">{{ $product->product_name }}</span>
					</div>
					<div class="d-flex justify-content-between mb-2">
						<span class="text-muted">Tujuan ID</span>
						<span class="fw-bold text-break">{{ $target }}</span>
					</div>
					<div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
						<span class="fw-bold fs-5">Total Bayar</span>
						<span class="fw-bold fs-4 text-primary">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
					</div>
				</div>

				{{-- PILIH METODE PEMBAYARAN --}}
				<div class="glass-card shadow-sm p-4 mb-4">
					<h5 class="fw-bold mb-3">Pilih Metode Pembayaran</h5>

					<div class="accordion" id="paymentAccordion">
						{{-- QRIS --}}
						<div class="accordion-item border-0 mb-2">
							<h2 class="accordion-header">
								<button class="accordion-button glass-card fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#qrisCollapse" aria-expanded="true">
									<i class="fas fa-qrcode me-2 text-success"></i> QRIS (All Payment)
								</button>
							</h2>
							<div id="qrisCollapse" class="accordion-collapse collapse show" data-bs-parent="#paymentAccordion">
								<div class="accordion-body bg-transparent px-0">
									<div class="payment-option">
										<input type="radio" class="btn-check" name="payment_method" id="pay_SP" value="SP" required>
										<label class="btn btn-outline-light w-100 d-flex align-items-center justify-content-between p-3 payment-label" for="pay_SP">
											<div class="d-flex align-items-center gap-3">
												<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/1200px-QRIS_logo.svg.png" height="25" alt="QRIS">
												<span class="fw-bold">QRIS Instant</span>
											</div>
											<i class="fas fa-check-circle text-primary fs-5 opacity-0 check-icon"></i>
										</label>
									</div>
								</div>
							</div>
						</div>

						{{-- VIRTUAL ACCOUNT --}}
						<div class="accordion-item border-0">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed glass-card fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#vaCollapse">
									<i class="fas fa-university me-2 text-primary"></i> Virtual Account
								</button>
							</h2>
							<div id="vaCollapse" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
								<div class="accordion-body bg-transparent px-0">
									<div class="row g-2">
										{{-- BCA VA --}}
										<div class="col-12">
											<input type="radio" class="btn-check" name="payment_method" id="pay_BC" value="BC">
											<label class="btn btn-outline-light w-100 d-flex align-items-center justify-content-between p-3 payment-label" for="pay_BC">
												<div class="d-flex align-items-center gap-3">
													<img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" height="20" alt="BCA">
													<span class="fw-bold">BCA Virtual Account</span>
												</div>
												<i class="fas fa-check-circle text-primary fs-5 opacity-0 check-icon"></i>
											</label>
										</div>
										{{-- MANDIRI VA --}}
										<div class="col-12">
											<input type="radio" class="btn-check" name="payment_method" id="pay_M2" value="M2">
											<label class="btn btn-outline-light w-100 d-flex align-items-center justify-content-between p-3 payment-label" for="pay_M2">
												<div class="d-flex align-items-center gap-3">
													<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" height="20" alt="Mandiri">
													<span class="fw-bold">Mandiri Virtual Account</span>
												</div>
												<i class="fas fa-check-circle text-primary fs-5 opacity-0 check-icon"></i>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				{{-- TOMBOL KONFIRMASI --}}
				<button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5 rounded-pill shadow hover-scale" id="btnPay">
					<i class="fas fa-lock me-2"></i> Bayar Sekarang
				</button>
			</form>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
	/* Styling Payment Label agar mirip Card Produk */
	.payment-label {
		background-color: var(--bg-body);
		border: 1px solid var(--card-border);
		color: var(--text-main);
		transition: all 0.2s;
		border-radius: 12px;
	}
	.payment-label:hover {
		border-color: var(--secondary-color);
		transform: translateY(-2px);
	}
	.btn-check:checked + .payment-label {
		background-color: rgba(79, 70, 229, 0.05);
		border-color: var(--primary-color) !important;
		box-shadow: 0 0 0 1px var(--primary-color) inset;
	}
	.btn-check:checked + .payment-label .check-icon {
		opacity: 1 !important;
	}

	/* Accordion Style override */
	.accordion-button {
		background-color: var(--card-bg) !important;
		color: var(--text-main) !important;
		border: 1px solid var(--card-border);
		border-radius: 12px !important;
	}
	.accordion-button:not(.collapsed) {
		background-color: rgba(79, 70, 229, 0.1) !important;
		color: var(--primary-color) !important;
		box-shadow: none;
	}
</style>
@endpush
