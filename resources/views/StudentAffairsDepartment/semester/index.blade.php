@extends('layouts.studentAffairsDepartment')

@section('title', 'Học kỳ')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Học kỳ']]"/>
@endsection

@section('main')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="h3 mb-0 text-gray-800">Quản lý học kỳ</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addSemesterModal
            ">
                <i class="fas fa-plus"></i> Thêm học kỳ mới
            </button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách học kỳ</h6>
                <div class="d-flex">
                    <form method="GET" action="{{ route('student-affairs-department.semester.index') }}"
                          class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" name="search" id="search-semester"
                               placeholder="Tìm kiếm học kỳ..." value="{{ request('search') }}"
                        >
                        <button class="btn btn-outline-secondary btn-search-semester" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th>Tên học kỳ</th>
                            <th>Năm học</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($data['semesters']['data'] ?? [] as $semester)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $semester['name'] }}</td>
                                <td>{{ $semester['school_year'] }}</td>
                                <td>{{ $semester['start_date'] }}</td>
                                <td>{{ $semester['end_date'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editSemesterBtn"
                                            data-id="{{ $semester['id'] }}" data-name="{{ $semester['name'] }}"
                                            data-school_year="{{ $semester['school_year'] }}"
                                            data-start_date="{{ $semester['start_date'] }}"
                                            data-end_date="{{ $semester['end_date'] }}"
                                            data-current-page="{{ $data['semesters']['current_page'] }}"
                                            data-search="{{ request('search') }}"
                                            data-bs-toggle="modal" data-bs-target="#editSemesterModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteSemster" data-bs-toggle="modal"
                                            data-bs-target="#deleteSemesterModal"
                                            data-current-page="{{ $data['semesters']['current_page'] }}"
                                            data-search="{{ request('search') }}"
                                            data-id={{ $semester['id'] }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có học kỳ nào.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <x-pagination.pagination :paginate="$data['semesters']"/>
            </div>
        </div>
    </div>

    <!-- Add Semester Modal -->
    <div class="modal fade auto-reset-modal" id="addSemesterModal" tabindex="-1"
         aria-labelledby="addSemesterModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSemesterModalLabel">Thêm học kỳ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="createSemesterForm"
                      action="{{ route('student-affairs-department.semester.create') }}">
                    <div class="modal-body">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="semesterName1" class="form-label">Tên học kỳ</label>
                            <select class="form-select" id="semesterName1" name="name">
                                <option value="Học kỳ 1">Học kỳ 1</option>
                                <option value="Học kỳ 2">Học kỳ 2</option>
                                <option value="Học kỳ phụ">Học kỳ phụ</option>
                                <option value="Học kỳ hè">Học kỳ hè</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semesterSchoolYear1" class="form-label">Năm học</label>
                            <input type="text" class="form-control" id="semesterSchoolYear1" name="school_year"
                                   placeholder="Ví dụ: 2023-2024" required>
                            <div id="school_year_error1" class="text-danger text-danger-error"></div>
                            @error('school_year')
                            <div class="text-danger text-danger-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="createStartDate1" class="form-label">Ngày bắt đầu</label>
                                <input type="text" class="form-control" id="createStartDate1" name="start_date"
                                       placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
                                       onblur="if(!this.value)this.type='text'" required value=""
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                <div id="start_date1" class="text-danger text-danger-error"></div>
                                @error('start_date')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="createEndDate1" class="form-label">Ngày kết thúc</label>
                                <input type="text" class="form-control" id="createEndDate1" name="end_date"
                                       placeholder="Chọn thời gian kết thúc" onfocus="(this.type='datetime-local')"
                                       onblur="if(!this.value)this.type='text'" required value=""
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                <div id="end_date1" class="text-danger text-danger-error"></div>
                                @error('end_date')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary" id="createSemesterBtn">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Semester Modal -->
    <div class="modal fade auto-reset-modal" id="editSemesterModal" tabindex="-1"
         aria-labelledby="editSemesterModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSemesterModal">Chỉnh sửa học kỳ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editSemesterForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" class="current_page" name="current_page" value="">
                        <input type="hidden" name="search" class="search_keyword">
                        <div class="mb-3">
                            <label for="editSemesterName2" class="form-label">Tên học kỳ</label>
                            <select class="form-select" id="editSemesterName2" name="name">
                                <option value="Học kỳ 1" selected>Học kỳ 1</option>
                                <option value="Học kỳ 2">Học kỳ 2</option>
                                <option value="Học kỳ phụ">Học kỳ phụ</option>
                                <option value="Học kỳ hè">Học kỳ hè</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editSemesterSchoolYear2" class="form-label">Năm học</label>
                            <input type="text" class="form-control" id="editSemesterSchoolYear2" name="school_year"
                                   value="">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editStartDate2" class="form-label">Thời gian bắt đầu</label>
                                <input type="text" class="form-control" id="editStartDate2" name="start_date"
                                       placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
                                       onblur="if(!this.value)this.type='text'" required
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                <div id="start_date2" class="text-danger text-danger-error"></div>
                                @error('start_date')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editEndDate2" class="form-label">Thời gian kết thúc</label>
                                <input type="text" class="form-control" id="editEndDate2" name="end_date"
                                       placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
                                       onblur="if(!this.value)this.type='text'" required
                                       min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                <div id="end_date2" class="text-danger text-danger-error"></div>
                                @error('end_date')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary editSemesterBtnSubmit">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Semester Modal -->
    <div class="modal fade" id="deleteSemesterModal" tabindex="-1" aria-labelledby="deleteSemesterModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSemesterModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa học kỳ <strong>Học kỳ 1 năm học 2023-2024</strong> không?</p>
                    <p class="text-danger"><strong>Lưu ý:</strong> Hành động này không thể hoàn tác. Tất cả dữ liệu liên
                        quan đến học kỳ này sẽ bị xóa vĩnh viễn.</p>
                </div>
                <form method="POST" id="deleteSemesterForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" class="current_page" name="current_page" value="">
                    <input type="hidden" name="search" class="search_keyword" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger btnSemesterDelete">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Reset form when modal is closed
            $('.auto-reset-modal').on('hidden.bs.modal', function () {
                $('.text-danger-error').text('');
                $('.form-control').removeClass('is-invalid');
            });

            $('#createSemesterBtn').on('click', function (e) {
                let isValid = true;

                $('.text-danger-error').text('');
                $('.form-control').removeClass('is-invalid');

                const schoolYear = $('#semesterSchoolYear1').val().trim();
                const schoolYearError = $('#school_year_error1');
                const schoolYearRegex = /^\d{4}-\d{4}$/;
                if (!schoolYear) {
                    schoolYearError.text('Năm học không được để trống.');
                    $('#semesterSchoolYear1').addClass('is-invalid');
                    isValid = false;
                } else if (!schoolYearRegex.test(schoolYear)) {
                    schoolYearError.text('Định dạng năm học phải là YYYY-YYYY.');
                    $('#semesterSchoolYear1').addClass('is-invalid');
                    isValid = false;
                }

                // Validate start_date
                const startDateVal = $('#createStartDate1').val().trim();
                const startDateError = $('#start_date1');
                if (!startDateVal) {
                    startDateError.text('Ngày bắt đầu không được để trống.');
                    $('#createStartDate1').addClass('is-invalid');
                    isValid = false;
                }

                // Validate end_date
                const endDateVal = $('#createEndDate1').val().trim();
                const endDateError = $('#end_date1');
                if (!endDateVal) {
                    endDateError.text('Ngày kết thúc không được để trống.');
                    $('#createEndDate1').addClass('is-invalid');
                    isValid = false;
                }

                if (startDateVal && endDateVal) {
                    const start = new Date(startDateVal);
                    const end = new Date(endDateVal);
                    if (start >= end) {
                        endDateError.text('Ngày kết thúc phải sau ngày bắt đầu.');
                        $('#createEndDate1').addClass('is-invalid');
                        isValid = false;
                    }
                }

                // Ngăn submit nếu có lỗi
                if (!isValid) {
                    e.preventDefault();
                    return
                }

                $('#createSemesterForm').submit();
            });

            $('tbody').on('click', '.editSemesterBtn', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const schoolYear = $(this).data('school_year');
                const startDate = $(this).data('start_date');
                const endDate = $(this).data('end_date');
                const currentPage = $(this).data('current-page');
                const search = $(this).data('search');

                // Cập nhật action của form
                $('#editSemesterForm').attr('action',
                    `/student-affairs-department/semester/edit-semester/${id}`);

                $('#editSemesterId2').val(id);
                $('#editSemesterName2').val(name);
                $('#editSemesterSchoolYear2').val(schoolYear);
                $('#editStartDate2').val(startDate);
                $('#editEndDate2').val(endDate);
                $('.current_page').val(currentPage);
                $('.search_keyword').val(search);
            });

            $('.editSemesterBtnSubmit').on('click', function (e) {
                let isValid = true;

                $('.text-danger-error').text('');
                $('.form-control').removeClass('is-invalid');

                // Validate start_date
                const startDateVal = $('#editStartDate2').val().trim();
                const startDateError = $('#start_date2');
                if (!startDateVal) {
                    startDateError.text('Ngày bắt đầu không được để trống.');
                    $('#editStartDate2').addClass('is-invalid');
                    isValid = false;
                }

                // Validate end_date
                const endDateVal = $('#editEndDate2').val().trim();
                const endDateError = $('#end_date2');
                if (!endDateVal) {
                    endDateError.text('Ngày kết thúc không được để trống.');
                    $('#editEndDate2').addClass('is-invalid');
                    isValid = false;
                }

                if (startDateVal && endDateVal) {
                    const start = new Date(startDateVal);
                    const end = new Date(endDateVal);
                    if (start >= end) {
                        endDateError.text('Ngày kết thúc phải sau ngày bắt đầu.');
                        $('#editEndDate2').addClass('is-invalid');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    return
                }

                $('#editSemesterForm').submit();
            });

            $('.deleteSemster').on('click', function (e) {
                e.preventDefault();
                const semesterId = $(this).data('id');
                const currentPage = $(this).data('current-page');
                const search = $(this).data('search');

                $('.current_page').val(currentPage);
                $('.search_keyword').val(search);
                $('#deleteSemesterForm').attr('action',
                    `/student-affairs-department/semester/${semesterId}`);
            });

        });
    </script>
@endpush
