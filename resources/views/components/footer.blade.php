<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4">
                <h5><i class="fas fa-cloud me-2"></i>CLOUD NEST STORE</h5>
                <p class="small text-muted" style="text-align: justify;">
                    Platform penyedia produk digital terlengkap dan terpercaya.
                    Mulai dari Top Up Game, Pulsa, Paket Data, Voucher, hingga Token PLN
                    tersedia dengan proses instan dan berbagai metode pembayaran otomatis.
                </p>
            </div>

            <div class="col-lg-3 mb-4">
                <h5>Bantuan</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <a href="{{ route('pages.cara-order') }}" class="text-decoration-none text-muted">Cara Order</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('pages.contact') }}" class="text-decoration-none text-muted">Hubungi Kami</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('pages.terms') }}" class="text-decoration-none text-muted">Syarat & Ketentuan</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('pages.privacyPolicy') }}" class="text-decoration-none text-muted">Kebijakan Privasi</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 mb-4">
                <h5>Kontak</h5>
                <p class="small text-muted">
                    <i class="fab fa-whatsapp me-2"></i> +62 895-3208-94991<br>
                    <i class="fas fa-envelope me-2"></i> admin@idcloudnest.com
                </p>
            </div>
        </div>
    </div>

    <div class="footer-bottom text-center">
        <small>&copy; {{ date('Y') }} Cloud Nest Store. All Rights Reserved.</small>
    </div>
</footer>
