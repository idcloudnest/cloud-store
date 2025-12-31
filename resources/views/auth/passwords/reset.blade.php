@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 85vh;">

    <div class="w-100 animate-up" style="max-width: 450px;">

        {{-- HEADER --}}
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3 shadow-glow" style="width: 80px; height: 80px;">
                <i class="fas fa-key fa-2x"></i>
            </div>
            <h3 class="fw-bold text-body">Buat Password Baru</h3>
            <p class="text-muted small">Silakan masukkan password baru untuk akun Anda.</p>
        </div>

        {{-- CARD FORM --}}
        <div class="card border-0 glass-card overflow-hidden">
            <div class="card-body p-4 p-md-5">

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    {{-- TOKEN (WAJIB ADA - HIDDEN) --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- EMAIL (WAJIB ADA - READONLY) --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">EMAIL ANDA</label>
                        <div class="input-group custom-input-group rounded-3 overflow-hidden">
                            <span class="input-group-text border-0 bg-transparent text-muted ps-3">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                   class="form-control border-0 bg-transparent shadow-none text-body"
                                   value="{{ $email ?? old('email') }}"
                                   required readonly>
                        </div>
                    </div>

                    {{-- PASSWORD BARU --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PASSWORD BARU</label>
                        <div class="input-group custom-input-group rounded-3 overflow-hidden">
                            <input type="password" name="password" id="newPass"
                                   class="form-control border-0 bg-transparent shadow-none text-body" required autofocus>
                            <button class="btn border-0 text-muted pe-3" type="button" onclick="togglePassword('newPass', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">ULANGI PASSWORD</label>
                        <div class="input-group custom-input-group rounded-3 overflow-hidden">
                            <input type="password" name="password_confirmation" id="confPass"
                                   class="form-control border-0 bg-transparent shadow-none text-body" required>
                            <button class="btn border-0 text-muted pe-3" type="button" onclick="togglePassword('confPass', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-primary rounded-3 btn-anim">
                        <i class="fas fa-save me-2"></i> Simpan Password
                    </button>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    /* Gunakan Style yang sama persis dengan halaman Forgot Password sebelumnya */
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
    }
    :root { --input-bg: #f8f9fa; --input-border: #e9ecef; }
    [data-bs-theme="dark"] { --input-bg: rgba(0, 0, 0, 0.3); --input-border: rgba(255, 255, 255, 0.1); }
    .custom-input-group { background-color: var(--input-bg); border: 1px solid var(--input-border); transition: all 0.3s ease; }
    .custom-input-group:focus-within { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1); }
    .animate-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@push('scripts')
<script>
    // Script Toggle Password (sama seperti register)
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text"; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye');
        } else {
            input.type = "password"; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endpush
