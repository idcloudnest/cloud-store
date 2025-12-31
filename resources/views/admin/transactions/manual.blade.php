@extends('layouts.admin') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Input Transaksi Manual')

@section('content')
<div class="row justify-content-center">
	<div class="col-lg-10">

		{{-- Header & Breadcrumb --}}
		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h4 class="mb-1 fw-bold text-dark">Transaksi Manual</h4>
				<p class="text-muted small mb-0">Input order secara manual untuk pelanggan.</p>
			</div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
					<li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Transaksi</a></li>
					<li class="breadcrumb-item active text-primary" aria-current="page">Manual</li>
				</ol>
			</nav>
		</div>

		{{-- Alert Notifikasi (Opsional) --}}
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
				<i class="fas fa-check-circle me-2"></i> {{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if($errors->any())
			<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
				<ul class="mb-0 ps-3">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		{{-- Card Form --}}
		<div class="card border-0 shadow-sm" style="border-radius: 16px;">
			<div class="card-header bg-white border-bottom py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
				<h6 class="mb-0 fw-bold text-primary">
					<i class="fas fa-keyboard me-2"></i> Form Input
				</h6>
			</div>
			<div class="card-body p-4">

				<form id="form-transaction" action="{{ route('admin.transactions.store') }}" method="POST">

					<div class="row g-4">
						<div class="col-md-6">
							<label for="user_id" class="form-label text-muted small fw-bold text-uppercase">Pelanggan / Reseller</label>
							<select class="form-select py-2" id="user_id" name="user_id" required>
								<option value="" disabled>-- Cari Pengguna --</option>
								{{-- Opsi Spesial: Diri Sendiri --}}
								<option value="{{ auth()->id() }}" class="fw-bold bg-light">
									★ SAYA SENDIRI ({{ auth()->user()->balance_formatted }})
								</option>

								{{-- Loop User Lain --}}
								@foreach($users as $user)
								@if($user->id != auth()->id()) {{-- Hindari duplikat --}}
								<option value="{{ $user->id }}">
									{{ $user->name }} ({{ $user->balance_formatted }})
								</option>
								@endif
								@endforeach
								{{-- <option value="" selected disabled>-- Cari Pengguna --</option>
								<option value="1">Admin Utama (Rp 1.000.000)</option>
								<option value="2">Reseller Alpha (Rp 50.000)</option> --}}
							</select>
							<div class="form-text">Saldo pengguna akan terpotong otomatis.</div>
						</div>

						<div class="col-md-6">
							<label for="target" class="form-label text-muted small fw-bold text-uppercase">Nomor Tujuan / ID Pelanggan</label>
							<div class="input-group">
								<span class="input-group-text bg-light border-end-0"><i class="fas fa-phone-alt text-muted"></i></span>
								<input type="text" class="form-control py-2 border-start-0 ps-0" id="target" name="target" placeholder="Contoh: 081234567890" required>
							</div>
						</div>

						<div class="col-md-6">
							<label for="category-id" class="form-label text-muted small fw-bold text-uppercase">Kategori Produk</label>
							<select class="form-select py-2" id="category-id" name="category_id">
								<option value="" selected disabled>-- Pilih Kategori --</option>
								@if (count($brand))
									@foreach ($brand as $item)
										<option value="{{ $item->category }}">{{strtoupper($item->category)}}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="col-md-6">
							<label for="supplier_id" class="form-label text-muted small fw-bold text-uppercase">Jalur Supplier (Opsional)</label>
							<select class="form-select py-2" id="supplier_id" name="supplier_id">
								<option value="auto" selected>Otomatis (Best Price)</option>
								<option value="digiflazz">Digiflazz</option>
								<option value="vip">VIP Payment</option>
							</select>
						</div>

						<div class="col-md-6">
							<label for="product-code" class="form-label text-muted small fw-bold text-uppercase">Produk</label>
							<select class="form-select py-2" id="product-code" name="product_code" required>
								<option value="" selected disabled>-- Pilih Produk --</option>
							</select>
						</div>

						 <div class="col-md-6">
							<label for="custom_price" class="form-label text-muted small fw-bold text-uppercase">Harga Jual (Override)</label>
							<div class="input-group">
								<span class="input-group-text bg-light">Rp</span>
								<input type="number" class="form-control py-2" id="custom_price" name="custom_price" placeholder="Kosongkan untuk harga default">
							</div>
							<div class="form-text">Biarkan kosong untuk menggunakan harga asli produk.</div>
						</div>

						{{-- <div class="col-md-6">

							<div class="mb-3">
								<label for="user_id" class="form-label text-muted small fw-bold text-uppercase">Pelanggan / Reseller</label>
								<select class="form-select py-2" id="user_id" name="user_id" required>
									<option value="" selected disabled>-- Cari Pengguna --</option>
									<option value="1">Admin Utama (Rp 1.000.000)</option>
									<option value="2">Reseller Alpha (Rp 50.000)</option>
								</select>
								<div class="form-text">Saldo pengguna akan terpotong otomatis.</div>
							</div>

							<div class="mb-3">
								<label for="category-id" class="form-label text-muted small fw-bold text-uppercase">Kategori Produk</label>
								<select class="form-select py-2" id="category-id" name="category_id">
									<option value="" selected disabled>-- Pilih Kategori --</option>
									@if (count($brand))
										@foreach ($brand as $item)
											<option value="{{ $item->category }}">{{strtoupper($item->category)}}</option>
										@endforeach
									@endif
								</select>
							</div>

							<div class="mb-3">
								<label for="product-code" class="form-label text-muted small fw-bold text-uppercase">Produk</label>
								<select class="form-select py-2" id="product-code" name="product_code" required>
									<option value="" selected disabled>-- Pilih Produk --</option>
								</select>
							</div>

						</div> --}}

						{{-- <div class="col-md-6">
							<div class="mb-3">
								<label for="target" class="form-label text-muted small fw-bold text-uppercase">Nomor Tujuan / ID Pelanggan</label>
								<div class="input-group">
									<span class="input-group-text bg-light border-end-0"><i class="fas fa-phone-alt text-muted"></i></span>
									<input type="text" class="form-control py-2 border-start-0 ps-0" id="target" name="target" placeholder="Contoh: 081234567890" required>
								</div>
							</div>

							<div class="mb-3">
								<label for="supplier_id" class="form-label text-muted small fw-bold text-uppercase">Jalur Supplier (Opsional)</label>
								<select class="form-select py-2" id="supplier_id" name="supplier_id">
									<option value="auto" selected>Otomatis (Best Price)</option>
									<option value="digiflazz">Digiflazz</option>
									<option value="vip">VIP Payment</option>
								</select>
							</div>

							 <div class="mb-3">
								<label for="custom_price" class="form-label text-muted small fw-bold text-uppercase">Harga Jual (Override)</label>
								<div class="input-group">
									<span class="input-group-text bg-light">Rp</span>
									<input type="number" class="form-control py-2" id="custom_price" name="custom_price" placeholder="Kosongkan untuk harga default">
								</div>
								<div class="form-text">Biarkan kosong untuk menggunakan harga asli produk.</div>
							</div>

						</div> --}}
					</div>

					<hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

					<div class="row align-items-end">
						<div class="col-md-8 mb-3 mb-md-0">
							<div class="bg-light p-3 rounded-3 border d-flex align-items-start">
								<i class="fas fa-info-circle text-info mt-1 me-3"></i>
								<small class="text-muted">
									Pastikan <strong>Nomor Tujuan</strong> dan <strong>Produk</strong> sudah benar. Transaksi yang sudah masuk ke supplier biasanya tidak dapat dibatalkan.
								</small>
							</div>
						</div>
						<div class="col-md-4 text-end">
							<button type="button" onclick="storeTransaction()" id="btn-submit" class="btn btn-primary btn-anim w-100 py-2 fw-bold shadow-sm" style="background-color: var(--primary-color); border:none;">
								<i class="fas fa-paper-plane me-2"></i> Kirim Transaksi
							</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
	{{-- Jika ingin menggunakan Select2 agar dropdown lebih bagus --}}
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<style>
		.form-control:focus, .form-select:focus {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
		}
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script>
		// $(document).ready(function() {
		// 	// Contoh Logic Sederhana: Update harga saat produk dipilih (Dummy)
		// 	// $('#product_code').on('change', function() {
		// 	// 	// Di sini Anda bisa menambahkan AJAX untuk mengambil harga produk
		// 	// 	// console.log("Produk dipilih: " + $(this).val());
		// 	// });
		// });
	</script>

	{{-- Load SweetAlert2 CDN --}}
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function() {
			$('#user_id').select2({
				theme: 'bootstrap-5',
				placeholder: '-- Cari Pengguna --',
				width: "100%",
			})
			$('#product-code').select2({
				theme: 'bootstrap-5',
				placeholder: '-- Cari Produk --',
				width: "100%",
			})

			$('#product-code').on('change', function() {
				// Di sini Anda bisa menambahkan AJAX untuk mengambil harga produk
				// console.log("Produk dipilih: " + $(this).val());
			})

			$('#category-id').on('change', function() {
				var categoryName = $(this).val();
				var productSelect = $('#product-code');

				// Kosongkan dropdown produk & tampilkan loading
				productSelect.empty().append('<option value="" selected disabled>Sedang memuat...</option>');

				// Jika pakai Select2, trigger change agar tampilan update
				productSelect.trigger('change');

				if (categoryName) {
					$.ajax({
						url: "{{ route('admin.products.items.getProductsByCategory') }}", // Panggil route yang kita buat
						type: "POST",
						data: { category: categoryName },
						dataType: "json",
						success: function(data) {
							productSelect.empty().append('<option value="" selected disabled>-- Pilih Produk --</option>');

							if (data.length > 0) {
								$.each(data, function(key, value) {
									var label = value.product_name + ' (Rp ' + new Intl.NumberFormat('id-ID').format(value.price) + ')'

									productSelect.append('<option value="' + value.buyer_sku_code + '">' + label + '</option>')
								})
							} else {
								productSelect.append('<option value="" disabled>Produk tidak tersedia</option>')
							}

							// PENTING: Refresh Select2 setelah data masuk
							productSelect.trigger('change');
						},
						error: function(xhr, status, error) {
							console.error("Error: " + error)
							productSelect.empty().append('<option value="" disabled>Gagal memuat produk</option>')
							productSelect.trigger('change')
						}
					})
				} else {
					productSelect.empty().append('<option value="" selected disabled>-- Pilih Produk --</option>');
					productSelect.trigger('change');
				}
			});
		})

		// FUNGSI UTAMA: Dipanggil saat tombol diklik
		function storeTransaction() {
			// 1. Ambil Form dan Tombol
			var form = $('#form-transaction');
			var btn = $('#btn-submit');
			var originalBtnHtml = btn.html(); // Simpan teks asli tombol

			// 2. Cek Validasi HTML5 Sederhana (Required fields)
			// if (!form[0].checkValidity()) return form[0].reportValidity(); // Tampilkan error bawaan browser jika ada field kosong

			// 3. Tampilkan SweetAlert Konfirmasi
			Swal.fire({
				title: 'Konfirmasi Transaksi',
				text: "Apakah data nomor dan produk sudah benar? Saldo akan langsung terpotong.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Kirim Sekarang!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				// console.log(form.attr('action'))

				if (result.isConfirmed) {

					// 4. Proses AJAX
					$.ajax({
						url: form.attr('action'), // Ambil URL dari action form
						type: "POST",
						data: form.serialize(), // Ambil semua data input
						dataType: 'json',
						beforeSend: function() {
							// Ubah tombol jadi loading agar tidak diklik 2x
							btn.prop('disabled', true);
							btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');
						},
						success: function(response) {
							// return console.log(response);

							// Jika Sukses (Code 200)
							Swal.fire({
								title: 'Berhasil!',
								text: response.message || 'Transaksi berhasil diproses.',
								icon: 'success',
								timer: 2000,
								showConfirmButton: false
							}).then(() => {
								// Redirect atau Reset Form
								// window.location.href = "{{ route('admin.transactions.index') }}"; // Jika mau redirect
								// window.location.reload(); // Reload halaman
								btn.prop('disabled', false);
								btn.html(originalBtnHtml); // Kembalikan tombol
							});
						},
						error: function(xhr) {
							// Jika Gagal (Validasi Laravel atau Error Server)
							btn.prop('disabled', false);
							btn.html(originalBtnHtml); // Kembalikan tombol

							var errorMessage = "Terjadi kesalahan pada server.";

							// Cek jika error validasi dari Laravel (422)
							if (xhr.status === 422) {
								var errors = xhr.responseJSON.data;
								console.log(xhr.responseJSON);

								errorMessage = "";
								$.each(errors, function(key, value) {
									errorMessage += value[0] + "<br>"; // List error
								});
							} else if(xhr.responseJSON && xhr.responseJSON.message) {
								errorMessage = xhr.responseJSON.message;
							}

							Swal.fire({
								title: 'Gagal!',
								html: errorMessage, // Pakai HTML agar bisa enter
								icon: 'error'
							});
						}
					});
				}
			});
		}
	</script>
@endpush
