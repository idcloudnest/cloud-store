@extends('layouts.admin')

@section('title', 'Data Produk & SKU')

@push('styles')
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

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
	</style>
@endpush

@section('content')

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

			<button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
				<i class="fa-solid fa-plus me-1"></i> Tambah Produk
			</button>
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
			<div class="table-responsive p-3">
				<table class="table table-hover align-middle mb-0" id="productTable" style="width:100%">
					<thead class="bg-light text-secondary">
						<tr>
							<th class="ps-3 py-3">Produk & SKU</th> <th>Brand</th>                          <th>Harga (Modal vs Jual)</th>          <th>Server/Stok</th>                    <th>Status</th>                         <th class="text-end pe-3">Aksi</th>     </tr>
					</thead>
					<tbody>

						<tr>
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
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
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
	</div>

@endsection

@push('scripts')
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

	<script>
		$(document).ready(function() {
			var table = $('#productTable').DataTable({
				// "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" },
				"dom": 'rtp',
				"pageLength": 10,
				"columnDefs": [ { "orderable": false, "targets": 5 } ], // No sort on Actions
				"order": [[ 1, "asc" ]] // Sort by Brand
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

			// $('#sync-product').click(function () {
			// 	$.ajax({
			// 		url: "{{ route('api.provider.sync-product') }}",
			// 		type: "POST",
			// 		data: { id: id },
			// 		success: function(res) {
			// 			// Animasi angka update
			// 			text.fadeOut(200, function() {
			// 				$(this).text(res.data.balance).fadeIn(200);
			// 			});
			// 			Swal.fire({
			// 				toast: true, position: 'top-end', icon: 'success',
			// 				title: 'Saldo: ' + res.data.balance, showConfirmButton: false, timer: 3000
			// 			});

			// 			lastUpdate(id, res.data.last_update)
			// 		},
			// 		error: function(err) {
			// 			Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Gagal terhubung ke provider' });
			// 		},
			// 		complete: function() {
			// 			btn.html(originalIcon).prop('disabled', false);
			// 		}
			// 	});
			// })

			$(document).on('click', '.btn-sync-trigger', function() {
				let providerId   = $(this).data('id');
				let providerName = $(this).data('name');

				Swal.fire({
					title: 'Sync ' + providerName + '?',
					text: "Produk & Harga akan diperbarui dari server provider ini. Proses mungkin memakan waktu.",
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#1f9d55',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, Mulai Sync',
					cancelButtonText: 'Batal',
					showLoaderOnConfirm: true, // Tampilkan loading spinner otomatis
					preConfirm: () => {
						// Return Promise Ajax
						return $.ajax({
							url: "{{ route('api.provider.sync-product') }}", // Ganti ke route universal
							type: "POST",
							data: {
								provider_id: providerId,
							}
						})
						.then(response => {
							console.log(response);
							if (response?.meta?.code !== 200) {
								throw new Error(response?.meta?.message || 'Terjadi kesalahan!');
							}
							return response;
						})
						.catch(error => {
							console.error(error);

							Swal.showValidationMessage(
								`Request failed: ${error.responseJSON ? error.responseJSON.meta.message : 'Gagal terhubung ke server!'}`
							)
						});
					},
					allowOutsideClick: () => !Swal.isLoading()
				}).then((result) => {
					console.log(result);

					if (result.isConfirmed) {
						Swal.fire({
							title: 'Success!',
							text: result.value?.meta?.message,
							icon: 'success'
						}).then(() => {
							// Reload halaman atau refresh tabel otomatis
							location.reload();
						});
					}
				})
			})
		})
	</script>
@endpush
