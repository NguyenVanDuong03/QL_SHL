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
                    <div class="input-group">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" placeholder="Nhập mật khẩu" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary password-toggle" type="button"
                                data-target="password">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Xác nhận mật khẩu</label>
                    <div class="input-group">
                        <input id="password-confirm" type="password" class="form-control" placeholder="Nhập mật khẩu"
                               name="password_confirmation"
                               required autocomplete="new-password">
                        <button class="btn btn-outline-secondary password-toggle" type="button"
                                data-target="password-confirm">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
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
