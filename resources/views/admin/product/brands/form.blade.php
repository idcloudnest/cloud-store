<div class="row py-5">
	<div class="col-md-8 mx-auto">
		<div class="card border-0 shadow-sm mb-4">

			{{-- HEADER --}}
			<div class="card-header bg-info text-white">
				<h4 class="mb-0">{{ $title }}</h4>
			</div>

			<div class="card-body">
				<form id="form-brand">

					<input type="hidden" name="brand_id" value="{{ $brand?->id }}">

					{{-- SECTION 1: BRAND INFO (READ ONLY) --}}
					<div class="card border-0 bg-primary bg-opacity-10 rounded-3 mb-4">
						<div class="card-body p-3">
							<div class="row align-items-center g-3">

								{{-- Brand Identity --}}
								<div class="col-md-6 border-end border-primary border-opacity-25">
									<small class="text-uppercase fw-bold text-primary" style="font-size: 0.65rem; letter-spacing: 1px;">
										BRAND IDENTITAS
									</small>

									<div class="d-flex align-items-center mt-2">
										@if($brand?->image)
										@php $assetParse = $brand->image ? config('app.asset_url').assetParse($brand->image) : '#'; @endphp
											<img src="{{ $assetParse }}" class="rounded me-3"
												style="width:auto;height:48px;object-fit:contain;background:#fff;">
										@else
											<i class="fa-solid fa-tags fs-3 text-primary me-3"></i>
										@endif

										<div>
											<h6 class="fw-bold mb-0">{{ $brand?->name ?? '-' }}</h6>
											<span class="font-monospace text-muted small">
												/{{ $brand?->slug ?? '-' }}
											</span>
										</div>
									</div>
								</div>

								{{-- Status --}}
								<div class="col-md-6 text-center">
									<small class="text-muted fw-bold d-block mb-1" style="font-size:0.7rem;">
										STATUS BRAND
									</small>

									@if($brand?->status)
										<span class="badge bg-success bg-opacity-75 rounded-pill px-4 py-2">
											<i class="fa-solid fa-check-circle me-1"></i> AKTIF
										</span>
									@else
										<span class="badge bg-danger bg-opacity-75 rounded-pill px-4 py-2">
											<i class="fa-solid fa-ban me-1"></i> NONAKTIF
										</span>
									@endif
								</div>

							</div>
						</div>
					</div>

					{{-- SECTION 2: GENERAL SETTINGS --}}
					<h6 class="fw-bold text-secondary mb-3">
						<i class="fa-solid fa-sliders me-2"></i>Pengaturan Brand
					</h6>

					<div class="row g-3 mb-4">
						<div class="col-md-4">
							<label class="form-label small fw-bold text-muted">Nama Brand</label>
							<div class="input-group">
								<span class="input-group-text bg-white border-end-0 text-muted">
									<i class="fa-solid fa-tag"></i>
								</span>
								<input type="text"
									class="form-control border-start-0 ps-0"
									name="name"
									value="{{ $brand?->name }}"
									placeholder="Nama brand"
									autocomplete="off">
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label small fw-bold text-muted">Nama Brand</label>
							<select name="category_id" id="category-id">
								<option value="" selected disabled>-- PILIH CATEGORY --</option>
								@if (count($category))
									@foreach ($category as $item)
										<option value="{{ $item->id }}" {{ $brand?->category_id == $item->id ? 'selected' : '' }}>{{ strtoupper($item->name) }}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label small fw-bold text-muted">Status</label>
							<div class="card border px-3 py-2">
								<div class="form-check form-switch d-flex justify-content-between ps-0 mb-0">
									<label class="form-check-label fw-bold cursor-pointer">
										Aktifkan?
									</label>
									<input class="form-check-input ms-auto"
										type="checkbox"
										name="status"
										value="1"
										{{ $brand?->status ? 'checked' : '' }}
										style="transform: scale(1.2);">
								</div>
							</div>
						</div>
					</div>

					{{-- SECTION 3: SLUG & STYLE --}}
					<div class="card border-0 shadow-sm mb-4" style="background:#fdfdfd;">
						<div class="card-body p-3">
							<h6 class="fw-bold text-secondary mb-3">
								<i class="fa-solid fa-palette me-2"></i>Slug & Tampilan
							</h6>

							<div class="row g-3">
								<div class="col-md-8">
									<label class="form-label small fw-bold text-muted">Slug URL</label>
									<input type="text"
										class="form-control font-monospace"
										name="slug"
										value="{{ $brand?->slug }}"
										placeholder="contoh: mobile-legends">
								</div>

								<div class="col-md-4">
									<label class="form-label small fw-bold text-muted">Warna Brand</label>
									<input type="color"
										class="form-control form-control-color w-100"
										name="color"
										value="{{ $brand?->color ?? '#000000' }}">
								</div>
							</div>
						</div>
					</div>

					{{-- SECTION 4: MEDIA --}}
					<div class="card border-0 shadow-sm">
						<div class="card-body p-3">
							<h6 class="fw-bold text-secondary mb-3">
								<i class="fa-solid fa-image me-2"></i>Logo & Icon
							</h6>

							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Logo Brand</label>
									<input type="file" name="image" class="form-control">
									<small class="text-muted">Kosongkan jika tidak diganti</small>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Icon (Opsional)</label>
									<input type="file" name="icon" class="form-control">
								</div>
							</div>
						</div>
					</div>

				</form>

				{{-- ACTION BUTTON --}}
				<div class="d-flex justify-content-between gap-2 mt-5 pt-3 border-top">
					<button type="button" class="btn btn-light border btn-kembali px-4">
						Batal
					</button>
					<button type="button"
						class="btn btn-primary px-4 fw-bold shadow-sm btn-loader btn-store">
						<i class="fa-solid fa-save me-2"></i>
						{{ $brand ? 'Simpan Perubahan' : 'Simpan Brand' }}
					</button>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(() => {
		$('#category-id').select2({
			theme: 'bootstrap-5',
			// dropdownParent: $('#assignModal'),
			placeholder: '-- PILIH CATEGORY --',
			width: '100%'
		});
	})
	// $(document).on('load', function () {
	// })

	$('#second-container').off('click', '.btn-kembali').on('click', '.btn-kembali', function() {
		$('#second-container').fadeOut(400, function() {
			$('#main-container').fadeIn(400)
			$('#second-container').empty()
		})
	})

	$('.btn-store').click(async function(e){
		const currentText = $(this).text()
		$(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...')

		const payload = new FormData($('#form-brand')[0])
		// const payload = $('#form-brand').serialize()

		const {status, data: {data}, data: {meta}} = await postRequest("{{route('admin.products.brands.store')}}", payload);

		if (status !== 200) {
			await $swal.warning({
				text: meta?.message,
				hideClass: module.var_swal.fadeOutUp,
			})

			return $(this).attr('disabled', false).html(currentText)
		}

		await $swal.success({
			title: meta.message,
			text: '',
			showClass: module.var_swal.fadeInDown,
			hideClass: module.var_swal.fadeOutUp,
		})

		$(this).attr('disabled', false).html(currentText)

		$('#second-container').hide('slow', async function () {
			await $('#main-container').fadeIn()
			await $('#second-container').empty()
			table.draw()
		})
	})
</script>
