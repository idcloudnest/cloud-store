@extends('layouts.admin')

@section('title', 'Data Transaksi')

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
				<div><span class="text-muted small text-uppercase fw-bold">Omzet Bulan Ini</span><h5 class="fw-bold mb-0 text-primary">{{ formatRupiah($omzet) }}</h5></div>
				<div class="bg-primary bg-opacity-10 p-2 rounded text-primary"><i class="fa-solid fa-chart-line"></i></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Total Trx</span><h5 class="fw-bold mb-0 text-success">{{ $trxCount }}</h5></div>
				<div class="bg-success bg-opacity-10 p-2 rounded text-success"><i class="fa-solid fa-check-double"></i></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Pending</span><h5 class="fw-bold mb-0 text-warning">{{ $trxPendingCount }}</h5></div>
				<div class="bg-warning bg-opacity-10 p-2 rounded text-warning"><i class="fa-solid fa-clock"></i></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
				<div><span class="text-muted small text-uppercase fw-bold">Gagal/Refund</span><h5 class="fw-bold mb-0 text-danger">{{ $trxFailedCount }}</h5></div>
				<div class="bg-danger bg-opacity-10 p-2 rounded text-danger"><i class="fa-solid fa-circle-xmark"></i></div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-3">
					<label class="filter-label">Payment Status</label>
					<select id="payment-status-filter" class="form-select" style="text-align-last: center;">
						<option value="">--SEMUA--</option>
						<option value="unpaid">Unpaid</option>
						<option value="paid">Paid</option>
						<option value="expired">Expired</option>
						<option value="refunded">Refunded</option>
						<option value="failed">Failed</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Delivery Status</label>
					<select id="delivery-status-filter" class="form-select" style="text-align-last: center;">
						<option value="">--SEMUA--</option>
						<option value="success">Success</option>
						<option value="pending">Pending</option>
						<option value="processing">Processing</option>
						<option value="failed">Failed</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Kategori</label>
					<select id="category-filter" class="form-select" style="text-align-last: center;">
						<option value="">--SEMUA--</option>
						@if (count($categories))
							@foreach ($categories as $category)
								<option value="{{ $category->name }}">{{ strtoupper($category->name) }}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="col-md-3">
					<button class="btn btn-light w-100 fw-bold border" onclick="window.location.reload()"><i class="fa-solid fa-rotate-right me-1"></i> Reset</button>
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
							{{-- <th class="ps-3 py-3">ID Transaksi</th> --}}
							<th>ID Transaksi</th>
							<th>User / Tujuan</th>
							<th>Produk</th>
							<th>Kategori</th>
							<th>Harga</th>
							<th>Tanggal</th>
							<th>Status Pembayaran</th>
							<th>Status Delivery</th>
							<th class="text-end pe-3">Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	{{-- MODAL DETAIL TRANSAKSI --}}
	<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-light">
					<h5 class="modal-title fw-bold">Detail Transaksi</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-4">
					{{-- Loading Spinner (Default visible) --}}
					<div id="modal-loader" class="text-center py-5">
						<div class="spinner-border text-primary" role="status"></div>
						<p class="small text-muted mt-2">Memuat data...</p>
					</div>

					{{-- Content (Default hidden) --}}
					<div id="modal-content" class="d-none">
						<div class="text-center mb-4">
							<div id="modal-status-badge" class="badge px-3 py-2 mb-2 rounded-pill"></div>
							<h3 class="fw-bold text-primary mb-0" id="modal-amount"></h3>
							<small class="text-muted trx-id" id="modal-invoice"></small>
						</div>

						<div class="row g-3">
							<div class="col-6">
								<label class="small text-muted fw-bold text-uppercase">Produk</label>
								<div class="fw-bold text-dark" id="modal-product"></div>
								<small class="text-muted" id="modal-category"></small>
							</div>
							<div class="col-6 text-end">
								<label class="small text-muted fw-bold text-uppercase">Tanggal</label>
								<div class="fw-bold text-dark" id="modal-date"></div>
							</div>

							<div class="col-12 border-bottom pb-3"></div>

							<div class="col-6">
								<label class="small text-muted fw-bold text-uppercase">Tujuan / ID</label>
								</br><small class="fw-bold text-primary" id="modal-user"></small>
								<div class="fw-bold text-dark" id="modal-customer-no"></div>
							</div>
							<div class="col-6 text-end">
								<label class="small text-muted fw-bold text-uppercase">Payment Status</label>
								<div id="modal-payment-status"></div>
							</div>

							{{-- Section SN / Pesan Provider --}}
							<div class="col-12 mt-4 bg-light p-3 rounded">
								<label class="small text-muted fw-bold d-block mb-1">SN / Token / Pesan Provider:</label>
								<code class="text-dark d-block text-break" id="modal-sn"></code>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer border-0 bg-light">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('styles')
	<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css" id="main-style-link">

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
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js" rel="stylesheet"></script>
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js" rel="stylesheet"></script>

	<script>
		// Badge Helper
		const getStatusBadge = (status) => {
			switch(status.toLowerCase()) {
				case 'success': return '<span class="badge badge-soft-success">SUKSES</span>';
				case 'pending': return '<span class="badge badge-soft-warning">PENDING</span>';
				case 'processing': return '<span class="badge badge-soft-primary">PROSES</span>';
				case 'failed': return '<span class="badge badge-soft-danger">GAGAL</span>';
				case 'paid': return '<span class="badge badge-soft-success">LUNAS</span>';
				case 'unpaid': return '<span class="badge badge-soft-secondary">BELUM BAYAR</span>';
				case 'refunded': return '<span class="badge bg-info">REFUND</span>';
				default: return `<span class="badge bg-secondary">${status}</span>`;
			}
		}

		let $formatter
		let $swal
		$(document).ready(async function() {
			module = await initModul()
			$formatter = module.formatter
			$swal = module.swal

			var table = $('#data-table').DataTable({
				processing: true, // Tampilkan pesan loading
				serverSide: true, // Aktifkan pengolahan di server (AJAX)
				searchDelay: 500,
				scrollX: true,
				columnDefs: [{
						targets: [8],
						orderable: false
					},
					{
						targets: [8],
						searchable: false
					},
					// {
					// 	targets: ['_all'],
					// 	className: 'text-center'
					// },
					// {
					// 	targets: [0],
					// 	width: '5%'
					// },
				],
				ajax: "{{ route('admin.transactions.index') }}", // URL ke Controller tadi
				columns: [
					{data: 'invoice', name: 'invoice'},
					// {data: 'customer_no', name: 'customer_no'},
					{data: 'target', name: 'target'},
					{data: 'product_name_snapshot', name: 'product_name_snapshot'},
					{
						data: 'category',
						name: 'product.categories.name',
						// render: (data, type, row, meta) => data?.toUpperCase(),
					},
					{data: 'total_rupiah', name: 'total_amount'},
					{
						data: 'created_at',
						name: 'created_at',
						className: 'text-center',
					},
					{
						data: 'payment_status',
						name: 'payment_status',
						className: 'text-center',
					},
					{
						data: 'delivery_status',
						name: 'delivery_status',
						className: 'text-center',
					},
					{
						data: 'action',
						name: 'action',
						className: 'fw-bold text-end pe-3'
					},
				]
			});

			// --- LOGIKA CUSTOM FILTER ---
			// 1. Search Bar (Global)
			$('#customSearch').on('keyup', function() {
				table.search(this.value).draw();
			});

			$('#category-filter').on('change', function() {
				var status = $(this).val();
				// Regex search untuk pencocokan tepat
				table.column(3).search(status ? '^' + status + '$' : '', true, false).draw();
			});
			// 2. Filter Status (Kolom index 5)
			$('#payment-status-filter').on('change', function() {
				var status = $(this).val();
				// Regex search untuk pencocokan tepat
				table.column(6).search(status ? '^' + status + '$' : '', true, false).draw();
			});
			$('#delivery-status-filter').on('change', function() {
				var status = $(this).val();
				console.log(status);

				// Regex search untuk pencocokan tepat
				table.column(7).search(status ? '^' + status + '$' : '', true, false).draw();
			});

			// 3. Filter Kategori (Kolom index 2 - Mengandung teks Games/Pulsa/PLN)
			$('#categoryFilter').on('change', function() {
				table.column(3).search(this.value).draw();
			});




			// // --- INISIALISASI DATATABLES ---
			// var table = $('#transactionsTable').DataTable({
			// 	"language": {
			// 		"url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" // Bahasa Indonesia
			// 	},
			// 	"dom": 'rtp', // HIDE Default Search & Length (Kita pakai custom filter di atas)
			// 	"pageLength": 10,
			// 	"columnDefs": [
			// 		{ "orderable": false, "targets": 6 } // Matikan sorting di kolom Aksi (index 6)
			// 	],
			// 	"order": [[ 0, "asc" ]] // Default sort berdasarkan Tanggal (index 4)
			// });
		})

		$(document).on('click', '.btn-detail', async function() {
			let trxId = $(this).data('id');
			let url = "{{ route('admin.transactions.show', ':id') }}";
			url = url.replace(':id', trxId);

			// 1. Reset & Show Modal Loader
			$('#modal-content').addClass('d-none');
			$('#modal-loader').removeClass('d-none');
			$('#detailModal').modal('show');

			const {status, data: {data}, data: {meta}} = await getRequest(url);
			console.log(data);

			if (status !== 200) {
				$('#modal-loader').addClass('d-none');
				$('#modal-content').removeClass('d-none').html(`
					<div class="text-center py-4">
						<i class="fa-solid fa-circle-exclamation text-danger fa-3x mb-3"></i>
						<h5 class="text-danger fw-bold">Gagal Memuat Data</h5>
						<p class="text-muted">Terjadi kesalahan saat menghubungi server.</p>
					</div>
				`);
			};


			$('#modal-invoice').text('#' + data.invoice);
			$('#modal-amount').text(formatRupiah(data.total_amount));
			$('#modal-date').text(formatDate(data.created_at));

			// Product & Category (Safe check if product deleted)
			let prodName = data.product_name_snapshot || (data.product ? data.product.product_name : '-');
			let catName = data.product ? data.product?.category?.name.toUpperCase() : '-';
			$('#modal-product').text(prodName);
			$('#modal-category').text(catName);

			// Customer info
			$('#modal-customer-no').text(data.customer_no + (data?.sku_snapshot.toLowerCase().includes('game') ? ` (${data.zone_id})` : ''));
			// let userName = data.user ? data.user.name : 'Guest / Terhapus';
			let userName = data.customer_name ?? data?.user?.name ?? 'Guest / Terhapus';
			$('#modal-user').text(userName);

			// Status Badges
			$('#modal-status-badge').html(getStatusBadge(data.delivery_status.toUpperCase()));
			// Kita manipulasi class parent badge utama untuk warna background light-nya
			$('#modal-status-badge').attr('class', 'badge px-3 py-2 mb-2 rounded-pill'); // reset
			if(data.delivery_status == 'success') $('#modal-status-badge').addClass('badge-soft-success');
			else if(data.delivery_status == 'pending') $('#modal-status-badge').addClass('badge-soft-warning');
			else if(data.delivery_status == 'failed') $('#modal-status-badge').addClass('badge-soft-danger');
			else $('#modal-status-badge').addClass('badge-soft-primary');

			$('#modal-status-badge').text(data.delivery_status.toUpperCase());

			$('#modal-payment-status').html(getStatusBadge(data.payment_status.toUpperCase()));

			// SN Message
			let sn = data.sn ? data.sn : '-';
			let msg = data.provider_message ? data.provider_message : '';
			const category = data?.product?.category_id || ''

			// parsing token PLN
			if (category == 4 && sn.includes('/')) {
				let parts = sn.split('/');

				let token = parts[0] || '-';
				let name  = parts[1] || '-';
				let tarif = parts[2] || '-';
				let daya  = parts[3] || '-';
				let kwh   = parts[4] || '-';

				sn = `
					<div class="mb-2 text-center">
						<div class="fw-bold fs-4 letter-spacing" id="pln-token">${token}</div>

						<button
							type="button"
							class="btn btn-sm btn-outline-primary mt-2"
							onclick="copyPlnToken()">
							📋 Salin Token
						</button>

						<div id="copy-feedback" class="small text-success mt-1 d-none">
							✓ Token berhasil disalin
						</div>

						<small class="d-block text-muted mt-1">TOKEN LISTRIK PLN</small>
					</div>

					<hr class="my-2">

					<table class="table table-sm table-borderless mb-1">
						<tr>
							<td class="text-muted">Nama</td>
							<td class="text-end fw-semibold">${name}</td>
						</tr>
						<tr>
							<td class="text-muted">Tarif</td>
							<td class="text-end">${tarif}</td>
						</tr>
						<tr>
							<td class="text-muted">Daya</td>
							<td class="text-end">${daya}</td>
						</tr>
						<tr>
							<td class="text-muted">kWh</td>
							<td class="text-end">${kwh}</td>
						</tr>
					</table>
				`;
			}

			$('#modal-sn').html(sn + '<br><span class="text-muted small">' + msg + '</span>');

			$('#modal-loader').addClass('d-none');
			$('#modal-content').removeClass('d-none');
		})

		$('body').on('click', '.btn-resend', function() {
			let trxId = $(this).data('id');

			Swal.fire({
				title: 'Kirim Ulang Job?',
				text: "Transaksi ini akan dimasukkan kembali ke antrian worker!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Kirim!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					// Tampilkan Loading
					Swal.fire({
						title: 'Sedang Memproses...',
						text: 'Mohon tunggu sebentar',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});

					// Eksekusi AJAX
					$.ajax({
						url: "{{ route('api.provider.resend') }}",
						type: 'POST',
						data: { id: trxId },
						success: function(response) {
							// Tutup Loading & Tampilkan Sukses
							Swal.fire({
								title: 'Berhasil!',
								text: response.message,
								icon: 'success',
								timer: 2000,
								showConfirmButton: false
							});

							// Reload DataTable otomatis
							$('#data-table').DataTable().ajax.reload(null, false);
						},
						error: function(xhr) {
							// Tutup Loading & Tampilkan Error
							let msg = xhr.responseJSON ? xhr.responseJSON?.meta?.message : 'Terjadi kesalahan server';
							Swal.fire(
								'Gagal!',
								msg,
								'error'
							);
						}
					});
				}
			});
		});

		function copyPlnToken() {
			const token = document.getElementById('pln-token').innerText;

			// modern browser
			if (navigator.clipboard) {
				navigator.clipboard.writeText(token).then(() => {
					showCopyFeedback();
				});
			} else {
				// fallback (browser lama)
				const temp = document.createElement('textarea');
				temp.value = token;
				document.body.appendChild(temp);
				temp.select();
				document.execCommand('copy');
				document.body.removeChild(temp);
				showCopyFeedback();
			}
		}
		function showCopyFeedback() {
			const el = document.getElementById('copy-feedback');
			el.classList.remove('d-none');

			setTimeout(() => {
				el.classList.add('d-none');
			}, 2000);
		}

	</script>
@endpush
