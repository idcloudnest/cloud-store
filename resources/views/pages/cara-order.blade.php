@extends('layouts.front')

@php
	$appName = config('app.name');

	$homeUrl = Route::has('pages.home') ? route('pages.home') : url('/');
	$invoiceUrl = Route::has('pages.invoices') ? route('pages.invoices') : url('/invoices');
	$contactUrl = Route::has('pages.contact') ? route('pages.contact') : url('/contact');
@endphp

@section('title', "Cara Order - $appName")

@push('styles')
<style>
	/* =========================================================
	   CARA ORDER PAGE - CLOUD NEST / GAMESQUAD THEME
	   ========================================================= */

	.cn-order-page {
		position: relative;
		min-height: 100vh;
		padding: 52px 0 88px;
		color: #ffffff;
		overflow: hidden;
	}

	.cn-order-page::before {
		content: '';
		position: absolute;
		inset: 0;
		background:
			radial-gradient(circle at 18% 10%, rgba(0, 198, 255, .18), transparent 28%),
			radial-gradient(circle at 84% 18%, rgba(255, 122, 24, .16), transparent 26%),
			linear-gradient(180deg, #083f86 0%, #062f67 45%, #05285a 100%);
		z-index: -3;
	}

	.cn-order-page::after {
		content: '';
		position: absolute;
		left: 0;
		right: 0;
		top: 245px;
		height: 3px;
		background: linear-gradient(90deg, transparent, #ff3d3d, #ff7a18, #ff3d3d, transparent);
		box-shadow: 0 0 18px rgba(255, 61, 61, .38);
		opacity: .78;
		z-index: -2;
	}

	.cn-order-wrap {
		position: relative;
		z-index: 2;
		max-width: 1120px;
		margin: 0 auto;
	}

	/* HERO */
	.cn-order-hero {
		position: relative;
		overflow: hidden;
		border-radius: 28px;
		padding: 34px;
		background:
			linear-gradient(135deg, rgba(8, 86, 171, .94), rgba(5, 48, 103, .96)),
			radial-gradient(circle at top right, rgba(255, 122, 24, .22), transparent 36%);
		border: 1px solid rgba(141, 205, 255, .24);
		box-shadow:
			0 24px 70px rgba(0, 0, 0, .25),
			inset 0 1px 0 rgba(255, 255, 255, .12);
	}

	.cn-order-hero::before {
		content: '';
		position: absolute;
		width: 280px;
		height: 280px;
		top: -90px;
		right: -90px;
		border-radius: 999px;
		background: rgba(255, 122, 24, .18);
		filter: blur(20px);
	}

	.cn-order-hero::after {
		content: '';
		position: absolute;
		width: 180px;
		height: 180px;
		left: -65px;
		bottom: -70px;
		border-radius: 999px;
		background: rgba(0, 198, 255, .18);
		filter: blur(18px);
	}

	.cn-order-hero-content {
		position: relative;
		z-index: 2;
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 24px;
		align-items: center;
	}

	.cn-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		width: fit-content;
		padding: 8px 12px;
		border-radius: 999px;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border: 1px solid rgba(255, 138, 31, .26);
		font-size: .78rem;
		font-weight: 900;
		letter-spacing: .04em;
		text-transform: uppercase;
		margin-bottom: 14px;
	}

	.cn-order-title {
		margin: 0;
		color: #ffffff;
		font-size: clamp(2rem, 4vw, 3.35rem);
		font-weight: 950;
		letter-spacing: -.045em;
		line-height: 1.04;
		text-shadow: 0 4px 16px rgba(0, 0, 0, .22);
	}

	.cn-order-title span {
		color: #ff8a1f;
	}

	.cn-order-subtitle {
		max-width: 740px;
		margin: 16px 0 0;
		color: rgba(255, 255, 255, .78);
		font-size: 1rem;
		line-height: 1.8;
	}

	.cn-order-badge {
		width: 116px;
		height: 116px;
		border-radius: 30px;
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(145deg, rgba(255, 255, 255, .16), rgba(255, 255, 255, .06));
		border: 1px solid rgba(255, 255, 255, .2);
		box-shadow:
			0 16px 42px rgba(0, 0, 0, .22),
			inset 0 1px 0 rgba(255, 255, 255, .16);
	}

	.cn-order-badge i {
		font-size: 3rem;
		color: #ff8a1f;
		filter: drop-shadow(0 8px 14px rgba(255, 138, 31, .25));
	}

	.cn-order-actions {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		margin-top: 24px;
	}

	.cn-btn-primary,
	.cn-btn-secondary {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 9px;
		min-height: 44px;
		padding: 11px 16px;
		border-radius: 14px;
		text-decoration: none;
		font-weight: 900;
		font-size: .92rem;
		transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, background .22s ease;
	}

	.cn-btn-primary {
		color: #ffffff;
		background: linear-gradient(135deg, #ff7a18, #ff3d3d);
		box-shadow: 0 14px 28px rgba(255, 86, 40, .22);
	}

	.cn-btn-primary:hover {
		color: #ffffff;
		transform: translateY(-2px);
		box-shadow: 0 18px 36px rgba(255, 86, 40, .32);
	}

	.cn-btn-secondary {
		color: rgba(255, 255, 255, .86);
		background: rgba(255, 255, 255, .08);
		border: 1px solid rgba(255, 255, 255, .16);
	}

	.cn-btn-secondary:hover {
		color: #ffffff;
		background: rgba(255, 255, 255, .12);
		border-color: rgba(255, 138, 31, .45);
		transform: translateY(-2px);
	}

	/* SUMMARY STRIP */
	.cn-summary-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 14px;
		margin: 20px 0;
	}

	.cn-summary-card {
		display: flex;
		align-items: center;
		gap: 14px;
		padding: 16px;
		border-radius: 20px;
		background: rgba(5, 39, 88, .82);
		border: 1px solid rgba(141, 205, 255, .16);
		box-shadow: 0 14px 34px rgba(0, 0, 0, .16);
		backdrop-filter: blur(14px);
	}

	.cn-summary-icon {
		width: 46px;
		height: 46px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex: 0 0 auto;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .13);
		border: 1px solid rgba(255, 138, 31, .24);
	}

	.cn-summary-card strong {
		display: block;
		color: #ffffff;
		font-size: .95rem;
		font-weight: 900;
		margin-bottom: 2px;
	}

	.cn-summary-card span {
		color: rgba(255, 255, 255, .66);
		font-size: .8rem;
		line-height: 1.45;
	}

	/* SECTION TITLE */
	.cn-section-head {
		display: flex;
		align-items: end;
		justify-content: space-between;
		gap: 18px;
		margin: 36px 0 18px;
	}

	.cn-section-kicker {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		color: #ff8a1f;
		font-size: .8rem;
		font-weight: 900;
		text-transform: uppercase;
		letter-spacing: .04em;
		margin-bottom: 7px;
	}

	.cn-section-title {
		margin: 0;
		color: #ffffff;
		font-size: clamp(1.45rem, 2.3vw, 2rem);
		font-weight: 950;
		letter-spacing: -.03em;
	}

	.cn-section-desc {
		max-width: 600px;
		margin: 8px 0 0;
		color: rgba(255, 255, 255, .68);
		line-height: 1.7;
		font-size: .94rem;
	}

	/* STEPS */
	.cn-steps-wrap {
		position: relative;
	}

	.cn-steps-line {
		position: absolute;
		left: 8%;
		right: 8%;
		top: 84px;
		height: 3px;
		background: linear-gradient(90deg, rgba(255, 122, 24, .1), rgba(255, 122, 24, .8), rgba(255, 61, 61, .8), rgba(255, 122, 24, .1));
		box-shadow: 0 0 18px rgba(255, 122, 24, .25);
		z-index: 0;
	}

	.cn-step-card {
		position: relative;
		z-index: 1;
		height: 100%;
		padding: 22px;
		border-radius: 24px;
		background: rgba(7, 70, 141, .76);
		border: 1px solid rgba(141, 205, 255, .18);
		box-shadow: 0 18px 42px rgba(0, 0, 0, .18);
		transition: transform .22s ease, border-color .22s ease, background .22s ease;
		overflow: hidden;
	}

	.cn-step-card::before {
		content: '';
		position: absolute;
		inset: 0;
		background:
			radial-gradient(circle at top right, rgba(255, 138, 31, .13), transparent 34%),
			linear-gradient(180deg, rgba(255, 255, 255, .055), transparent);
		opacity: 0;
		transition: opacity .22s ease;
		pointer-events: none;
	}

	.cn-step-card:hover {
		transform: translateY(-7px);
		border-color: rgba(255, 138, 31, .52);
		background: rgba(8, 78, 156, .88);
	}

	.cn-step-card:hover::before {
		opacity: 1;
	}

	.cn-step-top {
		position: relative;
		z-index: 2;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		margin-bottom: 18px;
	}

	.cn-step-number {
		width: 44px;
		height: 44px;
		border-radius: 15px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffffff;
		font-weight: 950;
		background: linear-gradient(135deg, #ff7a18, #ff3d3d);
		box-shadow: 0 12px 22px rgba(255, 86, 40, .22);
	}

	.cn-step-icon {
		width: 52px;
		height: 52px;
		border-radius: 18px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border: 1px solid rgba(255, 138, 31, .24);
		font-size: 1.35rem;
	}

	.cn-step-card h3 {
		position: relative;
		z-index: 2;
		color: #ffffff;
		font-size: 1.05rem;
		font-weight: 950;
		margin-bottom: 10px;
	}

	.cn-step-card p {
		position: relative;
		z-index: 2;
		color: rgba(255, 255, 255, .72);
		font-size: .9rem;
		line-height: 1.7;
		margin: 0;
	}

	/* INFO / TIPS */
	.cn-tips-card {
		margin-top: 22px;
		padding: 20px;
		border-radius: 24px;
		background:
			linear-gradient(135deg, rgba(255, 138, 31, .15), rgba(0, 198, 255, .07));
		border: 1px solid rgba(255, 255, 255, .14);
		box-shadow: 0 18px 42px rgba(0, 0, 0, .16);
		display: grid;
		grid-template-columns: auto 1fr;
		gap: 16px;
		align-items: start;
	}

	.cn-tips-icon {
		width: 50px;
		height: 50px;
		border-radius: 17px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffb020;
		background: rgba(255, 176, 32, .13);
		border: 1px solid rgba(255, 176, 32, .26);
		font-size: 1.2rem;
	}

	.cn-tips-card h3 {
		color: #ffffff;
		font-size: 1.05rem;
		font-weight: 950;
		margin: 0 0 6px;
	}

	.cn-tips-card p {
		color: rgba(255, 255, 255, .72);
		line-height: 1.75;
		margin: 0;
		font-size: .92rem;
	}

	/* FAQ */
	.cn-faq-card {
		border-radius: 26px;
		background: rgba(4, 41, 92, .86);
		border: 1px solid rgba(141, 205, 255, .18);
		box-shadow: 0 26px 70px rgba(0, 0, 0, .22);
		overflow: hidden;
		backdrop-filter: blur(14px);
	}

	.cn-faq-header {
		padding: 22px 24px;
		border-bottom: 1px solid rgba(141, 205, 255, .16);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
	}

	.cn-faq-header h2 {
		margin: 0;
		color: #ffffff;
		font-size: 1.25rem;
		font-weight: 950;
		letter-spacing: -.02em;
	}

	.cn-faq-header span {
		color: rgba(255, 255, 255, .62);
		font-size: .86rem;
	}

	.cn-faq-body {
		padding: 10px;
	}

	.cn-faq-card .accordion-item {
		background: transparent;
		border: 0;
		margin-bottom: 10px;
	}

	.cn-faq-card .accordion-item:last-child {
		margin-bottom: 0;
	}

	.cn-faq-card .accordion-button {
		border-radius: 18px !important;
		background: rgba(7, 70, 141, .58);
		border: 1px solid rgba(141, 205, 255, .14);
		color: #ffffff;
		font-weight: 900;
		box-shadow: none;
		padding: 16px 18px;
	}

	.cn-faq-card .accordion-button:not(.collapsed) {
		background: rgba(255, 138, 31, .14);
		border-color: rgba(255, 138, 31, .32);
		color: #ffffff;
	}

	.cn-faq-card .accordion-button::after {
		filter: invert(1);
		opacity: .8;
	}

	.cn-faq-card .accordion-body {
		color: rgba(255, 255, 255, .72);
		line-height: 1.75;
		font-size: .92rem;
		padding: 14px 18px 18px;
	}

	/* CTA */
	.cn-help-box {
		margin-top: 24px;
		padding: 20px;
		border-radius: 24px;
		background:
			linear-gradient(135deg, rgba(255, 138, 31, .16), rgba(0, 198, 255, .08));
		border: 1px solid rgba(255, 255, 255, .14);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 18px;
	}

	.cn-help-box h3 {
		margin: 0 0 5px;
		color: #ffffff;
		font-size: 1.05rem;
		font-weight: 950;
	}

	.cn-help-box p {
		margin: 0;
		color: rgba(255, 255, 255, .72);
		font-size: .92rem;
		line-height: 1.65;
	}

	/* ANIMATION */
	.cn-animate-up {
		animation: cnFadeUp .7s ease-out forwards;
		opacity: 0;
	}

	.cn-animate-down {
		animation: cnFadeDown .7s ease-out forwards;
		opacity: 0;
	}

	@keyframes cnFadeUp {
		from {
			opacity: 0;
			transform: translateY(26px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	@keyframes cnFadeDown {
		from {
			opacity: 0;
			transform: translateY(-22px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	@media (max-width: 991px) {
		.cn-order-page {
			padding-top: 32px;
		}

		.cn-order-hero-content {
			grid-template-columns: 1fr;
		}

		.cn-order-badge {
			width: 90px;
			height: 90px;
			border-radius: 24px;
		}

		.cn-order-badge i {
			font-size: 2.2rem;
		}

		.cn-summary-grid {
			grid-template-columns: 1fr;
		}

		.cn-steps-line {
			display: none;
		}

		.cn-section-head {
			align-items: start;
			flex-direction: column;
		}
	}

	@media (max-width: 576px) {
		.cn-order-page {
			padding: 24px 0 64px;
		}

		.cn-order-hero {
			border-radius: 22px;
			padding: 24px 20px;
		}

		.cn-order-actions {
			flex-direction: column;
		}

		.cn-btn-primary,
		.cn-btn-secondary {
			width: 100%;
		}

		.cn-step-card {
			border-radius: 20px;
			padding: 18px;
		}

		.cn-tips-card {
			grid-template-columns: 1fr;
		}

		.cn-faq-header {
			align-items: start;
			flex-direction: column;
		}

		.cn-help-box {
			align-items: stretch;
			flex-direction: column;
		}
	}
</style>
@endpush

@section('content')
<section class="cn-order-page">
	<div class="container">
		<div class="cn-order-wrap">

			{{-- HERO --}}
			<div class="cn-order-hero cn-animate-down">
				<div class="cn-order-hero-content">
					<div>
						<div class="cn-eyebrow">
							<i class="fa-solid fa-list-check"></i>
							Panduan Transaksi
						</div>

						<h1 class="cn-order-title">
							Cara <span>Order</span>
						</h1>

						<p class="cn-order-subtitle">
							Ikuti langkah mudah untuk membeli produk digital di <strong>{{ $appName }}</strong>.
							Mulai dari pilih produk, isi data tujuan, bayar, sampai transaksi diproses otomatis.
						</p>

						<div class="cn-order-actions">
							<a href="{{ $homeUrl }}" class="cn-btn-primary">
								<i class="fa-solid fa-bag-shopping"></i>
								Mulai Order
							</a>

							<a href="{{ $invoiceUrl }}" class="cn-btn-secondary">
								<i class="fa-solid fa-receipt"></i>
								Cek Riwayat Pesanan
							</a>
						</div>
					</div>

					<div class="cn-order-badge">
						<i class="fa-solid fa-cart-shopping"></i>
					</div>
				</div>
			</div>

			{{-- SUMMARY --}}
			<div class="cn-summary-grid">
				<div class="cn-summary-card cn-animate-up" style="animation-delay: 80ms;">
					<div class="cn-summary-icon">
						<i class="fa-solid fa-bolt"></i>
					</div>
					<div>
						<strong>Proses Cepat</strong>
						<span>Transaksi diproses otomatis setelah pembayaran terkonfirmasi.</span>
					</div>
				</div>

				<div class="cn-summary-card cn-animate-up" style="animation-delay: 160ms;">
					<div class="cn-summary-icon">
						<i class="fa-solid fa-shield-halved"></i>
					</div>
					<div>
						<strong>Data Aman</strong>
						<span>Pastikan data tujuan benar sebelum checkout agar transaksi lancar.</span>
					</div>
				</div>

				<div class="cn-summary-card cn-animate-up" style="animation-delay: 240ms;">
					<div class="cn-summary-icon">
						<i class="fa-solid fa-headset"></i>
					</div>
					<div>
						<strong>Support Siap Bantu</strong>
						<span>Hubungi CS jika ada kendala pada status pesanan.</span>
					</div>
				</div>
			</div>

			{{-- STEPS --}}
			@php
				$steps = [
					[
						'icon' => 'fa-magnifying-glass',
						'title' => 'Pilih Produk',
						'desc' => 'Cari dan pilih kategori produk yang ingin dibeli, seperti top up game, voucher, pulsa, data, atau layanan PPOB.'
					],
					[
						'icon' => 'fa-pen-to-square',
						'title' => 'Masukkan Data',
						'desc' => 'Isi nomor HP, ID game, server ID, nomor meter PLN, atau data tujuan lain sesuai instruksi produk.'
					],
					[
						'icon' => 'fa-wallet',
						'title' => 'Lakukan Pembayaran',
						'desc' => 'Pilih metode pembayaran yang tersedia seperti QRIS, e-wallet, virtual account, atau saldo member.'
					],
					[
						'icon' => 'fa-circle-check',
						'title' => 'Pesanan Diproses',
						'desc' => 'Setelah pembayaran terkonfirmasi, sistem akan memproses transaksi dan menampilkan bukti pesanan.'
					]
				];
			@endphp

			<div class="cn-section-head">
				<div>
					<div class="cn-section-kicker">
						<i class="fa-solid fa-route"></i>
						Step by Step
					</div>
					<h2 class="cn-section-title">Alur Transaksi</h2>
					<p class="cn-section-desc">
						Flow transaksi dibuat simpel supaya user bisa checkout tanpa bingung, khususnya saat diakses dari mobile.
					</p>
				</div>
			</div>

			<div class="cn-steps-wrap">
				<div class="cn-steps-line"></div>

				<div class="row g-4">
					@foreach($steps as $index => $step)
						<div class="col-12 col-md-6 col-lg-3 cn-animate-up" style="animation-delay: {{ 120 + ($index * 110) }}ms;">
							<div class="cn-step-card">
								<div class="cn-step-top">
									<div class="cn-step-number">
										{{ $index + 1 }}
									</div>

									<div class="cn-step-icon">
										<i class="fa-solid {{ $step['icon'] }}"></i>
									</div>
								</div>

								<h3>{{ $step['title'] }}</h3>
								<p>{{ $step['desc'] }}</p>
							</div>
						</div>
					@endforeach
				</div>
			</div>

			{{-- TIPS --}}
			<div class="cn-tips-card">
				<div class="cn-tips-icon">
					<i class="fa-solid fa-triangle-exclamation"></i>
				</div>
				<div>
					<h3>Tips sebelum checkout</h3>
					<p>
						Cek ulang nomor tujuan, ID game, server, dan nominal produk sebelum membayar.
						Transaksi yang sudah sukses biasanya tidak bisa dibatalkan karena produk digital langsung dikirim ke tujuan.
					</p>
				</div>
			</div>

			{{-- FAQ --}}
			<div class="cn-section-head">
				<div>
					<div class="cn-section-kicker">
						<i class="fa-solid fa-circle-question"></i>
						FAQ
					</div>
					<h2 class="cn-section-title">Pertanyaan Umum</h2>
					<p class="cn-section-desc">
						Beberapa pertanyaan yang sering muncul saat melakukan transaksi produk digital.
					</p>
				</div>
			</div>

			<div class="cn-faq-card">
				<div class="cn-faq-header">
					<div>
						<h2>Bantuan Transaksi</h2>
						<span>Informasi singkat seputar proses order dan refund.</span>
					</div>
					<i class="fa-solid fa-comments" style="color: #ff8a1f; font-size: 1.4rem;"></i>
				</div>

				<div class="cn-faq-body">
					<div class="accordion accordion-flush" id="faqAccordion">

						<div class="accordion-item">
							<h2 class="accordion-header" id="faqHeading1">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
									Berapa lama proses transaksi masuk?
								</button>
							</h2>
							<div id="faq1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									Rata-rata transaksi diproses instan dalam 1-60 detik setelah pembayaran terkonfirmasi.
									Jika sedang ada gangguan provider, proses bisa lebih lama dan akan dicek maksimal 1x24 jam.
								</div>
							</div>
						</div>

						<div class="accordion-item">
							<h2 class="accordion-header" id="faqHeading2">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
									Bagaimana jika transaksi gagal tapi saldo terpotong?
								</button>
							</h2>
							<div id="faq2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									Tenang, jika transaksi gagal dari sisi sistem atau operator, saldo akan dikembalikan sesuai kebijakan refund.
									Pengembalian biasanya diproses ke saldo member, bukan ke rekening bank atau e-wallet pribadi.
								</div>
							</div>
						</div>

						<div class="accordion-item">
							<h2 class="accordion-header" id="faqHeading3">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
									Apakah pesanan bisa dibatalkan setelah berhasil?
								</button>
							</h2>
							<div id="faq3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									Tidak. Produk digital yang sudah berstatus sukses dan sudah terkirim ke data tujuan tidak bisa dibatalkan.
									Karena itu, pastikan semua data sudah benar sebelum melakukan pembayaran.
								</div>
							</div>
						</div>

						<div class="accordion-item">
							<h2 class="accordion-header" id="faqHeading4">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
									Di mana saya bisa cek status pesanan?
								</button>
							</h2>
							<div id="faq4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
								<div class="accordion-body">
									Status pesanan bisa dicek melalui halaman riwayat transaksi atau lacak pesanan.
									Siapkan nomor invoice agar proses pengecekan lebih cepat.
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			{{-- HELP CTA --}}
			<div class="cn-help-box">
				<div>
					<h3>Masih bingung cara order?</h3>
					<p>
						Hubungi Customer Service kalau butuh bantuan sebelum melakukan transaksi.
					</p>
				</div>

				<a href="{{ $contactUrl }}" class="cn-btn-primary">
					<i class="fa-solid fa-headset"></i>
					Hubungi CS
				</a>
			</div>

		</div>
	</div>
</section>
@endsection
