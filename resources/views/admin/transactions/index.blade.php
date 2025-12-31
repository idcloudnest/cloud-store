{{-- @extends('layouts.admin')

@section('title', 'Data Transaksi')

@push('styles')
	<style>
		/* Soft Badges */
		.badge-soft-primary { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
		.badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
		.badge-soft-warning { background-color: rgba(254, 207, 239, 0.1); color: #d63384; }
		.badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }

		/* Table Styling */
		.table-avatar { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
		.trx-id { font-family: 'Courier New', monospace; font-weight: 700; letter-spacing: 0.5px; }

		/* Filter Card */
		.filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }
	</style>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h4 class="fw-bold mb-1">Riwayat Transaksi</h4>
		<span class="text-muted small">Pantau semua aktivitas penjualan real-time</span>
	</div>
	<div class="d-flex gap-2">
		<button class="btn btn-outline-success btn-sm"><i class="fa-solid fa-file-excel me-1"></i> Export Excel</button>
		<button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Transaksi Manual</button>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-3">
		<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
			<div>
				<span class="text-muted small text-uppercase fw-bold">Omzet Hari Ini</span>
				<h5 class="fw-bold mb-0 text-primary">Rp 1.250.000</h5>
			</div>
			<div class="bg-primary bg-opacity-10 p-2 rounded text-primary"><i class="fa-solid fa-chart-line"></i></div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
			<div>
				<span class="text-muted small text-uppercase fw-bold">Total Trx</span>
				<h5 class="fw-bold mb-0 text-success">142</h5>
			</div>
			<div class="bg-success bg-opacity-10 p-2 rounded text-success"><i class="fa-solid fa-check-double"></i></div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
			<div>
				<span class="text-muted small text-uppercase fw-bold">Pending</span>
				<h5 class="fw-bold mb-0 text-warning">8</h5>
			</div>
			<div class="bg-warning bg-opacity-10 p-2 rounded text-warning"><i class="fa-solid fa-clock"></i></div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
			<div>
				<span class="text-muted small text-uppercase fw-bold">Gagal/Refund</span>
				<h5 class="fw-bold mb-0 text-danger">2</h5>
			</div>
			<div class="bg-danger bg-opacity-10 p-2 rounded text-danger"><i class="fa-solid fa-circle-xmark"></i></div>
		</div>
	</div>
</div>

<div class="card border-0 shadow-sm mb-4">
	<div class="card-body">
		<form action="" method="GET">
			<div class="row g-3 align-items-end">
				<div class="col-md-3">
					<label class="filter-label">Cari Data</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
						<input type="text" class="form-control border-start-0 ps-0" placeholder="TRX ID / No. HP / Username">
					</div>
				</div>
				<div class="col-md-2">
					<label class="filter-label">Status</label>
					<select class="form-select">
						<option value="">Semua Status</option>
						<option value="success">Sukses</option>
						<option value="pending">Pending</option>
						<option value="failed">Gagal</option>
					</select>
				</div>
				<div class="col-md-2">
					<label class="filter-label">Produk</label>
					<select class="form-select">
						<option value="">Semua Kategori</option>
						<option value="games">Games</option>
						<option value="pulsa">Pulsa & Data</option>
						<option value="pln">Token PLN</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Tanggal</label>
					<input type="date" class="form-control">
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-primary w-100 fw-bold">Filter</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card border-0 shadow-sm">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover align-middle mb-0" id="transactionsTable">
				<thead class="bg-light text-secondary">
					<tr>
						<th class="ps-4 py-3">ID Transaksi</th>
						<th>User / Tujuan</th>
						<th>Produk</th>
						<th>Harga</th>
						<th>Tanggal</th>
						<th>Status</th>
						<th class="text-end pe-4">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="ps-4">
							<span class="trx-id text-primary">#TRX-88201</span>
							<div class="small text-muted">Via: BCA</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
									<i class="fa-solid fa-user text-secondary"></i>
								</div>
								<div>
									<div class="fw-bold text-dark">Budi Santoso</div>
									<small class="text-muted d-block">0812-3456-7890</small>
								</div>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<img src="https://cdn-icons-png.flaticon.com/512/3408/3408506.png" class="table-avatar me-2" alt="Icon">
								<div>
									<div class="fw-bold">MLBB 86 Diamonds</div>
									<small class="text-muted">Games</small>
								</div>
							</div>
						</td>
						<td class="fw-bold text-dark">Rp 23.500</td>
						<td class="small text-muted">
							28 Des 2024<br>
							14:30 WIB
						</td>
						<td><span class="badge badge-soft-success px-3 py-2 rounded-pill">Sukses</span></td>
						<td class="text-end pe-4">
							<div class="dropdown">
								<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown">
									<i class="fa-solid fa-ellipsis-vertical"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
									<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-eye me-2 text-primary"></i> Detail</a></li>
									<li><a class="dropdown-item" href="#"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak Struk</a></li>
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i> Batalkan</a></li>
								</ul>
							</div>
						</td>
					</tr>

					<tr>
						<td class="ps-4">
							<span class="trx-id text-primary">#TRX-88202</span>
							<div class="small text-muted">Via: QRIS</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
									<i class="fa-solid fa-user text-secondary"></i>
								</div>
								<div>
									<div class="fw-bold text-dark">Guest User</div>
									<small class="text-muted d-block">0857-9999-1111</small>
								</div>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<img src="https://cdn-icons-png.flaticon.com/512/3616/3616430.png" class="table-avatar me-2" alt="Icon">
								<div>
									<div class="fw-bold">Telkomsel 50k</div>
									<small class="text-muted">Pulsa</small>
								</div>
							</div>
						</td>
						<td class="fw-bold text-dark">Rp 50.500</td>
						<td class="small text-muted">
							28 Des 2024<br>
							14:35 WIB
						</td>
						<td><span class="badge badge-soft-warning px-3 py-2 rounded-pill">Pending</span></td>
						<td class="text-end pe-4">
							<div class="dropdown">
								<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown">
									<i class="fa-solid fa-ellipsis-vertical"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
									<li><a class="dropdown-item" href="#"><i class="fa-solid fa-eye me-2 text-primary"></i> Detail</a></li>
									<li><a class="dropdown-item" href="#"><i class="fa-solid fa-paper-plane me-2 text-success"></i> Cek Provider</a></li>
								</ul>
							</div>
						</td>
					</tr>

					<tr>
						<td class="ps-4">
							<span class="trx-id text-primary">#TRX-88203</span>
							<div class="small text-muted">Via: Saldo</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
									<i class="fa-solid fa-user text-secondary"></i>
								</div>
								<div>
									<div class="fw-bold text-dark">Siti Aminah</div>
									<small class="text-muted d-block">1212334455</small>
								</div>
							</div>
						</td>
						<td>
							<div class="d-flex align-items-center">
								<img src="https://cdn-icons-png.flaticon.com/512/2830/2830303.png" class="table-avatar me-2" alt="Icon">
								<div>
									<div class="fw-bold">Token PLN 20k</div>
									<small class="text-muted">PLN</small>
								</div>
							</div>
						</td>
						<td class="fw-bold text-dark">Rp 20.500</td>
						<td class="small text-muted">
							28 Des 2024<br>
							14:40 WIB
						</td>
						<td><span class="badge badge-soft-danger px-3 py-2 rounded-pill">Gagal</span></td>
						<td class="text-end pe-4">
							<div class="dropdown">
								<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown">
									<i class="fa-solid fa-ellipsis-vertical"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
									<li><a class="dropdown-item" href="#"><i class="fa-solid fa-eye me-2 text-primary"></i> Detail</a></li>
									<li><a class="dropdown-item" href="#"><i class="fa-solid fa-rotate-right me-2 text-warning"></i> Refund Saldo</a></li>
								</ul>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="d-flex justify-content-between align-items-center p-3 border-top">
			<small class="text-muted">Menampilkan 1-3 dari 142 data</small>
			<nav>
				<ul class="pagination pagination-sm mb-0">
					<li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
					<li class="page-item active"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item"><a class="page-link" href="#">Next</a></li>
				</ul>
			</nav>
		</div>
	</div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			<div class="modal-header bg-light">
				<h5 class="modal-title fw-bold">Detail Transaksi</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body p-4">
				<div class="text-center mb-4">
					<div class="badge badge-soft-success px-3 py-2 mb-2 rounded-pill">Transaksi Sukses</div>
					<h3 class="fw-bold text-primary mb-0">Rp 23.500</h3>
					<small class="text-muted">#TRX-88201</small>
				</div>

				<div class="row g-3">
					<div class="col-6">
						<label class="small text-muted fw-bold">Produk</label>
						<div class="fw-bold text-dark">MLBB 86 Diamonds</div>
					</div>
					<div class="col-6 text-end">
						<label class="small text-muted fw-bold">Tanggal</label>
						<div class="fw-bold text-dark">28 Des 2024, 14:30</div>
					</div>
					<div class="col-12 border-bottom pb-3"></div>

					<div class="col-6">
						<label class="small text-muted fw-bold">Tujuan / ID</label>
						<div class="fw-bold text-dark">12345678 (Zone 2022)</div>
						<small class="text-muted">User: Budi Santoso</small>
					</div>
					<div class="col-6 text-end">
						<label class="small text-muted fw-bold">Metode Bayar</label>
						<div class="fw-bold text-dark">BCA Virtual Account</div>
					</div>

					<div class="col-12 mt-4 bg-light p-3 rounded">
						<label class="small text-muted fw-bold d-block mb-1">Serial Number (SN) / Catatan:</label>
						<code class="text-dark d-block">88992211002233 / Topup Sukses via Digiflazz</code>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 bg-light">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary btn-sm"><i class="fa-solid fa-print me-1"></i> Cetak</button>
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#transactionsTable').DataTable({
				"paging": false, // Karena kita pakai pagination manual laravel
				"searching": false, // Kita pakai custom search
				"info": false
			});
		});
	</script>
@endpush --}}

@extends('layouts.admin')

@section('title', 'Data Transaksi')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<style>
	/* Soft Badges */
	.badge-soft-primary { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
	.badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
	.badge-soft-warning { background-color: rgba(254, 207, 239, 0.1); color: #d63384; }
	.badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }

	/* Table Styling */
	.table-avatar { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
	.trx-id { font-family: 'Courier New', monospace; font-weight: 700; letter-spacing: 0.5px; }
	.filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }

	/* Custom Pagination DataTables agar rapi */
	.dataTables_wrapper .dataTables_paginate .paginate_button {
		padding: 0;
		margin-left: 5px;
	}
	.page-item.active .page-link {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}
</style>
@endpush

@section('content')

	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="fw-bold mb-1">Riwayat Transaksi</h4>
			<span class="text-muted small">Pantau semua aktivitas penjualan real-time</span>
		</div>
		<div class="d-flex gap-2">
			<button class="btn btn-outline-success btn-sm"><i class="fa-solid fa-file-excel me-1"></i> Export Excel</button>
			<a href="{{ route('admin.transactions.form') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Transaksi Manual</a>
		</div>
	</div>

	<div class="row g-3 mb-4">
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Omzet Hari Ini</span><h5 class="fw-bold mb-0 text-primary">Rp 1.250.000</h5></div>
				<div class="bg-primary bg-opacity-10 p-2 rounded text-primary"><i class="fa-solid fa-chart-line"></i></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Total Trx</span><h5 class="fw-bold mb-0 text-success">142</h5></div>
				<div class="bg-success bg-opacity-10 p-2 rounded text-success"><i class="fa-solid fa-check-double"></i></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Pending</span><h5 class="fw-bold mb-0 text-warning">8</h5></div>
				<div class="bg-warning bg-opacity-10 p-2 rounded text-warning"><i class="fa-solid fa-clock"></i></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Gagal/Refund</span><h5 class="fw-bold mb-0 text-danger">2</h5></div>
				<div class="bg-danger bg-opacity-10 p-2 rounded text-danger"><i class="fa-solid fa-circle-xmark"></i></div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-4">
					<label class="filter-label">Cari Data</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
						<input type="text" id="customSearch" class="form-control border-start-0 ps-0" placeholder="TRX ID / No. HP / Username...">
					</div>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Status</label>
					<select id="statusFilter" class="form-select">
						<option value="">Semua Status</option>
						<option value="Sukses">Sukses</option>
						<option value="Pending">Pending</option>
						<option value="Gagal">Gagal</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Kategori</label>
					<select id="categoryFilter" class="form-select">
						<option value="">Semua Kategori</option>
						<option value="Games">Games</option>
						<option value="Pulsa">Pulsa</option>
						<option value="PLN">PLN</option>
					</select>
				</div>
				<div class="col-md-2">
					<button class="btn btn-light w-100 fw-bold border" onclick="window.location.reload()"><i class="fa-solid fa-rotate-right me-1"></i> Reset</button>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-5">
		<div class="card-body p-0">
			<div class="table-responsive p-3">
				<table class="table table-hover align-middle mb-0" id="transactionsTable" style="width:100%">
					<thead class="bg-light text-secondary">
						<tr>
							<th class="ps-3 py-3">ID Transaksi</th>
							<th>User / Tujuan</th>
							<th>Produk</th> <th>Harga</th>
							<th>Tanggal</th>
							<th>Status</th>
							<th class="text-end pe-3">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="ps-3">
								<span class="trx-id text-primary">#TRX-88201</span>
								<div class="small text-muted">Via: BCA</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
										<i class="fa-solid fa-user text-secondary"></i>
									</div>
									<div>
										<div class="fw-bold text-dark">Budi Santoso</div>
										<small class="text-muted d-block">0812-3456-7890</small>
									</div>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<img src="https://cdn-icons-png.flaticon.com/512/3408/3408506.png" class="table-avatar me-2" alt="Icon">
									<div>
										<div class="fw-bold">MLBB 86 Diamonds</div>
										<small class="text-muted">Games</small>
									</div>
								</div>
							</td>
							<td class="fw-bold text-dark">Rp 23.500</td>
							<td class="small text-muted" data-order="2024-12-28 14:30"> 28 Des 2024<br>14:30 WIB
							</td>
							<td><span class="badge badge-soft-success px-3 py-2 rounded-pill">Sukses</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-eye me-2 text-primary"></i> Detail</a></li>
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td class="ps-3">
								<span class="trx-id text-primary">#TRX-88202</span>
								<div class="small text-muted">Via: QRIS</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
										<i class="fa-solid fa-user text-secondary"></i>
									</div>
									<div>
										<div class="fw-bold text-dark">Guest User</div>
										<small class="text-muted d-block">0857-9999-1111</small>
									</div>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<img src="https://cdn-icons-png.flaticon.com/512/3616/3616430.png" class="table-avatar me-2" alt="Icon">
									<div>
										<div class="fw-bold">Telkomsel 50k</div>
										<small class="text-muted">Pulsa</small>
									</div>
								</div>
							</td>
							<td class="fw-bold text-dark">Rp 50.500</td>
							<td class="small text-muted" data-order="2024-12-28 14:35">
								28 Des 2024<br>14:35 WIB
							</td>
							<td><span class="badge badge-soft-warning px-3 py-2 rounded-pill">Pending</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-eye me-2 text-primary"></i> Detail</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td class="ps-3">
								<span class="trx-id text-primary">#TRX-88203</span>
								<div class="small text-muted">Via: Saldo</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
										<i class="fa-solid fa-user text-secondary"></i>
									</div>
									<div>
										<div class="fw-bold text-dark">Siti Aminah</div>
										<small class="text-muted d-block">1212334455</small>
									</div>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<img src="https://cdn-icons-png.flaticon.com/512/2830/2830303.png" class="table-avatar me-2" alt="Icon">
									<div>
										<div class="fw-bold">Token PLN 20k</div>
										<small class="text-muted">PLN</small>
									</div>
								</div>
							</td>
							<td class="fw-bold text-dark">Rp 20.500</td>
							<td class="small text-muted" data-order="2024-12-28 14:40">
								28 Des 2024<br>14:40 WIB
							</td>
							<td><span class="badge badge-soft-danger px-3 py-2 rounded-pill">Gagal</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-eye me-2 text-primary"></i> Detail</a></li>
									</ul>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

@endsection

@push('scripts')
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

	<script>
		$(document).ready(function() {
			// --- INISIALISASI DATATABLES ---
			var table = $('#transactionsTable').DataTable({
				"language": {
					"url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" // Bahasa Indonesia
				},
				"dom": 'rtp', // HIDE Default Search & Length (Kita pakai custom filter di atas)
				"pageLength": 10,
				"columnDefs": [
					{ "orderable": false, "targets": 6 } // Matikan sorting di kolom Aksi (index 6)
				],
				"order": [[ 0, "asc" ]] // Default sort berdasarkan Tanggal (index 4)
			});

			// --- LOGIKA CUSTOM FILTER ---

			// 1. Search Bar (Global)
			$('#customSearch').on('keyup', function() {
				table.search(this.value).draw();
			});

			// 2. Filter Status (Kolom index 5)
			$('#statusFilter').on('change', function() {
				var status = $(this).val();
				// Regex search untuk pencocokan tepat
				table.column(5).search(status ? '^' + status + '$' : '', true, false).draw();
			});

			// 3. Filter Kategori (Kolom index 2 - Mengandung teks Games/Pulsa/PLN)
			$('#categoryFilter').on('change', function() {
				table.column(2).search(this.value).draw();
			});
		});
	</script>
@endpush
