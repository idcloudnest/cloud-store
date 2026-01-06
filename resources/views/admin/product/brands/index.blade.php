@extends('layouts.admin')

@section('title', 'Data Brand & Kategori')

@section('content')

	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="fw-bold mb-1">Data Brand / Kategori</h4>
			<span class="text-muted small">Atur daftar game dan operator pulsa yang tersedia</span>
		</div>
		<div class="d-flex gap-2">
			<button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addBrandModal">
				<i class="fa-solid fa-plus me-1"></i> Tambah Brand
			</button>
		</div>
	</div>

	<div class="row g-3 mb-4">
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-primary bg-opacity-10 p-3 rounded me-3 text-primary"><i class="fa-solid fa-layer-group fa-xl"></i></div>
					<div><span class="text-muted small text-uppercase fw-bold">Total Brand</span><h4 class="fw-bold mb-0">{{ $brandCount }}</h4></div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-info bg-opacity-10 p-3 rounded me-3 text-info"><i class="fa-solid fa-gamepad fa-xl"></i></div>
					<div><span class="text-muted small text-uppercase fw-bold">Game</span><h4 class="fw-bold mb-0">{{ $gameCount }}</h4></div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-success bg-opacity-10 p-3 rounded me-3 text-success"><i class="fa-solid fa-sim-card fa-xl"></i></div>
					<div><span class="text-muted small text-uppercase fw-bold">Operator Pulsa</span><h4 class="fw-bold mb-0">{{ $operatorCount }}</h4></div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-danger bg-opacity-10 p-3 rounded me-3 text-danger"><i class="fa-solid fa-triangle-exclamation fa-xl"></i></div>
					<div><span class="text-muted small text-uppercase fw-bold">Gangguan</span><h4 class="fw-bold mb-0">2</h4></div>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-5">
					<label class="filter-label">Cari Brand</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
						<input type="text" id="customSearch" class="form-control border-start-0 ps-0" placeholder="Nama Brand (Contoh: Mobile Legends)">
					</div>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Kategori</label>
					<select id="categoryFilter" class="form-select">
						<option value="">Semua Kategori</option>
						<option value="Games">Games</option>
						<option value="Pulsa">Pulsa & Data</option>
						<option value="E-Wallet">E-Wallet</option>
						<option value="PLN">Token PLN</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Status</label>
					<select id="statusFilter" class="form-select">
						<option value="">Semua Status</option>
						<option value="Aktif">Aktif</option>
						<option value="Gangguan">Gangguan</option>
						<option value="Nonaktif">Nonaktif</option>
					</select>
				</div>
				<div class="col-md-1">
					<button class="btn btn-light w-100 border" onclick="window.location.reload()" title="Reset"><i class="fa-solid fa-rotate-right"></i></button>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-5">
		<div class="card-body p-0">
			<div class="table-responsive p-3" style="overflow-x: hidden;">
				<table class="table table-hover align-middle mb-0" id="data-table" style="width:100%">
					<thead class="bg-light text-secondary">
						<tr>
							<th>Brand Info</th>
							<th>Kategori</th>
							<th>Provider</th>
							<th>Total SKU</th>
							<th>Status</th>
							<th class="text-end pe-3">Aksi</th>
						</tr>
					</thead>
					<tbody>
						{{-- <tr>
							<td class="ps-3">
								<div class="d-flex align-items-center">
									<img src="https://play-lh.googleusercontent.com/WMOXe7XqV7189X7964rA24y_74QoH7c6uYw6e6v36Q6g72_1361250280" class="brand-logo me-3">
									<div>
										<div class="brand-name">Mobile Legends</div>
										<div class="brand-slug">/games/mobile-legends</div>
									</div>
								</div>
							</td>
							<td><span class="badge badge-soft-primary rounded-pill px-3">Games</span></td>
							<td><small class="text-muted fw-bold"><i class="fa-solid fa-server me-1"></i> Digiflazz</small></td>
							<td><span class="fw-bold text-dark">45 Produk</span></td>
							<td><span class="badge badge-soft-success rounded-pill px-3">Aktif</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Brand</a></li>
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-list me-2 text-info"></i> Lihat Produk</a></li>
										<li><hr class="dropdown-divider"></li>
										<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td class="ps-3">
								<div class="d-flex align-items-center">
									<img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg" class="brand-logo me-3">
									<div>
										<div class="brand-name">Telkomsel</div>
										<div class="brand-slug">/pulsa/telkomsel</div>
									</div>
								</div>
							</td>
							<td><span class="badge badge-soft-success rounded-pill px-3">Pulsa & Data</span></td>
							<td><small class="text-muted fw-bold"><i class="fa-solid fa-server me-1"></i> Digiflazz</small></td>
							<td><span class="fw-bold text-dark">120 Produk</span></td>
							<td><span class="badge badge-soft-success rounded-pill px-3">Aktif</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Brand</a></li>
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-list me-2 text-info"></i> Lihat Produk</a></li>
										<li><hr class="dropdown-divider"></li>
										<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td class="ps-3">
								<div class="d-flex align-items-center">
									<img src="https://play-lh.googleusercontent.com/83J2f70Q_g9iO_2rZ9b0h6y122247167623" class="brand-logo me-3" style="filter: grayscale(100%); opacity: 0.7;">
									<div>
										<div class="brand-name text-muted">Genshin Impact</div>
										<div class="brand-slug text-muted">/games/genshin-impact</div>
									</div>
								</div>
							</td>
							<td><span class="badge badge-soft-primary rounded-pill px-3">Games</span></td>
							<td><small class="text-muted fw-bold"><i class="fa-solid fa-server me-1"></i> VIPayment</small></td>
							<td><span class="fw-bold text-muted">10 Produk</span></td>
							<td><span class="badge badge-soft-danger rounded-pill px-3">Gangguan</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Brand</a></li>
										<li><a class="dropdown-item text-success" href="#"><i class="fa-solid fa-power-off me-2"></i> Aktifkan</a></li>
									</ul>
								</div>
							</td>
						</tr> --}}
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title fw-bold"><i class="fa-solid fa-tags me-2"></i>Tambah Brand Baru</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-4">
					<form>
						<div class="mb-3">
							<label class="form-label small fw-bold text-muted">Nama Brand</label>
							<input type="text" class="form-control" placeholder="Contoh: Mobile Legends">
						</div>
						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label small fw-bold text-muted">Slug URL</label>
								<input type="text" class="form-control" placeholder="mobile-legends">
							</div>
							<div class="col-md-6">
								<label class="form-label small fw-bold text-muted">Kategori</label>
								<select class="form-select">
									<option value="Games">Games</option>
									<option value="Pulsa">Pulsa</option>
									<option value="PLN">PLN</option>
								</select>
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label small fw-bold text-muted">Server Provider</label>
							<select class="form-select">
								<option value="Digiflazz">Digiflazz</option>
								<option value="VIPayment">VIPayment</option>
								<option value="Manual">Manual Process</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label small fw-bold text-muted">Logo Brand</label>
							<input type="file" class="form-control">
							<small class="text-muted" style="font-size: 0.7rem;">Format: JPG/PNG, Max 2MB. Rasio 1:1.</small>
						</div>

						<div class="mt-4 text-end border-top pt-3">
							<button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Batal</button>
							<button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Brand</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection


@push('styles')
	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"> --}}
	<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css" id="main-style-link">

	<style>
		/* --- STYLES UTILS (Konsisten) --- */
		.badge-soft-primary { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
		.badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
		.badge-soft-warning { background-color: rgba(254, 207, 239, 0.1); color: #d63384; }
		.badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }
		.badge-soft-info { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }
		.badge-soft-dark { background-color: rgba(52, 58, 64, 0.1); color: #343a40; }

		.filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }

		/* --- KHUSUS BRAND --- */
		.brand-logo {
			width: 50px; height: 50px;
			border-radius: 12px; /* Rounded kotak untuk logo game/provider */
			object-fit: contain;
			background-color: #fff;
			border: 1px solid #e9ecef;
			padding: 2px;
		}

		.brand-name { font-weight: 700; color: #2d3436; font-size: 0.95rem; }
		.brand-slug { font-family: 'Courier New', monospace; font-size: 0.8rem; color: #667eea; }



		/* FIX 2: Paksa Tabel untuk Scroll, bukan Melebarkan Halaman */
		div.dataTables_wrapper {
			width: 100%;
			margin: 0 auto;
		}

		/* FIX 3: Pastikan Text Tidak Wrap agar Scroll Muncul */
		#data-table th,
		#data-table td {
			white-space: nowrap;
			vertical-align: middle;
		}

		#data-table th,
		#data-table td {
			white-space: nowrap;
			vertical-align: middle;
		}

		/* Opsional: Perhalus scrollbar */
		div.dataTables_scrollBody::-webkit-scrollbar {
			height: 10px;
		}
		div.dataTables_scrollBody::-webkit-scrollbar-thumb {
			background: #ccc;
			border-radius: 5px;
		}

		/* Trik tambahan: Jika 'static' membuat posisi dropdown agak aneh,
		kita paksa overflow visible HANYA secara vertikal pada body tabel */
		.dataTables_scrollBody {
			overflow-y: visible !important;
			overflow-x: auto !important;
		}

		.dropdown-menu {
			z-index: 1055 !important; /* Lebih tinggi dari Navbar/Modal Bootstrap standar */
		}

		/* Perbaikan visual tombol aksi agar tidak loncat saat diklik */
		.action-btn:focus, .action-btn:active {
			outline: none;
			box-shadow: none;
		}
	</style>
@endpush

@push('scripts')
	{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script> --}}

	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js" rel="stylesheet"></script>
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js" rel="stylesheet"></script>

	<script>
		$(document).ready(function() {
			// var table = $('#brandTable').DataTable({
			// 	"language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" },
			// 	"dom": 'rtp',
			// 	"pageLength": 10,
			// 	"columnDefs": [ { "orderable": false, "targets": 5 } ]
			// });

			var table = $('#data-table').DataTable({
				processing: true, // Tampilkan pesan loading
				serverSide: true, // Aktifkan pengolahan di server (AJAX)
				searchDelay: 500,
				scrollX: true,
				columnDefs: [{
						targets: [5],
						orderable: false
					},
					{
						targets: [5],
						searchable: false
					},
				],
				ajax: "{{ route('admin.products.brands.index') }}", // URL ke Controller tadi
				columns: [
					// "data" harus sesuai dengan nama kolom di database atau nama custom column di controller
					{data: 'name', name: 'name',},
					{data: 'category_list', name: 'category_list',},
					{data: 'provider_name', name: 'provider_name',},
					{data: 'products_count', name: 'products_count',},
					{data: 'status', name: 'status',},
					{data: 'action', name: 'action',},
				]
			});

			// Connect Filters
			$('#customSearch').on('keyup', function() { table.search(this.value).draw(); });
			$('#categoryFilter').on('change', function() { table.column(1).search(this.value).draw(); }); // Index 1: Kategori
			$('#statusFilter').on('change', function() { table.column(4).search(this.value).draw(); }); // Index 4: Status
		});
	</script>
@endpush
