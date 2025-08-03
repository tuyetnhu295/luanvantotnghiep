@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách khách hàng</h2>
        {{-- <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?> --}}

        <p class="text-muted">Bạn có thể tìm kiếm theo số điện thoại hoặc email</p>

        <div class="table-responsive">
            <table id="customerTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Mã khách hàng</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Email</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Giới tính</th>
                        <th scope="col">Số nhà</th>
                        <th scope="col">Xã/Phường</th>
                        <th scope="col">Quận/Huyện</th>
                        <th scope="col">Tỉnh/Thành phố</th>
                        <th scope="col">Yêu cầu xóa</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer as $key => $value)
                        <tr>
                            <td>{{ $value->customer_id }}</td>
                            <td>{{ $value->customer_name }}</td>
                            <td>{{ $value->customer_email }}</td>
                            <td>{{ $value->customer_phone }}</td>
                            <td>{{ $value->customer_birthday ? \Carbon\Carbon::parse($value->customer_birthday)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                @if ($value->customer_sex == 0)
                                    Nữ
                                @else
                                    Nam
                                @endif
                            </td>
                            <td>{{ $value->address }}</td>
                            <td>{{ $value->ward }}</td>
                            <td>{{ $value->district }}</td>
                            <td>{{ $value->city}}</td>
                            <td style="text-align: center;">
                                @if ($value->delete_request == 0)
                                    <span>✅</span>
                                @else
                                    <span>❌</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align:
                                        center">
                                <a href="{{ URL::to('/admin/customers/edit/' . $value->customer_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{ URL::to('/admin/customers/delete/' . $value->customer_id) }}"
                                    class="text-danger me-2"
                                    onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')"><i
                                        class="bi bi-trash"></i></a>
                                <a href="{{ URL::to('/admin/customers/view/' . $value->customer_id) }}"
                                    class="text-primary me-2"><i class="bi bi-eye text-primary"></i></a>
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
            /* Tạo khoảng cách giữa ô tìm kiếm và bảng */
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable({
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
