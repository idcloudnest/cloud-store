@php
	$appName = $appName ?? config('app.name', 'Cloud Nest Store');

	$whatsappNumber = preg_replace('/\D+/', '', config('services.whatsapp.number', '62895320894991'));
	$whatsappLabel = config('services.whatsapp.label', '0895-3208-94991');
	$emailSupport = config('mail.from.address', 'support@idcloudnest.com');

	$homeUrl = url('/');
	$invoiceUrl = Route::has('pages.invoices') ? route('pages.invoices') : '#';
	$caraOrderUrl = Route::has('pages.cara-order') ? route('pages.cara-order') : '#';
	$contactUrl = Route::has('pages.contact') ? route('pages.contact') : '#';
	$termsUrl = Route::has('pages.terms') ? route('pages.terms') : '#';
	$privacyUrl = Route::has('pages.privacyPolicy') ? route('pages.privacyPolicy') : '#';

	$waMessage = rawurlencode("Halo Admin {$appName}, saya butuh bantuan.");
@endphp

<footer class="cn-footer">
	<div class="cn-footer-glow cn-footer-glow-left"></div>
	<div class="cn-footer-glow cn-footer-glow-right"></div>

	<div class="container position-relative">

		{{-- TOP CTA --}}
		{{-- <div class="cn-footer-cta">
			<div>
				<div class="cn-footer-eyebrow">
					<i class="fa-solid fa-bolt"></i>
					Fast Digital Checkout
				</div>

				<h3>Top up & bayar produk digital lebih gampang.</h3>
				<p>
					Temukan produk favorit, selesaikan pembayaran, dan pantau status pesanan langsung dari satu tempat.
				</p>
			</div>

			<div class="cn-footer-cta-actions">
				<a href="{{ $homeUrl }}" class="cn-footer-btn cn-footer-btn-primary">
					<i class="fa-solid fa-bag-shopping"></i>
					Mulai Top Up
				</a>

				<a href="{{ $invoiceUrl }}" class="cn-footer-btn cn-footer-btn-ghost">
					<i class="fa-solid fa-receipt"></i>
					Cek Pesanan
				</a>
			</div>
		</div> --}}

		{{-- MAIN FOOTER --}}
		<div class="cn-footer-main">
			<div class="row g-4 align-items-start">

				{{-- BRAND --}}
				<div class="col-lg-4">
					<div class="cn-footer-brand">
						<div class="cn-footer-logo-wrap">
							<img
								src="{{ asset('images/cloudnest.png') }}"
								class="cn-footer-logo"
								alt="{{ $appName }}"
								onerror="this.style.display='none'">
						</div>

						<div>
							<h5>{{ $appName }}</h5>
							<span>Top Up Game & PPOB Digital</span>
						</div>
					</div>

					<p class="cn-footer-desc">
						Platform produk digital untuk top up game, voucher, pulsa, paket data, token PLN,
						dan pembayaran tagihan dengan proses cepat, aman, dan mobile-friendly.
					</p>

					<div class="cn-footer-social">
						<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener" aria-label="WhatsApp">
							<i class="fa-brands fa-whatsapp"></i>
						</a>

						<a href="#" aria-label="Instagram">
							<i class="fa-brands fa-instagram"></i>
						</a>

						<a href="#" aria-label="Telegram">
							<i class="fa-brands fa-telegram"></i>
						</a>

						<a href="mailto:{{ $emailSupport }}" aria-label="Email">
							<i class="fa-solid fa-envelope"></i>
						</a>
					</div>
				</div>

				{{-- MENU --}}
				<div class="col-6 col-lg-2">
					<h6 class="cn-footer-heading">Menu</h6>
					<ul class="cn-footer-list">
						<li>
							<a href="{{ $homeUrl }}">
								<i class="fa-solid fa-angle-right"></i>
								Top Up
							</a>
						</li>
						<li>
							<a href="{{ $caraOrderUrl }}">
								<i class="fa-solid fa-angle-right"></i>
								Cara Order
							</a>
						</li>
						<li>
							<a href="{{ $invoiceUrl }}">
								<i class="fa-solid fa-angle-right"></i>
								Lacak Pesanan
							</a>
						</li>
						<li>
							<a href="{{ $contactUrl }}">
								<i class="fa-solid fa-angle-right"></i>
								Hubungi Kami
							</a>
						</li>
					</ul>
				</div>

				{{-- LEGAL --}}
				<div class="col-6 col-lg-2">
					<h6 class="cn-footer-heading">Bantuan</h6>
					<ul class="cn-footer-list">
						<li>
							<a href="{{ $termsUrl }}">
								<i class="fa-solid fa-angle-right"></i>
								Syarat & Ketentuan
							</a>
						</li>
						<li>
							<a href="{{ $privacyUrl }}">
								<i class="fa-solid fa-angle-right"></i>
								Kebijakan Privasi
							</a>
						</li>
						<li>
							<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener">
								<i class="fa-solid fa-angle-right"></i>
								WhatsApp CS
							</a>
						</li>
						<li>
							<a href="mailto:{{ $emailSupport }}">
								<i class="fa-solid fa-angle-right"></i>
								Email Support
							</a>
						</li>
					</ul>
				</div>

				{{-- PAYMENT & SUPPORT --}}
				<div class="col-lg-4">
					<div class="cn-footer-support-card">
						<div class="cn-footer-support-icon">
							<i class="fa-solid fa-headset"></i>
						</div>

						<div>
							<small>Customer Support</small>
							<strong>{{ $whatsappLabel }}</strong>
							<span>Siapkan invoice saat komplain agar pengecekan lebih cepat.</span>
						</div>
					</div>

					<div class="cn-footer-payment">
						<h6 class="cn-footer-heading mb-3">Metode Pembayaran</h6>

						<div class="cn-payment-grid">
							<span>QRIS</span>
							<span>BCA</span>
							<span>BNI</span>
							<span>BRI</span>
							<span>Mandiri</span>
							<span>Gopay</span>
							<span>ShopeePay</span>
							<span>Saldo</span>
						</div>
					</div>
				</div>

			</div>
		</div>

		{{-- BOTTOM --}}
		<div class="cn-footer-bottom">
			<div>
				&copy; {{ date('Y') }} <strong>{{ $appName }}</strong>. All Rights Reserved.
			</div>

			<div class="cn-footer-bottom-links">
				<a href="{{ $termsUrl }}">Terms</a>
				<span></span>
				<a href="{{ $privacyUrl }}">Privacy</a>
				<span></span>
				<a href="{{ $contactUrl }}">Support</a>
			</div>
		</div>
	</div>
</footer>

<a
	href="https://wa.me/{{ $whatsappNumber }}?text={{ $waMessage }}"
	class="cn-wa-button"
	target="_blank"
	rel="noopener"
	aria-label="Chat WhatsApp">
	<i class="fa-brands fa-whatsapp"></i>
</a>

<style>
	/* =========================================================
	   CLOUD NEST FOOTER
	   ========================================================= */

	.cn-footer {
		position: relative;
		overflow: hidden;
		padding: 0 0 22px;
		margin-top: auto;
		color: #ffffff;
		background:
			radial-gradient(circle at 12% 0%, rgba(0, 198, 255, .16), transparent 30%),
			radial-gradient(circle at 88% 8%, rgba(255, 122, 24, .14), transparent 28%),
			linear-gradient(180deg, #05285a 0%, #041f49 48%, #031936 100%);
		border-top: 1px solid rgba(141, 205, 255, .18);
		z-index: 5;
	}

	.cn-footer::before {
		content: '';
		position: absolute;
		left: 0;
		right: 0;
		top: 0;
		height: 3px;
		background: linear-gradient(90deg, transparent, #ff3d3d, #ff7a18, #ff3d3d, transparent);
		box-shadow: 0 0 18px rgba(255, 61, 61, .42);
		opacity: .9;
	}

	.cn-footer-glow {
		position: absolute;
		width: 360px;
		height: 360px;
		border-radius: 999px;
		filter: blur(48px);
		opacity: .35;
		pointer-events: none;
	}

	.cn-footer-glow-left {
		left: -160px;
		top: 80px;
		background: rgba(0, 198, 255, .22);
	}

	.cn-footer-glow-right {
		right: -160px;
		top: 10px;
		background: rgba(255, 122, 24, .20);
	}

	/* CTA */
	.cn-footer-cta {
		position: relative;
		z-index: 2;
		transform: translateY(-34px);
		margin-bottom: -4px;
		padding: 24px;
		border-radius: 26px;
		background:
			linear-gradient(135deg, rgba(8, 86, 171, .96), rgba(5, 48, 103, .96)),
			radial-gradient(circle at top right, rgba(255, 122, 24, .24), transparent 38%);
		border: 1px solid rgba(141, 205, 255, .24);
		box-shadow:
			0 24px 70px rgba(0, 0, 0, .26),
			inset 0 1px 0 rgba(255, 255, 255, .12);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 22px;
		overflow: hidden;
	}

	.cn-footer-cta::after {
		content: '';
		position: absolute;
		width: 220px;
		height: 220px;
		right: -80px;
		top: -90px;
		border-radius: 999px;
		background: rgba(255, 122, 24, .18);
		filter: blur(18px);
		pointer-events: none;
	}

	.cn-footer-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 12px;
		margin-bottom: 10px;
		width: fit-content;
		border-radius: 999px;
		color: #ff8a1f;
		background: rgba(255, 138, 31, .12);
		border: 1px solid rgba(255, 138, 31, .26);
		font-size: .75rem;
		font-weight: 900;
		letter-spacing: .04em;
		text-transform: uppercase;
	}

	.cn-footer-cta h3 {
		position: relative;
		z-index: 2;
		margin: 0;
		color: #ffffff;
		font-size: clamp(1.35rem, 2.4vw, 2rem);
		font-weight: 950;
		letter-spacing: -.03em;
		line-height: 1.18;
	}

	.cn-footer-cta p {
		position: relative;
		z-index: 2;
		max-width: 640px;
		margin: 8px 0 0;
		color: rgba(255, 255, 255, .72);
		font-size: .92rem;
		line-height: 1.7;
	}

	.cn-footer-cta-actions {
		position: relative;
		z-index: 2;
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		flex: 0 0 auto;
	}

	.cn-footer-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 9px;
		min-height: 44px;
		padding: 11px 16px;
		border-radius: 14px;
		text-decoration: none;
		font-size: .9rem;
		font-weight: 900;
		transition: transform .22s ease, box-shadow .22s ease, background .22s ease, border-color .22s ease;
	}

	.cn-footer-btn-primary {
		color: #ffffff;
		background: linear-gradient(135deg, #ff7a18, #ff3d3d);
		box-shadow: 0 14px 28px rgba(255, 86, 40, .24);
	}

	.cn-footer-btn-primary:hover {
		color: #ffffff;
		transform: translateY(-2px);
		box-shadow: 0 18px 36px rgba(255, 86, 40, .34);
	}

	.cn-footer-btn-ghost {
		color: rgba(255, 255, 255, .88);
		background: rgba(255, 255, 255, .08);
		border: 1px solid rgba(255, 255, 255, .16);
	}

	.cn-footer-btn-ghost:hover {
		color: #ffffff;
		background: rgba(255, 255, 255, .12);
		border-color: rgba(255, 138, 31, .45);
		transform: translateY(-2px);
	}

	/* MAIN */
	.cn-footer-main {
		position: relative;
		z-index: 2;
		padding: 28px 0 26px;
	}

	.cn-footer-brand {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-bottom: 16px;
	}

	.cn-footer-logo-wrap {
		width: 72px;
		height: 56px;
		padding: 10px 8px;
		flex: 0 0 auto;
		border-radius: 18px;
		display: flex;
		align-items: center;
		justify-content: center;
		background:
			linear-gradient(145deg, rgba(255, 255, 255, .98), rgba(224, 246, 255, .94), rgba(175, 226, 255, .88));
		border: 1px solid rgba(125, 211, 252, .55);
		box-shadow:
			0 14px 30px rgba(0, 0, 0, .22),
			inset 0 1px 0 rgba(255, 255, 255, .9);
		/* padding: 8px; */
	}

	.cn-footer-logo {
		max-width: 100%;
		max-height: 100%;
		object-fit: contain;
		display: block;
	}

	.cn-footer-brand h5 {
		margin: 0;
		color: #ffffff;
		font-size: 1.15rem;
		font-weight: 950;
		letter-spacing: -.02em;
	}

	.cn-footer-brand span {
		display: block;
		margin-top: 2px;
		color: rgba(255, 255, 255, .58);
		font-size: .82rem;
		font-weight: 700;
	}

	.cn-footer-desc {
		max-width: 520px;
		margin: 0;
		color: rgba(255, 255, 255, .66);
		font-size: .9rem;
		line-height: 1.8;
	}

	.cn-footer-social {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		margin-top: 18px;
	}

	.cn-footer-social a {
		width: 42px;
		height: 42px;
		border-radius: 15px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		color: rgba(255, 255, 255, .82);
		background: rgba(255, 255, 255, .075);
		border: 1px solid rgba(255, 255, 255, .12);
		text-decoration: none;
		transition: transform .22s ease, background .22s ease, color .22s ease, border-color .22s ease;
	}

	.cn-footer-social a:hover {
		color: #ffffff;
		background: rgba(255, 138, 31, .16);
		border-color: rgba(255, 138, 31, .42);
		transform: translateY(-3px) rotate(-4deg);
	}

	.cn-footer-heading {
		margin: 0 0 14px;
		color: #ffffff;
		font-size: .95rem;
		font-weight: 950;
		letter-spacing: -.01em;
	}

	.cn-footer-list {
		list-style: none;
		padding: 0;
		margin: 0;
		display: grid;
		gap: 9px;
	}

	.cn-footer-list a {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		color: rgba(255, 255, 255, .66);
		text-decoration: none;
		font-size: .88rem;
		font-weight: 700;
		transition: color .22s ease, transform .22s ease;
	}

	.cn-footer-list a i {
		color: #ff8a1f;
		font-size: .72rem;
		opacity: .85;
	}

	.cn-footer-list a:hover {
		color: #ffffff;
		transform: translateX(4px);
	}

	.cn-footer-support-card {
		display: grid;
		grid-template-columns: auto 1fr;
		gap: 14px;
		padding: 16px;
		margin-bottom: 18px;
		border-radius: 22px;
		background:
			linear-gradient(135deg, rgba(53, 213, 111, .12), rgba(0, 198, 255, .07));
		border: 1px solid rgba(255, 255, 255, .14);
	}

	.cn-footer-support-icon {
		width: 48px;
		height: 48px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #35d56f;
		background: rgba(53, 213, 111, .12);
		border: 1px solid rgba(53, 213, 111, .24);
	}

	.cn-footer-support-card small {
		display: block;
		color: rgba(255, 255, 255, .52);
		text-transform: uppercase;
		font-weight: 900;
		letter-spacing: .04em;
		font-size: .72rem;
		margin-bottom: 3px;
	}

	.cn-footer-support-card strong {
		display: block;
		color: #ffffff;
		font-size: .98rem;
		font-weight: 950;
		margin-bottom: 3px;
	}

	.cn-footer-support-card span {
		display: block;
		color: rgba(255, 255, 255, .64);
		font-size: .82rem;
		line-height: 1.55;
	}

	.cn-footer-payment {
		padding: 16px;
		border-radius: 22px;
		background: rgba(255, 255, 255, .055);
		border: 1px solid rgba(255, 255, 255, .10);
	}

	.cn-payment-grid {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}

	.cn-payment-grid span {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-height: 34px;
		padding: 8px 11px;
		border-radius: 12px;
		color: rgba(255, 255, 255, .82);
		background: rgba(255, 255, 255, .08);
		border: 1px solid rgba(255, 255, 255, .13);
		font-size: .78rem;
		font-weight: 900;
		letter-spacing: .01em;
	}

	/* BOTTOM */
	.cn-footer-bottom {
		position: relative;
		z-index: 2;
		padding-top: 18px;
		border-top: 1px solid rgba(141, 205, 255, .16);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		color: rgba(255, 255, 255, .58);
		font-size: .84rem;
	}

	.cn-footer-bottom strong {
		color: rgba(255, 255, 255, .86);
	}

	.cn-footer-bottom-links {
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.cn-footer-bottom-links a {
		color: rgba(255, 255, 255, .58);
		text-decoration: none;
		transition: color .22s ease;
	}

	.cn-footer-bottom-links a:hover {
		color: #ff8a1f;
	}

	.cn-footer-bottom-links span {
		width: 4px;
		height: 4px;
		border-radius: 999px;
		background: rgba(255, 255, 255, .28);
	}

	/* FLOATING WHATSAPP */
	.cn-wa-button {
		position: fixed;
		right: 22px;
		bottom: 22px;
		z-index: 1050;
		width: 58px;
		height: 58px;
		border-radius: 20px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffffff;
		background: linear-gradient(135deg, #25d366, #13a64a);
		border: 1px solid rgba(255, 255, 255, .22);
		box-shadow:
			0 18px 36px rgba(0, 0, 0, .28),
			0 0 0 8px rgba(37, 211, 102, .12);
		text-decoration: none;
		font-size: 1.65rem;
		transition: transform .22s ease, box-shadow .22s ease;
	}

	.cn-wa-button:hover {
		color: #ffffff;
		transform: translateY(-4px) scale(1.03);
		box-shadow:
			0 24px 42px rgba(0, 0, 0, .34),
			0 0 0 10px rgba(37, 211, 102, .15);
	}

	@media (max-width: 991px) {
		.cn-footer {
			padding-bottom: 18px;
		}

		.cn-footer-cta {
			transform: translateY(-22px);
			margin-bottom: 0;
			align-items: flex-start;
			flex-direction: column;
		}

		.cn-footer-cta-actions {
			width: 100%;
		}

		.cn-footer-btn {
			flex: 1 1 0;
		}

		.cn-footer-main {
			padding-top: 18px;
		}
	}

	@media (max-width: 576px) {
		.cn-footer-cta {
			border-radius: 22px;
			padding: 20px;
		}

		.cn-footer-cta-actions {
			flex-direction: column;
		}

		.cn-footer-btn {
			width: 100%;
		}

		.cn-footer-brand {
			align-items: flex-start;
		}

		.cn-footer-logo-wrap {
			width: 64px;
			height: 50px;
			border-radius: 16px;
		}

		.cn-footer-bottom {
			align-items: flex-start;
			flex-direction: column;
		}

		.cn-wa-button {
			right: 16px;
			bottom: 16px;
			width: 54px;
			height: 54px;
			border-radius: 18px;
		}
	}
</style>
