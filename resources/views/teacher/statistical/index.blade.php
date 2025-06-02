@extends('layouts.teacher')

@section('title', 'Thống kê')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Thống kê']]" />
@endsection

@section('main')
    <div class="container-fluid py-4">
        <!-- Class selector -->
        <div class="mb-4">
            <select class="form-select" style="max-width: 150px;">
                <option selected>63KTPM2</option>
                <option>64KTPM1</option>
                <option>62KTPM3</option>
            </select>
        </div>

        <!-- Main content row -->
        <div class="row g-4">
            <!-- Left card: Class meetings -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Sổ buổi họp lớp</h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">Ngày họp</th>
                                    <th scope="col">Phương thức</th>
                                    <th scope="col">Số lượng tham gia</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>20/04/2025</td>
                                    <td>
                                        <div class="fw-medium">Trực tiếp</div>
                                        <div class="text-muted small">306A2</div>
                                    </td>
                                    <td>60/60</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Xuất file</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>20/04/2025</td>
                                    <td>
                                        <div class="fw-medium">Trực tuyến</div>
                                        <div class="text-muted small">Microsoft Teams</div>
                                    </td>
                                    <td>60/60</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Xuất file</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>20/04/2025</td>
                                    <td>
                                        <div class="fw-medium">Dã ngoại</div>
                                        <div class="text-muted small">Quán trà đá Thủy Lợi</div>
                                    </td>
                                    <td>60/60</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Xuất file</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>20/04/2025</td>
                                    <td>
                                        <div class="fw-medium">Trực tuyến</div>
                                        <div class="text-muted small">Microsoft Teams</div>
                                    </td>
                                    <td>60/60</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Xuất file</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>20/04/2025</td>
                                    <td>
                                        <div class="fw-medium">Trực tiếp</div>
                                        <div class="text-muted small">203A3</div>
                                    </td>
                                    <td>60/60</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Xuất file</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right card: Attendance chart -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Sinh viên tham gia sinh hoạt lớp</h5>

                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning students card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold mb-0">Danh sách sinh viên cảnh báo học vụ</h5>
                            <span class="badge bg-danger">18 sinh viên</span>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary" type="button">Xem chi tiết</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');

            const attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Có mặt', 'Vắng có phép', 'Vắng'],
                    datasets: [{
                        label: 'Số sinh viên',
                        data: [30, 20, 10],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(54, 162, 235, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 30,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
