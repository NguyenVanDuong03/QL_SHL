@extends('layouts.teacher')

@section('title', 'Thống kê')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Thống kê']]"/>
@endsection

@push('styles')
    <style>
        /* Custom styles */
        .stat-card {
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .class-card {
            transition: all 0.2s;
        }

        .class-card:hover {
            border-color: #007bff;
        }

        .warning-card {
            border-left: 4px solid;
        }

        .warning-card.danger {
            border-left-color: #dc3545;
        }

        .warning-card.warning {
            border-left-color: #ffc107;
        }

        .warning-card.info {
            border-left-color: #17a2b8;
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .mobile-full {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }

            .mobile-scroll {
                overflow-x: auto;
            }

            .mobile-scroll table {
                min-width: 800px;
            }

            .mobile-card {
                margin-bottom: 1rem;
            }

            .mobile-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }

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
                            type="button">Điểm rèn luyện
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
                                    @foreach(array_slice($data['statisticalSemester'] ?? [], 0, 5) as $activity)
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
                                    @endforeach
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
                            <h5 class="mb-0">Điểm rèn luyện</h5>
                        </div>
                        <div class="card-body p-0" style="height: 300px; overflow-y: auto;">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="conductTable">
                                    <thead>
                                    <tr>
                                        <th>Lớp</th>
                                        <th>Số SV</th>
                                        <th>Xuất sắc</th>
                                        <th>Tốt</th>
                                        <th>Khá</th>
                                        <th>TB</th>
                                        <th>Yếu</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                    </thead>
                                    <tbody id="conductBody">
                                    {{--                                    @foreach(array_slice($data['conducts'] ?? [], 0, 5) as $conduct)--}}
                                    {{--                                        <tr>--}}
                                    {{--                                            <td>{{ $conduct['class'] }}</td>--}}
                                    {{--                                            <td>{{ $conduct['total_students'] }}</td>--}}
                                    {{--                                            <td>{{ $conduct['excellent'] }}</td>--}}
                                    {{--                                            <td>{{ $conduct['good'] }}</td>--}}
                                    {{--                                            <td>{{ $conduct['fair'] }}</td>--}}
                                    {{--                                            <td>{{ $conduct['average'] }}</td>--}}
                                    {{--                                            <td>{{ $conduct['poor'] }}</td>--}}
                                    {{--                                            <td><span class="badge bg-danger">{{ $conduct['warning'] }}</span></td>--}}
                                    {{--                                        </tr>--}}
                                    {{--                                    @endforeach--}}
                                    </tbody>
                                </table>
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
        $(document).ready(function () {
            let activityPage = 1;
            let conductPage = 1;
            const pageSize = 5;

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

            function loadMoreConducts() {
                {{--$.ajax({--}}
                {{--    url: '{{ route("teacher.statistical.conducts") }}',--}}
                {{--    method: 'GET',--}}
                {{--    data: {--}}
                {{--        page: conductPage + 1,--}}
                {{--        semester_id: $('#semesterSelect').val()--}}
                {{--    },--}}
                {{--    success: function (response) {--}}
                {{--        if (response.conducts.length > 0) {--}}
                {{--            conductPage++;--}}
                {{--            response.conducts.forEach(conduct => {--}}
                {{--                $('#conductBody').append(`--}}
                {{--                <tr>--}}
                {{--                    <td>${conduct.class}</td>--}}
                {{--                    <td>${conduct.total_students}</td>--}}
                {{--                    <td>${conduct.excellent}</td>--}}
                {{--                    <td>${conduct.good}</td>--}}
                {{--                    <td>${conduct.fair}</td>--}}
                {{--                    <td>${conduct.average}</td>--}}
                {{--                    <td>${conduct.poor}</td>--}}
                {{--                    <td><span class="badge bg-danger">${conduct.warning}</span></td>--}}
                {{--                </tr>--}}
                {{--            `);--}}
                {{--            });--}}
                {{--        }--}}
                {{--    }--}}
                {{--});--}}
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
    </script>
@endpush
