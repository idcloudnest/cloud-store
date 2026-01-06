@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - Cloud Nest Store')

@push('styles')
<style>
	/* Background Decoration (Glowing Blobs) */
	.glow-container { position: relative; z-index: 1; }

	.glow-blob {
		position: absolute;
		width: 300px; height: 300px;
		background: var(--primary-color); filter: blur(90px); opacity: 0.4;
		border-radius: 50%;
		z-index: -1;
		animation: float 3s infinite ease-in-out;
	}

	.blob-1 { top: -50px; left: -100px; }
	.blob-2 { bottom: -50px; right: -50px; background: var(--secondary-color); animation-delay: 2s; }

	@keyframes float {
		0%, 100% { transform: translate(0, 0); }
		50% { transform: translate(30px, 20px); }
	}

	/* Card Customization */
	.policy-card {
		background: var(--card-bg);
		border: 1px solid var(--card-border);
		border-radius: 20px;
		box-shadow: 0 10px 30px rgba(0,0,0,0.1); /* Soft shadow */
		overflow: hidden;
	}

	.policy-header-bg {
		background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
		padding: 3rem 2rem;
		text-align: center;
		border-bottom: 1px solid var(--card-border);
	}

	.policy-body {
		padding: 3rem;
		color: var(--text-muted);
	}

	/* Typography */
	.section-title {
		color: var(--text-main);
		font-weight: 700;
		margin-top: 2.5rem;
		margin-bottom: 1.5rem;
		display: flex;
		align-items: center;
	}

	.section-title::before {
		content: '';
		display: block;
		width: 8px;
		height: 30px;
		background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
		margin-right: 15px;
		border-radius: 4px;
	}

	.sub-title {
		color: var(--text-main);
		font-weight: 600;
		font-size: 1.1rem;
		margin-top: 1.5rem;
		margin-bottom: 0.8rem;
	}

	/* List Customization */
	.policy-list {
		list-style: none;
		padding-left: 0;
	}

	.policy-list li {
		margin-bottom: 12px;
		padding-left: 30px;
		position: relative;
		line-height: 1.6;
	}

	.policy-list li::before {
		content: '\f00c'; /* FontAwesome Check */
		font-family: 'Font Awesome 6 Free';
		font-weight: 900;
		position: absolute;
		left: 0;
		top: 2px;
		color: var(--secondary-color);
		font-size: 0.9rem;
	}

	.policy-list.warning li::before {
		content: '\f071'; /* FontAwesome Warning */
		color: #f59e0b; /* Red for warnings */
	}
	.policy-list.error li::before {
		content: '\f057'; /* FontAwesome Warning */
		color: #ef4444; /* Red for warnings */
	}

	/* Highlight Box */
	.highlight-box {
		background: rgba(99, 102, 241, 0.05);
		border-left: 4px solid var(--primary-color);
		padding: 1.5rem;
		border-radius: 8px;
		margin: 2rem 0;
		color: var(--text-main);
	}

	/* Responsive Adjustments */
	@media (max-width: 768px) {
		.policy-body { padding: 1.5rem; }
		.policy-header-bg { padding: 2rem 1rem; }
	}

	.refund-alert {
		background: rgba(245, 158, 11, 0.1);
		/* border: 1px solid rgba(245, 158, 11, 0.2); */
		/* border-radius: 12px; */
		padding: 1.5rem;
		margin-top: 1rem;

		/* background: rgba(245, 158, 11, 0.05); */
		border: 1px dashed rgba(245, 158, 11, 0.3);
		border-radius: 16px; padding: 1.5rem; margin-top: 1rem;
	}
	.refund-alert h5 { color: #f59e0b; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container mt-5 py-5 glow-container">
	{{-- <div class="glow-blob blob-1 py-5 mt-5"></div>
	<div class="glow-blob blob-2"></div> --}}

	<div class="row justify-content-center">
		<div class="col-lg-10">

			<div class="policy-card" style="text-align: justify;">
				<div class="policy-header-bg">
					<h1 class="display-5 fw-bold text-primary-gradient mb-3">Syarat & Ketentuan</h1>
					<p class="text-muted mb-0" style="font-size: 1.1rem;">
						Harap membaca kebijakan ini dengan saksama sebelum melakukan transaksi di <strong class="text-primary-gradient">Cloud Nest Store</strong>.
					</p>
				</div>

				<div class="policy-body">

					<div class="highlight-box">
						<i class="fas fa-info-circle me-2"></i>
						Dengan mendaftar atau melakukan transaksi, Anda dianggap telah memahami dan menyetujui seluruh isi dalam syarat dan ketentuan di bawah ini.
					</div>

					<h2 class="section-title">1. Syarat Penggunaan</h2>

					<h3 class="sub-title"><i class="fas fa-cube me-2 text-primary"></i> Umum</h3>
					<ul class="policy-list">
						<li>Cloud Nest Store bertindak sebagai perantara antara pembeli dengan penyedia layanan operator (provider).</li>
						<li>Hal-hal seperti gangguan sinyal, gangguan server operator, dan kesalahan input data oleh pengguna berada di luar kendali kami.</li>
					</ul>

					<h3 class="sub-title"><i class="fas fa-box-open me-2 text-primary"></i> Produk Digital</h3>
					<ul class="policy-list">
						<li>Produk yang dijual berupa produk digital (Pulsa, Data, Token, Voucher Game) yang tidak memerlukan pengiriman fisik.</li>
						<li>Harga produk dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya mengikuti kebijakan dari provider pusat.</li>
					</ul>

					<h3 class="sub-title"><i class="fas fa-shopping-cart me-2 text-primary"></i> Pemesanan & Transaksi</h3>
					<ul class="policy-list warning">
						<li>Pengguna wajib memastikan data tujuan (Nomor HP, ID Game, Server ID) yang dimasukkan <strong>sudah benar</strong>.</li>
						<li>Kesalahan penulisan data tujuan yang menyebabkan produk terkirim ke orang lain menjadi <strong>tanggung jawab penuh pengguna</strong>.</li>
						<li>Transaksi yang sudah berstatus <strong>SUKSES</strong> di sistem kami tidak dapat dibatalkan atau ditarik kembali.</li>
					</ul>

					<hr style="border-color: var(--card-border); margin: 3rem 0;">

					<h2 class="section-title">2. Kebijakan Pengembalian Dana</h2>
					<p class="mb-4">Kami memahami kesalahan teknis bisa terjadi. Berikut adalah prosedur <em>Refund Policy</em> kami:</p>

					<div class="row g-4">
						<div class="col-md-12">
							<div class="p-4 rounded-4 refund-alert">
								<h4 class="fw-bold mb-3" style="color: #f59e0b;"><i class="fas fa-exclamation-triangle me-2"></i> Note</h4>
								<ul class="mb-0 p-0" style="font-size: 0.95rem; list-style: none;">
									<li class="mb-2"><strong>PENTING:</strong> Pengembalian dana (Refund) hanya akan diproses ke <strong>SALDO MEMBER</strong> website, bukan ke rekening bank/e-wallet pribadi.</li>
									<li><strong>Nominal Refund</strong> Sesuai dengan harga produk, <strong>tidak termasuk</strong> biaya admin/biaya payment gateway yang sudah terpotong.</li>
								</ul>
							</div>
						</div>

						<div class="col-md-6">
							<div class="p-4 rounded-4 h-100" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
								<h4 class="fw-bold mb-3" style="color: #10b981;"><i class="fas fa-check-circle me-2"></i> Refund Diterima</h4>
								<ul class="policy-list mb-0" style="font-size: 0.95rem;">
									<li><strong>Stok Kosong:</strong> Produk sedang gangguan/habis dari pusat.</li>
									<li><strong>Transaksi Gagal:</strong> Status Gagal tapi saldo terpotong.</li>
									<li><strong>Error Sistem:</strong> Bug yang menyebabkan nominal tidak sesuai.</li>
								</ul>
							</div>
						</div>

						<div class="col-md-6">
							<div class="p-4 rounded-4 h-100" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);">
								<h4 class="fw-bold mb-3" style="color: #ef4444;"><i class="fas fa-times-circle me-2"></i> Refund Ditolak</h4>
								<ul class="policy-list error mb-0" style="font-size: 0.95rem;">
									<li><strong>Kesalahan Data:</strong> Salah ketik ID/Nomor HP.</li>
									<li><strong>Kelalaian Pengguna:</strong> Ketidaktelitian dalam memilih produk atau spesifikasi layanan.</li>
									<li><strong>Produk Sukses:</strong> SN/Bukti topup sudah terbit valid.</li>
								</ul>
							</div>
						</div>
					</div>

					<h3 class="sub-title mt-5"><i class="fas fa-headset me-2 text-primary"></i> Cara Komplain</h3>
					<p>Jika Anda mengalami kendala yang memenuhi syarat refund:</p>
					<ol class="text-muted ms-3">
						<li class="mb-2">Komplain maksimal <strong>1x24 jam</strong> setelah transaksi.</li>
						<li class="mb-2">Hubungi WhatsApp CS kami di <strong class="text-primary-gradient">0895-3208-94991</strong>.</li>
						<li class="mb-2">Sertakan Screenshot bukti transfer dan Nomor Invoice.</li>
						<li>Proses pengecekan estimasi <strong>1-3 hari kerja</strong>.</li>
					</ol>

				</div>
			</div>

		</div>
	</div>
</div>
@endsection
