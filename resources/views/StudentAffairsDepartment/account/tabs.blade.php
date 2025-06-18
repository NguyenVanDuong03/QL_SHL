<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <!-- Tab navigation -->
            <ul class="nav nav-tabs border-0 d-flex justify-content-start flex-wrap gap-2" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link border border-b-blue-200 rounded {{ request()->routeIs('student-affairs-department.account.index') ? 'active' : '' }}"
                        href="{{ route('student-affairs-department.account.index') }}">
                        Danh sách tài khoản giáo viên
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link border border-b-blue-200 rounded {{ request()->routeIs('student-affairs-department.account.student') ? 'active' : '' }}"
                        href="{{ route('student-affairs-department.account.student') }}">
                        Danh sách tài khoản sinh viên
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
