@php
    switch (Auth::user()->role ?? null) {
        case '1':
            $layout = 'layouts.teacher';
            $breadcumb = 'Thông tin cá nhân';
            break;
        case '4':
            $layout = 'layouts.studentAffairsDepartment';
            $breadcumb = 'Thông tin cá nhân';
            break;
        case '3':
            $layout = 'layouts.classStaff';
            $breadcumb = '';
            break;
        case '0':
            $layout = 'layouts.student';
            $breadcumb = '';
            break;
        case '2':
            $layout = 'layouts.facultyOffice';
            $breadcumb = 'Thôgn tin cá nhân';
            break;
        default:
            $layout = 'layouts.app'; // layout mặc định
            $breadcumb = '';
            break;
    }
@endphp

@extends($layout)

@section('title', 'Thông tin cá nhân')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => $breadcumb]]" />
@endsection


@push('styles')
    <style>
        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        /* Card Styling */
        .profile-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-card .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 1.5rem;
        }

        .profile-card .card-body {
            padding: 2rem;
        }

        /* Section Divider */
        .section-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, #007bff, #e9ecef, #007bff);
            margin: 2rem 0;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Button Styling */
        .btn-custom {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary.btn-custom {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }

        .btn-primary.btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        /* Password Toggle */
        .password-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Role Badge */
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .role-teacher { background-color: #d4edda; color: #155724; }
        .role-ctsv { background-color: #d1ecf1; color: #0c5460; }
        .role-cbl { background-color: #fff3cd; color: #856404; }
        .role-student { background-color: #f8d7da; color: #721c24; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .profile-card .card-body {
                padding: 1rem;
            }

            .btn-mobile-full {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .mobile-stack .col-md-6 {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .profile-card {
                margin: 0.5rem 0;
            }

            .profile-card .card-header {
                padding: 1rem;
            }

            .profile-card .card-header h3 {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-3 py-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11 col-xl-10">
                <div class="card profile-card">
                    <div class="card-header text-white text-center">
                        <h3 class="mb-0">
                            <i class="fa fa-person-circle me-2"></i>
                            Thông tin cá nhân
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- First Row: Name, Email, Phone -->
                            <div class="row mb-3">
                                <div class="col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="name" class="form-label">
                                        <i class="fa fa-person me-1"></i>
                                        Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', Auth::user()->name) }}"
                                           required
                                           placeholder="Nhập họ và tên đầy đủ">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="email" class="form-label">
                                        <i class="fa fa-envelope me-1"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           name="email"
                                           value="{{ Auth::user()->email }}"
                                           readonly>
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Email không thể thay đổi
                                    </small>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="phone" class="form-label">
                                        <i class="fa fa-telephone me-1"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', Auth::user()->phone) }}"
                                           placeholder="Nhập số điện thoại">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Row: Date of Birth, Gender, Role -->
                            <div class="row mb-3">
                                <div class="col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="date_of_birth" class="form-label">
                                        <i class="fa fa-calendar me-1"></i>
                                        Ngày sinh
                                    </label>
                                    <input type="date"
                                           class="form-control @error('date_of_birth') is-invalid @enderror"
                                           id="date_of_birth"
                                           name="date_of_birth"
                                           value="{{ old('date_of_birth', Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="gender" class="form-label">
                                        <i class="fa fa-gender-ambiguous me-1"></i>
                                        Giới tính
                                    </label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="Nam" {{ old('gender', Auth::user()->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ old('gender', Auth::user()->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="role" class="form-label">
                                        <i class="fa fa-shield-check me-1"></i>
                                        Vai trò
                                    </label>
                                    <div>
                                        @php
                                            $roleClass = '';
                                            $roleText = '';
                                            switch(Auth::user()->role) {
                                                case '1':
                                                    $roleClass = 'role-teacher';
                                                    $roleText = 'Giáo viên';
                                                    break;
                                                case '4':
                                                    $roleClass = 'role-ctsv';
                                                    $roleText = 'Cán bộ công tác sinh viên';
                                                    break;
                                                case '3':
                                                    $roleClass = 'role-cbl';
                                                    $roleText = Auth::user()->student?->position == 1 ? 'Lớp trưởng' : (Auth::user()->student?->position == 2 ? 'Lớp phó' : 'Bí thư');
                                                    break;
                                                case '0':
                                                    $roleClass = 'role-student';
                                                    $roleText = 'Sinh viên';
                                                    break;
                                                case '2':
                                                    $roleClass = 'bg-info text-white';
                                                    $roleText = 'Văn phòng khoa';
                                                    break;
                                                default:
                                                    $roleClass = 'bg-secondary text-white';
                                                    $roleText = 'Không xác định';
                                            }
                                        @endphp
                                        <span class="role-badge {{ $roleClass }}">
                                            <i class="fa fa-person-badge me-1"></i>
                                            {{ $roleText }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change Section -->
                            <hr class="section-divider">
                            <h5 class="section-title">
                                <i class="fa fa-key me-2"></i>
                                Đổi mật khẩu
                            </h5>
                            <p class="text-muted small mb-2">Để trống nếu không muốn thay đổi mật khẩu</p>

                            <!-- Password Row -->
                            <div class="row mb-3">
                                <div class="col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               id="current_password"
                                               name="current_password"
                                        placeholder="Nhập mật khẩu hiện tại">
                                        <button class="btn btn-outline-secondary password-toggle" type="button" data-target="current_password">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-12 mb-3 mb-md-0">
                                    <label for="new_password" class="form-label">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('new_password') is-invalid @enderror"
                                               id="new_password"
                                               name="new_password"
                                               placeholder="Nhập mật khẩu mới">
                                        <button class="btn btn-outline-secondary password-toggle" type="button" data-target="new_password">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-12">
                                    <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                        id="new_password_confirmation"
                                        name="new_password_confirmation"
                                        placeholder="Nhập lại mật khẩu mới">
                                        <button class="btn btn-outline-secondary password-toggle" type="button" data-target="new_password_confirmation">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-custom">
                                        <i class="fa fa-save me-1"></i>
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form validation
            $("#profileForm").on("submit", function(e) {
                let isValid = true;

                // Name validation
                const name = $("#name").val();
                const nameRegex = /^[\p{L}\p{M}\s'-]+$/u;
                if (!nameRegex.test(name)) {
                    e.preventDefault();
                    showAlert("Họ và tên chỉ được chứa chữ cái, dấu cách, dấu nháy đơn hoặc dấu gạch ngang.", "warning");
                    $("#name").focus();
                    isValid = false;
                }

                // Phone validation
                const phone = $("#phone").val();
                const phoneRegex = /^(\+84|0)(3[2-9]|5[689]|7[06-9]|8[1-689]|9[0-46-9])[0-9]{7}$/;
                if (phone && !phoneRegex.test(phone)) {
                    e.preventDefault();
                    showAlert("Số điện thoại không hợp lệ. Vui lòng sử dụng định dạng (ví dụ: 0912345678 hoặc +84912345678).", "warning");
                    $("#phone").focus();
                    isValid = false;
                }

                // Date of birth validation
                const dob = $("#date_of_birth").val();
                if (dob) {
                    const dobDate = new Date(dob);
                    const today = new Date();
                    const minAge = 13;
                    const maxAge = 120;
                    const age = today.getFullYear() - dobDate.getFullYear();
                    if (age < minAge || age > maxAge || dobDate > today) {
                        e.preventDefault();
                        showAlert("Ngày sinh không hợp lệ. Độ tuổi phải từ 13 đến 120 và không được trong tương lai.", "warning");
                        $("#date_of_birth").val("");
                        $("#date_of_birth").focus();
                        isValid = false;
                    }
                }

                // Password validation
                const currentPassword = $("#current_password").val();
                const newPassword = $("#new_password").val();
                const confirmPassword = $("#new_password_confirmation").val();
                const strongPasswordRegex = /^.{8,}$/;

                if (newPassword || confirmPassword || currentPassword) {
                    if (!currentPassword) {
                        e.preventDefault();
                        showAlert("Vui lòng nhập mật khẩu hiện tại.", "warning");
                        $("#current_password").focus();
                        isValid = false;
                    }

                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        showAlert("Mật khẩu mới không khớp.", "warning");
                        $("#new_password_confirmation").focus();
                        isValid = false;
                    }

                    if (newPassword && !strongPasswordRegex.test(newPassword)) {
                        e.preventDefault();
                        showAlert("Mật khẩu phải có ít nhất 8 ký tự.", "warning");
                        $("#new_password").focus();
                        isValid = false;
                    }
                }

                // Show loading state
                if (isValid) {
                    const submitBtn = $(this).find('button[type="submit"]');
                    const originalText = submitBtn.html();
                    submitBtn.html('<i class="fa fa-hourglass-split me-1"></i> Đang lưu...').prop('disabled', true);

                    // Re-enable button after 3 seconds (in case of no redirect)
                    setTimeout(function() {
                        submitBtn.html(originalText).prop('disabled', false);
                    }, 3000);
                }

                return isValid;
            });

            // Show custom alert
            function showAlert(message, type = 'info') {
                const alertClass = type === 'warning' ? 'alert-warning' :
                    type === 'success' ? 'alert-success' :
                        type === 'danger' ? 'alert-danger' : 'alert-info';

                const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                $('.card-body').prepend(alertHtml);

                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endpush

