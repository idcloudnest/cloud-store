@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 85vh;">

	{{-- BUTTON KEMBALI (Floating) --}}
	<div class="position-absolute top-0 start-0 m-4 animate-fade-down">
		<a href="{{ route('login') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm fw-bold">
			<i class="fas fa-arrow-left me-1"></i> Kembali
		</a>
	</div>

	<div class="w-100 animate-up" style="max-width: 450px;">

		{{-- HEADER ICON --}}
		<div class="text-center mb-4">
			<div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3 shadow-glow" style="width: 80px; height: 80px;">
				<i class="fas fa-lock-open fa-2x"></i>
			</div>
			<h3 class="fw-bold text-body">Lupa Password?</h3>
			<p class="text-muted small px-4">Jangan khawatir. Masukkan email yang terdaftar, kami akan mengirimkan link untuk mereset password Anda.</p>
		</div>

		{{-- CARD FORM --}}
		<div class="card border-0 glass-card overflow-hidden">
			<div class="card-body p-4 p-md-5">

				{{-- ALERT SUKSES (Jika Link Terkirim) --}}
				@if (session('status'))
					<div class="alert alert-success border-0 bg-success bg-opacity-10 text-success d-flex align-items-center mb-4 rounded-3">
						<i class="fas fa-check-circle me-2"></i> {{ session('status') }}
					</div>
				@endif

				<form method="POST" action="{{ route('password.email') }}">
					@csrf

					<div class="mb-4">
						<label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>

						{{-- Seamless Input Group (Sama seperti Login/Register) --}}
						<div class="input-group custom-input-group rounded-3 overflow-hidden">
							<span class="input-group-text border-0 bg-transparent text-muted ps-3">
								<i class="fas fa-envelope"></i>
							</span>
							<input type="email" name="email"
								   class="form-control border-0 bg-transparent shadow-none text-body @error('email') is-invalid @enderror"
								   placeholder="email@anda.com"
								   value="{{ old('email') }}"
								   required autofocus>
						</div>

						{{-- Error Message --}}
						@error('email')
							<div class="text-danger small mt-1">
								<i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
							</div>
						@enderror
					</div>

					<button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-primary rounded-3 btn-anim">
						<i class="fas fa-paper-plane me-2"></i> Kirim Link Reset
					</button>

				</form>
			</div>

			{{-- FOOTER CARD --}}
			<div class="card-footer bg-transparent border-top border-secondary border-opacity-10 py-3 text-center">
				<small class="text-muted">Ingat password Anda? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk Disini</a></small>
			</div>
		</div>

	</div>
</div>
@endsection

@push('styles')
<style>
	/* Styling Glass Card & Input (Copy dari sebelumnya agar konsisten) */
	.glass-card {
		background: var(--card-bg);
		border: 1px solid var(--card-border);
		box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
	}

	/* Variabel Warna Adaptif Input */
	:root {
		--input-bg: #f8f9fa;
		--input-border: #e9ecef;
	}
	[data-bs-theme="dark"] {
		--input-bg: rgba(0, 0, 0, 0.3);
		--input-border: rgba(255, 255, 255, 0.1);
	}

	/* Custom Input Group Style */
	.custom-input-group {
		background-color: var(--input-bg);
		border: 1px solid var(--input-border);
		transition: all 0.3s ease;
	}
	.custom-input-group:focus-within {
		border-color: var(--primary-color);
		box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1);
	}

	/* Animations */
	.animate-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
	.animate-fade-down { animation: fadeDown 0.8s ease-out forwards; }

	@keyframes fadeUp {
		from { opacity: 0; transform: translateY(30px); }
		to { opacity: 1; transform: translateY(0); }
	}
	@keyframes fadeDown {
		from { opacity: 0; transform: translateY(-30px); }
		to { opacity: 1; transform: translateY(0); }
	}

	/* Tombol Animasi Tekan */
	.btn-anim:active {
		transform: scale(0.98);
	}
</style>
@endpush
