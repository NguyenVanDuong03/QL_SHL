@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/student.css') }}">
    <style>
        /* Back to Top Button Styles */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2b82df, #0f4976);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
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
                bottom: 90px;
                right: 20px;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }
    </style>
@endpush

@section('content')
    <x-navbar.student />

    <main class="pt-5 pb-4 mt-5">
        @yield('main')
    </main>

    <x-footer.footer />

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" title="Về đầu trang">
        <i class="fas fa-chevron-up"></i>
    </button>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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

            // Hiện/ẩn button khi cuộn
            $(window).scroll(function() {
                const scrollTop = $(this).scrollTop();

                if (scrollTop > scrollThreshold) {
                    backToTopBtn.addClass('show');
                } else {
                    backToTopBtn.removeClass('show');
                }
            });

            // Xử lý click button với spam prevention
            backToTopBtn.click(function(e) {
                e.preventDefault();

                const currentTime = Date.now();

                // Kiểm tra nếu đang scroll
                if (isScrolling) {
                    showSpamWarning('Đang cuộn, vui lòng đợi...');
                    return;
                }

                // Kiểm tra cooldown
                if (currentTime - lastClickTime < clickCooldown) {
                    showSpamWarning('Vui lòng đợi ' + Math.ceil((clickCooldown - (currentTime - lastClickTime)) / 1000) + ' giây');
                    $(this).addClass('cooldown');
                    setTimeout(() => {
                        $(this).removeClass('cooldown');
                    }, 500);
                    return;
                }

                // Kiểm tra số lần click trong 1 phút
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

                // Thêm thời gian click hiện tại
                clickTimes.push(currentTime);
                lastClickTime = currentTime;

                // Bắt đầu scroll
                isScrolling = true;
                $(this).addClass('scrolling bounce');

                // Thay đổi icon thành spinner
                const originalIcon = $(this).find('i');
                originalIcon.removeClass('fa-chevron-up').addClass('fa-spinner');

                // Cuộn lên đầu trang
                $('html, body').animate({
                    scrollTop: 0
                }, {
                    duration: 800,
                    easing: 'swing',
                    complete: function() {
                        // Reset trạng thái sau khi hoàn thành
                        isScrolling = false;
                        backToTopBtn.removeClass('scrolling bounce');
                        originalIcon.removeClass('fa-spinner').addClass('fa-chevron-up');
                    }
                });
            });

            // Hàm hiển thị cảnh báo spam
            function showSpamWarning(message) {
                // Xóa thông báo cũ nếu có
                $('.spam-warning').remove();

                const warning = $(`
                    <div class="spam-warning position-fixed bg-warning text-dark px-3 py-2 rounded shadow-sm"
                         style="bottom: 90px; right: 30px; z-index: 1001; font-size: 0.875rem; max-width: 200px;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        ${message}
                    </div>
                `);

                $('body').append(warning);

                // Tự động xóa sau 3 giây
                setTimeout(() => {
                    warning.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            // Xử lý khi người dùng rời khỏi trang (reset spam counter)
            $(window).on('beforeunload', function() {
                clickTimes = [];
                isScrolling = false;
            });

            // Keyboard support với spam prevention
            $(document).keydown(function(e) {
                if (e.key === 'Home' && e.ctrlKey) {
                    e.preventDefault();
                    if (!isScrolling) {
                        backToTopBtn.click();
                    }
                }
            });

            // Reset disabled state khi scroll xuống lại
            $(window).scroll(function() {
                if ($(this).scrollTop() > scrollThreshold && backToTopBtn.hasClass('disabled')) {
                    // Nếu user scroll xuống lại, có thể reset một phần restriction
                    const currentTime = Date.now();
                    clickTimes = clickTimes.filter(time => currentTime - time < clickTimeWindow / 2);

                    if (clickTimes.length < maxClicksPerMinute / 2) {
                        backToTopBtn.removeClass('disabled');
                    }
                }
            });
        });
    </script>
@endpush
