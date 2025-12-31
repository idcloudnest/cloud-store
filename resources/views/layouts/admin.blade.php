<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'Admin IDCloudStore')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style>
		/* --- CORE VARIABLES --- */
		:root {
			--sidebar-width: 20.5rem;
			--sidebar-bg: #1e1e2d;
			--sidebar-sub-bg: #151521;
			--sidebar-sub-sub-bg: #0f0f16;
			--primary-color: #667eea;
			--navbar-mobile-height: 70px;
			--navbar-mobile-margin: 15px;
		}

		body { overflow-x: hidden; background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }

		/* --- UTILS --- */
		.badge-soft-success { background-color: #d1fae5; color: #065f46; }
		.badge-soft-warning { background-color: #fef3c7; color: #92400e; }

		/* --- LAYOUT UTAMA --- */
		#wrapper { display: flex; width: 100%; transition: all 0.3s; }
		#page-content-wrapper { width: 100%; transition: margin 0.3s ease-out; min-height: 100vh; }

		/* --- SIDEBAR BASE --- */
		#sidebar-wrapper {
			background-color: var(--sidebar-bg);
			width: var(--sidebar-width);
			position: fixed;
			overflow-y: auto;
			transition: all 0.3s ease-out;
			z-index: 1040;
		}

		/* LOGIKA TAMPILAN MOBILE */
		@media (max-width: 767.98px) {
			.navbar {
				margin: var(--navbar-mobile-margin);
				border-radius: 16px;
				box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
				z-index: 1050; position: fixed; top: 0; left: 0; right: 0; width: auto;
			}
			#sidebar-wrapper {
				top: calc(var(--navbar-mobile-height) + var(--navbar-mobile-margin) + 10px);
				height: calc(100vh - (var(--navbar-mobile-height) + var(--navbar-mobile-margin) + 25px));
				left: var(--navbar-mobile-margin);
				border-radius: 16px;
				transform: translateX(calc(-100% - 20px));
				margin-left: 0; padding-top: 10px;
				box-shadow: 0 10px 30px rgba(0,0,0,0.3);
			}
			body.sb-open #sidebar-wrapper { transform: translateX(0); }
			#wrapper { margin-top: 100px; }
			.sidebar-heading { display: none !important; }
			.mobile-logo-center { position: absolute; left: 50%; transform: translateX(-50%); display: flex; align-items: center; white-space: nowrap; }
		}

		/* LOGIKA TAMPILAN DESKTOP */
		@media (min-width: 768px) {
			.navbar { margin: 0; border-radius: 0; z-index: 1030; position: sticky; top: 0;}
			#sidebar-wrapper { top: 0; left: 0; height: 100vh; margin-left: 0; border-radius: 0; transform: none; box-shadow: none; }
			#page-content-wrapper { margin-left: var(--sidebar-width); }
			body.sb-hidden #sidebar-wrapper { margin-left: calc(var(--sidebar-width) * -1); }
			body.sb-hidden #page-content-wrapper { margin-left: 0; }
			.mobile-logo-center { display: none; }
			#wrapper { margin-top: 0; }
		}

		/* --- STYLING MENU --- */
		.sidebar-heading { padding: 1.5rem; color: white; border-bottom: 1px solid rgba(255,255,255,0.05); }
		.list-group-item { background: transparent; color: #aeb7c2; border: none; padding: 0.9rem 1.5rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s; border-left: 4px solid transparent; text-decoration: none;}
		.list-group-item:hover { color: #fff; background: rgba(255,255,255,0.05); }

		/* STATE ACTIVE (Untuk Menu Anak) */
		.list-group-item.active { color: #fff; background: #2b2b40; border-left: 4px solid #667eea; }

		/* STATE ACTIVE PARENT (Untuk Menu Induk) */
		.list-group-item.active-parent {
			color: #fff !important;
			background-color: rgba(255, 255, 255, 0.05);
			border-left: 4px solid #667eea;
		}
		.list-group-item.active-parent .dropdown-icon { color: #fff; opacity: 1; }

		.menu-text { display: flex; align-items: center; font-size: 0.95rem;}
		.sidebar-submenu { background: var(--sidebar-sub-bg); font-size: 0.9rem; }
		.sidebar-submenu .list-group-item { padding-left: 3.5rem; }
		.sidebar-sub-submenu { background: var(--sidebar-sub-sub-bg); }
		.sidebar-sub-submenu .list-group-item { padding-left: 4.8rem; border-left: none !important;}
		.sidebar-sub-submenu .list-group-item::before { content: "•"; margin-right: 8px; color: #667eea; }

		.dropdown-icon { transition: transform 0.3s ease; font-size: 0.75rem; opacity: 0.7;}
		/* Rotasi panah jika aria-expanded=true */
		a[aria-expanded="true"] .dropdown-icon { transform: rotate(180deg); opacity: 1;}

		/* --- ANIMASI & TOMBOL --- */
		.btn-anim { transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
		.btn-anim:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
		.btn-anim:active { transform: scale(0.95); }

		.action-btn {
			width: 32px; height: 32px; padding: 0; line-height: 32px;
			text-align: center; border-radius: 8px; border: none; background: #f0f2f5;
			transition: all 0.2s ease;
		}
		.action-btn:hover { background: #e2e6ea; transform: scale(1.15); }


		/* --- STYLING LAINNYA TETAP SAMA --- */
		/* .sidebar-heading { padding: 1.5rem; color: white; border-bottom: 1px solid rgba(255,255,255,0.05); }
		.list-group-item { background: transparent; color: #aeb7c2; border: none; padding: 0.9rem 1.5rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s; border-left: 4px solid transparent; }
		.list-group-item:hover { color: #fff; background: rgba(255,255,255,0.05); }
		.list-group-item.active { color: #fff; background: #2b2b40; border-left: 4px solid #667eea; }
		.menu-text { display: flex; align-items: center; font-size: 0.95rem;}
		.sidebar-submenu { background: var(--sidebar-sub-bg); font-size: 0.9rem; }
		.sidebar-submenu .list-group-item { padding-left: 3.5rem; }
		.sidebar-sub-submenu { background: var(--sidebar-sub-sub-bg); }
		.sidebar-sub-submenu .list-group-item { padding-left: 4.8rem; font-size: 0.85rem; border-left: none !important; }
		.sidebar-sub-submenu .list-group-item::before { content: "•"; margin-right: 8px; color: #667eea; }
		.dropdown-icon { transition: transform 0.3s ease; font-size: 0.75rem; opacity: 0.7;}
		a[aria-expanded="true"] .dropdown-icon { transform: rotate(180deg); opacity: 1;}
		.badge-soft-success { background-color: #d1fae5; color: #065f46; }
		.badge-soft-warning { background-color: #fef3c7; color: #92400e; } */
	</style>

	@stack('styles')
</head>
<body>

	<script>
		(function() {
			if (window.innerWidth >= 768) {
				const isHidden = localStorage.getItem('idcloudstore_sidebar_hidden');
				if (isHidden === 'true') document.body.classList.add('sb-hidden');
			}
		})();
	</script>

	<div id="wrapper">
		@include('components.admin.sidebar')

		<div id="page-content-wrapper">

			@include('components.admin.navbar')

			<div class="container-fluid py-4 px-4" id="main-content">
				@yield('content')
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		// $(document).ready(function () {
		// 	$('#transaksiTable').DataTable({
		// 		language: {
		// 			url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" // Bahasa Indonesia
		// 		},
		// 		order: [[ 4, "desc" ]], // Urutkan berdasarkan kolom Tanggal (index 4) descending
		// 		pageLength: 5, // Tampilkan 5 baris per halaman
		// 		lengthMenu: [5, 10, 25, 50]
		// 	});
		// });

		$(document).ready(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			})

			// Logic Sidebar Toggle (Hanya untuk Buka/Tutup Sidebar)
			const sidebarToggle = document.getElementById('sidebarToggle');
			const storageKey = 'idcloudstore_sidebar_hidden';

			if (sidebarToggle) {
				sidebarToggle.addEventListener('click', function(event) {
					event.preventDefault();
					if (window.innerWidth >= 768) {
						document.body.classList.toggle('sb-hidden');
						localStorage.setItem(storageKey, document.body.classList.contains('sb-hidden'));
					} else {
						document.body.classList.toggle('sb-open');
					}
				});
			}
		});
	</script>

	@stack('scripts')
</body>
</html>
