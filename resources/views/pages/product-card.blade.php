@php
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

<a href="{{ $url }}" class="gs-product-card {{ $cardClass }} js-product-card" data-product-type="{{ $typeSlug }}" title="{{ $name }}">
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
</a>
