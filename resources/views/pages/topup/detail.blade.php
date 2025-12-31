@extends('layouts.app')

@section('title', 'Top Up Game - ID Cloud Store')

@push('styles')
<style>
    /* Styling khusus halaman detail */
    .section-title {
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .circle-number {
        background: var(--primary-color);
        color: white;
        width: 30px; height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center; justify-content: center;
        margin-right: 10px;
        font-size: 0.9rem;
    }

    /* Item Card Selection */
    .nominal-card, .payment-card {
        cursor: pointer;
        border: 1px solid var(--card-border);
        background: var(--bg-body);
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }
    .nominal-card:hover, .payment-card:hover {
        border-color: var(--secondary-color);
        transform: translateY(-2px);
    }

    /* Active State (Dipilih) */
    .nominal-card.active, .payment-card.active {
        background: rgba(79, 70, 229, 0.1); /* Transparansi Primary */
        border: 2px solid var(--primary-color);
    }
    .active-icon {
        display: none;
        position: absolute;
        top: 0; right: 0;
        background: var(--primary-color);
        color: white;
        padding: 2px 8px;
        border-bottom-left-radius: 10px;
        font-size: 0.7rem;
    }
    .nominal-card.active .active-icon, .payment-card.active .active-icon {
        display: block;
    }

    /* Sticky Sidebar Layout */
    .sticky-product-info {
        position: sticky;
        top: 80px; /* Jarak dari navbar */
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="container mt-5 pt-5 pb-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Games</a></li>
            <li class="breadcrumb-item active text-primary" aria-current="page">{{ ucfirst($slug ?? 'Detail Produk') }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        <div class="col-lg-4 mb-4">
            <div class="sticky-product-info">
                <div class="card border-0 shadow-sm" style="background: var(--card-bg);">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <img src="https://cdn1.codashop.com/S/content/mobile/images/product-tiles/MLBB-2025-tiles-178x178.jpg"
                                 class="rounded-4 shadow-lg mb-3" style="width: 140px;">
                            <h4 class="fw-bold">{{ ucfirst($slug ?? 'Nama Game') }}</h4>
                            <p class="text-muted small">Moonton • Mobile Game</p>
                        </div>

                        <hr style="border-color: var(--card-border);">

                        <div class="accordion accordion-flush" id="infoAccordion">
                            <div class="accordion-item" style="background: transparent;">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent text-muted shadow-none p-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#descCollapse">
                                        <i class="fas fa-info-circle me-2"></i> Cara Top Up
                                    </button>
                                </h2>
                                <div id="descCollapse" class="accordion-collapse collapse show" data-bs-parent="#infoAccordion">
                                    <div class="accordion-body p-0 pt-2 small text-muted">
                                        <ol class="ps-3 mb-0">
                                            <li>Masukkan User ID & Zone ID.</li>
                                            <li>Pilih Nominal yang diinginkan.</li>
                                            <li>Pilih Metode Pembayaran.</li>
                                            <li>Klik Beli Sekarang & lakukan pembayaran.</li>
                                            <li>Diamond akan masuk otomatis.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <form action="#" method="POST" id="orderForm">
                @csrf

                <div class="card border-0 shadow-sm mb-4" style="background: var(--card-bg);">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <span class="circle-number">1</span>
                            <span>Masukkan Data Akun</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 col-7">
                                <label class="form-label small text-muted">User ID</label>
                                <input type="number" class="form-control form-control-lg bg-body text-body" placeholder="Contoh: 12345678" required>
                            </div>
                            <div class="col-md-6 col-5">
                                <label class="form-label small text-muted">Zone ID</label>
                                <input type="number" class="form-control form-control-lg bg-body text-body" placeholder="(1234)" required>
                            </div>
                            <div class="col-12">
                                <small class="text-muted fst-italic"><i class="fas fa-question-circle me-1"></i> Untuk mengetahui User ID Anda, silakan klik menu profil dibagian kiri atas pada menu utama game.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="background: var(--card-bg);">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <span class="circle-number">2</span>
                            <span>Pilih Nominal</span>
                        </div>

                        <div class="row g-3">
                            @php
                                $nominals = [
                                    ['name' => 'Weekly Diamond Pass', 'price' => 'Rp 28.500'],
                                    ['name' => '86 Diamonds', 'price' => 'Rp 20.000'],
                                    ['name' => '172 Diamonds', 'price' => 'Rp 40.000'],
                                    ['name' => '257 Diamonds', 'price' => 'Rp 60.000'],
                                    ['name' => '344 Diamonds', 'price' => 'Rp 80.000'],
                                    ['name' => '429 Diamonds', 'price' => 'Rp 100.000'],
                                    ['name' => '514 Diamonds', 'price' => 'Rp 120.000'],
                                    ['name' => '706 Diamonds', 'price' => 'Rp 160.000'],
                                    ['name' => 'Twilight Pass', 'price' => 'Rp 150.000'],
                                ];
                            @endphp

                            @foreach($nominals as $nom)
                            <div class="col-6 col-md-4">
                                <div class="card h-100 nominal-card p-3 text-center" onclick="selectNominal(this, '{{ $nom['price'] }}')">
                                    <div class="active-icon"><i class="fas fa-check"></i></div>
                                    <div class="fw-bold mb-1 text-body">{{ $nom['name'] }}</div>
                                    <small class="text-primary">{{ $nom['price'] }}</small>
                                    <input type="radio" name="nominal" value="{{ $nom['name'] }}" class="d-none">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="background: var(--card-bg);">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <span class="circle-number">3</span>
                            <span>Metode Pembayaran</span>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <div class="payment-card rounded-3 p-3 d-flex align-items-center justify-content-between" onclick="selectPayment(this)">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" height="30" class="bg-white p-1 rounded">
                                    <div>
                                        <div class="fw-bold text-body">QRIS (All Payment)</div>
                                        <small class="text-muted">Scan QR code</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary payment-price">-</div>
                                    <div class="active-icon"><i class="fas fa-check"></i></div>
                                </div>
                                <input type="radio" name="payment" value="QRIS" class="d-none">
                            </div>

                            <div class="payment-card rounded-3 p-3 d-flex align-items-center justify-content-between" onclick="selectPayment(this)">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" height="30" class="bg-white p-1 rounded">
                                    <div>
                                        <div class="fw-bold text-body">DANA</div>
                                        <small class="text-muted">Potongan otomatis</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary payment-price">-</div>
                                    <div class="active-icon"><i class="fas fa-check"></i></div>
                                </div>
                                <input type="radio" name="payment" value="DANA" class="d-none">
                            </div>

                            <div class="payment-card rounded-3 p-3 d-flex align-items-center justify-content-between" onclick="selectPayment(this)">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Shopee.svg" height="30" class="bg-white p-1 rounded">
                                    <div>
                                        <div class="fw-bold text-body">ShopeePay</div>
                                        <small class="text-muted">Aplikasi Shopee</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary payment-price">-</div>
                                    <div class="active-icon"><i class="fas fa-check"></i></div>
                                </div>
                                <input type="radio" name="payment" value="SHOPEEPAY" class="d-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="background: var(--card-bg);">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <span class="circle-number">4</span>
                            <span>Bukti Pembayaran</span>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control bg-body text-body" id="whatsappNumber" placeholder="08xx">
                            <label for="whatsappNumber">Nomor WhatsApp (Opsional)</label>
                        </div>
                        <p class="small text-muted">*Bukti transaksi akan dikirimkan ke nomor WhatsApp di atas jika diisi.</p>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5 shadow-lg rounded-pill mb-5" id="btnSubmit">
                    <i class="fas fa-shopping-cart me-2"></i> Beli Sekarang
                </button>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logic untuk Nominal Selection
    function selectNominal(element, price) {
        // Reset kelas active dari semua nominal
        $('.nominal-card').removeClass('active');
        // Tambahkan kelas active ke elemen yang diklik
        $(element).addClass('active');

        // Centang radio button di dalamnya
        $(element).find('input[type="radio"]').prop('checked', true);

        // Update harga di bagian Payment Method (Simulasi user experience)
        $('.payment-price').text(price);

        // Animasi kecil scroll ke payment (optional)
        // $('html, body').animate({
        //     scrollTop: $(".payment-card").first().offset().top - 200
        // }, 500);
    }

    // Logic untuk Payment Selection
    function selectPayment(element) {
        $('.payment-card').removeClass('active');
        $(element).addClass('active');
        $(element).find('input[type="radio"]').prop('checked', true);
    }

    // Form Submit Validation
    $('#orderForm').on('submit', function(e) {
        e.preventDefault();

        let userId = $('input[placeholder="Contoh: 12345678"]').val();
        let nominal = $('input[name="nominal"]:checked').val();
        let payment = $('input[name="payment"]:checked').val();

        if(!userId) {
            alert('Mohon isi User ID Anda!');
            $('input[placeholder="Contoh: 12345678"]').focus();
            return;
        }
        if(!nominal) {
            alert('Mohon pilih nominal top up!');
            return;
        }
        if(!payment) {
            alert('Mohon pilih metode pembayaran!');
            return;
        }

        // Simulasi Loading
        let btn = $('#btnSubmit');
        let oriText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        setTimeout(() => {
            alert('Pesanan berhasil dibuat! Mengalihkan ke pembayaran...');
            window.location.href = "{{ url('/order') }}"; // Redirect ke halaman history/pembayaran
        }, 1500);
    });
</script>
@endpush
