@extends('layouts.front')

@section('title', 'Cara Order')

@section('content')
<div class="container py-5">

	{{-- HEADER --}}
	<div class="text-center mb-5 animate-fade-down">
		<div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3 shadow-glow" style="width: 70px; height: 70px;">
			<i class="fas fa-list-ol fa-2x"></i>
		</div>
		<h2 class="fw-bold text-body">Cara Transaksi</h2>
		<p class="text-muted">Panduan mudah melakukan pembelian di Cloud Nest Store.</p>
	</div>

	{{-- STEPS GRID --}}
	<div class="row g-4 position-relative">

		{{-- Garis Penghubung (Hanya muncul di Desktop) --}}
		<div class="d-none d-lg-block position-absolute top-50 start-0 w-100 border-top border-primary border-opacity-25" style="z-index: 0; transform: translateY(-20px);"></div>

		@php
			$steps = [
				[
					'icon' => 'fa-search',
					'title' => 'Pilih Produk',
					'desc' => 'Pilih kategori produk (Pulsa, Game, dll) yang ingin Anda beli di halaman utama.'
				],
				[
					'icon' => 'fa-edit',
					'title' => 'Masukkan Data',
					'desc' => 'Isi Nomor HP, ID Game, atau Nomor Meter PLN dengan benar pada kolom yang tersedia.'
				],
				[
					'icon' => 'fa-wallet',
					'title' => 'Bayar',
					'desc' => 'Pilih metode pembayaran (QRIS, E-Wallet, Transfer Bank) dan selesaikan pembayaran.'
				],
				[
					'icon' => 'fa-check-circle',
					'title' => 'Selesai',
					'desc' => 'Sistem akan memproses otomatis dalam hitungan detik. Bukti transaksi akan muncul.'
				]
			];
		@endphp

		@foreach($steps as $index => $step)
		<div class="col-12 col-md-6 col-lg-3 position-relative z-1 animate-up" style="animation-delay: {{ $index * 150 }}ms">
			<div class="card h-100 border-0 glass-card text-center p-4 hover-lift">
				<div class="step-number bg-primary text-white fw-bold rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
					{{ $index + 1 }}
				</div>
				<div class="mb-3 text-primary">
					<i class="fas {{ $step['icon'] }} fa-3x"></i>
				</div>
				<h5 class="fw-bold text-body">{{ $step['title'] }}</h5>
				<p class="text-muted small mb-0">{{ $step['desc'] }}</p>
			</div>
		</div>
		@endforeach

	</div>

	{{-- FAQ SECTION --}}
	<div class="row justify-content-center mt-5 pt-4">
		<div class="col-lg-8">
			<div class="card glass-card border-0 p-4">
				<h5 class="fw-bold text-body mb-4"><i class="fas fa-question-circle text-warning me-2"></i> Pertanyaan Umum</h5>

				<div class="accordion accordion-flush" id="faqAccordion">
					{{-- FAQ Item 1 --}}
					<div class="accordion-item bg-transparent">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed bg-transparent text-body fw-bold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
								Berapa lama proses transaksi masuk?
							</button>
						</h2>
						<div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
							<div class="accordion-body text-muted small">
								Rata-rata transaksi diproses instan dalam 1-60 detik setelah pembayaran terkonfirmasi. Jika terjadi gangguan, maksimal 1x24 jam.
							</div>
						</div>
					</div>

					{{-- FAQ Item 2 --}}
					<div class="accordion-item bg-transparent">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed bg-transparent text-body fw-bold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
								Bagaimana jika transaksi gagal tapi saldo terpotong?
							</button>
						</h2>
						<div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
							<div class="accordion-body text-muted small">
								Tenang saja, sistem kami otomatis mengembalikan saldo (Refund) ke akun Anda jika transaksi gagal dari sisi operator.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
@endsection

@push('styles')
<style>
	/* Styling Glass Card (Menggunakan Var Layout) */
	.glass-card {
		background: var(--card-bg);
		border: 1px solid var(--card-border);
		box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
		transition: transform 0.3s, border-color 0.3s;
	}

	/* Efek Angkat saat Hover */
	.hover-lift:hover {
		transform: translateY(-10px);
		border-color: var(--primary-color);
	}

	/* Nomor Langkah */
	.step-number {
		width: 40px; height: 40px;
		font-size: 1.2rem;
		box-shadow: 0 0 15px rgba(var(--primary-color), 0.5);
	}

	/* Accordion Custom */
	.accordion-button:not(.collapsed) {
		color: var(--primary-color) !important;
		box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
	}
	.accordion-button::after {
		filter: grayscale(100%); /* Agar panah accordion netral */
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
