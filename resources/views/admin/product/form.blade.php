<div class="row py-5">
	<div class="col-md-8 mx-auto">
		<div class="card border-0 shadow-sm mb-4">
			<div class="card-header bg-info">
				<h4>{{ $title }}</h4>
			</div>
			<div class="card-body">
				<div class="row g-3 align-items-end">
					<div class="col-md-12">
						<form id="form-product">
							<input type="hidden" id="product-id" name="product_id" value="{{ $product?->id }}">

							{{-- SECTION 1: PROVIDER INFO & HEALTH STATUS (READ ONLY) --}}
							<div class="card border-0 bg-primary bg-opacity-10 rounded-3 mb-4">
								<div class="card-body p-3">
									<div class="row align-items-center g-3">
										{{-- Provider Detail --}}
										<div class="col-md-5 border-end border-primary border-opacity-25">
											<small class="text-uppercase fw-bold text-primary" style="font-size: 0.65rem; letter-spacing: 1px;">SOURCE PROVIDER</small>
											<div class="d-flex align-items-center mt-1">
												<i class="fa-solid fa-server text-primary fs-4 me-3"></i>
												<div style="line-height: 1.2;">
													<h6 class="fw-bold text-dark mb-0">{{ $product?->provider?->name ?? '-' }}</h6>
													<span class="font-monospace text-muted small"><i class="fa-solid fa-barcode me-1"></i>{{ $product?->buyer_sku_code ?? '-' }}</span>
												</div>
											</div>
										</div>

										{{-- Status Seller (Dari Pusat) --}}
										<div class="col-6 col-md-3 text-center">
											<small class="text-muted fw-bold d-block mb-1" style="font-size: 0.7rem;">STATUS SELLER (PUSAT)</small>
											@if($product?->seller_product_status)
												<span class="badge bg-success bg-opacity-75 text-white rounded-pill px-3 py-2 shadow-sm">
													<i class="fa-solid fa-check-circle me-1"></i> NORMAL
												</span>
											@else
												<span class="badge bg-danger bg-opacity-75 text-white rounded-pill px-3 py-2 shadow-sm">
													<i class="fa-solid fa-ban me-1"></i> GANGGUAN
												</span>
											@endif
										</div>

										{{-- Status Buyer (Ke User) --}}
										<div class="col-6 col-md-3 text-center border-start border-primary border-opacity-25">
											<small class="text-muted fw-bold d-block mb-1" style="font-size: 0.7rem;">STATUS API (BUYER)</small>
											@if($product?->buyer_product_status)
												<span class="badge bg-success bg-opacity-75 text-white rounded-pill px-3 py-2 shadow-sm">
													<i class="fa-solid fa-wifi me-1"></i> CONNECTED
												</span>
											@else
												<span class="badge bg-danger bg-opacity-75 text-white rounded-pill px-3 py-2 shadow-sm">
													<i class="fa-solid fa-link-slash me-1"></i> DISCONNECT
												</span>
											@endif
										</div>
									</div>
								</div>
							</div>

							{{-- SECTION 2: GENERAL SETTINGS --}}
							<h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-sliders me-2"></i>Pengaturan Produk</h6>
							<div class="row g-3 mb-4">
								<div class="col-md-8">
									<label class="form-label small fw-bold text-muted">Nama Produk (Custom)</label>
									<div class="input-group">
										<span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-tag"></i></span>
										<input type="text" class="form-control border-start-0 ps-0" id="edit_product_name" name="product_name"
											placeholder="Nama tampilan di aplikasi user" value="{{ $product?->product_name ?? '' }}" autocomplete="off">
									</div>
								</div>

								<div class="col-md-4">
									<label class="form-label small fw-bold text-muted">Status Jual</label>
									<div class="card border px-3 py-2">
										<div class="form-check form-switch d-flex align-items-center justify-content-between ps-0 mb-0">
											<label class="form-check-label fw-bold cursor-pointer" for="product-status">Aktifkan?</label>
											<input class="form-check-input ms-auto cursor-pointer" type="checkbox" id="product-status" name="product_status" {{ $product?->status ? 'checked' : '' }} style="transform: scale(1.2);">
										</div>
									</div>
								</div>
							</div>

							{{-- SECTION 3: PRICING & PROFIT --}}
							<div class="card border-0 shadow-sm" style="background: #fdfdfd;">
								<div class="card-body p-3">
									<h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-coins me-2"></i>Harga & Keuntungan</h6>

									<div class="row g-3 align-items-center">
										{{-- Harga Modal --}}
										<div class="col-md-4">
											<label class="form-label small fw-bold text-muted">Modal (Server)</label>
											<div class="input-group">
												<span class="input-group-text bg-light text-muted border-end-0">Rp</span>
												<input type="text" class="form-control bg-light text-dark fw-bold border-start-0"
												id="price" value="{{ $product?->price_nf ?? '' }}" readonly disabled>
											</div>
										</div>

										{{-- Icon Arrow --}}
										<div class="col-md-1 text-center d-none d-md-block pt-4 text-muted opacity-50">
											<i class="fa-solid fa-arrow-right"></i>
										</div>

										{{-- Harga Jual --}}
										<div class="col-md-4">
											<label class="form-label small fw-bold text-success">Harga Jual</label>
											<div class="input-group shadow-sm">
												<span class="input-group-text bg-success text-white border-success">Rp</span>
												<input type="text" class="form-control fw-bold text-success border-success"
												id="selling-price" name="selling_price" value="{{ $product?->selling_price_nf ?? '' }}" autocomplete="off">
											</div>
										</div>

										{{-- Profit Badge --}}
										<div class="col-md-3">
											<label class="form-label small fw-bold text-muted d-block text-center">Estimasi Profit</label>
											<div id="profit-box" class="rounded-3 py-2 px-1 text-center border" style="background: #f8f9fa; transition: all 0.3s;">
												<h5 class="fw-bold mb-0" id="edit-profit">Rp 0</h5>
											</div>
										</div>
									</div>
								</div>
							</div>

							{{-- SECTION 4: E-MONEY CONFIG --}}
							<div id="container-e-money" class="row mt-3 g-3">
								<div class="col-12"><div class="d-flex align-items-center"><hr class="flex-grow-1"><span class="mx-3 text-muted small fw-bold">Konfigurasi E-Money</span><hr class="flex-grow-1"></div></div>
								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Nominal Min</label>
									<div class="input-group">
										<span class="input-group-text bg-white text-muted">Rp</span>
										<input type="text" class="form-control fw-bold text-dark"
										id="min-value" name="min_value" value="{{ $product?->min_value_nf ?? '' }}">
									</div>
								</div>

								<div class="col-md-6">
									<label class="form-label small fw-bold text-muted">Nominal Max</label>
									<div class="input-group">
										<span class="input-group-text bg-white text-muted">Rp</span>
										<input type="text" class="form-control fw-bold text-dark"
										id="max-value" name="max_value" value="{{ $product?->max_value_nf ?? '' }}" autocomplete="off">
									</div>
								</div>
							</div>

						</form>

						{{-- ACTION BUTTONS --}}
						<div class="d-flex justify-content-end gap-2 mt-5 pt-3 border-top">
							<button type="button" class="btn btn-light border btn-kembali px-4">Batal</button>
							<button type="button" class="btn btn-primary px-4 fw-bold shadow-sm btn-loader btn-store">
								<i class="fa-solid fa-save me-2"></i> {{ $product ? 'Simpan Perubahan' : 'Simpan Produk' }}
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(() => {
		const category = "{{ $product?->category_id }}"
		isMoney = category == 7;
		$('#container-e-money').toggleClass('d-none', !isMoney);

		calculateProfit()
	})

	$('#second-container').off('click', '.btn-kembali').on('click', '.btn-kembali', function() {
		$('#second-container').fadeOut(400, function() {
			$('#main-container').fadeIn(400)
			$('#second-container').empty()
		})
	})

	$('#min-value').setRules('0-9').on('keyup', (e)=>{
		$this = $(e.currentTarget)
		$this.val($formatter.formatNumberId($this.val()))
	})
	$('#max-value').setRules('0-9').on('keyup', (e)=>{
		$this = $(e.currentTarget)
		$this.val($formatter.formatNumberId($this.val()))
	})

	$('#selling-price').setRules('0-9').on('keyup', (e)=>{
		$this = $(e.currentTarget)
		$this.val($formatter.formatNumberId($this.val()))

		calculateProfit()
	})

	$('.btn-store').click(async function(e){
		const currentText = $(this).text()
		$(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...')

		// const payload = new FormData($('#form-product')[0])
		const payload = $('#form-product').serialize()

		const {status, data: {data}, data: {meta}} = await postRequest("{{route('admin.products.items.store')}}", payload);

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

	function calculateProfit(){
		const modal = Number(module.parse.onlyNumber($('#price').val())) || 0;
		const jual  = Number(module.parse.onlyNumber($('#selling-price').val())) || 0;

		const profit = jual - modal;
		const isLoss = profit < 0;

		const formatted = $formatter.formatRupiah(Math.abs(profit));
		const profitText = `${isLoss ? '-' : profit > 0 ? '+' : ''}${formatted}`;

		const profitEl = $('#edit-profit');
		const boxEl = $('#profit-box');

		profitEl
			.text(profitText)
			.toggleClass('text-success', !isLoss)
			.toggleClass('text-danger', isLoss);

		boxEl.css(
			'background',
			isLoss ? '#fff5f5' : '#f0fff4'
		);
	}
</script>
