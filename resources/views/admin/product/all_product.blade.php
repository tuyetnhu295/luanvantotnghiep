@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Sản phẩm</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo tên sản phẩm hoặc ngày thêm.</p>

        <div class="table-responsive">
            <table id="productTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Hình ảnh sản phẩm</th>
                        <th scope="col">Chất liệu</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Nội dung</th>
                        <th scope="col">Loại sản phẩm</th>
                        <th scope="col">Thương hiệu</th>
                        <th scope="col">Hiển thị</th>
                        <th scope="col">Ngày thêm</th>
                        <th scope="col">Tổng số lượng bán</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_product as $key => $product)
                        <tr>
                            <td>{{ $product->product_id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->slug_product }}</td>
                            <td>{{ number_format($product->product_price, 0, ',', '.') }}₫</td>
                            <td><img src="{{ asset('uploads/products/' . $product->product_image) }}" width="80"></td>
                            <td>{{ $product->product_material }}</td>
                            <td>{{ $product->product_desc }}</td>
                            <td>{{ $product->product_content }}</td>
                            <td>
                                @foreach ($subcate_product as $subcate)
                                    @if ($subcate->subcategory_product_id == $product->subcategory_product_id)
                                        {{ $subcate->subcategory_product_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($brand_product as $brand)
                                    @if ($brand->brand_product_id == $product->brand_product_id)
                                        {{ $brand->brand_product_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if ($product->product_status == 0)
                                    <a href="{{ URL::to('/admin/all-product/unactive-product/' . $product->product_id) }}">
                                        <span class="fa-thumb-styling fa fa-thumbs-up"
                                            style="font-size: 18px;color:blue;"></span>
                                    </a>
                                @else
                                    <a href="{{ URL::to('/admin/all-product/active-product/' . $product->product_id) }}">
                                        <span class="fa fa-thumbs-down" style="font-size: 18px;color:red;"></span>
                                    </a>
                                @endif
                            </td>
                            <td>{{$product->total_sold}}</td>
                            <td>{{ \Carbon\Carbon::parse($product->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align: center">
                                <a href="{{ URL::to('/admin/all-product/edit-product/' . $product->product_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                <a onclick="return confirm('Bạn có chắc là muốn xóa sản phẩm này không ?')"
                                    href="{{ URL::to('/admin/all-product/delete-product/' . $product->product_id) }}"
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
            $('#productTable').DataTable({
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
