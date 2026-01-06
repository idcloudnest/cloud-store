@extends('layouts.admin')

@section('title', 'Input Transaksi Manual')

@section('content')
<div class="row justify-content-center">
	<div class="col-lg-10">

		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h4 class="mb-1 fw-bold text-dark">Transaksi Manual</h4>
				<p class="text-muted small mb-0">Pilih kategori untuk memulai transaksi.</p>
			</div>
		</div>

		<div class="row g-3 mb-4" id="category-grid">
			@foreach($categories as $cat)
			<div class="col-6 col-md-3 col-lg">
				<div class="card border-0 shadow-sm h-100 category-card cursor-pointer btn-anim"
					 data-category="{{ $cat->category }}"
					 onclick="selectCategory(this, '{{ $cat->category }}', '{{ $cat->category }}')">
					<div class="card-body text-center p-3">
						<div class="icon-wrapper mb-2 text-{{ $cat->category_color }}">
							<i class="fas {{ $cat->category_icon }} fa-2x"></i>
						</div>
						<span class="small fw-bold text-uppercase d-block">{{ $cat->category }}</span>
					</div>
				</div>
			</div>
			@endforeach
		</div>

		<div class="card border-0 shadow-sm d-none" id="transaction-card" style="border-radius: 16px;">
			<div class="card-header bg-white border-bottom py-3">
				<h6 class="mb-0 fw-bold text-primary">
					<i class="fas fa-keyboard me-2"></i> INPUT <span id="selected-category-title">Transaksi</span>
				</h6>
			</div>
			<div class="card-body p-4">
				<form id="form-transaction" action="{{ route('admin.transactions.store') }}" method="POST">

					{{-- Hidden Input untuk menyimpan kategori yang dipilih --}}
					<input type="hidden" name="category_id" id="hidden_category_id">

					<div class="row g-4">
						{{-- Dropdown Khusus Game (Brand) --}}
						<div class="col-md-12 d-none" id="game-brand-box">
							<label class="form-label text-muted small fw-bold text-uppercase">Pilih Game</label>
							<select class="form-select" id="game-brand-select" data-placeholder="-- PILIH GAME --">
								{{-- List Game akan di-load via AJAX --}}
							</select>
						</div>

						{{-- User Select --}}
						<div class="col-md-6">
							<label class="form-label text-muted small fw-bold text-uppercase">Pelanggan</label>
							<select class="form-select" id="user-id" name="user_id" required>
								<option value="" disabled>-- Cari Pengguna --</option>
								<option value="{{ auth()->id() }}" class="fw-bold bg-light" data-balance="{{ auth()->user()->balance_formatted }}" data-self="1">
									SAYA SENDIRI
									{{-- <div class="d-flex justify-content-between align-items-center w-100">
										<span class="fw-bold">★ SAYA SENDIRI</span>
										<span class="badge bg-success bg-opacity-10 text-success rounded-pill">{{ auth()->user()->balance_formatted }}</span>
									</div> --}}
								</option>
								@foreach($users as $user)
									@if($user->id != auth()->id())
										<option value="{{ $user->id }}" data-balance="{{ $user->balance_formatted }}">{{ $user->username }}</option>
									@endif
								@endforeach
							</select>
						</div>

						<div class="col-md-6">
							<label id="label-target" class="form-label text-muted small fw-bold text-uppercase">Nomor Tujuan</label>

							{{-- A. INPUT STANDARD (Pulsa, PLN, E-Money, dll) --}}
							<div class="input-group custom-input-group rounded-3 overflow-hidden" id="standard-input-box">
								<span class="input-group-text border-0 bg-transparent text-muted ps-3 border-end">
									<i id="icon-target" class="fas fa-phone-alt text-muted"></i>
								</span>

								{{-- Input Utama (Yang akan dikirim ke Controller) --}}
								<input type="text" class="form-control border-0 bg-transparent shadow-none text-body"
								id="target" name="target" placeholder="Masukan Nomor..." autocomplete="off" oninput="getUsername(this)">

								{{-- Loading Icon --}}
								<span class="input-group-text border-0 bg-transparent text-primary" id="loading-icon" style="display: none;">
									<i class="fas fa-circle-notch fa-spin"></i>
								</span>
							</div>

							{{-- B. INPUT KHUSUS GAMES (User ID + Server ID) --}}
							<div class="row g-2 d-none" id="game-input-box">
								<div class="col-7">
									<div class="input-group rounded-3 overflow-hidden border">
										<span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-user"></i></span>
										<input type="text" class="form-control border-0 shadow-none bg-white cursor-disabled"
										id="game_user_id" name="user_id" placeholder="User ID (Ex: 123456)" data-type="user-id" oninput="getUsername(this)" disabled>
									</div>
								</div>
								<div class="col-5">
									<div class="input-group rounded-3 overflow-hidden border">
										<span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-server"></i></span>
										<input type="text" class="form-control border-0 shadow-none bg-white cursor-disabled"
										id="game_server_id" name="server_id" placeholder="Zone/Server" data-type="server-id" oninput="getUsername(this)" disabled>
									</div>
								</div>
							</div>

							{{-- Result Nama Pelanggan --}}
							<div id="customer-name-result" class="form-text fw-bold mt-2 ps-1" style="display: none;"></div>
						</div>

						{{-- Product Select --}}
						<div class="col-md-12">
							<label class="form-label text-muted small fw-bold text-uppercase">Pilih Produk</label>
							<select class="form-select" id="product-code" name="product_code" required disabled data-placeholder="-- SILAHKAN PILIH PRODUK --">
							</select>
						</div>

						{{-- Custom Price & Supplier (Opsional) bisa ditaruh di collapse/accordion biar rapi --}}
						 <div class="col-md-6">
							<label class="form-label text-muted small fw-bold text-uppercase">Harga Jual (Override)</label>
							<div class="input-group">
								<span class="input-group-text bg-light">Rp</span>
								<input type="number" class="form-control" name="custom_price" placeholder="Default">
							</div>
						</div>
					</div>

					<div class="mt-4 text-end">
						<button type="button" onclick="storeTransaction()" id="btn-submit" class="btn btn-primary w-100 py-2 fw-bold">
							<i class="fas fa-paper-plane me-2"></i> Proses Transaksi
						</button>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>
@endsection

@push('styles')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<style>
		.cursor-pointer { cursor: pointer; }

		/* Style untuk Card Kategori saat Active */
		.category-card { transition: all 0.2s ease; border: 2px solid transparent !important;}
		.category-card:hover { transform: translateY(-5px); }

		.category-card.active {
			border-color: var(--primary-color) !important;
			background-color: rgba(102, 126, 234, 0.05);
		}
		.category-card.active .icon-wrapper { transform: scale(1.1); }
	</style>
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		// --- VARIABLES GLOBAL ---
		let currentCategory = '';
		let typingTimer;
		const doneTypingInterval = 1000; // Jeda waktu 1 detik (1000ms)

		// Elemen Cache
		const $targetInput = $('#target');
		const $loadingIcon = $('#loading-icon');
		const $resultDiv   = $('#customer-name-result');
		const $categoryId  = $('#category-id');

		// Konfigurasi Placeholder & Icon per kategori
		const config = {
			'pulsa':      { label: 'Nomor Handphone', icon: 'fa-mobile-alt', placeholder: '0812xxxx' },
			'data':       { label: 'Nomor Handphone', icon: 'fa-wifi',       placeholder: '0812xxxx' },
			'pln':        { label: 'Nomor Meter / ID Pel', icon: 'fa-bolt',  placeholder: '5145xxxx' },
			'games':      { label: 'ID Player (Zone ID)', icon: 'fa-gamepad',placeholder: '123456 (1234)' },
			'e-money':    { label: 'Nomor E-Wallet',  icon: 'fa-wallet',     placeholder: '0812xxxx' },
			'streaming':  { label: 'Nomor HP / Akun', icon: 'fa-play',       placeholder: '0812xxxx' },
			'masa-aktif': { label: 'Nomor Handphone', icon: 'fa-clock',      placeholder: '0812xxxx' }
		};

		$(document).ready(() => {
			$('#user-id').select2({
				theme: 'bootstrap-5',
				width: '100%',
				placeholder: '-- Pilih Kategori Terlebih Dahulu --',
				dropdownParent: $('#transaction-card'), // Agar dropdown tidak tertutup overflow card/modal
				templateResult: formatUser,    // Custom tampilan list opsi
				templateSelection: formatUser,  // Custom tampilan saat dipilih
				escapeMarkup: m => m
			}).on('select2:open', function() {
				// Cari field search di dalam container yang sedang terbuka, lalu fokuskan
				document.querySelector('.select2-search__field').focus();
			})
			function formatUser(user) {
				if (!user.id) return user.text;

				const balance = $(user.element).data('balance') ?? '-';
				const isSelf  = $(user.element).data('self');

				return `
					<div class="d-flex justify-content-between align-items-center">
						<span class="${isSelf ? 'fw-bold text-info' : ''}">
							${isSelf ? '★ ' : ''}${user.text}
						</span>
						<span class="badge bg-success bg-opacity-10 text-success rounded-pill">
							${balance}
						</span>
					</div>
				`;
			}
			// function formatUserSelection(user) {
			// 	if (!user.id) return user.text;

			// 	const balance = $(user.element).data('balance') ?? '';
			// 	const isSelf  = $(user.element).data('self');

			// 	return `${isSelf ? '★ ' : ''}${user.text} (${balance})`;
			// }


			$('#product-code').select2({
				theme: 'bootstrap-5',
				width: '100%',
				placeholder: '-- Pilih Kategori Terlebih Dahulu --',
				dropdownParent: $('#transaction-card'), // Agar dropdown tidak tertutup overflow card/modal
				templateResult: formatProductOption,    // Custom tampilan list opsi
				templateSelection: formatProductOption,  // Custom tampilan saat dipilih
				escapeMarkup: function(markup) { return markup }
			}).on('select2:open', function() {
				// Cari field search di dalam container yang sedang terbuka, lalu fokuskan
				document.querySelector('.select2-search__field').focus();
			})

			$('#game-brand-select').select2({
				theme: 'bootstrap-5',
				width: '100%',
				dropdownParent: $('#transaction-card'),
				templateResult: formatProductOption,    // Custom tampilan list opsi
				templateSelection: formatProductOption,  // Custom tampilan saat dipilih
				escapeMarkup: function(markup) { return markup }
			}).on('select2:open', function() {
				// Cari field search di dalam container yang sedang terbuka, lalu fokuskan
				document.querySelector('.select2-search__field').focus();
			})

			// Event saat Brand Game dipilih (Mobile Legends / Free Fire)
			$('#game-brand-select').on('change', function() {
				var brandName = $(this).val();

				if (brandName) {
					$('#game_user_id').attr('disabled', false).removeClass('cursor-disabled')
					$('#game_server_id').attr('disabled', false).removeClass('cursor-disabled')
					$('#product-code').attr('disabled', false)
				} else {
					$('#game_user_id').attr('disabled', true).addClass('cursor-disabled')
					$('#game_server_id').attr('disabled', true).addClass('cursor-disabled')
					$('#product-code').attr('disabled', true)
				}

				// Load produk berdasarkan Nama Brand (bukan kategori 'games' lagi)
				loadProducts(brandName, true);
			});

			function formatProductOption(state) {
				if (!state.id) return state.text; // Return text default untuk placeholder

				if (state.id === '#') return $('<span class="text-success fw-bold"><i class="fas fa-circle-check me-1"></i> ' + state.text + '</span>');
				if (state.id === '##') return $('<span class="text-primary fw-bold"><i class="fas fa-exclamation-circle me-1"></i> ' + state.text + '</span>');
				if (state.id === '###') return $('<span class="text-danger fw-bold"><i class="fas fa-circle-xmark me-1"></i> ' + state.text + '</span>');

				// Ambil data harga dari attribut data-price (nanti kita set saat AJAX)
				var price = $(state.element).data('price');
				var formattedPrice = price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(price) : '';

				// ['#000000', '#00b8dd', '#00b30f']
				// Return HTML custom (Nama Kiri - Harga Kanan)
				var $state = $(
					'<div class="d-flex justify-content-between align-items-center w-100">' +
						'<span class="fw-bold">' + state.text + '</span>' +
						'<span class="badge bg-success bg-opacity-10 text-success rounded-pill">' + formattedPrice + '</span>' +
						// '<span style="color: #00b8dd !important; border-color: #00b8dd !important;">' + formattedPrice + '</span>' +
					'</div>'
				);
				return $state;
			}
		})

		function getUsername(self) {
			const $this = $(self)
			const type = $this.data('type')

			let currentVal = $this.val()
			if (/[^0-9]/.test(currentVal)) return self.value = currentVal.replace(/[^0-9]/g, '');

			// Bersihkan state sebelumnya
			clearTimeout(typingTimer)
			$resultDiv.slideUp()

			let isPln = currentCategory.includes('pln')

			if (isPln && currentVal.length >= 11) {
				typingTimer = setTimeout(function () {
					checkUsername({
						category: currentCategory,
						target: currentVal,
					});
				}, doneTypingInterval);
			}

			let isGames = currentCategory.includes('games')

			const isUser = type == 'user-id'
			const isServer = type == 'server-id'
			if (isGames && ( (isUser && currentVal >= 5 && $('#game_server_id').val().length >= 3) || (isServer && currentVal >= 3 && $('#game_user_id').val().length >= 5) )) {
				console.log($('#game-brand-select').find(':selected').data('type'));

				typingTimer = setTimeout(function () {
					checkUsername({
						category: currentCategory,
						code_game: $('#game-brand-select').find(':selected').data('type'),
						user_id: $('#game_user_id').val(),
						server_id: $('#game_server_id').val(),
					});
				}, doneTypingInterval);
			}
		}

		function selectCategory(el, catId, catName) {
			currentCategory = catId;

			// ... (Kode UI Highlight Card & Reset Input tetap sama) ...
			$('.category-card').removeClass('active');
			$(el).addClass('active');
			$('#transaction-card').removeClass('d-none').addClass('fade-in');
			$('#target').val('');
			$('#game_user_id').val('');
			$('#game_server_id').val('');
			$('#customer-name-result').hide();
			$('#selected-category-title').text(catName.toUpperCase());
			$('#hidden_category_id').val(catId);

			// Reset Dropdown Produk
			$('#product-code').empty().trigger('change');

			// --- LOGIC BARU ---
			if (catId === 'games') {
				// 1. Tampilkan Input Khusus Game (2 Kolom)
				$('#standard-input-box').addClass('d-none');
				$('#game-input-box').removeClass('d-none');
				$('#label-target').text('Detail Akun Game');

				// 2. Tampilkan Dropdown Pilih Game & Load Datanya
				$('#game-brand-box').removeClass('d-none');
				loadGameBrands(catId);

			} else {
				// Mode Normal (Pulsa/PLN)
				$('#standard-input-box').removeClass('d-none');
				$('#game-input-box').addClass('d-none');
				$('#game-brand-box').addClass('d-none'); // Sembunyikan pilih game

				// Setup Label & Icon Normal
				const conf = config[catId] || config['pulsa'];
				$('#label-target').text(conf.label);
				$('#target').attr('placeholder', conf.placeholder);
				$('#icon-target').attr('class', 'fas ' + conf.icon + ' text-muted');

				// Langsung Load Produk berdasarkan Kategori
				loadProducts(catId, false);
			}
		}

		function loadGameBrands(category) {
			var brandSelect = $('#game-brand-select');
			brandSelect.empty().append('<option value="##" selected disabled>LOADING GAME...</option>');
			brandSelect.prop('disabled', true);

			$('#product-code').empty().append('<option value="##" selected disabled>-- SILAHKAN PILIH GAME DULU --</option>')

			$.ajax({
				url: "{{ route('admin.products.items.get-brands-by-category') }}", // Sesuaikan route Anda
				type: "POST",
				data: { category: category },
				dataType: "json",
				success: function(data) {

					brandSelect.empty().append('<option value="#" selected disabled>-- PILIH GAME --</option>');

					$.each(data?.data, function(key, value) {
						// Value.brand adalah nama game (Mobile Legends, Free Fire)
						// toLowerCase().replace(/\s+/g, '-')
						brandSelect.append(`<option value="${value.brand}" data-type="${value.brand.toLowerCase().replace(/\s+/g, '-')}">${value.brand}</option>`);
					});

					brandSelect.prop('disabled', false);
					brandSelect.trigger('change');
				}
			});
		}
		function loadProducts(identifier, isBrandMode = false) {
			var productSelect = $('#product-code');

			// Jangan load jika identifier kosong
			if(!identifier) return;

			productSelect.empty().append('<option value="##" selected disabled>SEDANG MEMUAT PRODUK...</option>');
			productSelect.prop('disabled', true);
			productSelect.trigger('change');

			// Siapkan Payload Data
			var payload = {};

			if (isBrandMode) {
				payload.brand = identifier; // Kirim sebagai 'brand' jika mode game
			} else {
				payload.category = identifier; // Kirim sebagai 'category' jika mode pulsa/pln
			}

			$.ajax({
				url: "{{ route('admin.products.items.getProductsByCategory') }}",
				type: "POST",
				data: payload,
				dataType: "json",
				success: function(data) {
					productSelect.empty();

					if (data.data.length > 0) {
						productSelect.append('<option value="#" selected disabled>-- PILIH PRODUK --</option>');
						$.each(data.data, function(key, value) {
							var price = value.selling_price ?? value.price;
							// productSelect.append(`<option value="${value.buyer_sku_code}" data-price="${price}">${value.product_name}</option>`);
							productSelect.append(`<option value="${value.id}" data-price="${price}">${value.product_name}</option>`);
						});
						productSelect.prop('disabled', false);
					} else {
						productSelect.append('<option value="###" disabled selected>-- PRODUK BELUM TERSEDIA --</option>');
					}
					productSelect.trigger('change');
				},
				error: function() {
					productSelect.empty().append('<option value="###" disabled selected>Gagal memuat produk</option>');
					productSelect.trigger('change');
				}
			});
		}

		function setupInputLogic() {
			let typingTimer;

			// A. Listener untuk Input Standard (Pulsa/PLN)
			$('#target').on('input', function() {
				if(currentCategory === 'games') return; // Abaikan jika sedang mode game

				let val = $(this).val();
				let isPln = (currentCategory === 'pln');

				$('#customer-name-result').hide();
				clearTimeout(typingTimer);

				// Auto Check PLN
				if (isPln) {
					this.value = val.replace(/[^0-9]/g, ''); // Hanya angka
					if (this.value.length >= 11) {
						// typingTimer = setTimeout(() => processCheck(this.value, null, true), 1000);
						typingTimer = setTimeout(() => checkUsername({
							target: this.value
						}), 1000);
					}
				}
			});

			// B. Listener untuk Input Game (User ID + Server ID)
			// $('#game_user_id, #game_server_id').on('input', function() {
			// 	let userId = $('#game_user_id').val();
			// 	let serverId = $('#game_server_id').val();

			// 	// GABUNGKAN UserID dan ServerID ke dalam #target (Hidden logic)
			// 	// Format gabungan tergantung provider, umumnya: 123451234 (langsung gabung)
			// 	// atau ada yang minta format 12345|1234.
			// 	// Disini kita asumsi digabung langsung untuk dikirim ke API

			// 	let combined = userId + serverId;
			// 	$('#target').val(combined); // Masukkan ke input 'target' yang asli (hidden/visible)
			// });

			// // C. Tombol Cek Nickname Game
			// $('#btn-check-game').on('click', function() {
			// 	let userId = $('#game_user_id').val();
			// 	let serverId = $('#game_server_id').val();
			// 	let sku = $('#product-code').val();

			// 	if(!userId || !serverId) return Swal.fire('Info', 'User ID dan Server ID harus diisi', 'warning');
			// 	if(!sku) return Swal.fire('Info', 'Pilih produk game dulu', 'warning');

			// 	// Gabungkan untuk pengecekan
			// 	let combinedTarget = userId + serverId;
			// 	processCheck(combinedTarget, sku, false);
			// });
		}

		// REQUEST API
		function checkUsername(data) {
			// check-username
			$.ajax({
				url: "{{ route('api.provider.check-username') }}",
				type: "POST",
				data: data,
				beforeSend: function() {
					$loadingIcon.fadeIn()
					$targetInput.addClass('text-muted')
				},
				success: function(response) {
					if (response?.meta?.code === 200) {
						const data = response.data

						let info = `<i class="fas fa-check-circle me-1"></i> ${data.name ?? data}`;

						if (data.segment_power) info += ` <span class="badge bg-info text-dark ms-1">${data.segment_power}</span>`;

						$resultDiv.html(info)
								.removeClass('text-danger').addClass('text-success')
								.slideDown()
					} else {
						$resultDiv.html('<i class="fas fa-times-circle me-1"></i> ' + response?.meta?.message)
								.removeClass('text-success').addClass('text-danger')
								.slideDown()
					}
				},
				error: function(xhr) {
					let msg = xhr.responseJSON && xhr.responseJSON.meta.message ? xhr.responseJSON.meta.message : 'ID Pelanggan tidak ditemukan.';
					$resultDiv.html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg)
							.removeClass('text-success').addClass('text-danger')
							.slideDown()
				},
				complete: function() {
					$loadingIcon.fadeOut()
					$targetInput.removeClass('text-muted')
				}
			})
		}

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
								text: response?.meta?.message || 'Transaksi berhasil diproses.',
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
			});
		}
	</script>
@endpush
