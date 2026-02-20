@extends('layouts.front')

@section('title', 'Lacak Pesanan')

@section('content')
<div class="container py-5 d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">

	{{-- JUDUL & ICON --}}
	<div class="text-center mb-5 animate-fade-down">
		<div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3 shadow-glow" style="width: 80px; height: 80px;">
			<i class="fas fa-search-location fa-2x"></i>
		</div>
		{{-- Gunakan text-body agar otomatis Hitam(Light) / Putih(Dark) --}}
		<h2 class="fw-bold text-body">Lacak Pesanan</h2>
		<p class="text-muted">Masukkan Nomor Invoice atau Nomor HP tujuan untuk mengecek status.</p>
	</div>

	{{-- FORM PENCARIAN (GLASS STYLE ADAPTIF) --}}
	<div class="card border-0 shadow-lg mb-5 w-100 glass-card animate-up" style="max-width: 700px;">
		<div class="card-body p-4 p-md-5">

			@if(session('error'))
				<div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger border-0 d-flex align-items-center mb-4 rounded-3 fw-bold">
					<i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
				</div>
			@endif

			<form action="{{ route('pages.invoices') }}" method="GET">
				<div class="form-group mb-0">
					<label class="form-label fw-bold small text-muted text-uppercase ls-1">Nomor Invoice / No HP</label>
					<div class="input-group input-group-lg custom-input-group rounded-3 overflow-hidden">

						{{-- Icon Input --}}
						<span class="input-group-text border-0 bg-transparent text-muted ps-3">
							<i class="fas fa-keyboard"></i>
						</span>

						{{-- Input Field --}}
						<input type="text" name="search"
							   class="form-control border-0 bg-transparent shadow-none fw-bold"
							   placeholder="Contoh: INV-12345xxx"
							   value="{{ $search ?? '' }}"
							   autocomplete="off"
							   required>

						{{-- Tombol Cek --}}
						<button type="submit" class="btn btn-primary fw-bold px-4 py-2 m-1 rounded-3 d-flex align-items-center shadow-primary">
							<i class="fas fa-search me-2"></i> Cek
						</button>
					</div>
					<div class="form-text mt-3 text-muted small">
						<i class="fas fa-info-circle me-1 text-primary"></i> Data transaksi disimpan selama 30 hari.
					</div>
				</div>
			</form>
		</div>
	</div>

	{{-- HASIL PENCARIAN --}}
	@if(isset($transaction))
	<div class="card border-0 shadow-lg w-100 glass-card animate-up" style="max-width: 700px;">
		<div class="card-header bg-transparent border-bottom border-secondary border-opacity-10 py-3">
			<div class="d-flex justify-content-between align-items-center">
				<span class="fw-bold text-muted small">NO. INVOICE</span>
				<span class="fw-bold font-monospace text-primary tracking-wider">{{ $transaction->invoice }}</span>
			</div>
		</div>
		<div class="card-body p-4">

			{{-- Status --}}
			<div class="text-center mb-4">
				@if($transaction->status == 'success')
					<div class="status-icon bg-success bg-opacity-10 text-success mb-3 mx-auto shadow-sm">
						<i class="fas fa-check fa-lg"></i>
					</div>
					<h5 class="fw-bold text-body">Transaksi Berhasil</h5>
					<small class="text-muted">Pesanan telah sukses dikirim.</small>
				@elseif($transaction->status == 'pending')
					<div class="status-icon bg-warning bg-opacity-10 text-warning mb-3 mx-auto shadow-sm">
						<i class="fas fa-spinner fa-spin fa-lg"></i>
					</div>
					<h5 class="fw-bold text-body">Menunggu Proses</h5>
					<small class="text-muted">Sedang dalam antrian sistem.</small>
				@else
					<div class="status-icon bg-danger bg-opacity-10 text-danger mb-3 mx-auto shadow-sm">
						<i class="fas fa-times fa-lg"></i>
					</div>
					<h5 class="fw-bold text-body">Transaksi Gagal</h5>
					<small class="text-muted">Silakan hubungi admin jika saldo terpotong.</small>
				@endif
			</div>

			<hr class="border-secondary opacity-10">

			{{-- Detail Info --}}
			<div class="row g-3 mt-2">
				<div class="col-6 mb-2">
					<small class="text-muted d-block small mb-1 text-uppercase">Produk</small>
					{{-- Text body = Hitam(Light) / Putih(Dark) --}}
					<span class="fw-bold text-body">{{ $transaction->product_name ?? $transaction->product_code }}</span>
				</div>
				<div class="col-6 mb-2 text-end">
					<small class="text-muted d-block small mb-1 text-uppercase">Harga</small>
					<span class="fw-bold text-primary">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
				</div>
				<div class="col-12 mb-2">
					<small class="text-muted d-block small mb-1 text-uppercase">Tujuan</small>
					<span class="fw-bold text-body fs-5 font-monospace">{{ $transaction->target }}</span>
				</div>

				{{-- SN Code --}}
				@if($transaction->status == 'success' && $transaction->sn)
				<div class="col-12 mt-3">
					<div class="p-3 rounded-3 sn-box border border-primary border-opacity-25 position-relative overflow-hidden">
						{{-- Aksen Hiasan --}}
						<div class="position-absolute top-0 start-0 w-1 h-100 bg-primary"></div>

						<small class="text-primary fw-bold d-block mb-1 text-uppercase ls-1">SN / Token / Kode</small>
						<div class="d-flex justify-content-between align-items-center">
							<span class="fw-bold font-monospace text-body text-break me-2" id="snText">{{ $transaction->sn }}</span>
							<button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('snText')">
								<i class="fas fa-copy"></i>
							</button>
						</div>
					</div>
				</div>
				@endif
			</div>

		</div>
		<div class="card-footer bg-transparent border-top border-secondary border-opacity-10 py-3 text-center">
			<small class="text-muted">Waktu: {{ $transaction->created_at->format('d M Y, H:i') }} WIB</small>
		</div>
	</div>
	@endif

</div>
@endsection

@push('styles')
<style>
	/* =========================================
	   THEME AWARE CSS VARIABLES
	   ========================================= */

	/* 1. Default (Light Mode) */
	:root {
		--glass-bg: rgba(255, 255, 255, 0.9); /* Putih Kaca */
		--glass-border: rgba(0, 0, 0, 0.05);
		--input-bg: #f3f4f6; /* Abu-abu terang */
		--input-text: #1f2937;
		--sn-bg: rgba(79, 70, 229, 0.05); /* Biru sangat muda */
	}

	/* 2. Dark Mode Override */
	[data-bs-theme="dark"] {
		/* Biru Malam Kaca */
		/* --glass-bg: rgba(15, 23, 42, 0.6); */
		--glass-bg: rgba(29, 50, 101, 0.6); /* Biru Malam Kaca */
		--glass-border: rgba(255, 255, 255, 0.1);
		--input-bg: rgba(0, 0, 0, 0.3); /* Hitam transparan */
		--input-text: #ffffff;
		--sn-bg: rgba(0, 0, 0, 0.3);
	}

	/* =========================================
	   APPLYING VARIABLES
	   ========================================= */

	/* Kartu Kaca Adaptif */
	.glass-card {
		background: var(--glass-bg);
		backdrop-filter: blur(12px);
		-webkit-backdrop-filter: blur(12px);
		border: 1px solid var(--glass-border);
		box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
		transition: background 0.3s, border 0.3s;
	}

	/* Input Group Adaptif */
	.custom-input-group {
		background-color: var(--input-bg);
		border: 1px solid var(--glass-border);
		transition: background-color 0.3s;
	}

	/* Input Text Color */
	.form-control {
		color: var(--input-text) !important;
	}

	/* Box SN Adaptif */
	.sn-box {
		background-color: var(--sn-bg);
	}

	/* =========================================
	   UTILITIES
	   ========================================= */

	/* Icon Status */
	.status-icon {
		width: 65px; height: 65px;
		border-radius: 50%;
		display: flex; align-items: center; justify-content: center;
		border: 1px solid var(--glass-border);
	}

	/* Glow Effect */
	.shadow-glow {
		box-shadow: 0 0 15px rgba(var(--primary-color-rgb), 0.3);
	}

	.ls-1 { letter-spacing: 1px; }

	/* Animations */
	.animate-up { animation: fadeUp 0.6s ease-out forwards; }
	.animate-fade-down { animation: fadeDown 0.6s ease-out forwards; }

	@keyframes fadeUp {
		from { opacity: 0; transform: translateY(20px); }
		to { opacity: 1; transform: translateY(0); }
	}
	@keyframes fadeDown {
		from { opacity: 0; transform: translateY(-20px); }
		to { opacity: 1; transform: translateY(0); }
	}
</style>
@endpush

@push('scripts')
<script>
	function copyToClipboard(elementId) {
		var copyText = document.getElementById(elementId).innerText;
		navigator.clipboard.writeText(copyText).then(function() {
			alert("Kode berhasil disalin!");
		}, function(err) {
			console.error('Gagal menyalin: ', err);
		});
	}
</script>
@endpush
