@extends('layouts.teacher')

@section('title', 'Danh sách sinh viên')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Điểm rèn luyện', 'url' => 'teacher.conduct-score.index'],
        ['label' => 'Danh sách lớp học', 'url' => 'teacher.conduct-score.infoConductScore', 'params' => ['conduct_evaluation_period_id' => $data['conduct_evaluation_period_id']]],
        ['label' => 'Danh sách sinh viên'],
    ]"/>
@endsection

@push('styles')
    <style>
        .stats-card {
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 16px;
        }

        .stats-number {
            font-size: 1.125rem;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .stats-label {
            font-size: 0.75rem;
        }

        .student-code {
            font-family: 'Courier New', monospace;
            color: #2563eb;
        }

        .badge-custom {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        .action-btn {
            padding: 4px 8px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #f8f9fa;
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="mb-4">
                    <div class="d-md-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('teacher.conduct-score.infoConductScore', ['conduct_evaluation_period_id' => $data['conduct_evaluation_period_id']]) }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>

                    </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-6 col-md-3">
                            <div class="stats-card bg-secondary">
                                <div class="stats-number text-white"
                                     id="totalStudents">{{ $data['listConductScores']['total'] }}</div>
                                <div class="stats-label text-white">Tổng sinh viên</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stats-card bg-primary">
                                <div class="stats-number text-white"
                                     id="studentEvaluated">{{ $data['countStudentsByConductStatus']['self_evaluated'] }}</div>
                                <div class="stats-label text-white">Sinh viên đã chấm</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stats-card bg-success">
                                <div class="stats-number text-white"
                                     id="teacherEvaluated">{{ $data['countStudentsByConductStatus']['class_teacher_evaluated'] }}</div>
                                <div class="stats-label text-white">GVCN đã chấm</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stats-card bg-danger">
                                <div class="stats-number text-white"
                                     id="rejectedCount">{{ $data['countStudentsByConductStatus']['student_affairs_evaluated'] }}</div>
                                <div class="stats-label text-white">VP Khoa dã chấm</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="d-flex justify-content-end mb-4">
                    <form method="GET" action="{{ route('teacher.conduct-score.list') }}" class="position-relative">
                        <input type="hidden" name="conduct_evaluation_period_id" value="{{ $data['conduct_evaluation_period_id'] }}">
                        <input type="hidden" name="study_class_id" value="{{ $data['study_class_id'] }}">
                        <div class="input-group me-2" style="width: 250px;">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm..."
                                   id="search">
                            <button class="btn btn-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <h4 class="text-center mb-4">Quản lý đánh giá điểm rèn luyện</h4>
                <!-- Table -->
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="studentsTable">
                            <thead class="table-light">
                            <tr>
                                <th class="fw-semibold text-dark">STT</th>
                                <th class="fw-semibold text-dark">Mã SV</th>
                                <th class="fw-semibold text-dark">Tên sinh viên</th>
                                <th class="fw-semibold text-dark">Trạng thái</th>
                                <th class="fw-semibold text-dark">Điểm rèn luyện</th>
                                <th class="fw-semibold text-dark text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data['listConductScores']['data'] ?? [] as $item)
                                <tr>
                                    <td class="fw-medium">{{ $loop->iteration }}</td>
                                    <td class="student-code">{{ $item['student_code'] }}</td>
                                    <td class="fw-medium">{{ $item['student_name'] }}</td>
                                    <td>
                                        @php
                                            $status = $item['evaluation_status'];
                                            $statusClass = match ($status) {
                                                0 => 'bg-info',         // SV tự đánh giá
                                                1 => 'bg-primary',      // GVCN đánh giá
                                                2 => 'bg-success',      // CTSV đánh giá
                                                3 => 'bg-danger',       // Từ chối
                                                default => 'bg-secondary', // Chưa đánh giá
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} badge-custom">
                                            {{ $item['status_description'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $item['total_self_score'] }} / {{ $item['total_class_score'] }} / {{ $item['total_final_score'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('teacher.conduct-score.detail', ['student_id' => $item['student_id'], 'conduct_evaluation_period_id' => $data['conduct_evaluation_period_id'], 'study_class_id' => $data['study_class_id'] ]) }}"
                                           class="action-btn btn-edit" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Không có sinh viên nào</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <x-pagination.pagination :paginate="$data['listConductScores']" class="mt-4"/>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Search functionality
            $('#searchInput').on('keyup', function () {
                filterTable();
            });

            // Status filter
            $('#statusFilter').on('change', function () {
                filterTable();
            });

            function filterTable() {
                const searchTerm = $('#searchInput').val().toLowerCase();
                const statusFilter = $('#statusFilter').val();
                let visibleCount = 0;

                $('.student-row').each(function () {
                    const $row = $(this);
                    const name = $row.data('name');
                    const code = $row.data('code');
                    const status = $row.data('status');

                    // Check search match
                    const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);

                    // Check status filter
                    let matchesStatus = true;
                    if (statusFilter !== 'all') {
                        switch (statusFilter) {
                            case 'not_evaluated':
                                matchesStatus = status === 'null';
                                break;
                            case 'student_evaluated':
                                matchesStatus = status === '0';
                                break;
                            case 'teacher_evaluated':
                                matchesStatus = status === '1';
                                break;
                            case 'rejected':
                                matchesStatus = status === '3';
                                break;
                        }
                    }

                    if (matchesSearch && matchesStatus) {
                        $row.show();
                        visibleCount++;
                    } else {
                        $row.hide();
                    }
                });

                // Update showing count
                $('#showingCount').text(visibleCount);
                updateStats();

                // Show/hide no results message
                if (visibleCount === 0) {
                    if ($('#noResultsRow').length === 0) {
                        $('#studentsTable tbody').append(
                            '<tr id="noResultsRow"><td colspan="6" class="text-center py-4 text-muted">Không tìm thấy sinh viên nào</td></tr>'
                        );
                    }
                } else {
                    $('#noResultsRow').remove();
                }
            }

            function updateStats() {
                let totalVisible = 0;
                let studentEvaluated = 0;
                let teacherEvaluated = 0;
                let rejected = 0;

                $('.student-row:visible').each(function () {
                    totalVisible++;
                    const status = $(this).data('status');
                    if (status === '0') studentEvaluated++;
                    else if (status === '1') teacherEvaluated++;
                    else if (status === '3') rejected++;
                });

                $('#totalStudents').text(totalVisible);
                $('#studentEvaluated').text(studentEvaluated);
                $('#teacherEvaluated').text(teacherEvaluated);
                $('#rejectedCount').text(rejected);
            }
        });

        // Action functions
        function viewDetail(studentId) {
            alert('Xem chi tiết sinh viên ID: ' + studentId);
            // Có thể redirect đến trang chi tiết
            // window.location.href = '/students/' + studentId;
        }

        function editStudent(studentId) {
            alert('Chỉnh sửa sinh viên ID: ' + studentId);
            // Có thể redirect đến trang chỉnh sửa
            // window.location.href = '/students/' + studentId + '/edit';
        }

        function deleteStudent(studentId) {
            if (confirm('Bạn có chắc chắn muốn xóa sinh viên này?')) {
                // Xóa dòng khỏi bảng
                $('[onclick="deleteStudent(\'' + studentId + '\')"]').closest('tr').remove();

                // Cập nhật lại STT
                $('.student-row').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });

                // Cập nhật thống kê
                updateStats();
                $('#showingCount').text($('.student-row:visible').length);
                $('#totalCount').text($('.student-row').length);

                alert('Xóa sinh viên thành công!');
            }
        }

        // Function to update stats (make it global)
        function updateStats() {
            let totalVisible = 0;
            let studentEvaluated = 0;
            let teacherEvaluated = 0;
            let rejected = 0;

            $('.student-row:visible').each(function () {
                totalVisible++;
                const status = $(this).data('status');
                if (status === '0') studentEvaluated++;
                else if (status === '1') teacherEvaluated++;
                else if (status === '3') rejected++;
            });

            $('#totalStudents').text(totalVisible);
            $('#studentEvaluated').text(studentEvaluated);
            $('#teacherEvaluated').text(teacherEvaluated);
            $('#rejectedCount').text(rejected);
        }
    </script>
@endpush
