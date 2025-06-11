<div id="sidebarWrapper" class="col-md-3 col-lg-2 sidebar-wrapper fixed-top">

    <!-- Navigation Menu -->
    <div class="px-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'teacher.index' ? 'active' : '' }}"
                   href="{{ route('teacher.index') }}">
                    <i class="fas fa-home"></i>
                    Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-bell"></i>
                    Thông báo
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'teacher.class-session.index' ||
                                        Route::currentRouteName() == 'teacher.class-session.fixed-class-activitie' ||
                                        Route::currentRouteName() == 'teacher.class-session.flexible-class-activitie' ||
                                        Route::currentRouteName() == 'teacher.class-session.create' ||
                                        Route::currentRouteName() == 'teacher.class-session.detail' ||
                                        Route::currentRouteName() == 'teacher.class-session.detailFixedClassActivitie' ||
                                        Route::currentRouteName() == 'teacher.class-session.infoFixedClassActivitie' ||
                                        Route::currentRouteName() == 'teacher.class-session.flexibleCreate' ||
                                        Route::currentRouteName() == 'teacher.class-session.flexibleDetail'
                                        ? 'active' : '' }}"
                   href="{{ route('teacher.class-session.index') }}">
                    <i class="fas fa-book"></i>
                    Sinh hoạt lớp
                    @if($countClassSession > 0)
                        <span class="badge bg-danger ms-1">{{ $countClassSession }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'teacher.class.index' ? 'active' : '' }}"
                   href="{{ route('teacher.class.index') }}">
                    <i class="fas fa-users"></i>
                    Lớp học
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-bar"></i>
                    Điểm rèn luyện
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'teacher.statistical.index' ? 'active' : '' }}" href="{{ route('teacher.statistical.index') }}">
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
