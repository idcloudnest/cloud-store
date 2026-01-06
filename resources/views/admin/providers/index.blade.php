@extends('layouts.admin')

@section('title', 'Pengaturan Provider')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h4 class="mb-1 fw-bold text-dark">Provider Server</h4>
		<p class="text-muted small mb-0">Kelola koneksi API ke supplier.</p>
	</div>
	<button class="btn btn-primary btn-sm shadow-sm" onclick="openModal('create')">
		<i class="fas fa-plus me-2"></i> Tambah Provider
	</button>
</div>

<div class="row g-4">
	@foreach($providers as $provider)
	<div class="col-md-6 col-lg-4">
		<div class="card border-0 shadow-sm h-100 provider-card">
			{{-- Header Card --}}
			<div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-start">
				<div class="d-flex align-items-center">
					{{-- Logo Placeholder based on Code --}}
					<div class="rounded-3 p-2 bg-light me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
						{{-- @if($provider->code == 'digiflazz')
							<i class="fas fa-bolt text-warning fa-lg"></i>
						@elseif($provider->code == 'vip')
							<i class="fas fa-crown text-warning fa-lg"></i>
						@else
							<i class="fas fa-server text-primary fa-lg"></i>
						@endif --}}
						<i class="fas fa-server text-primary fa-lg"></i>
					</div>
					<div>
						<h6 class="fw-bold mb-0 text-dark">{{ $provider->name }}</h6>
						<span class="badge {{ $provider->mode == 'production' ? 'bg-success' : 'bg-warning' }} rounded-pill" style="font-size: 0.65rem;">
							{{ strtoupper($provider->mode) }}
						</span>
					</div>
				</div>

				<div class="form-check form-switch">
					<input class="form-check-input cursor-pointer" type="checkbox" role="switch"
						onchange="toggleStatus({{ $provider->id }})"
						{{ $provider->is_active ? 'checked' : '' }}>
				</div>
			</div>

			{{-- Body Card --}}
			<div class="card-body">
				<div class="bg-light rounded-3 p-3 mb-3 border border-dashed">
					<small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Sisa Saldo</small>
					<div class="d-flex justify-content-between align-items-end mt-1">
						<h4 class="fw-bold mb-0 text-dark" id="balance-text-{{ $provider->id }}">
							{{ formatRupiah($provider->balance) }}
						</h4>
						<button class="btn btn-link btn-sm p-0 text-decoration-none" onclick="checkBalance({{ $provider->id }})" id="btn-refresh-{{ $provider->id }}">
							<i class="fas fa-sync-alt"></i>
						</button>
					</div>
				</div>

				<ul class="list-group list-group-flush small">
					<li class="list-group-item px-0 d-flex justify-content-between border-0 pb-1">
						<span class="text-muted">Username</span>
						<span class="fw-bold text-dark">{{ $provider->api_username ?? '-' }}</span>
					</li>
					<li class="list-group-item px-0 d-flex justify-content-between border-0 pt-1">
						<span class="text-muted">Updated</span>
						<span class="text-end" id="last-update-{{ $provider->id }}">{{ $provider->updated_at->diffForHumans() }}</span>
					</li>
				</ul>
			</div>

			{{-- Footer Card --}}
			<div class="card-footer bg-white border-0 pb-4 pt-0">
				<button class="btn btn-outline-secondary w-100 btn-sm fw-bold" onclick='editProvider(@json($provider))'>
					<i class="fas fa-cog me-2"></i> Konfigurasi API
				</button>
			</div>
		</div>
	</div>
	@endforeach
</div>

{{-- MODAL FORM PROVIDER --}}
<div class="modal fade" id="providerModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">
			<div class="modal-header border-bottom-0 pb-0">
				<h5 class="modal-title fw-bold" id="modalTitle">Konfigurasi Provider</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form id="formProvider">
					@csrf
					<input type="hidden" id="provider_id" name="id">

					<div class="mb-3">
						<label class="form-label small fw-bold text-uppercase text-muted">Nama Provider</label>
						<input type="text" class="form-control" name="name" id="name" required placeholder="Contoh: Digiflazz">
					</div>

					<div class="row g-2 mb-3">
						<div class="col-6">
							<label class="form-label small fw-bold text-uppercase text-muted">Kode (Slug)</label>
							<input type="text" class="form-control bg-light" name="code" id="code" required placeholder="digiflazz">
							<div class="form-text" style="font-size: 0.7rem">Digunakan di controller.</div>
						</div>
						<div class="col-6">
							<label class="form-label small fw-bold text-uppercase text-muted">Mode</label>
							<select class="form-select select-center" name="mode" id="mode">
								<option value="development">Development</option>
								<option value="production">Production</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label small fw-bold text-uppercase text-muted">Base URL API</label>
							<div class="input-group">
								<span class="input-group-text bg-light"><i class="fas fa-link text-muted"></i></span>
								<input type="url" class="form-control" name="base_url" id="base_url" placeholder="https://">
							</div>
							<div class="form-text" style="font-size: 0.7rem">Endpoint utama koneksi ke supplier.</div>
						</div>
					</div>

					<hr class="my-4 border-dashed">

					<div class="mb-3">
						<label class="form-label small fw-bold text-uppercase text-muted">API Username / Buyer SKU</label>
						<input type="text" class="form-control" name="api_username" id="api_username">
					</div>

					<div class="mb-3">
						<label class="form-label small fw-bold text-uppercase text-muted">API Key (Production/Dev)</label>
						<div class="input-group">
							<input type="password" class="form-control" name="api_key" id="api_key">
							<button class="btn btn-outline-secondary" type="button" onclick="togglePassword('api_key')">
								<i class="fas fa-eye"></i>
							</button>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label small fw-bold text-uppercase text-muted">Secret Key / Webhook Secret</label>
						<div class="input-group">
							<input type="password" class="form-control" name="secret_key" id="secret_key">
							<button class="btn btn-outline-secondary" type="button" onclick="togglePassword('secret_key')">
								<i class="fas fa-eye"></i>
							</button>
						</div>
						<div class="form-text">Kosongkan jika tidak ada.</div>
					</div>
				</form>
			</div>
			<div class="modal-footer border-top-0 pt-0">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-primary fw-bold px-4" onclick="saveProvider()">Simpan</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	const modal = new bootstrap.Modal(document.getElementById('providerModal'));

	function openModal(type) {
		if(type === 'create') {
			$('#formProvider')[0].reset();
			$('#provider_id').val('');
			$('#modalTitle').text('Tambah Provider Baru');
			$('#code').prop('readonly', false).removeClass('text-muted');
		}
		modal.show();
	}

	// function editProvider(data) {
	// 	$('#provider_id').val(data.id);
	// 	$('#name').val(data.name);
	// 	$('#code').val(data.code).prop('readonly', true).addClass('text-muted'); // Code tidak boleh ubah sembarangan
	// 	$('#mode').val(data.mode);
	// 	$('#api_username').val(data.api_username);
	// 	$('#api_key').val(data.api_key);
	// 	$('#secret_key').val(data.secret_key);

	// 	$('#modalTitle').text('Edit Konfigurasi: ' + data.name);
	// 	modal.show();
	// }
	function editProvider(data) {
        $('#provider_id').val(data.id);
        $('#name').val(data.name);

        // Code
        $('#code').val(data.code).prop('readonly', true).addClass('text-muted');

        // Mode
        $('#mode').val(data.mode);

        // --- TAMBAHAN: BASE URL ---
        $('#base_url').val(data.base_url);
        // --------------------------

        $('#api_username').val(data.api_username);
        $('#api_key').val(data.api_key);
        $('#secret_key').val(data.secret_key);

        $('#modalTitle').text('Edit Konfigurasi: ' + data.name);
        modal.show();
    }

	function togglePassword(id) {
		let input = $('#' + id);
		if (input.attr('type') === 'password') {
			input.attr('type', 'text');
		} else {
			input.attr('type', 'password');
		}
	}

	// 1. SIMPAN PROVIDER
	function saveProvider() {
		let formData = $('#formProvider').serialize();
		let id = $('#provider_id').val();
		let url = id ? "{{ route('admin.providers.update', ':id') }}".replace(':id', id)
					 : "{{ route('admin.providers.store') }}";

		$.ajax({
			url: "{{ route('admin.providers.store') }}",
			type: 'POST',
			data: formData,
			success: function(res) {
				modal.hide();

				Swal.fire('Berhasil', res.meta.message, 'success').then(() => location.reload());
			},
			error: function(xhr) {
				var errorMessage = "Terjadi kesalahan pada server.";

				// Cek jika error validasi dari Laravel (422)
				if (xhr.status === 422) {
					var errors = xhr.responseJSON.data;

					errorMessage = "";
					$.each(errors, function(key, value) {
						errorMessage += value[0] + "<br>"; // List error
					});
				} else if(xhr.responseJSON && xhr.responseJSON?.meta?.message) {
					errorMessage = xhr.responseJSON.meta.message;
				}

				Swal.fire({
					title: 'Gagal!',
					html: errorMessage, // Pakai HTML agar bisa enter
					icon: 'error'
				});
			}
		});
	}

	// 2. CEK SALDO (REALTIME HIT API)
	function checkBalance(id) {
		let btn = $('#btn-refresh-' + id);
		let text = $('#balance-text-' + id);
		let originalIcon = btn.html();

		btn.html('<i class="fas fa-circle-notch fa-spin"></i>').prop('disabled', true);

		$.ajax({
			url: "{{ route('admin.providers.check-balance') }}",
			type: "POST",
			data: { id: id },
			success: function(res) {
				// Animasi angka update
				text.fadeOut(200, function() {
					$(this).text(res.data.balance).fadeIn(200);
				});
				Swal.fire({
					toast: true, position: 'top-end', icon: 'success',
					title: 'Saldo: ' + res.data.balance, showConfirmButton: false, timer: 3000
				});

				lastUpdate(id, res.data.last_update)
			},
			error: function(err) {
				Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Gagal terhubung ke provider' });
			},
			complete: function() {
				btn.html(originalIcon).prop('disabled', false);
			}
		});
	}

	// 3. TOGGLE STATUS (ON/OFF)
	function toggleStatus(id) {
		$.ajax({
			url: "{{ route('admin.providers.toggle-status') }}",
			type: "POST",
			data: { id: id},
			success: function(res) {
				Swal.fire({
					toast: true, position: 'top-end', icon: 'success',
					title: res.meta.message, showConfirmButton: false, timer: 2000
				})

				lastUpdate(id, res.data.last_update)
			}
		});
	}

	function lastUpdate(id,str) {
		console.log(str);

		$(`#last-update-${id}`).text(str)
	}
</script>
@endpush
