<footer>
	<div class="container">
		<div class="row gy-4">
			{{-- Kolom 1: Brand --}}
			<div class="col-lg-4 col-md-6">
				<div class="d-flex align-items-center gap-2 mb-3">
					<div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
						<i class="fa-solid fa-cloud-bolt"></i>
					</div>
					<h5 class="mb-0">IDCloudStore</h5>
				</div>
				<p class="small opacity-75">
					Platform Top Up Game & PPOB Termurah, Tercepat, dan Terpercaya di Indonesia. Buka 24 Jam Non-stop.
				</p>
			</div>

			{{-- Kolom 2: Peta Situs --}}
			<div class="col-lg-2 col-md-3 col-6">
				<h6 class="fw-bold mb-3 text-uppercase" style="color: var(--primary-color)">Peta Situs</h6>
				<ul class="list-unstyled d-flex flex-column gap-2 small">
					<li><a href="#" class="text-decoration-none" style="color: var(--text-muted)">Beranda</a></li>
					<li><a href="#" class="text-decoration-none" style="color: var(--text-muted)">Masuk</a></li>
					<li><a href="#" class="text-decoration-none" style="color: var(--text-muted)">Daftar</a></li>
				</ul>
			</div>

			{{-- Kolom 3: Dukungan --}}
			<div class="col-lg-2 col-md-3 col-6">
				<h6 class="fw-bold mb-3 text-uppercase" style="color: var(--primary-color)">Dukungan</h6>
				<ul class="list-unstyled d-flex flex-column gap-2 small">
					<li><a href="#" class="text-decoration-none" style="color: var(--text-muted)">WhatsApp Admin</a></li>
					<li><a href="#" class="text-decoration-none" style="color: var(--text-muted)">Telegram</a></li>
					<li><a href="#" class="text-decoration-none" style="color: var(--text-muted)">Email Support</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="footer-bottom text-center">
		<div class="container">
			<small>&copy; {{ date('Y') }} IDCloudStore. All rights reserved.</small>
		</div>
	</div>
</footer>
