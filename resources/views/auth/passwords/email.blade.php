@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/password-email.css') }}">
@endpush

@section('content')
<div class="container mt-5">
    <div class="reset-email-header">
        <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" class="logo">
        <h1 class="welcome-text">Quên mật khẩu?</h1>
        <p class="sub-text">Nhập email để nhận liên kết đặt lại mật khẩu.</p>
    </div>

    <div class="reset-email-container">
        <div class="reset-email-card">
            <h2 class="reset-email-title">Đặt lại mật khẩu</h2>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-reset-email mb-3">Gửi liên kết đặt lại mật khẩu</button>
            </form>

            <div class="d-flex justify-content-center mt-3">
                <a href="{{ route('login') }}">Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</div>

@endsection
