@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách sản phẩm yêu thích</h2>

        <p class="text-muted">Bạn có thể tìm kiếm theo tên sản phẩm.</p>

        <div class="table-responsive">
            <table id="favoriteTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Lượt yêu thích</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($favorite as $val)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $val->product_name }}</td>
                            <td>{{ $val->product_price }}</td>
                            <td><img src="{{ asset('uploads/products/' . $val->product_image) }}" width="120"></td>
                            <td style="text-align:
                                        center">{{ $val->total }}</td>

                            <td style="text-align:
                                        center">
                                <a href="{{ URL::to('/admin/favorite-products/product-details/' . $val->product_id) }}"
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
            $('#favoriteTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm sản phẩm:",
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
