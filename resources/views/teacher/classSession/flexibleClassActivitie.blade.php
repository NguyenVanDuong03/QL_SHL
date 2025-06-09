@extends('layouts.teacher')

@section('title', 'Sinh hoạt lớp linh hoạt')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Sinh hoạt lớp linh hoạt'],
    ]"/>
@endsection

@section('main')
    <!-- Main Content -->
    <div class="col bg-light">
        <!-- Content -->
        <div class="px-4 pt-2">
            <div class="mb-2">
                <a href="{{ route('teacher.class-session.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-5 mb-3">
                    <div class="d-flex justify-content-between mt-4">
                        <div class="text-end small">
                            <p class="mb-0">📊 Tổng số lớp: <strong>{{ $data['totalClasses'] }}</strong></p>
                            <p class="mb-0 text-muted">⏳ Chưa đăng ký:
                                <strong>{{ $data['totalClasses'] - $data['countFlexibleClassSessionRequestByLecturer'] - $data['countFlexibleRejectedByLecturer'] }}
                                </strong></p>
                        </div>
                        <div class="text-end small">
                            <p class="mb-0 text-success">✅ Đăng ký thành công:
                                <strong>{{ $data['countFlexibleClassSessionRequestByLecturer'] }}</strong></p>
                            <p class="mb-0 text-danger">❌ Không thành công:
                                <strong>{{ $data['countFlexibleRejectedByLecturer'] }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm & thống kê -->
                <div class="col-md-6 col-lg-7 d-flex justify-content-end align-items-end mb-3">
                    <form method="GET" action="{{ route('teacher.class-session.flexible-class-activitie') }}"
                          class="input-group" style="max-width: 300px; margin-left: auto;">
                        <input type="text" class="form-control" placeholder="Tìm kiếm lớp học" name="search"
                               value="{{ request('search') }}"
                               aria-label="Search class" aria-describedby="search-addon">
                        <button class="btn btn-outline-secondary" id="search-addon">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <div class="">
                        <a href="{{ route('teacher.class-session.flexibleCreateRequest') }}" class="btn btn-success btn-sm ms-3 w-100">
                            Tạo lịch
                        </a>
                    </div>
                </div>
            </div>

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
                                <th scope="col" class="px-4 py-1">Trạng thái</th>
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
                                            <strong>{{ $class['study_class']['name'] }}</strong>
                                        </td>
                                        <td class="px-4 py-1 d-none d-md-table-cell">
                                            {{ $class['study_class']['major']['faculty']['department']['name'] }}
                                        </td>
                                        <td class="px-4 py-1 d-none d-md-table-cell">
                                            <span class="badge {{ $class['position'] == '0' ? 'bg-success' : ($class['position'] == '1' ? 'bg-primary' : 'bg-warning') }}">{{ $class['position'] == '0' ? 'Trực tiếp tại trường' : ($class['position'] == '1' ? 'Trực tuyến' : 'Dã ngoại') }}</span>
                                        </td>
                                        <td class="px-4 py-1">
                                            @if ($class['status'] == '2')
                                                <span class="badge bg-danger">Không thành công</span>
                                            @elseif ($class['status'] == '0')
                                                <span class="badge bg-secondary">Đang chờ duyệt</span>
                                            @else
                                                <span class="badge bg-success">Đăng ký thành công</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-1 text-center">
                                            <a class="btn btn-primary btn-sm"
                                               title="{Chỉnh sửa"
                                               href="{{ route('teacher.class-session.flexibleCreate', ['study-class-id' => $class['id'], 'session-request-id' => $class['id'] ?? null]) }}">
                                                <i class="fas fa-file-signature"></i>
                                            </a>
                                            <a href="{{ route('teacher.class-session.flexibleDetail', ['study-class-id' => $class['study_class_id'], 'session-request-id' => $class['id'] ?? null]) }}"
                                               class="btn btn-secondary btn-sm"
                                               title="Chi tiết">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <button
                                                class="btn btn-danger btn-sm btn-delete-class-session"
                                                title="Hủy đăng ký"
                                                data-id="{{ $class['id'] }}"
                                                data-room-id="{{ $class['room']['id'] ?? '' }}"
                                                data-current-page="{{ $data['getStudyClassByIds']['current_page'] }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Không có lớp học nào được tìm
                                        thấy.
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

    <!-- Modal delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Xác nhận hủy đăng ký</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Bạn có chắc muốn hủy đăng ký này?
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="room_id" id="request_room_id" value="">
                    <input type="hidden" name="current_page" class="current_page" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-delete-class-session').on('click', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                const roomId = $(this).data('room-id');
                const currentPage = $(this).data('current-page');

                $('#request_room_id').val(roomId);
                $('.current_page').val(currentPage);

                $('#deleteForm').attr('action', `/teacher/class-session/session-class-activitie/${id}`);
            });
        });
    </script>
@endpush
