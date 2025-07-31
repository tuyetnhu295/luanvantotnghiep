@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Hình ảnh sản phẩm</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo hình ảnh sản phẩm hoặc ngày thêm.</p>

        <div class="table-responsive">
            <table id="productimagesTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Màu sản phẩm</th>
                        <th scope="col">Loại </th>
                        <th scope="col">Hình ảnh sản phẩm</th>
                        <th scope="col">Hiển thị</th>
                        <th scope="col">Ngày thêm</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($images as $key => $images)
                        <tr>
                            <td>{{ $images->product_image_id }}</td>
                            <td>
                                {{ $images->product_name }}
                            </td>
                            <td>
                                {{ $images->color_name }}
                            </td>
                            <td>{{ $images->image_type }}</td>
                            <td><img src="{{ asset('uploads/products/' . $images->product_image) }}" width="80"></td>
                            <td>
                                @if ($images->product_image_status == 0)
                                    <a
                                        href="{{ URL::to('/admin/all-product-images/unactive/' . $images->product_image_id) }}">
                                        <span class="fa-thumb-styling fa fa-thumbs-up"
                                            style="font-size: 18px;color:blue;"></span>
                                    @else
                                        <a
                                            href="{{ URL::to('/admin/all-product-images/active/' . $images->product_image_id) }}">
                                            <span class="fa fa-thumbs-down" style="font-size: 18px;color:red;"></span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($images->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align: center">
                                <a href="{{ URL::to('/admin/all-product-images/edit/' . $images->product_image_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                <a onclick="return confirm('Bạn có chắc là muốn xóa sản phẩm này không ?')"
                                    href="{{ URL::to('/admin/all-product-images/delete/' . $images->product_image_id) }}"
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
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#productimagesTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm hình ảnh sản phẩm:",
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
