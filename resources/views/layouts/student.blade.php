@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/student.css') }}">
@endpush

@section('content')
    <x-navbar.student />

    <main class="py-4">
        @yield('main')
    </main>

    <x-footer.footer />
@endsection
