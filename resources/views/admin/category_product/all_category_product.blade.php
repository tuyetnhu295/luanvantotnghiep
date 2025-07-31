@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh mục sản phẩm</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo tên danh mục hoặc ngày thêm.</p>

        <div class="table-responsive">
            <table id="categoryTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên danh mục</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Banner</th>
                        <th scope="col">Hiển thị</th>
                        <th scope="col">Ngày thêm</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_category_product as $key => $cate_pro)
                        <tr>
                            <td>{{ $cate_pro->category_product_id }}</td>
                            <td>{{ $cate_pro->category_product_name }}</td>
                            <td>{{ $cate_pro->slug_category_product }}</td>
                            <td>{{ $cate_pro->category_product_desc }}</td>
                            <td><img src="{{ asset('uploads/banner/category/' . $cate_pro->banner) }}" width="120">
                            </td>
                            <td>
                                <?php
                                if ($cate_pro->category_product_status == 0) {
                                ?>
                                <a
                                    href="{{ URL::to('/admin/all-category-product/unactive-category-product/' . $cate_pro->category_product_id) }}">
                                    <span class="fa-thumb-styling fa fa-thumbs-up"
                                        style="font-size: 18px;color:blue;"></span>
                                </a>
                                <?php
                                    } else {
                                ?>
                                <a
                                    href="{{ URL::to('/admin/all-category-product/active-category-product/' . $cate_pro->category_product_id) }}">
                                    <span class="fa fa-thumbs-down" style="font-size: 18px;color:red;"></span>
                                </a>
                                <?php
                                }
                                ?>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($cate_pro->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align:
                                        center">
                                <a href="{{ URL::to('/admin/all-category-product/edit-category-product/' . $cate_pro->category_product_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                <a onclick="return confirm('Bạn có chắc là muốn xóa danh mục sản phẩm này không ?')"
                                    href="{{ URL::to('/admin/all-category-product/delete-category-product/' . $cate_pro->category_product_id) }}"
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
            $('#categoryTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm danh mục:",
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
