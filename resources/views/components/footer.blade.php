{{-- <footer>
	<div class="container">
		<div class="row">
			<div class="col-lg-5 mb-4">
				<h5><i class="fas fa-cloud me-2"></i>{{ strtoupper($appName)}}</h5>
				<p class="small text-muted" style="text-align: justify;">
					Platform penyedia produk digital terlengkap dan terpercaya.
					Mulai dari Top Up Game, Pulsa, Paket Data, Voucher, hingga Token PLN
					tersedia dengan proses instan dan berbagai metode pembayaran otomatis.
				</p>
			</div>

			<div class="col-lg-3 mb-4">
				<h5>Bantuan</h5>
				<ul class="list-unstyled small">
					<li class="mb-2">
						<a href="{{ route('pages.cara-order') }}" class="text-decoration-none text-muted">Cara Order</a>
					</li>
					<li class="mb-2">
						<a href="{{ route('pages.contact') }}" class="text-decoration-none text-muted">Hubungi Kami</a>
					</li>
					<li class="mb-2">
						<a href="{{ route('pages.terms') }}" class="text-decoration-none text-muted">Syarat & Ketentuan</a>
					</li>
					<li class="mb-2">
						<a href="{{ route('pages.privacyPolicy') }}" class="text-decoration-none text-muted">Kebijakan Privasi</a>
					</li>
				</ul>
			</div>

			<div class="col-lg-4 mb-4">
				<h5>Kontak</h5>
				<p class="small text-muted">
					<i class="fab fa-whatsapp me-2"></i> +62 895-3208-94991<br>
					<i class="fas fa-envelope me-2"></i> admin@idcloudnest.com
				</p>
			</div>
		</div>
	</div>

	<div class="footer-bottom text-center">
		<small>&copy; {{ date('Y') }} {{ $appName }}. All Rights Reserved.</small>
	</div>
</footer> --}}


{{-- <footer class="footer-section pt-5 mt-auto">
	<div class="container pb-4">
		<div class="row gy-4">

			<div class="col-lg-5 col-md-12">
				<div class="d-flex align-items-center gap-2 mb-3">
					<img src="{{ asset('cloudnest.png') }}" height="40" width="auto" alt="">
					<h5 class="fw-bold mb-0 text-white">{{ config('app.name') }}</h5>
				</div>
				<p class="text-white-50 small pe-lg-5">
					Platform Top Up Game & PPOB termurah dan terpercaya di Indonesia.
					Menyediakan berbagai macam produk digital dengan proses otomatis dan layanan pelanggan terbaik.
				</p>

				<div class="d-flex gap-3 mt-4">
					<a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
					<a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
					<a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
				</div>
			</div>

			<div class="col-lg-2 col-6">
				<h6 class="text-white fw-bold mb-3">Peta Situs</h6>
				<ul class="list-unstyled d-flex flex-column gap-2">
					<li class="mb-2">
						<a href="{{ route('pages.cara-order') }}" class="text-decoration-none text-muted">Cara Order</a>
					</li>
					<li class="mb-2">
						<a href="{{ route('pages.contact') }}" class="text-decoration-none text-muted">Hubungi Kami</a>
					</li>
					<li class="mb-2">
						<a href="{{ route('pages.terms') }}" class="text-decoration-none text-muted">Syarat & Ketentuan</a>
					</li>
					<li class="mb-2">
						<a href="{{ route('pages.privacyPolicy') }}" class="text-decoration-none text-muted">Kebijakan Privasi</a>
					</li>
				</ul>
			</div>

			<div class="col-lg-2 col-6">
				<h6 class="text-white fw-bold mb-3">Dukungan</h6>
				<ul class="list-unstyled d-flex flex-column gap-2">
					<li><a href="#" class="footer-link">Hubungi Kami</a></li>
					<li><a href="#" class="footer-link">Syarat & Ketentuan</a></li>
					<li><a href="#" class="footer-link">Kebijakan Privasi</a></li>
				</ul>
			</div>

			<div class="col-lg-3 col-md-12">
				<h6 class="text-white fw-bold mb-3">Metode Pembayaran</h6>
				<div class="payment-grid bg-white bg-opacity-10 p-3 rounded">
					<div class="d-flex flex-wrap gap-2">
						<img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="bg-white rounded p-1" height="25" alt="BCA">
						<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" class="bg-white rounded p-1" height="25" alt="Mandiri">
						<img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" class="bg-white rounded p-1" height="25" alt="Gopay">
						<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/1200px-QRIS_logo.svg.png" class="bg-white rounded p-1" height="25" alt="QRIS">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="copyright-bar py-3 text-center border-top border-white border-opacity-10">
		<div class="container">
			<small class="text-white-50">&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All Rights Reserved.</small>
		</div>
	</div>
</footer> --}}
<footer class="footer-section pt-5 mt-auto">
	<div class="container pb-4">
		<div class="row gy-4">

			{{-- Kolom 1: Info Brand --}}
			<div class="col-lg-5 col-md-12">
				<div class="d-flex align-items-center gap-2 mb-3">
					<img src="{{ asset('cloudnest.png') }}" height="40" width="auto" alt="">
					{{-- Ganti text-white jadi footer-title --}}
					<h5 class="mb-0 footer-title">{{ config('app.name') }}</h5>
				</div>
				{{-- Hapus text-white-50 --}}
				<p class="small pe-lg-5">
					Platform Top Up Game & PPOB termurah dan terpercaya di Indonesia.
					Menyediakan berbagai macam produk digital dengan proses otomatis.
				</p>

				<div class="d-flex gap-3 mt-4">
					<a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
					<a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
					<a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
				</div>
			</div>

			{{-- Kolom 2: Peta Situs --}}
			<div class="col-lg-2 col-6">
				{{-- Ganti text-white fw-bold jadi footer-title --}}
				<h6 class="footer-title">Peta Situs</h6>
				<ul class="list-unstyled d-flex flex-column gap-2">
					<li class="mb-2"><a href="{{ route('pages.cara-order') }}" class="footer-link">Cara Order</a></li>
					<li class="mb-2"><a href="{{ route('pages.contact') }}" class="footer-link">Hubungi Kami</a></li>
					<li class="mb-2"><a href="{{ route('pages.terms') }}" class="footer-link">Syarat & Ketentuan</a></li>
				</ul>
			</div>

			{{-- Kolom 3: Dukungan --}}
			<div class="col-lg-2 col-6">
				<h6 class="footer-title">Dukungan</h6>
				<ul class="list-unstyled d-flex flex-column gap-2">
					{{-- Gunakan footer-link agar warnanya dinamis --}}
					<li><a href="#" class="footer-link">Bantuan Pelanggan</a></li>
					<li><a href="#" class="footer-link">Status Server</a></li>
					<li><a href="{{ route('pages.privacyPolicy') }}" class="footer-link">Kebijakan Privasi</a></li>
				</ul>
			</div>

			{{-- Kolom 4: Pembayaran --}}
			<div class="col-lg-3 col-md-12">
				<h6 class="footer-title">Metode Pembayaran</h6>
				{{-- Hapus bg-white bg-opacity-10, ganti jadi class payment-grid --}}
				<div class="payment-grid">
					<div class="d-flex flex-wrap gap-2">
						<img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="bg-white rounded p-1" height="25" alt="BCA">
						<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" class="bg-white rounded p-1" height="25" alt="Mandiri">
						<img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" class="bg-white rounded p-1" height="25" alt="Gopay">
						<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/1200px-QRIS_logo.svg.png" class="bg-white rounded p-1" height="25" alt="QRIS">
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Copyright --}}
	{{-- Hapus border-white dll --}}
	<div class="copyright-bar py-3 text-center">
		<div class="container">
			<small>&copy; {{ date('Y') }} <strong>{{ $appName }}</strong>. All Rights Reserved.</small>
		</div>
	</div>
</footer>

<style>
	/* --- SECTION FEATURES --- */
	/* .section-title {
		color: var(--text-main);
	} */
	/* .divider {
		width: 60px; height: 4px;
		margin-top: 10px;
	} */

	/* .feature-card {
		border: 1px solid var(--card-border);
		background: var(--card-bg);
		transition: transform 0.3s ease, box-shadow 0.3s ease;
		border-radius: 16px;
	}

	.feature-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 10px 30px rgba(0,0,0,0.1);
		border-color: var(--primary-color);
	} */

	/* --- FOOTER STYLE (Solid Dark) --- */
	/* .footer-section {
		background-color: #0f172a;
		color: #ffffff;
	} */

	/* .footer-link {
		color: #94a3b8;
		text-decoration: none;
		font-size: 0.9rem;
		transition: color 0.2s ease, padding-left 0.2s ease;
	}

	.footer-link:hover {
		color: var(--primary-color);
		padding-left: 5px;
	} */

	/* Social Buttons */
	/* .social-btn {
		width: 36px; height: 36px;
		background: rgba(255,255,255,0.1);
		color: white;
		border-radius: 50%;
		display: flex; align-items: center; justify-content: center;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	.social-btn:hover {
		background: var(--primary-color);
		transform: rotate(10deg);
		color: white;
	} */

	/* Payment Grid */
	.payment-grid img {
		object-fit: contain;
	}

	:root, [data-bs-theme="light"] {
		--footer-bg: #ffffff;
		--footer-text-main: #1e293b;
		--footer-text-muted: #64748b;
		--footer-border: #e2e8f0;
		--footer-social-bg: #f1f5f9;
		--footer-social-icon: #1e293b;
		--footer-payment-bg: #f8fafc;
	}

	[data-bs-theme="dark"] {
		--footer-bg: #0f172a;
		--footer-text-main: #ffffff;
		--footer-text-muted: #94a3b8;
		--footer-border: rgba(255, 255, 255, 0.1);
		--footer-social-bg: rgba(255, 255, 255, 0.1);
		--footer-social-icon: #ffffff;
		--footer-payment-bg: rgba(255, 255, 255, 0.05);
	}
	.footer-section {
		background-color: var(--footer-bg);
		color: var(--footer-text-muted);
		transition: background-color 0.3s ease, color 0.3s ease;
		border-top: 1px solid var(--footer-border);
	}


	.footer-title {
		color: var(--footer-text-main);
		font-weight: 700;
		margin-bottom: 1rem;
	}


	.footer-link {
		color: var(--footer-text-muted);
		text-decoration: none;
		font-size: 0.9rem;
		transition: all 0.2s ease;
	}
	.footer-link:hover {
		color: var(--primary-color);
		padding-left: 5px;
	}


	.social-btn {
		width: 36px; height: 36px;
		background: var(--footer-social-bg);
		color: var(--footer-social-icon);
		border-radius: 50%;
		display: flex; align-items: center; justify-content: center;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	.social-btn:hover {
		background: var(--primary-color);
		color: #fff;
		transform: rotate(10deg);
	}


	.payment-grid {
		background-color: var(--footer-payment-bg);
		padding: 1rem;
		border-radius: 8px;
	}


	.copyright-bar {
		border-top: 1px solid var(--footer-border);
		color: var(--footer-text-muted);
	}
</style>
