@extends('layouts.front') {{-- Panggil layout SALJU tadi --}}

@section('title', 'Top Up Game Murah')

@section('content')
<div class="container py-5">

	{{-- Hero Section --}}
	<div class="text-center mb-5" style="padding: 60px 0;">
		<h1 class="display-4 fw-bold mb-3">
			Top Up Game <span class="text-primary-gradient">Termurah</span>
		</h1>
		<p class="lead" style="color: var(--text-muted);">
			Proses detik, buka 24 jam, metode pembayaran lengkap.
		</p>
	</div>

	{{-- Grid Produk --}}
	<div class="row g-4">
		@php
			$games = [
				['name' => 'Mobile Legends', 'img' => 'https://example.com/ml.jpg'],
				['name' => 'Free Fire', 'img' => 'https://example.com/ff.jpg'],
				['name' => 'PUBG Mobile', 'img' => 'https://example.com/pubg.jpg'],
				['name' => 'Valorant', 'img' => 'https://example.com/valo.jpg'],
			];
		@endphp

		@foreach($games as $game)
		<div class="col-6 col-md-3">
			<div class="game-card h-100 position-relative">
				<div class="ratio ratio-1x1 bg-dark">
					{{-- Ganti src dengan gambar asli --}}
					<img src="https://via.placeholder.com/300x300?text={{ $game['name'] }}" class="card-img-top object-fit-cover" alt="{{ $game['name'] }}">
				</div>
				<div class="p-3 text-center">
					<h6 class="card-title mb-1 small">{{ $game['name'] }}</h6>
					<small style="color: var(--text-muted)">Garena / Moonton</small>
					<div class="d-grid mt-2">
						<button class="btn btn-sm btn-primary rounded-pill">Top Up</button>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>

</div>
@endsection
