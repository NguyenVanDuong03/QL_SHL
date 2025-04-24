<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="container mb-4">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end gap-3">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="btn btn-outline-secondary"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="btn btn-outline-secondary"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="btn btn-outline-secondary">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <h1>
            Base
        </h1>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
