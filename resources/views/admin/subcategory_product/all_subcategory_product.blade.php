@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Loại sản phẩm</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo loại sản phẩm hoặc ngày thêm.</p>

        <div class="table-responsive">
            <table id="subcategoryTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Loại sản phẩm</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Danh mục sản phẩm</th>
                        <th scope="col">Banner</th>
                        <th scope="col">Hiển thị</th>
                        <th scope="col">Ngày thêm</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_subcategory_product as $key => $subcate_pro)
                        <tr>
                            <td>{{ $subcate_pro->subcategory_product_id }}</td>
                            <td>{{ $subcate_pro->subcategory_product_name }}</td>
                            <td>{{ $subcate_pro->slug_subcategory_product }}</td>
                            <td>{{ $subcate_pro->subcategory_product_desc }}</td>
                            <td>
                                @foreach ($parentcate_pro as $subcate)
                                    @if ($subcate->category_product_id == $subcate_pro->parent_category_product_id)
                                        {{ $subcate->category_product_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td><img src="{{ asset('uploads/banner/subcategory/' . $subcate_pro->banner) }}" width="120"></td>
                            <td>
                                <?php
                                if ($subcate_pro->subcategory_product_status == 0) {
                                ?>
                                <a
                                    href="{{ URL::to('/admin/all-subcategory-product/unactive-subcategory-product/' . $subcate_pro->subcategory_product_id) }}">
                                    <span class="fa-thumb-styling fa fa-thumbs-up"
                                        style="font-size: 18px;color:blue;"></span>
                                </a>
                                <?php
                                    } else {
                                ?>
                                <a
                                    href="{{ URL::to('/admin/all-subcategory-product/active-subcategory-product/' . $subcate_pro->subcategory_product_id) }}">
                                    <span class="fa fa-thumbs-down" style="font-size: 18px;color:red;"></span>
                                </a>
                                <?php
                                }
                                ?>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($subcate_pro->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align:
                                        center">
                                <a href="{{ URL::to('/admin/all-subcategory-product/edit-subcategory-product/' . $subcate_pro->subcategory_product_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                <a onclick="return confirm('Bạn có chắc là muốn xóa danh mục sản phẩm này không ?')"
                                    href="{{ URL::to('/admin/all-subcategory-product/delete-subcategory-product/' . $subcate_pro->subcategory_product_id) }}"
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
