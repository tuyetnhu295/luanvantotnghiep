@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách nhân viên</h2>

        @if (session('message'))
            <p class="alert alert-success">{{ session('message') }}</p>
            @php
                session()->forget('message');
            @endphp
        @endif

        <p class="text-muted">Bạn có thể tìm kiếm theo số điện thoại hoặc email</p>

        <div class="table-responsive text-center">
            <table id="adminTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Mã nhân viên</th>
                        <th scope="col">Tên nhân viên</th>
                        <th scope="col">Email</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Role</th>
                        <th scope="col">Khóa / Mở khóa</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Ngày cập nhật</th>
                        <th scope="col">Trao quyền</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admin as $value)
                        <tr>
                            <td>{{ $value->admin_id }}</td>
                            <td>{{ $value->admin_name }}</td>
                            <td>{{ $value->admin_email }}</td>
                            <td>{{ $value->admin_phone }}</td>
                            <td>{{ $value->admin_role }}</td>
                            <td class="text-center">
                                @if ($value->status == 0)
                                    <a href="{{ url('/admin/staffs/lock/' . $value->admin_id) }}">
                                        <span class="fa fa-unlock"></span>
                                    </a>
                                @else
                                    <a href="{{ url('/admin/staffs/unlock/' . $value->admin_id) }}">
                                        <span class="fa fa-lock"></span>
                                    </a>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->updated_at)->format('d/m/Y') }}</td>
                            
                            @php
                                $current_role = $value->admin_role;
                            @endphp

                            <td>
                                <form action="{{ url('/admin/staffs/assign-role/' . $value->admin_id) }}" method="POST"
                                    class="d-flex flex-column gap-1">
                                    @csrf


                                    @foreach (['superadmin', 'manager', 'staff', 'shipper'] as $role)
                                        <div class="form-check">
                                            <input class="form-check-input radio-mod" type="radio" name="selected_role"
                                                id="{{ $role }}{{ $value->admin_id }}"
                                                value="{{ $role }}"
                                                {{ $current_role === $role ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="{{ $role }}{{ $value->admin_id }}">
                                                {{ ucfirst($role) }}
                                            </label>
                                        </div>
                                    @endforeach

                                    <button type="submit" class="btn btn-sm btn-primary mt-1">Trao quyền</button>
                                </form>
                            </td>

                            <td class="text-center">
                                <a href="{{ url('/admin/staffs/edit/' . $value->admin_id) }}" class="text-primary me-2"
                                    title="Sửa"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{ url('/admin/staffs/delete/' . $value->admin_id) }}" class="text-danger me-2"
                                    onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?')" title="Xóa"><i
                                        class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        div.dataTables_filter {
            margin-bottom: 1rem;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#adminTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm số điện thoại hoặc email:",
                    lengthMenu: "Hiển thị _MENU_ dòng",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                    paginate: {
                        previous: "Trước",
                        next: "Sau"
                    },
                    zeroRecords: "Không tìm thấy dữ liệu phù hợp",
                    infoEmpty: "Không có dữ liệu",
                    infoFiltered: "(lọc từ _MAX_ mục)"
                }
            });
        });
    </script>
@endsection
