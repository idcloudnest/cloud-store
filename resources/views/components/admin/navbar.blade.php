<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
	<div class="container-fluid position-relative d-flex flex-nowrap align-items-center">
		<button class="btn btn-light btn-anim flex-shrink-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>

		<div class="mobile-logo-center">
			<div class="bg-primary text-white rounded p-1 me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
				<i class="fa-solid fa-cloud-bolt"></i>
			</div>
			<div class="text-start" style="line-height: 1;">
				<div style="font-size: 1rem; font-weight: 800; letter-spacing: -0.5px; color: #333;">Cloud<span class="text-primary">Nest</span></div>
			</div>
		</div>

		<div class="ms-auto d-flex align-items-center">
			<a class="nav-link btn-anim rounded-circle bg-light me-2 d-none d-sm-flex align-items-center justify-content-center" style="width: 40px; height:40px;" href="#"><i class="far fa-bell"></i></a>
			<div class="dropdown">
				<a class="nav-link dropdown-toggle fw-bold d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" class="rounded-circle border border-2 border-primary" style="padding: 2px;" width="35" height="35">
					{{-- <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle">
						<span class="visually-hidden">Online</span>
					</span> --}}
                {{-- <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" class="rounded-circle border border-2 border-primary p-1" width="80" height="80"> --}}

					<span class="d-none d-md-inline ms-2">{{ Auth()->user()->name ?? 'Super Admin' }}</span>
				</a>
				<ul class="dropdown-menu dropdown-menu-end shadow border-0" style="position: absolute;">
					<li><a class="dropdown-item" href="#">Profile</a></li>
					<li><a class="dropdown-item" href="#">Settings</a></li>
					<li><hr class="dropdown-divider"></li>
					<li><a class="dropdown-item text-danger" href="{{ route('auth.logout') }}">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
</nav>
