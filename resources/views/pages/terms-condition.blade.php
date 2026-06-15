@extends('layouts.front')

@php
	$appName = config('app.name', 'Cloud Nest Store');
	$whatsappNumber = '62895320894991';
@endphp

@section('title', "Syarat & Ketentuan - {$appName}")

@push('styles')
<style>
	.gs-policy-page {
		position: relative;
		isolation: isolate;
		padding: 10px 0 20px;
	}

	.gs-policy-page:before,
	.gs-policy-page:after {
		content: '';
		position: absolute;
		z-index: -1;
		width: 320px;
		height: 320px;
		border-radius: 999px;
		filter: blur(84px);
		opacity: .28;
		pointer-events: none;
	}

	.gs-policy-page:before {
		top: 18px;
		left: -170px;
		background: #00b8ff;
	}

	.gs-policy-page:after {
		right: -180px;
		bottom: 12%;
		background: #ff4248;
	}

	.gs-policy-shell {
		max-width: 1040px;
		margin-inline: auto;
	}

	.gs-policy-hero {
		position: relative;
		overflow: hidden;
		border-radius: 22px;
		padding: clamp(24px, 4vw, 44px);
		background:
			radial-gradient(circle at 88% 18%, rgba(255, 255, 255, .20), transparent 23%),
			linear-gradient(135deg, rgba(9, 91, 190, .98) 0%, rgba(7, 61, 137, .96) 52%, rgba(4, 39, 93, .98) 100%);
		border: 1px solid rgba(255, 255, 255, .16);
		box-shadow: var(--gs-shadow, 0 18px 40px rgba(0, 0, 0, .18));
	}

	.gs-policy-hero:before {
		content: '';
		position: absolute;
		inset: auto -30px -56px auto;
		width: 260px;
		height: 260px;
		border-radius: 50%;
		background: rgba(255, 122, 0, .18);
	}

	.gs-policy-hero:after {
		content: '';
		position: absolute;
		top: 26px;
		right: 30px;
		width: 120px;
		height: 120px;
		border-radius: 36px;
		border: 2px solid rgba(255, 255, 255, .16);
		transform: rotate(10deg);
	}

	.gs-policy-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 9px;
		min-height: 34px;
		padding: 7px 13px;
		border-radius: 999px;
		background: rgba(255, 122, 0, .16);
		border: 1px solid rgba(255, 122, 0, .35);
		color: var(--gs-orange, #ff7a00);
		font-size: .82rem;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: .04em;
	}

	.gs-policy-title {
		margin: 18px 0 12px;
		max-width: 760px;
		font-size: clamp(2rem, 5vw, 3.45rem);
		line-height: 1.03;
		font-weight: 900;
		letter-spacing: -.04em;
		color: #fff;
		text-transform: uppercase;
		text-shadow: 3px 4px 0 rgba(1, 31, 89, .62);
	}

	.gs-policy-title span {
		color: #ffe752;
	}

	.gs-policy-lead {
		max-width: 760px;
		margin: 0;
		color: rgba(255, 255, 255, .78);
		font-size: 1rem;
		line-height: 1.75;
	}

	.gs-policy-hero-actions {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
		margin-top: 26px;
	}

	.gs-policy-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		min-height: 46px;
		padding: 0 20px;
		border-radius: 12px;
		font-weight: 850;
		font-size: .92rem;
		text-decoration: none;
		transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
	}

	.gs-policy-btn-primary {
		background: var(--gs-button, #ff4545);
		color: #fff;
		box-shadow: 0 12px 22px rgba(255, 66, 72, .28);
	}

	.gs-policy-btn-soft {
		background: rgba(255, 255, 255, .10);
		border: 1px solid rgba(255, 255, 255, .20);
		color: #fff;
	}

	.gs-policy-btn:hover {
		color: #fff;
		transform: translateY(-2px);
	}

	.gs-policy-meta-grid {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 14px;
		margin-top: 18px;
	}

	.gs-policy-meta-card {
		padding: 16px;
		border-radius: 16px;
		background: rgba(255, 255, 255, .08);
		border: 1px solid rgba(255, 255, 255, .12);
		backdrop-filter: blur(12px);
	}

	.gs-policy-meta-card i {
		width: 32px;
		height: 32px;
		border-radius: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 10px;
		background: rgba(255, 122, 0, .16);
		color: var(--gs-orange, #ff7a00);
	}

	.gs-policy-meta-card strong {
		display: block;
		color: #fff;
		font-size: .9rem;
		margin-bottom: 4px;
	}

	.gs-policy-meta-card span {
		display: block;
		font-size: .78rem;
		line-height: 1.5;
		color: rgba(255, 255, 255, .66);
	}

	.gs-policy-divider {
		position: relative;
		height: 58px;
		margin: 22px 0 14px;
	}

	.gs-policy-divider:before {
		content: '';
		position: absolute;
		top: 20px;
		left: 0;
		right: 0;
		height: 4px;
		background: var(--gs-red, #ff4248);
		clip-path: polygon(0 0, 36% 0, 40% 100%, 62% 100%, 66% 0, 100% 0, 100% 100%, 66% 100%, 62% 100%, 40% 100%, 36% 100%, 0 100%);
		box-shadow: 0 0 20px rgba(255, 66, 72, .28);
	}

	.gs-policy-alert {
		display: grid;
		grid-template-columns: auto 1fr;
		gap: 14px;
		align-items: flex-start;
		padding: 18px;
		border-radius: 18px;
		background: rgba(255, 122, 0, .11);
		border: 1px solid rgba(255, 122, 0, .28);
		color: rgba(255, 255, 255, .82);
	}

	.gs-policy-alert i {
		width: 38px;
		height: 38px;
		border-radius: 13px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: rgba(255, 122, 0, .18);
		color: var(--gs-orange, #ff7a00);
	}

	.gs-policy-alert strong {
		color: #fff;
	}

	.gs-policy-nav {
		display: grid;
		grid-template-columns: repeat(5, minmax(0, 1fr));
		gap: 12px;
		margin: 18px 0 24px;
	}

	.gs-policy-nav a {
		padding: 13px 12px;
		border-radius: 14px;
		background: rgba(14, 77, 125, .75);
		border: 1px solid rgba(255, 255, 255, .12);
		color: rgba(255, 255, 255, .78);
		font-size: .82rem;
		font-weight: 800;
		text-decoration: none;
		text-align: center;
		transition: transform .2s ease, background .2s ease, color .2s ease;
	}

	.gs-policy-nav a:hover {
		transform: translateY(-2px);
		background: rgba(13, 76, 168, .92);
		color: #fff;
	}

	.gs-policy-section {
		margin-top: 18px;
		padding: clamp(18px, 3vw, 28px);
		border-radius: 20px;
		background:
			linear-gradient(180deg, rgba(14, 77, 125, .96), rgba(8, 59, 103, .96));
		border: 1px solid rgba(255, 255, 255, .13);
		box-shadow: 0 14px 30px rgba(0, 0, 0, .13);
	}

	.gs-policy-section-title {
		display: flex;
		align-items: center;
		gap: 12px;
		margin: 0 0 20px;
		color: #fff;
		font-size: clamp(1.18rem, 3vw, 1.45rem);
		font-weight: 900;
	}

	.gs-policy-section-title .icon {
		width: 42px;
		height: 42px;
		border-radius: 14px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: rgba(255, 122, 0, .16);
		color: var(--gs-orange, #ff7a00);
		box-shadow: inset 0 0 0 1px rgba(255, 122, 0, .18);
	}

	.gs-policy-subtitle {
		display: flex;
		align-items: center;
		gap: 9px;
		margin: 22px 0 12px;
		font-size: 1rem;
		font-weight: 850;
		color: #fff;
	}

	.gs-policy-subtitle i {
		color: #7dd3fc;
	}

	.gs-policy-text,
	.gs-policy-section p,
	.gs-policy-section ol {
		color: rgba(255, 255, 255, .72);
		line-height: 1.75;
	}

	.gs-policy-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.gs-policy-list li {
		position: relative;
		padding: 13px 14px 13px 44px;
		margin-bottom: 10px;
		border-radius: 14px;
		background: rgba(255, 255, 255, .055);
		border: 1px solid rgba(255, 255, 255, .08);
		color: rgba(255, 255, 255, .76);
		line-height: 1.65;
	}

	.gs-policy-list li:before {
		content: '\f00c';
		font-family: 'Font Awesome 6 Free';
		font-weight: 900;
		position: absolute;
		top: 14px;
		left: 14px;
		width: 22px;
		height: 22px;
		border-radius: 8px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: rgba(16, 185, 129, .13);
		color: #34d399;
		font-size: .72rem;
	}

	.gs-policy-list.warning li:before {
		content: '\f071';
		background: rgba(245, 158, 11, .13);
		color: #fbbf24;
	}

	.gs-policy-list.error li:before {
		content: '\f057';
		background: rgba(239, 68, 68, .14);
		color: #fb7185;
	}

	.gs-policy-refund-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 16px;
		margin-top: 18px;
	}

	.gs-policy-refund-card {
		padding: 18px;
		border-radius: 18px;
		border: 1px solid rgba(255, 255, 255, .12);
		background: rgba(255, 255, 255, .06);
	}

	.gs-policy-refund-card.full {
		grid-column: 1 / -1;
		background: rgba(245, 158, 11, .11);
		border-style: dashed;
		border-color: rgba(245, 158, 11, .38);
	}

	.gs-policy-refund-card.accept {
		background: rgba(16, 185, 129, .10);
		border-color: rgba(16, 185, 129, .22);
	}

	.gs-policy-refund-card.reject {
		background: rgba(239, 68, 68, .10);
		border-color: rgba(239, 68, 68, .22);
	}

	.gs-policy-refund-title {
		display: flex;
		align-items: center;
		gap: 10px;
		margin: 0 0 13px;
		font-weight: 900;
		font-size: 1.05rem;
		color: #fff;
	}

	.gs-policy-refund-card.full .gs-policy-refund-title { color: #fbbf24; }
	.gs-policy-refund-card.accept .gs-policy-refund-title { color: #34d399; }
	.gs-policy-refund-card.reject .gs-policy-refund-title { color: #fb7185; }

	.gs-policy-steps {
		counter-reset: step;
		list-style: none;
		padding: 0;
		margin: 14px 0 0;
	}

	.gs-policy-steps li {
		counter-increment: step;
		position: relative;
		padding: 14px 14px 14px 58px;
		margin-bottom: 10px;
		border-radius: 14px;
		background: rgba(255, 255, 255, .055);
		border: 1px solid rgba(255, 255, 255, .08);
		color: rgba(255, 255, 255, .76);
		line-height: 1.65;
	}

	.gs-policy-steps li:before {
		content: counter(step);
		position: absolute;
		left: 14px;
		top: 14px;
		width: 30px;
		height: 30px;
		border-radius: 10px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: var(--gs-orange, #ff7a00);
		color: #fff;
		font-weight: 900;
		font-size: .85rem;
	}

	.gs-policy-bottom-cta {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 18px;
		margin-top: 18px;
		padding: 20px;
		border-radius: 20px;
		background: linear-gradient(135deg, rgba(255, 66, 72, .18), rgba(255, 122, 0, .12));
		border: 1px solid rgba(255, 255, 255, .12);
	}

	.gs-policy-bottom-cta h3 {
		margin: 0 0 4px;
		font-size: 1.05rem;
		font-weight: 900;
		color: #fff;
	}

	.gs-policy-bottom-cta p {
		margin: 0;
		color: rgba(255, 255, 255, .70);
		font-size: .9rem;
	}

	@media (max-width: 991px) {
		.gs-policy-meta-grid { grid-template-columns: 1fr; }
		.gs-policy-nav { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	}

	@media (max-width: 767px) {
		.gs-policy-page { padding-top: 0; }
		.gs-policy-hero { border-radius: 18px; }
		.gs-policy-hero:after { display: none; }
		.gs-policy-refund-grid { grid-template-columns: 1fr; }
		.gs-policy-refund-card.full { grid-column: auto; }
		.gs-policy-bottom-cta { align-items: flex-start; flex-direction: column; }
		.gs-policy-btn { width: 100%; }
	}

	@media (max-width: 520px) {
		.gs-policy-nav { display: flex; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
		.gs-policy-nav::-webkit-scrollbar { display: none; }
		.gs-policy-nav a { min-width: 142px; }
		.gs-policy-alert { grid-template-columns: 1fr; }
	}
</style>
@endpush

@section('content')
<div class="container gs-policy-page">
	<div class="gs-policy-shell">
		<section class="gs-policy-hero">
			<div class="gs-policy-eyebrow">
				<i class="fa-solid fa-shield-halved"></i>
				Legal Policy
			</div>

			<h1 class="gs-policy-title">Syarat & <span>Ketentuan</span></h1>
			<p class="gs-policy-lead">
				Harap membaca kebijakan ini dengan saksama sebelum melakukan transaksi di
				<strong>{{ $appName }}</strong>. Dengan menggunakan layanan kami, Anda dianggap telah memahami dan menyetujui ketentuan yang berlaku.
			</p>

			<div class="gs-policy-hero-actions">
				<a href="{{ url('/') }}" class="gs-policy-btn gs-policy-btn-primary">
					<i class="fa-solid fa-bag-shopping"></i> Mulai Transaksi
				</a>
				<a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" class="gs-policy-btn gs-policy-btn-soft">
					<i class="fa-brands fa-whatsapp"></i> Hubungi CS
				</a>
			</div>

			<div class="gs-policy-meta-grid">
				<div class="gs-policy-meta-card">
					<i class="fa-solid fa-gamepad"></i>
					<strong>Produk Digital</strong>
					<span>Pulsa, data, token, voucher, top up game, dan layanan PPOB.</span>
				</div>
				<div class="gs-policy-meta-card">
					<i class="fa-solid fa-wallet"></i>
					<strong>Refund Saldo Member</strong>
					<span>Pengembalian dana hanya diproses ke saldo akun website.</span>
				</div>
				<div class="gs-policy-meta-card">
					<i class="fa-solid fa-headset"></i>
					<strong>Komplain 1x24 Jam</strong>
					<span>Sertakan invoice dan bukti pembayaran supaya pengecekan lebih cepat.</span>
				</div>
			</div>
		</section>

		<div class="gs-policy-divider" aria-hidden="true"></div>

		<div class="gs-policy-alert">
			<i class="fa-solid fa-circle-info"></i>
			<div>
				<strong>Penting:</strong> Pastikan data tujuan seperti Nomor HP, ID Game, dan Server ID sudah benar sebelum checkout. Produk digital yang sudah sukses tidak dapat dibatalkan.
			</div>
		</div>

		<nav class="gs-policy-nav" aria-label="Navigasi syarat dan ketentuan">
			<a href="#penggunaan">Penggunaan</a>
			<a href="#produk">Produk Digital</a>
			<a href="#transaksi">Transaksi</a>
			<a href="#refund">Refund</a>
			<a href="#komplain">Komplain</a>
		</nav>

		<section class="gs-policy-section" id="penggunaan">
			<h2 class="gs-policy-section-title">
				<span class="icon"><i class="fa-solid fa-user-check"></i></span>
				1. Syarat Penggunaan
			</h2>

			<h3 class="gs-policy-subtitle"><i class="fa-solid fa-cube"></i> Umum</h3>
			<ul class="gs-policy-list">
				<li>{{ $appName }} bertindak sebagai perantara antara pembeli dengan penyedia layanan operator atau provider.</li>
				<li>Gangguan sinyal, gangguan server operator, dan kesalahan input data oleh pengguna berada di luar kendali kami.</li>
			</ul>
		</section>

		<section class="gs-policy-section" id="produk">
			<h2 class="gs-policy-section-title">
				<span class="icon"><i class="fa-solid fa-box-open"></i></span>
				2. Produk Digital
			</h2>

			<ul class="gs-policy-list">
				<li>Produk yang dijual berupa produk digital seperti Pulsa, Data, Token, Voucher Game, dan layanan digital lain yang tidak memerlukan pengiriman fisik.</li>
				<li>Harga produk dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya mengikuti kebijakan provider pusat.</li>
			</ul>
		</section>

		<section class="gs-policy-section" id="transaksi">
			<h2 class="gs-policy-section-title">
				<span class="icon"><i class="fa-solid fa-cart-shopping"></i></span>
				3. Pemesanan & Transaksi
			</h2>

			<ul class="gs-policy-list warning">
				<li>Pengguna wajib memastikan data tujuan seperti Nomor HP, ID Game, dan Server ID yang dimasukkan sudah benar.</li>
				<li>Kesalahan penulisan data tujuan yang menyebabkan produk terkirim ke orang lain menjadi tanggung jawab penuh pengguna.</li>
				<li>Transaksi yang sudah berstatus <strong>SUKSES</strong> di sistem kami tidak dapat dibatalkan atau ditarik kembali.</li>
			</ul>
		</section>

		<section class="gs-policy-section" id="refund">
			<h2 class="gs-policy-section-title">
				<span class="icon"><i class="fa-solid fa-rotate-left"></i></span>
				4. Kebijakan Pengembalian Dana
			</h2>

			<p>
				Kami memahami kendala teknis bisa terjadi. Berikut prosedur <em>Refund Policy</em> yang berlaku di {{ $appName }}.
			</p>

			<div class="gs-policy-refund-grid">
				<div class="gs-policy-refund-card full">
					<h3 class="gs-policy-refund-title"><i class="fa-solid fa-triangle-exclamation"></i> Note</h3>
					<ul class="gs-policy-list warning mb-0">
						<li><strong>Pengembalian dana hanya diproses ke SALDO MEMBER website</strong>, bukan ke rekening bank atau e-wallet pribadi.</li>
						<li><strong>Nominal refund sesuai harga produk</strong>, tidak termasuk biaya admin atau biaya payment gateway yang sudah terpotong.</li>
					</ul>
				</div>

				<div class="gs-policy-refund-card accept">
					<h3 class="gs-policy-refund-title"><i class="fa-solid fa-circle-check"></i> Refund Diterima</h3>
					<ul class="gs-policy-list mb-0">
						<li><strong>Stok kosong:</strong> Produk sedang gangguan atau habis dari pusat.</li>
						<li><strong>Transaksi gagal:</strong> Status gagal tetapi saldo sudah terpotong.</li>
						<li><strong>Error sistem:</strong> Bug yang menyebabkan nominal atau status tidak sesuai.</li>
					</ul>
				</div>

				<div class="gs-policy-refund-card reject">
					<h3 class="gs-policy-refund-title"><i class="fa-solid fa-circle-xmark"></i> Refund Ditolak</h3>
					<ul class="gs-policy-list error mb-0">
						<li><strong>Kesalahan data:</strong> Salah ketik ID Game, Server ID, atau Nomor HP.</li>
						<li><strong>Kelalaian pengguna:</strong> Salah memilih produk atau spesifikasi layanan.</li>
						<li><strong>Produk sukses:</strong> SN atau bukti top up sudah terbit valid.</li>
					</ul>
				</div>
			</div>
		</section>

		<section class="gs-policy-section" id="komplain">
			<h2 class="gs-policy-section-title">
				<span class="icon"><i class="fa-solid fa-headset"></i></span>
				5. Cara Komplain
			</h2>

			<p>Jika Anda mengalami kendala yang memenuhi syarat refund, ikuti langkah berikut:</p>

			<ol class="gs-policy-steps">
				<li>Komplain maksimal <strong>1x24 jam</strong> setelah transaksi.</li>
				<li>Hubungi WhatsApp CS kami di <strong>0895-3208-94991</strong>.</li>
				<li>Sertakan screenshot bukti transfer dan Nomor Invoice.</li>
				<li>Proses pengecekan estimasi <strong>1-3 hari kerja</strong>.</li>
			</ol>
		</section>

		<div class="gs-policy-bottom-cta">
			<div>
				<h3>Masih bingung dengan ketentuan transaksi?</h3>
				<p>Chat CS dulu sebelum checkout supaya pesanan lebih aman.</p>
			</div>
			<a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" class="gs-policy-btn gs-policy-btn-primary">
				<i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
			</a>
		</div>
	</div>
</div>
@endsection
