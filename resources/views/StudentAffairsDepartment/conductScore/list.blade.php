@extends('layouts.studentAffairsDepartment')

@section('title', 'Danh sách điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Điểm rèn luyện', 'url' => 'student-affairs-department.conduct-score.index'],
        ['label' => 'Danh sách điểm rèn luyện'],
    ]" />
@endsection

@push('styles')
    <style>

    </style>
@endpush

@section('main')
    <div class="container-fluid mt-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-end justify-content-md-between align-items-center">
                    <a href="{{ route('teacher.class-session.detailFixedClassActivitie') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <div class="d-flex gap-2">
                        <form class="position-relative">
                            <div class="input-group me-2" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Tìm kiếm..."
                                       id="search">
                                <button class="btn btn-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="facultyFilter" class="form-label">Khoa/Ngành</label>
                                <select class="form-select" id="facultyFilter">
                                    <option value="">Tất cả khoa/ngành</option>
                                    <option value="cntt">Công nghệ thông tin</option>
                                    <option value="kt">Kinh tế</option>
                                    <option value="nn">Ngoại ngữ</option>
                                    <option value="xd">Xây dựng</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">Trạng thái</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="completed">Đã hoàn thành</option>
                                    <option value="in_progress">Đang đánh giá</option>
                                    <option value="not_started">Chưa bắt đầu</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="yearFilter" class="form-label">Khóa</label>
                                <select class="form-select" id="yearFilter">
                                    <option value="">Tất cả khóa</option>
                                    <option value="k17">K17 (2020-2024)</option>
                                    <option value="k18">K18 (2021-2025)</option>
                                    <option value="k19">K19 (2022-2026)</option>
                                    <option value="k20">K20 (2023-2027)</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" id="filterBtn">
                                    <i class="fas fa-filter me-2"></i>Lọc
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="text-center mb-4 d-none d-md-block">Danh sách lớp học - Học kỳ 1 (2023-2024)</h4>
        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Lớp học</th>
                                    <th scope="col" class="d-none d-md-block">Ngành/Khoa</th>
                                    <th scope="col">Tổng sinh viên</th>
                                    <th scope="col">Đã đánh giá</th>
                                    <th scope="col">Chưa đánh giá</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Hardcoded data -->
                                @forelse($data['getStudyClassList']['data'] ?? [] as $item)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>{{ $item['study_class_name'] }}</td>
                                        <td class="d-none d-md-block">
                                            {{ $item['major_name'] }} <br>
                                            <small class="text-muted">{{ $item['department_name'] }}</small>
                                        </td>
                                        <td>{{ $item['total_students'] }}</td>
                                        <td class="text-center">{{ $item['has_evaluated'] }}</td>
                                        <td class="text-center">
                                            {{ $item['not_evaluated'] }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <a href="#" class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-outline-success btn-sm" title="Xuất Excel">
                                                    <i class="fas fa-file-excel"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <x-pagination.pagination :paginate="$data['getStudyClassList']" />

    <!-- Modal Xem Chi Tiết -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="detailModalLabel">Chi tiết lớp: Công nghệ thông tin 1 - K18</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Mã lớp:</label>
                                <p>CNTT01-K18</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Khoa/Ngành:</label>
                                <p>Công nghệ thông tin</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Số sinh viên:</label>
                                <p>42</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Giáo viên chủ nhiệm:</label>
                                <p>ThS. Nguyễn Văn A</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Trạng thái đánh giá:</label>
                                <p><span class="badge bg-success">Đã hoàn thành</span></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Ngày hoàn thành:</label>
                                <p>15/01/2024</p>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Thống kê điểm rèn luyện</h6>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">5</h3>
                                    <p class="mb-0">Xuất sắc</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">15</h3>
                                    <p class="mb-0">Tốt</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">18</h3>
                                    <p class="mb-0">Khá</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">4</h3>
                                    <p class="mb-0">Trung bình</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Danh sách sinh viên</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center">STT</th>
                                <th scope="col">MSSV</th>
                                <th scope="col">Họ và tên</th>
                                <th scope="col" class="text-center">Điểm</th>
                                <th scope="col" class="text-center">Xếp loại</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>2018010001</td>
                                <td>Nguyễn Văn An</td>
                                <td class="text-center">92</td>
                                <td class="text-center"><span class="badge bg-success">Tốt</span></td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>2018010002</td>
                                <td>Trần Thị Bình</td>
                                <td class="text-center">95</td>
                                <td class="text-center"><span class="badge bg-primary">Xuất sắc</span></td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>2018010003</td>
                                <td>Lê Văn Cường</td>
                                <td class="text-center">85</td>
                                <td class="text-center"><span class="badge bg-info">Khá</span></td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr><tr>
                                <td class="text-center">4</td>
                                <td>2018010004</td>
                                <td>Phạm Thị Dung</td>
                                <td class="text-center">78</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">Trung bình</span></td>
                            </tr>
                            <tr>
                                <td class="text-center">5</td>
                                <td>2018010005</td>
                                <td>Hoàng Văn Em</td>
                                <td class="text-center">88</td>
                                <td class="text-center"><span class="badge bg-info">Khá</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Xuất Excel
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Tooltip initialization
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Handle view detail button click
            $('.btn-outline-primary').not('.disabled').click(function(e) {
                e.preventDefault();
                $('#detailModal').modal('show');
            });

            // Handle filter button click
            $('#filterBtn').click(function() {
                const faculty = $('#facultyFilter').val();
                const status = $('#statusFilter').val();
                const year = $('#yearFilter').val();

                console.log('Filtering by:', { faculty, status, year });
                // Here you would normally make an AJAX request or submit a form
                // For demo purposes, we'll just show an alert
                alert('Đã áp dụng bộ lọc: ' +
                    (faculty ? 'Khoa: ' + $('#facultyFilter option:selected').text() : '') +
                    (status ? ', Trạng thái: ' + $('#statusFilter option:selected').text() : '') +
                    (year ? ', Khóa: ' + $('#yearFilter option:selected').text() : ''));
            });

            // Handle export all button click
            $('#exportAllBtn').click(function() {
                alert('Đang xuất dữ liệu tất cả các lớp...');
                // Here you would normally trigger the export functionality
            });

            // Add hover effects to table rows
            $('tbody tr').hover(
                function() {
                    $(this).addClass('table-active');
                },
                function() {
                    $(this).removeClass('table-active');
                }
            );
        });
    </script>
@endpush

