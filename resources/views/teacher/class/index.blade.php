@extends('layouts.teacher')

@section('title', 'Lớp học')

@section('breadcrumb')
    <x-breadcrumb.breadcrumb :links="[['label' => 'Lớp học']]" />
@endsection

@section('main')
    <div class="container-fluid pb-4">
        <!-- Header with search -->
        <div class="d-flex justify-content-between mb-4">
            <h4>Lớp học</h4>
            <form method="GET" action="{{ route('teacher.class.index') }}" class="input-group" style="max-width: 300px;">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm lớp học" aria-label="Recipient's username"
                           aria-describedby="basic-addon2">
                    <button type="submit" class="input-group-text btn btn-outline-secondary" id="basic-addon2"><i class="fas fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>

        <!-- Class table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên lớp</th>
                            <th scope="col">Sĩ số</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['classes']['data'] as $index => $class)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <div class="fw-bold">{{ $class['name'] }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ count($class['students']) }} sinh viên</span>
                                </td>
                                <td>
                                    <div class="btn-group gap-2" role="group">
                                        <!-- Xem chi tiết -->
                                        <a href="{{ route('teacher.class.infoStudent', $class['id']) }}"
                                                class="btn btn-primary btn-sm {{ count($class['students']) > 0 ? '' : 'disabled' }}"
                                                title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if(count($data['classes']['data']) == 0)
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không có lớp học nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <x-pagination.pagination :paginate="$data['classes']" />
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

        });
    </script>
@endpush
