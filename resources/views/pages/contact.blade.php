@extends('layouts.front')

@section('title', 'Hubungi Kami')

@section('content')
<div class="container py-5">

	<div class="text-center mb-5 animate-fade-down">
		<h2 class="fw-bold text-body">Pusat Bantuan</h2>
		<p class="text-muted">Kami siap membantu Anda 24/7. Silakan hubungi kami melalui kontak di bawah ini.</p>
	</div>

	<div class="row g-4">

		{{-- KOLOM KIRI: INFO KONTAK --}}
		<div class="col-lg-5 animate-up">
			<div class="card h-100 border-0 glass-card overflow-hidden">
				<div class="card-body p-4">
					<h5 class="fw-bold text-body mb-4">Informasi Kontak</h5>

					<div class="d-flex align-items-center mb-4">
						<div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
							<i class="fab fa-whatsapp fa-lg"></i>
						</div>
						<div class="ms-3">
							<small class="text-muted d-block text-uppercase">WhatsApp Admin</small>
							<a href="https://wa.me/62895320894991" target="_blank" class="fw-bold text-body text-decoration-none stretched-link">0895-3208-94991</a>
						</div>
					</div>

					<div class="d-flex align-items-center mb-4">
						<div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
							<i class="fab fa-telegram-plane fa-lg"></i>
						</div>
						<div class="ms-3">
							<small class="text-muted d-block text-uppercase">Telegram Channel</small>
							<a href="#" class="fw-bold text-body text-decoration-none">@IDCloudStore</a>
						</div>
					</div>

					<div class="d-flex align-items-center mb-4">
						<div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
							<i class="fas fa-envelope fa-lg"></i>
						</div>
						<div class="ms-3">
							<small class="text-muted d-block text-uppercase">Email Support</small>
							<a href="mailto:admin@idcloudnest.com" class="fw-bold text-body text-decoration-none">admin@idcloudnest.com</a>
						</div>
					</div>

					<hr class="border-secondary opacity-25 my-4">

					<h6 class="fw-bold text-body mb-3">Lokasi Kantor</h6>
					{{-- Embed Google Map --}}
					<div class="rounded-3 overflow-hidden border border-secondary border-opacity-25" style="height: 200px;">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.448554746201!2d106.816666!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDknMDAuMCJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid"
								width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
				</div>
			</div>
		</div>

		{{-- KOLOM KANAN: FORM KIRIM PESAN --}}
		<div class="col-lg-7 animate-up" style="animation-delay: 200ms;">
			<div class="card h-100 border-0 glass-card">
				<div class="card-body p-4 p-md-5">
					<h5 class="fw-bold text-body mb-2">Kirim Pesan</h5>
					<p class="text-muted small mb-4">Punya kendala transaksi? Isi formulir di bawah, tim kami akan membalas via Email.</p>

					<form action="#" method="POST">
						@csrf
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label small fw-bold text-muted">Nama Lengkap</label>
								<input type="text" class="form-control custom-input" placeholder="Nama Anda" required>
							</div>
							<div class="col-md-6">
								<label class="form-label small fw-bold text-muted">Email / WhatsApp</label>
								<input type="text" class="form-control custom-input" placeholder="Contoh: 0812xxx" required>
							</div>
							<div class="col-12">
								<label class="form-label small fw-bold text-muted">Subjek / ID Transaksi</label>
								<input type="text" class="form-control custom-input" placeholder="Contoh: Komplain INV-123xx" required>
							</div>
							<div class="col-12">
								<label class="form-label small fw-bold text-muted">Pesan</label>
								<textarea class="form-control custom-input" rows="5" placeholder="Jelaskan kendala Anda..." required></textarea>
							</div>
							<div class="col-12 mt-4">
								<button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-primary">
									<i class="fas fa-paper-plane me-2"></i> Kirim Pesan
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
</div>
@endsection

@push('styles')
<style>
	/* Styling Glass Card */
	.glass-card {
		background: var(--card-bg);
		border: 1px solid var(--card-border);
		box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
	}

	/* Input Custom agar sinkron Dark/Light */
	.custom-input {
		background-color: var(--bg-body);
		border: 1px solid var(--card-border);
		color: var(--text-main);
		transition: all 0.3s;
	}
	.custom-input:focus {
		background-color: var(--card-bg);
		border-color: var(--primary-color);
		box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1);
		color: var(--text-main);
	}
	.custom-input::placeholder {
		color: var(--text-muted);
		opacity: 0.5;
	}

	/* Animations */
	.animate-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
	.animate-fade-down { animation: fadeDown 0.8s ease-out forwards; }

	@keyframes fadeUp {
		from { opacity: 0; transform: translateY(30px); }
		to { opacity: 1; transform: translateY(0); }
	}
	@keyframes fadeDown {
		from { opacity: 0; transform: translateY(-30px); }
		to { opacity: 1; transform: translateY(0); }
	}
</style>
@endpush
