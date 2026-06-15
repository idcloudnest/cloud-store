@php
	$currentRoute = Route::currentRouteName();
	$appName = $appName ?? config('app.name', 'Cloud Nest Store');
	$loginUrl = Route::has('auth.login') ? route('auth.login') : url('/login');
	$registerUrl = Route::has('auth.register') ? route('auth.register') : url('/register');
	$invoiceUrl = Route::has('pages.invoices') ? route('pages.invoices') : url('/invoices');
	$memberDashboardUrl = Route::has('member.dashboard') ? route('member.dashboard') : url('/dashboard');
	$adminDashboardUrl = Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard');
	$dashboardUrl = auth()->check() && auth()->user()?->role !== 'member' ? $adminDashboardUrl : $memberDashboardUrl;
	$userName = auth()->check() ? auth()->user()->name : 'Guest';
	$userBalance = auth()->check() ? (auth()->user()->balance ?? auth()->user()->saldo ?? 0) : 0;
	$coinText = is_numeric($userBalance) ? number_format($userBalance, 0, ',', '.') : $userBalance;
	$logo = asset('cloudnest.png');
	$brandWords = preg_split('/\s+/', trim($appName));
	$brandLast = count($brandWords) > 1 ? array_pop($brandWords) : '';
	$brandFirst = count($brandWords) ? implode(' ', $brandWords) : $appName;
@endphp

<header class="gs-header">
	<div class="gs-header-top">
		<div class="container gs-header-inner">
			<a href="{{ url('/') }}" class="gs-logo" aria-label="{{ $appName }}">
				<span class="gs-logo-mark">
					<img src="{{ $logo }}" alt="{{ $appName }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
					<span class="gs-logo-fallback" style="display:none;">CN</span>
				</span>
				<span class="gs-logo-copy">
					<strong>{{ $brandFirst }}</strong>
					@if($brandLast)
						<small>{{ $brandLast }}</small>
					@endif
				</span>
			</a>

			<form class="gs-search" action="{{ url('/') }}" method="GET">
				<input type="search" name="q" value="{{ request('q') }}" placeholder="Cari Game atau Voucher" autocomplete="off">
				<button type="submit" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
			</form>

			<div class="gs-actions">
				@guest
					<a href="{{ $loginUrl }}" class="gs-icon-btn" aria-label="Masuk">
						<i class="fa-solid fa-circle-user"></i>
					</a>
				@else
					<a href="{{ $dashboardUrl }}" class="gs-icon-btn" title="{{ $userName }}" aria-label="Dashboard">
						<i class="fa-solid fa-circle-user"></i>
					</a>
				@endguest

				<a href="{{ $dashboardUrl }}" class="gs-coin-btn" aria-label="Saldo">
					<span class="gs-coin-dot"></span>
					<span>{{ $coinText }}</span>
				</a>

				@guest
					<a href="{{ $registerUrl }}" class="gs-reseller-btn">Daftar Reseller</a>
				@else
					<a href="#reseller" class="gs-reseller-btn">Daftar Reseller</a>
				@endguest
			</div>
		</div>
	</div>

	<div class="gs-header-menu">
		<div class="container">
			<ul class="gs-menu-list">
				<li>
					<a class="gs-menu-link {{ ($currentRoute === 'pages.home' || $currentRoute === 'home' || request()->is('/')) ? 'active' : '' }}" href="{{ url('/') }}">
						<i class="fa-solid fa-bag-shopping"></i> Top up
					</a>
				</li>
				<li>
					<a class="gs-menu-link {{ $currentRoute === 'pages.invoices' ? 'active' : '' }}" href="{{ $invoiceUrl }}">
						<i class="fa-regular fa-file-lines"></i> Riwayat transaksi
					</a>
				</li>
				<li>
					<a class="gs-menu-link" href="#leaderboard">
						<i class="fa-solid fa-trophy"></i> Leaderboard
					</a>
				</li>
				<li>
					<a class="gs-menu-link" href="#reseller">
						<i class="fa-solid fa-qrcode"></i> Reseller
					</a>
				</li>
				<li>
					<a class="gs-menu-link" href="#affiliate">
						<i class="fa-solid fa-network-wired"></i> Affilate
					</a>
				</li>
			</ul>
		</div>
	</div>
</header>
