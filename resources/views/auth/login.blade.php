@extends('layouts.app')

@section('title', 'Đăng nhập')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container mt-5">
        <div class="login-header">
            <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" class="logo">
            <h1 class="welcome-text">Chào mừng!</h1>
            <p class="sub-text">Chào mừng bạn đến với Hệ thống quản lý<br>Sinh hoạt lớp trường Đại học Thủy lợi.</p>
        </div>

        <div class="login-container">
            <div class="login-card">
                <h2 class="login-title">Đăng nhập</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" placeholder="Nhập email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Nhập mật khẩu" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Ghi nhớ tôi</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login mb-3">Đăng nhập</button>
                </form>

                <div class="d-flex align-items-center justify-content-between">
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    </div>
                    <div class="register-link">
                        <a href="{{ route('register') }}">Đăng ký tài khoản</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
