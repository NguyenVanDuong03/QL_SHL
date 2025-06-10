@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/studentAffairsDepartment.css') }}">
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-0">
            <x-navbar.student-affairs-department />

            <main class="col-md-9 col-lg-10 ms-sm-auto px-0 main-content">
                <!-- Toggle Button and Header -->
                <header class="main-header fixed-top">
                    <button class="btn btn-sm" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="h5 mb-0">
                        @yield('breadcrumb')
                    </h1>
                    <div class="text-white">{{ Auth::user()->name }}</div>
                </header>

                <main>
                    @yield('main')
                </main>

                <x-footer.footer />
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#toggleSidebar').on('click', function() {
                $('#sidebarWrapper').toggleClass('show');
            });

            $('body').on('click', function(event) {
                if ($(window).width() < 768) {
                    if (!$(event.target).closest('#sidebarWrapper, #toggleSidebar').length) {
                        $('#sidebarWrapper').removeClass('show');
                    }
                }
            });
        });
    </script>
@endpush
