@extends('layouts.facultyOffice')

@section('title', 'Trang chủ')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Trang chủ']]" />
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
                        <div class="stat-card-title text-uppercase">LỚP CHỦ NHIỆM {{ $data['semester']['name'] ?? '---' }}
                            - {{ $data['semester']['school_year'] ?? '---' }}</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalStudyClasses'] ?? 0 }} lớp</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header d-flex align-items-start">
                        <div class="stat-card-title text-uppercase">SINH VIÊN ĐĂNG BÁO HỌC
                            VỤ {{ $data['semester']['name'] ?? '---' }} - {{ $data['semester']['school_year'] ?? '---' }}</div>
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
                            LỚP {{ $data['semester']['name'] ?? '---' }} - {{ $data['semester']['school_year'] ?? '---' }}</div>
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
                        <div class="stat-card-title text-uppercase">ĐIỂM RÈN LUYỆN {{ $data['semester']['name'] ?? '---' }}
                            - {{ $data['semester']['school_year'] ?? '---' }}</div>
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
            <!-- Roles Trend Chart -->
            <div class="col-lg-5">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Tài khoản tham gia hệ thống</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="rolesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="col-lg-7">
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            initializeRolesChart();

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

        function initializeRolesChart() {
            const ctx = document.getElementById('rolesChart').getContext('2d');
            const rawData = @json($data['statisticalUserByRole'] ?? []);

            const groupedData = {
                'Giảng viên': 0,
                'Sinh viên': 0,
            };

            rawData.forEach(item => {
                switch (item.role) {
                    case 1:
                        groupedData['Giảng viên'] += item.total;
                        break;
                    case 0:
                    case 3:
                        groupedData['Sinh viên'] += item.total;
                        break;
                }
            });

            const labels = Object.keys(groupedData);
            const data = Object.values(groupedData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số lượng',
                        data: data,
                        backgroundColor: '#3b82f6',
                        borderColor: '#0C4095FF',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endpush
