<div class="container-fluid my-4 border-bottom">
    <div class="row">
        <div class="col-12">
            <!-- Tab navigation -->
            <ul class="nav nav-tabs border-0 d-flex justify-content-center flex-wrap gap-2" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link border border-b-blue-200 rounded {{ request()->routeIs('student-affairs-department.class-session.index') ? 'active' : '' }}"
                        href="{{ route('student-affairs-department.class-session.index') }}">
                        Sinh hoạt lớp cố định
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link border border-b-blue-200 rounded {{ request()->routeIs('student-affairs-department.class-session.flexibleClassActivities') ? 'active' : '' }}"
                        href="{{ route('student-affairs-department.class-session.flexibleClassActivities') }}">
                        Sinh hoạt lớp linh hoạt
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
