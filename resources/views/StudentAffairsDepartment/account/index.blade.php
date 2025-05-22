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
                            <span class="text-primary fw-bold">Tài khoản giáo viên</span>
                            <div class="d-flex">
                                <div class="input-group me-2" style="width: 250px;">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm giáo viên..."
                                        id="teacherSearch">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <form method="POST"
                                    action="{{ route('student-affairs-department.account.importLecturer') }}"
                                    enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <label for="teacherExcelFile" class="btn btn-sm btn-success me-2 mb-0">
                                        <i class="fas fa-file-excel me-1"></i> Import Excel
                                        <input type="file" id="teacherExcelFile" name="teacherExcelFile" class="d-none"
                                            accept=".xlsx, .xls">
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
                                        <th>Họ và tên</th>
                                        <th>Ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Email</th>
                                        <th style="width: 100px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="teacherTableBody">
                                    @foreach ($lecturers['data'] as $lecturer)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $lecturer['user']['name'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lecturer['user']['date_of_birth'])->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $lecturer['user']['gender'] }}</td>
                                            <td>{{ $lecturer['user']['email'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Hiển thị {{ $lecturers['from'] }}-{{ $lecturers['to'] }} của {{ $lecturers['total'] }}
                                mục</span>
                            <x-pagination.pagination :paginate="$lecturers" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('main')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h4 mb-0">Danh sách tài khoản sinh viên</h3>
                    <div class="d-flex">
                        <div class="input-group me-2" style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Tìm kiếm sinh viên..." id="studentSearch">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('student-affairs-department.account.importStudent') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">Tài khoản sinh viên</span>
                                <div>
                                    <label for="studentExcelFile" class="btn btn-sm btn-success me-2">
                                        <i class="fas fa-file-excel me-1"></i> Import Excel
                                        <input type="file" id="studentExcelFile" class="d-none" name="studentExcelFile"
                                            accept=".xlsx, .xls">
                                    </label>
                                </div>
                            </div>
                        </div>
                </form>
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-y: auto;">
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
                                @foreach ($data['students']['data'] as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student['student_code'] }}</td>
                                        <td>{{ $student['user']['name'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $student['user']['gender'] }}</td>
                                        <td>{{ $student['user']['email'] }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Hiển thị {{ $data['students']['from'] }}-{{ $data['students']['to'] }} của
                            {{ $data['students']['total'] }} mục</span>
                        <x-pagination.pagination :paginate="$data['students']" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h4 mb-0">Danh sách tài khoản giáo viên</h3>
                <div class="d-flex">
                    <div class="input-group me-2" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Tìm kiếm giáo viên..." id="teacherSearch">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <form method="POST" action="{{ route('student-affairs-department.account.importLecturer') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-header py-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">Tài khoản giáo viên</span>
                            <div>
                                <label for="teacherExcelFile" class="btn btn-sm btn-success me-2">
                                    <i class="fas fa-file-excel me-1"></i> Import Excel
                                    <input type="file" id="teacherExcelFile" name="teacherExcelFile" class="d-none"
                                        accept=".xlsx, .xls">
                                </label>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-y: auto;">
                        <table class="table table-hover table-striped mb-0 columns-acctount">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">STT</th>
                                    <th>Họ và tên</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
                                    <th>Email</th>
                                    <th style="width: 100px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="teacherTableBody">
                                @foreach ($data['lecturers']['data'] as $lecturer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lecturer['user']['name'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lecturer['user']['date_of_birth'])->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $lecturer['user']['gender'] }}</td>
                                        <td>{{ $lecturer['user']['email'] }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Hiển thị {{ $data['lecturers']['from'] }}-{{ $data['lecturers']['to'] }} của
                            {{ $data['lecturers']['total'] }} mục</span>
                        <x-pagination.pagination2 :paginate="$data['lecturers']" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#teacherExcelFile').on('change', function() {
                if (this.files.length > 0) {
                    $(this).closest('form').submit();
                }
            });

            // $('#studentExcelFile').on('change', function() {
            //     if (this.files.length > 0) {
            //         $(this).closest('form').submit();
            //     }
            // });
        });
    </script>
@endpush
