@extends('layouts.teacher')

@section('title', 'Điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Điểm rèn luyện']]" />
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
                    <h4 class="flex-grow-1 mb-0 d-none d-md-block">Danh sách điểm rèn luyện</h4>
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

        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 80px;">STT</th>
                                    <th scope="col">Học kỳ</th>
                                    <th scope="col">Thời gian bắt đầu</th>
                                    <th scope="col">Thời gian kết thúc</th>
                                    <th scope="col" class="text-center" style="width: 200px;">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($data['ConductEvaluationPeriods']['data'] as $index => $conductEvaluationPeriod)
                                    <tr>
                                        <td class="text-center fw-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="fw-semibold">
                                            {{ $conductEvaluationPeriod['semester']['name'] }} -
                                            {{ $conductEvaluationPeriod['semester']['school_year'] }}
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($conductEvaluationPeriod['open_date'])->format('H:i d/m/Y') }}
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                            {{ \Carbon\Carbon::parse($conductEvaluationPeriod['end_date'])->format('H:i d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('teacher.conduct-score.infoConductScore', ['conduct_evaluation_period_id' => $conductEvaluationPeriod['id']]) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                            Không có dữ liệu
                                        </td>
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
        <div class="row mt-4">
            <div class="col-12">
                <x-pagination.pagination :paginate="$data['ConductEvaluationPeriods']" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush


