@extends('layouts.front')

@php
	$appName = config('app.name');
	$contactUrl = Route::has('pages.contact') ? route('pages.contact') : url('/contact');
@endphp

@section('title', "Syarat & Ketentuan - $appName")

@push('styles')
<style>
	/* =========================================================
	   TERMS PAGE - CLOUD NEST / GAMESQUAD THEME
	   ========================================================= */

	.cn-policy-page {
		position: relative;
		min-height: 100vh;
		padding: 52px 0 90px;
		color: #ffffff;
		overflow: hidden;
	}

	.cn-policy-page::before {
		content: '';
		position: absolute;
		inset: 0;
		background:
			radial-gradient(circle at 18% 12%, rgba(0, 198, 255, .18), transparent 28%),
			radial-gradient(circle at 82% 18%, rgba(255, 116, 24, .16), transparent 26%),
			linear-gradient(180deg, #083f86 0%, #062f67 42%, #05285a 100%);
		z-index: -3;
	}

	.cn-policy-page::after {
		content: '';
		position: absolute;
		left: 0;
		right: 0;
		top: 220px;
		height: 2px;
		background: linear-gradient(90deg, transparent, rgba(255, 62, 62, .95), transparent);
		box-shadow: 0 0 16px rgba(255, 62, 62, .38);
		opacity: .75;
		z-index: -2;
	}

	.cn-policy-wrap {
		position: relative;
		z-index: 2;
		max-width: 1080px;
		margin: 0 auto;
	}

	.cn-policy-hero {
		position: relative;
		border-radius: 26px;
		padding: 34px;
		overflow: hidden;
		background:
			linear-gradient(135deg, rgba(8, 86, 171, .94), rgba(5, 48, 103, .96)),
			radial-gradient(circle at top right, rgba(255, 117, 24, .24), transparent 34%);
		border: 1px solid rgba(141, 205, 255, .24);
		box-shadow:
			0 24px 70px rgba(0, 0, 0, .24),
			inset 0 1px 0 rgba(255, 255, 255, .12);
	}

	.cn-policy-hero::before {
		content: '';
		position: absolute;
		width: 280px;
		height: 280px;
		right: -90px;
		top: -90px;
		background: rgba(255, 116, 24, .18);
		filter: blur(20px);
		border-radius: 999px;
	}

	.cn-policy-hero::after {
		content: '';
		position: absolute;
		width: 160px;
		height: 160px;
		left: -58px;
		bottom: -65px;
		background: rgba(0, 198, 255, .18);
		filter: blur(18px);
		border-radius: 999px;
	}

	.cn-hero-content {
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
		font-weight: 800;
		letter-spacing: .04em;
		text-transform: uppercase;
		margin-bottom: 14px;
	}

	.cn-policy-title {
		margin: 0;
		font-size: clamp(2rem, 4vw, 3.3rem);
		font-weight: 900;
		letter-spacing: -.045em;
		line-height: 1.04;
		color: #ffffff;
		text-shadow: 0 4px 16px rgba(0, 0, 0, .22);
	}

	.cn-policy-title span {
		color: #ff8a1f;
	}

	.cn-policy-subtitle {
		max-width: 720px;
		margin: 16px 0 0;
		color: rgba(255, 255, 255, .78);
		font-size: 1rem;
		line-height: 1.8;
	}

	.cn-policy-badge {
		width: 112px;
		height: 112px;
		border-radius: 28px;
		display: flex;
		align-items: center;
		justify-content: center;
		background:
			linear-gradient(145deg, rgba(255, 255, 255, .16), rgba(255, 255, 255, .06));
		border: 1px solid rgba(255, 255, 255, .2);
		box-shadow:
			0 16px 42px rgba(0, 0, 0, .22),
			inset 0 1px 0 rgba(255, 255, 255, .16);
	}

	.cn-policy-badge i {
		font-size: 3rem;
		color: #ff8a1f;
		filter: drop-shadow(0 8px 14px rgba(255, 138, 31, .25));
	}

	.cn-policy-meta {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		margin-top: 22px;
	}

	.cn-meta-pill {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 9px 12px;
		border-radius: 999px;
		background: rgba(255, 255, 255, .09);
		border: 1px solid rgba(255, 255, 255, .14);
		color: rgba(255, 255, 255, .82);
		font-size: .82rem;
		font-weight: 700;
	}

	.cn-policy-nav {
		position: sticky;
		top: 92px;
		z-index: 10;
		margin: 22px 0;
		padding: 12px;
		border-radius: 18px;
		background: rgba(5, 39, 88, .82);
		border: 1px solid rgba(141, 205, 255, .16);
		backdrop-filter: blur(16px);
		box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
		display: flex;
		gap: 10px;
		overflow-x: auto;
		scrollbar-width: none;
	}

	.cn-policy-nav::-webkit-scrollbar {
		display: none;
	}

	.cn-policy-nav a {
		flex: 0 0 auto;
		text-decoration: none;
		color: rgba(255, 255, 255, .82);
		border: 1px solid rgba(255, 255, 255, .18);
		background: rgba(255, 255, 255, .06);
		border-radius: 12px;
		padding: 10px 14px;
		font-size: .86rem;
		font-weight: 800;
		transition: all .22s ease;
	}

	.cn-policy-nav a:hover {
		color: #ffffff;
		border-color: rgba(255, 138, 31, .65);
		background: rgba(255, 138, 31, .16);
		transform: translateY(-1px);
	}

	.cn-policy-card {
		border-radius: 26px;
		background: rgba(4, 41, 92, .86);
		border: 1px solid rgba(141, 205, 255, .18);
		box-shadow: 0 26px 70px rgba(0, 0, 0, .22);
		overflow: hidden;
		backdrop-filter: blur(14px);
	}

	.cn-policy-content {
		padding: 30px;
	}

	.cn-alert-intro {
		display: grid;
		grid-template-columns: auto 1fr;
		gap: 15px;
		padding: 18px;
		border-radius: 20px;
		background:
			linear-gradient(135deg, rgba(255, 138, 31, .16), rgba(255, 255, 255, .045));
		border: 1px solid rgba(255, 138, 31, .28);
		color: rgba(255, 255, 255, .88);
		line-height: 1.75;
		margin-bottom: 28px;
	}

	.cn-alert-intro i {
		width: 42px;
		height: 42px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 14px;
		background: rgba(255, 138, 31, .16);
		color: #ff8a1f;
	}

	.cn-section {
		scroll-margin-top: 170px;
		margin-top: 28px;
	}

	.cn-section:first-of-type {
		margin-top: 0;
	}

	.cn-section-heading {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-bottom: 18px;
	}

	.cn-section-number {
		width: 42px;
		height: 42px;
		flex: 0 0 auto;
		border-radius: 14px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffffff;
		font-weight: 900;
		background: linear-gradient(135deg, #ff7a18, #ff3d3d);
		box-shadow: 0 12px 22px rgba(255, 86, 40, .22);
	}

	.cn-section-title {
		margin: 0;
		color: #ffffff;
		font-size: clamp(1.25rem, 2vw, 1.65rem);
		font-weight: 900;
		letter-spacing: -.02em;
	}

	.cn-section-desc {
		color: rgba(255, 255, 255, .72);
		line-height: 1.8;
		margin-bottom: 18px;
	}

	.cn-info-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 16px;
	}

	.cn-info-card {
		height: 100%;
		border-radius: 22px;
		padding: 20px;
		background: rgba(7, 70, 141, .72);
		border: 1px solid rgba(141, 205, 255, .18);
		box-shadow: 0 16px 32px rgba(0, 0, 0, .16);
		transition: transform .22s ease, border-color .22s ease, background .22s ease;
	}

	.cn-info-card:hover {
		transform: translateY(-4px);
		border-color: rgba(255, 138, 31, .45);
		background: rgba(8, 78, 156, .86);
	}

	.cn-info-icon {
		width: 48px;
		height: 48px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 14px;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border: 1px solid rgba(255, 138, 31, .24);
	}

	.cn-info-card h3 {
		font-size: 1rem;
		font-weight: 900;
		color: #ffffff;
		margin-bottom: 10px;
	}

	.cn-policy-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.cn-policy-list li {
		position: relative;
		padding-left: 28px;
		color: rgba(255, 255, 255, .74);
		line-height: 1.75;
		font-size: .93rem;
		margin-bottom: 10px;
	}

	.cn-policy-list li:last-child {
		margin-bottom: 0;
	}

	.cn-policy-list li::before {
		content: '\f00c';
		font-family: 'Font Awesome 6 Free';
		font-weight: 900;
		position: absolute;
		left: 0;
		top: 2px;
		color: #39d98a;
		font-size: .86rem;
	}

	.cn-policy-list.warning li::before {
		content: '\f071';
		color: #ffb020;
	}

	.cn-policy-list.danger li::before {
		content: '\f057';
		color: #ff5b5b;
	}

	.cn-divider {
		position: relative;
		margin: 34px 0;
		height: 20px;
	}

	.cn-divider::before {
		content: '';
		position: absolute;
		left: -30px;
		right: -30px;
		top: 9px;
		height: 3px;
		background: linear-gradient(90deg, #ff3d3d, #ff7a18, #ff3d3d);
		box-shadow: 0 0 16px rgba(255, 61, 61, .34);
		clip-path: polygon(0 0, 42% 0, 45% 100%, 55% 100%, 58% 0, 100% 0, 100% 100%, 0 100%);
	}

	.cn-refund-note {
		display: grid;
		grid-template-columns: auto 1fr;
		gap: 16px;
		padding: 20px;
		border-radius: 22px;
		background: rgba(255, 176, 32, .12);
		border: 1px dashed rgba(255, 176, 32, .42);
		margin-bottom: 18px;
	}

	.cn-refund-note .cn-note-icon {
		width: 48px;
		height: 48px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffb020;
		background: rgba(255, 176, 32, .12);
		border: 1px solid rgba(255, 176, 32, .28);
	}

	.cn-refund-note h3 {
		margin: 0 0 8px;
		color: #ffb020;
		font-size: 1.05rem;
		font-weight: 900;
	}

	.cn-refund-note p {
		margin: 0;
		color: rgba(255, 255, 255, .76);
		line-height: 1.75;
		font-size: .94rem;
	}

	.cn-refund-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 16px;
	}

	.cn-refund-card {
		position: relative;
		overflow: hidden;
		border-radius: 22px;
		padding: 20px;
		min-height: 100%;
		border: 1px solid rgba(255, 255, 255, .12);
		background: rgba(255, 255, 255, .055);
	}

	.cn-refund-card.accepted {
		border-color: rgba(57, 217, 138, .28);
		background: rgba(57, 217, 138, .09);
	}

	.cn-refund-card.rejected {
		border-color: rgba(255, 91, 91, .30);
		background: rgba(255, 91, 91, .09);
	}

	.cn-refund-card h3 {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 14px;
		font-size: 1.05rem;
		font-weight: 900;
		color: #ffffff;
	}

	.cn-refund-card.accepted h3 i {
		color: #39d98a;
	}

	.cn-refund-card.rejected h3 i {
		color: #ff5b5b;
	}

	.cn-steps {
		counter-reset: complain-step;
		list-style: none;
		padding: 0;
		margin: 0;
		display: grid;
		gap: 12px;
	}

	.cn-steps li {
		counter-increment: complain-step;
		/* display: grid; */
		display: flex;
		grid-template-columns: auto 1fr;
		gap: 12px;
		align-items: start;
		padding: 14px;
		border-radius: 18px;
		background: rgba(7, 70, 141, .58);
		border: 1px solid rgba(141, 205, 255, .14);
		color: rgba(255, 255, 255, .76);
		line-height: 1.65;
	}

	.cn-steps li::before {
		content: counter(complain-step);
		width: 30px;
		height: 30px;
		border-radius: 11px;
		display: flex;
		align-items: center;
		justify-content: center;
		background: rgba(255, 138, 31, .16);
		border: 1px solid rgba(255, 138, 31, .28);
		color: #ff8a1f;
		font-weight: 900;
		font-size: .86rem;
	}

	.cn-contact-box {
		margin-top: 24px;
		padding: 20px;
		border-radius: 22px;
		background:
			linear-gradient(135deg, rgba(255, 138, 31, .16), rgba(0, 198, 255, .08));
		border: 1px solid rgba(255, 255, 255, .14);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 18px;
	}

	.cn-contact-box h3 {
		margin: 0 0 4px;
		font-size: 1.05rem;
		color: #ffffff;
		font-weight: 900;
	}

	.cn-contact-box p {
		margin: 0;
		color: rgba(255, 255, 255, .72);
		font-size: .92rem;
		line-height: 1.6;
	}

	.cn-contact-btn {
		flex: 0 0 auto;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 9px;
		padding: 12px 16px;
		border-radius: 14px;
		color: #ffffff;
		background: linear-gradient(135deg, #ff7a18, #ff3d3d);
		text-decoration: none;
		font-weight: 900;
		box-shadow: 0 14px 28px rgba(255, 86, 40, .22);
		transition: transform .22s ease, box-shadow .22s ease;
	}

	.cn-contact-btn:hover {
		color: #ffffff;
		transform: translateY(-2px);
		box-shadow: 0 18px 34px rgba(255, 86, 40, .30);
	}

	@media (max-width: 991px) {
		.cn-policy-page {
			padding-top: 32px;
		}

		.cn-hero-content {
			grid-template-columns: 1fr;
		}

		.cn-policy-badge {
			width: 86px;
			height: 86px;
			border-radius: 22px;
		}

		.cn-policy-badge i {
			font-size: 2.25rem;
		}

		.cn-info-grid {
			grid-template-columns: 1fr;
		}

		.cn-refund-grid {
			grid-template-columns: 1fr;
		}

		.cn-policy-nav {
			top: 76px;
		}
	}

	@media (max-width: 576px) {
		.cn-policy-page {
			padding: 24px 0 64px;
		}

		.cn-policy-hero {
			border-radius: 20px;
			padding: 24px 20px;
		}

		.cn-policy-content {
			padding: 18px;
		}

		.cn-policy-card {
			border-radius: 20px;
		}

		.cn-alert-intro,
		.cn-refund-note {
			grid-template-columns: 1fr;
		}

		.cn-contact-box {
			align-items: stretch;
			flex-direction: column;
		}

		.cn-contact-btn {
			width: 100%;
		}

		.cn-divider::before {
			left: -18px;
			right: -18px;
		}
	}
</style>
@endpush

@section('content')
<section class="cn-policy-page">
	<div class="container">
		<div class="cn-policy-wrap">

			{{-- HERO --}}
			<div class="cn-policy-hero">
				<div class="cn-hero-content">
					<div>
						<div class="cn-eyebrow">
							<i class="fa-solid fa-shield-halved"></i>
							Legal Information
						</div>

						<h1 class="cn-policy-title">
							Syarat & <span>Ketentuan</span>
						</h1>

						<p class="cn-policy-subtitle">
							Harap membaca kebijakan ini dengan saksama sebelum melakukan transaksi di
							<strong>{{ $appName }}</strong>. Dengan menggunakan layanan kami, Anda dianggap sudah memahami dan menyetujui aturan yang berlaku.
						</p>

						<div class="cn-policy-meta">
							<span class="cn-meta-pill">
								<i class="fa-regular fa-calendar-check"></i>
								Update: {{ date('d F Y') }}
							</span>
							<span class="cn-meta-pill">
								<i class="fa-solid fa-gamepad"></i>
								Produk Digital
							</span>
							<span class="cn-meta-pill">
								<i class="fa-solid fa-bolt"></i>
								Transaksi Otomatis
							</span>
						</div>
					</div>

					<div class="cn-policy-badge">
						<i class="fa-solid fa-file-contract"></i>
					</div>
				</div>
			</div>

			{{-- QUICK NAV --}}
			<nav class="cn-policy-nav" aria-label="Navigasi syarat ketentuan">
				<a href="#syarat-penggunaan">
					<i class="fa-solid fa-circle-info me-1"></i>
					Syarat Penggunaan
				</a>
				<a href="#produk-digital">
					<i class="fa-solid fa-box-open me-1"></i>
					Produk Digital
				</a>
				<a href="#transaksi">
					<i class="fa-solid fa-cart-shopping me-1"></i>
					Transaksi
				</a>
				<a href="#refund">
					<i class="fa-solid fa-rotate-left me-1"></i>
					Refund
				</a>
				<a href="#komplain">
					<i class="fa-solid fa-headset me-1"></i>
					Komplain
				</a>
			</nav>

			{{-- CONTENT --}}
			<div class="cn-policy-card">
				<div class="cn-policy-content">

					<div class="cn-alert-intro">
						<i class="fas fa-info-circle"></i>
						<div>
							<strong>Informasi penting:</strong>
							Dengan mendaftar atau melakukan transaksi, Anda dianggap telah memahami dan menyetujui seluruh isi dalam syarat dan ketentuan di bawah ini.
						</div>
					</div>

					{{-- SECTION 1 --}}
					<section id="syarat-penggunaan" class="cn-section">
						<div class="cn-section-heading">
							<div class="cn-section-number">1</div>
							<h2 class="cn-section-title">Syarat Penggunaan</h2>
						</div>

						<p class="cn-section-desc">
							Bagian ini menjelaskan posisi layanan {{ $appName }} dan batasan tanggung jawab saat pengguna melakukan transaksi produk digital.
						</p>

						<div class="cn-info-grid">
							<div class="cn-info-card">
								<div class="cn-info-icon">
									<i class="fas fa-cube"></i>
								</div>
								<h3>Umum</h3>
								<ul class="cn-policy-list">
									<li>{{ $appName }} bertindak sebagai perantara antara pembeli dengan penyedia layanan operator atau provider.</li>
									<li>Gangguan sinyal, gangguan server operator, dan kesalahan input data oleh pengguna berada di luar kendali kami.</li>
								</ul>
							</div>

							<div id="produk-digital" class="cn-info-card">
								<div class="cn-info-icon">
									<i class="fas fa-box-open"></i>
								</div>
								<h3>Produk Digital</h3>
								<ul class="cn-policy-list">
									<li>Produk yang dijual berupa produk digital seperti pulsa, data, token, voucher game, dan layanan sejenis.</li>
									<li>Produk digital tidak memerlukan pengiriman fisik.</li>
									<li>Harga produk dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya mengikuti kebijakan provider pusat.</li>
								</ul>
							</div>

							<div id="transaksi" class="cn-info-card">
								<div class="cn-info-icon">
									<i class="fas fa-shopping-cart"></i>
								</div>
								<h3>Pemesanan & Transaksi</h3>
								<ul class="cn-policy-list warning">
									<li>Pengguna wajib memastikan data tujuan seperti nomor HP, ID game, dan server ID sudah benar.</li>
									<li>Kesalahan penulisan data tujuan yang menyebabkan produk terkirim ke orang lain menjadi tanggung jawab penuh pengguna.</li>
									<li>Transaksi yang sudah berstatus <strong>SUKSES</strong> di sistem kami tidak dapat dibatalkan atau ditarik kembali.</li>
								</ul>
							</div>
						</div>
					</section>

					<div class="cn-divider"></div>

					{{-- SECTION 2 --}}
					<section id="refund" class="cn-section">
						<div class="cn-section-heading">
							<div class="cn-section-number">2</div>
							<h2 class="cn-section-title">Kebijakan Pengembalian Dana</h2>
						</div>

						<p class="cn-section-desc">
							Kami memahami kesalahan teknis bisa terjadi. Berikut prosedur refund yang berlaku di {{ $appName }}.
						</p>

						<div class="cn-refund-note">
							<div class="cn-note-icon">
								<i class="fas fa-exclamation-triangle"></i>
							</div>
							<div>
								<h3>Catatan Refund</h3>
								<p>
									<strong>PENTING:</strong> Pengembalian dana hanya akan diproses ke
									<strong>saldo member</strong> website, bukan ke rekening bank atau e-wallet pribadi.
									Nominal refund mengikuti harga produk dan tidak termasuk biaya admin/payment gateway yang sudah terpotong.
								</p>
							</div>
						</div>

						<div class="cn-refund-grid">
							<div class="cn-refund-card accepted">
								<h3>
									<i class="fas fa-check-circle"></i>
									Refund Diterima
								</h3>
								<ul class="cn-policy-list">
									<li><strong>Stok kosong:</strong> Produk sedang gangguan atau habis dari pusat.</li>
									<li><strong>Transaksi gagal:</strong> Status gagal tetapi saldo sudah terpotong.</li>
									<li><strong>Error sistem:</strong> Bug sistem yang menyebabkan nominal atau status transaksi tidak sesuai.</li>
								</ul>
							</div>

							<div class="cn-refund-card rejected">
								<h3>
									<i class="fas fa-times-circle"></i>
									Refund Ditolak
								</h3>
								<ul class="cn-policy-list danger">
									<li><strong>Kesalahan data:</strong> Salah ketik ID game, nomor HP, atau server tujuan.</li>
									<li><strong>Kelalaian pengguna:</strong> Tidak teliti dalam memilih produk atau spesifikasi layanan.</li>
									<li><strong>Produk sukses:</strong> SN, serial number, atau bukti top up sudah terbit valid.</li>
								</ul>
							</div>
						</div>
					</section>

					<div class="cn-divider"></div>

					{{-- SECTION 3 --}}
					<section id="komplain" class="cn-section">
						<div class="cn-section-heading">
							<div class="cn-section-number">3</div>
							<h2 class="cn-section-title">Cara Komplain</h2>
						</div>

						<p class="cn-section-desc">
							Jika Anda mengalami kendala yang memenuhi syarat refund, silakan lakukan komplain dengan mengikuti langkah berikut.
						</p>

						<ol class="cn-steps">
							<li>
								Komplain maksimal <strong>1x24 jam</strong> setelah transaksi dilakukan.
							</li>
							<li>
								Hubungi Customer Service melalui WhatsApp di <strong>0895-3208-94991.</strong>
							</li>
							<li>
								Sertakan screenshot bukti transfer, nomor invoice, dan detail kendala transaksi.
							</li>
							<li>
								Proses pengecekan membutuhkan estimasi <strong>1-3 hari kerja,</strong> tergantung antrian dan respons provider.
							</li>
						</ol>

						<div class="cn-contact-box">
							<div>
								<h3>Butuh bantuan transaksi?</h3>
								<p>
									Hubungi tim support kami jika ada transaksi yang bermasalah atau butuh pengecekan lebih lanjut.
								</p>
							</div>

							<a href="{{ $contactUrl }}" class="cn-contact-btn">
								<i class="fa-solid fa-headset"></i>
								Hubungi CS
							</a>
						</div>
					</section>

				</div>
			</div>

		</div>
	</div>
</section>
@endsection
