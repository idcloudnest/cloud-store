<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" style="background: var(--card-bg); color: var(--text-main);">
			<div class="modal-header border-secondary">
				<h5 class="modal-title fw-bold" id="modalTitle">Top Up</h5>
				<button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body p-4">
				<form id="topupForm">
					<div class="mb-4">
						<h6 class="text-primary fw-bold mb-3"><i class="fas fa-user-circle me-2"></i>1. Masukkan Data</h6>
						<div class="row g-2">
							<div class="col-7">
								<input type="text" class="form-control" placeholder="User ID / No. HP" required>
							</div>
							<div class="col-5" id="zoneIdInput">
								<input type="text" class="form-control" placeholder="Zone ID">
							</div>
						</div>
					</div>

					<div class="mb-4">
						<h6 class="text-primary fw-bold mb-3"><i class="fas fa-coins me-2"></i>2. Pilih Nominal</h6>
						<div class="row g-2" id="nominalContainer">
						</div>
					</div>

					<div class="mb-4">
						<h6 class="text-primary fw-bold mb-3"><i class="fas fa-wallet me-2"></i>3. Pembayaran</h6>
						<div class="row g-2">
							<div class="col-4">
								<button type="button" class="btn btn-outline-secondary w-100 py-3"><i class="fas fa-qrcode mb-1"></i><br>QRIS</button>
							</div>
							<div class="col-4">
								<button type="button" class="btn btn-outline-secondary w-100 py-3"><i class="fas fa-wallet mb-1"></i><br>E-Money</button>
							</div>
							<div class="col-4">
								<button type="button" class="btn btn-outline-secondary w-100 py-3"><i class="fas fa-university mb-1"></i><br>Bank</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer border-secondary">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-primary fw-bold px-4" id="btnBeliSekarang">Bayar Sekarang</button>
			</div>
		</div>
	</div>
</div>
