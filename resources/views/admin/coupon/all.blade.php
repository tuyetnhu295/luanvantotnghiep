@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách mã khuyến mãi</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo mã khuyến mãi hoặc ngày thêm mã khuyến mãi</p>

        <div class="table-responsive">
            <table id="couponTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Mã khuyến mãi</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Loại giảm giá</th>
                        <th scope="col">Giá trị giảm giá</th>
                        <th scope="col">Giá trị đơn hàng tối thiểu</th>
                        <th scope="col">Loại khách hàng áp dụng</th>
                        <th scope="col">Ngày bắt đầu</th>
                        <th scope="col">Ngày kết thúc</th>
                        <th scope="col">Số lần sử dụng tối đa</th>
                        <th scope="col">Số lần đã sử dụng</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupon as $key => $value)
                        <tr>
                            <td>{{ $value->coupon_id }}</td>
                            <td>{{ $value->coupon_code }}</td>
                            <td>{{ $value->description }}</td>
                            <td>
                                @if ($value->discount_type == 'percentage')
                                    Theo phần trăm
                                @else
                                    Số tiền
                                @endif
                            </td>
                            <td>
                                @if ($value->discount_type == 'percentage')
                                    {{ $value->discount_value }} %
                                @else
                                    {{ number_format($value->discount_value, 0, ',', '.') }}₫
                                @endif
                            </td>
                            <td>{{ number_format($value->min_order_value, 0, ',', '.') }}₫</td>
                            <td>
                                @if ($value->customer_type == 'new')
                                    Khách hàng mới
                                @elseif($value->customer_type == 'all')
                                    Tất cả khách hàng
                                @else
                                    Khách hàng lâu năm
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($value->start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->end_date)->format('d/m/Y') }}</td>
                            <td>{{ $value->usage_limit }}</td>
                            <td>{{ $value->used_count }}</td>
                            <td>
                                @switch($value->status)
                                    @case('active')
                                        <span class="badge bg-info text-dark">Hoạt động</span>
                                    @break

                                    @case('inactive')
                                     <span class="badge bg-warning text-dark">Không hoạt động</span>
                                    @break

                                    @case('expired')
                                     <span class="badge bg-danger text-dark">Hết hạn</span>
                                    @break

                                    @default
                                @endswitch
                            </td>
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align:center">
                                <a onclick="return confirm('Bạn có chắc là muốn xóa  mã khuyến mãi này không ?')"
                                    href="{{ URL::to('/admin/coupon/delete-coupon/' . $value->coupon_id) }}"
                                    class="text-danger"><i class="bi bi-trash"></i></a>
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
            $('#couponTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm mã khuyến mãi:",
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
