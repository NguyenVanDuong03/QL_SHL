@extends('layouts.studentAffairsDepartment')

@section('title', 'Phòng học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Phòng học']]" />
@endsection

@section('main')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-gray-800">Quản lý phòng học</h1>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    <i class="fas fa-plus"></i> Thêm phòng học
                </button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary mb-2">Danh sách phòng học</h6>
                <div class="d-flex flex-wrap gap-2">
                    <form method="GET" action="{{ route('student-affairs-department.room.index') }}" class="input-group" style="width: 250px;">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." id="searchInput"  value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary btn-search-room" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="classroomsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã phòng</th>
                                <th>Tên phòng</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($rooms['data']))
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @else
                                @foreach ($rooms['data'] ?? [] as $room)
                                    <tr data-id="{{ $room['id'] ?? $loop->iteration }}" data-name="{{ $room['name'] }}"
                                        data-status="{{ $room['status'] }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $room['name'] }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $room['status'] == 0 ? 'bg-success' : 'bg-warning' }}">{{ $room['status'] == 0 ? 'Trống' : 'Đang sử dụng' }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group gap-3">
                                                <button type="button" class="btn btn-sm btn-warning edit-room-btn"
                                                    data-id="{{ $room['id'] }}" data-name="{{ $room['name'] }}"
                                                    data-status="{{ $room['status'] }}"
                                                    data-current-page={{ $rooms['current_page'] }}
                                                    data-search="{{ request('search') }}"
                                                    data-bs-target="#editRoomModal" data-bs-toggle="modal"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-room-btn"
                                                    data-bs-target="#deleteRoomModal" data-id="{{ $room['id'] }}"
                                                    data-current-page={{ $rooms['current_page'] }} data-search="{{ request('search') }}"
                                                    data-bs-toggle="modal"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <x-pagination.pagination :paginate="$rooms" />
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade auto-reset-modal" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel"
        >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Thêm phòng học mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addRoomForm" method="POST" action="{{ route('student-affairs-department.room.create') }}">
                    <div class="modal-body">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="room_name" class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" id="room_name" name="name" required>
                            <div class="text-danger text-danger-error"></div>
                            @error('name')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="room_status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="room_status" name="status" required>
                                <option value="0">Trống</option>
                                <option value="1">Đang sử dụng</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="saveNewRoom">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Edit and Delete Modals -->
    <!-- Edit Room Modal -->
    <div class="modal fade auto-reset-modal" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel"
        >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomModalLabel">Chỉnh sửa phòng học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editRoomForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="current_page" name="current_page">
                        <input type="hidden" name="search" class="search_keyword">
                        <div class="mb-3">
                            <label for="edit_room_name" class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" id="edit_room_name" name="name" required>
                            <div class="text-danger text-danger-error"></div>
                            @error('name')
                                <div class="text-danger text-danger-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_room_status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="edit_room_status" name="status" required>
                                <option value="0">Trống</option>
                                <option value="1">Đang sử dụng</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="saveEditRoom">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Room Modal -->
    <div class="modal fade auto-reset-modal" id="deleteRoomModal" tabindex="-1" aria-labelledby="deleteRoomModalLabel"
        >
        <div class="modal-dialog">
            <form method="POST" id="deleteRoomForm">
                @csrf
                @method('DELETE')
                <input type="hidden" class="current_page" name="current_page">
                <input type="hidden" name="search" class="search_keyword">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRoomModalLabel">Xác nhận xóa phòng học</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa phòng học <strong id="delete_room_name"></strong>?</p>
                        <p class="text-danger">Hành động này không thể hoàn tác.</p>
                        <input type="hidden" id="delete_room_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger" id="confirmDeleteRoom">Xóa</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Reset form when modal is closed
            $('.auto-reset-modal').on('hidden.bs.modal', function() {
                $('.text-danger-error').text('');
                $('.form-control').removeClass('is-invalid');
            });

            $('#saveNewRoom').on('click', function(e) {
                let isValid = true;

                $('.text-danger-error').text('');
                $('.form-control').removeClass('is-invalid');

                let name = $('#room_name').val().trim();
                if (name === '') {
                    $('#room_name').addClass('is-invalid');
                    $('.text-danger-error').text('Tên phòng không được để trống.');
                    isValid = false;
                }
                if (!isValid) {
                    e.preventDefault();
                    return;
                }
                $('#addRoomForm').submit();

            });

            $('#saveEditRoom').on('click', function(e) {
                let isValid = true;

                $('.text-danger-error').text('');
                $('.form-control').removeClass('is-invalid');

                let name = $('#edit_room_name').val().trim();
                if (name === '') {
                    $('#edit_room_name').addClass('is-invalid');
                    $('.text-danger-error').text('Tên phòng không được để trống.');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    return;
                }

                $('#editRoomForm').submit();

            });

            $('tbody').on('click', '.edit-room-btn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let status = $(this).data('status');
                let currentPage = $(this).data('current-page');
                let searchKeyword = $(this).data('search');

                $('#editRoomForm').attr('action', `/student-affairs-department/room/${id}`);

                $('#edit_room_name').val(name);
                $('.current_page').val(currentPage);
                $('.search_keyword').val(searchKeyword);
                if (status == 0) {
                    $('#edit_room_status').val(0);
                } else {
                    $('#edit_room_status').val(1);
                }
            });

            $('.delete-room-btn').on('click', function(e) {
                e.preventDefault();
                let currentPage = $(this).data('current-page');
                let id = $(this).data('id');
                let searchKeyword = $(this).data('search');

                $('.current_page').val(currentPage);
                $('.search_keyword').val(searchKeyword);
                $('#deleteRoomForm').attr('action', `/student-affairs-department/room/${id}`);
            });

        });
    </script>
@endpush
