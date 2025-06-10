@extends('layouts.studentAffairsDepartment')

@section('title', 'Danh sách báo cáo sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Sinh hoạt lớp', 'url' => 'student-affairs-department.class-session.index'],
        ['label' => 'Danh sách báo cáo sinh hoạt lớp']]"/>
@endsection

@push('styles')
    <style>
        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd #0d6efd #fff;
        }

        .nav-tabs .nav-link {
            color: #0d6efd;
        }

        .stats-card {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            padding: 1rem;
            margin-bottom: 1.5rem;
            background-color: white;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
@endpush

@section('main')
    @include('StudentAffairsDepartment.classSession.tabs')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="px-4">
            <h4 class="text-center mb-4 fw-bold">Danh sách báo cáo sinh hoạt lớp</h4>

            <!-- Statistics Cards -->
            <div class="d-flex justify-content-between">
                <h6 class="text-muted mb-2">Tổng số lớp toàn trường: <span
                        class="fw-bold">{{ $data['countStudyClass'] ?? 0 }}</span></h6>
                <h6 class="text-muted mb-2">Tổng số báo cáo: <span
                        class="fw-bold">{{ $data['reports']['total'] ?? 0 }}</span></h6>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Bộ lọc</h5>
                </div>
                <div class="card-body">
                    <form id="filter-form" method="GET"
                          action="{{ route('student-affairs-department.class-session.listReports') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="semester-filter" class="form-label">Học kỳ</label>
                                <select id="semester-filter" name="semester_id" class="form-select">
                                    <option value="">Tất cả học kỳ</option>
                                    @foreach($data['getSemesters'] ?? [] as $semester)
                                        <option
                                            value="{{ $semester['id'] }}" {{ $semester['id'] == request()->get('semester_id') ? 'selected' : '' }}>{{ $semester['name'] }}
                                            - {{ $semester['school_year'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="department-filter" class="form-label">Khoa/Ngành</label>
                                <select id="department-filter" name="major_id" class="form-select">
                                    <option value="">Tất cả khoa/ngành</option>
                                    @foreach($data['getMajors'] ?? [] as $major)
                                        <option
                                            value="{{ $major['id'] }}" {{ $major['id'] == request()->get('major_id') ? 'selected' : '' }}>{{ $major['name'] }}
                                            /{{ $major['faculty']['department']['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="class-filter" class="form-label">Tên lớp</label>
                                <input type="text" id="class-filter" name="study_class_name"
                                       value="{{ request()->get('study_class_name') }}" class="form-control"
                                       placeholder="Nhập tên lớp...">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" id="reset-filter" class="btn btn-outline-secondary me-2">Đặt lại
                            </button>
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col" class="px-4 py-3">STT</th>
                                <th scope="col" class="px-4 py-3">Tên lớp</th>
                                <th scope="col" class="px-4 py-3 d-none d-md-table-cell">Thời gian</th>
                                <th scope="col" class="px-4 py-3 d-none d-md-table-cell">Ngành/Khoa</th>
                                <th scope="col" class="px-4 py-3">Học kỳ</th>
                                <th scope="col" class="px-4 py-3 text-center">Xem chi tiết</th>
                            </tr>
                            </thead>
                            <tbody id="reports-table-body">
                            @forelse($data['reports']['data'] ?? [] as $index => $report)
                                <tr>
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $report['class_session_request']['study_class']['name'] }}</td>
                                    <td class="px-4 py-3 d-none d-md-table-cell">{{ \Carbon\Carbon::parse($report['class_session_request']['proposed_at'])->format('H:i d/m/Y') }}</td>
                                    <td class="px-4 py-3 d-none d-md-table-cell">{{ $report['class_session_request']['study_class']['major']['name'] }}
                                        <br> <small
                                            class="text-muted">{{ $report['class_session_request']['study_class']['major']['faculty']['department']['name'] }}</small>
                                    </td>
                                    <td class="px-4 py-3">{{ $report['class_session_request']['class_session_registration']['semester']['name'] ?? '' }}
                                        - {{ $report['class_session_request']['class_session_registration']['semester']['school_year'] ?? '' }} </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="btn btn-outline-primary btn-sm view-report-btn"
                                                title="Xem chi tiết báo cáo"
                                                data-id="{{ $report['id'] }}"
                                                data-request-id="{{ $report['class_session_request']['id'] }}"
                                                data-class-id="{{ $report['class_session_request']['study_class']['id'] }}"
                                                data-class-name="{{ $report['class_session_request']['study_class']['name'] }}"
                                                data-proposed-at="{{ \Carbon\Carbon::parse($report['class_session_request']['proposed_at'])->format('H:i d/m/Y') }}"
                                                data-semester="{{ $report['class_session_request']['class_session_registration']['semester']['name'] ?? '' }}"
                                                data-other-activities="{{ $report['other_activities'] }}"
                                                data-teacher-attendance="{{ $report['teacher_attendance'] ? 'Có' : 'Không' }}"
                                                data-attending-students="{{ $report['attending_students'] }}"
                                                data-politics="{{ $report['politics_ethics_lifestyle'] }}"
                                                data-academic="{{ $report['academic_training_status'] }}"
                                                data-on-campus="{{ $report['on_campus_student_status'] }}"
                                                data-off-campus="{{ $report['off_campus_student_status'] }}"
                                                data-suggestions="{{ $report['suggestions_to_faculty_university'] }}"
                                                data-bs-toggle="modal" data-bs-target="#reportDetailModal">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Không có dữ liệu báo cáo</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <x-pagination.pagination :paginate="$data['reports']"/>
            </div>
        </div>
    </div>

    <!-- Report Detail Modal -->
    <div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportDetailModalLabel">Chi tiết báo cáo sinh hoạt lớp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Lớp:</strong> <span id="modal-class-name"></span></p>
                        <p class="mb-1"><strong>Ngày báo cáo:</strong> <span id="modal-report-date"></span></p>
                        <p class="mb-1"><strong>Học kỳ:</strong> <span id="modal-semester"></span></p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Thông tin chung</h6>
                            <p class="mb-1"><strong>Giáo viên tham dự:</strong> <span
                                    id="modal-teacher-attendance"></span></p>
                            <p class="mb-1"><strong>Số sinh viên tham dự:</strong> <span
                                    id="modal-attending-students"></span></p>
                            <p class="mb-1"><strong>Hoạt động khác:</strong> <span id="modal-other-activities"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Tình hình lớp</h6>
                            <p class="mb-1"><strong>Chính trị, đạo đức, lối sống:</strong> <span
                                    id="modal-politics"></span></p>
                            <p class="mb-1"><strong>Tình hình học tập, rèn luyện:</strong> <span
                                    id="modal-academic"></span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Tình hình sinh viên</h6>
                            <p class="mb-1"><strong>Sinh viên nội trú:</strong> <span id="modal-on-campus"></span></p>
                            <p class="mb-1"><strong>Sinh viên ngoại trú:</strong> <span id="modal-off-campus"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Kiến nghị</h6>
                            <p id="modal-suggestions"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <a href="{{ route('student-affairs-department.class-session.exportReport') }}" target="_blank"
                       id="export-excel-btn" class="btn btn-success" title="Xuất Excel danh sách điểm danh">
                        <i class="fa fa-file-excel-o me-2"></i>Xuất Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Reset filters
            $('#reset-filter').on('click', function () {
                $('#semester-filter').val('');
                $('#department-filter').val('');
                $('#class-filter').val('');
            });

            // Handle view report button click
            $(document).on('click', '.view-report-btn', function () {
                const reportId = $(this).data('report-id');
                const requestId = $(this).data('request-id');
                const classId = $(this).data('class-id');
                const className = $(this).data('class-name');
                const proposedAt = $(this).data('proposed-at');
                const semester = $(this).data('semester');
                const otherActivities = $(this).data('other-activities');
                const teacherAttendance = $(this).data('teacher-attendance');
                const attendingStudents = $(this).data('attending-students');
                const politics = $(this).data('politics');
                const academic = $(this).data('academic');
                const onCampus = $(this).data('on-campus');
                const offCampus = $(this).data('off-campus');
                const suggestions = $(this).data('suggestions');
                // Set modal data
                $('#modal-class-name').text(className);
                $('#modal-report-date').text(proposedAt);
                $('#modal-semester').text(semester);
                $('#modal-other-activities').text(otherActivities);
                $('#modal-teacher-attendance').text(teacherAttendance);
                $('#modal-attending-students').text(attendingStudents);
                $('#modal-politics').text(politics);
                $('#modal-academic').text(academic);
                $('#modal-on-campus').text(onCampus);
                $('#modal-off-campus').text(offCampus);
                $('#modal-suggestions').text(suggestions);

                $('#export-excel-btn').attr('href', `/student-affairs-department/class-session/export-attendance/${requestId}/${classId}`);
            });
        });
    </script>
@endpush
