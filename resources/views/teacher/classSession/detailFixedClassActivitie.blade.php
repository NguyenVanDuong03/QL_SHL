@extends('layouts.teacher')

@section('title', 'Lịch sinh hoạt lớp')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Lịch sinh hoạt lớp'],
    ]"/>
@endsection

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="px-4 pt-2">
            <div class="container-fluid">
                <div class="row">
                    <!-- Thông tin học kỳ -->
                    <div class="col-md-6 col-lg-5 mb-3">
                        <div class="mb-2">
                            <a href="{{ route('teacher.class-session.index') }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                        <h5 class="mb-1">{{ $data['getCSRSemesterInfo']->name }}
                            - {{ $data['getCSRSemesterInfo']->school_year }}</h5>
                        <div class="d-flex justify-content-between">
                            <div class="text-end small">
                                <p class="mb-0">📊 Tổng số lớp: <strong>{{ $data['totalClasses'] }}</strong></p>
                                <p class="mb-0 text-muted">⏳ Chưa đăng ký:
                                    <strong>{{ $data['totalClasses'] - $data['countApprovedByLecturerAndSemester'] - $data['countRejectedByLecturerAndSemester'] }}
                                    </strong></p>
                            </div>
                            <div class="text-end small">
                                <p class="mb-0 text-success">✅ Đăng ký thành công:
                                    <strong>{{ $data['countApprovedByLecturerAndSemester'] }}</strong></p>
                                <p class="mb-0 text-danger">❌ Không thành công:
                                    <strong>{{ $data['countRejectedByLecturerAndSemester'] }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Tìm kiếm & thống kê -->
                    <div class="col-md-6 col-lg-7 d-flex justify-content-end align-items-end">
                        <form method="GET" action="{{ route('teacher.class-session.detailFixedClassActivitie') }}" class="input-group mb-3" style="max-width: 300px; margin-left: auto;">
                            <input type="text" class="form-control" placeholder="Tìm kiếm lớp học" name="search" value="{{ request('search') }}"
                                   aria-label="Search class" aria-describedby="search-addon">
                            <button class="btn btn-outline-secondary" id="search-addon">
                                <i class="fas fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>


{{--            @if (isset($data['getStudyClassByIds']) && $data['getStudyClassByIds']['total'] > 0)--}}
                <!-- Table -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" class="px-4 py-1">STT</th>
                                    <th scope="col" class="px-4 py-1">Tên lớp</th>
                                    <th scope="col" class="px-4 py-1 d-none d-md-table-cell">Khoa</th>
                                    <th scope="col" class="px-4 py-1 d-none d-md-table-cell">Hình thức</th>
                                    <th scope="col" class="px-4 py-1">Thời gian họp</th>
                                    <th scope="col" class="px-4 py-1 text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data['getStudyClassByIds']['total'] > 0)
                                    @foreach ($data['getStudyClassByIds']['data'] as $index => $class)
                                        <tr>
                                            <td class="px-4 py-1">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-1">
                                                <strong>{{ $class['name'] }}</strong>
                                            </td>
                                            <td class="px-4 py-1 d-none d-md-table-cell">
                                                {{ $class['major']['faculty']['department']['name'] }}
                                            </td>
                                            <td class="px-4 py-1 d-none d-md-table-cell">
                                                    <span
                                                        class="badge {{ $class['class_session_requests']['position'] == '0' ? 'bg-success' : ($class['class_session_requests']['position'] == '1' ? 'bg-primary' : 'bg-warning') }}">{{ $class['class_session_requests']['position'] == '0' ? 'Trực tiếp tại trường' : ($class['class_session_requests']['position'] == '1' ? 'Trực tuyến' : 'Dã ngoại') }}</span>
                                            </td>
                                            <td class="px-4 py-1">
                                                {{ \Carbon\Carbon::parse($class['class_session_requests']['proposed_at'])->format('H:i d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-1 text-center">
                                                <a href="{{ route('teacher.class-session.infoFixedClassActivitie', ['study-class-id' => $class['id'], 'session-request-id' => $class['class_session_requests']['id'] ?? null]) }}"
                                                   class="btn btn-secondary btn-sm {{ empty($class['class_session_requests']) ? 'disabled' : '' }}"
                                                   title="Chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Không có lớp học nào được tìm thấy.
                                        </td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <x-pagination.pagination :paginate="$data['getStudyClassByIds']"/>
                </div>
{{--            @else--}}
{{--                <div class="text-center alert alert-warning" role="alert">--}}
{{--                    <i class="fas fa-exclamation-triangle me-2"></i>--}}
{{--                    Tất cả các lớp đã được đăng ký.--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </div>

    <!-- View Class Modal -->
    <div class="modal fade" id="viewClassModal" tabindex="-1" aria-labelledby="viewClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClassModalLabel">
                        <i class="fas fa-user me-2"></i>Thông tin sinh viên
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên lớp:</label>
                                <p id="viewClassName" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Khoa:</label>
                                <p id="viewClassEmail" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình thức:</label>
                                <p id="viewClassGender" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ghi chú:</label>
                                <p id="viewClassGender" class="text-muted mb-0"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái:</label>
                                <p id="viewClassDob" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phòng:</label>
                                <p id="viewClassPhone" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa điểm:</label>
                                <p id="viewClassPosition" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nền tảng:</label>
                                <p id="viewClassPosition" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã phòng họp:</label>
                                <p id="viewClassPosition" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mật khẩu:</label>
                                <p id="viewClassPosition" class="text-muted mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đường dẫn:</label>
                                <p id="viewClassPosition" class="text-muted mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

        });
    </script>

@endsection
