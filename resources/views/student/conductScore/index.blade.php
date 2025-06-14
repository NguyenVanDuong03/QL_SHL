@extends('layouts.student')

@section('title', 'Điểm rèn luyện')

@push('styles')
    <style>
        .alert-warning-custom {
            background-color: #ff8c00;
            color: white;
            border: none;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .score-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .score-summary div {
            margin-bottom: 5px;
        }

        .table-container {
            background-color: white;
            border-radius: 5px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .form-select, .form-control {
            border: 1px solid #ced4da;
        }

        /* Enhanced table styling */
        .criteria-row {
            vertical-align: middle;
        }

        .criteria-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 200px;
        }

        .image-upload-btn {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .image-upload-btn input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
            max-height: 100px;
            overflow-y: auto;
        }

        .image-preview {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview .remove-image {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .note-input {
            width: 100%;
            min-height: 60px;
            resize: vertical;
            font-size: 0.85rem;
        }

        .score-input {
            max-width: 80px;
            text-align: center;
        }

        .image-count-badge {
            background: #007bff;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .alert-warning-custom {
                font-size: 0.8rem;
                padding: 10px;
                margin-bottom: 15px;
            }

            .score-summary {
                padding: 10px;
                margin-bottom: 15px;
            }

            .score-summary .row {
                margin: 0;
            }

            .score-summary .col-6,
            .score-summary .col-12 {
                padding: 2px;
                font-size: 0.85rem;
            }

            .form-controls-mobile {
                flex-direction: column;
            }

            .form-controls-mobile .col-md-3,
            .form-controls-mobile .col-md-6 {
                width: 100%;
                margin-bottom: 15px;
            }

            /* Mobile table styling */
            .table-responsive-mobile {
                font-size: 0.8rem;
            }

            .table-responsive-mobile th,
            .table-responsive-mobile td {
                padding: 8px 4px;
                vertical-align: top;
            }

            .criteria-actions {
                min-width: 150px;
                gap: 5px;
            }

            .criteria-actions .btn {
                font-size: 0.7rem;
                padding: 4px 8px;
            }

            .note-input {
                min-height: 40px;
                font-size: 0.75rem;
            }

            .image-preview {
                width: 30px;
                height: 30px;
            }

            .mobile-buttons {
                text-align: center;
                margin-top: 20px;
            }

            .mobile-buttons .btn {
                width: 45%;
                margin: 5px 2%;
                font-size: 0.9rem;
            }

            @media (max-width: 480px) {
                .table-bordered th,
                .table-bordered td {
                    border-width: 1px;
                }

                .score-summary .col-6 {
                    width: 100%;
                    text-align: center;
                    margin-bottom: 8px;
                }

                .criteria-actions {
                    min-width: 120px;
                }
            }
        }

        /* Tablet optimizations */
        @media (min-width: 769px) and (max-width: 1024px) {
            .score-summary {
                padding: 12px;
            }

            .table-responsive-mobile th,
            .table-responsive-mobile td {
                padding: 10px 6px;
            }
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Image modal styles */
        .image-modal .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .image-modal img {
            max-width: 100%;
            height: auto;
        }

        /* Progress bar for uploads */
        .upload-progress {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 5px;
        }

        .upload-progress-bar {
            height: 100%;
            background: #007bff;
            width: 0;
            transition: width 0.3s ease;
        }
    </style>
@endpush

@section('main')
    <div class="container mt-4">
        <!-- Alert Banner -->
        @if(!$data['checkConductEvaluationPeriod'])
            <div class="alert alert-warning-custom" role="alert">
                ĐÃ HẾT THỜI GIAN NHẬP ĐIỂM RÈN LUYỆN
            </div>
        @endif

        <!-- Form Controls -->
        <div class="row mb-4 form-controls-mobile">
            <div class="col-md-3 col-12">
                <label for="hocKy" class="form-label">Học kỳ</label>
                <form action="{{ route('student.conduct-score.index') }}" class="input-group">
                    <select class="form-select" id="semester_id" name="semester_id">
                        @forelse($data['semesters'] ?? [] as $semester)
                            <option
                                value="{{ $semester['id'] }}" {{ $semester['id'] == request()->get('semester_id') ? 'selected' : '' }}>
                                {{ $semester['name'] }} - {{ $semester['school_year'] }}
                            </option>
                        @empty
                            <option value="">Không có học kỳ nào</option>
                        @endforelse
                    </select>
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i> Tải lại
                    </button>
                </form>
            </div>
            <div class="col-md-9 col-12">
                <div class="score-summary">
                    <div class="row text-end">
                        <div class="col-6">
                            <strong>Tổng điểm: <span id="tongDiem">0</span></strong>
                        </div>
                        <div class="col-6">
                            <strong>Điểm quy đổi: <span id="diemQuyDoi">0</span></strong>
                        </div>
                        <div class="col-6">
                            <strong>Xếp loại: <span id="xepLoai">Chưa có dữ liệu</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 table-responsive-mobile" id="diemRenLuyenTable">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th style="min-width: 200px;">Nội dung đánh giá</th>
                        <th style="min-width: 60px;">Điểm tối đa</th>
                        <th style="min-width: 70px;">Điểm</th>
                        <th style="min-width: 250px;">Hành động & Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($data['checkConductEvaluationPeriod'])
                        @forelse($data['getConductCriteriaData'] ?? [] as $item)
                            <tr class="criteria-row" data-criteria="{{ $item['criterion_id'] }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['content'] }}</td>
                                <td class="text-center">{{ $item['max_score'] }}</td>
                                <td class="text-center">
                                    <input type="number" class="form-control form-control-sm score-input"
                                           min="0" max="{{ $item['max_score'] }}" value="{{ $item['self_score'] ?? 0 }}"
                                           data-max="{{ $item['max_score'] }}">
                                </td>
                                <td>
                                    <div class="criteria-actions">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="image-upload-btn btn btn-outline-primary btn-sm">
                                                <i class="fas fa-camera"></i> Thêm ảnh
                                                <input type="file" accept="image/*" class="image-input">
                                            </label>
                                            <button type="button" class="btn btn-outline-info btn-sm view-images-btn"
                                                    style="display: {{ $item['evidence_path'] ? 'inline-block' : 'none' }};">
                                                <i class="fas fa-eye"></i> Xem
                                            </button>
                                        </div>
                                        <div class="image-preview-container">
                                            @if($item['evidence_path'])
                                                <div class="image-preview" data-criteria="{{ $item['criterion_id'] }}">
                                                    <img src="{{ asset('storage/' . $item['evidence_path']) }}" alt="Evidence Image">
                                                    <button type="button" class="remove-image" title="Xóa ảnh">×</button>
                                                </div>
                                            @endif
                                        </div>
                                        <textarea class="form-control note-input"
                                                  placeholder="Ghi chú cho tiêu chí này...">{{ $item['note'] ?? '' }}</textarea>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @foreach($data['conductCriterias'] ?? [] as $criteria)
                                <tr class="criteria-row" data-criteria="{{ $criteria['id'] }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $criteria['content'] }}</td>
                                    <td class="text-center">{{ $criteria['max_score'] }}</td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm score-input"
                                               min="0" max="{{ $criteria['max_score'] }}" value="0"
                                               data-max="{{ $criteria['max_score'] }}">
                                    </td>
                                    <td>
                                        <div class="criteria-actions">
                                            <div class="d-flex align-items-center gap-2">
                                                <label class="image-upload-btn btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-camera"></i> Thêm ảnh
                                                    <input type="file" accept="image/*" class="image-input">
                                                </label>
                                                <button type="button"
                                                        class="btn btn-outline-info btn-sm view-images-btn"
                                                        style="display: none;">
                                                    <i class="fas fa-eye"></i> Xem
                                                </button>
                                            </div>
                                            <div class="image-preview-container"></div>
                                            <textarea class="form-control note-input"
                                                      placeholder="Ghi chú cho tiêu chí này..."></textarea>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Không có tiêu chí nào để đánh giá</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12 text-end mobile-buttons">
                <button type="button" class="btn btn-secondary me-2" id="resetBtn">Đặt lại</button>
                <button type="button" class="btn btn-success me-2" id="exportBtn">Xuất dữ liệu</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Lưu điểm</button>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade image-modal" id="imageModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hình ảnh minh chứng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="imageModalBody">
                        <!-- Images will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize criteriaData
            let criteriaData = {};

            // Populate criteriaData from backend data
            @if($data['checkConductEvaluationPeriod'])
                @forelse($data['getConductCriteriaData'] ?? [] as $item)
                criteriaData[{{ $item['criterion_id'] }}] = {
                image: @if($item['evidence_path'])
                {
                    name: '{{ basename($item['evidence_path']) }}',
                    data: '{{ asset('storage/' . $item['evidence_path']) }}',
                    path: '{{ $item['evidence_path'] }}',
                    uploadDate: new Date().toISOString()
                }
                @else
                    null
                @endif,
                note: '{{ $item['note'] ?? '' }}',
                score: {{ $item['self_score'] ?? 0 }}
            };
            @empty
                @foreach($data['conductCriterias'] ?? [] as $criteria)
                criteriaData[{{ $criteria['id'] }}] = {
                image: null,
                note: '',
                score: 0
            };
            @endforeach
            @endforelse
            @endif

            // Ensure all table rows have corresponding criteriaData entries
            $('.criteria-row').each(function () {
                const criteriaId = $(this).data('criteria');
                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = {
                        image: null,
                        note: '',
                        score: 0
                    };
                }
            });

            // Function to calculate total score
            function calculateTotal() {
                let total = 0;
                $('.score-input').each(function () {
                    total += parseInt($(this).val()) || 0;
                });

                $('#tongDiem').text(total);
                $('#diemQuyDoi').text(total);

                // Determine classification
                let classification = '';
                if (total >= 90) {
                    classification = 'Xuất sắc';
                } else if (total >= 80) {
                    classification = 'Tốt';
                } else if (total >= 65) {
                    classification = 'Khá';
                } else if (total >= 50) {
                    classification = 'Trung bình';
                } else if (total >= 35) {
                    classification = 'Yếu';
                } else {
                    classification = 'Kém';
                }

                $('#xepLoai').text(classification);
            }

            // Function to update image preview
            function updateImagePreview(criteriaId) {
                const row = $(`.criteria-row[data-criteria="${criteriaId}"]`);
                const previewContainer = row.find('.image-preview-container');
                const viewBtn = row.find('.view-images-btn');

                // Clear existing previews
                previewContainer.empty();

                // Ensure criteriaData[criteriaId] exists
                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = { image: null, note: '', score: 0 };
                }

                // Add preview for the single image
                if (criteriaData[criteriaId].image) {
                    const image = criteriaData[criteriaId].image;
                    const preview = $(`
                        <div class="image-preview" data-criteria="${criteriaId}">
                            <img src="${image.data}" alt="${image.name}">
                            <button type="button" class="remove-image" title="Xóa ảnh">×</button>
                        </div>
                    `);
                    previewContainer.append(preview);
                    viewBtn.show();
                } else {
                    viewBtn.hide();
                }
            }

            // Initialize UI with criteriaData
            function initializeUI() {
                Object.keys(criteriaData).forEach(criteriaId => {
                    const row = $(`.criteria-row[data-criteria="${criteriaId}"]`);
                    if (row.length) {
                        // Update score
                        row.find('.score-input').val(criteriaData[criteriaId].score);
                        // Update note
                        row.find('.note-input').val(criteriaData[criteriaId].note);
                        // Update image
                        updateImagePreview(criteriaId);
                    }
                });
                calculateTotal();
            }

            // Handle image upload (single image)
            $('.image-input').on('change', function () {
                const file = this.files[0];
                const criteriaId = $(this).closest('.criteria-row').data('criteria');

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = { image: null, note: '', score: 0 };
                }

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        criteriaData[criteriaId].image = {
                            file: file, // Store the File object for FormData
                            name: file.name,
                            size: file.size,
                            type: file.type,
                            data: e.target.result, // Base64 for preview
                            uploadDate: new Date().toISOString()
                        };
                        updateImagePreview(criteriaId);
                    };
                    reader.readAsDataURL(file);
                } else {
                    showAlert('Vui lòng chọn một file hình ảnh hợp lệ!', 'error');
                }

                this.value = '';
            });

            // Handle image removal
            $(document).on('click', '.remove-image', function () {
                const criteriaId = $(this).closest('.criteria-row').data('criteria');

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = { image: null, note: '', score: 0 };
                }

                criteriaData[criteriaId].image = null;
                updateImagePreview(criteriaId);
            });

            // Handle view image
            $('.view-images-btn').on('click', function () {
                const criteriaId = $(this).closest('.criteria-row').data('criteria');
                const modalBody = $('#imageModalBody');

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = { image: null, note: '', score: 0 };
                }

                modalBody.empty();

                if (criteriaData[criteriaId].image) {
                    const image = criteriaData[criteriaId].image;
                    const imageElement = $(`
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>${image.name}</h6>
                                <small class="text-muted">${new Date(image.uploadDate).toLocaleString()}</small>
                            </div>
                            <img src="${image.data}" class="img-fluid" alt="${image.name}">
                        </div>
                    `);
                    modalBody.append(imageElement);
                } else {
                    modalBody.html('<p class="text-center text-muted">Chưa có hình ảnh nào</p>');
                }

                $('#imageModal').modal('show');
            });

            // Handle note changes
            $('.note-input').on('input', function () {
                const criteriaId = $(this).closest('.criteria-row').data('criteria');

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = { image: null, note: '', score: 0 };
                }

                criteriaData[criteriaId].note = $(this).val();
            });

            // Handle score input changes
            let debounceTimer;
            $('.score-input').on('input change', function () {
                clearTimeout(debounceTimer);
                let $input = $(this);
                const criteriaId = $(this).closest('.criteria-row').data('criteria');

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = { image: null, note: '', score: 0 };
                }

                debounceTimer = setTimeout(function () {
                    let maxScore = parseInt($input.data('max'));
                    let currentValue = parseInt($input.val()) || 0;

                    if (currentValue > maxScore) {
                        $input.val(maxScore);
                        currentValue = maxScore;
                        showAlert('Điểm không được vượt quá điểm tối đa: ' + maxScore);
                    } else if (currentValue < 0) {
                        $input.val(0);
                        currentValue = 0;
                    }

                    criteriaData[criteriaId].score = currentValue;
                    calculateTotal();
                }, 300);
            });

            // Reset button
            $('#resetBtn').on('click', function () {
                if (confirm('Bạn có chắc chắn muốn đặt lại tất cả dữ liệu về trạng thái ban đầu?')) {
                    $(this).addClass('loading');

                    // Reset scores
                    $('.score-input').val(0);

                    // Reset notes
                    $('.note-input').val('');

                    // Reset images
                    $('.image-preview-container').empty();
                    $('.view-images-btn').hide();

                    // Reset data
                    Object.keys(criteriaData).forEach(key => {
                        criteriaData[key] = { image: null, note: '', score: 0 };
                    });

                    calculateTotal();

                    setTimeout(() => {
                        $(this).removeClass('loading');
                        showAlert('Đã đặt lại tất cả dữ liệu thành công!');
                    }, 500);
                }
            });

            // Export data button
            $('#exportBtn').on('click', function () {
                const exportData = {
                    semester_id: $('#semester_id').val(),
                    tongDiem: parseInt($('#tongDiem').text()),
                    xepLoai: $('#xepLoai').text(),
                    criteria: criteriaData,
                    exportDate: new Date().toISOString()
                };

                const dataStr = JSON.stringify(exportData, null, 2);
                const dataBlob = new Blob([dataStr], { type: 'application/json' });
                const url = URL.createObjectURL(dataBlob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'conduct_scores.json';
                link.click();
                URL.revokeObjectURL(url);

                showAlert('Đã xuất dữ liệu thành công!');
            });

            // Save button
            $('#saveBtn').on('click', function () {
                let $btn = $(this);
                $btn.addClass('loading').text('Đang lưu...');

                const formData = new FormData();
                formData.append('semester_id', $('#semester_id').val());
                formData.append('total_score', parseInt($('#tongDiem').text()));
                formData.append('classification', $('#xepLoai').text());
                formData.append('saveDate', new Date().toISOString());

                const details = Object.keys(criteriaData).map(criteriaId => {
                    const detail = {
                        conduct_criteria_id: parseInt(criteriaId),
                        self_score: criteriaData[criteriaId].score,
                        note: criteriaData[criteriaId].note || ''
                    };
                    if (criteriaData[criteriaId].image && criteriaData[criteriaId].image.file) {
                        formData.append(`evidence[${criteriaId}]`, criteriaData[criteriaId].image.file);
                        detail.evidence_description = criteriaData[criteriaId].note || '';
                    }
                    return detail;
                });

                if (!details.length) {
                    showAlert('Không có tiêu chí nào để lưu!', 'error');
                    $btn.removeClass('loading').text('Lưu điểm');
                    return;
                }

                console.log('Details:', details);
                formData.append('details', JSON.stringify(details));

                $.ajax({
                    url: '{{ route('student.conduct-score.save') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        showAlert('Đã lưu điểm rèn luyện thành công!');
                        $btn.removeClass('loading').text('Lưu điểm');
                    },
                    error: function (xhr, status, error) {
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response:', xhr.responseText);
                        showAlert('Lỗi khi lưu dữ liệu: ' + (xhr.responseJSON?.message || error || 'Unknown error'), 'error');
                        $btn.removeClass('loading').text('Lưu điểm');
                    }
                });
            });

            // Custom alert function
            function showAlert(message, type = 'info') {
                if (window.innerWidth <= 768) {
                    const alertClass = type === 'error' ? 'alert-danger' : 'alert-info';
                    const alertDiv = $(`
                        <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                             style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 90%;">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    $('body').append(alertDiv);
                    setTimeout(() => {
                        alertDiv.alert('close');
                    }, 3000);
                } else {
                    alert(message);
                }
            }

            // Initialize UI
            initializeUI();
        });
    </script>
@endpush
