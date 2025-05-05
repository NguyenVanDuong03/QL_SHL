@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/classStaff.css') }}">
@endpush

@section('content')
    <x-navbar.class-staff />

    <main class="py-4">
        @yield('main')
    </main>

    <x-footer.footer />
@endsection
