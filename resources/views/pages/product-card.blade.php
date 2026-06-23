@php
	// $name = data_get($item, 'name', 'Produk Digital');
	// $image = data_get($item, 'image') ?? data_get($item, 'image_url') ?? asset('default-game.jpg');
	// $id = data_get($item, 'slug') ?? data_get($item, 'id', '#');

	// $variant = $variant ?? 'default';
	// $type = $type ?? data_get($item, 'type') ?? data_get($item, 'category') ?? 'top-up';
	// $typeSlug = Str::slug($type);
	// $name = data_get($item, 'name')
	// 	?? data_get($item, 'title')
	// 	?? data_get($item, 'product_name')
	// 	?? data_get($item, 'product_name_snapshot')
	// 	?? 'Produk Digital';
	// $slug = data_get($item, 'slug') ?? data_get($item, 'code') ?? Str::slug($name);
	// $image = data_get($item, 'image')
	// 	?? data_get($item, 'image_url')
	// 	?? data_get($item, 'thumbnail')
	// 	?? data_get($item, 'icon')
	// 	?? 'https://placehold.co/320x320/0e4d7d/ffffff?text=' . urlencode($name);

	// if ($image && !Str::startsWith($image, ['http://', 'https://', 'data:'])) {
	// 	$image = asset($image);
	// }

	// $url = data_get($item, 'url');
	// if (!$url) {
	// 	$url = Route::has('member.transaction.order') ? route('member.transaction.order', $slug) : url('/order/' . $slug);
	// }
	// $url = "#$slug";

	// $badge = $badge ?? data_get($item, 'badge') ?? 'Top up';
	// $cardClass = $variant === 'popular' ? 'gs-product-card--popular' : '';
	$variant = $variant ?? 'default';
	$type = $type ?? data_get($item, 'type') ?? data_get($item, 'category') ?? 'top-up';
	$typeSlug = Str::slug($type);
		$name = data_get($item, 'name')
		?? data_get($item, 'title')
		?? data_get($item, 'product_name')
		?? data_get($item, 'product_name_snapshot')
		?? 'Produk Digital';
	$slug = data_get($item, 'slug') ?? data_get($item, 'code') ?? Str::slug($name);
	$image = data_get($item, 'image')
		?? data_get($item, 'image_url')
		?? data_get($item, 'thumbnail')
		?? data_get($item, 'icon')
		?? 'https://placehold.co/320x320/0e4d7d/ffffff?text=' . urlencode($name);

	if ($image && !Str::startsWith($image, ['http://', 'https://', 'data:'])) {
		$image = asset($image);
	}

	$url = data_get($item, 'url');
	if (!$url) {
		$url = Route::has('member.transaction.order') ? route('member.transaction.order', $slug) : url('/order/' . $slug);
	}
	$url = "#$slug";

	$badge = $badge ?? data_get($item, 'badge') ?? 'Top up';
	$cardClass = $variant === 'popular' ? 'gs-product-card--popular' : '';
@endphp

<style>
	:root {
		/* --cn-card-bg-start: #17669a;
		--cn-card-bg-end: #103464; */
		--cn-card-bg-start: #1f78b4;
		--cn-card-bg-end: #16477f;
		--cn-card-bg-hover-start: #1f7fbd;
		--cn-card-bg-hover-end: #16477f;

		--cn-card-border: rgba(109, 213, 255, 0.32);
		--cn-card-border-hover: rgba(142, 231, 255, 0.72);

		--cn-title-bg: rgba(5, 19, 43, 0.62);
		--cn-title-text: #ffffff;

		--cn-glow: rgba(54, 181, 255, 0.34);
		--cn-orange-glow: rgba(255, 143, 49, 0.16);
	}

	.cn-card-minimal {
		position: relative;
		display: flex;
		flex-direction: column;
		height: 100%;
		padding: 8px;
		text-decoration: none;
		border-radius: 15px;
		overflow: hidden;

		background:
			linear-gradient(180deg, var(--cn-card-bg-start), var(--cn-card-bg-end));

		border: 1px solid var(--cn-card-border);

		box-shadow:
			0 12px 28px rgba(0, 0, 0, 0.28),
			inset 0 1px 0 rgba(255, 255, 255, 0.12),
			inset 0 -1px 0 rgba(0, 0, 0, 0.18);

		transition:
			transform 0.22s ease,
			border-color 0.22s ease,
			box-shadow 0.22s ease,
			background 0.22s ease;
	}

	.cn-card-minimal::before {
		content: "";
		position: absolute;
		inset: 0;
		opacity: 0.45;
		pointer-events: none;

		background:
			radial-gradient(circle at 20% 0%, rgba(255, 255, 255, 0.18), transparent 32%),
			radial-gradient(circle at 100% 100%, var(--cn-orange-glow), transparent 38%);
	}

	.cn-card-minimal:hover {
		transform: translateY(-6px) scale(1.015);

		background:
			linear-gradient(180deg, var(--cn-card-bg-hover-start), var(--cn-card-bg-hover-end));

		border-color: var(--cn-card-border-hover);

		box-shadow:
			0 18px 42px rgba(0, 0, 0, 0.36),
			0 0 0 1px rgba(106, 220, 255, 0.18),
			0 0 32px var(--cn-glow),
			inset 0 1px 0 rgba(255, 255, 255, 0.18);
	}

	.cn-card-img-wrap {
		position: relative;
		z-index: 1;

		width: 100%;
		aspect-ratio: 1 / 1;
		border-radius: 11px;
		overflow: hidden;

		background:
			linear-gradient(145deg, #eff7ff, #d7e8ff);

		display: flex;
		align-items: center;
		justify-content: center;

		border: 1px solid rgba(255, 255, 255, 0.45);

		box-shadow:
			0 8px 18px rgba(0, 0, 0, 0.28),
			inset 0 0 0 1px rgba(255, 255, 255, 0.18);
	}

	.cn-card-img {
		width: 100%;
		height: 100%;
		object-fit: cover;

		transition:
			transform 0.32s ease,
			filter 0.32s ease;
	}

	.cn-card-minimal:hover .cn-card-img {
		transform: scale(1.06);
		filter: saturate(1.14) contrast(1.06) brightness(1.03);
	}

	.cn-card-title {
		position: relative;
		z-index: 1;

		color: var(--cn-title-text);
		background: var(--cn-title-bg);

		font-size: 12.5px;
		font-weight: 800;
		letter-spacing: 0.01em;
		line-height: 1.25;

		margin: 8px -2px -2px;
		padding: 8px 7px;

		border-radius: 0 0 10px 10px;

		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;

		text-shadow: 0 1px 8px rgba(0, 0, 0, 0.55);
	}
	/* .cn-card-title {
		position: relative;
		z-index: 1;

		color: var(--cn-title-text);
		background: var(--cn-title-bg);

		font-size: 12.5px;
		font-weight: 800;
		letter-spacing: 0.01em;
		line-height: 1.4;

		margin: 8px -2px -2px;
		padding: 9px 8px 10px;

		min-height: 40px;

		border-radius: 0 0 10px 10px;

		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;

		display: flex;
		align-items: center;

		text-shadow: 0 1px 8px rgba(0, 0, 0, 0.55);
	} */

	.cn-card-minimal:focus-visible {
		outline: none;
		border-color: rgba(255, 169, 64, 0.95);
		box-shadow:
			0 0 0 3px rgba(255, 169, 64, 0.24),
			0 16px 36px rgba(0, 0, 0, 0.34);
	}
</style>

{{-- <a href="{{ $id !== '#' ? route('product.detail', $id) : '#' }}" class="cn-card-minimal" data-product-type="{{ $typeSlug }}" title="{{ $name }}"> --}}

<a href="{{ $url }}" class="cn-card-minimal {{ $cardClass }} js-product-card" data-product-type="{{ $typeSlug }}" title="{{ $name }}">
	<div class="cn-card-img-wrap">
		<img src="{{ $image }}" alt="{{ $name }}" class="cn-card-img" loading="lazy">
	</div>
	<h3 class="cn-card-title">{{ $name }}</h3>
</a>



{{-- <a href="{{ $url }}" class="gs-product-card {{ $cardClass }} js-product-card" data-product-type="{{ $typeSlug }}" title="{{ $name }}">
	<div class="gs-product-thumb">
		<img src="{{ $image }}" alt="{{ $name }}" loading="lazy">

		@if($variant === 'popular')
			<div class="gs-popular-meta">
				<span class="gs-popular-title">{{ $name }}</span>
				<span class="gs-popular-badge">{{ $badge }}</span>
			</div>
		@endif
	</div>

	@if($variant !== 'popular')
		<span class="gs-product-name">{{ $name }}</span>
	@endif
</a> --}}




{{-- <a href="{{ $url }}" class="gs-product-card {{ $cardClass }} js-product-card" data-product-type="{{ $typeSlug }}" title="{{ $name }}">
	<div class="gs-product-thumb">
		<img src="{{ $image }}" alt="{{ $name }}" loading="lazy">

		@if($variant === 'popular')
		<div class="gs-popular-meta">
			<span class="gs-popular-badge">{{ $badge }}</span>
		</div>
		@endif
	</div>

	@if($variant !== 'popular')
	<span class="gs-product-name">{{ $name }}</span>
	@endif
</a> --}}
