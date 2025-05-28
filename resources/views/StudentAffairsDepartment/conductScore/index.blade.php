@extends('layouts.studentAffairsDepartment')

@section('title', 'Điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Điểm rèn luyện']]" />
@endsection

@section('main')
    <div class="container-fluid mt-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-end justify-content-md-between align-items-center">
                    <h2 class="flex-grow-1 mb-0 d-none d-md-block">Danh sách điểm rèn luyện</h2>
                    <div class="d-flex gap-2">
                        <form class="position-relative">
                            <input type="text" class="form-control" placeholder="Tìm kiếm" name="search"
                                value="{{ request()->get('search') }}"
                                style="width: 250px; padding-right: 40px;">
                            <i class="fas fa-search position-absolute"
                                style="right: 12px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                        </form>
                        <button class="btn btn-primary px-3" data-bs-target="#confirmCreateModal" data-bs-toggle="modal">Tạo
                            mới</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Cards Grid -->
        <div class="row g-4 mt-2">
            <!-- Card 1 -->
            @foreach ($data['ConductEvaluationPeriods']['data'] as $conductEvaluationPeriod)
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 shadow-sm" style="border-radius: 12px;">
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="card-title fw-bold text-dark mb-0">
                                    {{ $conductEvaluationPeriod['semester']['name'] }} -
                                    {{ $conductEvaluationPeriod['semester']['school_year'] }}
                                </h6>
                                <a class="btn btn-primary btn-sm rounded-circle p-2" href="{{ route('student-affairs-department.conduct-score.infoConductScore', $conductEvaluationPeriod['id']) }}"
                                    style="width: 35px; height: 35px;">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <div class="text-muted small">
                                <div>Thời gian bắt đầu:
                                    {{ \Carbon\Carbon::parse($conductEvaluationPeriod['open_date'])->format('H:i d/m/Y') }}
                                </div>
                                <div>Thời gian kết thúc:
                                    {{ \Carbon\Carbon::parse($conductEvaluationPeriod['end_date'])->format('H:i d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tạo mới -->
                <div class="modal fade auto-reset-modal" id="confirmCreateModal" tabindex="-1"
                    aria-labelledby="confirmCreateModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="modal-title fw-bold" id="createModalLabel">Đăng ký đánh giá điểm rèn luyện
                                    </h5>
                                    <button type="button" class="btn btn-outline-danger btnReset" id="btnReset">Đặt
                                        lại</button>
                                </div>

                                <form id="createform" method="POST"
                                    action="{{ route('student-affairs-department.conduct-score.create') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="mb-3">
                                        <label for="semester_id" class="form-label">Học kỳ</label>
                                        <select class="form-select" id="semester_id" name="semester_id" required>
                                            @if (empty($data['semesters']))
                                                <option disabled selected>Không có học kỳ nào</option>
                                            @else
                                                @foreach ($data['semesters'] as $semester)
                                                    <option value="{{ $semester->id }}">{{ $semester->name }} -
                                                        {{ $semester->school_year }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="semester_id_error" class="text-danger text-danger-error"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="open_date" class="form-label">Thời gian bắt đầu</label>
                                        <input type="text" class="form-control" id="open_date" name="open_date"
                                            placeholder="Chọn thời gian bắt đầu" onfocus="(this.type='datetime-local')"
                                            onblur="if(!this.value)this.type='text'" required
                                            min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                        <div id="open_date_error" class="text-danger text-danger-error"></div>
                                        @error('open_date')
                                            <div class="text-danger text-danger-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Thời gian kết thúc</label>
                                        <input type="text" class="form-control" id="end_date" name="end_date"
                                            placeholder="Chọn thời gian kết thúc" onfocus="(this.type='datetime-local')"
                                            onblur="if(!this.value)this.type='text'" required
                                            min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                                        <div id="end_date_error" class="text-danger text-danger-error"></div>
                                        @error('end_date')
                                            <div class="text-danger text-danger-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-center gap-3 mt-4">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                            style="width: 120px;">Quay lại</button>
                                        <button type="submit" class="btn btn-primary btn-create-submit"
                                            style="width: 120px;">Đăng ký</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <x-pagination.pagination :paginate="$data['ConductEvaluationPeriods']" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Reset form when modal is closed
            $('.auto-reset-modal').on('hidden.bs.modal', function() {
                $('.text-danger-error').text('');
                $(this).find('form')[0].reset();
            });

            // Reset form when button is clicked
            $('.btnReset').click(function() {
                $('.text-danger-error').text('');
                $('#createform')[0].reset();
            });

            $('.btn-create-submit').on('click', function(e) {
                e.preventDefault();

                let openDate = $('#open_date').val();
                let endDate = $('#end_date').val();
                let semesterId = $('#semester_id').val();

                $('#open_date_error').text('');
                $('#end_date_error').text('');

                if (semesterId == null) {
                    $('#semester_id_error').text('Vui lòng tạo học kỳ trước khi tạo lịch sinh hoạt lớp');
                    return;
                }

                if (!openDate || !endDate) {
                    if (!openDate) $('#open_date_error').text('Vui lòng chọn thời gian bắt đầu');
                    if (!endDate) $('#end_date_error').text('Vui lòng chọn thời gian kết thúc');
                    return;
                }

                if (new Date(openDate) >= new Date(endDate)) {
                    $('#end_date_error').text('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    return;
                }

                $('#createform').submit();
            });

            $('.btn-edit-submit').on('click', function(e) {
                e.preventDefault();

                let openDate = $('#open_date_edit').val();
                let endDate = $('#end_date_edit').val();
                let semesterId = $('#semester_id_edit').val();

                $('#open_date_error_edit').text('');
                $('#end_date_error_edit').text('');

                if (semesterId == null) {
                    $('#semester_id_error-edit').text(
                        'Vui lòng tạo học kỳ trước khi tạo lịch sinh hoạt lớp');
                    return;
                }

                if (!openDate || !endDate) {
                    if (!openDate) $('#open_date_error_edit').text('Vui lòng chọn thời gian bắt đầu');
                    if (!endDate) $('#end_date_error_edit').text('Vui lòng chọn thời gian kết thúc');
                    return;
                }

                if (new Date(openDate) >= new Date(endDate)) {
                    $('#end_date_error_edit').text('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    return;
                }

                $('#editform').submit();
            });

        });
    </script>
@endpush
