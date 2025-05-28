<div id="sidebarWrapper" class="col-md-3 col-lg-2 sidebar-wrapper">
    <!-- Logo -->
    <div class="sidebar-logo">
        <div>
            <img src="{{ asset('images/LogoTlu.png') }}" alt="Logo" height="40">
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="px-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.index' ? 'active' : '' }}"
                    href="{{ route('student-affairs-department.index') }}">
                    <i class="fas fa-home"></i>
                    Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.semester.index' ? 'active' : '' }}" href="{{ route('student-affairs-department.semester.index') }}">
                    <i class="fas fa-calendar-minus"></i>
                    Học kỳ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.class-session.index' ? 'active' : '' }}" href="{{ route('student-affairs-department.class-session.index') }}">
                    <i class="fas fa-bell"></i>
                    Sinh hoạt lớp
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.conduct-score.index' ? 'active' : '' }}" href="{{ route('student-affairs-department.conduct-score.index') }}">
                    <i class="fas fa-book"></i>
                    Điểm rèn luyện
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.class.index' ? 'active' : '' }}" href="{{ route('student-affairs-department.class.index') }}">
                    <i class="fas fa-users"></i>
                    Lớp học
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.account.index' ? 'active' : '' }}" href="{{ route('student-affairs-department.account.index') }}">
                    <i class="fas fa-user-plus"></i>
                    Tài khoản GV & SV
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'student-affairs-department.room.index' ? 'active' : '' }}" href="{{ route('student-affairs-department.room.index') }}">
                    <i class="fas fa-user-graduate"></i>
                    Phòng học
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-bar"></i>
                    Cảnh báo học vụ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-line"></i>
                    Thống kê
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
