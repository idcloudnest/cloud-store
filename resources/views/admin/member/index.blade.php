{{-- @extends('layouts.admin')

@section('title', 'Data Member')

@push('styles')
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

	<style>
		/* --- COPY STYLE DARI HALAMAN TRANSAKSI (Konsistensi) --- */
		.badge-soft-primary { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
		.badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
		.badge-soft-warning { background-color: rgba(254, 207, 239, 0.1); color: #d63384; }
		.badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }
		.badge-soft-info { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }

		.filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }

		/* --- STYLE KHUSUS MEMBER --- */
		.member-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
		.saldo-text { font-family: 'Segoe UI', sans-serif; font-weight: 700; color: #2d3436; }

		/* Level Badge Styles */
		.level-badge { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 4px; }
		.level-reseller { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
		.level-user { background: #e2e6ea; color: #6c757d; }
	</style>
@endpush

@section('content')

	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="fw-bold mb-1">Kelola Member</h4>
			<span class="text-muted small">Total 1,240 member terdaftar</span>
		</div>
		<div class="d-flex gap-2">
			<button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-file-export me-1"></i> Export CSV</button>
			<button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
				<i class="fa-solid fa-user-plus me-1"></i> Tambah Member
			</button>
		</div>
	</div>

	<div class="row g-3 mb-4">
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-primary bg-opacity-10 p-3 rounded me-3 text-primary">
						<i class="fa-solid fa-users fa-xl"></i>
					</div>
					<div>
						<span class="text-muted small text-uppercase fw-bold">Total Member</span>
						<h4 class="fw-bold mb-0">1,240</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-success bg-opacity-10 p-3 rounded me-3 text-success">
						<i class="fa-solid fa-wallet fa-xl"></i>
					</div>
					<div>
						<span class="text-muted small text-uppercase fw-bold">Total Saldo User</span>
						<h4 class="fw-bold mb-0">Rp 45.2jt</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-info bg-opacity-10 p-3 rounded me-3 text-info">
						<i class="fa-solid fa-store fa-xl"></i>
					</div>
					<div>
						<span class="text-muted small text-uppercase fw-bold">Reseller / VIP</span>
						<h4 class="fw-bold mb-0">85</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0 shadow-sm p-3">
				<div class="d-flex align-items-center">
					<div class="bg-danger bg-opacity-10 p-3 rounded me-3 text-danger">
						<i class="fa-solid fa-user-slash fa-xl"></i>
					</div>
					<div>
						<span class="text-muted small text-uppercase fw-bold">Diblokir</span>
						<h4 class="fw-bold mb-0">3</h4>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-5">
					<label class="filter-label">Cari Member</label>
					<div class="input-group">
						<span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
						<input type="text" id="memberSearch" class="form-control border-start-0 ps-0" placeholder="Nama / No. HP / Email...">
					</div>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Level</label>
					<select id="levelFilter" class="form-select">
						<option value="">Semua Level</option>
						<option value="Reseller">Reseller</option>
						<option value="Member">Member Biasa</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="filter-label">Status</label>
					<select id="statusFilter" class="form-select">
						<option value="">Semua Status</option>
						<option value="Aktif">Aktif</option>
						<option value="Nonaktif">Nonaktif / Blokir</option>
					</select>
				</div>
				<div class="col-md-1">
					<button class="btn btn-light w-100 border" title="Reset"><i class="fa-solid fa-rotate-right"></i></button>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-5">
		<div class="card-body p-0">
			<div class="table-responsive p-3">
				<table class="table table-hover align-middle mb-0" id="memberTable" style="width:100%">
					<thead class="bg-light text-secondary">
						<tr>
							<th class="ps-3 py-3">Member</th>
							<th>Kontak</th>
							<th>Level</th>
							<th>Sisa Saldo</th>
							<th>Status</th>
							<th class="text-end pe-3">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="ps-3">
								<div class="d-flex align-items-center">
									<img src="https://ui-avatars.com/api/?name=Andi+Saputra&background=random" class="member-avatar me-3">
									<div>
										<div class="fw-bold text-dark">Andi Saputra</div>
										<small class="text-muted">andi@gmail.com</small>
									</div>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<i class="fa-brands fa-whatsapp text-success me-2 fa-lg"></i>
									<span class="fw-semibold text-dark">0812-3456-7890</span>
								</div>
							</td>
							<td><span class="level-badge level-reseller shadow-sm">RESELLER</span></td>
							<td><span class="saldo-text text-primary">Rp 1.500.000</span></td>
							<td><span class="badge badge-soft-success rounded-pill px-3">Aktif</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item fw-bold text-primary" href="#" data-bs-toggle="modal" data-bs-target="#topupModal">
											<i class="fa-solid fa-circle-plus me-2"></i> Tambah Saldo
										</a></li>
										<li><hr class="dropdown-divider"></li>
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-secondary"></i> Edit Profil</a></li>
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-key me-2 text-secondary"></i> Reset Password</a></li>
										<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i> Blokir Member</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td class="ps-3">
								<div class="d-flex align-items-center">
									<img src="https://ui-avatars.com/api/?name=Siti+Nur&background=random" class="member-avatar me-3">
									<div>
										<div class="fw-bold text-dark">Siti Nurhaliza</div>
										<small class="text-muted">siti.nur@yahoo.com</small>
									</div>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<i class="fa-brands fa-whatsapp text-success me-2 fa-lg"></i>
									<span class="fw-semibold text-dark">0857-9999-8888</span>
								</div>
							</td>
							<td><span class="level-badge level-user">MEMBER</span></td>
							<td><span class="saldo-text text-dark">Rp 25.000</span></td>
							<td><span class="badge badge-soft-success rounded-pill px-3">Aktif</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item fw-bold text-primary" href="#" data-bs-toggle="modal" data-bs-target="#topupModal"><i class="fa-solid fa-circle-plus me-2"></i> Tambah Saldo</a></li>
										<li><hr class="dropdown-divider"></li>
										<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-secondary"></i> Edit Profil</a></li>
										<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i> Blokir Member</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td class="ps-3">
								<div class="d-flex align-items-center">
									<img src="https://ui-avatars.com/api/?name=Budi+Gelap&background=000&color=fff" class="member-avatar me-3 grayscale" style="filter: grayscale(100%);">
									<div>
										<div class="fw-bold text-muted">Budi Gelap</div>
										<small class="text-muted">budi.dark@gmail.com</small>
									</div>
								</div>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<i class="fa-brands fa-whatsapp text-secondary me-2 fa-lg"></i>
									<span class="fw-semibold text-muted">0899-1111-2222</span>
								</div>
							</td>
							<td><span class="level-badge level-user">MEMBER</span></td>
							<td><span class="saldo-text text-muted">Rp 0</span></td>
							<td><span class="badge badge-soft-danger rounded-pill px-3">Nonaktif</span></td>
							<td class="text-end pe-3">
								<div class="dropdown">
									<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
									<ul class="dropdown-menu dropdown-menu-end border-0 shadow">
										<li><a class="dropdown-item text-success" href="#"><i class="fa-solid fa-check-circle me-2"></i> Aktifkan Kembali</a></li>
									</ul>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="topupModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-light">
					<h5 class="modal-title fw-bold">Tambah Saldo Manual</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-4">
					<div class="alert alert-info d-flex align-items-center mb-3">
						<i class="fa-solid fa-circle-info me-2 fa-lg"></i>
						<small>Saldo akan bertambah ke akun: <b>Andi Saputra</b></small>
					</div>
					<form>
						<div class="mb-3">
							<label class="form-label small fw-bold text-muted">Nominal Topup</label>
							<div class="input-group">
								<span class="input-group-text fw-bold">Rp</span>
								<input type="number" class="form-control fw-bold" placeholder="0">
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label small fw-bold text-muted">Catatan (Opsional)</label>
							<textarea class="form-control" rows="2" placeholder="Contoh: Bonus Event, Refund TRX..."></textarea>
						</div>
						<button type="submit" class="btn btn-primary w-100 fw-bold">Proses Topup</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title fw-bold">Tambah Member Baru</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body p-4">
					<form>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label small fw-bold">Nama Lengkap</label>
								<input type="text" class="form-control" placeholder="Nama sesuai KTP">
							</div>
							<div class="col-md-6">
								<label class="form-label small fw-bold">Nomor WhatsApp</label>
								<input type="number" class="form-control" placeholder="08xxxxx">
							</div>
							<div class="col-md-6">
								<label class="form-label small fw-bold">Email</label>
								<input type="email" class="form-control" placeholder="alamat@email.com">
							</div>
							<div class="col-md-6">
								<label class="form-label small fw-bold">Password Default</label>
								<input type="text" class="form-control" value="123456" readonly>
								<small class="text-muted" style="font-size: 0.7rem;">Member wajib ganti password saat login.</small>
							</div>
							<div class="col-md-6">
								<label class="form-label small fw-bold">Level Akun</label>
								<select class="form-select">
									<option value="member">Member Biasa</option>
									<option value="reseller">Reseller (Harga Khusus)</option>
								</select>
							</div>
						</div>
						<div class="mt-4 text-end">
							<button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Batal</button>
							<button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Data</button>
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
			var table = $('#memberTable').DataTable({
				"language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" },
				"dom": 'rtp', // Hide default search
				"pageLength": 10,
				"columnDefs": [ { "orderable": false, "targets": 5 } ] // Disable sort action column
			});

			// Connect Custom Filter
			$('#memberSearch').on('keyup', function() { table.search(this.value).draw(); });
			$('#levelFilter').on('change', function() { table.column(2).search(this.value).draw(); }); // Kolom Level index 2
			$('#statusFilter').on('change', function() { table.column(4).search(this.value).draw(); }); // Kolom Status index 4
		});
	</script>
@endpush --}}

@extends('layouts.admin')

@section('title', 'Data Member')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        /* --- STYLE UTILS (Sama seperti Transaksi) --- */
        .badge-soft-primary { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
        .badge-soft-success { background-color: rgba(42, 245, 152, 0.1); color: #1f9d55; }
        .badge-soft-warning { background-color: rgba(254, 207, 239, 0.1); color: #d63384; }
        .badge-soft-danger { background-color: rgba(255, 154, 158, 0.1); color: #d63031; }

        .filter-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }

        /* --- STYLE KHUSUS MEMBER --- */
        .member-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .saldo-text { font-family: 'Segoe UI', sans-serif; font-weight: 700; color: #2d3436; }

        /* Level Badges */
        .level-badge { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 4px; }
        .level-reseller { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .level-user { background: #e2e6ea; color: #6c757d; }

        /* Pagination Style */
        .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0; margin-left: 5px; }
        .page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }
    </style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Member</h4>
            <span class="text-muted small">Total 1,240 member terdaftar</span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-file-export me-1"></i> Export CSV</button>
            <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                <i class="fa-solid fa-user-plus me-1"></i> Tambah Member
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3 text-primary"><i class="fa-solid fa-users fa-xl"></i></div>
                    <div><span class="text-muted small text-uppercase fw-bold">Total Member</span><h4 class="fw-bold mb-0">1,240</h4></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded me-3 text-success"><i class="fa-solid fa-wallet fa-xl"></i></div>
                    <div><span class="text-muted small text-uppercase fw-bold">Total Saldo User</span><h4 class="fw-bold mb-0">Rp 45.2jt</h4></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded me-3 text-info"><i class="fa-solid fa-store fa-xl"></i></div>
                    <div><span class="text-muted small text-uppercase fw-bold">Reseller / VIP</span><h4 class="fw-bold mb-0">85</h4></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-3 rounded me-3 text-danger"><i class="fa-solid fa-user-slash fa-xl"></i></div>
                    <div><span class="text-muted small text-uppercase fw-bold">Diblokir</span><h4 class="fw-bold mb-0">3</h4></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="filter-label">Cari Member</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="memberSearch" class="form-control border-start-0 ps-0" placeholder="Nama / No. HP / Email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Level</label>
                    <select id="levelFilter" class="form-select">
                        <option value="">Semua Level</option>
                        <option value="Reseller">Reseller</option>
                        <option value="Member">Member</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Status</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
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
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="memberTable" style="width:100%">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-3 py-3">Member</th> <th>Kontak</th>                   <th>Level</th>                    <th>Sisa Saldo</th>               <th>Status</th>                   <th class="text-end pe-3">Aksi</th> </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Andi+Saputra&background=random" class="member-avatar me-3">
                                    <div>
                                        <div class="fw-bold text-dark">Andi Saputra</div>
                                        <small class="text-muted">andi@gmail.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fa-brands fa-whatsapp text-success me-2 fa-lg"></i>
                                    <span class="fw-semibold text-dark">0812-3456-7890</span>
                                </div>
                            </td>
                            <td><span class="level-badge level-reseller shadow-sm">RESELLER</span></td>

                            <td data-order="1500000">
                                <span class="saldo-text text-primary">Rp 1.500.000</span>
                            </td>

                            <td><span class="badge badge-soft-success rounded-pill px-3">Aktif</span></td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li><a class="dropdown-item fw-bold text-primary" href="#" data-bs-toggle="modal" data-bs-target="#topupModal"><i class="fa-solid fa-circle-plus me-2"></i> Tambah Saldo</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-secondary"></i> Edit Profil</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i> Blokir</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Siti+Nur&background=random" class="member-avatar me-3">
                                    <div>
                                        <div class="fw-bold text-dark">Siti Nurhaliza</div>
                                        <small class="text-muted">siti.nur@yahoo.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fa-brands fa-whatsapp text-success me-2 fa-lg"></i>
                                    <span class="fw-semibold text-dark">0857-9999-8888</span>
                                </div>
                            </td>
                            <td><span class="level-badge level-user">MEMBER</span></td>

                            <td data-order="25000">
                                <span class="saldo-text text-dark">Rp 25.000</span>
                            </td>

                            <td><span class="badge badge-soft-success rounded-pill px-3">Aktif</span></td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li><a class="dropdown-item fw-bold text-primary" href="#" data-bs-toggle="modal" data-bs-target="#topupModal"><i class="fa-solid fa-circle-plus me-2"></i> Tambah Saldo</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-secondary"></i> Edit Profil</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i> Blokir</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Budi+Gelap&background=000&color=fff" class="member-avatar me-3" style="filter: grayscale(100%);">
                                    <div>
                                        <div class="fw-bold text-muted">Budi Gelap</div>
                                        <small class="text-muted">budi.dark@gmail.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fa-brands fa-whatsapp text-secondary me-2 fa-lg"></i>
                                    <span class="fw-semibold text-muted">0899-1111-2222</span>
                                </div>
                            </td>
                            <td><span class="level-badge level-user">MEMBER</span></td>

                            <td data-order="0">
                                <span class="saldo-text text-muted">Rp 0</span>
                            </td>

                            <td><span class="badge badge-soft-danger rounded-pill px-3">Nonaktif</span></td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li><a class="dropdown-item text-success" href="#"><i class="fa-solid fa-check-circle me-2"></i> Aktifkan</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.admin.modal-member') {{-- Atau paste kode modal langsung --}}

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // --- INISIALISASI DATATABLES ---
            var table = $('#memberTable').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" },
                "dom": 'rtp', // Hide default search box (agar pakai custom filter kita)
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 5 }, // Disable sort kolom Aksi
                    { "targets": 3, "type": "num" }       // Pastikan kolom saldo dianggap angka
                ],
                "order": [[ 0, "asc" ]] // Default urut nama A-Z
            });

            // --- LOGIKA FILTER CUSTOM ---

            // 1. Search Global (Nama/Email/HP)
            $('#memberSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // 2. Filter Level (Kolom index 2)
            // Menggunakan regex exact match (^...$) agar "Reseller" tidak tertukar dengan kata lain
            $('#levelFilter').on('change', function() {
                var val = $(this).val();
                table.column(2).search(val ? '^' + val + '$' : '', true, false).draw();
            });

            // 3. Filter Status (Kolom index 4)
            $('#statusFilter').on('change', function() {
                var val = $(this).val();
                table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
            });
        });
    </script>
@endpush
