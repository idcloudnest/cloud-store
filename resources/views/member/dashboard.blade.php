@extends('layouts.front')

@section('title', 'Dashboard Member')

@section('content')
<div class="container">

	{{-- =========================================
		 1. TOMBOL MENU MOBILE (Hanya Tampil di HP)
		 ========================================= --}}
	<div class="d-lg-none mb-4">
		<div class="card border-0 shadow-sm bg-primary text-white overflow-hidden">
			<div class="card-body p-3 d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center gap-3">
					{{-- Avatar Kecil --}}
					<img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fff&color=6366f1"
						 class="rounded-circle border border-2 border-white" width="40" height="40">
					<div>
						<small class="d-block opacity-75" style="font-size: 0.75rem;">Halo, Selamat Datang</small>
						<h6 class="mb-0 fw-bold">{{ Str::limit(Auth::user()->name, 15) }}</h6>
					</div>
				</div>

				{{-- Tombol Trigger Sidebar --}}
				<button class="btn btn-light btn-sm fw-bold text-primary shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
					<i class="fas fa-bars me-1"></i> Menu
				</button>
			</div>
		</div>
	</div>

	<div class="row g-4">

		{{-- =========================================
			 2. SIDEBAR DESKTOP (Hilang di HP)
			 ========================================= --}}
		<div class="col-lg-3 d-none d-lg-block">
			@include('components.member.sidebar')
		</div>

		{{-- =========================================
			 3. KONTEN UTAMA (Kanan)
			 ========================================= --}}
		<div class="col-lg-9 col-12">

			<div class="row g-4">

				{{-- HERO SALDO --}}
				<div class="col-12">
					<div class="card border-0 shadow-sm overflow-hidden text-white card-balance">
						<div class="card-body p-4 position-relative z-1">
							<div class="d-flex justify-content-between align-items-start">
								<div>
									<small class="opacity-75 text-uppercase fw-bold ls-1">Saldo Aktif</small>
									<h2 class="fw-bold mb-0 mt-1">{{ $user->balance_formatted }}</h2>
									<div class="mt-2 text-white-50 small">
										Level: <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25">{{ strtoupper($user->role) }}</span>
									</div>
								</div>
								<div>
									<a href="#" class="btn btn-light fw-bold text-primary shadow-sm btn-anim px-3 py-2 small-mobile-btn">
										<i class="fas fa-plus-circle me-1"></i> <span class="d-none d-sm-inline">Isi Saldo</span><span class="d-sm-none">Topup</span>
									</a>
								</div>
							</div>

							<div class="mt-4 pt-3 border-top border-white border-opacity-25 d-flex gap-4">
								<div>
									<small class="d-block opacity-75">Pengeluaran</small>
									<span class="fw-bold fs-mobile">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</span>
								</div>
								<div>
									<small class="d-block opacity-75">Limit Kredit</small>
									<span class="fw-bold fs-mobile">Rp {{ number_format($user->credit_limit, 0, ',', '.') }}</span>
								</div>
							</div>
						</div>
						{{-- <div class="position-absolute top-0 end-0 p-3 opacity-10">
							<i class="fas fa-wallet fa-10x"></i>
						</div> --}}
					</div>
				</div>

				{{-- MENU CEPAT --}}
				<div class="col-lg-8">
					<div class="card h-100 shadow-sm border-0">
						<div class="card-header py-3">
							<h6 class="mb-0 fw-bold"><i class="fas fa-grid-2 me-2 text-primary"></i> Produk</h6>
						</div>
						<div class="card-body">
							<div class="row text-center g-3">
								@php
									$menus = [
										['icon' => 'fa-mobile-alt', 'color' => 'danger', 'label' => 'Pulsa'],
										['icon' => 'fa-globe', 'color' => 'success', 'label' => 'Data'],
										['icon' => 'fa-bolt', 'color' => 'warning', 'label' => 'PLN'],
										['icon' => 'fa-wallet', 'color' => 'info', 'label' => 'E-Money'],
										['icon' => 'fa-gamepad', 'color' => 'primary', 'label' => 'Games'],
										['icon' => 'fa-ticket-alt', 'color' => 'secondary', 'label' => 'Voucher'],
									];
								@endphp

								@foreach($menus as $menu)
								<div class="col-4 col-md-4 col-lg-2">
									<a href="{{ route('member.transaction.order', strtolower($menu['label'])) }}" class="text-decoration-none text-body d-block p-2 rounded-3 menu-item btn-anim">
										<div class="icon-box bg-{{ $menu['color'] }} bg-opacity-10 text-{{ $menu['color'] }} mb-2 mx-auto rounded-4 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
											<i class="fas {{ $menu['icon'] }} fa-lg"></i>
										</div>
										<small class="fw-bold d-block" style="font-size: 0.8rem;">{{ $menu['label'] }}</small>
									</a>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>

				{{-- INFO WIDGET --}}
				<div class="col-lg-4">
					<div class="card h-100 shadow-sm border-0">
						<div class="card-header py-3">
							<h6 class="mb-0 fw-bold"><i class="fas fa-bullhorn me-2 text-warning"></i> Info</h6>
						</div>
						<div class="card-body p-0">
							<div class="list-group list-group-flush rounded-bottom-3">
								<div class="list-group-item bg-transparent border-bottom border-secondary border-opacity-10 p-3">
									<span class="badge bg-danger mb-2">Gangguan</span>
									<h6 class="mb-1 text-body small fw-bold lh-base">Open Telkomsel Gangguan</h6>
									<small class="text-muted" style="font-size: 0.75rem;">Hari ini 10:00 WIB</small>
								</div>
								<div class="list-group-item bg-transparent border-0 p-3">
									<span class="badge bg-success mb-2">Promo</span>
									<h6 class="mb-1 text-body small fw-bold lh-base">Diskon Admin PLN!</h6>
									<small class="text-muted" style="font-size: 0.75rem;">Kemarin 14:00 WIB</small>
								</div>
							</div>
						</div>
					</div>
				</div>

				{{-- TABEL TRANSAKSI --}}
				<div class="col-12">
					<div class="card shadow-sm border-0 mb-4">
						<div class="card-header py-3 d-flex justify-content-between align-items-center">
							<h6 class="mb-0 fw-bold">Transaksi</h6>
							<a href="#" class="text-decoration-none small fw-bold">Semua <i class="fas fa-arrow-right ms-1"></i></a>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table align-middle mb-0" style="min-width: 600px;"> {{-- Min-width agar bisa discroll horizontal di HP --}}
									<thead>
										<tr>
											<th class="ps-4 py-3 small text-muted text-uppercase fw-bold">Produk</th>
											<th class="py-3 small text-muted text-uppercase fw-bold">Tujuan</th>
											<th class="py-3 small text-muted text-uppercase fw-bold">Harga</th>
											<th class="py-3 small text-muted text-uppercase text-center fw-bold">Status</th>
											<th class="pe-4 py-3 small text-muted text-uppercase text-end fw-bold">Tanggal</th>
										</tr>
									</thead>
									<tbody>
										@forelse($recentTransactions as $trx)
										<tr>
											<td class="ps-4">
												<span class="fw-bold d-block text-body">{{ $trx->product_code }}</span>
												<small class="text-muted" style="font-size: 0.75rem;">{{ $trx->invoice }}</small>
											</td>
											<td class="text-body">{{ $trx->target }}</td>
											<td class="fw-bold text-body">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
											<td class="text-center">
												@if($trx->status == 'success')
													<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Sukses</span>
												@elseif($trx->status == 'pending')
													<span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Proses</span>
												@else
													<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Gagal</span>
												@endif
											</td>
											<td class="pe-4 text-end text-muted small">
												{{ $trx->created_at->format('d M H:i') }}
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="5" class="text-center py-5 text-muted">
												<div class="mb-2"><i class="fas fa-history fa-2x opacity-50"></i></div>
												<p class="mb-0 small">Belum ada transaksi.</p>
											</td>
										</tr>
										@endforelse
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="offcanvas offcanvas-start border-0" tabindex="-1" id="mobileSidebar" style="background-color: var(--card-bg); color: var(--text-main);">
	<div class="offcanvas-header border-bottom border-secondary border-opacity-10">
		<h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2">
			<div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
				<i class="fa-solid fa-cloud-bolt"></i>
			</div>
			IDCloudStore
		</h5>
		{{-- TOMBOL CLOSE YANG DIPERBAIKI --}}
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">X</button>
	</div>
	<div class="offcanvas-body p-0">
		@include('components.member.sidebar')
	</div>
</div>

@endsection

@push('styles')
<style>
	.card-balance {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 16px;
	}
	.menu-item:hover {
		background-color: rgba(128, 128, 128, 0.05);
	}
	.menu-item:hover .icon-box {
		transform: scale(1.1);
		transition: transform 0.2s;
	}
	.ls-1 { letter-spacing: 1px; }

	/* FIX: Agar tombol X terlihat di Dark Mode */
	[data-bs-theme="dark"] .btn-close {
		filter: invert(1) grayscale(100%) brightness(200%);
	}

	/* CSS Khusus Mobile agar font tidak kebesaran */
	@media (max-width: 576px) {
		.fs-mobile { font-size: 0.9rem; }
		.small-mobile-btn { padding: 6px 12px; font-size: 0.85rem; }
	}
</style>
@endpush

@push('scripts')
	<script>
		$(document).ready(function() {
			// Logic: Saat link menu diklik -> Tutup Sidebar Mobile
			$('#mobileSidebar a').on('click', function() {
				// Cek apakah link tersebut bukan link logout (karena logout butuh submit form)
				// dan bukan dropdown toggle (jika ada)
				if (!$(this).attr('onclick') && !$(this).hasClass('dropdown-toggle')) {
					var offcanvasEl = document.getElementById('mobileSidebar');
					var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
					if (bsOffcanvas) {
						bsOffcanvas.hide();
					}
				}
			});
		});
	</script>
@endpush
