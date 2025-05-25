@extends('layouts.teacher')

@section('title', 'Thông tin lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Lớp học', 'url' => 'teacher.class.index'], ['label' => 'Thông tin lớp học']]" />
@endsection

@push('styles')
    <style>
        .resize-none {
            resize: none;
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <!-- Header with search -->
        <div class="d-flex justify-content-between mb-4">
            <div class="d-flex align-items-center justify-content-start flex-column">
                <a href="{{ route('teacher.class.index') }}">
                    <i class="fas fa-arrow-left-long"></i>
                </a>
            </div>
            <div class="input-group" style="max-width: 300px;">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Tìm kiếm lớp học" aria-label="Recipient's username"
                        aria-describedby="basic-addon2">
                    <button class="input-group-text" id="basic-addon2"><i class="fas fa-magnifying-glass"></i></button>
                </div>
                <div class="d-flex align-items-center justify-content-between w-100">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noteModal">
                        Sinh viên cần theo dõi
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ClassModal">
                        Cán sự lớp
                    </button>
                </div>
            </div>
        </div>

        <h3 class="text-center my-4">Lớp {{ $data['classInfo']->name }}</h3>
        <!-- Class cards grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4">
            @foreach ($data['students']['data'] as $student)
                <div class="col class-card">
                    <div class="card shadow-sm h-100 position-relative">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $student['user']['name'] }}</h5>
                            <p class="card-text m-0">Email: {{ $student['user']['email'] }}</p>
                            <p class="card-text m-0">Giới tính: {{ $student['user']['gender'] == 0 ? 'Nam' : 'Nữ' }}</p>
                            <p class="card-text m-0">Ngày sinh:
                                {{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}</p>
                            <p class="card-text m-0">Số điện thoại: {{ $student['user']['phone'] }}</p>
                            <p class="card-text m-0">Chức vụ:
                                {{ $student['position'] == 0 ? 'Sinh viên' : ($student['position'] == 1 ? 'Lớp trưởng' : ($student['position'] == 2 ? 'Lớp phó' : 'Bí thư')) }}
                            </p>
                            {{-- <div class="text-end position-absolute" style="top: 10px; right: 10px;">
                                <input class="" type="checkbox" id="checkStudent{{ $student['id'] }}"
                                    name="checkStudent{{ $student['id'] }}" value="{{ $student['id'] }}">
                                <p class="text-danger">Chú ý</p>
                            </div> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{-- <div class="d-flex justify-content-end">
            @include('components.pagination.pagination', [
                'paginate' => $data['students'],
            ])
        </div> --}}
        <x-pagination.pagination :paginate="$data['students']" />
    </div>

    <!-- Note Class Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" >
        <div class="modal-dialog modal-lg"> <!-- Made the modal larger -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Danh sách sinh viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form id="studentNotesForm">
                        <div class="student-notes-container" style="height: 450px; overflow-y: auto; padding: 1rem;">
                            <div class="student-note-item mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="note-1" class="form-label mb-0 fw-bold">Nguyễn Văn A</label>
                                    <span class="badge bg-primary">MSSV: 20210001</span>
                                </div>
                                <textarea name="note-1" id="note-1" class="form-control resize-none" rows="3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi velit facere ullam nihil laudantium quam autem maxime dignissimos sed quas, libero, optio corporis sit aspernatur rem, necessitatibus facilis quos expedita!</textarea>
                                <div class="invalid-feedback">Vui lòng nhập ghi chú.</div>
                            </div>

                            <div class="student-note-item mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="note-2" class="form-label mb-0 fw-bold">Nguyễn Văn B</label>
                                    <span class="badge bg-primary">MSSV: 20210002</span>
                                </div>
                                <textarea name="note-2" id="note-2" class="form-control resize-none" rows="3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi velit facere ullam nihil laudantium quam autem maxime dignissimos sed quas, libero, optio corporis sit aspernatur rem, necessitatibus facilis quos expedita!</textarea>
                                <div class="invalid-feedback">Vui lòng nhập ghi chú.</div>
                            </div>

                            <div class="student-note-item mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="note-3" class="form-label mb-0 fw-bold">Nguyễn Văn C</label>
                                    <span class="badge bg-primary">MSSV: 20210003</span>
                                </div>
                                <textarea name="note-3" id="note-3" class="form-control resize-none" rows="3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi velit facere ullam nihil laudantium quam autem maxime dignissimos sed quas, libero, optio corporis sit aspernatur rem, necessitatibus facilis quos expedita!</textarea>
                                <div class="invalid-feedback">Vui lòng nhập ghi chú.</div>
                            </div>

                            <div class="student-note-item mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="note-4" class="form-label mb-0 fw-bold">Nguyễn Văn D</label>
                                    <span class="badge bg-primary">MSSV: 20210004</span>
                                </div>
                                <textarea name="note-4" id="note-4" class="form-control resize-none" rows="3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi velit facere ullam nihil laudantium quam autem maxime dignissimos sed quas, libero, optio corporis sit aspernatur rem, necessitatibus facilis quos expedita!</textarea>
                                <div class="invalid-feedback">Vui lòng nhập ghi chú.</div>
                            </div>

                            <div class="student-note-item mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="note-5" class="form-label mb-0 fw-bold">Nguyễn Văn E</label>
                                    <span class="badge bg-primary">MSSV: 20210005</span>
                                </div>
                                <textarea name="note-5" id="note-5" class="form-control resize-none" rows="3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi velit facere ullam nihil laudantium quam autem maxime dignissimos sed quas, libero, optio corporis sit aspernatur rem, necessitatibus facilis quos expedita!</textarea>
                                <div class="invalid-feedback">Vui lòng nhập ghi chú.</div>
                            </div>

                            <div class="student-note-item mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="note-6" class="form-label mb-0 fw-bold">Nguyễn Văn F</label>
                                    <span class="badge bg-primary">MSSV: 20210006</span>
                                </div>
                                <textarea name="note-6" id="note-6" class="form-control resize-none" rows="3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi velit facere ullam nihil laudantium quam autem maxime dignissimos sed quas, libero, optio corporis sit aspernatur rem, necessitatibus facilis quos expedita!</textarea>
                                <div class="invalid-feedback">Vui lòng nhập ghi chú.</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <span class="me-auto text-muted small">Tổng số: 6 sinh viên</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveNotesBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Modal -->
    <div class="modal fade" id="ClassModal" tabindex="-1" aria-labelledby="ClassModalLabel" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ClassModalLabel">Cán sự lớp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm">
                        <div class="mb-3">
                            <label for="classCode" class="form-label">Lớp trưởng</label>
                            <select class="form-select" id="classCode" name="classCode" required>
                                <option value="">Nguyễn Văn A</option>
                                <option value="">Nguyễn Văn B</option>
                                <option value="">Nguyễn Văn C</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="className" class="form-label">Lớp phó</label>
                            <select class="form-select" id="className" name="className" required>
                                <option value="">Nguyễn Văn A</option>
                                <option value="">Nguyễn Văn B</option>
                                <option value="">Nguyễn Văn C</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="studentCount" class="form-label">Bí thứ</label>
                            <select class="form-select" id="studentCount" name="studentCount" required>
                                <option value="">Nguyễn Văn A</option>
                                <option value="">Nguyễn Văn B</option>
                                <option value="">Nguyễn Văn C</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveClassBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
