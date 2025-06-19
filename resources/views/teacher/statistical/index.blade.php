@extends('layouts.teacher')

@section('title', 'Thống kê')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Thống kê']]"/>
@endsection

@push('styles')
    <style>
        .nav-tabs .nav-link {
            color: #000;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }

        .nav-tabs .nav-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endpush

@section('main')
    <div class="m-4">
        <!-- Header -->
        <h4 class="mb-0">Thống Kê</h4>

        <div class="py-4">
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-book text-primary mb-2"></i>
                            <h6 class="text-muted">Tổng lớp</h6>
                            <h4 class="mb-0">{{ $data['countStudyClassBySemester'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-users text-success mb-2"></i>
                            <h6 class="text-muted">Tổng SV</h6>
                            <h4 class="mb-0">{{ $data['getTotalStudentsByLecturer'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar text-info mb-2"></i>
                            <h6 class="text-muted">Buổi SHL</h6>
                            <h4 class="mb-0">{{ $data['getTotalDoneSessionsByLecturer'] }}
                                /{{ $data['getTotalSessionsByLecturer'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 bg-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line text-warning mb-2"></i>
                            <h6 class="text-muted">Tỷ lệ tham gia</h6>
                            <h4 class="mb-0">{{ $data['participationRate']->attendance_rate }}%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="lecturerTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="activities-tab" data-bs-toggle="tab"
                            data-bs-target="#activities" type="button">Buổi SHL
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="conduct-tab" data-bs-toggle="tab" data-bs-target="#conduct"
                            type="button">Cảnh báo học vụ
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="lecturerTabsContent">
                <!-- Activities Tab -->
                <div class="tab-pane fade show active" id="activities" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Danh sách Sinh hoạt lớp cố định</h5>
                        </div>
                        <div class="card-body p-0" style="height: 300px; overflow-y: auto;">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="activitiesTable">
                                    <thead>
                                    <tr>
                                        <th>Học kỳ</th>
                                        <th>Lớp</th>
                                        <th>Ngày</th>
                                        <th>Tham gia</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                    </thead>
                                    <tbody id="activitiesBody">
                                    @forelse(array_slice($data['statisticalSemester'] ?? [], 0, 5) as $activity)
                                        <tr>
                                            <td>{{ $activity['semester_name'] }} - {{ $activity['school_year'] }}</td>
                                            <td>{{ $activity['class_name'] }}</td>
                                            <td>{{ $activity['proposed_at'] }}</td>
                                            <td>{{ $activity['attendance_count'] }}
                                                /{{ $activity['total_students'] }}</td>
                                            <td>
                                                <a href="{{ route('teacher.statistical.exportAttendance', ['class_request_id' => $activity['class_session_requests_id'], 'study_class_id' => $activity['class_id'], 'study_class_name' => $activity['class_name']]) }}"
                                                   class="btn btn-success btn-sm" target="_blank">
                                                    <i class="fas fa-file-excel me-2"></i> Xuất file
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conduct Tab -->
                <div class="tab-pane fade" id="conduct" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Cảnh báo học vụ</h5>
                        </div>
                        <div class="card-body p-0" style="height: 300px; overflow-y: auto;">
                            <canvas id="warningChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let activityPage = 1;
            let conductPage = 1;
            const pageSize = 5;

            initializeWarningChart();

            function loadMoreActivities() {
                $.ajax({
                    url: '{{ route("teacher.statistical.index") }}',
                    method: 'GET',
                    data: {
                        page: activityPage + 1,
                        semester_id: $('#semesterSelect').val()
                    },
                    success: function (response) {
                        if (response.activities && response.activities.length > 0) {
                            activityPage++;
                            response.activities.forEach(activity => {
                                $('#activitiesBody').append(`
                            <tr>
                                <td>${activity.semester_name} - ${activity.school_year}</td>
                                <td>${activity.class_name}</td>
                                <td>${activity.proposed_at}</td>
                                <td>${activity.attendance_count}/${activity.total_students}</td>
                                <td>
                                    <a href="{{ route('teacher.statistical.exportAttendance', ['class_request_id' => ':class_request_id', 'study_class_id' => ':study_class_id', 'study_class_name' => ':study_class_name']) }}"
                                       class="btn btn-success btn-sm" target="_blank">
                                        <i class="fas fa-file-excel me-2"></i> Xuất file
                                    </a>
                                </td>
                            </tr>
                        `.replace(':class_request_id', activity.class_session_requests_id)
                                    .replace(':study_class_id', activity.class_id)
                                    .replace(':study_class_name', activity.class_name));
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Lỗi khi tải hoạt động:', xhr);
                    }
                });
            }

            $('#activitiesTable').on('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    loadMoreActivities();
                }
            });

            $('#conductTable').on('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    loadMoreConducts();
                }
            });
        });

        function initializeWarningChart() {
            const ctx = document.getElementById('warningChart').getContext('2d');
            const rawData = @json($data['getAcademicWarningsCountByLecturerAndSemester'] ?? []);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [`${rawData.semester_name} ${rawData.school_year}`],
                    datasets: [{
                        label: 'Số sinh viên bị cảnh báo',
                        data: [rawData.total_students],
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Số sinh viên: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }
    </script>
@endpush
