@extends('layouts.studentAffairsDepartment')

@section('title', 'Thống kê')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Thống kê']]"/>
@endsection

@push('styles')
    <style>
        .progress-custom {
            height: 8px;
            border-radius: 4px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.025);
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            background-color: #fff;
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .chart-container-large {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>
@endpush

@section('main')
    <div class="dashboard-bg">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="h2 text-dark mb-0">Thống Kê</h4>
            </div>

            <!-- Main Content Tabs -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center gap-2" id="overview-tab"
                                    data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-chart-bar"></i>
                                Tổng quan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="activities-tab"
                                    data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">
                                <i class="fas fa-calendar-check"></i>
                                Buổi SHL
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="warnings-tab"
                                    data-bs-toggle="tab" data-bs-target="#warnings" type="button" role="tab">
                                <i class="fas fa-exclamation-triangle"></i>
                                Cảnh báo HV
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="dashboardTabsContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row g-4 mb-4">
                                <!-- Roles Trend Chart -->
                                <div class="col-lg-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h5 class="card-title mb-0">Tài khoản tham gia hệ thống</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="rolesChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Class Distribution Pie Chart -->
                                <div class="col-lg-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white">
                                            <h5 class="card-title mb-0">Phân bố lớp theo khoa</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="classDistributionChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activities Tab -->
                        <div class="tab-pane fade" id="activities" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">Danh sách Sinh hoạt lớp cố định</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-hover mb-0" id="activitiesTable">
                                            <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="fw-semibold">Học kỳ</th>
                                                <th class="fw-semibold">Lớp</th>
                                                <th class="fw-semibold">Ngày</th>
                                                <th class="fw-semibold">Tham gia</th>
                                                <th class="fw-semibold">Trạng thái</th>
                                            </tr>
                                            </thead>
                                            <tbody id="activitiesBody">
                                            @forelse(array_slice($data['statisticalSemester'] ?? [], 0, 5) as $activity)
                                                <tr>
                                                    <td>{{ $activity['semester_name'] ?? 'HK1' }} - {{ $activity['school_year'] ?? '' }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $activity['class_name'] ?? '' }}</span>
                                                    </td>
                                                    <td>{{ $activity['proposed_at'] ?? '' }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="fw-semibold">
                                                                {{ $activity['attendance_count'] ?? 0 }}/{{ $activity['total_students'] ?? 0 }}
                                                            </span>
                                                            <div class="progress progress-custom flex-grow-1" style="width: 100px;">
                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                     style="width: {{ (($activity['attendance_count'] ?? 0) / ($activity['total_students'] ?? 0)) * 100 }}%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('student-affairs-department.statistical.exportAttendance', [
                                                            'class_request_id' => $activity['class_session_requests_id'],
                                                            'study_class_id' => $activity['class_id'],
                                                            'study_class_name' => $activity['class_name']
                                                        ]) }}"
                                                           class="btn btn-success btn-sm d-flex align-items-center gap-1" target="_blank">
                                                            <i class="fas fa-file-excel"></i>
                                                            Xuất file
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                        Không có dữ liệu
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warnings Tab -->
                        <div class="tab-pane fade" id="warnings" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">Cảnh báo học vụ theo học kỳ</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container-large">
                                        <canvas id="warningsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize all charts
            initializeClassDistributionChart();
            initializeWarningsChart();
            initializeRolesChart();

            // Load more activities functionality
            let activityPage = 1;
            const pageSize = 5;

            function loadMoreActivities() {
                $.ajax({
                    url: '{{ route("teacher.statistical.index") }}',
                    method: 'GET',
                    data: {
                        page: activityPage + 1,
                        semester_id: $('#semesterSelect').val()
                    },
                    success: function(response) {
                        if (response.activities && response.activities.length > 0) {
                            activityPage++;
                            response.activities.forEach(activity => {
                                const attendanceRate = (activity.attendance_count / activity.total_students) * 100;
                                $('#activitiesBody').append(`
                            <tr>
                                <td>${activity.semester_name} - ${activity.school_year}</td>
                                <td><span class="badge bg-primary">${activity.class_name}</span></td>
                                <td>${activity.proposed_at}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold">${activity.attendance_count}/${activity.total_students}</span>
                                        <div class="progress progress-custom flex-grow-1" style="width: 100px;">
                                            <div class="progress-bar bg-primary" style="width: ${attendanceRate}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('teacher.statistical.exportAttendance', ['class_request_id' => ':class_request_id', 'study_class_id' => ':study_class_id', 'study_class_name' => ':study_class_name']) }}"
                                       class="btn btn-success btn-sm d-flex align-items-center gap-1" target="_blank">
                                        <i class="fas fa-file-excel"></i> Xuất file
                                    </a>
                                </td>
                            </tr>
                        `.replace(':class_request_id', activity.class_session_requests_id)
                                    .replace(':study_class_id', activity.class_id)
                                    .replace(':study_class_name', activity.class_name));
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Lỗi khi tải hoạt động:', xhr);
                    }
                });
            }

            // Scroll to load more
            $('#activitiesTable').parent().on('scroll', function() {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 5) {
                    loadMoreActivities();
                }
            });
        });

        function initializeClassDistributionChart() {
            const ctx = document.getElementById('classDistributionChart').getContext('2d');
            const rawData = @json($data['statisticalClassByDepartment'] ?? []);
            const labels = rawData.map(item => item.department_name);
            const data = rawData.map(item => item.total_classes);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function initializeWarningsChart() {
            const ctx = document.getElementById('warningsChart').getContext('2d');
            const rawData = @json($data['staticalAcademicWarningBySemester'] ?? []);

            const labels = rawData.map(item => `${item.semester_name} ${item.school_year}`);
            const data = rawData.map(item => item.warning_count);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số sinh viên bị cảnh báo',
                        data: data,
                        backgroundColor: '#ef4444',
                        borderColor: '#dc2626',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số sinh viên'
                            }
                        }
                    }
                }
            });
        }

        function initializeRolesChart() {
            const ctx = document.getElementById('rolesChart').getContext('2d');
            const rawData = @json($data['statisticalUserByRole'] ?? []);

            const groupedData = {
                'Giảng viên': 0,
                'Sinh viên': 0,
                'Văn phòng khoa': 0
            };

            rawData.forEach(item => {
                switch (item.role) {
                    case 1:
                        groupedData['Giảng viên'] += item.total;
                        break;
                    case 0:
                    case 3:
                        groupedData['Sinh viên'] += item.total;
                        break;
                    case 2:
                        groupedData['Văn phòng khoa'] += item.total;
                        break;
                }
            });

            const labels = Object.keys(groupedData);
            const data = Object.values(groupedData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số lượng',
                        data: data,
                        backgroundColor: '#3b82f6',
                        borderColor: '#0C4095FF',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endpush
