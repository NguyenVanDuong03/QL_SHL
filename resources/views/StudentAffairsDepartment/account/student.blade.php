@extends('layouts.studentAffairsDepartment')

@section('title', 'Tài khoản GV & SV')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Tài khoản GV & SV']]" />
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
    </style>
@endpush

@section('main')
    <!-- Include the tab navigation -->
    @include('StudentAffairsDepartment.account.tabs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold d-none d-md-block">Tài khoản sinh viên</span>
                            <div class="d-flex">
                                <div class="input-group me-2" style="width: 250px;">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm sinh viên..." id="studentSearch">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('student-affairs-department.account.importStudent') }}" enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <label for="studentExcelFile" class="btn btn-sm btn-success me-2 mb-0">
                                        <i class="fas fa-file-excel me-1"></i> Import Excel
                                        <input type="file" id="studentExcelFile" class="d-none" name="studentExcelFile" accept=".xlsx, .xls">
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 columns-acctount">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">STT</th>
                                        <th>MSSV</th>
                                        <th>Họ và tên</th>
                                        <th>ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Email</th>
                                        <th style="width: 100px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
                                @if($data['students']['total'] == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">Không có dữ liệu sinh viên</td>
                                    </tr>
                                @else
                                    @foreach ($data['students']['data'] as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student['student_code'] }}</td>
                                            <td>{{ $student['user']['name'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}</td>
                                            <td>{{ $student['user']['gender'] }}</td>
                                            <td>{{ $student['user']['email'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info btn-show-student" title="Xem chi tiết"
                                                        data-id="{{ $student['id'] }}"
                                                        data-name="{{ $student['user']['name'] }}"
                                                        data-email="{{ $student['user']['email'] }}"
                                                        data-birth="{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}"
                                                        data-gender="{{ $student['user']['gender'] }}"
                                                        data-code="{{ $student['student_code'] }}"
                                                        data-position="{{ $student['position'] }}"
                                                        data-class="{{ $student['study_class']['name'] }}"
                                                        data-cohort="{{ $student['cohort']['name'] }}"
                                                        data-phone="{{ $student['user']['phone'] }}"
                                                        data-bs-toggle="modal" data-bs-target="#showModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <x-pagination.pagination :paginate="$data['students']" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- show teacher --}}
    <div class="modal fade auto-reset-modal" id="showModal" tabindex="-1" aria-labelledby="showModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Thông tin chi tiết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong>
                            <span class="student-name"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Mã sinh viên</strong>
                            <span class="student-code"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Email</strong>
                            <span class="student-email"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày sinh:</strong>
                            <span class="student-birth"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong>
                            <span class="student-gender"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <span class="student-phone"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Niên khóa:</strong>
                            <span class="student-cohort"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Lớp:</strong>
                            <span class="student-class"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Vai trò:</strong>
                            <span class="student-position"></span>
                        </div>
{{--                        <div class="col-md-6">--}}
{{--                            <strong>Khoa:</strong>--}}
{{--                            <span class="student-deparment"></span>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay lại</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // $('.auto-reset-modal').on('hidden.bs.modal', function() {
            //     $(this).find('form')[0].reset();
            // });

            $('#studentExcelFile').on('change', function() {
                if (this.files.length > 0) {
                    $(this).closest('form').submit();
                }
            });

            $('.btn-show-student').on('click', function() {
                const student = $(this).data();
                $('.student-name').text(student.name);
                $('.student-code').text(student.code);
                $('.student-email').text(student.email);
                $('.student-birth').text(student.birth);
                $('.student-gender').text(student.gender);
                $('.student-phone').text(student.phone);
                $('.student-cohort').text(student.cohort);
                $('.student-class').text(student.class);

                if(student.position == 0)
                    $('.student-position').text('Sinh viên');
                else if(student.position == 1)
                    $('.student-position').text('Lớp trưởng');
                else if(student.position == 2)
                    $('.student-position').text('Lớp phó');
                else if(student.position == 3)
                    $('.student-position').text('Bí thư');
                else
                    $('.student-position').text('---');
                // $('.student-deparment').text(student.deparment);

            });
        });
    </script>
@endpush
