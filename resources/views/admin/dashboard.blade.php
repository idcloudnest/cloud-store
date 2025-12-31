@extends('layouts.admin')

@section('title', 'Admin IDCloudStore - Dashboard')

@push('styles')
<style>
	.chart-container { position: relative; height: 300px; width: 100%; }
	.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
	/* .bg-gradient-success { background: linear-gradient(135deg, #2af598 0%, #009efd 100%); } */
	.bg-gradient-warning { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); }
	/* .bg-gradient-info { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); } */
	.chart-container { position: relative; height: 300px; width: 100%; }

	/* PERBAIKAN WARNA GRADIENT (HIGH CONTRAST) */
	/* Biru - Ungu Gelap */
	/* .bg-gradient-primary { background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%); } */
	/* Hijau Zamrud Gelap (Bukan Neon) */
	.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
	/* Oranye - Merah (Supaya teks putih terbaca jelas) */
	/* .bg-gradient-warning { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); } */
	/* Biru Laut - Ungu (Lebih gelap dari sebelumnya) */
	.bg-gradient-info { background: linear-gradient(135deg, #36096d 0%, #37d5d6 100%); }
</style>
@endpush

@section('content')
<div class="row g-3 mb-4">
	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-white bg-gradient-primary h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Saldo Digiflazz</h6>
					<i class="fa-solid fa-wallet fa-lg text-white-50"></i>
				</div>
				<h3 class="fw-bold mb-0">Rp 850.000</h3>
				<small class="text-white-50 fw-bold" style="font-size: 0.75rem;">Cukup untuk ~40 trx</small>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-white bg-gradient-success h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Trx Sukses (Hari Ini)</h6>
					<i class="fa-solid fa-check-circle fa-lg text-white-50"></i>
				</div>
				<h3 class="fw-bold mb-0">842</h3>
				<small class="text-white-50 fw-bold"><i class="fa-solid fa-arrow-up"></i> 12% dari kemarin</small>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-dark bg-gradient-warning h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Trx Pending</h6>
					<i class="fa-solid fa-clock fa-lg opacity-50"></i>
				</div>
				<h3 class="fw-bold mb-0">5</h3>
				<small class="text-danger fw-bold" style="font-size: 0.75rem;">Butuh tindakan segera!</small>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-white bg-gradient-info h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Total Member</h6>
					<i class="fa-solid fa-users fa-lg text-white-50"></i>
				</div>
				<h3 class="fw-bold mb-0">1,240</h3>
				<small class="text-white-50 fw-bold">+15 Member baru hari ini</small>
			</div>
		</div>
	</div>
</div>

{{-- <div class="row g-3 mb-4">
	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-white bg-gradient-primary h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Saldo Digiflazz</h6>
					<i class="fa-solid fa-wallet fa-lg opacity-50"></i>
				</div>
				<h3 class="fw-bold mb-0">Rp 850.000</h3>
				<small class="text-white-50" style="font-size: 0.75rem;">Cukup untuk ~40 trx</small>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-white bg-gradient-success h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Trx Sukses (Hari Ini)</h6>
					<i class="fa-solid fa-check-circle fa-lg opacity-50"></i>
				</div>
				<h3 class="fw-bold mb-0">842</h3>
				<small class="text-white-50"><i class="fa-solid fa-arrow-up"></i> 12% dari kemarin</small>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm bg-gradient-warning h-100" style="color: #444;">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Trx Pending</h6>
					<i class="fa-solid fa-clock fa-lg opacity-50"></i>
				</div>
				<h3 class="fw-bold mb-0">5</h3>
				<small class="text-danger fw-bold" style="font-size: 0.75rem;">Butuh tindakan segera!</small>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-3">
		<div class="card border-0 shadow-sm text-white bg-gradient-info h-100">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="text-uppercase mb-0 opacity-75">Total Member</h6>
					<i class="fa-solid fa-users fa-lg opacity-50"></i>
				</div>
				<h3 class="fw-bold mb-0">1,240</h3>
				<small class="text-white-50">+15 Member baru hari ini</small>
			</div>
		</div>
	</div>
</div> --}}

<div class="row g-3 mb-4">
	<div class="col-lg-8">
		<div class="card shadow-sm border-0 h-100">
			<div class="card-header bg-white py-3">
				<h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Grafik Pendapatan (7 Hari)</h6>
			</div>
			<div class="card-body">
				<div class="chart-container">
					<canvas id="salesChart"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card shadow-sm border-0 h-100">
			<div class="card-header bg-white py-3">
				<h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Produk Terlaris</h6>
			</div>
			<div class="card-body d-flex align-items-center justify-content-center">
				<div style="height: 250px; width: 100%;">
					<canvas id="productsChart"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row g-3 mb-5">
	<div class="col-lg-8">
		<div class="card shadow-sm border-0">
			<div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
				<h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-list me-2 text-primary"></i>10 Transaksi Terakhir</h6>
				<a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="bg-light">
							<tr>
								<th class="ps-4">TRX ID</th>
								<th>Produk</th>
								<th>Tujuan</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="ps-4 fw-bold text-primary">#TRX-9921</td>
								<td><small class="d-block text-muted">Games</small>MLBB 86 Diamond</td>
								<td>12345678</td>
								<td><span class="badge bg-success bg-opacity-10 text-success">Sukses</span></td>
								<td><button class="btn btn-sm btn-light"><i class="fa-solid fa-eye"></i></button></td>
							</tr>
							<tr>
								<td class="ps-4 fw-bold text-primary">#TRX-9922</td>
								<td><small class="d-block text-muted">Pulsa</small>Telkomsel 50k</td>
								<td>081234567890</td>
								<td><span class="badge bg-warning bg-opacity-10 text-warning">Pending</span></td>
								<td><button class="btn btn-sm btn-light"><i class="fa-solid fa-paper-plane text-success"></i></button></td>
							</tr>
							<tr>
								<td class="ps-4 fw-bold text-primary">#TRX-9923</td>
								<td><small class="d-block text-muted">PLN</small>Token 20k</td>
								<td>556677889900</td>
								<td><span class="badge bg-danger bg-opacity-10 text-danger">Gagal</span></td>
								<td><button class="btn btn-sm btn-light"><i class="fa-solid fa-rotate-right text-warning"></i></button></td>
							</tr>
							<tr>
								<td class="ps-4 fw-bold text-primary">#TRX-9924</td>
								<td><small class="d-block text-muted">Games</small>FF 140 DM</td>
								<td>55667788</td>
								<td><span class="badge bg-success bg-opacity-10 text-success">Sukses</span></td>
								<td><button class="btn btn-sm btn-light"><i class="fa-solid fa-eye"></i></button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card shadow-sm border-0">
			<div class="card-header bg-white py-3">
				<h6 class="m-0 fw-bold text-dark"><i class="fa-solid fa-server me-2 text-primary"></i>Status Jalur Provider</h6>
			</div>
			<div class="card-body">
				<ul class="list-group list-group-flush">
					<li class="list-group-item d-flex justify-content-between align-items-center px-0">
						<div class="d-flex align-items-center">
							<span class="badge bg-success rounded-circle p-1 me-2"><span class="visually-hidden">Online</span></span>
							<div>
								<span class="fw-bold d-block">Digiflazz</span>
								<small class="text-muted" style="font-size: 0.75rem;">Koneksi Stabil</small>
							</div>
						</div>
						<small class="text-success fw-bold">98ms</small>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center px-0">
						<div class="d-flex align-items-center">
							<span class="badge bg-success rounded-circle p-1 me-2"><span class="visually-hidden">Online</span></span>
							<div>
								<span class="fw-bold d-block">Vipayment</span>
								<small class="text-muted" style="font-size: 0.75rem;">Koneksi Stabil</small>
							</div>
						</div>
						<small class="text-success fw-bold">112ms</small>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center px-0">
						<div class="d-flex align-items-center">
							<span class="badge bg-danger rounded-circle p-1 me-2"><span class="visually-hidden">Offline</span></span>
							<div>
								<span class="fw-bold d-block">Apigames (Backup)</span>
								<small class="text-danger" style="font-size: 0.75rem;">RTO / Maintenance</small>
							</div>
						</div>
						<small class="text-danger fw-bold">Offline</small>
					</li>
				</ul>
				<div class="mt-3 text-center">
					<button class="btn btn-light btn-sm w-100 border">Cek Koneksi Ulang</button>
				</div>
			</div>
		</div>

		<div class="card shadow-sm border-0 mt-3">
			<div class="card-body">
				<h6 class="fw-bold mb-3">Jalan Pintas</h6>
				<div class="row g-2">
					<div class="col-6">
						<a href="#" class="btn btn-primary w-100 py-2 btn-sm"><i class="fa-solid fa-plus me-1"></i> Deposit</a>
					</div>
					<div class="col-6">
						<a href="#" class="btn btn-outline-dark w-100 py-2 btn-sm"><i class="fa-solid fa-bullhorn me-1"></i> Broadcast</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- <div class="row g-3 mb-4">
	<div class="col-md-4">
		<div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
			<div class="card-body">
				<h6 class="text-uppercase mb-1 opacity-75">Saldo Digiflazz</h6>
				<h3 class="fw-bold">Rp 850.000</h3>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #2af598 0%, #009efd 100%);">
			<div class="card-body">
				<h6 class="text-uppercase mb-1 opacity-75">Transaksi Sukses</h6>
				<h3 class="fw-bold">842</h3>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); color: #333 !important;">
			<div class="card-body">
				<h6 class="text-uppercase mb-1 opacity-75" style="color: #444;">Transaksi Pending</h6>
				<h3 class="fw-bold" style="color: #333;">5</h3>
			</div>
		</div>
	</div>
</div> --}}

{{-- <div class="card shadow border-0 mb-5">
	<div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
		<h6 class="m-0 fw-bold text-primary"><i class="fa-solid fa-list me-2"></i>Data Transaksi Realtime</h6>
		<button class="btn btn-sm btn-success rounded-pill px-3"><i class="fa-solid fa-file-excel me-1"></i> Export</button>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="transaksiTable" class="table table-hover align-middle" style="width:100%">
				<thead>
					<tr>
						<th>TRX ID</th>
						<th>Produk</th>
						<th>Tujuan</th>
						<th>Harga</th>
						<th>Tanggal</th>
						<th>Status</th>
						<th class="text-end">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="fw-bold text-primary">#TRX-9921</td>
						<td>
							<div class="d-flex align-items-center">
								<i class="fa-solid fa-gem text-info me-2"></i>
								<span>MLBB 86 Diamond</span>
							</div>
						</td>
						<td>12345678 (UserA)</td>
						<td>Rp 23.000</td>
						<td class="small text-muted">28 Des, 09:10</td>
						<td><span class="badge badge-soft-success">Sukses</span></td>
						<td class="text-end">
							<button class="btn btn-sm btn-light action-btn" title="Detail"><i class="fa-solid fa-eye text-primary"></i></button>
						</td>
					</tr>
					<tr>
						<td class="fw-bold text-primary">#TRX-9922</td>
						<td>
							<div class="d-flex align-items-center">
								<i class="fa-solid fa-mobile-screen text-danger me-2"></i>
								<span>Telkomsel 50k</span>
							</div>
						</td>
						<td>081234567890</td>
						<td>Rp 50.500</td>
						<td class="small text-muted">28 Des, 09:15</td>
						<td><span class="badge badge-soft-warning">Pending</span></td>
						<td class="text-end">
							<button class="btn btn-sm btn-light action-btn" title="Detail"><i class="fa-solid fa-eye text-primary"></i></button>
							<button class="btn btn-sm btn-light action-btn" title="Proses Ulang"><i class="fa-solid fa-paper-plane text-success"></i></button>
						</td>
					</tr>
					<tr>
						<td class="fw-bold text-primary">#TRX-9923</td>
						<td>
							<div class="d-flex align-items-center">
								<i class="fa-solid fa-bolt text-warning me-2"></i>
								<span>Token PLN 20k</span>
							</div>
						</td>
						<td>14123456789</td>
						<td>Rp 20.500</td>
						<td class="small text-muted">28 Des, 09:20</td>
						<td><span class="badge badge-soft-danger">Gagal</span></td>
						<td class="text-end">
							<button class="btn btn-sm btn-light action-btn" title="Detail"><i class="fa-solid fa-eye text-primary"></i></button>
							<button class="btn btn-sm btn-light action-btn" title="Coba Lagi"><i class="fa-solid fa-rotate-right text-warning"></i></button>
						</td>
					</tr>
					<tr>
						<td class="fw-bold text-primary">#TRX-9924</td>
						<td><div class="d-flex align-items-center"><i class="fa-solid fa-gem text-info me-2"></i><span>FF 140 DM</span></div></td>
						<td>55667788</td>
						<td>Rp 20.000</td>
						<td class="small text-muted">28 Des, 09:30</td>
						<td><span class="badge badge-soft-success">Sukses</span></td>
						<td class="text-end"><button class="btn btn-sm btn-light action-btn"><i class="fa-solid fa-eye text-primary"></i></button></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div> --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// --- 1. SALES CHART CONFIG ---
		const ctxSales = document.getElementById('salesChart').getContext('2d');
		new Chart(ctxSales, {
			type: 'line',
			data: {
				labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
				datasets: [{
					label: 'Pendapatan (Rp)',
					data: [1500000, 2100000, 1800000, 2400000, 2900000, 3500000, 4200000],
					borderColor: '#667eea',
					backgroundColor: 'rgba(102, 126, 234, 0.1)',
					borderWidth: 2,
					tension: 0.4, // Membuat garis melengkung
					fill: true,
					pointBackgroundColor: '#fff',
					pointBorderColor: '#667eea',
					pointRadius: 4
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { display: false }
				},
				scales: {
					y: {
						beginAtZero: true,
						grid: { borderDash: [2, 4], color: '#f0f0f0' },
						ticks: {
							callback: function(value) {
								return 'Rp ' + value.toLocaleString('id-ID'); // Format Rupiah
							}
						}
					},
					x: { grid: { display: false } }
				}
			}
		});

		// --- 2. PRODUCT PIE CHART CONFIG ---
		const ctxProduct = document.getElementById('productsChart').getContext('2d');
		new Chart(ctxProduct, {
			type: 'doughnut',
			data: {
				labels: ['Mobile Legends', 'Free Fire', 'Pulsa', 'PLN', 'Lainnya'],
				datasets: [{
					data: [45, 30, 15, 5, 5], // Persentase Dummy
					backgroundColor: [
					'#667eea', // MLBB (Primary)
					'#ff9a9e', // FF (Warning/Pink)
					'#2af598', // Pulsa (Success/Green)
					'#f6d365', // PLN (Yellow)
					'#e2e2e2'  // Lainnya
					],
					borderWidth: 0,
					hoverOffset: 4
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
				},
				cutout: '70%', // Membuat bolong tengah lebih besar
			}
		});
	});
</script>
@endpush
