@extends('layouts.admin')

@section('title', 'Data Produk & SKU')

@section('content')
	<div id="main-container">

		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h4 class="fw-bold mb-1">Data Item / SKU</h4>
				<span class="text-muted small">Kelola harga dan stok produk (Digiflazz/VIP)</span>
			</div>
			<div class="d-flex gap-2">
				<div class="dropdown">
					<button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-cloud-arrow-down me-1"></i> Sync Produk
					</button>
					<ul class="dropdown-menu shadow border-0">
						<li><h6 class="dropdown-header small text-uppercase fw-bold">Pilih Provider</h6></li>

						{{-- Loop Provider dari Database --}}
						@foreach(\App\Models\Provider::where(['is_active' => 1, 'code' => 'digiflazz'])->get() as $prov)
							<li>
								<a class="dropdown-item d-flex justify-content-between align-items-center cursor-pointer btn-sync-trigger"
								href="javascript:void(0)"
								data-id="{{ $prov->id }}"
								data-name="{{ $prov->name }}">
									<span>{{ $prov->name }}</span>
									{{-- Ikon kecil sesuai provider (opsional) --}}
									<i class="fa-solid fa-server text-muted small ms-2"></i>
								</a>
							</li>
						@endforeach

						@if(\App\Models\Provider::count() == 0)
							<li><span class="dropdown-item text-muted small">Belum ada provider</span></li>
						@endif
					</ul>
				</div>

				{{-- <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
					<i class="fa-solid fa-plus me-1"></i> Tambah Produk
				</button> --}}
			</div>
			{{-- <div class="d-flex gap-2">
				<button class="btn btn-outline-success btn-sm" id="sync-product"><i class="fa-solid fa-cloud-arrow-down me-1"></i> Sync Produk</button>
				<button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
					<i class="fa-solid fa-plus me-1"></i> Tambah Produk
				</button>
			</div> --}}
		</div>

		<div class="row g-3 mb-4">
			<div class="col-md-3">
				<div class="card border-0 shadow-sm p-3">
					<div class="d-flex align-items-center">
						<div class="bg-primary bg-opacity-10 p-3 rounded me-3 text-primary"><i class="fa-solid fa-box fa-xl"></i></div>
						<div><span class="text-muted small text-uppercase fw-bold">Total SKU</span><h4 class="fw-bold mb-0">1,520</h4></div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card border-0 shadow-sm p-3">
					<div class="d-flex align-items-center">
						<div class="bg-success bg-opacity-10 p-3 rounded me-3 text-success"><i class="fa-solid fa-check-circle fa-xl"></i></div>
						<div><span class="text-muted small text-uppercase fw-bold">Stok Ready</span><h4 class="fw-bold mb-0">1,480</h4></div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card border-0 shadow-sm p-3">
					<div class="d-flex align-items-center">
						<div class="bg-danger bg-opacity-10 p-3 rounded me-3 text-danger"><i class="fa-solid fa-ban fa-xl"></i></div>
						<div><span class="text-muted small text-uppercase fw-bold">Stok Habis</span><h4 class="fw-bold mb-0">35</h4></div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card border-0 shadow-sm p-3">
					<div class="d-flex align-items-center">
						<div class="bg-warning bg-opacity-10 p-3 rounded me-3 text-warning"><i class="fa-solid fa-percent fa-xl"></i></div>
						<div><span class="text-muted small text-uppercase fw-bold">Promo/Flash</span><h4 class="fw-bold mb-0">5</h4></div>
					</div>
				</div>
			</div>
		</div>

		<div class="card border-0 shadow-sm mb-4">
			<div class="card-body">
				<div class="row g-3 align-items-end">
					<div class="col-md-4">
						<label class="filter-label">Cari Produk</label>
						<div class="input-group">
							<span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
							<input type="text" id="productSearch" class="form-control border-start-0 ps-0" placeholder="Nama Produk / Kode SKU...">
						</div>
					</div>
					<div class="col-md-3">
						<label class="filter-label">Brand</label>
						<select id="brandFilter" class="form-select">
							<option value="">Semua Brand</option>
							<option value="Telkomsel">Telkomsel</option>
							<option value="Mobile Legends">Mobile Legends</option>
							<option value="PLN">PLN</option>
						</select>
					</div>
					<div class="col-md-3">
						<label class="filter-label">Status Stok</label>
						<select id="stockFilter" class="form-select">
							<option value="">Semua Status</option>
							<option value="Ready">Ready</option>
							<option value="Habis">Habis / Gangguan</option>
						</select>
					</div>
					<div class="col-md-2">
						<button class="btn btn-light w-100 border" onclick="window.location.reload()"><i class="fa-solid fa-rotate-right me-1"></i> Reset</button>
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
								<th>Produk</th>
								<th>SKU</th>
								<th>Brand</th>
								{{-- <th>Harga Modal</th> --}}
								<th>Harga (Modal vs Jual)</th>
								{{-- <th>Harga Jual</th> --}}
								<th>Category</th>
								<th>Status</th>
								<th>Last Update</th>
								<th class="text-end pe-3">Aksi</th>
								{{-- <th class="text-end pe-3">Aksi</th> --}}
							</tr>
						</thead>
						<tbody>

							{{-- <tr>
								<td class="ps-3">
									<div class="fw-bold text-dark">Telkomsel 5.000</div>
									<span class="sku-code">TS5</span>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg" class="brand-icon-sm me-2">
										<small class="fw-bold text-muted">Telkomsel</small>
									</div>
								</td>
								<td data-order="5800">
									<div class="d-flex flex-column">
										<small class="text-muted" style="font-size: 0.7rem;">Modal: Rp 5.250</small>
										<span class="price-sell">Rp 5.800</span>
										<span class="profit-text"><i class="fa-solid fa-arrow-trend-up me-1"></i>+Rp 550</span>
									</div>
								</td>
								<td>
									<small class="d-block text-muted fw-bold">Digiflazz</small>
									<span class="badge badge-soft-success rounded-pill" style="font-size: 0.65rem;">Stok Aman</span>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" checked>
										<label class="form-check-label small">Aktif</label>
									</div>
								</td>
								<td class="text-end pe-3">
									<div class="dropdown">
										<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
										<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
											<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Harga</a></li>
											<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
										</ul>
									</div>
								</td>
							</tr>

							<tr>
								<td class="ps-3">
									<div class="fw-bold text-dark">MLBB 86 Diamonds</div>
									<div class="d-flex align-items-center gap-2">
										<span class="sku-code">ML86</span>
										<span class="badge bg-warning text-dark" style="font-size: 0.6rem;">PROMO</span>
									</div>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<img src="https://play-lh.googleusercontent.com/WMOXe7XqV7189X7964rA24y_74QoH7c6uYw6e6v36Q6g72_1361250280" class="brand-icon-sm me-2">
										<small class="fw-bold text-muted">Mobile Legends</small>
									</div>
								</td>
								<td data-order="19500">
									<div class="d-flex flex-column">
										<small class="text-muted" style="font-size: 0.7rem;">Modal: Rp 19.000</small>
										<div class="d-flex align-items-center">
											<span class="price-sell text-danger me-1">Rp 19.500</span>
											<small class="text-decoration-line-through text-muted" style="font-size: 0.7rem;">22k</small>
										</div>
										<span class="profit-text text-warning"><i class="fa-solid fa-fire me-1"></i>Tipis (+500)</span>
									</div>
								</td>
								<td>
									<small class="d-block text-muted fw-bold">VIPayment</small>
									<span class="badge badge-soft-success rounded-pill" style="font-size: 0.65rem;">Stok Aman</span>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" checked>
										<label class="form-check-label small">Aktif</label>
									</div>
								</td>
								<td class="text-end pe-3">
									<div class="dropdown">
										<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
										<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
											<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Harga</a></li>
											<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
										</ul>
									</div>
								</td>
							</tr>

							<tr>
								<td class="ps-3">
									<div class="fw-bold text-muted">Token PLN 20.000</div>
									<span class="sku-code text-muted">PLN20</span>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<img src="https://cdn-icons-png.flaticon.com/512/2830/2830303.png" class="brand-icon-sm me-2" style="filter: grayscale(100%);">
										<small class="fw-bold text-muted">PLN</small>
									</div>
								</td>
								<td data-order="20500">
									<div class="d-flex flex-column">
										<small class="text-muted" style="font-size: 0.7rem;">Modal: Rp 20.050</small>
										<span class="price-sell text-muted">Rp 20.500</span>
									</div>
								</td>
								<td>
									<small class="d-block text-muted fw-bold">Digiflazz</small>
									<span class="badge badge-soft-danger rounded-pill" style="font-size: 0.65rem;">Gangguan</span>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" disabled>
										<label class="form-check-label small text-danger">Maint.</label>
									</div>
								</td>
								<td class="text-end pe-3">
									<div class="dropdown">
										<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
										<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
											<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Harga</a></li>
											<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>
										</ul>
									</div>
								</td>
							</tr> --}}

						</tbody>
					</table>
				</div>
			</div>
		</div>

		{{-- <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content border-0 shadow">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title fw-bold">Tambah Produk Baru</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body p-4">
						<form>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Nama Produk</label>
									<input type="text" class="form-control" placeholder="Contoh: Telkomsel 10k">
								</div>
								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Brand / Kategori</label>
									<select class="form-select">
										<option selected>Pilih Brand...</option>
										<option value="1">Telkomsel</option>
										<option value="2">Mobile Legends</option>
									</select>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Server Provider</label>
									<select class="form-select">
										<option value="Digiflazz">Digiflazz</option>
										<option value="VIPayment">VIPayment</option>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Kode SKU Provider</label>
									<input type="text" class="form-control font-monospace" placeholder="Contoh: xld10">
								</div>

								<div class="col-12"><hr class="my-1"></div>

								<div class="col-md-4">
									<label class="form-label small fw-bold text-primary">Harga Modal (Server)</label>
									<div class="input-group">
										<span class="input-group-text bg-primary bg-opacity-10 text-primary fw-bold">Rp</span>
										<input type="number" class="form-control fw-bold text-primary" placeholder="0">
									</div>
								</div>
								<div class="col-md-4">
									<label class="form-label small fw-bold text-success">Harga Jual (Member)</label>
									<div class="input-group">
										<span class="input-group-text bg-success bg-opacity-10 text-success fw-bold">Rp</span>
										<input type="number" class="form-control fw-bold text-success" placeholder="0">
									</div>
								</div>
								<div class="col-md-4">
									<label class="form-label small fw-bold text-info">Harga Jual (Reseller)</label>
									<div class="input-group">
										<span class="input-group-text bg-info bg-opacity-10 text-info fw-bold">Rp</span>
										<input type="number" class="form-control fw-bold text-info" placeholder="0">
									</div>
								</div>

								<div class="col-12">
									<div class="alert alert-light border d-flex justify-content-between align-items-center py-2 px-3 mb-0">
										<small class="text-muted fw-bold">Estimasi Keuntungan:</small>
										<span class="fw-bold text-success">+ Rp 0</span>
									</div>
								</div>
							</div>

							<div class="mt-4 text-end">
								<button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Produk</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div> --}}

		{{-- <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content border-0 shadow">
					<div class="modal-header bg-warning bg-opacity-10">
						<h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Produk</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body p-4">
						<form id="form-edit-product">
							<input type="hidden" id="edit_id" name="id">

							<div class="row g-3">
								<div class="col-12">
									<div class="alert alert-light border d-flex align-items-center mb-0">
										<i class="fa-solid fa-info-circle text-primary me-3 fs-4"></i>
										<div>
											<small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Detail Provider</small>
											<span class="fw-bold text-dark" id="edit_product_name_display">-</span>
											<span class="badge bg-secondary ms-2" id="edit_sku_display">-</span>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Nama Produk (Custom)</label>
									<input type="text" class="form-control" id="edit_product_name" name="product_name">
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Status Produk</label>
									<div class="form-check form-switch pt-2">
										<input class="form-check-input cursor-pointer" type="checkbox" id="edit_seller_product_status" name="seller_product_status">
										<label class="form-check-label fw-bold" for="edit_seller_product_status" id="label_status">Aktif</label>
									</div>
								</div>

								<div class="col-12"><hr class="my-1 border-dashed opacity-50"></div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Harga Modal (Dari Server)</label>
									<div class="input-group">
										<span class="input-group-text bg-light text-muted">Rp</span>
										<input type="number" class="form-control bg-light text-muted fw-bold" id="edit_price" readonly disabled>
									</div>
									<div class="form-text small text-end" style="font-size: 0.7rem;">*Harga modal update otomatis saat sync</div>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-success">Harga Jual</label>
									<div class="input-group">
										<span class="input-group-text bg-success bg-opacity-10 text-success fw-bold">Rp</span>
										<input type="number" class="form-control fw-bold text-success" id="edit_selling_price" name="selling_price" oninput="calculateProfit()">
									</div>
								</div>

								<div class="col-12">
									<div class="d-flex justify-content-between align-items-center p-3 rounded-3" id="profit-box" style="background: #f8f9fa;">
										<span class="small fw-bold text-muted text-uppercase">Estimasi Margin / Keuntungan</span>
										<h5 class="fw-bold mb-0 text-success" id="edit_profit">Rp 0</h5>
									</div>
								</div>
							</div>

							<div class="mt-4 text-end">
								<button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div> --}}

		{{-- <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content border-0 shadow">
					<div class="modal-header bg-warning bg-opacity-10">
						<h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Produk</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body p-4">
						<form id="form-edit-product">
							<input type="hidden" id="edit_id" name="id">

							<div class="row g-3">
								<div class="col-12">
									<div class="alert alert-light border d-flex align-items-center mb-0">
										<i class="fa-solid fa-info-circle text-primary me-3 fs-4"></i>
										<div>
											<small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Detail Provider (Read-Only)</small>
											<span class="fw-bold text-dark" id="edit_display_name">-</span>
											<span class="badge bg-secondary ms-2 font-monospace" id="edit_display_sku">-</span>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Nama Produk (Custom)</label>
									<input type="text" class="form-control" id="edit_product_name" name="product_name" placeholder="Nama tampilan di aplikasi user">
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Status Produk</label>
									<div class="form-check form-switch pt-2">
										<input class="form-check-input cursor-pointer" type="checkbox" id="edit_seller_product_status" name="seller_product_status">
										<label class="form-check-label fw-bold" for="edit_seller_product_status" id="label_status">Aktif</label>
									</div>
								</div>

								<div class="col-12"><hr class="my-1 border-dashed opacity-50"></div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Harga Modal (Otomatis dari Sync)</label>
									<div class="input-group">
										<span class="input-group-text bg-light text-muted">Rp</span>
										<input type="number" class="form-control bg-light text-muted fw-bold" id="edit_price" readonly disabled>
									</div>
									<div class="form-text small text-end" style="font-size: 0.7rem;">*Tidak dapat diedit manual</div>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-success">Harga Jual</label>
									<div class="input-group">
										<span class="input-group-text bg-success bg-opacity-10 text-success fw-bold">Rp</span>
										<input type="number" class="form-control fw-bold text-success" id="edit_selling_price" name="selling_price" oninput="calculateProfit()">
									</div>
								</div>

								<div class="col-12">
									<div class="d-flex justify-content-between align-items-center p-3 rounded-3" id="profit-box" style="background: #f8f9fa;">
										<div>
											<span class="small fw-bold text-muted text-uppercase d-block">Estimasi Margin</span>
											<small class="text-muted" style="font-size: 0.7rem;">(Harga Jual - Harga Modal)</small>
										</div>
										<h4 class="fw-bold mb-0 text-success" id="edit_profit">Rp 0</h4>
									</div>
								</div>
							</div>

							<div class="mt-4 text-end">
								<button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div> --}}
	</div>

	<div id="second-container"></div>
@endsection

@push('styles')
	<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css" id="main-style-link">

	<style>
		/* --- COPY STYLE CONSISTENCY --- */
		.badge-soft-primary { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
		.badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
		.badge-soft-warning { background-color: rgba(254, 207, 239, 0.1); color: #d63384; }
		.badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }
		.filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }

		/* --- PRODUCT SPECIFIC STYLES --- */
		.brand-icon-sm { width: 30px; height: 30px; border-radius: 6px; object-fit: contain; background: #fff; border: 1px solid #eee; }
		.sku-code { font-family: 'Courier New', monospace; font-weight: 700; color: #667eea; background: #f0f2f5; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; }

		.price-modal { font-size: 0.75rem; text-decoration: line-through; color: #adb5bd; }
		.price-sell { font-weight: 700; color: #2d3436; font-size: 0.95rem; }
		.profit-text { font-size: 0.7rem; font-weight: 700; color: #1f9d55; }


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
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js" rel="stylesheet"></script>
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js" rel="stylesheet"></script>

	<script>
		let $formatter
		let $swal
		let table

		$(async () => {
			module = await initModul()
			$formatter = module.formatter
			$swal = module.swal

			table = $('#data-table').DataTable({
				processing: true, // Tampilkan pesan loading
				serverSide: true, // Aktifkan pengolahan di server (AJAX)
				searchDelay: 500,
				scrollX: true,
				stateSave: true,
				columnDefs: [
					{
						targets: [7],
						orderable: false
					},
					{
						targets: [7],
						searchable: false
					},
					{
						targets: [6,7],
						className: 'text-center'
					},
					{ targets: [7], width: '5%' },
					// { targets: [0], width: '12%' },
				],
				ajax: "{{ route('admin.products.items.index') }}", // URL ke Controller tadi
				columns: [
					// "data" harus sesuai dengan nama kolom di database atau nama custom column di controller
					{data: 'product_name', name: 'product_name'},
					{
						data: 'buyer_sku_code',
						name: 'buyer_sku_code',
						// orderData: [8],
					},
					{data: 'brand.name', name: 'brand.name'},
					{data: 'price', name: 'price'},
					{data: 'category.name', name: 'category.name'},
					{data: 'status', name: 'status'},
					{data: 'updated_at', name: 'updated_at'},
					{
						data: 'action',
						name: 'action',
						className: 'fw-bold text-end pe-3'
					},
					// {
					// 	data: 'code_sort',
					// 	name: 'code_sort',
					// 	visible: true,
					// 	searchable: false
					// },
				]
			});

			// Connect Custom Filters
			$('#productSearch').on('keyup', function() { table.search(this.value).draw(); });

			// Filter Brand (Index 1) - Regex Exact Match
			$('#brandFilter').on('change', function() {
				var val = $(this).val();
				// Kita cari text brand di dalam kolom (Telkomsel, dll)
				table.column(1).search(val ? val : '', true, false).draw();
			});

			// Filter Stock (Index 3)
			$('#stockFilter').on('change', function() {
				var val = $(this).val();
				if(val == 'Ready') table.column(3).search('Aman').draw();
				else if(val == 'Habis') table.column(3).search('Gangguan|Habis', true, false).draw();
				else table.column(3).search('').draw();
			});

			$(document).on('click', '.btn-sync-trigger', function() {
				let providerId   = $(this).data('id');
				let providerName = $(this).data('name');

				// 1. Tampilkan Modal Pilihan (Prabayar / Pasca)
				Swal.fire({
					title: `Sync ${providerName}`,
					text: "Pilih jenis produk yang ingin disinkronkan:",
					icon: 'info',
					showCancelButton: true,
					showConfirmButton: false, // Kita pakai tombol custom HTML
					cancelButtonText: 'Batal',
					html: `
						<div class="d-grid gap-2 mt-3">
							<button id="btn-sync-prepaid" class="btn btn-success fw-bold py-2">
								<i class="fa-solid fa-mobile-screen me-2"></i> PRABAYAR (Prepaid)
								<div class="small fw-normal opacity-75">Pulsa, Data, E-Money, Games</div>
							</button>
							<button id="btn-sync-postpaid" class="btn btn-warning text-dark fw-bold py-2">
								<i class="fa-solid fa-file-invoice-dollar me-2"></i> PASCABAYAR (Postpaid)
								<div class="small fw-normal opacity-75">PLN Pasca, PDAM, BPJS, Internet</div>
							</button>
						</div>
					`,
					didOpen: () => {
						// Binding click event ke tombol custom di dalam SweetAlert
						const popup = Swal.getPopup();

						// A. Handler Sync Prabayar
						popup.querySelector('#btn-sync-prepaid').addEventListener('click', () => {
							executeSync(providerId, providerName, 'prepaid');
						});

						// B. Handler Sync Pascabayar
						popup.querySelector('#btn-sync-postpaid').addEventListener('click', () => {
							executeSync(providerId, providerName, 'pasca');
						});
					}
				});
			})

			// Fungsi Terpisah untuk Eksekusi AJAX
			function executeSync(providerId, providerName, type) {
				let labelType = (type === 'prepaid') ? 'PRABAYAR' : 'PASCABAYAR';

				Swal.fire({
					title: `Sync ${labelType}...`,
					html: `Sedang mengambil data <b>${providerName}</b>.<br>Mohon jangan tutup halaman ini.`,
					allowOutsideClick: false,
					allowEscapeKey: false,
					didOpen: () => {
						Swal.showLoading();

						// Eksekusi AJAX
						$.ajax({
							url: "{{ route('api.provider.sync-product') }}",
							type: "POST",
							data: {
								provider_id: providerId,
								type: type // Mengirim parameter 'prabayar' atau 'pascabayar'
							},
							success: function(response) {
								// Cek respon sukses standar API
								if (response?.meta?.code === 200) {
									Swal.fire({
										title: 'Selesai!',
										text: `${response.meta.message}`, // Misal: "Berhasil update 500 produk"
										icon: 'success'
									}).then(() => {
										location.reload(); // Refresh halaman
									});
								} else {
									Swal.fire('Gagal!', response?.meta?.message || 'Terjadi kesalahan logika.', 'error');
								}
							},
							error: function(xhr) {
								let errMsg = xhr.responseJSON?.meta?.message || 'Gagal terhubung ke server provider.';
								Swal.fire('Error Server!', errMsg, 'error');
							}
						});
					}
				});
			}

			// --- LOGIC EDIT PRODUK ---

			// 1. Saat Tombol Edit Diklik (Fetch Data)
			$(document).on('click', '.btn-edit-product', async function() {
				const $this = $(this),
				id = $this.data('id') ?? null,
				{status, data: {data}, data: {meta}} = await postRequest("{{route('admin.products.items.form')}}", {product_id: id});

				// await module.swal.error({text: meta.message})
				// if (status !== 200) return $this.prop('disabled', false);
				if (status !== 200) return $swal.error({text: meta.message});

				$('#main-container').fadeOut(400, async function () {
					await $('#second-container').empty().html(data.content).hide().fadeIn(400)
					$this.prop('disabled', false)
				})

				// let id = $(this).data('id');

				// // Reset Form & UI
				// $('#form-edit-product')[0].reset();
				// $('#edit_profit').text('Rp 0');
				// $('#profit-box').css('background', '#f8f9fa');

				// // Tampilkan Modal
				// $('#editProductModal').modal('show');

				// // Ambil Data dari Server
				// $.ajax({
				// 	// url: "/admin/products/" + id + "/edit", // Pastikan Route ini ada di Laravel
				// 	url: "{{ route('admin.products.items.store') }}", // Pastikan Route ini ada di Laravel
				// 	type: "GET",
				// 	beforeSend: function() {
				// 		$('#edit_product_name').val('Loading...');
				// 	},
				// 	success: function(response) {
				// 		if(response.success) { // Sesuaikan dengan format JSON return controller Anda
				// 			let data = response.data;

				// 			// Isi Data ke Form
				// 			$('#edit_id').val(data.id);

				// 			// Info Read Only
				// 			$('#edit_display_name').text(data.product_name); // Atau data.brand + ' ' + data.name
				// 			$('#edit_display_sku').text(data.buyer_sku_code);
				// 			$('#edit_price').val(data.price); // Modal

				// 			// Input Editable
				// 			$('#edit_product_name').val(data.product_name);
				// 			$('#edit_selling_price').val(data.selling_price);

				// 			// Switch Status (Asumsi di DB: 1 = Aktif, 0 = Nonaktif)
				// 			// Sesuaikan field status di DB Anda (misal: seller_product_status)
				// 			let status = data.seller_product_status == 1 || data.seller_product_status == true;
				// 			$('#edit_seller_product_status').prop('checked', status);
				// 			updateStatusLabel();

				// 			// Hitung Profit Awal
				// 			calculateProfit();
				// 		} else {
				// 			Swal.fire('Error', 'Data tidak ditemukan', 'error');
				// 			$('#editProductModal').modal('hide');
				// 		}
				// 	},
				// 	error: function() {
				// 		Swal.fire('Error', 'Gagal mengambil data produk', 'error');
				// 		$('#editProductModal').modal('hide');
				// 	}
				// });
			});

			// 2. Helper Hitung Profit Realtime
			// window.calculateProfit = function() {
			// 	let modal = parseFloat($('#edit_price').val()) || 0;
			// 	let jual  = parseFloat($('#edit_selling_price').val()) || 0;
			// 	let profit = jual - modal;

			// 	let formatted = 'Rp ' + new Intl.NumberFormat('id-ID').format(profit);

			// 	// Update UI Warna
			// 	let profitEl = $('#edit_profit');
			// 	let boxEl = $('#profit-box');

			// 	profitEl.text((profit > 0 ? '+' : '') + formatted);

			// 	if(profit < 0) {
			// 		profitEl.removeClass('text-success').addClass('text-danger');
			// 		boxEl.css('background', '#fff5f5'); // Merah muda tipis
			// 	} else {
			// 		profitEl.removeClass('text-danger').addClass('text-success');
			// 		boxEl.css('background', '#f0fff4'); // Hijau muda tipis
			// 	}
			// }

			// 3. Helper Status Label Change
			$('#edit_seller_product_status').on('change', function() {
				updateStatusLabel();
			});

			function updateStatusLabel() {
				if($('#edit_seller_product_status').is(':checked')) {
					$('#label_status').text('Aktif').removeClass('text-muted').addClass('text-success');
				} else {
					$('#label_status').text('Nonaktif').removeClass('text-success').addClass('text-muted');
				}
			}

			// 4. Simpan Perubahan (Update)
			$('#form-edit-product').on('submit', function(e) {
				e.preventDefault();
				let id = $('#edit_id').val();
				let formData = $(this).serialize();

				$.ajax({
					url: "{{ route('admin.products.items.store') }}", // Route Update (Method PUT)
					type: "POST", // Browser form submit default POST
					// data: formData + "&_method=PUT&_token={{ csrf_token() }}", // Spoofing PUT & CSRF
					data: formData,
					beforeSend: function() {
						Swal.showLoading();
					},
					success: function(response) {
						if (response.success) { // Sesuaikan key response controller
							$('#editProductModal').modal('hide');
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: 'Data produk berhasil diperbarui',
								timer: 1500,
								showConfirmButton: false
							}).then(() => {
								// Refresh Tabel DataTables
								$('#data-table').DataTable().ajax.reload(null, false); // false = stay on current page
							});
						} else {
							Swal.fire('Gagal', response.message || 'Gagal update data', 'error');
						}
					},
					error: function(xhr) {
						let msg = 'Terjadi kesalahan pada server';
						if(xhr.responseJSON && xhr.responseJSON.message) {
							msg = xhr.responseJSON.message;
						}
						Swal.fire('Error', msg, 'error');
					}
				});
			});
		})
	</script>
@endpush
