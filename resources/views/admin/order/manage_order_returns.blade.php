@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách đơn hàng hoàn trả</h2>
       
      @if (session('message'))
            <p class="alert alert-success">{{ session('message') }}</p>
            @php
                session()->forget('message');
            @endphp
        @endif
        <p class="text-muted">Bạn có thể tìm kiếm theo đơn hàng hoàn trả hoặc ngày trả.</p>

        <div class="table-responsive">
            <table id="returnTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Mã trả hàng</th>
                        <th scope="col">Ngày trả</th>
                        <th scope="col">Tổng số lượng trả về</th>
                        <th scope="col">Lý do</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($returns as $val)
                        <tr>
                            <td>{{ $val->order_code }}</td>
                            <td>{{ $val->customer_name }}</td>
                            <td>{{ $val->return_code }}</td>
                            <td>{{ \Carbon\Carbon::parse($val->return_date)->format('d/m/Y') }}</td>
                            <td>{{ $val->quantity }}</td>
                            <td>{{ $val->reason }}</td>
                            <td>
                                @switch($val->status)
                                    @case('processing')
                                        <span class="badge bg-info text-dark">Đang xử lý</span>
                                    @break

                                    @case('approved')
                                        <span class="badge bg-success">Đã chấp nhận</span>
                                    @break

                                    @case('rejected')
                                        <span class="badge bg-danger">Bị từ chối</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">Đang chờ xử lý</span>
                                @endswitch
                            </td>

                            <td style="text-align: center">
                                <a href="{{ URL::to('/admin/order/manage-order-returns/view-order-returns/' . $val->return_code) }}"
                                    class="text-primary me-2"><i class="bi bi-eye text-primary"></i></a>
                                <a href="{{ URL::to('/admin/order/manage-order-returns/edit-order-returns/' . $val->return_code) }}"
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
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#returnTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm đơn hàng hoàn trả hoặc ngày trả:",
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
