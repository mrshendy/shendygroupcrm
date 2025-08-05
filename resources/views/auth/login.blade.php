@extends('layouts.master2')

@section('title')
تسجيل دخول - نظام SHENDY GROUP CRM
@stop

@section('content')
<div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
    <div class="container" style="max-width: 1500px;">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                    <div class="row g-0">
                        <!-- الجانب الأيسر -->
                        <div class="col-md-6 d-none d-md-block" style="background: url('{{ asset('assets/images/file.png') }}') center/cover no-repeat;">
                            <div class="h-100 d-flex flex-column justify-content-between p-4" style="background: rgba(114, 169, 212, 0.93);">
                                <div style="padding-top: 153px !important;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-bottom: 20px;">
                                        <img 
                                            src="{{ asset('assets/images/logoshendy.png') }}" 
                                            alt="logo shendy"
                                            style="
                                                height: 80px;
                                                background-color: #fff;
                                                border: 1px solid #ddd;
                                                border-radius: 12px;
                                                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                                                padding: 5px;
                                                width: 200px;
                                            "
                                            class="mb-4"
                                        >
                                        <img 
                                            src="{{ asset('assets/images/logo.png') }}" 
                                            alt="logo"
                                            style="
                                                height: 80px;
                                                background-color: #fff;
                                                border: 1px solid #ddd;
                                                border-radius: 12px;
                                                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                                                padding: 5px;
                                            "
                                            class="mb-4"
                                        >
                                    </div>
                                    
                                    <h4 class="text-white fw-bold text-center">شركة ShendyGroup</h4>
                                    <p class="text-white-50 text-center">نظام متكامل لإدارة مشاريع، عملاء، حسابات، وفريق عمل شركتك</p>
                                </div>
                                <div class="text-white-50 small text-center">
                                    جميع الحقوق محفوظة &copy; {{ date('Y') }} - SHENDY GROUP CRM
                                </div>
                            </div>
                        </div>
                        <!-- الجانب الأيمن -->
                        <div class="col-md-6 bg-white">
                            <div class="p-4 p-md-5">
                                <h4 class="fw-bold text-primary mb-3">مرحبًا بك في SHENDY GROUP CRM 👋</h4>
                                <p class="text-muted mb-4">سجّل دخولك للمتابعة إلى نظام إدارة شركتك باحتراف</p>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">اسم المستخدم أو البريد الإلكتروني</label>
                                        <input id="email" name="email" type="text" placeholder="أدخل البريد الإلكتروني" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <span class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">كلمة المرور</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="********" required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggle-password" style="margin-right: -1px !important;border-top-right-radius: 0 !important;border-bottom-right-radius: 0 !important;">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">تذكرني</label>
                                    </div>

                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-login-box-line me-1"></i> تسجيل الدخول
                                        </button>
                                    </div>
                                </form>
                                <hr class="my-4">
                                <p class="mb-0 text-center text-muted small">
                                    جميع الحقوق محفوظة © {{ date('Y') }} - SHENDY GROUP
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>

<script>
document.getElementById('toggle-password').addEventListener('click', function () {
    let passwordInput = document.getElementById('password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.innerHTML = '<i class=\"ri-eye-off-line\"></i>';
    } else {
        passwordInput.type = 'password';
        this.innerHTML = '<i class=\"ri-eye-line\"></i>';
    }
});
</script>
@endsection
