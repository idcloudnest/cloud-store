@extends('layouts.front')

@section('title', 'Top Up Game & PPOB')

@section('content')
@php
	$allProducts = collect($products ?? $catalogProducts ?? []);

	$sampleTopup = collect([
		['name' => 'MLBB', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/1d8ad8/ffffff?text=MLBB'],
		['name' => 'PUBG Mobile', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/2b5f8f/ffffff?text=PUBG'],
		['name' => 'FF', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/654ea3/ffffff?text=FF'],
		['name' => 'METAL SLUG', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/d8742d/ffffff?text=METAL+SLUG'],
		['name' => 'GARENA UNDAWN', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/203047/ffffff?text=UNDAWN'],
		['name' => 'CALL OF DUTY', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/475569/ffffff?text=CODM'],
		['name' => 'POINT BLANK', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/3f3f46/ffffff?text=PB'],
		['name' => 'RIDE OUT HEROES', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/a21caf/ffffff?text=ROH'],
		['name' => 'RAGNAROK M', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/f5a3bc/ffffff?text=RAGNAROK'],
		['name' => 'ARENA OF VALOR', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/33a6db/ffffff?text=AOV'],
		['name' => 'MANGO LIVE', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/9333ea/ffffff?text=MANGO'],
		['name' => 'LIFE AFTER', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/64748b/ffffff?text=LIFE+AFTER'],
		['name' => 'MARVEL SUPER WAR', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/d946ef/ffffff?text=MARVEL'],
		['name' => 'Honor of Kings', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/f59e0b/ffffff?text=HOK'],
		['name' => 'ARENA BREAKOUT', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/334155/ffffff?text=ARENA'],
		['name' => 'AUDITION', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/ec4899/ffffff?text=AUDITION'],
		['name' => 'BLACK CLOVER M', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/111827/ffffff?text=BLACK+CLOVER'],
		['name' => 'BLOOD STRIKE', 'category' => 'Top Up', 'image' => 'https://placehold.co/320x320/b91c1c/ffffff?text=BLOOD+STRIKE'],
	]);

	$sampleCinema = collect([
		['name' => 'TIXID', 'category' => 'Voucher', 'image' => 'https://placehold.co/320x320/f8c56b/082f49?text=TIX'],
		['name' => 'MTIX', 'category' => 'Voucher', 'image' => 'https://placehold.co/320x320/064e3b/ffffff?text=XXI+m.tix'],
	]);

	$sampleEtoll = collect([
		['name' => 'Saldo E-Toll Mandiri', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/020617/ffffff?text=mandiri+e-toll'],
		['name' => 'Saldo E-Toll BNI', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/f59e0b/ffffff?text=BNI+e-toll'],
		['name' => 'Saldo E-Toll BRI', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/29388f/ffffff?text=BRIZZI'],
	]);

	$sampleBills = collect([
		['name' => 'PLN Token Tagihan', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/facc15/e11d48?text=PLN'],
		['name' => 'PDAM', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/ffffff/0f80b7?text=PDAM'],
		['name' => 'BPJS Kesehatan', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/ffffff/22c55e?text=BPJS'],
		['name' => 'BPJS Ketenagakerjaan', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/ffffff/16a34a?text=BPJS+TK'],
		['name' => 'Telkom IndiHome', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/ffffff/e11d48?text=INDIHOME'],
		['name' => 'Multifinance', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/38bdf8/ffffff?text=MULTIFINANCE'],
		['name' => 'Telepon Pascabayar', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/38bdf8/ffffff?text=TELPON'],
		['name' => 'Internet & TV', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/38bdf8/ffffff?text=INTERNET+TV'],
		['name' => 'Kartu Kredit', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/38bdf8/ffffff?text=KARTU+KREDIT'],
		['name' => 'Asuransi', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/38bdf8/ffffff?text=ASURANSI'],
		['name' => 'PGN', 'category' => 'Pembayaran', 'image' => 'https://placehold.co/320x320/ffffff/0ea5e9?text=PGN'],
	]);

	$topupItems = collect($topupProducts ?? $gameProducts ?? []);
	if ($topupItems->isEmpty() && $allProducts->isNotEmpty()) {
		$topupItems = $allProducts;
	}
	if ($topupItems->isEmpty()) {
		$topupItems = $sampleTopup;
	}

	$popularItems = collect($popularProducts ?? []);
	if ($popularItems->isEmpty()) {
		$popularItems = $topupItems->take(5)->values();
	}

	$cinemaItems = collect($cinemaProducts ?? $bioskopProducts ?? []);
	if ($cinemaItems->isEmpty()) {
		$cinemaItems = $sampleCinema;
	}

	$etollItems = collect($etollProducts ?? $eTollProducts ?? []);
	if ($etollItems->isEmpty()) {
		$etollItems = $sampleEtoll;
	}

	$billItems = collect($billProducts ?? $tagihanProducts ?? []);
	if ($billItems->isEmpty()) {
		$billItems = $sampleBills;
	}

	$bannerItems = collect($banners ?? []);
	if ($bannerItems->isEmpty()) {
		$bannerItems = collect([
			[
				'kicker' => 'Pakai Outfit Kece<br>Buat Naik Gunung di Map Roblox',
				'title' => 'Top Up Robux Sekarang',
				'subtitle' => 'Langsung di ' . config('app.name', 'CloudNest'),
			],
			[
				'kicker' => 'Top up game favorit kamu',
				'title' => 'Murah, Cepat, Aman',
				'subtitle' => 'Checkout otomatis 24 jam',
			],
			[
				'kicker' => 'Bayar tagihan tanpa ribet',
				'title' => 'PPOB Digital Lengkap',
				'subtitle' => 'Pulsa, PLN, E-Toll, BPJS',
			],
		]);
	}
@endphp

<div class="container">
	<section class="gs-hero-wrap">
		<div id="homeBannerCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				@foreach($bannerItems as $banner)
					@php
						$bannerImage = data_get($banner, 'image') ?? data_get($banner, 'image_url');
						if ($bannerImage && !Str::startsWith($bannerImage, ['http://', 'https://', 'data:'])) {
							$bannerImage = asset($bannerImage);
						}
					@endphp

					<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
						<div class="gs-hero" @if($bannerImage) style="background-image: linear-gradient(90deg, rgba(0, 142, 231, .92) 0%, rgba(42, 205, 221, .68) 54%, rgba(255,255,255,.06) 100%), url('{{ $bannerImage }}');" @endif>
							<div class="gs-hero-content">
								<div class="gs-hero-kicker">{!! data_get($banner, 'kicker', 'Top up produk digital favorit') !!}</div>
								<h1 class="gs-hero-title">{{ data_get($banner, 'title', 'Top Up Sekarang') }}</h1>
								<div class="gs-hero-subtitle">{{ data_get($banner, 'subtitle', config('app.name')) }}</div>
							</div>

							<div class="gs-payment-badge">
								<span>BISA BAYAR PAKAI</span>
								<div class="gs-payment-logos">
									<strong>QRIS</strong>
									<strong>ShopeePay</strong>
									<strong>VA</strong>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>

			<div class="carousel-indicators">
				@foreach($bannerItems as $banner)
					<button type="button" data-bs-target="#homeBannerCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
				@endforeach
			</div>
		</div>
	</section>

	<section class="gs-section">
		<h2 class="gs-section-title">🔥 Game Populer</h2>
		<div class="gs-popular-row">
			@foreach($popularItems as $item)
				@include('components.front.product-card', ['item' => $item, 'type' => 'top-up', 'variant' => 'popular', 'badge' => 'Top up'])
			@endforeach
		</div>
	</section>
</div>

<div class="gs-red-divider" aria-hidden="true"></div>

<div class="container">
	<div class="gs-filter-pills">
		<button type="button" class="gs-filter-btn active" data-filter-target="all">All</button>
		<button type="button" class="gs-filter-btn" data-filter-target="top-up">Top Up</button>
		<button type="button" class="gs-filter-btn" data-filter-target="voucher">Voucher</button>
		<button type="button" class="gs-filter-btn" data-filter-target="pembayaran">Pembayaran</button>
	</div>

	<section class="gs-section js-product-section" data-section-type="top-up">
		<h2 class="gs-section-title">Top up game</h2>
		<div class="gs-product-grid">
			@foreach($topupItems as $item)
				@include('components.front.product-card', ['item' => $item, 'type' => 'top-up'])
			@endforeach
		</div>
	</section>

	<section class="gs-section js-product-section" data-section-type="voucher">
		<h2 class="gs-section-title">Bioskop</h2>
		<div class="gs-product-grid">
			@foreach($cinemaItems as $item)
				@include('components.front.product-card', ['item' => $item, 'type' => 'voucher'])
			@endforeach
		</div>
	</section>

	<section class="gs-section js-product-section" data-section-type="pembayaran">
		<h2 class="gs-section-title">E-Toll</h2>
		<div class="gs-product-grid">
			@foreach($etollItems as $item)
				@include('components.front.product-card', ['item' => $item, 'type' => 'pembayaran'])
			@endforeach
		</div>
	</section>

	<section class="gs-section js-product-section" data-section-type="pembayaran">
		<h2 class="gs-section-title">Tagihan</h2>
		<div class="gs-product-grid">
			@foreach($billItems as $item)
				@include('components.front.product-card', ['item' => $item, 'type' => 'pembayaran'])
			@endforeach
		</div>
	</section>
</div>
@endsection
