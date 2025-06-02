@extends('layouts.teacher')

@section('title', 'Lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Lớp học']]" />
@endsection

@section('main')
    <div class="container-fluid py-4">
        <!-- Header with search -->
        <div class="d-flex justify-content-between mb-4">
            <h4>Lớp học</h4>
            <div class="input-group" style="max-width: 300px;">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Tìm kiếm lớp học" aria-label="Recipient's username"
                           aria-describedby="basic-addon2">
                    <button class="input-group-text" id="basic-addon2"><i class="fas fa-magnifying-glass"></i></button>
                </div>
            </div>
        </div>

        <!-- Class table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%">#</th>
                            <th scope="col" style="width: 40%">Tên lớp</th>
                            <th scope="col" style="width: 20%">Sĩ số</th>
                            <th scope="col" style="width: 35%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['classes']['data'] as $index => $class)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <div class="fw-bold">{{ $class['name'] }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ count($class['students']) }} sinh viên</span>
                                </td>
                                <td>
                                    <div class="btn-group gap-2" role="group">
                                        <!-- Xem chi tiết -->
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm {{ count($class['students']) > 0 ? '' : 'disabled' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewClassModal"
                                                data-class-id="{{ $class['id'] }}"
                                                data-class-name="{{ $class['name'] }}"
                                                data-student-count="{{ count($class['students']) }}"
                                                title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Sửa -->
                                        <button type="button"
                                                class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editClassModal"
                                                data-class-id="{{ $class['id'] }}"
                                                data-class-name="{{ $class['name'] }}"
                                                title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Xóa -->
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteClassModal"
                                                data-class-id="{{ $class['id'] }}"
                                                data-class-name="{{ $class['name'] }}"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if(count($data['classes']['data']) == 0)
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không có lớp học nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            <x-pagination.pagination :paginate="$data['classes']" />
        </div>
    </div>

    <!-- View Class Modal -->
    <div class="modal fade" id="viewClassModal" tabindex="-1" aria-labelledby="viewClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClassModalLabel">
                        <i class="fas fa-eye me-2"></i>Chi tiết lớp học
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Tên lớp:</strong>
                            <p id="viewClassName" class="text-muted"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Sĩ số:</strong>
                            <p id="viewStudentCount" class="text-muted"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-grid">
                        <a id="viewStudentListBtn" href="#" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Xem danh sách sinh viên
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal -->
    <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa lớp học
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClassForm">
                        <input type="hidden" id="editClassId" name="classId">
                        <div class="mb-3">
                            <label for="editClassName" class="form-label">Tên lớp</label>
                            <input type="text" class="form-control" id="editClassName" name="className" required>
                            <div class="invalid-feedback">Vui lòng nhập tên lớp.</div>
                        </div>
{{--                        <div class="mb-3">--}}
{{--                            <label for="editClassDescription" class="form-label">Mô tả</label>--}}
{{--                            <textarea class="form-control" id="editClassDescription" name="classDescription" rows="3"></textarea>--}}
{{--                        </div>--}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveEditClassBtn">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Class Modal -->
    <div class="modal fade" id="deleteClassModal" tabindex="-1" aria-labelledby="deleteClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClassModalLabel">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Cảnh báo!</strong> Hành động này không thể hoàn tác.
                    </div>
                    <p>Bạn có chắc chắn muốn xóa lớp học <strong id="deleteClassName"></strong> không?</p>
                    <input type="hidden" id="deleteClassId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // View Class Modal
            $('#viewClassModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const classId = button.data('class-id');
                const className = button.data('class-name');
                const studentCount = button.data('student-count');

                $('#viewClassName').text(className);
                $('#viewStudentCount').text(studentCount + ' sinh viên');
                $('#viewStudentListBtn').attr('href', "{{ route('teacher.class.infoStudent', ['id' => '']) }}/" + classId);
            });

            // Edit Class Modal
            $('#editClassModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const classId = button.data('class-id');
                const className = button.data('class-name');

                $('#editClassId').val(classId);
                $('#editClassName').val(className);
            });

            // Delete Class Modal
            $('#deleteClassModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const classId = button.data('class-id');
                const className = button.data('class-name');

                $('#deleteClassId').val(classId);
                $('#deleteClassName').text(className);
            });

            // Save Edit Class
            $('#saveEditClassBtn').on('click', function () {
                const formData = new FormData($('#editClassForm')[0]);

                // Add your AJAX call here to save the changes
                console.log('Saving class changes...', Object.fromEntries(formData));

                // Close modal after successful save
                $('#editClassModal').modal('hide');
            });

            // Confirm Delete
            $('#confirmDeleteBtn').on('click', function () {
                const classId = $('#deleteClassId').val();

                // Add your AJAX call here to delete the class
                console.log('Deleting class with ID:', classId);

                // Close modal after successful delete
                $('#deleteClassModal').modal('hide');
            });
        });
    </script>
@endpush
