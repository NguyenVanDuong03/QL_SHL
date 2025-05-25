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
                            <span class="text-primary fw-bold">Tài khoản sinh viên</span>
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
                                    @foreach ($data['students']['data'] as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student['student_code'] }}</td>
                                            <td>{{ $student['user']['name'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}</td>
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
                        <x-pagination.pagination :paginate="$data['students']" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#studentExcelFile').on('change', function() {
                if (this.files.length > 0) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endpush
