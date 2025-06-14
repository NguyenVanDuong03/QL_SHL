@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/studentAffairsDepartment.css') }}">
@endpush

@push('styles')
    <style>
        /* Back to Top Button Styles */
        .back-to-top {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #2b82df, #0f4976);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none; /* Hidden by default */
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(43, 130, 223, 0.3);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover:not(.disabled) {
            background: linear-gradient(135deg, #0f4976, #2b82df);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(43, 130, 223, 0.4);
        }

        .back-to-top:active:not(.disabled) {
            transform: translateY(-1px);
        }

        .back-to-top i {
            transition: transform 0.3s ease;
        }

        .back-to-top:hover:not(.disabled) i {
            transform: translateY(-2px);
        }

        /* Disabled state for spam prevention */
        .back-to-top.disabled {
            cursor: not-allowed;
            opacity: 0.6;
            pointer-events: none;
        }

        .back-to-top.scrolling {
            background: linear-gradient(135deg, #6c757d, #495057);
            cursor: wait;
        }

        .back-to-top.scrolling i {
            animation: spin 1s linear infinite;
        }

        /* Loading spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Bounce animation */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-5px);
            }
            60% {
                transform: translateY(-3px);
            }
        }

        .back-to-top.bounce {
            animation: bounce 0.6s ease;
        }

        /* Cooldown indicator */
        .back-to-top.cooldown::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid #ffc107;
            border-radius: 50%;
            animation: cooldownPulse 0.5s ease-in-out;
        }

        @keyframes cooldownPulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.2); opacity: 0; }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .back-to-top {
                display: flex; /* Show button only on mobile */
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-0">
            <x-navbar.student-affairs-department/>

            <main class="col-md-9 col-lg-10 ms-sm-auto px-0 main-content">
                <!-- Toggle Button and Header -->
                <header class="main-header fixed-top">
                    <button class="btn btn-sm" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center justify-content-between px-3">
                        <div class="d-none d-md-block">
                            <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" height="40">
                        </div>
                        <h1 class="text-white mb-0">
                            @yield('breadcrumb')
                        </h1>
                    </div>
                    <div class="text-white">{{ Auth::user()->name }}</div>
                </header>

                <main>
                    @yield('main')
                </main>

                <x-footer.footer/>

                <button class="back-to-top" id="backToTop" title="Về đầu trang">
                    <i class="fas fa-chevron-up"></i>
                </button>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#toggleSidebar').on('click', function () {
                $('#sidebarWrapper').toggleClass('show');
            });

            $('body').on('click', function (event) {
                if ($(window).width() < 768) {
                    if (!$(event.target).closest('#sidebarWrapper, #toggleSidebar').length) {
                        $('#sidebarWrapper').removeClass('show');
                    }
                }
            });

            const backToTopBtn = $('#backToTop');
            const scrollThreshold = 300;

            // Spam prevention variables
            let isScrolling = false;
            let lastClickTime = 0;
            let clickCount = 0;
            const clickCooldown = 1000; // 1 giây cooldown
            const maxClicksPerMinute = 10; // Tối đa 10 clicks/phút
            const clickTimeWindow = 60000; // 1 phút
            let clickTimes = [];

            // Check if screen is mobile (md and below)
            function isMobile() {
                return window.innerWidth <= 768;
            }

            // Show/hide button when scrolling on mobile
            $(window).scroll(function() {
                if (!isMobile()) return; // Skip if not mobile

                const scrollTop = $(this).scrollTop();

                if (scrollTop > scrollThreshold) {
                    backToTopBtn.addClass('show');
                } else {
                    backToTopBtn.removeClass('show');
                }
            });

            // Handle click with spam prevention
            backToTopBtn.click(function(e) {
                e.preventDefault();

                const currentTime = Date.now();

                // Check if scrolling
                if (isScrolling) {
                    showSpamWarning('Đang cuộn, vui lòng đợi...');
                    return;
                }

                // Check cooldown
                if (currentTime - lastClickTime < clickCooldown) {
                    showSpamWarning('Vui lòng đợi ' + Math.ceil((clickCooldown - (currentTime - lastClickTime)) / 1000) + ' giây');
                    $(this).addClass('cooldown');
                    setTimeout(() => {
                        $(this).removeClass('cooldown');
                    }, 500);
                    return;
                }

                // Check clicks per minute
                clickTimes = clickTimes.filter(time => currentTime - time < clickTimeWindow);
                if (clickTimes.length >= maxClicksPerMinute) {
                    showSpamWarning('Bạn đã click quá nhiều lần. Vui lòng đợi 1 phút.');
                    $(this).addClass('disabled');
                    setTimeout(() => {
                        $(this).removeClass('disabled');
                        clickTimes = [];
                    }, clickTimeWindow);
                    return;
                }

                // Record click time
                clickTimes.push(currentTime);
                lastClickTime = currentTime;

                // Start scrolling
                isScrolling = true;
                $(this).addClass('scrolling bounce');

                // Change icon to spinner
                const originalIcon = $(this).find('i');
                originalIcon.removeClass('fa-chevron-up').addClass('fa-spinner');

                // Scroll to top
                $('html, body').animate({
                    scrollTop: 0
                }, {
                    duration: 800,
                    easing: 'swing',
                    complete: function() {
                        // Reset state after completion
                        isScrolling = false;
                        backToTopBtn.removeClass('scrolling bounce');
                        originalIcon.removeClass('fa-spinner').addClass('fa-chevron-up');
                    }
                });
            });

            // Show spam warning
            function showSpamWarning(message) {
                // Remove old warnings
                $('.spam-warning').remove();

                const warning = $(`
                    <div class="spam-warning position-fixed bg-warning text-dark px-3 py-2 rounded shadow-sm"
                         style="bottom: 150px; right: 20px; z-index: 1001; font-size: 0.875rem; max-width: 200px;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        ${message}
                    </div>
                `);

                $('body').append(warning);

                // Auto-remove after 3 seconds
                setTimeout(() => {
                    warning.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            // Reset spam counter on page leave
            $(window).on('beforeunload', function() {
                clickTimes = [];
                isScrolling = false;
            });

            // Keyboard support with spam prevention
            $(document).keydown(function(e) {
                if (e.key === 'Home' && e.ctrlKey && isMobile()) {
                    e.preventDefault();
                    if (!isScrolling) {
                        backToTopBtn.click();
                    }
                }
            });

            // Reset disabled state when scrolling down again
            $(window).scroll(function() {
                if (!isMobile()) return; // Skip if not mobile

                if ($(this).scrollTop() > scrollThreshold && backToTopBtn.hasClass('disabled')) {
                    const currentTime = Date.now();
                    clickTimes = clickTimes.filter(time => currentTime - time < clickTimeWindow / 2);

                    if (clickTimes.length < maxClicksPerMinute / 2) {
                        backToTopBtn.removeClass('disabled');
                    }
                }
            });

            // Initial check for mobile on page load
            if (isMobile() && $(window).scrollTop() > scrollThreshold) {
                backToTopBtn.addClass('show');
            }
        });
    </script>
@endpush
