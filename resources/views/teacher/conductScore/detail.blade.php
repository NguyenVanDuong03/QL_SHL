@extends('layouts.teacher')

@section('title', 'Chi tiết điểm rèn luyện')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[
        ['label' => 'Điểm rèn luyện', 'url' => 'teacher.conduct-score.index'],
        ['label' => 'Danh sách lớp học', 'url' => 'teacher.conduct-score.infoConductScore', 'params' => [
            'conduct_evaluation_period_id' => $data['conduct_evaluation_period_id']
        ]],
        ['label' => 'Danh sách sinh viên', 'url' => 'teacher.conduct-score.list', 'params' => [
            'study_class_id' => $data['study_class_id'],
            'conduct_evaluation_period_id' => $data['conduct_evaluation_period_id']
        ]],
        ['label' => 'Chi tiết sinh viên']
    ]"/>
@endsection

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
    <div class="m-4">
        <!-- Form Controls -->
        <div class="row form-controls-mobile">
            <div class="col-md-3 col-12">
                <a href="{{ route('teacher.conduct-score.list', ['study_class_id' => $data['study_class_id'], 'conduct_evaluation_period_id' => $data['conduct_evaluation_period_id']]) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
            <div class="col-md-9 col-12">
                <div class="score-summary">
                    <div class="row text-end">
                        <div class="col-6">
                            <span>Sinh viên: <span>{{ $data['student']['user']['name'] }}</span></span>
                        </div>
                        <div class="col-6">
                            <span>Mã sinh viên: <span>{{ $data['student']['student_code'] }}</span></span>
                        </div>
                        <div class="col-6">
                            <span>Email: <span>{{ $data['student']['user']['email'] }}</span></span>
                        </div>
                        <div class="col-6">
                            <span>Lớp học: <span>{{ $data['student']['study_class']['name'] }}</span></span>
                        </div>
                        @if ($data['checkConductEvaluationPeriodBySemesterId'])
                            <div class="col-6">
                                <span>Tổng điểm SV: <span id="tongDiemSV">0</span></span>
                            </div>
                            <div class="col-6">
                                <span>Tổng điểm GVCN: <span id="tongDiemGVCN">0</span></span>
                            </div>
                            <div class="col-6">
                                <span>Tổng điểm khoa: <span>{{ $data['calculateTotalScore'] }}</span></span>
                            </div>
                        @endif
                        <div class="col-6">
                            <strong>Điểm cuối cùng: <span>{{ $data['calculateTotalScore'] }}</span></strong>
                        </div>
                        <div class="col-6">
                            <strong>Điểm quy đổi: <span id="diemQuyDoi">0</span></strong>
                        </div>
                        <div class="col-6 text-center text-md-end">
                            <strong>Xếp loại: <span id="xepLoai">Chưa có dữ liệu</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Banner -->
        @if(!$data['checkConductEvaluationPeriod'])
            <div class="alert alert-warning-custom" role="alert">
                CHƯA ĐẾN THỜI GIAN CHẤM ĐIỂM RÈN LUYỆN
            </div>
        @endif

        <h4 class="text-center">Đánh giá điểm rèn luyện</h4>
        @php
            $sectionHeaders = [
                0 => 'ĐÁNH GIÁ VỀ Ý THỨ THAM GIA HỌC TẬP',
                5 => 'ĐÁNH GIÁ VỀ Ý THỨC VÀ KẾT QUẢ CHẤP HÀNH NỘI QUY, QUY CHẾ, QUY ĐỊNH CỦA NHÀ TRƯỜNG',
                9 => 'ĐÁNH GIÁ Ý THỨC, KẾT QUẢ THAM GIA CÁC HOẠT ĐỘNG CHÍNH TRỊ, XÃ HỘI, VĂN HÓA, VĂN NGHỆ THỂ THAO, PHÒNG CHỐNG TỘI PHẠM VÀ CÁC TỆ NẠN XÃ HỘI',
                13 => 'ĐÁNH GIÁ Ý THỨC CÔNG DÂN TRONG QUAN HỆ CỘNG ĐỒNG',
                16 => 'ĐÁNH GIÁ Ý THỨC, KẾT QUẢ THAM GIA CÔNG TÁC CÁN BỘ LỚP, ĐOÀN THỂ, TỔ CHỨC TRONG TRƯỜNG HOẶC ĐẠT THÀNH TÍCH ĐẶC BIỆT TRONG HỌC TẬP, RÈN LUYỆN (SINH VIÊN ĐẠT ĐƯỢC NHIỀU TIÊU CHÍ THÌ CỘNG ĐIỂM KHÔNG ĐƯỢC VƯỢT QUÁ 10 ĐIỂM)',
            ];

            $subSectionHeaders = [
                3 => '(ĐIỂM ĐƯỢC TÍNH TỰ ĐỘNG DỰA TRÊN ĐIỂM HỌC TẬP CỦA SINH VIÊN)',
                4 => '(ĐIỂM ĐƯỢC TÍNH TỰ ĐỘNG DỰA TRÊN ĐIỂM HỌC TẬP CỦA SINH VIÊN)',
            ];
        @endphp
            <!-- Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 table-responsive-mobile" id="diemRenLuyenTable">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th style="min-width: 200px;">Nội dung đánh giá</th>
                        <th style="min-width: 91px;">Điểm tối đa</th>
                        <th style="min-width: 70px;">Điểm SV</th>
                        <th style="min-width: 70px;">Điểm GVCN</th>
                        <th style="min-width: 250px;">Hành động & Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($data['checkConductEvaluationPeriodBySemesterId'])
                        @forelse($data['getConductCriteriaData'] ?? [] as $index => $item)
                            @if (isset($sectionHeaders[$index]))
                                <tr>
                                    <td colspan="6" class="bg-secondary text-white">
                                        <strong>{{ $sectionHeaders[$index] }}</strong>
                                    </td>
                                </tr>
                            @endif
                            <tr class="criteria-row" data-criteria="{{ $item['criterion_id'] }}"
                                data-score-id="{{ $data['student_conduct_score_id'] }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['content'] }} {!! isset($subSectionHeaders[$index]) ? '<p class="m-0 p-0"><small class="text-danger fs-7">' . $subSectionHeaders[$index] . '</small></p>' : '' !!}</td>
                                <td class="text-center">{{ $item['max_score'] }}</td>
                                <td class="text-center">
                                    <input type="number" class="form-control form-control-sm"
                                           value="{{ $item['self_score'] ?? 0 }}"
                                           disabled>
                                </td>
                                <td class="text-center">
                                    <input type="number" class="form-control form-control-sm score-input"
                                           min="0" max="{{ $item['max_score'] }}"
                                           value="{{ $item['class_score'] ?? 0 }}"
                                           data-max="{{ $item['max_score'] }}"
                                        {{ $data['checkConductEvaluationPeriodBySemesterId'] ? '' : 'disabled' }}
                                        {{ $index == 4 || $index == 3 ? 'disabled' : '' }}
                                    >
                                </td>
                                <td>
                                    <div class="criteria-actions">
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-sm view-images-btn"
                                                    style="display: {{ $item['evidence_path'] ? 'inline-block' : 'none' }};">
                                                <i class="fas fa-eye"></i> Xem
                                            </button>
                                            <div class="image-preview-container">
                                                @if($item['evidence_path'])
                                                    <div class="image-preview"
                                                         data-criteria="{{ $item['criterion_id'] }}">
                                                        <img src="{{ asset('storage/' . $item['evidence_path']) }}"
                                                             alt="Evidence Image">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div>Ghi chú:
                                            <p class="note-input">{{ isset($item['note']) && !empty($item['note']) ? $item['note'] : '' }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @php
                                $currentSection = -1;
                            @endphp
                            @foreach($data['conductCriterias'] ?? [] as $index2 => $criteria)
                                @if (isset($sectionHeaders[$index2]))
                                    <tr>
                                        <td colspan="6" class="bg-secondary text-white">
                                            <strong>{{ $sectionHeaders[$index2] }}</strong>
                                        </td>
                                    </tr>
                                @endif
                                @if (isset($sectionHeaders[$index2]) && $currentSection !== $index2)
                                    @php
                                        $currentSection = $index2;
                                    @endphp
                                    <tr>
                                        <td colspan="6" class="bg-secondary text-white">
                                            <strong>{{ $sectionHeaders[$index2] }}</strong>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="criteria-row" data-criteria="{{ $criteria['id'] }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $criteria['content'] }} {!! isset($subSectionHeaders[$index2]) ? '<p class="m-0 p-0"><small class="text-danger fs-7">' . $subSectionHeaders[$index2] . '</small></p>' : '' !!}</td>
                                    <td class="text-center">{{ $criteria['max_score'] }}</td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm"
                                               value="0" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm score-input"
                                               min="0" max="{{ $criteria['max_score'] }}"
                                               value="{{ $criteria['class_score'] ?? 0 }}"
                                               data-max="{{ $criteria['max_score'] }}"
                                            {{ $data['checkConductEvaluationPeriodBySemesterId'] ? '' : 'disabled' }}
                                            {{ $index == 4 || $index == 3 ? 'disabled' : '' }}
                                        >
                                    </td>
                                    <td>
                                        <div class="criteria-actions">
                                            <div>Ghi chú:
                                                <p class="note-input">{{ isset($criteria['note']) && !empty($criteria['note']) ? $criteria['note'] : '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <strong>Chưa có dữ liệu điểm rèn luyện cho sinh viên này.</strong>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        @if ($data['checkConductEvaluationPeriodBySemesterId'])
            <div class="row mt-4">
                <div class="col-12 text-end mobile-buttons">
                    <button type="button" class="btn btn-secondary me-2" id="resetBtn">Đặt lại</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Lưu điểm</button>
                </div>
            </div>
        @endif

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
                class_score: {{ $item['class_score'] ?? 0 }},
                scoreId: '{{ $data['student_conduct_score_id'] }}'
            };
            @empty
                @foreach($data['conductCriterias'] ?? [] as $criteria)
                criteriaData[{{ $criteria['id'] }}] = {
                image: null,
                note: '{{ $criteria['note'] ?? '' }}',
                class_score: {{ $criteria['class_score'] ?? 0 }},
                scoreId: null
            };
            @endforeach
            @endforelse
            @endif

            // Ensure all table rows have corresponding criteriaData entries
            $('.criteria-row').each(function () {
                const criteriaId = $(this).data('criteria');
                const scoreId = $(this).data('score-id');
                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = {
                        image: null,
                        note: '',
                        class_score: 0,
                        scoreId: scoreId
                    };
                } else {
                    criteriaData[criteriaId].scoreId = scoreId;
                }
            });

            // Function to calculate totals
            function calculateTotal() {
                let total = 0; // Total score (now based on class_score)
                let totalSV = 0; // Total student score
                let totalGVCN = 0; // Total teacher score
                let lastFourTotal = 0;
                const rows = $('.criteria-row');
                const lastFourRows = rows.slice(-4);

                rows.each(function (index) {
                    const score = parseInt($(this).find('.score-input').val()) || 0; // Class score
                    const studentScore = parseInt($(this).find('td:eq(3) input').val()) || 0; // Student score
                    total += score;
                    totalSV += studentScore;
                    totalGVCN += score;
                    if (index >= rows.length - 4) {
                        lastFourTotal += score;
                    }
                });

                const diemQuyDoi = @json($data['calculateTotalScore']);
                $('#tongDiem').text(total);
                $('#tongDiemSV').text(totalSV);
                $('#tongDiemGVCN').text(totalGVCN);
                $('#diemQuyDoi').text((diemQuyDoi / 100).toFixed(2));
                let classification = '';
                if (diemQuyDoi >= 90) {
                    classification = 'Xuất sắc';
                } else if (diemQuyDoi >= 80) {
                    classification = 'Tốt';
                } else if (diemQuyDoi >= 65) {
                    classification = 'Khá';
                } else if (diemQuyDoi >= 50) {
                    classification = 'Trung bình';
                } else if (diemQuyDoi >= 35) {
                    classification = 'Yếu';
                } else {
                    classification = 'Kém';
                }
                $('#xepLoai').text(classification);
                return lastFourTotal;
            }

            // Function to update image preview
            function updateImagePreview(criteriaId) {
                const row = $(`.criteria-row[data-criteria="${criteriaId}"]`);
                const previewContainer = row.find('.image-preview-container');
                const viewBtn = row.find('.view-images-btn');

                previewContainer.empty();

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = {image: null, note: '', class_score: 0, scoreId: null};
                }

                if (criteriaData[criteriaId].image) {
                    const image = criteriaData[criteriaId].image;
                    const preview = $(`
                            <div class="image-preview" data-criteria="${criteriaId}">
                                <img src="${image.data}" alt="${image.name}">
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
                        const scoreInput = row.find('.score-input');
                        scoreInput.val(criteriaData[criteriaId].class_score || 0);
                        row.find('.note-input').text(criteriaData[criteriaId].note || '');
                        updateImagePreview(criteriaId);
                    }
                });
                calculateTotal();
            }

            $(document).on('click', '.view-images-btn', function () {
                const criteriaId = $(this).closest('.criteria-row').data('criteria');
                const modalBody = $('#imageModalBody');

                modalBody.empty();

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = {image: null, note: '', class_score: 0, scoreId: null};
                }

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

            let debounceTimer;
            $('.score-input').on('input change', function () {
                clearTimeout(debounceTimer);
                let $input = $(this);
                const criteriaId = $(this).closest('.criteria-row').data('criteria');
                const rowIndex = $('.criteria-row').index($input.closest('.criteria-row'));

                if (!criteriaData[criteriaId]) {
                    criteriaData[criteriaId] = {image: null, note: '', class_score: 0, scoreId: null};
                }

                debounceTimer = setTimeout(function () {
                    let maxScore = parseInt($input.data('max'));
                    let currentValue = parseInt($input.val()) || 0;

                    const isLastFour = rowIndex >= $('.criteria-row').length - 4;
                    if (isLastFour) {
                        let lastFourTotal = 0;
                        $('.criteria-row').slice(-4).each(function (index, element) {
                            const score = parseInt($(element).find('.score-input').val()) || 0;
                            if (element === $input.closest('.criteria-row')[0]) {
                                lastFourTotal += currentValue;
                            } else {
                                lastFourTotal += score;
                            }
                        });

                        if (lastFourTotal > 10) {
                            const allowedValue = 10 - (lastFourTotal - currentValue);
                            currentValue = allowedValue < 0 ? 0 : allowedValue;
                            $input.val(currentValue);
                            toastr.error('Tổng điểm của mục 5 không được vượt quá 10!');
                        }
                    }

                    if (currentValue > maxScore) {
                        $input.val(maxScore);
                        currentValue = maxScore;
                        toastr.error('Điểm không được vượt quá điểm tối đa: ' + maxScore);
                    } else if (currentValue < 0) {
                        $input.val(0);
                        currentValue = 0;
                    }

                    criteriaData[criteriaId].class_score = currentValue;
                    calculateTotal();
                }, 300);
            });

            $('#resetBtn').on('click', function () {
                $(this).addClass('loading');

                $('.score-input').each(function () {
                    const criteriaId = $(this).closest('.criteria-row').data('criteria');
                    $(this).val(criteriaData[criteriaId].class_score || 0);
                });

                Object.keys(criteriaData).forEach(key => {
                    criteriaData[key].class_score = criteriaData[key].class_score || 0;
                });

                calculateTotal();
                $(this).removeClass('loading');
            });

            $('#saveBtn').on('click', function () {
                let $btn = $(this);
                $btn.addClass('loading').text('Đang lưu...');

                const lastFourTotal = calculateTotal();
                if (lastFourTotal > 10) {
                    toastr.error('Tổng điểm của 4 tiêu chí cuối không được vượt quá 10!');
                    $btn.removeClass('loading').text('Lưu điểm');
                    return;
                }

                const details = Object.keys(criteriaData).map(criteriaId => ({
                    student_conduct_score_id: criteriaData[criteriaId].scoreId,
                    conduct_criteria_id: parseInt(criteriaId),
                    class_score: criteriaData[criteriaId].class_score || 0
                }));

                if (!details.length) {
                    toastr.error('Không có tiêu chí nào để lưu!');
                    $btn.removeClass('loading').text('Lưu điểm');
                    return;
                }

                const formData = new FormData();
                formData.append('details', JSON.stringify(details));
                formData.append('conduct_evaluation_period_id', {{ $data['conduct_evaluation_period_id'] }});
                formData.append('student_id', '{{ $data['student']['id'] }}');

                $.ajax({
                    url: '{{ route('teacher.conduct-score.save') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success('Đã lưu điểm rèn luyện thành công!');
                        $btn.removeClass('loading').text('Lưu điểm');
                    },
                    error: function (xhr, status, error) {
                        toastr.error('Đã xảy ra lỗi khi lưu điểm rèn luyện, vui lòng thử lại sau!');
                        $btn.removeClass('loading').text('Lưu điểm');
                    }
                });
            });

            initializeUI();
        });
    </script>
@endpush
