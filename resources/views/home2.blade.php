{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamerPay - Topup Terpercaya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6f42c1; /* Ungu Neon */
            --secondary-color: #0dcaf0; /* Cyan */
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #334155;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--secondary-color) !important;
            font-size: 1.5rem;
        }
        .navbar-brand span {
            color: var(--primary-color);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e1e2e 100%);
            padding: 80px 0 40px 0;
            text-align: center;
        }
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .highlight {
            color: var(--secondary-color);
            text-shadow: 0 0 10px rgba(13, 202, 240, 0.5);
        }

        /* Category Filter */
        .category-btn {
            background-color: var(--card-bg);
            color: #94a3b8;
            border: 1px solid #334155;
            margin: 0 5px 10px 5px;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .category-btn:hover, .category-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(111, 66, 193, 0.5);
        }

        /* Product Cards */
        .game-card {
            background-color: var(--card-bg);
            border: 1px solid #334155;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            height: 100%;
            position: relative;
        }
        .game-card:hover {
            transform: translateY(-5px);
            border-color: var(--secondary-color);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .card-img-wrapper {
            position: relative;
            padding-top: 60%; /* Aspect Ratio 16:9 like */
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
            margin-bottom: 5px;
            font-size: 1rem;
        }
        .card-publisher {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Form Elements inside Modal */
        .form-control, .form-select {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background-color: #0f172a;
            color: white;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.25);
        }

        /* Payment Method Radio Logic */
        .payment-radio {
            display: none;
        }
        .payment-label {
            display: block;
            background: #0f172a;
            border: 1px solid #334155;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .payment-radio:checked + .payment-label {
            border-color: var(--secondary-color);
            background: rgba(13, 202, 240, 0.1);
        }
        .nominal-btn {
            width: 100%;
            margin-bottom: 10px;
        }

        /* Footer */
        footer {
            background-color: #0b1120;
            padding: 40px 0;
            margin-top: 50px;
            border-top: 1px solid #334155;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-gamepad"></i> Gamer<span>Pay</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Daftar Game</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cek Pesanan</a></li>
                    <li class="nav-item"><a class="btn btn-outline-info ms-lg-3 btn-sm rounded-pill px-4" href="#">Masuk / Daftar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container mt-5">
            <h1 class="hero-title">Top Up Game & Voucher <br> <span class="highlight">Murah, Cepat, Aman</span></h1>
            <p class="text-muted mb-4">Layanan top up game online 24 jam nonstop. Proses detik masuk.</p>

            <div class="d-flex justify-content-center flex-wrap" id="category-filters">
                <button class="category-btn active" data-filter="all"><i class="fas fa-th-large me-2"></i>Semua</button>
                <button class="category-btn" data-filter="game"><i class="fas fa-gamepad me-2"></i>Games</button>
                <button class="category-btn" data-filter="pulsa"><i class="fas fa-mobile-alt me-2"></i>Pulsa & Data</button>
                <button class="category-btn" data-filter="token"><i class="fas fa-bolt me-2"></i>PLN Token</button>
                <button class="category-btn" data-filter="voucher"><i class="fas fa-ticket-alt me-2"></i>Voucher</button>
            </div>
        </div>
    </section>

    <section class="container mb-5" id="products">
        <div class="row g-3 g-md-4" id="product-list">

            <div class="col-6 col-md-3 product-item" data-category="game">
                <div class="game-card" onclick="openOrderModal('Mobile Legends', 'Game')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/1a1a2e/FFF?text=Mobile+Legends" alt="MLBB">
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
                        <img src="https://placehold.co/400x300/1a1a2e/FFF?text=Free+Fire" alt="FF">
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
                        <img src="https://placehold.co/400x300/1a1a2e/FFF?text=PUBG+Mobile" alt="PUBG">
                    </div>
                    <div class="card-body">
                        <div class="card-title">PUBG Mobile</div>
                        <div class="card-publisher">Tencent</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="game">
                <div class="game-card" onclick="openOrderModal('Valorant', 'Game')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/1a1a2e/FFF?text=Valorant" alt="Valorant">
                    </div>
                    <div class="card-body">
                        <div class="card-title">Valorant</div>
                        <div class="card-publisher">Riot Games</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="pulsa">
                <div class="game-card" onclick="openOrderModal('Telkomsel', 'Pulsa')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/e00/FFF?text=Telkomsel" alt="Telkomsel">
                    </div>
                    <div class="card-body">
                        <div class="card-title">Telkomsel</div>
                        <div class="card-publisher">Pulsa & Data</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="pulsa">
                <div class="game-card" onclick="openOrderModal('Indosat', 'Pulsa')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/ffcc00/000?text=Indosat" alt="Indosat">
                    </div>
                    <div class="card-body">
                        <div class="card-title">Indosat Ooredoo</div>
                        <div class="card-publisher">Pulsa & Data</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="token">
                <div class="game-card" onclick="openOrderModal('Token PLN', 'PLN')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/FFB703/000?text=PLN+Token" alt="PLN">
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
                        <img src="https://placehold.co/400x300/FFF/000?text=Google+Play" alt="GPlay">
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
            <div class="modal-content" style="background-color: #1e293b; color: white;">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="modalTitle">Top Up</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="topupForm">
                        <div class="mb-4">
                            <h6 class="text-info"><i class="fas fa-user me-2"></i>1. Masukkan Data Akun / Nomor</h6>
                            <div class="row g-2">
                                <div class="col-7">
                                    <input type="text" class="form-control" placeholder="User ID / No. HP" required>
                                </div>
                                <div class="col-5" id="zoneIdInput">
                                    <input type="text" class="form-control" placeholder="Zone ID (Optional)">
                                </div>
                            </div>
                            <small class="text-muted">Untuk Pulsa/PLN, masukkan nomor di kolom User ID.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info"><i class="fas fa-coins me-2"></i>2. Pilih Nominal</h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom1" value="86" autocomplete="off">
                                    <label class="btn btn-outline-secondary nominal-btn" for="nom1">86 Diamonds</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom2" value="172" autocomplete="off">
                                    <label class="btn btn-outline-secondary nominal-btn" for="nom2">172 Diamonds</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom3" value="257" autocomplete="off">
                                    <label class="btn btn-outline-secondary nominal-btn" for="nom3">257 Diamonds</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom4" value="Starlight" autocomplete="off">
                                    <label class="btn btn-outline-secondary nominal-btn" for="nom4">Member Weekly</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info"><i class="fas fa-wallet me-2"></i>3. Metode Pembayaran</h6>
                            <div class="row g-2">
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="payment" id="pay1" class="payment-radio" value="Qris">
                                    <label for="pay1" class="payment-label">
                                        <i class="fas fa-qrcode fa-2x mb-2 text-white"></i><br>QRIS
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="payment" id="pay2" class="payment-radio" value="Dana">
                                    <label for="pay2" class="payment-label">
                                        <i class="fas fa-wallet fa-2x mb-2 text-primary"></i><br>DANA
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="payment" id="pay3" class="payment-radio" value="VA">
                                    <label for="pay3" class="payment-label">
                                        <i class="fas fa-university fa-2x mb-2 text-warning"></i><br>Bank Transfer
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnBeliSekarang"><i class="fas fa-shopping-cart me-2"></i>Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-lg-start">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase text-info">GamerPay</h5>
                    <p class="text-muted">
                        Platform Top Up Game Termurah dan Terpercaya di Indonesia.
                        Proses otomatis 24 Jam.
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Dukungan</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#!" class="text-muted text-decoration-none">Hubungi WhatsApp</a></li>
                        <li><a href="#!" class="text-muted text-decoration-none">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Metode</h5>
                    <div class="fs-3">
                        <i class="fab fa-cc-visa text-muted me-2"></i>
                        <i class="fab fa-cc-mastercard text-muted me-2"></i>
                        <i class="fas fa-qrcode text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright: <a class="text-info text-decoration-none" href="#">GamerPay.id</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Logic Filter Kategori
            $('.category-btn').click(function() {
                // Hapus kelas active dari semua tombol, tambahkan ke yang diklik
                $('.category-btn').removeClass('active');
                $(this).addClass('active');

                const filterValue = $(this).attr('data-filter');

                if (filterValue === 'all') {
                    $('.product-item').fadeIn(300);
                } else {
                    $('.product-item').hide(); // Sembunyikan semua dulu
                    // Tampilkan yang sesuai atribut data-category
                    $('.product-item[data-category="' + filterValue + '"]').fadeIn(300);
                }
            });

            // 2. Logic Tombol Beli (Simulasi)
            $('#btnBeliSekarang').click(function() {
                // Ambil data sederhana (hanya validasi dasar)
                let userVal = $('#topupForm input[type="text"]').first().val();
                let nominal = $('input[name="nominal"]:checked').val();
                let payment = $('input[name="payment"]:checked').val();

                if(!userVal || !nominal || !payment) {
                    alert("Harap lengkapi semua data form!");
                } else {
                    // Tutup modal
                    $('#orderModal').modal('hide');
                    // Tampilkan alert sukses (Bisa diganti SweetAlert nanti)
                    alert("Pesanan Dibuat!\nID: " + userVal + "\nItem: " + nominal + "\nBayar via: " + payment);
                }
            });
        });

        // 3. Fungsi Buka Modal & Set Judul
        function openOrderModal(itemName, category) {
            $('#modalTitle').text('Top Up ' + itemName);

            // Logic menyembunyikan Zone ID jika bukan Game
            if(category === 'Game') {
                $('#zoneIdInput').show();
                $('.nominal-btn').each(function(index) {
                    // Simulasi ubah teks nominal
                    const list = ["86 Diamonds", "172 Diamonds", "257 Diamonds", "Weekly Pass"];
                    $(this).text(list[index] || "Item");
                });
            } else if (category === 'Pulsa') {
                $('#zoneIdInput').hide();
                $('.nominal-btn').each(function(index) {
                    const list = ["Pulsa 10k", "Pulsa 25k", "Pulsa 50k", "Pulsa 100k"];
                    $(this).text(list[index] || "Item");
                });
            } else if (category === 'PLN') {
                $('#zoneIdInput').hide();
                $('.nominal-btn').each(function(index) {
                    const list = ["Token 20k", "Token 50k", "Token 100k", "Token 200k"];
                    $(this).text(list[index] || "Item");
                });
            } else {
                 $('#zoneIdInput').hide();
                 $('.nominal-btn').text("Voucher Value");
            }

            // Reset Form
            $('#topupForm')[0].reset();

            // Tampilkan Modal
            var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
            myModal.show();
        }
    </script>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Nest Store - Topup Games & Voucher</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6366f1; /* Indigo Neon */
            --secondary-color: #38bdf8; /* Sky Blue */
            --bg-dark: #0f172a;       /* Dark Blue background */
            --card-bg: #1e293b;       /* Lighter dark for cards */
            --text-light: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--text-light) !important;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .navbar-brand i {
            color: var(--secondary-color);
        }
        .navbar-brand span {
            color: var(--primary-color);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(99, 102, 241, 0.1) 100%);
            padding: 100px 0 50px 0;
            text-align: center;
        }
        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: -webkit-linear-gradient(45deg, #fff, var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Category Filter */
        .category-btn {
            background-color: var(--card-bg);
            color: #cbd5e1;
            border: 1px solid #334155;
            margin: 0 5px 10px 5px;
            padding: 8px 25px;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        .category-btn:hover, .category-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.5);
            transform: translateY(-2px);
        }

        /* Product Cards */
        .game-card {
            background-color: var(--card-bg);
            border: 1px solid #334155;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            position: relative;
        }
        .game-card:hover {
            transform: translateY(-8px);
            border-color: var(--secondary-color);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 0 10px rgba(56, 189, 248, 0.3);
        }
        .card-img-wrapper {
            position: relative;
            padding-top: 60%;
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
            margin-bottom: 5px;
            font-size: 1rem;
            color: white;
        }
        .card-publisher {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Modal Styling */
        .modal-content {
            background-color: #1e293b;
            color: white;
            border: 1px solid #475569;
        }
        .form-control, .form-select {
            background-color: #0f172a;
            border: 1px solid #475569;
            color: white;
            padding: 12px;
        }
        .form-control:focus {
            background-color: #0f172a;
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        .payment-label {
            display: block;
            background: #0f172a;
            border: 1px solid #334155;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .payment-radio:checked + .payment-label {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.1);
            color: white;
        }

        /* --- FOOTER YANG DITINGKATKAN --- */
        footer {
            margin-top: auto; /* Push footer to bottom */
            background-color: #1e293b; /* Warna lebih terang dari body */
            border-top: 4px solid var(--primary-color); /* Garis neon di atas */
            padding-top: 60px;
            padding-bottom: 30px;
            color: #e2e8f0; /* Warna teks putih gading (sangat jelas) */
        }

        footer h5 {
            color: #ffffff; /* Judul footer putih murni */
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        footer p, footer li {
            font-size: 0.95rem;
            line-height: 1.6;
        }

        footer a {
            color: #94a3b8; /* Link warna abu terang */
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: var(--secondary-color); /* Hover jadi biru terang */
        }

        .footer-copyright {
            background-color: #0f172a; /* Bagian paling bawah lebih gelap */
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid #334155;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-cloud me-2"></i>ID Cloud<span>Store</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Lacak Order</a></li>
                    <li class="nav-item ps-3">
                        <a class="btn btn-primary btn-sm rounded-pill px-4 fw-bold" href="#">Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container mt-5">
            <h1 class="hero-title">Cloud Nest Store</h1>
            <p class="text-white-50 fs-5 mb-4">Platform Top Up Game & PPOB Termurah Se-Indonesia</p>

            <div class="d-flex justify-content-center flex-wrap gap-2" id="category-filters">
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
                        <img src="https://placehold.co/400x300/2A2A3E/FFF?text=Mobile+Legends" alt="MLBB">
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
                        <img src="https://placehold.co/400x300/2A2A3E/FFF?text=Free+Fire" alt="FF">
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
                        <img src="https://placehold.co/400x300/2A2A3E/FFF?text=PUBG+Mobile" alt="PUBG">
                    </div>
                    <div class="card-body">
                        <div class="card-title">PUBG Mobile</div>
                        <div class="card-publisher">Tencent</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="game">
                <div class="game-card" onclick="openOrderModal('Valorant', 'Game')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/2A2A3E/FFF?text=Valorant" alt="Valorant">
                    </div>
                    <div class="card-body">
                        <div class="card-title">Valorant</div>
                        <div class="card-publisher">Riot Games</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="pulsa">
                <div class="game-card" onclick="openOrderModal('Telkomsel', 'Pulsa')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/c00/FFF?text=Telkomsel" alt="Telkomsel">
                    </div>
                    <div class="card-body">
                        <div class="card-title">Telkomsel</div>
                        <div class="card-publisher">Pulsa & Data</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="pulsa">
                <div class="game-card" onclick="openOrderModal('Indosat', 'Pulsa')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/eab308/000?text=Indosat" alt="Indosat">
                    </div>
                    <div class="card-body">
                        <div class="card-title">Indosat</div>
                        <div class="card-publisher">Pulsa & Data</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 product-item" data-category="token">
                <div class="game-card" onclick="openOrderModal('Token PLN', 'PLN')">
                    <div class="card-img-wrapper">
                        <img src="https://placehold.co/400x300/f59e0b/000?text=PLN+Token" alt="PLN">
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
                        <img src="https://placehold.co/400x300/eee/000?text=Google+Play" alt="GPlay">
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
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="modalTitle">Top Up</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="topupForm">
                        <div class="mb-4">
                            <h6 class="text-primary fw-bold"><i class="fas fa-user-circle me-2"></i>1. Masukkan Data Akun / Nomor</h6>
                            <div class="row g-2">
                                <div class="col-7">
                                    <input type="text" class="form-control" placeholder="User ID / No. HP" required>
                                </div>
                                <div class="col-5" id="zoneIdInput">
                                    <input type="text" class="form-control" placeholder="Zone ID (Optional)">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary fw-bold"><i class="fas fa-coins me-2"></i>2. Pilih Nominal</h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom1" value="Item 1">
                                    <label class="btn btn-outline-secondary w-100 nominal-btn" for="nom1">Item 1</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom2" value="Item 2">
                                    <label class="btn btn-outline-secondary w-100 nominal-btn" for="nom2">Item 2</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom3" value="Item 3">
                                    <label class="btn btn-outline-secondary w-100 nominal-btn" for="nom3">Item 3</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="nominal" id="nom4" value="Item 4">
                                    <label class="btn btn-outline-secondary w-100 nominal-btn" for="nom4">Item 4</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary fw-bold"><i class="fas fa-wallet me-2"></i>3. Metode Pembayaran</h6>
                            <div class="row g-2">
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="payment" id="pay1" class="payment-radio" value="Qris">
                                    <label for="pay1" class="payment-label">
                                        <i class="fas fa-qrcode fa-2x mb-2 text-white"></i><br>QRIS
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="payment" id="pay2" class="payment-radio" value="Dana">
                                    <label for="pay2" class="payment-label">
                                        <i class="fas fa-wallet fa-2x mb-2 text-info"></i><br>DANA
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" name="payment" id="pay3" class="payment-radio" value="VA">
                                    <label for="pay3" class="payment-label">
                                        <i class="fas fa-university fa-2x mb-2 text-warning"></i><br>Transfer Bank
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary px-4 fw-bold" id="btnBeliSekarang">Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 mb-4 mb-md-0">
                    <h5><i class="fas fa-cloud me-2"></i>CLOUD NEST STORE</h5>
                    <p>
                        Penyedia layanan Top Up Game, Pulsa, dan PPOB terpercaya sejak 2023.
                        Transaksi aman, proses instan, dan layanan pelanggan responsif 24 Jam.
                    </p>
                    <div class="mt-3">
                        <a href="#" class="me-3 fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="me-3 fs-5"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Navigasi Cepat</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="#">Beranda</a></li>
                        <li class="mb-2"><a href="#products">Daftar Harga</a></li>
                        <li class="mb-2"><a href="#">Cek Pesanan</a></li>
                        <li class="mb-2"><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5>Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-info"></i> support@idcloudstore.com</li>
                        <li class="mb-2"><i class="fab fa-whatsapp me-2 text-success"></i> 0812-3456-7890</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-danger"></i> Jakarta, Indonesia</li>
                    </ul>
                    <div class="mt-3">
                        <h6 class="text-white">Pembayaran:</h6>
                        <i class="fab fa-cc-visa fa-2x me-2"></i>
                        <i class="fab fa-cc-mastercard fa-2x me-2"></i>
                        <i class="fas fa-qrcode fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-copyright text-center">
            <div class="container">
                © 2023 <span class="text-primary fw-bold">Cloud Nest Store</span>. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Logic Filter
            $('.category-btn').click(function() {
                $('.category-btn').removeClass('active');
                $(this).addClass('active');
                const filterValue = $(this).attr('data-filter');
                if (filterValue === 'all') {
                    $('.product-item').fadeIn(300);
                } else {
                    $('.product-item').hide();
                    $('.product-item[data-category="' + filterValue + '"]').fadeIn(300);
                }
            });

            // Logic Beli
            $('#btnBeliSekarang').click(function() {
                let userVal = $('#topupForm input[type="text"]').first().val();
                let nominal = $('input[name="nominal"]:checked').val();
                let payment = $('input[name="payment"]:checked').val();

                if(!userVal || !nominal || !payment) {
                    alert("Harap lengkapi formulir sebelum membeli!");
                } else {
                    $('#orderModal').modal('hide');
                    alert("Terima kasih! Pesanan " + nominal + " untuk Cloud Nest Store sedang diproses.");
                }
            });
        });

        // Open Modal
        function openOrderModal(itemName, category) {
            $('#modalTitle').text('Top Up ' + itemName);

            if(category === 'Game') {
                $('#zoneIdInput').show();
                $('.nominal-btn').each(function(index) {
                    const list = ["86 Diamonds", "172 Diamonds", "257 Diamonds", "Weekly Pass"];
                    $(this).text(list[index] || "Item");
                    $(this).prev().val(list[index]); // Set value radio
                });
            } else if (category === 'Pulsa') {
                $('#zoneIdInput').hide();
                $('.nominal-btn').each(function(index) {
                    const list = ["Pulsa 10k", "Pulsa 25k", "Pulsa 50k", "Pulsa 100k"];
                    $(this).text(list[index] || "Item");
                    $(this).prev().val(list[index]);
                });
            } else if (category === 'PLN') {
                $('#zoneIdInput').hide();
                $('.nominal-btn').each(function(index) {
                    const list = ["Token 20k", "Token 50k", "Token 100k", "Token 200k"];
                    $(this).text(list[index] || "Item");
                    $(this).prev().val(list[index]);
                });
            } else {
                 $('#zoneIdInput').hide();
                 $('.nominal-btn').text("Voucher Value");
            }

            $('#topupForm')[0].reset();
            var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
            myModal.show();
        }
    </script>
</body>
</html>
