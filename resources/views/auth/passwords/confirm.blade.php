@extends('layouts.app')

@section('title', 'Xác nhận mật khẩu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/password-comfirm.css') }}">
@endpush

@section('content')
    <div class="container mt-5">
        <div class="password-comfirm-header">
            <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" class="logo">
            <h1 class="welcome-text">Xác nhận mật khẩu</h1>
            <p class="sub-text">Vui lòng xác nhận mật khẩu trước khi tiếp tục.</p>
        </div>

        <div class="password-comfirm-container">
            <div class="password-comfirm-card">
                <h2 class="password-comfirm-title">Xác nhận mật khẩu</h2>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

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

                    <button type="submit" class="btn btn-primary btn-password-comfirm">Xác nhận</button>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
