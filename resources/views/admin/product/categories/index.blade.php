@extends('layouts.admin')

@section('title', 'Data Kategori')

@section('content')
	{{-- HEADER --}}
	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="fw-bold mb-1">Data Kategori</h4>
			<span class="text-muted small">Kelola kategori dan sub-kategori produk</span>
		</div>
		<div class="d-flex gap-2">
			<div class="d-flex gap-2">
				<button class="btn btn-success btn-sm" id="btn-assign">
					<i class="fa-solid fa-tags me-1"></i> Assign Produk
				</button>

				<button class="btn btn-primary btn-sm" id="btn-create">
					<i class="fa-solid fa-plus me-1"></i> Tambah Kategori
				</button>
			</div>
		</div>
	</div>

	{{-- FILTER (Opsional, disederhanakan) --}}
	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-4">
					<label class="filter-label">Status</label>
					<select id="status-filter" class="form-select" style="text-align-last: center;">
						<option value="">--SEMUA--</option>
						<option value="1">Aktif</option>
						<option value="0">Non-Aktif</option>
					</select>
				</div>
				<div class="col-md-4">
					<button class="btn btn-light w-100 fw-bold border" onclick="window.location.reload()">
						<i class="fa-solid fa-rotate-right me-1"></i> Reset
					</button>
				</div>
			</div>
		</div>
	</div>

	{{-- TABLE --}}
	<div class="card border-0 shadow-sm mb-5">
		<div class="card-body p-0">
			<div class="table-responsive p-3" style="overflow-x: hidden;">
				<table class="table table-hover align-middle mb-0" id="data-table" style="width:100%">
					<thead class="bg-light text-secondary">
						<tr>
							<th>Sort Order</th>
							<th>Nama Kategori</th>
							<th>Slug</th>
							<th>Parent Kategori</th>
							<th>Total SKU</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	{{-- MODAL CREATE/EDIT (Satu Modal untuk Dua Fungsi) --}}
	<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-light">
					<h5 class="modal-title fw-bold" id="modal-title">Tambah Kategori</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="category-form">
					<input type="hidden" id="category_id" name="category_id">

					<div class="modal-body p-4">
						<div class="mb-3">
							<label class="form-label fw-bold small text-uppercase">Nama Kategori <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Elektronik" required>
						</div>

						<div class="mb-3">
							<label class="form-label fw-bold small text-uppercase">Parent Kategori</label>
							<select class="form-select" id="parent-id" name="parent_id">
								<option value="">-- JADIKAN KATEGORI UTAMA --</option>
								@foreach($parents as $parent)
									<option value="{{ $parent->id }}">{{ $parent->name }}</option>
								@endforeach
							</select>
							<small class="text-muted">Kosongkan jika ini adalah kategori induk.</small>
						</div>

						<div class="row">
							<div class="col-md-6">
								<label class="form-label fw-bold small text-uppercase">Urutan</label>
								<input type="number" class="form-control" id="sort-order" name="sort_order" value="0" min="0" required>
								<small class="text-muted" style="font-size: 10px;">Angka kecil tampil duluan.</small>
							</div>

							<div class="col-md-6">
								<label class="form-label fw-bold small text-uppercase">Status</label>
								<select class="form-select" id="status" name="status">
									<option value="1">Aktif</option>
									<option value="0">Non-Aktif</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer border-0 bg-light">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary btn-sm" id="btn-save">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-success text-white">
					<h5 class="modal-title fw-bold"><i class="fa-solid fa-tags me-2"></i>Assign Produk Massal</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<form id="assign-form">
					{{-- @csrf --}}
					<div class="modal-body p-4">
						<div class="mb-4">
							<label class="form-label fw-bold small text-uppercase">Pilih Kategori Tujuan</label>
							{{-- <select class="form-select" id="assign_category_id" name="category_id" required> --}}
							<select class="form-select" id="assign_category_id" name="category_ids[]" multiple required>
								{{-- <option value="">-- PILIH KATEGORI --</option> --}}
								@foreach($parents as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="mb-3">
							<label class="form-label fw-bold small text-uppercase">Cari & Pilih Produk</label>
							<select class="form-select" id="select_products" name="product_ids[]" multiple="multiple" required>
							</select>
							<small class="text-muted">Ketik nama produk atau SKU. Bisa pilih banyak sekaligus.</small>
						</div>
					</div>
					<div class="modal-footer border-0 bg-light">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-success btn-sm" id="btn-save-assign">
							<i class="fa-solid fa-save me-1"></i> Simpan Perubahan
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="showProductsModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-info text-white">
					<h5 class="modal-title fw-bold">
						<i class="fa-solid fa-box-open me-2"></i>Daftar Produk: <span id="view-category-name"></span>
					</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-0">
					<div class="table-responsive p-3">
						<table class="table table-bordered table-hover w-100" id="modal-products-table">
							<thead class="bg-light">
								<tr>
									<th width="5%">No</th>
									<th>Nama Produk</th>
									<th>SKU</th>
									<th>Harga</th>
									<th class="text-center">Status</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

@endsection

@push('styles')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
	<style>
		/* Mengcopy style badge dari file Anda */
		.badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
		.badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }
		.filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }


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
		table.dataTable {
			width: 100% !important;
			margin: 0 auto;
		}
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js" rel="stylesheet"></script>
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js" rel="stylesheet"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function() {
			$('#assign_category_id').select2({
				theme: 'bootstrap-5',
				dropdownParent: $('#assignModal'),
				placeholder: 'Pilih Kategori Tujuan',
				width: '100%'
			});
			$('#select_products').select2({
				theme: 'bootstrap-5',
				dropdownParent: $('#assignModal'),
				placeholder: 'Ketik nama produk...',
				width: '100%',
				allowClear: true,
				ajax: {
					url: "{{ route('admin.products.items.search') }}",
					dataType: 'json',
					delay: 250,
					data: function (params) { return { search: params.term }; },
					processResults: function (data) {
						// Pastikan data array
						var results = Array.isArray(data) ? data : (data.data || []);
						return { results: results };
					},
					cache: true
				},
				// --- FITUR BARU: Custom Template Tampilan ---
				templateResult: function (product) {
					if (product.loading) return product.text;

					const colors = product.category_text == 'Tanpa Kategori' ? 'secondary' : 'info'
					// Tampilan di dalam list dropdown saat mencari
					var $container = $(
						`<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="fw-bold">${product.text}</div>
								<small class="text-muted">SKU: ${product.sku}</small>
							</div>
							<span class="badge bg-${colors} bg-opacity-10 text-${colors}" style="font-size: 0.7rem;">
								${product.category_text}
							</span>
						</div>`
					);
					return $container;
				},
				templateSelection: function (product) {
					// Tampilan setelah dipilih
					if (!product.sku) return product.text;
					return `${product.text} (${product.sku})`;
				}
			})

			var productsTable;

			// EVENT SAAT TOMBOL "LIHAT PRODUK" DIKLIK
			$('body').on('click', '.btn-view-products', function() {
				var catId = $(this).data('id');
				var catName = $(this).data('name');

				// 1. Update Judul Modal
				$('#view-category-name').text(catName);

				// 2. Tampilkan Modal
				$('#showProductsModal').modal('show');

				// 3. Setup URL Endpoint
				// Ganti :id dengan ID kategori yang diklik
				var url = "{{ route('admin.products.categories.products', ':id') }}";
				url = url.replace(':id', catId);

				// 4. Inisialisasi (atau Re-inisialisasi) DataTable
				if ($.fn.DataTable.isDataTable('#modal-products-table')) {
					// Jika tabel sudah pernah dibuat, hancurkan dulu (agar bisa load data baru)
					$('#modal-products-table').DataTable().destroy();
				}

				productsTable = $('#modal-products-table').DataTable({
					processing: true,
					serverSide: true,
					ajax: url, // Load data dari URL khusus kategori ini
					pageLength: 10, // Tampilkan 10 data per halaman modal
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
						{data: 'product_name', name: 'product_name'},
						{data: 'buyer_sku_code', name: 'buyer_sku_code'},
						{data: 'price', name: 'price'},
						{data: 'status', name: 'status', className: 'text-center'},
					],
					// Opsional: Hilangkan search box jika ingin modal lebih bersih
					// dom: 'rtp'
				});
			});

			// --- 3. BUKA MODAL ---
			$('#btn-assign').click(function() {
				$('#assign-form')[0].reset();
				$('#assign_category_id').val('').trigger('change');
				$('#select_products').val(null).trigger('change'); // Reset pilihan produk
				$('#assignModal').modal('show');
			});

			// --- 4. SUBMIT FORM ASSIGN ---
			$('#assign-form').submit(function(e) {
				e.preventDefault();

				// Ambil data
				var formData = $(this).serialize();
				var btn = $('#btn-save-assign');

				// Loading state
				btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...');

				$.ajax({
					url: "{{ route('admin.products.categories.assign') }}",
					type: "POST",
					data: formData,
					success: function(response) {
						$('#assignModal').modal('hide');

						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: response?.meta?.message,
							timer: 2000,
							showConfirmButton: false
						});

						// Reset tombol
						btn.prop('disabled', false).html('<i class="fa-solid fa-save me-1"></i> Simpan Perubahan');
						table.ajax.reload()
					},
					error: function(xhr) {
						btn.prop('disabled', false).text('Simpan Perubahan');
						var msg = 'Gagal menyimpan data.';
						if(xhr.responseJSON && xhr.responseJSON?.meta?.message) msg = xhr.responseJSON.message;
						Swal.fire('Error!', msg, 'error');
					}
				});
			});


			$('#parent-id').select2({
				theme: 'bootstrap-5',
				width: '100%',
				placeholder: '-- JADIKAN KATEGORI UTAMA --',
				dropdownParent: $('#categoryModal'), // Agar dropdown tidak tertutup overflow card/modal
			}).on('select2:open', function() {
				document.querySelector('.select2-search__field').focus();
			})

			// 1. Inisialisasi DataTable
			var table = $('#data-table').DataTable({
				processing: true,
				serverSide: true,
				searchDelay: 500,
				scrollX: true,
				columnDefs: [
					{
						targets: [6],
						orderable: false
					},
					{
						targets: [6],
						searchable: false
					},
					{
						targets: [0,4],
						className: 'text-center'
					},
					{
						targets: [6],
						className: 'fw-bold text-end pe-3'
					},
					{ targets: [0], width: '10%' },
					{ targets: [6], width: '5%' },
					{ targets: [2,3,5], width: '15%' },
				],
				ajax: "{{ route('admin.products.categories.index') }}",
				columns: [
					// {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
					{data: 'sort_order', name: 'sort_order'},
					{data: 'name', name: 'name', className: 'fw-bold'},
					{data: 'slug', name: 'slug', className: 'text-muted small'},
					{data: 'parent_name', name: 'parent.name'},
					{data: 'products_count', name: 'parent.name'},
					{
						data: 'status',
						name: 'status',
						// className: 'text-center',
						// render: function(data) {
						// 	if(data == 'active') return '<span class="badge badge-soft-success">AKTIF</span>';
						// 	return '<span class="badge badge-soft-danger">NON-AKTIF</span>';
						// }
					},
					{data: 'action', name: 'action'},
				]
			});

			// 2. Filter Status Custom
			$('#status-filter').on('change', function() {
				var val = $(this).val();
				// Search di kolom Status (index 4)
				table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
			});

			// 3. Tombol Tambah (Buka Modal Reset)
			$('#btn-create').click(function() {
				$('#category-form')[0].reset(); // Kosongkan form
				$('#parent-id').val('').trigger('change');
				$('#category_id').val(''); // Hapus ID (indikator mode create)
				$('#modal-title').text('Tambah Kategori');
				$('#btn-save').text('Simpan');
				$('#categoryModal').modal('show');
			});

			// 4. Tombol Edit (Ambil Data & Buka Modal)
			$('body').on('click', '.btn-edit', function() {
				var id = $(this).data('id');
				var url = "{{ route('admin.products.categories.show', ':id') }}".replace(':id', id);

				$.get(url, function(data) {
					$('#modal-title').text('Edit Kategori');
					$('#btn-save').text('Update');
					$('#category_id').val(data?.data?.id);
					$('#name').val(data?.data?.name);
					$('#parent-id').val(data?.data?.parent_id).trigger('change'); // Dropdown otomatis terpilih
					$('#status').val(data?.data?.status);
					$('#sort-order').val(data?.data?.sort_order);
					$('#categoryModal').modal('show');
				});
			});

			// 5. Submit Form (Create & Update handled here)
			$('#category-form').submit(function(e) {
				e.preventDefault();
				var id = $('#category_id').val();

				$.ajax({
					url: "{{ route('admin.products.categories.store') }}",
					type: 'POST', // POST atau PUT
					data: $(this).serialize(),
					success: function(response) {
						$('#categoryModal').modal('hide');
						table.ajax.reload(); // Refresh tabel
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 1500,
							showConfirmButton: false
						});
					},
					error: function(xhr) {
						var msg = 'Terjadi kesalahan sistem';
						if(xhr.responseJSON && xhr.responseJSON.message) {
							msg = xhr.responseJSON.message;
						}
						Swal.fire('Gagal!', msg, 'error');
					}
				});
			});

			// 6. Hapus Data
			$('body').on('click', '.btn-delete', function() {
				var id = $(this).data('id');
				var url = "{{ route('admin.products.categories.destroy', ':id') }}".replace(':id', id);

				Swal.fire({
					title: 'Yakin hapus kategori ini?',
					text: "Data tidak bisa dikembalikan!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Ya, Hapus!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: url,
							type: 'DELETE',
							success: function(response) {
								table.ajax.reload();
								Swal.fire('Terhapus!', response.message, 'success');
							},
							error: function(xhr) {
								Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
							}
						});
					}
				});
			});
		});
	</script>
@endpush
