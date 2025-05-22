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

        <!-- Class cards grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4">
            @foreach ($data['classes']['data'] as $class)
                <div class="col class-card">
                    <div class="card shadow-sm h-100 position-relative">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $class['name'] }}</h5>
                            <p class="card-text">Sĩ số: {{ count($class['students']) }} sinh viên</p>
                            <a class="btn btn-primary position-absolute {{ count($class['students']) > 0 ? '' : 'disabled' }}" href="{{ route('teacher.class.infoStudent', ['id' => $class['id']]) }}"
                                style="top: 10px; right: 10px; border-radius: 5px;">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end">
            @include('components.pagination.pagination', [
                'paginate' => $data['classes'],
            ])
        </div>
    </div>

    {{-- <!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClassModalLabel">Thêm lớp mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addClassForm">
                    <div class="mb-3">
                        <label for="classCode" class="form-label">Mã lớp</label>
                        <input type="text" class="form-control" id="classCode" name="classCode" required>
                        <div class="invalid-feedback">Vui lòng nhập mã lớp.</div>
                    </div>
                    <div class="mb-3">
                        <label for="className" class="form-label">Tên lớp</label>
                        <input type="text" class="form-control" id="className" name="className" required>
                        <div class="invalid-feedback">Vui lòng nhập tên lớp.</div>
                    </div>
                    <div class="mb-3">
                        <label for="studentCount" class="form-label">Sĩ số</label>
                        <input type="number" class="form-control" id="studentCount" name="studentCount" min="1" required>
                        <div class="invalid-feedback">Vui lòng nhập sĩ số lớp.</div>
                    </div>
                    <div class="mb-3">
                        <label for="classYear" class="form-label">Năm học</label>
                        <select class="form-select" id="classYear" name="classYear" required>
                            <option value="">-- Chọn năm học --</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2022-2023">2022-2023</option>
                            <option value="2021-2022">2021-2022</option>
                        </select>
                        <div class="invalid-feedback">Vui lòng chọn năm học.</div>
                    </div>
                    <div class="mb-3">
                        <label for="classSemester" class="form-label">Học kỳ</label>
                        <select class="form-select" id="classSemester" name="classSemester" required>
                            <option value="">-- Chọn học kỳ --</option>
                            <option value="1">Học kỳ 1</option>
                            <option value="2">Học kỳ 2</option>
                        </select>
                        <div class="invalid-feedback">Vui lòng chọn học kỳ.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveClassBtn">Lưu</button>
            </div>
        </div>
    </div>
</div> --}}
@endsection
