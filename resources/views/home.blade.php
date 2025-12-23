<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ID Cloud Store - Topup Games & Voucher</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

	<style>
		/* --- VARIABLE SYSTEM (THEMING ENGINE) --- */
		:root {
			--primary-color: #4f46e5; /* Indigo */
			--secondary-color: #0ea5e9; /* Sky */
			--accent-color: #f59e0b;

			/* Default Light Mode Variables */
			--bg-body: #f8fafc;
			--bg-glass: rgba(255, 255, 255, 0.8);
			--text-main: #1e293b;
			--text-muted: #64748b;
			--card-bg: #ffffff;
			--card-border: #e2e8f0;
			--navbar-bg: rgba(255, 255, 255, 0.9);
			--shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
			--bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
		}

		/* Dark Mode Overrides */
		[data-bs-theme="dark"] {
			--primary-color: #6366f1; /* Neon Indigo */
			--secondary-color: #38bdf8; /* Neon Blue */

			--bg-body: #0f172a;
			--bg-glass: rgba(15, 23, 42, 0.8);
			--text-main: #f1f5f9;
			--text-muted: #94a3b8;
			--card-bg: #1e293b;
			--card-border: #334155;
			--navbar-bg: rgba(15, 23, 42, 0.95);
			--shadow-soft: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
			/* Gaming Background */
			--bg-gradient: radial-gradient(circle at 10% 20%, rgb(18, 26, 56) 0%, rgb(11, 15, 25) 90.2%);
		}

		body {
			font-family: 'Poppins', sans-serif;
			background: var(--bg-gradient);
			background-attachment: fixed; /* Agar background tetap saat scroll */
			color: var(--text-main);
			transition: background 0.5s ease, color 0.3s ease;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		/* Navbar */
		.navbar {
			background-color: var(--navbar-bg);
			backdrop-filter: blur(10px);
			border-bottom: 1px solid var(--card-border);
			transition: all 0.3s;
		}
		.navbar-brand {
			font-weight: 700;
			color: var(--text-main) !important;
		}
		.nav-link {
			color: var(--text-muted) !important;
			font-weight: 500;
		}
		.nav-link:hover, .nav-link.active {
			color: var(--primary-color) !important;
		}

		/* Theme Toggle Button */
		.theme-toggle {
			cursor: pointer;
			width: 40px;
			height: 40px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			background: var(--card-border);
			color: var(--text-main);
			border: none;
			transition: 0.3s;
		}
		.theme-toggle:hover {
			background: var(--primary-color);
			color: white;
			transform: rotate(15deg);
		}

		/* Hero Section */
		.hero-section {
			padding: 80px 0 40px 0;
			text-align: center;
		}
		.hero-title {
			font-size: 2.5rem;
			font-weight: 800;
			margin-bottom: 10px;
			background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}

		/* Category Filters */
		.category-btn {
			background-color: var(--card-bg);
			color: var(--text-muted);
			border: 1px solid var(--card-border);
			padding: 8px 20px;
			border-radius: 50px;
			font-size: 0.9rem;
			transition: 0.3s;
			margin: 5px;
		}
		.category-btn:hover, .category-btn.active {
			background-color: var(--primary-color);
			color: white;
			border-color: var(--primary-color);
			box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
		}

		/* Product Cards */
		.game-card {
			background-color: var(--card-bg);
			border: 1px solid var(--card-border);
			border-radius: 16px;
			overflow: hidden;
			transition: transform 0.3s ease, box-shadow 0.3s ease;
			cursor: pointer;
			height: 100%;
		}
		.game-card:hover {
			transform: translateY(-8px);
			box-shadow: var(--shadow-soft);
			border-color: var(--secondary-color);
		}
		.card-img-wrapper {
			position: relative;
			padding-top: 60%; /* Aspect Ratio 16:9-ish */
			overflow: hidden;
		}
		.card-img-wrapper img {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			object-fit: cover;
			transition: transform 0.5s;
		}
		.game-card:hover img {
			transform: scale(1.1);
		}
		.card-body {
			padding: 15px;
			text-align: center;
		}
		.card-title {
			font-weight: 600;
			margin-bottom: 2px;
			font-size: 1.1rem;
			color: var(--text-main);
		}
		.card-publisher {
			font-size: 0.8rem;
			color: var(--text-muted);
		}

		/* Modal Customization */
		.modal-content {
			background-color: var(--card-bg);
			color: var(--text-main);
			border: 1px solid var(--card-border);
		}
		.modal-header, .modal-footer {
			border-color: var(--card-border);
		}
		.form-control {
			background-color: var(--bg-body);
			border: 1px solid var(--card-border);
			color: var(--text-main);
		}
		.form-control:focus {
			background-color: var(--bg-body);
			border-color: var(--primary-color);
			color: var(--text-main);
		}
		.btn-close {
			filter: invert(var(--invert-value)); /* Auto invert icon color */
		}
		[data-bs-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
		[data-bs-theme="light"] .btn-close { filter: none; }

		/* Payment Radio Custom */
		.payment-label {
			display: block;
			background: var(--bg-body);
			border: 1px solid var(--card-border);
			padding: 15px;
			border-radius: 10px;
			cursor: pointer;
			text-align: center;
			color: var(--text-muted);
			transition: 0.2s;
		}
		.payment-radio:checked + .payment-label {
			border-color: var(--primary-color);
			background: rgba(99, 102, 241, 0.1);
			color: var(--primary-color);
			font-weight: bold;
		}

		/* Footer */
		footer {
			margin-top: auto;
			background-color: var(--card-bg);
			border-top: 3px solid var(--primary-color);
			padding-top: 40px;
			color: var(--text-muted);
		}
		footer h5 {
			color: var(--text-main);
			font-weight: 700;
		}
		.footer-bottom {
			background-color: var(--bg-body);
			padding: 20px 0;
			margin-top: 30px;
			border-top: 1px solid var(--card-border);
		}
	</style>
</head>
<body>

	<nav class="navbar navbar-expand-lg fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#">
				<i class="fas fa-cloud text-primary me-2"></i>ID Cloud<span class="text-primary">Store</span>
			</a>

			<div class="d-flex align-items-center gap-3 order-lg-last">
				<button class="theme-toggle" id="themeToggle" title="Ganti Mode">
					<i class="fas fa-moon"></i>
				</button>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto me-4">
					<li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
					<li class="nav-item"><a class="nav-link" href="#products">Produk</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Lacak</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<section class="hero-section mt-5">
		<div class="container">
			<h1 class="hero-title">Top Up Instan & Resmi</h1>
			<p class="text-muted fs-5 mb-4">Layanan Top Up Game & Voucher Termurah 24 Jam</p>

			<div class="d-flex justify-content-center flex-wrap" id="category-filters">
				<button class="category-btn active" data-filter="all"><i class="fas fa-th-large me-2"></i>Semua</button>
				<button class="category-btn" data-filter="game"><i class="fas fa-gamepad me-2"></i>Games</button>
				<button class="category-btn" data-filter="pulsa"><i class="fas fa-mobile-alt me-2"></i>Pulsa</button>
				<button class="category-btn" data-filter="token"><i class="fas fa-bolt me-2"></i>PLN</button>
				<button class="category-btn" data-filter="voucher"><i class="fas fa-ticket-alt me-2"></i>Voucher</button>
			</div>
		</div>
	</section>

	<section class="container mb-5" id="products">
		<div class="row g-3 g-md-4" id="product-list">

			<div class="col-6 col-md-3 product-item" data-category="game">
				<div class="game-card" onclick="openOrderModal('Mobile Legends', 'Game')">
					<div class="card-img-wrapper">
						<img src="https://cdn1.codashop.com/S/content/mobile/images/product-tiles/MLBB-2025-tiles-178x178.jpg" alt="MLBB">
					</div>
					<div class="card-body">
						<div class="card-title">Mobile Legends</div>
						<div class="card-publisher">Moonton</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-3 product-item" data-category="game">
				<div class="game-card" onclick="openOrderModal('Free Fire', 'Game')">
					<div class="card-img-wrapper">
						<img src="https://cdn1.codashop.com/S/content/mobile/images/product-tiles/free-fire-tile-codacash-new.jpg" alt="FF">
					</div>
					<div class="card-body">
						<div class="card-title">Free Fire</div>
						<div class="card-publisher">Garena</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-3 product-item" data-category="game">
				<div class="game-card" onclick="openOrderModal('PUBG Mobile', 'Game')">
					<div class="card-img-wrapper">
						<img src="https://cdn1.codashop.com/S/content/mobile/images/product-tiles/pubgm_tile_aug2024.jpg" alt="PUBG">
					</div>
					<div class="card-body">
						<div class="card-title">PUBG Mobile</div>
						<div class="card-publisher">Tencent</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-3 product-item" data-category="pulsa">
				<div class="game-card" onclick="openOrderModal('Telkomsel', 'Pulsa')">
					<div class="card-img-wrapper">
						<img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg" style="padding:20px; object-fit:contain; background:#f0f0f0;" alt="Telkomsel">
					</div>
					<div class="card-body">
						<div class="card-title">Telkomsel</div>
						<div class="card-publisher">Pulsa & Data</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-3 product-item" data-category="token">
				<div class="game-card" onclick="openOrderModal('Token PLN', 'PLN')">
					<div class="card-img-wrapper">
						<img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Logo_PLN.svg" style="padding:20px; object-fit:contain; background:#f0f0f0;" alt="PLN">
					</div>
					<div class="card-body">
						<div class="card-title">Token PLN</div>
						<div class="card-publisher">Listrik Prabayar</div>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-3 product-item" data-category="voucher">
				<div class="game-card" onclick="openOrderModal('Google Play', 'Voucher')">
					<div class="card-img-wrapper">
						<img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/Google_Play_Arrow_logo.svg" style="padding:20px; object-fit:contain; background:#f0f0f0;" alt="GPlay">
					</div>
					<div class="card-body">
						<div class="card-title">Google Play</div>
						<div class="card-publisher">Kode Voucher</div>
					</div>
				</div>
			</div>

		</div>
	</section>

	<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fw-bold" id="modalTitle">Top Up</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
							<div class="row g-2">
								<div class="col-6 col-md-3">
									<input type="radio" class="btn-check" name="nominal" id="nom1" value="Item 1">
									<label class="btn btn-outline-primary w-100 nominal-btn" for="nom1">Item 1</label>
								</div>
								<div class="col-6 col-md-3">
									<input type="radio" class="btn-check" name="nominal" id="nom2" value="Item 2">
									<label class="btn btn-outline-primary w-100 nominal-btn" for="nom2">Item 2</label>
								</div>
								<div class="col-6 col-md-3">
									<input type="radio" class="btn-check" name="nominal" id="nom3" value="Item 3">
									<label class="btn btn-outline-primary w-100 nominal-btn" for="nom3">Item 3</label>
								</div>
								<div class="col-6 col-md-3">
									<input type="radio" class="btn-check" name="nominal" id="nom4" value="Item 4">
									<label class="btn btn-outline-primary w-100 nominal-btn" for="nom4">Item 4</label>
								</div>
							</div>
						</div>

						<div class="mb-4">
							<h6 class="text-primary fw-bold mb-3"><i class="fas fa-wallet me-2"></i>3. Pembayaran</h6>
							<div class="row g-2">
								<div class="col-4">
									<input type="radio" name="payment" id="pay1" class="payment-radio" value="QRIS">
									<label for="pay1" class="payment-label">
										<i class="fas fa-qrcode fa-2x mb-2"></i><br><small>QRIS</small>
									</label>
								</div>
								<div class="col-4">
									<input type="radio" name="payment" id="pay2" class="payment-radio" value="DANA">
									<label for="pay2" class="payment-label">
										<i class="fas fa-wallet fa-2x mb-2"></i><br><small>DANA</small>
									</label>
								</div>
								<div class="col-4">
									<input type="radio" name="payment" id="pay3" class="payment-radio" value="BANK">
									<label for="pay3" class="payment-label">
										<i class="fas fa-university fa-2x mb-2"></i><br><small>Bank</small>
									</label>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary fw-bold px-4" id="btnBeliSekarang">Bayar Sekarang</button>
				</div>
			</div>
		</div>
	</div>

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-5 mb-4">
					<h5><i class="fas fa-cloud me-2"></i>ID CLOUD STORE</h5>
					<p class="small">Platform Top Up Game tercepat dan terpercaya. Kami menyediakan berbagai metode pembayaran untuk memudahkan transaksi Anda.</p>
				</div>
				<div class="col-lg-3 mb-4">
					<h5>Bantuan</h5>
					<ul class="list-unstyled small">
						<li class="mb-2"><a href="#" class="text-decoration-none text-muted">Cara Order</a></li>
						<li class="mb-2"><a href="#" class="text-decoration-none text-muted">Hubungi Kami</a></li>
					</ul>
				</div>
				<div class="col-lg-4 mb-4">
					<h5>Kontak</h5>
					<p class="small text-muted">
						<i class="fab fa-whatsapp me-2"></i> 0812-3456-7890<br>
						<i class="fas fa-envelope me-2"></i> help@idcloud.com
					</p>
				</div>
			</div>
		</div>
		<div class="footer-bottom text-center">
			<small>&copy; 2023 ID Cloud Store. All Rights Reserved.</small>
		</div>
	</footer>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		$(document).ready(function() {

			// --- 1. THEME SWITCHER LOGIC ---

			// Cek localStorage saat load
			const currentTheme = localStorage.getItem('theme') || 'dark'; // Default Dark
			setTheme(currentTheme);

			$('#themeToggle').click(function() {
				let activeTheme = $('html').attr('data-bs-theme');
				let newTheme = activeTheme === 'dark' ? 'light' : 'dark';
				setTheme(newTheme);
			});

			function setTheme(theme) {
				$('html').attr('data-bs-theme', theme);
				localStorage.setItem('theme', theme);

				// Update Icon
				if(theme === 'dark') {
					$('#themeToggle i').removeClass('fa-sun').addClass('fa-moon');
				} else {
					$('#themeToggle i').removeClass('fa-moon').addClass('fa-sun');
				}
			}


			// --- 2. FILTER LOGIC ---
			$('.category-btn').click(function() {
				$('.category-btn').removeClass('active');
				$(this).addClass('active');

				const filter = $(this).data('filter');
				if (filter === 'all') {
					$('.product-item').fadeIn(200);
				} else {
					$('.product-item').hide();
					$('.product-item[data-category="' + filter + '"]').fadeIn(200);
				}
			});


			// --- 3. TRANSACTION LOGIC ---
			$('#btnBeliSekarang').click(function() {
				// Simple validation
				let userVal = $('#topupForm input[type="text"]').first().val();
				let nominal = $('input[name="nominal"]:checked').val();

				if(!userVal || !nominal) {
					alert("Mohon lengkapi Data Akun dan Pilih Nominal!");
					return;
				}

				// Loading effect
				let btn = $(this);
				let originalText = btn.text();
				btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Proses...');

				setTimeout(() => {
					$('#orderModal').modal('hide');
					btn.prop('disabled', false).text(originalText);
					alert("Pesanan berhasil dibuat! Silakan lakukan pembayaran.");
				}, 1500);
			});
		});

		// --- 4. MODAL DYNAMIC CONTENT ---
		function openOrderModal(itemName, category) {
			$('#modalTitle').text('Top Up ' + itemName);

			// Reset Form
			$('#topupForm')[0].reset();

			// Atur Input Zone ID
			if(category === 'Game') {
				$('#zoneIdInput').show();
				// Dummy Data Items
				updateNominals(["86 Diamonds", "172 Diamonds", "257 Diamonds", "Weekly Pass"]);
			} else {
				$('#zoneIdInput').hide();
				if(category === 'Pulsa') updateNominals(["10.000", "25.000", "50.000", "100.000"]);
				else if(category === 'PLN') updateNominals(["20.000", "50.000", "100.000", "200.000"]);
				else updateNominals(["Voucher 20k", "Voucher 50k", "Voucher 100k", "Voucher 250k"]);
			}

			var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
			myModal.show();
		}

		function updateNominals(items) {
			$('.nominal-btn').each(function(index) {
				if(items[index]) {
					$(this).text(items[index]);
					$(this).prev().val(items[index]);
					$(this).parent().show();
				} else {
					$(this).parent().hide();
				}
			});
		}
	</script>
</body>
</html>
