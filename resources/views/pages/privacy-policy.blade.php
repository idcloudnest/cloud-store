@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Cloud Nest Store')

@push('styles')
<style>
	/* Menggunakan style yang sama dengan Terms agar konsisten */
	.glow-container { position: relative; z-index: 1; }

	.glow-blob {
		position: absolute;
		width: 300px; height: 300px;
		background: var(--primary-color); filter: blur(90px); opacity: 0.4;
		border-radius: 50%;
		z-index: -1;
		animation: float 3s infinite ease-in-out;
	}

	.blob-1 { top: -50px; right: -100px; } /* Posisi blob dibalik biar variasi */
	.blob-2 { bottom: -50px; left: -50px; background: var(--secondary-color); animation-delay: 2s; }

	@keyframes float {
		0%, 100% { transform: translate(0, 0); }
		50% { transform: translate(-30px, 20px); }
	}

	.policy-card {
		background: var(--card-bg);
		border: 1px solid var(--card-border);
		border-radius: 20px;
		box-shadow: 0 10px 30px rgba(0,0,0,0.1);
		overflow: hidden;
	}

	.policy-header-bg {
		background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(79, 70, 229, 0.05) 100%);
		padding: 3rem 2rem;
		text-align: center;
		border-bottom: 1px solid var(--card-border);
	}

	.section-title {
		color: var(--text-main);
		font-weight: 700;
		margin-top: 2rem;
		margin-bottom: 1rem;
		font-size: 1.25rem;
		display: flex;
		align-items: center;
	}

	.icon-box {
		width: 40px; height: 40px;
		background: rgba(99, 102, 241, 0.1);
		color: var(--primary-color);
		border-radius: 10px;
		display: flex; align-items: center; justify-content: center;
		margin-right: 15px;
	}

	.text-content { color: var(--text-muted); line-height: 1.7; font-size: 0.95rem; }

	/* Alert khusus Refund */
	.refund-alert {
		background: rgba(245, 158, 11, 0.1);
		border: 1px solid rgba(245, 158, 11, 0.2);
		border-radius: 12px;
		padding: 1.5rem;
		margin-top: 1rem;
	}
	.refund-alert h5 { color: #f59e0b; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container mt-5 py-5 glow-container">
	<div class="glow-blob blob-1"></div>
	<div class="glow-blob blob-2"></div>

	<div class="row justify-content-center">
		<div class="col-lg-10">
			<div class="policy-card" style="text-align: justify;">

				<div class="policy-header-bg">
					<h1 class="display-5 fw-bold text-primary-gradient mb-3">Kebijakan Privasi</h1>
					<p class="text-muted mb-0">
						Terakhir diperbarui: {{ date('d F Y') }}
					</p>
				</div>

				<div class="p-4 p-md-5">

					<div class="mb-5">
						<p class="text-content lead">
							Selamat datang di <strong>ID Cloud Store</strong>. Kami berkomitmen untuk melindungi privasi dan keamanan informasi pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda saat Anda menggunakan layanan kami.
						</p>
					</div>

					<div class="row g-4">
						<div class="col-md-6">
							<h2 class="section-title">
								<div class="icon-box"><i class="fas fa-database"></i></div>
								Informasi Dikumpulkan
							</h2>
							<ul class="text-content ps-3">
								<li class="mb-2"><strong>Informasi Pribadi:</strong> Nama, alamat email, nomor telepon (WhatsApp), dan detail pembayaran saat transaksi.</li>
								<li class="mb-2"><strong>Detail Transaksi:</strong> Riwayat pembelian produk, nominal, dan metode pembayaran.</li>
								<li><strong>Data Teknis:</strong> Alamat IP, jenis perangkat, dan browser untuk keperluan log keamanan.</li>
							</ul>
						</div>

						<div class="col-md-6">
							<h2 class="section-title">
								<div class="icon-box"><i class="fas fa-tasks"></i></div>
								Penggunaan Data
							</h2>
							<ul class="text-content ps-3">
								<li class="mb-2">Memproses transaksi top up game dan voucher secara otomatis.</li>
								<li class="mb-2">Menghubungi Anda via WhatsApp/Email untuk konfirmasi status pesanan.</li>
								<li>Mendeteksi dan mencegah aktivitas penipuan demi keamanan bersama.</li>
							</ul>
						</div>
					</div>

					<hr style="border-color: var(--card-border); margin: 3rem 0;">

					<div class="row g-4">
						<div class="col-12">
							<h2 class="section-title">
								<div class="icon-box"><i class="fas fa-shield-alt"></i></div>
								Keamanan & Pembagian Data
							</h2>
							<p class="text-content">
								Kami tidak akan menjual atau membagikan data pribadi Anda kepada pihak ketiga, kecuali kepada penyedia layanan pembayaran (Payment Gateway) untuk memproses transaksi atau jika diwajibkan oleh hukum yang berlaku. Kami menerapkan enkripsi standar industri untuk melindungi data Anda.
							</p>
						</div>
					</div>

					<div class="refund-alert mt-5">
						<div class="d-flex align-items-start">
							<i class="fas fa-exclamation-triangle fa-2x me-3" style="color: #f59e0b;"></i>
							<div>
								<h5>Kebijakan Pengembalian Dana (Refund)</h5>
								<p class="text-muted mb-2" style="font-size: 0.95rem;">
									Refund dapat dilakukan apabila proses top up gagal yang disebabkan oleh gangguan sistem, <em>limit</em> pembelian, atau <em>region lock</em>.
								</p>
								<ul class="mb-0 text-muted ps-3" style="font-size: 0.95rem;">
									<li class="mb-1 text-danger fw-bold">Pengembalian dana hanya diproses ke SALDO MEMBER.</li>
									<li class="mb-1">Kami <strong>TIDAK</strong> melayani refund ke Rekening Bank atau E-Wallet pribadi (Dana/Ovo/Gopay).</li>
									<li>Nominal refund sesuai harga produk, tidak termasuk biaya admin/biaya payment gateway.</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="mt-5">
						<h2 class="section-title">
							<div class="icon-box"><i class="fas fa-user-cog"></i></div>
							Hak & Perubahan
						</h2>
						<p class="text-content">
							Anda berhak mengakses atau memperbarui informasi akun Anda. Kebijakan ini dapat berubah sewaktu-waktu, dan perubahan signifikan akan kami informasikan melalui website.
						</p>
					</div>

					<div class="text-center mt-5 pt-4 border-top" style="border-color: var(--card-border) !important;">
						<p class="text-muted mb-2">Punya pertanyaan tentang privasi data Anda?</p>
						<p class="mb-3">
							<i class="fas fa-envelope me-2"></i> admin@idcloudnest.com &nbsp;|&nbsp;
							<i class="fab fa-whatsapp me-2"></i> +62 895-3208-94991
						</p>
						<small class="text-muted d-block mt-3">
							Terima kasih telah mempercayakan informasi pribadi Anda kepada <strong>ID Cloud Store</strong>.
						</small>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
