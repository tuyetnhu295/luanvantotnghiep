@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Liệt kê đơn hàng</h2>
        <p class="text-muted">Bạn có thể tìm kiếm theo đơn hàng hoặc ngày thêm.</p>

        <div class="table-responsive">
            <table id="productTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Ngày đặt</th>
                        <th scope="col">Nhân viên</th>
                        <th scope="col">Người giao</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Thanh toán</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_order as $key => $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                            <td>{{ $order->admin_name }}</td>
                            <td>{{ $order->shipper_name }}</td>
                            <td>{{ number_format((float) $order->order_total, 0, ',', '.') }}₫</td>
                            <td>
                                @switch($order->payment_status)
                                    @case('success')
                                        <span class="badge bg-success">Thành công</span>
                                    @break

                                    @case('failed')
                                        <span class="badge bg-danger">Thất bại</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">Đang chờ xử lý</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($order->order_status)
                                    @case('Đã xác nhận')
                                        <span class="badge bg-info text-dark">Đã xác nhận</span>
                                    @break

                                    @case('Đang giao hàng')
                                        <span class="badge bg-primary">Đang giao hàng</span>
                                    @break

                                    @case('Đã giao hàng')
                                        <span class="badge bg-success">Đã giao hàng</span>
                                    @break

                                    @case('Đã hủy')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @break

                                    @case('Đang chờ xử lý')
                                        <span class="badge bg-secondary">Đang chờ xử lý</span>
                                    @break

                                    @default
                                        <span class="badge bg-danger">Hoàn trả</span>
                                @endswitch
                            </td>

                            <td style="text-align: center">
                                <a href="{{ URL::to('/admin/order/manage-order/view-order/' . $order->order_id) }}"
                                    class="text-primary me-2"><i class="bi bi-eye text-primary"></i></a>
                                <a href="{{ URL::to('/admin/order/manage-order/edit-order/' . $order->order_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                {{-- <a onclick="return confirm('Bạn có chắc là muốn xóa đơn hàng này không ?')"
                                    href="{{ URL::to('/admin/order/manage-order/delete-order/' . $order->order_id) }}"
                                    class="text-danger"><i class="bi bi-trash"></i></a> --}}
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
            $('#productTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm đơn hàng hoặc ngày thêm:",
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
