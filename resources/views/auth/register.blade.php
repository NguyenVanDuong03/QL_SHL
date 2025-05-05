@extends('layouts.app')

@section('title', 'Đăng ký')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="container mt-5">
    <div class="register-header">
        <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" class="logo">
        <h1 class="welcome-text">Chào mừng!</h1>
        <p class="sub-text">Chào mừng bạn đến với Hệ thống quản lý<br>Sinh hoạt lớp trường Đại học Thủy lợi.</p>
    </div>

    <div class="register-container">
        <div class="register-card">
            <h2 class="register-title">Đăng ký</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" placeholder="Nhập họ và tên" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="Nhập email" value="{{ old('email') }}" required>
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

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password-confirm"
                        name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                </div>

                <button type="submit" class="btn btn-primary btn-register mb-3">Đăng ký</button>
            </form>

            <div class="d-flex justify-content-center">
                <div class="register-link">
                    <a href="{{ route('login') }}">Đã có tài khoản? Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
