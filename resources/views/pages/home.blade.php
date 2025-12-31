@extends('layouts.app')

@section('title', 'ID Cloud Store')

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

@section('content')
<section class="hero-section text-center mt-5 pt-5 pb-4">
	<div class="container mt-4">
		<h1 class="display-4 fw-bold text-primary-gradient mb-3">Top Up Instan & Resmi</h1>
		<p class="text-muted fs-5 mb-4">Layanan Top Up Game & Voucher Termurah 24 Jam</p>

		<div class="d-flex justify-content-center flex-wrap gap-2" id="category-filters">
			<button class="btn btn-outline-primary active rounded-pill px-4 filter-btn" data-filter="all">Semua</button>
			<button class="btn btn-outline-secondary rounded-pill px-4 filter-btn" data-filter="game">Games</button>
			<button class="btn btn-outline-secondary rounded-pill px-4 filter-btn" data-filter="pulsa">Pulsa</button>
			<button class="btn btn-outline-secondary rounded-pill px-4 filter-btn" data-filter="voucher">Voucher</button>
		</div>
	</div>
</section>


<section class="container mb-5" id="products">
	<div class="row g-3 g-md-4">
		{{-- Data Dummy (Sama seperti sebelumnya) --}}
		@php
		$products = [
		['name' => 'Mobile Legends', 'cat' => 'game', 'pub' => 'Moonton', 'img' => 'https://cdn1.codashop.com/S/content/mobile/images/product-tiles/MLBB-2025-tiles-178x178.jpg'],
		['name' => 'Free Fire', 'cat' => 'game', 'pub' => 'Garena', 'img' => 'https://cdn1.codashop.com/S/content/mobile/images/product-tiles/free-fire-tile-codacash-new.jpg'],
		['name' => 'Telkomsel', 'cat' => 'pulsa', 'pub' => 'Data & Pulsa', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg'],
		['name' => 'Google Play', 'cat' => 'voucher', 'pub' => 'Kode Voucher', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/d/d0/Google_Play_Arrow_logo.svg'],
		['name' => 'Genshin Impact', 'cat' => 'game', 'pub' => 'Hoyoverse', 'img' => 'https://cdn1.codashop.com/S/content/mobile/images/product-tiles/Genshin-Impact-tile-178x178.jpg'],
		['name' => 'PUBG Mobile', 'cat' => 'game', 'pub' => 'Tencent', 'img' => 'https://cdn1.codashop.com/S/content/mobile/images/product-tiles/PUBGM_tile_178x178.jpg'],
		];
		@endphp
		@foreach($products as $index => $item)
		<div class="col-6 col-md-3 col-lg-2 product-item" data-category="{{ $item['cat'] }}">
			<a href="{{ url('/topup/'.Str::slug($item['name'])) }}" class="text-decoration-none">
				<div class="gaming-card h-100">

					{{-- Badge Promo --}}
					@if($item['cat'] == 'game' && $index % 2 == 0)
					<div class="promo-badge">
						<i class="fas fa-coins me-1"></i> Diskon
					</div>
					@endif

					{{-- LOGIC PHP UNTUK POSISI GAMBAR --}}
					@php
						// Jika Game: Full Cover. Jika Logo: Contain (utuh)
						$imgClass = ($item['cat'] == 'game') ? 'object-fit-cover' : 'object-fit-contain';

						$bgImageWrapper = ($item['cat'] == 'game') ? '' : 'background: #f1f5f9;';

						$imgStyle = ($item['cat'] == 'game')
							? ''
							: 'padding: 1.5rem 1.5rem 3.5rem 1.5rem;';
					@endphp

					<div class="jagged-image-wrapper" style="{{ $bgImageWrapper }}">
						<img src="{{ $item['img'] }}"
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

	</div>
</section>
@include('components.modal')

@endsection

@push('scripts')
<script>
	// Logic Filter
	$('.filter-btn').click(function() {
		$('.filter-btn').removeClass('active btn-outline-primary').addClass('btn-outline-secondary');
		$(this).addClass('active btn-outline-primary').removeClass('btn-outline-secondary');

		const filter = $(this).data('filter');
		if (filter === 'all') {
			$('.product-item').fadeIn(200);
		} else {
			$('.product-item').hide();
			$('.product-item[data-category="' + filter + '"]').fadeIn(200);
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
@endpush
