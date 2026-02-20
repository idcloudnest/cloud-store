<div class="modal fade" id="topupModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 shadow">

			<div class="modal-header bg-light">
				<h5 class="modal-title fw-bold"><i class="fa-solid fa-wallet text-primary me-2"></i>Tambah Saldo Manual</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<div class="modal-body p-4">
				<div class="alert alert-info d-flex align-items-center mb-3">
					<div class="bg-white p-1 rounded-circle me-2 text-info">
						<i class="fa-solid fa-user"></i>
					</div>
					<div>
						<small class="d-block text-muted" style="line-height: 1;">Topup untuk:</small>
						<b id="modalUserName">Andi Saputra</b>
					</div>
				</div>

				<form>
					<div class="mb-3">
						<label class="form-label small fw-bold text-muted">Nominal Topup</label>
						<div class="input-group">
							<span class="input-group-text fw-bold bg-light">Rp</span>
							<input type="number" class="form-control fw-bold" placeholder="0" min="10000">
						</div>
						<div class="d-flex gap-2 mt-2">
							<button type="button" class="btn btn-outline-secondary btn-sm flex-fill">50rb</button>
							<button type="button" class="btn btn-outline-secondary btn-sm flex-fill">100rb</button>
							<button type="button" class="btn btn-outline-secondary btn-sm flex-fill">500rb</button>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label small fw-bold text-muted">Catatan (Opsional)</label>
						<textarea class="form-control" rows="2" placeholder="Contoh: Bonus Event, Refund TRX..."></textarea>
					</div>

					<button type="submit" class="btn btn-primary w-100 fw-bold py-2">
						<i class="fa-solid fa-paper-plane me-1"></i> Proses Topup
					</button>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content border-0 shadow">

			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2"></i>Tambah Member Baru</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
			</div>

			<div class="modal-body p-4">
				<form>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label small fw-bold text-muted">Nama Lengkap</label>
							<div class="input-group">
								<span class="input-group-text bg-light"><i class="fa-solid fa-id-card text-muted"></i></span>
								<input type="text" class="form-control" placeholder="Nama sesuai KTP">
							</div>
						</div>

						<div class="col-md-6">
							<label class="form-label small fw-bold text-muted">Nomor WhatsApp</label>
							<div class="input-group">
								<span class="input-group-text bg-light"><i class="fa-brands fa-whatsapp text-muted"></i></span>
								<input type="number" class="form-control" placeholder="08xxxxx">
							</div>
						</div>

						<div class="col-md-6">
							<label class="form-label small fw-bold text-muted">Email Aktif</label>
							<div class="input-group">
								<span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
								<input type="email" class="form-control" placeholder="alamat@email.com">
							</div>
						</div>

						<div class="col-md-6">
							<label class="form-label small fw-bold text-muted">Level Akun</label>
							<select class="form-select">
								<option value="member" selected>Member Biasa</option>
								<option value="reseller">Reseller (Harga Khusus)</option>
								<option value="vip">VIP Partner</option>
							</select>
						</div>

						<div class="col-12">
							<div class="bg-light p-3 rounded border d-flex align-items-start">
								<i class="fa-solid fa-key text-warning me-3 mt-1"></i>
								<div>
									<label class="small fw-bold text-dark d-block">Password Default</label>
									<div class="input-group input-group-sm mb-1" style="max-width: 200px;">
										<input type="text" class="form-control" value="123456" readonly>
										<button class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-copy"></i></button>
									</div>
									<small class="text-muted" style="font-size: 0.75rem;">
										User wajib mengganti password saat login pertama kali.
									</small>
								</div>
							</div>
						</div>
					</div>

					<div class="mt-4 text-end border-top pt-3">
						<button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
							<i class="fa-solid fa-save me-1"></i> Simpan Data
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
