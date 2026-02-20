@extends('layouts.app')

@section('title', 'Invoice #' . $trx->ref_id)

@section('content')
<div class="container mt-4 mb-5">
	<div class="row justify-content-center">
		<div class="col-lg-6">

			<div class="glass-card shadow p-4 text-center">
				{{-- STATUS BADGE --}}
				<div class="mb-4">
					@if($trx->status == 'PAID')
						<i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
						<h4 class="fw-bold mt-3 text-success">Pembayaran Berhasil</h4>
					@elseif($trx->status == 'EXPIRED')
						 <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
						<h4 class="fw-bold mt-3 text-danger">Transaksi Kadaluarsa</h4>
					@else
						<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
						<h4 class="fw-bold mt-3 text-primary">Menunggu Pembayaran</h4>
						<p class="text-muted small">Selesaikan pembayaran sebelum batas waktu habis.</p>
					@endif
				</div>

				{{-- INSTRUKSI PEMBAYARAN --}}
				<div class="alert alert-secondary text-start border-0 bg-opacity-10">
					<div class="d-flex justify-content-between mb-2">
						<span class="text-muted">Total Tagihan</span>
						<span class="fw-bold fs-5">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
					</div>
					<div class="d-flex justify-content-between mb-2">
						<span class="text-muted">Metode Pembayaran</span>
						<span class="fw-bold">{{ $trx->payment_method }}</span>
					</div>
					<div class="d-flex justify-content-between">
						<span class="text-muted">Nomor Invoice</span>
						<span class="fw-bold text-break">{{ $trx->ref_id }}</span>
					</div>
				</div>

				{{-- TAMPILAN KHUSUS VA / QRIS --}}
				@if($trx->status == 'UNPAID')
					<div class="bg-white p-4 rounded mb-4 border">
						@if($trx->qr_string)
							{{-- TAMPILAN QRIS --}}
							<h6 class="text-muted mb-3">Scan QR Code di bawah ini:</h6>
							{{-- Generate QR Code dari String (Pakai Library simple-qrcode) --}}
							<div class="d-flex justify-content-center">
								{!! QrCode::size(200)->generate($trx->qr_string) !!}
							</div>
						@elseif($trx->va_number)
							{{-- TAMPILAN VA --}}
							<h6 class="text-muted mb-2">Nomor Virtual Account:</h6>
							<h2 class="fw-bold text-primary mb-3 text-break" id="vaNumber">{{ $trx->va_number }}</h2>
							<button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('#vaNumber')">
								<i class="fas fa-copy me-1"></i> Salin Nomor
							</button>
						@elseif($trx->payment_url)
							 {{-- LINK PEMBAYARAN (E-Wallet Redirect) --}}
							 <a href="{{ $trx->payment_url }}" target="_blank" class="btn btn-primary w-100">
								<i class="fas fa-external-link-alt me-1"></i> Buka Aplikasi Pembayaran
							 </a>
						@endif
					</div>
				@endif

				<a href="{{ url('/') }}" class="btn btn-link text-muted">Kembali ke Beranda</a>
			</div>

		</div>
	</div>
</div>

<script>
	function copyToClipboard(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
		alert("Nomor VA berhasil disalin!");
	}
</script>
@endsection
