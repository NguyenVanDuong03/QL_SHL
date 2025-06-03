@extends('layouts.teacher')

@section('title', 'Sinh hoạt lớp cố định')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Sinh hoạt lớp', 'url' => 'teacher.class-session.index'],
        ['label' => 'Sinh hoạt lớp cố định', 'url' => 'teacher.class-session.fixed-class-activitie'],
        ['label' => 'Đăng ký sinh hoạt lớp']
    ]"/>
@endsection

@push('styles')
    <style>
        .card {
            border: none;
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .alert {
            border-radius: 8px;
        }

        .btn {
            border-radius: 6px;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
        }

        .input-group .btn {
            border-left: 0;
        }

        @media (max-width: 768px) {
            .mx-3 {
                margin-left: 1rem !important;
                margin-right: 1rem !important;
            }
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid mt-4">
        <!-- Header với thông tin khóa học -->
        <div class="mx-3">
            <div class="mb-2">
                <a href="{{ route('teacher.class-session.fixed-class-activitie') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center">
                        <h3 class="fw-bold d-none d-md-block">Chi tiết sinh hoạt lớp</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="my-4 mx-3">
            <div class="row">
                <!-- Cột trái - Thông tin chính -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div
                            class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-info-circle text-secondary me-2"></i>Thông tin sinh hoạt
                                lớp</h5>
                            <!-- Status badge -->
                            @php
                                $statusConfig = [
                                    0 => ['text' => 'Chờ duyệt', 'class' => 'bg-warning', 'icon' => 'fas fa-clock'],
                                    1 => ['text' => 'Đã duyệt', 'class' => 'bg-success', 'icon' => 'fas fa-check-circle'],
                                    2 => ['text' => 'Đã từ chối', 'class' => 'bg-danger', 'icon' => 'fas fa-times-circle']
                                ];
                                $currentStatus = $statusConfig[$data['getClassSessionRequest']->status ?? 0];
                            @endphp
                            <span class="badge {{ $currentStatus['class'] }} fs-6">
                                <i class="{{ $currentStatus['icon'] }} me-1"></i>{{ $currentStatus['text'] }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">Tiêu đề:</label>
                                <h5 class="text-dark">{{ $data['getClassSessionRequest']->title }}</h5>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">Nội dung chính:</label>
                                <div class="border rounded p-3 bg-light" style="min-height: 200px;">
                                    <p class="text-dark mb-0"
                                       style="white-space: pre-line;">{{ $data['getClassSessionRequest']->content ?? 'Không có nội dung' }}</p>
                                </div>
                            </div>

                            <!-- Rejection reason if status = 2 -->
                            @if(($data['getClassSessionRequest']->status ?? 0) == 2 && !empty($data['getClassSessionRequest']->rejection_reason))
                                <div class="mb-4">
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Lý do từ chối
                                        </h6>
                                        <p class="mb-0">{{ $data['getClassSessionRequest']->rejection_reason }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Action buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                @if(($data['getClassSessionRequest']->status ?? 0) == 0)
                                    <button type="button" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                    </button>
                                    <button type="button" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Hủy đăng ký
                                    </button>
                                @elseif(($data['getClassSessionRequest']->status ?? 0) == 1)
                                    <button type="button" class="btn btn-info">
                                        <i class="fas fa-users me-2"></i>Danh sách tham gia
                                    </button>
{{--                                    <button type="button" class="btn btn-success">--}}
{{--                                        <i class="fas fa-file-export me-2"></i>Xuất báo cáo--}}
{{--                                    </button>--}}
                                @else
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-redo me-2"></i>Đăng ký lại
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải - Chi tiết hình thức -->
                <div class="col-md-4 mt-md-0 mt-3">
                    <div class="card shadow-sm">
                        @php
                            $position = $data['getClassSessionRequest']->position ?? 0;
                            $positionConfig = [
                                0 => ['title' => 'Trực tiếp tại trường', 'class' => 'bg-success text-white', 'icon' => 'fas fa-building'],
                                1 => ['title' => 'Trực tuyến', 'class' => 'bg-primary text-white', 'icon' => 'fas fa-video'],
                                2 => ['title' => 'Dã ngoại', 'class' => 'bg-warning text-white', 'icon' => 'fas fa-tree']
                            ];
                            $currentPosition = $positionConfig[$position];
                        @endphp
                        <div class="card-header {{ $currentPosition['class'] }}">
                            <h6 class="mb-0 fw-bold">
                                <i class="{{ $currentPosition['icon'] }} me-2"></i>{{ $currentPosition['title'] }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold {{ $position == 0 ? 'text-success' : ($position == 1 ? 'text-primary' : 'text-warning') }}">Thời gian:</label>
                                <p class="text-dark mb-0 fs-5">
                                    <i class="fas fa-calendar-alt me-2 {{ $position == 0 ? 'text-success' : ($position == 1 ? 'text-primary' : 'text-warning') }}"></i>
                                    {{ \Carbon\Carbon::parse($data['getClassSessionRequest']->proposed_at)->format('H:i d/m/Y') }}
                                </p>
                            </div>

                            @if($position == 0)
                                <!-- Trực tiếp tại trường -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-success">Phòng học:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->room->name }}</p>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Sinh viên cần có mặt tại phòng học đúng giờ quy định.</small>
                                </div>
                            @elseif($position == 1)
                                <!-- Trực tuyến -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">Nền tảng:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->meeting_type ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">Mã cuộc họp:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               value="{{ $data['getClassSessionRequest']->meeting_id ?? '---' }}"
                                               readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="copyToClipboard(this.previousElementSibling)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                @if(!empty($data['getClassSessionRequest']->meeting_password))
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-primary">Mật khẩu:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   value="{{ $data['getClassSessionRequest']->meeting_password }}"
                                                   readonly>
                                            <button class="btn btn-outline-secondary" type="button"
                                                    onclick="copyToClipboard(this.previousElementSibling)">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <div class="d-grid">
                                        <a href="{{ $data['getClassSessionRequest']->meeting_url ?? '#' }}"
                                           target="_blank"
                                           class="btn btn-primary">
                                            <i class="fas fa-video me-2"></i>Tham gia cuộc họp
                                        </a>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <small>Kiểm tra kết nối internet và thiết bị trước khi tham gia.</small>
                                </div>
                            @else
                                <!-- Dã ngoại -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning">Địa điểm:</label>
                                    <p class="text-dark mb-0 fs-5">{{ $data['getClassSessionRequest']->location ?? '---' }}</p>
                                </div>
                                <div class="mb-3">
                                    <div class="d-grid">
                                        <button class="btn btn-warning"
                                                onclick="openMap('{{ $data['getClassSessionRequest']->location ?? '---' }}')">
                                            <i class="fas fa-map-marker-alt me-2"></i>Xem trên bản đồ
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-leaf me-2"></i>
                                    <small>Chuẩn bị đầy đủ đồ dùng cá nhân và tuân thủ quy định an toàn.</small>
                                </div>
                            @endif

                            @if(!empty($data['getClassSessionRequest']->note))
                                <div class="mt-4">
                                    <label class="form-label fw-bold text-secondary">Ghi chú:</label>
                                    <div class="border rounded p-2 bg-light">
                                        <p class="text-dark mb-0"
                                           style="white-space: pre-line;">{{ $data['getClassSessionRequest']->note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold">
                                <i class="fas fa-info me-2"></i>Thông tin bổ sung
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <div class="mb-2">
                                        <small class="text-muted">Học kỳ:</small>
                                        <p class="mb-0">{{ $data['getCSRSemesterInfo']->name }} - {{ $data['getCSRSemesterInfo']->school_year }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Lớp:</small>
                                        <p class="mb-0">{{ $data['getStudyClassByIds']->name }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Ngày tạo:</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->created_at ?? now())->format('H:i d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Cập nhật lần cuối:</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->updated_at ?? now())->format('H:i d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Loại sinh hoạt:</small>
                                        <p class="mb-0">
                                            @php
                                                $type = $data['getClassSessionRequest']->type ?? 0;
                                                echo $type == 0 ? 'SHL cố định' : 'SHL linh hoạt';
                                            @endphp
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <small class="text-muted">Học kỳ:</small>
                                        <p class="mb-0">{{ $data['getCSRSemesterInfo']->name }} - {{ $data['getCSRSemesterInfo']->school_year }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Lớp:</small>
                                        <p class="mb-0">{{ $data['getStudyClassByIds']->name }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Ngày tạo:</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->created_at ?? now())->format('H:i d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Cập nhật lần cuối:</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($data['getClassSessionRequest']->updated_at ?? now())->format('H:i d/m/Y') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Loại sinh hoạt:</small>
                                        <p class="mb-0">
                                            @php
                                                $type = $data['getClassSessionRequest']->type ?? 0;
                                                echo $type == 0 ? 'SHL cố định' : 'SHL linh hoạt';
                                            @endphp
                                        </p>
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
        function copyToClipboard(element) {
            element.select();
            element.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(element.value).then(function () {
                // Show success message
                showToast('Đã sao chép vào clipboard!', 'success');
            });
        }

        function openMap(location) {
            if (location) {
                const encodedLocation = encodeURIComponent(location);
                window.open(`https://www.google.com/maps/search/${encodedLocation}`, '_blank');
            }
        }

        function showToast(message, type = 'info') {
            // Simple toast implementation
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'info'} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush
