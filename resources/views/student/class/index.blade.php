@extends('layouts.student')

@section('title', 'Lớp học')

@push('styles')
    <style>
        .resize-none {
            resize: none;
        }

        .bg-pink {
            background-color: #e91e63 !important;
        }

        .tooltip-note {
            display: none;
            position: absolute;
            top: -5px;
            left: 90px;
            transform: translateY(-100%);
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 200px;
        "
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <!-- Header with navigation and actions -->
        <div class="d-md-flex justify-content-end align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <!-- Search -->
                <form class="input-group" method="GET"
                      action="{{ route('student.class.index') }}"
                      style="max-width: 250px;">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm tên, email, ID..."
                           aria-label="Search" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Students table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách sinh viên {{ $data['studyClassName'] }}</h5>
                    <span class="badge bg-primary">{{ $data['students']['total'] }} sinh viên</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên sinh viên</th>
                            <th scope="col" class="d-none d-md-table-cell">Email</th>
                            <th scope="col" class="d-none d-md-table-cell">Giới tính</th>
                            <th scope="col" class="d-none d-md-table-cell">Ngày sinh</th>
                            <th scope="col" class="d-none d-md-table-cell">Số điện thoại</th>
                            <th scope="col">Chức vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($data['students']['data'] ?? [] as $index => $student)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <div class="fw-medium">{{ $student['user']['name'] ?? '---' }} <br>
                                        <small class="text-muted">ID: {{ $student['student_code'] }}</small>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $student['user']['email'] ?? '---' }}</td>
                                <td class="d-none d-md-table-cell">
                                        <span
                                            class="badge {{ $student['user']['gender'] == 'Nam' ? 'bg-info' : 'bg-pink' }}">
                                            {{ $student['user']['gender'] == 'Nam' ? 'Nam' : 'Nữ' }}
                                        </span>
                                </td>
                                <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($student['user']['date_of_birth'])->format('d/m/Y') }}</td>
                                <td class="d-none d-md-table-cell">{{ $student['user']['phone'] }}</td>
                                <td>
                                    @php
                                        $positions = [
                                            0 => ['text' => 'Sinh viên', 'class' => 'bg-secondary'],
                                            1 => ['text' => 'Lớp trưởng', 'class' => 'bg-danger'],
                                            2 => ['text' => 'Lớp phó', 'class' => 'bg-warning'],
                                            3 => ['text' => 'Bí thư', 'class' => 'bg-success']
                                        ];
                                        $position = $positions[$student['position']] ?? $positions[0];
                                    @endphp
                                    <span class="badge {{ $position['class'] }}">{{ $position['text'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Không tìm thấy sinh viên có thông tin <span class="fw-bold">{{ request('search') }}</span></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <x-pagination.pagination :paginate="$data['students']"/>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
