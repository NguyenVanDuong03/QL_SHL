<div id="sidebarWrapper" class="col-md-3 col-lg-2 sidebar-wrapper fixed-top">

    <!-- Navigation Menu -->
    <div class="px-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'faculty-office.index' ? 'active' : '' }}"
                   href="{{ route('faculty-office.index') }}">
                    <i class="fas fa-home"></i>
                    Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'faculty-office.conduct-score.index' ? 'active' : '' }}" href="{{ route('faculty-office.conduct-score.index') }}">
                    <i class="fas fa-book"></i>
                    Điểm rèn luyện
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-line"></i>
                    Thống kê
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <i class="fas fa-chart-line"></i>
                    Thông tin cá nhân
                </a>
            </li>
        </ul>
    </div>

    <!-- Authentication Links -->
    <div class="mt-auto px-3 pb-3" style="margin-top: auto;">
        <ul class="nav flex-column">
            <li class="nav-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="nav-link w-100" href="#">
                        <i class="fas fa-sign-out-alt"></i>
                        {{ __('Đăng xuất') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
