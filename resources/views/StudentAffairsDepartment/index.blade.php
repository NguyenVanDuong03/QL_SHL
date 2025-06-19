@extends('layouts.studentAffairsDepartment')

@section('title', 'Trang chủ')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Trang chủ']]"/>
@endsection

@push('styles')
    <style>
        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
        }

        .col-lg-4,
        .col-lg-8 {
            display: flex;
            flex-direction: column;
        }

        .stats-card,
        .chart-card {
            flex: 1 1 auto;
            height: 100%;
            max-height: 505px;
            display: flex;
            flex-direction: column;
        }

        .chart-container {
            flex: 1 1 auto;
            min-height: 300px;
        }

        /* Rest of your existing styles */
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }

        .card-header-custom {
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-list {
            max-height: 500px;
            overflow-y: auto;
            flex: 1 1 auto;
            padding: 0;
        }

        .stats-item {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .stats-class {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }

        .stats-date {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
        }

        .stats-content {
            font-size: 0.9rem;
            color: #495057;
            line-height: 1.4;
        }

        .stats-content p {
            margin-bottom: 0.25rem;
        }

        .stats-actions {
            display: flex;
            gap: 0.5rem;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }

        .chart-controls {
            display: flex;
            align-items: center;
        }

        .chart-controls .form-select {
            width: auto;
            min-width: 120px;
        }

        .chart-container {
            padding: 1.5rem;
            height: 300px;
        }

        .chart-summary {
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .summary-item {
            padding: 0.5rem;
        }

        .summary-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .summary-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.35em 0.65em;
        }

        .view-all-btn {
            border: none;
            border-radius: 25px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .stats-item {
                padding: 1rem;
            }

            .chart-container {
                height: 250px;
                padding: 1rem;
            }

            .card-header-custom {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .summary-number {
                font-size: 1.25rem;
            }

            /* Adjust for responsive layout */
            .col-lg-4,
            .col-lg-8 {
                flex: 1 1 100%;
                max-width: 100%;
            }

            .stats-card,
            .chart-card {
                height: auto; /* Allow natural height in mobile view */
            }
        }

        .stats-list::-webkit-scrollbar {
            width: 6px;
        }

        .stats-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .stats-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .stats-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endpush

@section('main')
    <!-- Page Content -->
    <div class="container-fluid py-4">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header d-flex align-items-start">
                        <div class="stat-card-title text-uppercase">LỚP CHỦ NHIỆM {{ $data['semester']['name'] }}
                            - {{ $data['semester']['school_year'] }}</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalStudyClasses'] }} lớp</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header d-flex align-items-start">
                        <div class="stat-card-title text-uppercase">SINH VIÊN ĐĂNG BÁO HỌC
                            VỤ {{ $data['semester']['name'] }} - {{ $data['semester']['school_year'] }}</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalAcademicWarnings'] ?? 0 }} sinh viên</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header d-flex align-items-start">
                        <div class="stat-card-title text-uppercase">BÁO CÁO SINH HOẠT
                            LỚP {{ $data['semester']['name'] }} - {{ $data['semester']['school_year'] }}</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalClassSessionReports'] ?? 0 }} báo cáo</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header d-flex align-items-start">
                        <div class="stat-card-title text-uppercase">ĐIỂM RÈN LUYỆN {{ $data['semester']['name'] }}
                            - {{ $data['semester']['school_year'] }}</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalClassSessionReports'] ?? 0 }} sinh viên</div>
                </div>
            </div>
        </div>

        <!-- Statistics and Charts -->
        <div class="row g-4">
            <!-- Class Activities Pending Approval -->
            <div class="col-lg-4">
                <div class="stats-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-clock text-primary me-2"></i>Sinh hoạt lớp cần duyệt</h5>
                        <span class="badge bg-danger">{{ count($data['getAllClassSession']) ?? 0 }}</span>
                    </div>

                    <div class="stats-list">
                        <!-- Pending Item 1 -->
                        @forelse($data['getAllClassSession'] ?? [] as $item)
                            <div class="stats-item">
                                <div class="stats-header">
                                    <div class="stats-class">Lớp {{ $item['study_class']['name'] }}</div>
                                    @php
                                        $typeClass = $item['type'] == 0 ? 'bg-primary' : 'bg-success';
                                    @endphp
                                    <span class="badge {{ $typeClass }}">{{ $item['type'] == 0 ? 'Cố định' : 'Linh hoạt' }}</span>
                                </div>
                                <div class="stats-date">
                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($item['proposed_at'])->format('H:i d/m/Y') }}
                                </div>
                                <div class="stats-content">
                                    <p class="mb-1 title_cut"><strong>Chủ đề:</strong> {{ $item['title'] }}</p>
                                    <p class="mb-1"><strong>Hình thức:</strong> {{ $item['position'] == 0 ? 'Trực tiếp' : ($item['position'] == 1 ? 'Trực tuyến' : 'Dã ngoại') }}</p>
                                    <p class="mb-0"><strong>GVCN:</strong> {{ $item['lecturer']['user']['name'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <p class="text-center text-muted">Không có buổi sinh hoạt lớp nào cần duyệt.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- View All Button -->
                    <div class="text-center mt-3 p-3 border-top">
                        <a class="btn btn-primary view-all-btn" href="{{ route('student-affairs-department.class-session.index') }}">
                            <i class="fas fa-list me-2"></i>Xem tất cả
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <!-- Class Activity Types Statistics -->
                    <div class="col-12">
                        <div class="chart-card">
                            <div class="card-header-custom">
                                <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Thống kê Tổng số Buổi Sinh hoạt
                                    Lớp</h5>
                            </div>
                            <div class="chart-container">
                                <canvas id="activityTypesChart"></canvas>
                            </div>
                            <div class="chart-summary">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="summary-item">
                                            <div class="summary-number text-primary">{{ $data['countClassSession']['fixed'] ?? 0 }}</div>
                                            <div class="summary-label">Sinh hoạt cố định</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="summary-item">
                                            <div class="summary-number text-warning">{{ $data['countClassSession']['flexible'] ?? 0 }}</div>
                                            <div class="summary-label">Sinh hoạt linh hoạt</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="summary-item">
                                            <div class="summary-number text-success">{{ $data['countClassSession']['total'] ?? 0 }}</div>
                                            <div class="summary-label">Tổng cộng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Training Score Statistics -->
{{--                    <div class="col-12">--}}
{{--                        <div class="chart-card">--}}
{{--                            <div class="card-header-custom">--}}
{{--                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Thống kê Điểm Rèn luyện</h5>--}}
{{--                                --}}{{--                                <div class="chart-controls">--}}
{{--                                --}}{{--                                    <select class="form-select form-select-sm" id="scorePeriod" onchange="updateScoreChart()">--}}
{{--                                --}}{{--                                        <option value="semester">Theo học kỳ</option>--}}
{{--                                --}}{{--                                        <option value="year">Theo năm học</option>--}}
{{--                                --}}{{--                                        <option value="course">Theo khóa</option>--}}
{{--                                --}}{{--                                    </select>--}}
{{--                                --}}{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="chart-container">--}}
{{--                                <canvas id="trainingScoreChart"></canvas>--}}
{{--                            </div>--}}
{{--                            <div class="chart-summary">--}}
{{--                                <div class="row text-center">--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="summary-item">--}}
{{--                                            <div class="summary-number text-success">{{ $excellentCount ?? 342 }}</div>--}}
{{--                                            <div class="summary-label">Xuất sắc (90-100)</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="summary-item">--}}
{{--                                            <div class="summary-number text-info">{{ $goodCount ?? 578 }}</div>--}}
{{--                                            <div class="summary-label">Tốt (80-89)</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="summary-item">--}}
{{--                                            <div class="summary-number text-warning">{{ $averageCount ?? 234 }}</div>--}}
{{--                                            <div class="summary-label">Khá (65-79)</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="summary-item">--}}
{{--                                            <div class="summary-number text-danger">{{ $belowAverageCount ?? 46 }}</div>--}}
{{--                                            <div class="summary-label">Yếu (<65)</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".title_cut").each(function () {
                var text = $(this).text().trim();
                if (text.length > 20) {
                    $(this).attr("title", text);
                    $(this).text(text.substring(0, 20) + '...');
                }
            });

            // Initialize charts
            initializeActivityChart();
        });

        function initializeActivityChart() {
            const ctx = document.getElementById('activityTypesChart').getContext('2d');
            const data = @json($data['countClassSession']);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Sinh hoạt cố định', 'Sinh hoạt linh hoạt'],
                    datasets: [{
                        data: [data['fixed'], data['flexible']],
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(255, 193, 7, 0.8)'
                        ],
                        borderColor: [
                            'rgba(102, 126, 234, 1)',
                            'rgba(255, 193, 7, 1)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' buổi (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Event handlers
        function approveActivity(id) {
            if (confirm('Bạn có chắc chắn muốn duyệt buổi sinh hoạt này?')) {
                // Simulate approval
                const item = $(`.stats-item:nth-child(${id})`);
                item.fadeOut(300, function () {
                    $(this).remove();
                    updatePendingCount();
                });
            }
        }

        function viewDetail(id) {
            // Simulate view detail

        }

        function viewAllPending() {
            // Simulate view all

        }

        function updateActivityChart() {
            const period = $('#activityPeriod').val();
            // Simulate data update based on period

        }

        function updateScoreChart() {
            const period = $('#scorePeriod').val();
            // Simulate data update based on period

        }

        function updatePendingCount() {
            const currentCount = $('.stats-list .stats-item').length;
            $('.card-header-custom .badge').text(currentCount);
        }
    </script>
@endpush
