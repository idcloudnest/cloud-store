@extends('layouts.front')

@php
	$appName = config('app.name');

	$whatsappNumber = '62895320894991';
	$whatsappLabel = '0895-3208-94991';
	$emailSupport = 'admin@idcloudnest.com';
	$telegramChannel = '@IDCloudStore';

	$homeUrl = Route::has('pages.home') ? route('pages.home') : url('/');
	$invoiceUrl = Route::has('pages.invoices') ? route('pages.invoices') : url('/invoices');
	$contactSubmitUrl = Route::has('pages.contact.submit') ? route('pages.contact.submit') : '#';

	$waMessage = rawurlencode("Halo Admin {$appName}, saya butuh bantuan terkait transaksi.");
@endphp

@section('title', "Hubungi Kami - $appName")

@push('styles')
<style>
	/* =========================================================
	   CONTACT PAGE - CLOUD NEST / GAMESQUAD THEME
	   ========================================================= */

	.cn-contact-page {
		position: relative;
		min-height: 100vh;
		padding: 52px 0 88px;
		color: #ffffff;
		overflow: hidden;
	}

	.cn-contact-page::before {
		content: '';
		position: absolute;
		inset: 0;
		background:
			radial-gradient(circle at 18% 10%, rgba(0, 198, 255, .18), transparent 28%),
			radial-gradient(circle at 86% 18%, rgba(255, 122, 24, .16), transparent 26%),
			linear-gradient(180deg, #083f86 0%, #062f67 45%, #05285a 100%);
		z-index: -3;
	}

	.cn-contact-page::after {
		content: '';
		position: absolute;
		left: 0;
		right: 0;
		top: 250px;
		height: 3px;
		background: linear-gradient(90deg, transparent, #ff3d3d, #ff7a18, #ff3d3d, transparent);
		box-shadow: 0 0 18px rgba(255, 61, 61, .38);
		opacity: .78;
		z-index: -2;
	}

	.cn-contact-wrap {
		position: relative;
		z-index: 2;
		max-width: 1120px;
		margin: 0 auto;
	}

	/* HERO */
	.cn-contact-hero {
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

	.cn-contact-hero::before {
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

	.cn-contact-hero::after {
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

	.cn-contact-hero-content {
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

	.cn-contact-title {
		margin: 0;
		color: #ffffff;
		font-size: clamp(2rem, 4vw, 3.35rem);
		font-weight: 950;
		letter-spacing: -.045em;
		line-height: 1.04;
		text-shadow: 0 4px 16px rgba(0, 0, 0, .22);
	}

	.cn-contact-title span {
		color: #ff8a1f;
	}

	.cn-contact-subtitle {
		max-width: 740px;
		margin: 16px 0 0;
		color: rgba(255, 255, 255, .78);
		font-size: 1rem;
		line-height: 1.8;
	}

	.cn-contact-badge {
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

	.cn-contact-badge i {
		font-size: 3rem;
		color: #ff8a1f;
		filter: drop-shadow(0 8px 14px rgba(255, 138, 31, .25));
	}

	.cn-contact-actions {
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
		border: 0;
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

	/* SUPPORT QUICK CARDS */
	.cn-support-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 14px;
		margin: 20px 0;
	}

	.cn-support-card {
		position: relative;
		display: flex;
		align-items: center;
		gap: 14px;
		padding: 16px;
		border-radius: 20px;
		background: rgba(5, 39, 88, .82);
		border: 1px solid rgba(141, 205, 255, .16);
		box-shadow: 0 14px 34px rgba(0, 0, 0, .16);
		backdrop-filter: blur(14px);
		text-decoration: none;
		transition: transform .22s ease, border-color .22s ease, background .22s ease;
	}

	.cn-support-card:hover {
		transform: translateY(-4px);
		border-color: rgba(255, 138, 31, .45);
		background: rgba(7, 70, 141, .86);
	}

	.cn-support-icon {
		width: 48px;
		height: 48px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex: 0 0 auto;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .13);
		border: 1px solid rgba(255, 138, 31, .24);
	}

	.cn-support-card.whatsapp .cn-support-icon {
		color: #35d56f;
		background: rgba(53, 213, 111, .12);
		border-color: rgba(53, 213, 111, .24);
	}

	.cn-support-card.telegram .cn-support-icon {
		color: #4bb6ff;
		background: rgba(75, 182, 255, .12);
		border-color: rgba(75, 182, 255, .24);
	}

	.cn-support-card.email .cn-support-icon {
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border-color: rgba(255, 138, 31, .24);
	}

	.cn-support-card small {
		display: block;
		color: rgba(255, 255, 255, .56);
		font-size: .73rem;
		text-transform: uppercase;
		font-weight: 900;
		letter-spacing: .04em;
		margin-bottom: 3px;
	}

	.cn-support-card strong {
		display: block;
		color: #ffffff;
		font-size: .93rem;
		font-weight: 950;
		line-height: 1.35;
	}

	.cn-support-card span {
		display: block;
		color: rgba(255, 255, 255, .66);
		font-size: .8rem;
		line-height: 1.45;
		margin-top: 3px;
	}

	/* MAIN GRID */
	.cn-contact-main-grid {
		display: grid;
		grid-template-columns: 0.92fr 1.08fr;
		gap: 22px;
		margin-top: 22px;
	}

	.cn-panel {
		border-radius: 26px;
		background: rgba(4, 41, 92, .86);
		border: 1px solid rgba(141, 205, 255, .18);
		box-shadow: 0 26px 70px rgba(0, 0, 0, .22);
		overflow: hidden;
		backdrop-filter: blur(14px);
	}

	.cn-panel-header {
		padding: 22px 24px;
		border-bottom: 1px solid rgba(141, 205, 255, .16);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
	}

	.cn-panel-header h2 {
		margin: 0;
		color: #ffffff;
		font-size: 1.25rem;
		font-weight: 950;
		letter-spacing: -.02em;
	}

	.cn-panel-header p {
		margin: 6px 0 0;
		color: rgba(255, 255, 255, .62);
		font-size: .86rem;
		line-height: 1.6;
	}

	.cn-panel-header-icon {
		width: 46px;
		height: 46px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex: 0 0 auto;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border: 1px solid rgba(255, 138, 31, .24);
	}

	.cn-panel-body {
		padding: 22px 24px 24px;
	}

	/* CONTACT DETAIL */
	.cn-contact-list {
		display: grid;
		gap: 14px;
	}

	.cn-contact-item {
		display: grid;
		grid-template-columns: auto 1fr;
		gap: 14px;
		padding: 15px;
		border-radius: 20px;
		background: rgba(7, 70, 141, .58);
		border: 1px solid rgba(141, 205, 255, .14);
		text-decoration: none;
		transition: transform .22s ease, border-color .22s ease, background .22s ease;
	}

	.cn-contact-item:hover {
		transform: translateY(-3px);
		border-color: rgba(255, 138, 31, .42);
		background: rgba(8, 78, 156, .76);
	}

	.cn-contact-item-icon {
		width: 46px;
		height: 46px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border: 1px solid rgba(255, 138, 31, .24);
	}

	.cn-contact-item.whatsapp .cn-contact-item-icon {
		color: #35d56f;
		background: rgba(53, 213, 111, .12);
		border-color: rgba(53, 213, 111, .24);
	}

	.cn-contact-item.telegram .cn-contact-item-icon {
		color: #4bb6ff;
		background: rgba(75, 182, 255, .12);
		border-color: rgba(75, 182, 255, .24);
	}

	.cn-contact-item.email .cn-contact-item-icon {
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border-color: rgba(255, 138, 31, .24);
	}

	.cn-contact-item small {
		display: block;
		color: rgba(255, 255, 255, .52);
		text-transform: uppercase;
		font-weight: 900;
		letter-spacing: .04em;
		font-size: .72rem;
		margin-bottom: 4px;
	}

	.cn-contact-item strong {
		display: block;
		color: #ffffff;
		font-weight: 950;
		font-size: .96rem;
		line-height: 1.35;
	}

	.cn-contact-item span {
		display: block;
		color: rgba(255, 255, 255, .64);
		font-size: .82rem;
		line-height: 1.5;
		margin-top: 4px;
	}

	.cn-office-box {
		margin-top: 18px;
		padding: 16px;
		border-radius: 22px;
		background:
			linear-gradient(135deg, rgba(255, 138, 31, .13), rgba(0, 198, 255, .07));
		border: 1px solid rgba(255, 255, 255, .14);
	}

	.cn-office-box h3 {
		color: #ffffff;
		margin: 0 0 5px;
		font-size: 1rem;
		font-weight: 950;
	}

	.cn-office-box p {
		color: rgba(255, 255, 255, .68);
		margin: 0 0 14px;
		font-size: .88rem;
		line-height: 1.65;
	}

	.cn-map {
		overflow: hidden;
		border-radius: 18px;
		height: 220px;
		border: 1px solid rgba(141, 205, 255, .18);
		background: rgba(255, 255, 255, .05);
	}

	.cn-map iframe {
		width: 100%;
		height: 100%;
		border: 0;
		filter: saturate(.95) contrast(1.02);
	}

	/* FORM */
	.cn-form-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 16px;
	}

	.cn-form-group.full {
		grid-column: 1 / -1;
	}

	.cn-label {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		color: rgba(255, 255, 255, .72);
		font-size: .78rem;
		font-weight: 900;
		text-transform: uppercase;
		letter-spacing: .035em;
		margin-bottom: 8px;
	}

	.cn-input,
	.cn-textarea,
	.cn-select {
		width: 100%;
		border: 1px solid rgba(141, 205, 255, .18);
		background: rgba(255, 255, 255, .08);
		color: #ffffff;
		border-radius: 16px;
		padding: 13px 14px;
		outline: none;
		transition: border-color .22s ease, box-shadow .22s ease, background .22s ease;
	}

	.cn-input::placeholder,
	.cn-textarea::placeholder {
		color: rgba(255, 255, 255, .42);
	}

	.cn-input:focus,
	.cn-textarea:focus,
	.cn-select:focus {
		border-color: rgba(255, 138, 31, .55);
		background: rgba(255, 255, 255, .11);
		box-shadow: 0 0 0 4px rgba(255, 138, 31, .12);
		color: #ffffff;
	}

	.cn-select option {
		color: #0b1f3d;
		background: #ffffff;
	}

	.cn-textarea {
		resize: vertical;
		min-height: 148px;
	}

	.cn-form-hint {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		margin-top: 16px;
		padding: 14px;
		border-radius: 18px;
		background: rgba(255, 176, 32, .11);
		border: 1px dashed rgba(255, 176, 32, .36);
		color: rgba(255, 255, 255, .76);
		font-size: .86rem;
		line-height: 1.65;
	}

	.cn-form-hint i {
		color: #ffb020;
		margin-top: 4px;
	}

	.cn-submit-row {
		margin-top: 18px;
		display: flex;
		gap: 12px;
		align-items: center;
		justify-content: space-between;
	}

	.cn-submit-row .cn-btn-primary {
		min-width: 190px;
		cursor: pointer;
	}

	.cn-submit-note {
		color: rgba(255, 255, 255, .55);
		font-size: .8rem;
		line-height: 1.55;
	}

	/* SMALL INFO */
	.cn-info-strip {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 14px;
		margin-top: 22px;
	}

	.cn-info-box {
		padding: 16px;
		border-radius: 20px;
		background: rgba(7, 70, 141, .58);
		border: 1px solid rgba(141, 205, 255, .14);
		display: flex;
		gap: 12px;
		align-items: flex-start;
	}

	.cn-info-box i {
		color: #ff8a1f;
		margin-top: 3px;
	}

	.cn-info-box strong {
		display: block;
		color: #ffffff;
		font-size: .9rem;
		font-weight: 950;
		margin-bottom: 3px;
	}

	.cn-info-box span {
		display: block;
		color: rgba(255, 255, 255, .64);
		font-size: .8rem;
		line-height: 1.55;
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
		.cn-contact-page {
			padding-top: 32px;
		}

		.cn-contact-hero-content {
			grid-template-columns: 1fr;
		}

		.cn-contact-badge {
			width: 90px;
			height: 90px;
			border-radius: 24px;
		}

		.cn-contact-badge i {
			font-size: 2.2rem;
		}

		.cn-support-grid,
		.cn-contact-main-grid,
		.cn-info-strip {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 576px) {
		.cn-contact-page {
			padding: 24px 0 64px;
		}

		.cn-contact-hero {
			border-radius: 22px;
			padding: 24px 20px;
		}

		.cn-contact-actions {
			flex-direction: column;
		}

		.cn-btn-primary,
		.cn-btn-secondary {
			width: 100%;
		}

		.cn-panel {
			border-radius: 22px;
		}

		.cn-panel-header,
		.cn-panel-body {
			padding: 18px;
		}

		.cn-form-grid {
			grid-template-columns: 1fr;
		}

		.cn-submit-row {
			align-items: stretch;
			flex-direction: column;
		}

		.cn-submit-row .cn-btn-primary {
			min-width: 100%;
		}
	}
</style>
@endpush

@section('content')
<section class="cn-contact-page">
	<div class="container">
		<div class="cn-contact-wrap">

			{{-- HERO --}}
			<div class="cn-contact-hero cn-animate-down">
				<div class="cn-contact-hero-content">
					<div>
						<div class="cn-eyebrow">
							<i class="fa-solid fa-headset"></i>
							Customer Support
						</div>

						<h1 class="cn-contact-title">
							Pusat <span>Bantuan</span>
						</h1>

						<p class="cn-contact-subtitle">
							Kami siap membantu kendala transaksi, pengecekan invoice, refund, atau pertanyaan seputar layanan
							<strong>{{ $appName }}</strong>. Pilih channel paling nyaman untuk menghubungi tim support.
						</p>

						<div class="cn-contact-actions">
							<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener" class="cn-btn-primary">
								<i class="fa-brands fa-whatsapp"></i>
								Chat WhatsApp
							</a>

							<a href="{{ $invoiceUrl }}" class="cn-btn-secondary">
								<i class="fa-solid fa-receipt"></i>
								Cek Pesanan
							</a>
						</div>
					</div>

					<div class="cn-contact-badge">
						<i class="fa-solid fa-comments"></i>
					</div>
				</div>
			</div>

			{{-- QUICK SUPPORT --}}
			<div class="cn-support-grid">
				<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener" class="cn-support-card whatsapp cn-animate-up" style="animation-delay: 80ms;">
					<div class="cn-support-icon">
						<i class="fa-brands fa-whatsapp"></i>
					</div>
					<div>
						<small>WhatsApp Admin</small>
						<strong>{{ $whatsappLabel }}</strong>
						<span>Fast response untuk kendala transaksi.</span>
					</div>
				</a>

				<a href="#" class="cn-support-card telegram cn-animate-up" style="animation-delay: 160ms;">
					<div class="cn-support-icon">
						<i class="fa-brands fa-telegram"></i>
					</div>
					<div>
						<small>Telegram Channel</small>
						<strong>{{ $telegramChannel }}</strong>
						<span>Info update layanan dan pengumuman.</span>
					</div>
				</a>

				<a href="mailto:{{ $emailSupport }}" class="cn-support-card email cn-animate-up" style="animation-delay: 240ms;">
					<div class="cn-support-icon">
						<i class="fa-solid fa-envelope"></i>
					</div>
					<div>
						<small>Email Support</small>
						<strong>{{ $emailSupport }}</strong>
						<span>Untuk kebutuhan bantuan via email.</span>
					</div>
				</a>
			</div>

			{{-- MAIN CONTENT --}}
			<div class="cn-contact-main-grid">

				{{-- LEFT PANEL --}}
				<div class="cn-panel cn-animate-up" style="animation-delay: 120ms;">
					<div class="cn-panel-header">
						<div>
							<h2>Informasi Kontak</h2>
							<p>Pilih kontak resmi agar bantuan lebih aman dan jelas.</p>
						</div>

						<div class="cn-panel-header-icon">
							<i class="fa-solid fa-address-book"></i>
						</div>
					</div>

					<div class="cn-panel-body">
						<div class="cn-contact-list">

							<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener" class="cn-contact-item whatsapp">
								<div class="cn-contact-item-icon">
									<i class="fa-brands fa-whatsapp"></i>
								</div>
								<div>
									<small>WhatsApp Admin</small>
									<strong>{{ $whatsappLabel }}</strong>
									<span>Gunakan nomor ini untuk komplain transaksi, refund, atau bantuan checkout.</span>
								</div>
							</a>

							<a href="#" class="cn-contact-item telegram">
								<div class="cn-contact-item-icon">
									<i class="fa-brands fa-telegram"></i>
								</div>
								<div>
									<small>Telegram Channel</small>
									<strong>{{ $telegramChannel }}</strong>
									<span>Ikuti pengumuman produk, promo, dan status layanan.</span>
								</div>
							</a>

							<a href="mailto:{{ $emailSupport }}" class="cn-contact-item email">
								<div class="cn-contact-item-icon">
									<i class="fa-solid fa-envelope"></i>
								</div>
								<div>
									<small>Email Support</small>
									<strong>{{ $emailSupport }}</strong>
									<span>Kirim detail kendala lengkap beserta invoice dan bukti pembayaran.</span>
								</div>
							</a>

						</div>

						<div class="cn-office-box">
							<h3>
								<i class="fa-solid fa-location-dot me-2" style="color: #ff8a1f;"></i>
								Lokasi Kantor
							</h3>
							<p>
								Map berikut hanya sebagai titik informasi. Untuk bantuan paling cepat, gunakan WhatsApp admin.
							</p>

							<div class="cn-map">
								<iframe
									src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.448554746201!2d106.816666!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDknMDAuMCJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid"
									allowfullscreen=""
									loading="lazy"
									referrerpolicy="no-referrer-when-downgrade">
								</iframe>
							</div>
						</div>
					</div>
				</div>

				{{-- RIGHT PANEL --}}
				<div class="cn-panel cn-animate-up" style="animation-delay: 220ms;">
					<div class="cn-panel-header">
						<div>
							<h2>Kirim Pesan</h2>
							<p>Isi data kendala dengan jelas agar tim support bisa bantu lebih cepat.</p>
						</div>

						<div class="cn-panel-header-icon">
							<i class="fa-solid fa-paper-plane"></i>
						</div>
					</div>

					<div class="cn-panel-body">
						<form action="{{ $contactSubmitUrl }}" method="POST">
							@csrf

							<div class="cn-form-grid">
								<div class="cn-form-group">
									<label for="name" class="cn-label">
										Nama Lengkap
									</label>
									<input
										type="text"
										id="name"
										name="name"
										class="cn-input"
										placeholder="Nama Anda"
										value="{{ old('name') }}"
										required>
								</div>

								<div class="cn-form-group">
									<label for="contact" class="cn-label">
										Email / WhatsApp
									</label>
									<input
										type="text"
										id="contact"
										name="contact"
										class="cn-input"
										placeholder="Contoh: 0812xxxx / email"
										value="{{ old('contact') }}"
										required>
								</div>

								<div class="cn-form-group">
									<label for="category" class="cn-label">
										Kategori Bantuan
									</label>
									<select id="category" name="category" class="cn-select" required>
										<option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori</option>
										<option value="transaksi" {{ old('category') === 'transaksi' ? 'selected' : '' }}>Kendala Transaksi</option>
										<option value="refund" {{ old('category') === 'refund' ? 'selected' : '' }}>Refund</option>
										<option value="pembayaran" {{ old('category') === 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
										<option value="akun" {{ old('category') === 'akun' ? 'selected' : '' }}>Akun Member</option>
										<option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
									</select>
								</div>

								<div class="cn-form-group">
									<label for="subject" class="cn-label">
										Subjek / ID Transaksi
									</label>
									<input
										type="text"
										id="subject"
										name="subject"
										class="cn-input"
										placeholder="Contoh: Komplain INV-123xx"
										value="{{ old('subject') }}"
										required>
								</div>

								<div class="cn-form-group full">
									<label for="message" class="cn-label">
										Pesan
									</label>
									<textarea
										id="message"
										name="message"
										class="cn-textarea"
										placeholder="Jelaskan kendala Anda. Sertakan invoice, produk, nomor tujuan, dan kronologi singkat..."
										required>{{ old('message') }}</textarea>
								</div>
							</div>

							<div class="cn-form-hint">
								<i class="fa-solid fa-circle-info"></i>
								<div>
									Untuk komplain transaksi, sertakan nomor invoice dan bukti pembayaran agar pengecekan lebih cepat.
									Jangan bagikan password atau OTP kepada siapa pun.
								</div>
							</div>

							<div class="cn-submit-row">
								<div class="cn-submit-note">
									Balasan akan dikirim melalui kontak yang Anda isi di form.
								</div>

								<button type="submit" class="cn-btn-primary">
									<i class="fa-solid fa-paper-plane"></i>
									Kirim Pesan
								</button>
							</div>
						</form>
					</div>
				</div>

			</div>

			{{-- INFO STRIP --}}
			<div class="cn-info-strip">
				<div class="cn-info-box">
					<i class="fa-solid fa-clock"></i>
					<div>
						<strong>Jam Bantuan</strong>
						<span>Support dapat dihubungi setiap hari. Response mengikuti antrean pesan.</span>
					</div>
				</div>

				<div class="cn-info-box">
					<i class="fa-solid fa-receipt"></i>
					<div>
						<strong>Siapkan Invoice</strong>
						<span>Nomor invoice membantu admin menemukan transaksi lebih cepat.</span>
					</div>
				</div>

				<div class="cn-info-box">
					<i class="fa-solid fa-shield-halved"></i>
					<div>
						<strong>Kontak Resmi</strong>
						<span>Pastikan hanya menghubungi kontak resmi yang tertera di halaman ini.</span>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
@endsection
