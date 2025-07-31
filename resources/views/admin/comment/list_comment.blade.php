@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách bình luận</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo tên khách hàng hoặc ngày bình luận.</p>

        <div class="table-responsive">
            <table id="categoryTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Màu</th>
                        <th scope="col">Kích cỡ</th>
                        <th scope="col">Bình luận</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comment as $key => $val)
                        <tr>
                            <td>{{ $val->review_id }}</td>
                            <td>{{ $val->customer_name }}</td>
                            <td>{{ $val->product_name }}</td>
                            <td>{{ $val->color }}</td>
                            <td>{{ $val->size }}</td>
                            <td>{{ $val->review_text }} </td>
                            <td>
                                @switch($val->status)
                                    @case('pending')
                                        <span class="badge bg-secondary">Đang chờ xử lý</span>
                                    @break

                                    @case('approved')
                                        <span class="badge bg-success">Đã phê duyệt</span>
                                    @break

                                    @default
                                        <span class="badge bg-danger">Đã từ chối</span>
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align:
                                        center">
                                <a href="{{ URL::to('/admin/comments/update/' . $val->review_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
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
            $('#categoryTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm tên khách hàng hoặc ngày bình luận:",
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
