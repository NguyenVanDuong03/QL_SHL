@extends('layouts.studentAffairsDepartment')

@section('title', 'Phòng học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Phòng học']]" />
@endsection

{{-- @section('main') --}}
{{--    <div class="container-fluid py-4"> --}}
{{--        <div class="row mb-4"> --}}
{{--            <div class="col-md-6"> --}}
{{--                <h1 class="h3 mb-2 text-gray-800">Quản lý phòng học</h1> --}}
{{--                <p class="mb-4">Danh sách tất cả các phòng học trong hệ thống.</p> --}}
{{--            </div> --}}
{{--            <div class="col-md-6 text-md-end"> --}}
{{--                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassroomModal"> --}}
{{--                    <i class="fas fa-plus"></i> Thêm phòng học --}}
{{--                </button> --}}
{{--            </div> --}}
{{--        </div> --}}

{{--        <div class="card shadow mb-4"> --}}
{{--            <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center"> --}}
{{--                <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng học</h6> --}}
{{--                <div class="d-flex flex-wrap gap-2"> --}}
{{--                    <div class="input-group" style="width: 250px;"> --}}
{{--                        <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput"> --}}
{{--                        <button class="btn btn-outline-secondary" type="button"> --}}
{{--                            <i class="fas fa-search"></i> --}}
{{--                        </button> --}}
{{--                    </div> --}}
{{--                    <div class="dropdown"> --}}
{{--                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false"> --}}
{{--                            Lọc --}}
{{--                        </button> --}}
{{--                        <ul class="dropdown-menu" aria-labelledby="filterDropdown"> --}}
{{--                            <li><a class="dropdown-item" href="#">Tất cả</a></li> --}}
{{--                            <li><a class="dropdown-item" href="#">Đang sử dụng</a></li> --}}
{{--                            <li><a class="dropdown-item" href="#">Đang bảo trì</a></li> --}}
{{--                            <li><a class="dropdown-item" href="#">Trống</a></li> --}}
{{--                        </ul> --}}
{{--                    </div> --}}
{{--                </div> --}}
{{--            </div> --}}
{{--            <div class="card-body"> --}}
{{--                <div class="table-responsive"> --}}
{{--                    <table class="table table-bordered table-hover" id="classroomsTable" width="100%" cellspacing="0"> --}}
{{--                        <thead> --}}
{{--                        <tr> --}}
{{--                            <th>Mã phòng</th> --}}
{{--                            <th>Tên phòng</th> --}}
{{--                            <th>Trạng thái</th> --}}
{{--                            <th>Thao tác</th> --}}
{{--                        </tr> --}}
{{--                        </thead> --}}
{{--                        <tbody> --}}
{{--                        @if (empty($rooms['data'])) --}}
{{--                            <tr> --}}
{{--                                <td colspan="4" class="text-center">Không có dữ liệu</td> --}}
{{--                            </tr> --}}
{{--                        @else --}}
{{--                            @foreach ($rooms['data'] ?? [] as $room) --}}
{{--                                <tr> --}}
{{--                                    <td>{{ $loop->iteration }}</td> --}}
{{--                                    <td>{{ $room['name'] }}</td> --}}
{{--                                    <td> --}}
{{--                                        <span class="badge {{ $room['status'] == 0 ? 'bg-success' : 'bg-warning' }}">{{ $room['status'] == 0 ? 'Trống' : 'Đang sử dụng' }}</span> --}}
{{--                                    </td> --}}
{{--                                    <td> --}}
{{--                                        <div class="btn-group gap-3"> --}}
{{--                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Chỉnh sửa"> --}}
{{--                                                <i class="fas fa-edit"></i> --}}
{{--                                            </button> --}}
{{--                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Xóa"> --}}
{{--                                                <i class="fas fa-trash"></i> --}}
{{--                                            </button> --}}
{{--                                        </div> --}}
{{--                                    </td> --}}
{{--                                </tr> --}}
{{--                            @endforeach --}}
{{--                        @endif --}}
{{--                        </tbody> --}}
{{--                    </table> --}}
{{--                </div> --}}

{{--                <div class="d-flex justify-content-between align-items-center mt-3"> --}}
{{--                    <div> --}}
{{--                        <span>Hiển thị 1 đến 5 của 5 bản ghi</span> --}}
{{--                    </div> --}}
{{--                    <x-pagination.pagination :paginate="$rooms" /> --}}
{{--                </div> --}}
{{--            </div> --}}
{{--        </div> --}}
{{--    </div> --}}

{{--    <!-- Add Classroom Modal --> --}}
{{--    <div class="modal fade" id="addClassroomModal" tabindex="-1" aria-labelledby="addClassroomModalLabel" aria-hidden="true"> --}}
{{--        <div class="modal-dialog modal-lg"> --}}
{{--            <div class="modal-content"> --}}
{{--                <div class="modal-header"> --}}
{{--                    <h5 class="modal-title" id="addClassroomModalLabel">Thêm phòng học mới</h5> --}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
{{--                </div> --}}
{{--                <div class="modal-body"> --}}
{{--                    <form id="addClassroomForm"> --}}
{{--                        <div class="row mb-3"> --}}
{{--                            <div class="col-md-6"> --}}
{{--                                <label for="code" class="form-label">Mã phòng</label> --}}
{{--                                <input type="text" class="form-control" id="code" name="code" required> --}}
{{--                            </div> --}}
{{--                            <div class="col-md-6"> --}}
{{--                                <label for="name" class="form-label">Tên phòng</label> --}}
{{--                                <input type="text" class="form-control" id="name" name="name" required> --}}
{{--                            </div> --}}
{{--                        </div> --}}
{{--                        <div class="row mb-3"> --}}
{{--                            <div class="col-md-6"> --}}
{{--                                <label for="building" class="form-label">Tòa nhà</label> --}}
{{--                                <select class="form-select" id="building" name="building" required> --}}
{{--                                    <option value="">Chọn tòa nhà</option> --}}
{{--                                    <option value="A">Tòa nhà A</option> --}}
{{--                                    <option value="B">Tòa nhà B</option> --}}
{{--                                    <option value="C">Tòa nhà C</option> --}}
{{--                                </select> --}}
{{--                            </div> --}}
{{--                            <div class="col-md-6"> --}}
{{--                                <label for="capacity" class="form-label">Sức chứa</label> --}}
{{--                                <input type="number" class="form-control" id="capacity" name="capacity" min="1" required> --}}
{{--                            </div> --}}
{{--                        </div> --}}
{{--                        <div class="row mb-3"> --}}
{{--                            <div class="col-md-6"> --}}
{{--                                <label for="type" class="form-label">Loại phòng</label> --}}
{{--                                <select class="form-select" id="type" name="type" required> --}}
{{--                                    <option value="">Chọn loại phòng</option> --}}
{{--                                    <option value="Lý thuyết">Lý thuyết</option> --}}
{{--                                    <option value="Thực hành">Thực hành</option> --}}
{{--                                    <option value="Hội trường">Hội trường</option> --}}
{{--                                    <option value="Phòng họp">Phòng họp</option> --}}
{{--                                </select> --}}
{{--                            </div> --}}
{{--                            <div class="col-md-6"> --}}
{{--                                <label for="status" class="form-label">Trạng thái</label> --}}
{{--                                <select class="form-select" id="status" name="status" required> --}}
{{--                                    <option value="">Chọn trạng thái</option> --}}
{{--                                    <option value="Trống">Trống</option> --}}
{{--                                    <option value="Đang sử dụng">Đang sử dụng</option> --}}
{{--                                    <option value="Đang bảo trì">Đang bảo trì</option> --}}
{{--                                </select> --}}
{{--                            </div> --}}
{{--                        </div> --}}
{{--                        <div class="mb-3"> --}}
{{--                            <label for="description" class="form-label">Mô tả</label> --}}
{{--                            <textarea class="form-control" id="description" name="description" rows="3"></textarea> --}}
{{--                        </div> --}}
{{--                        <div class="mb-3"> --}}
{{--                            <label for="equipment" class="form-label">Thiết bị</label> --}}
{{--                            <div class="form-check"> --}}
{{--                                <input class="form-check-input" type="checkbox" value="projector" id="projector" name="equipment[]"> --}}
{{--                                <label class="form-check-label" for="projector"> --}}
{{--                                    Máy chiếu --}}
{{--                                </label> --}}
{{--                            </div> --}}
{{--                            <div class="form-check"> --}}
{{--                                <input class="form-check-input" type="checkbox" value="computer" id="computer" name="equipment[]"> --}}
{{--                                <label class="form-check-label" for="computer"> --}}
{{--                                    Máy tính --}}
{{--                                </label> --}}
{{--                            </div> --}}
{{--                            <div class="form-check"> --}}
{{--                                <input class="form-check-input" type="checkbox" value="air_conditioner" id="air_conditioner" name="equipment[]"> --}}
{{--                                <label class="form-check-label" for="air_conditioner"> --}}
{{--                                    Điều hòa --}}
{{--                                </label> --}}
{{--                            </div> --}}
{{--                            <div class="form-check"> --}}
{{--                                <input class="form-check-input" type="checkbox" value="sound_system" id="sound_system" name="equipment[]"> --}}
{{--                                <label class="form-check-label" for="sound_system"> --}}
{{--                                    Hệ thống âm thanh --}}
{{--                                </label> --}}
{{--                            </div> --}}
{{--                        </div> --}}
{{--                    </form> --}}
{{--                </div> --}}
{{--                <div class="modal-footer"> --}}
{{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button> --}}
{{--                    <button type="button" class="btn btn-primary">Lưu</button> --}}
{{--                </div> --}}
{{--            </div> --}}
{{--        </div> --}}
{{--    </div> --}}

{{--    <!-- JavaScript for initializing tooltips and other functionality --> --}}
{{--    <script> --}}
{{--        document.addEventListener('DOMContentLoaded', function() { --}}
{{--            // Initialize tooltips --}}
{{--            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')) --}}
{{--            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { --}}
{{--                return new bootstrap.Tooltip(tooltipTriggerEl) --}}
{{--            }); --}}

{{--            // Search functionality --}}
{{--            document.getElementById('searchInput').addEventListener('keyup', function() { --}}
{{--                var input, filter, table, tr, td, i, txtValue; --}}
{{--                input = document.getElementById('searchInput'); --}}
{{--                filter = input.value.toUpperCase(); --}}
{{--                table = document.getElementById('classroomsTable'); --}}
{{--                tr = table.getElementsByTagName('tr'); --}}

{{--                for (i = 0; i < tr.length; i++) { --}}
{{--                    // Skip header row --}}
{{--                    if (i === 0) continue; --}}

{{--                    let found = false; --}}
{{--                    // Loop through all table cells in the row --}}
{{--                    for (let j = 0; j < 5; j++) { // Only search first 5 columns --}}
{{--                        td = tr[i].getElementsByTagName('td')[j]; --}}
{{--                        if (td) { --}}
{{--                            txtValue = td.textContent || td.innerText; --}}
{{--                            if (txtValue.toUpperCase().indexOf(filter) > -1) { --}}
{{--                                found = true; --}}
{{--                                break; --}}
{{--                            } --}}
{{--                        } --}}
{{--                    } --}}

{{--                    if (found) { --}}
{{--                        tr[i].style.display = ''; --}}
{{--                    } else { --}}
{{--                        tr[i].style.display = 'none'; --}}
{{--                    } --}}
{{--                } --}}
{{--            }); --}}
{{--        }); --}}
{{--    </script> --}}
{{-- @endsection --}}

@section('main')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-gray-800">Quản lý phòng học</h1>
                <p class="mb-4">Danh sách tất cả các phòng học trong hệ thống.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    <i class="fas fa-plus"></i> Thêm phòng học
                </button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng học</h6>
                <div class="d-flex flex-wrap gap-2">
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
                        <button class="btn btn-outline-secondary btn-search-room" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
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
                                                    data-status="{{ $room['status'] }}" data-bs-target="#editRoomModal"
                                                    data-bs-toggle="modal" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-room-btn"
                                                    data-bs-target="#deleteRoomModal" data-id="{{ $room['id'] }}"
                                                    data-bs-toggle="modal" title="Xóa">
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

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <span>Hiển thị {{ $rooms['from'] ?? 1 }} đến {{ $rooms['to'] ?? count($rooms['data'] ?? []) }} của
                            {{ $rooms['total'] ?? count($rooms['data'] ?? []) }} bản ghi</span>
                    </div>
                    <x-pagination.pagination :paginate="$rooms" />
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade auto-reset-modal" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel"
        aria-hidden="true">
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
        aria-hidden="true">
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
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteRoomForm">
                @csrf
                @method('DELETE')
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

                $('#editRoomForm').attr('action', `/student-affairs-department/room/${id}`);

                $('#edit_room_name').val(name);
                if (status == 0) {
                    $('#edit_room_status').val(0);
                } else {
                    $('#edit_room_status').val(1);
                }
            });

            $('.delete-room-btn').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                $('#deleteRoomForm').attr('action', `/student-affairs-department/room/${id}`);
            });

            $('.btn-search-room').on('click', function() {
                let searchValue = $('#searchInput').val().toLowerCase();

                $.ajax({
                    url: '{{ route('student-affairs-department.room.index') }}',
                    type: 'GET',
                    data: {
                        search: searchValue
                    },
                    success: function(response) {
                        const rooms = response.data;
                        const tbody = $('tbody');
                        tbody.empty();

                        if(rooms.length === 0) {
                            tbody.append('<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>');
                        } else {
                            rooms.forEach((room, index) => {
                                tbody.append(`
                                    <tr data-id="${room.id}" data-name="${room.name}" data-status="${room.status}">
                                        <td>${index + 1}</td>
                                        <td>${room.name}</td>
                                        <td>
                                            <span class="badge ${room.status == 0 ? 'bg-success' : 'bg-warning'}">${room.status == 0 ? 'Trống' : 'Đang sử dụng'}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group gap-3">
                                                <button type="button" class="btn btn-sm btn-warning edit-room-btn" data-id="${room.id}" data-name="${room.name}" data-status="${room.status}" data-bs-target="#editRoomModal" data-bs-toggle="modal" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-room-btn" data-bs-target="#deleteRoomModal" data-id="${room.id}" data-bs-toggle="modal" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            })

        });
    </script>
@endpush
