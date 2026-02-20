{{-- @extends('layouts.app')

@section('title', config('app.name'))


@section('content')
	<section class="hero-section text-center mt-5 pt-5 pb-4">
		<div class="container mt-4">
			<h1 class="display-4 fw-bold text-primary-gradient mb-3">Top Up Instan & Resmi</h1>
			<p class="text-muted fs-5 mb-4">Layanan Top Up Game & Voucher Termurah 24 Jam</p>

			<div class="d-flex justify-content-center flex-wrap gap-2" id="category-filters">
				<button class="btn btn-outline-primary active rounded-pill px-4 filter-btn" data-filter="all">Semua</button>
				@if (count($categories))
					@foreach ($categories as $item)
						<button class="btn btn-outline-secondary rounded-pill px-4 filter-btn" data-filter="{{ $item->id }}">{{ strtoupper($item->name) }}</button>
					@endforeach
				@endif
			</div>
		</div>
	</section>


	<section class="container mb-5" id="products">
		<div class="row g-3 g-md-4">
			@if (count($brands))
				@foreach ($brands as $item)
					<div class="col-6 col-md-3 col-lg-2 product-item" data-category="{{ json_encode($item->category_list) }}">
						<a href="{{ url('/topup/'.Str::slug($item->slug)) }}" class="text-decoration-none">
							<div class="gaming-card h-100">
								@if($item->slug == 'game' && $index % 2 == 0)
									<div class="promo-badge">
										<i class="fas fa-coins me-1"></i> Diskon
									</div>
								@endif

								@php
									// Jika Game: Full Cover. Jika Logo: Contain (utuh)
									$imgClass = ($item->category_id == 6) ? 'object-fit-cover' : 'object-fit-contain';

									$bgImageWrapper = ($item->category_id == 6) ? '' : 'background: #f1f5f9;';

									$imgStyle = ($item->category_id == 6)
										? 'padding: 0 0 0.5rem 0;'
										: 'padding: 1.5rem 1.5rem 3rem 1.5rem;';
								@endphp

								<div class="jagged-image-wrapper" style="{{ $bgImageWrapper }}">
									@php $assetParse = $item['image'] ? config('app.asset_url').assetParse($item['image']) : '#'; @endphp
									<img src="{{$assetParse}}"
									class="position-absolute top-0 start-0 w-100 h-100 {{ $imgClass }}"
									style="{{ $imgStyle }}"
									alt="{{ $item['name'] }}"
									loading="lazy">
								</div>

								<div class="card-info text-center">
									<div class="game-title text-truncate">{{ $item['name'] }}</div>
									<div class="game-publisher">{{ $item['pub'] }}</div>
								</div>

							</div>
						</a>
					</div>
				@endforeach
			@endif
		</div>
	</section>
@endsection

@push('styles')
	<style>
		/* ... (Style lama tetap ada) ... */

		/* CUSTOM CARD STYLE (Gaming Theme) */
		.gaming-card {
			background: var(--card-bg); /* Mengikuti tema */
			border: none; /* Hapus border default */
			border-radius: 16px;
			overflow: hidden;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			position: relative;
		}

		.gaming-card:hover {
			transform: translateY(-8px) scale(1.02);
			box-shadow: 0 12px 24px rgba(var(--primary-color-rgb), 0.3);
			z-index: 10;
		}

		/* BADGE PROMO (Kuning) */
		.promo-badge {
			position: absolute;
			top: 0;
			right: 0;
			background: #facc15; /* Kuning terang */
			color: #000;
			font-weight: 800;
			font-size: 0.75rem;
			padding: 4px 12px;
			border-bottom-left-radius: 12px;
			z-index: 5;
			box-shadow: -2px 2px 5px rgba(0,0,0,0.2);
		}

		/* Icon Coin di Badge */
		.promo-badge i {
			color: #b45309; /* Warna icon lebih gelap */
		}

		/* UPDATE BAGIAN INI */
		.jagged-image-wrapper {
			position: relative;
			/* UBAH DARI 75% KE 100% */
			padding-top: 100%;
			overflow: hidden;

			/* UPDATE CLIP-PATH: */
			/* Saya ubah angkanya agar potongan geriginya lebih di ujung bawah (90%-95%) */
			/* Supaya gambar tidak terlalu terpotong banyak */
			clip-path: polygon(
			0 0, 100% 0, 100% 90%,
			92% 95%, 84% 90%, 76% 95%, 68% 90%,
			60% 95%, 52% 90%, 44% 95%, 36% 90%,
			28% 95%, 20% 90%, 12% 95%, 4% 90%,
			0 95%
			);

			background: #2d3748;
		}

		/* OPSI TAMBAHAN: Tambah tinggi area teks sedikit */
		.card-info {
			padding: 1.5rem 1rem; /* Padding diperbesar sedikit */
			background: linear-gradient(to bottom, var(--card-bg), rgba(0,0,0,0.2));
		}

		.game-title {
			font-weight: 700;
			font-size: 0.95rem;
			margin-bottom: 4px;
			color: var(--text-main);
		}

		.game-publisher {
			font-size: 0.75rem;
			color: var(--text-muted);
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
	</style>
@endpush

@push('scripts')
<script>
	$('.filter-btn').click(function() {
		$('.filter-btn').removeClass('active btn-outline-primary').addClass('btn-outline-secondary');
		$(this).addClass('active btn-outline-primary').removeClass('btn-outline-secondary');

		const filter = $(this).data('filter');

		if (filter === 'all') {
			$('.product-item').fadeIn(200);
		} else {
			// Sembunyikan semua dulu
			$('.product-item').hide();

			// Filter logic untuk Array
			$('.product-item').filter(function() {
				// Ambil data (jQuery otomatis mengubah JSON string jadi Array asli)
				let categories = $(this).data('category');

				// Cek apakah filter ada di dalam array categories
				// Contoh: ["games", "pulsa"].includes("games") -> true
				// return Array.isArray(categories) && categories.includes(filter);
				return categories.includes(filter);
			}).fadeIn(200);
		}
	});

	// Logic Buka Modal & Inject Data Dummy
	function openOrderModal(name, category) {
		$('#modalTitle').text('Top Up ' + name);
		$('#topupForm')[0].reset();

		let nominalHtml = '';
		let items = [];

		// Simulasi Item Berdasarkan Kategori
		if(category === 'Game') {
			$('#zoneIdInput').show();
			items = ["86 Diamond", "172 Diamond", "Weekly Pass", "Starlight"];
		} else {
			$('#zoneIdInput').hide();
			items = ["10.000", "25.000", "50.000", "100.000"];
		}

		// Render Tombol Nominal
		items.forEach((val, index) => {
			nominalHtml += `
				<div class="col-6 col-md-3">
					<input type="radio" class="btn-check" name="nominal" id="nom${index}" value="${val}">
					<label class="btn btn-outline-primary w-100" for="nom${index}">${val}</label>
				</div>
			`;
		});
		$('#nominalContainer').html(nominalHtml);

		var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
		myModal.show();
	}
</script>
@endpush --}}


@extends('layouts.app')

@section('title', 'Top Up Game & Pulsa Termurah')

@section('content')

	{{-- <section class="container mt-4">
		<div id="heroCarousel" class="carousel slide shadow rounded-4 overflow-hidden" data-bs-ride="carousel">
			<div class="carousel-indicators">
				@foreach($banners as $key => $banner)
					<button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
				@endforeach
			</div>
			<div class="carousel-inner">
				@foreach($banners as $key => $banner)
					<div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
						<img src="{{ $banner['image'] }}" class="d-block w-100 object-fit-cover" alt="{{ $banner['alt'] }}" style="height: 180px; md:height: 350px;" loading="lazy">
					</div>
				@endforeach
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon bg-dark rounded-circle p-3 bg-opacity-25" aria-hidden="true"></span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon bg-dark rounded-circle p-3 bg-opacity-25" aria-hidden="true"></span>
			</button>
		</div>
	</section>

	<section class="container mt-3">
		<div class="bg-white rounded-pill shadow-sm px-3 py-2 d-flex align-items-center gap-3">
			<span class="badge bg-primary rounded-pill"><i class="fas fa-bullhorn me-1"></i> Info</span>
			<marquee class="text-muted small fw-semibold" scrollamount="5">
				@foreach($lastTransactions as $trx)
					<span class="me-5"><i class="fas fa-check-circle text-success me-1"></i> {{ $trx }}</span>
				@endforeach
			</marquee>
		</div>
	</section> --}}

	{{-- 3. FILTER KATEGORI --}}
	<section class="container mt-4">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h5 class="fw-bold mb-0 border-start border-4 border-primary ps-3">Pilih Kategori</h5>
		</div>

		{{-- Scrollable di mobile --}}
		<div class="d-flex flex-nowrap overflow-auto gap-2 pb-2 hide-scrollbar" id="category-filters">
			<button class="btn btn-primary active rounded-pill px-4 filter-btn flex-shrink-0" data-filter="all">
				<i class="fas fa-th-large me-1"></i> Semua
			</button>
			@foreach ($categories as $item)
				<button class="btn btn-outline-secondary rounded-pill px-4 filter-btn flex-shrink-0" data-filter="{{ $item->id }}">
					{{-- Icon logic sederhana (bisa disesuaikan dengan DB) --}}
					@if(str_contains(strtolower($item->name), 'game')) <i class="fas fa-gamepad me-1"></i>
					@elseif(str_contains(strtolower($item->name), 'pulsa')) <i class="fas fa-mobile-alt me-1"></i>
					@else <i class="fas fa-tag me-1"></i>
					@endif
					{{ strtoupper($item->name) }}
				</button>
			@endforeach
		</div>
	</section>

	{{-- 4. GRID PRODUK --}}
	<section class="container mb-5 mt-3" id="products">
		<div class="row g-2 g-md-3">
			@foreach ($brands as $item)
				<div class="col-4 col-md-3 col-lg-2 product-item" data-category="{{ json_encode($item->category_list) }}">
					<a href="{{ url('/topup/'.$item->slug) }}" class="text-decoration-none">
						<div class="card h-100 border-0 shadow-sm hover-up overflow-hidden">
							{{-- Image Wrapper --}}
							<div class="position-relative bg-light ratio ratio-1x1">
								@php
									$image = $item->image ? config('app.asset_url').assetParse($item->image) : 'https://via.placeholder.com/150';
									// Game biasanya cover art (full), pulsa logo (contain)
									$imgClass = ($item->category_id == 6) ? 'object-fit-cover' : 'object-fit-contain p-3';
								@endphp
								<img src="{{ $image }}" class="{{ $imgClass }} w-100 h-100" alt="{{ $item->name }}" loading="lazy">

								{{-- Badge Kategori (Opsional) --}}
								{{-- <span class="position-absolute top-0 end-0 badge bg-dark bg-opacity-50 m-1" style="font-size: 0.6rem">
									{{ $item->category->name ?? 'Item' }}
								</span> --}}
							</div>

							<div class="card-body p-2 text-center">
								<h6 class="card-title fw-bold fs-7 mb-0 text-dark text-truncate">{{ $item->name }}</h6>
								<small class="text-muted" style="font-size: 0.7rem;">{{ $item->category->name ?? 'Otomatis' }}</small>
							</div>
						</div>
					</a>
				</div>
			@endforeach
		</div>
	</section>

	{{-- 5. KEUNGGULAN (Why Choose Us) --}}
	{{-- <section class="bg-white py-5 mt-5 border-top">
		<div class="container">
			<div class="row g-4 text-center">
				<div class="col-md-4">
					<div class="p-3">
						<i class="fas fa-bolt fa-3x text-warning mb-3"></i>
						<h5 class="fw-bold">Proses Kilat</h5>
						<p class="text-muted small">Detik itu bayar, detik itu juga item masuk ke akun kamu.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="p-3">
						<i class="fas fa-headset fa-3x text-primary mb-3"></i>
						<h5 class="fw-bold">Layanan 24/7</h5>
						<p class="text-muted small">Sistem kami online 24 jam non-stop melayani transaksimu.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="p-3">
						<i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
						<h5 class="fw-bold">Aman & Resmi</h5>
						<p class="text-muted small">Produk 100% legal dan aman untuk akun kesayanganmu.</p>
					</div>
				</div>
			</div>
		</div>
	</section> --}}

	{{-- 5. KEUNGGULAN (Why Choose Us) - REVISI --}}
<section class="py-5 my-5">
    <div class="container">
        {{-- Judul Section --}}
        <div class="text-center mb-5">
            <h3 class="fw-bold section-title">Kenapa Memilih Kami?</h3>
            <div class="divider mx-auto bg-primary rounded"></div>
        </div>

        <div class="row g-4">
            {{-- Item 1 --}}
            <div class="col-md-4">
                <div class="feature-card glass-card p-4 h-100 text-center position-relative overflow-hidden">
                    <div class="icon-box mb-3 mx-auto bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-main">Proses Kilat</h5>
                    <p class="text-muted small mb-0">Sistem otomatis memproses pesananmu dalam hitungan detik setelah pembayaran terkonfirmasi.</p>
                </div>
            </div>

            {{-- Item 2 --}}
            <div class="col-md-4">
                <div class="feature-card glass-card p-4 h-100 text-center position-relative overflow-hidden">
                    <div class="icon-box mb-3 mx-auto bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-headset fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-main">Layanan 24/7</h5>
                    <p class="text-muted small mb-0">Kendala transaksi? Tim support kami siap membantu kapanpun kamu butuhkan.</p>
                </div>
            </div>

            {{-- Item 3 --}}
            <div class="col-md-4">
                <div class="feature-card glass-card p-4 h-100 text-center position-relative overflow-hidden">
                    <div class="icon-box mb-3 mx-auto bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-main">Aman & Resmi</h5>
                    <p class="text-muted small mb-0">Transaksi 100% aman dengan enkripsi SSL. Produk legal dan bergaransi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
	/* Styling Tambahan */
	.hover-up { transition: transform 0.2s ease, box-shadow 0.2s ease; }
	.hover-up:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }

	.fs-7 { font-size: 0.85rem; }

	/* Hide Scrollbar for category buttons */
	.hide-scrollbar::-webkit-scrollbar { display: none; }
	.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

	/* Gradient Text */
	.text-primary-gradient {
		background: linear-gradient(to right, var(--bs-primary), var(--bs-info));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
	}


	/* Section Feature Adaptive */
	.section-features {
		background-color: var(--card-bg); /* Putih di Light, Abu Gelap di Dark */
		transition: background-color 0.3s ease;
	}
	/* Memastikan Judul kontras */
	.text-main { color: var(--text-main); }
	/* Memastikan border-top tidak terlalu terang di dark mode */
	.border-top { border-color: var(--card-border) !important; }
</style>
@endpush

@push('scripts')
<script>
	// Logic Filter Kategori
	$('.filter-btn').click(function() {
		// Style Active
		$('.filter-btn').removeClass('btn-primary active').addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary').addClass('btn-primary active');

		const filter = $(this).data('filter');

		// Animasi Filter
		const $products = $('.product-item');

		if (filter === 'all') {
			$products.fadeIn(200);
		} else {
			$products.hide().filter(function() {
				let categories = $(this).data('category');
				// Pastikan data-category array, jika int ubah ke array untuk includes
				if(!Array.isArray(categories)) categories = [categories];
				return categories.includes(filter) || categories.includes(parseInt(filter));
			}).fadeIn(200);
		}
	});
</script>
@endpush
