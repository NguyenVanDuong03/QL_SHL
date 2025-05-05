@extends('layouts.app')

@section('title', 'Xác thực Email')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endpush

@section('content')
    <div class="container mt-5">
        <div class="verify-header">
            <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" class="logo">
            <h1 class="welcome-text">Xác minh địa chỉ email</h1>
            <p class="sub-text">Chúng tôi đã gửi liên kết xác minh đến email của bạn.</p>
        </div>

        <div class="verify-container">
            <div class="verify-card">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        Liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.
                    </div>
                @endif

                <p>Trước khi tiếp tục, vui lòng kiểm tra email của bạn để tìm liên kết xác minh.</p>
                <p>Nếu bạn không nhận được email, bạn có thể yêu cầu lại bên dưới:</p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-verify">
                        Gửi lại liên kết xác minh
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
