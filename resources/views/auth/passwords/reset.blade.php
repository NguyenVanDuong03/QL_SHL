@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/password-reset.css') }}">
@endpush

@section('content')
    <div class="container mt-5">
        <div class="password-reset-header">
            <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" class="logo">
            <h1 class="welcome-text">Đặt lại mật khẩu</h1>
            <p class="sub-text">Nhập email và mật khẩu mới của bạn để tiếp tục.</p>
        </div>

        <div class="password-reset-container">
            <div class="password-reset-card">
                <h2 class="password-reset-title">Đặt lại mật khẩu</h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Địa chỉ Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                               autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới</label>
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
                    <button type="submit" class="btn btn-primary btn-password-reset">Đặt lại mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
@endsection
