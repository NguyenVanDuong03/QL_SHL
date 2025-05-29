<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/brands.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/solid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/svg-with-js.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/v4-shims.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

    <!-- CSS Loading Overlay -->
    <style>
        #global-loading .loading-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 9998;
        }

        #global-loading .loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            color: #333;
        }

        footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer .list-inline {
            padding: 0;
            list-style: none;
        }
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Extra CSS -->
    @stack('styles')
</head>

<body>

    <!-- Loading Overlay -->
    <div id="global-loading" style="display: none;">
        <div class="loading-backdrop"></div>
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
        </div>
    </div>

    <div id="app">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <!-- JS Loading Logic -->
    <script>
        function showGlobalLoading() {
            $('#global-loading').show();
            NProgress.start();
        }

        function hideGlobalLoading() {
            $('#global-loading').hide();
            NProgress.done();
        }

        $(document).on('click', 'a', function(e) {
            const href = $(this).attr('href');
            if (href && !href.startsWith('#') && !$(this).attr('target') && !href.startsWith('javascript:')) {
                showGlobalLoading();
            }
        });

        $(document).on('submit', 'form', function() {
            showGlobalLoading();
        });

        $(document).ajaxStart(function() {
            showGlobalLoading();
        });

        $(document).ajaxStop(function() {
            hideGlobalLoading();
        });

        $(window).on('load', function() {
            hideGlobalLoading();
        });
    </script>

    <script>
        function checkValidate(id, regex, message) {
            const input = $(id);
            const errorBox = input.next('.text-danger-error');

            if (input.val().trim() === '') {
                errorBox.text('Yêu cầu nhập đầy đủ thông tin!').show();
                input.addClass('is-invalid');
                return false;
            } else if (regex != '0' && !regex.test(input.val())) {
                errorBox.text(message).show();
                input.addClass('is-invalid');
                return false;
            }

            errorBox.text('').hide();
            input.removeClass('is-invalid');
            return true;
        }
    </script>

    <!-- Extra JS -->
    @stack('scripts')
</body>

</html>
