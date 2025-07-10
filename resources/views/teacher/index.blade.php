@extends('layouts.teacher')

@section('title', 'Trang chủ Giáo viên')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Trang chủ']]" />
@endsection

@push('styles')
    <style>
        .pending-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            height: 100%;
        }

        .pending-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pending-list {
            max-height: 500px;
            overflow-y: auto;
            flex: 1 1 auto;
            padding: 0;
        }

        .pending-item {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.3s ease;
        }

        .pending-item:hover {
            background-color: #f8f9fa;
        }

        .pending-item:last-child {
            border-bottom: none;
        }

        .pending-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .pending-class-name {
            font-weight: 600;
            color: #212529;
            font-size: 1rem;
        }

        .pending-date {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
        }

        .pending-content p {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #495057;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chart-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #212529;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .chart-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chart-container {
            height: 200px;
            margin-bottom: 1rem;
            position: relative;
        }

        .chart-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: auto;
        }

        .summary-item {
            text-align: center;
        }

        .summary-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .summary-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
        }

        .btn-view-all {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        .pending-list::-webkit-scrollbar {
            width: 6px;
        }

        .pending-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .pending-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .pending-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .text-blue { color: #0d6efd !important; }
        .text-orange { color: #fd7e14 !important; }
        .text-green { color: #198754 !important; }
        .text-purple { color: #6f42c1 !important; }

        .bg-blue-light { background-color: rgba(13, 110, 253, 0.1) !important; }
        .bg-orange-light { background-color: rgba(253, 126, 20, 0.1) !important; }
        .bg-green-light { background-color: rgba(25, 135, 84, 0.1) !important; }
        .bg-purple-light { background-color: rgba(111, 66, 193, 0.1) !important; }

        @media (max-width: 768px) {
            .chart-container {
                height: 150px;
            }

            .stat-card-value {
                font-size: 1.25rem;
            }

            .pending-item {
                padding: 1rem;
            }
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
                    <div class="stat-card-header">
                        <div class="stat-card-title">TỔNG SỐ LỚP CHỦ NHIỆM</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalClasses'] }} lớp</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">SINH VIÊN ĐĂNG BÁO HỌC VỤ</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['totalStudentWarning'] }} sinh viên</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">LỊCH SINH HOẠT LỚP</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ count($data['getAllClassSessionByLecturer'] ?? 0) }} buổi họp</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-title">ĐIỂM RÈN LUYỆN TRUNG BÌNH</div>
                        <div class="stat-card-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $data['getOverallAverageConductScoreWithTotalStudents']->average_conduct_score ?? 0 }} điểm</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Class Activities Pending Approval -->
            <div class="col-lg-4">
                <div class="pending-card">
                    <div class="pending-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock text-blue me-2"></i>
                            Sinh hoạt lớp cần duyệt
                        </h5>
                        <span class="badge bg-danger">{{ count($data['getAllClassSessionByLecturer'] ?? 0) }}</span>
                    </div>

                    <div class="pending-list">
                        @forelse($data['getAllClassSessionByLecturer'] ?? [] as $item)
                            <div class="pending-item">
                                <div class="pending-item-header">
                                    <div class="pending-class-name">Lớp {{ $item['study_class']['name'] ?? '' }}</div>
                                    @php
                                        $typeClass = ($item['type'] ?? 0) == 0 ? 'bg-primary' : 'bg-success';
                                        $typeLabel = ($item['type'] ?? 0) == 0 ? 'Cố định' : 'Linh hoạt';
                                    @endphp
                                    <span class="badge {{ $typeClass }}">{{ $typeLabel }}</span>
                                </div>

                                <div class="pending-date">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ \Carbon\Carbon::parse($item['proposed_at'] ?? now())->format('H:i d/m/Y') }}
                                </div>

                                <div class="pending-content">
                                    <p class="mb-1">
                                        <strong>Chủ đề:</strong>
                                        <span class="title_cut">{{ $item['title'] ?? '' }}</span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Hình thức:</strong>
                                        @php
                                            $position = $item['position'] ?? 0;
                                            $positionLabel = $position == 0 ? 'Trực tiếp' : ($position == 1 ? 'Trực tuyến' : 'Dã ngoại');
                                        @endphp
                                        {{ $positionLabel }}
                                    </p>
                                    @php
                                        $status = $item['status'] == 0 ? 'text-primary' : 'text-danger';
                                    @endphp
                                    <p class="mb-0">
                                        <strong>Trạng thái:</strong> <span class="{{ $status }}">{{ $item['status'] == 0 ? 'Chờ duyệt' : 'Bị từ chối' }}</span>
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="d-flex justify-content-center align-items-center">
                                <p class="text-muted text-center my-5">Không có buổi sinh hoạt lớp nào cần duyệt.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="text-center p-3 border-top">
                        <a href="{{ route('teacher.class-session.index') }}" class="btn btn-primary btn-view-all">
                            <i class="fas fa-list me-2"></i>Xem tất cả
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="col-lg-8">
                <div class="row g-4 h-100">
                    <!-- Chart 1 - Activity Types -->
                    <div class="col-md-6">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h6 class="chart-title">
                                    <i class="fas fa-chart-pie text-blue me-2"></i>
                                    Thống kê sinh hoạt lớp
                                </h6>
                            </div>
                            <div class="chart-body">
                                <div class="chart-container">
                                    <canvas id="activityChart"></canvas>
                                </div>
                                <div class="chart-summary">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="summary-item">
                                                <div class="summary-number text-blue">{{ $data['countClassSessionById']['fixed'] ?? 25 }}</div>
                                                <div class="summary-label">Sinh hoạt cố định</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="summary-item">
                                                <div class="summary-number text-orange">{{ $data['countClassSessionById']['flexible'] ?? 18 }}</div>
                                                <div class="summary-label">Sinh hoạt linh hoạt</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2 - Training Scores -->
                    <div class="col-md-6">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h6 class="chart-title">
                                    <i class="fas fa-chart-bar text-green me-2"></i>
                                    Điểm rèn luyện
                                </h6>
                            </div>
                            <div class="chart-body">
                                <div class="chart-container">
                                    <canvas id="trainingChart"></canvas>
                                </div>
                                <div class="chart-summary">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="summary-item">
                                                <div class="summary-number text-green">{{ $data['getOverallAverageConductScoreWithTotalStudents']->average_conduct_score ?? 0 }}</div>
                                                <div class="summary-label">Điểm TB</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="summary-item">
                                                <div class="summary-number text-blue">{{ $data['getOverallAverageConductScoreWithTotalStudents']->total_students ?? 0 }}</div>
                                                <div class="summary-label">Sinh viên</div>
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

    @include('common.updateLecturer')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".title_cut").each(function () {
                var text = $(this).text().trim();
                if (text.length > 20) {
                    $(this).attr("title", text);
                    $(this).text(text.substring(0, 20) + '...');
                }
            });

            // Initialize all charts
            initializeActivityChart();
            initializeTrainingChart();

            // Add hover effects to stat cards
            $('.stat-card').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );

            // Add click handlers for pending items
            $('.pending-item').click(function() {
                const className = $(this).find('.pending-class-name').text();
                alert('Xem chi tiết: ' + className);
            });
        });

        // Chart 1: Activity Types (Doughnut Chart)
        function initializeActivityChart() {
            const ctx = document.getElementById('activityChart').getContext('2d');
            const data = @json($data['countClassSessionById'] ?? [0, 0]);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Sinh hoạt cố định', 'Sinh hoạt linh hoạt'],
                    datasets: [{
                        data: [data.fixed, data.flexible],
                        backgroundColor: [
                            'rgba(13, 110, 253, 0.8)',
                            'rgba(253, 126, 20, 0.8)'
                        ],
                        borderColor: [
                            'rgba(13, 110, 253, 1)',
                            'rgba(253, 126, 20, 1)'
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
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
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

        // Chart 2: Training Scores (Bar Chart)
        function initializeTrainingChart() {
            const ctx = document.getElementById('trainingChart').getContext('2d');
            const rawData = @json($data['getAverageConductScores'] ?? []);
            const labels = rawData.map(item => {
                const semester = item.semester_name === 'Học kỳ 1' ? 'HK1' : (item.semester_name === 'Học kỳ 2' ? 'HK2' : (item.semester_name === 'Học kỳ hè' ? 'HKH' : 'HKP'));
                return `${semester} ${item.school_year}`;
            });
            const scores = rawData.map(item => parseFloat(item.average_conduct_score) || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Điểm trung bình',
                        data: scores,
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 2,
                        borderRadius: 6
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
                            beginAtZero: false,
                            min: 0,
                            max: 100,
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }

        function viewDetail(id) {
            window.location.href = '/class-session/' + id;
        }

        // Refresh data every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
@endpush
