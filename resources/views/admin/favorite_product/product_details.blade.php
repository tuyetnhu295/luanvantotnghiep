@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Chi tiết sản phẩm yêu thích</h2>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $product->product_id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->slug_product }}</td>
                        <td>{{ number_format($product->product_price, 0, ',', '.') }}₫</td>
                        <td><img src="{{ asset('uploads/products/' . $product->product_image) }}" width="80"></td>
                        <td>{{ $product->product_material }}</td>
                        <td>{{ $product->product_desc }}</td>
                        <td>{{ $product->product_content }}</td>
                        <td>{{ $product->subcategory_product_name }}</td>
                        <td>{{ $product->brand_product_name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        button a
        {
            text-decoration: none;
            color:black;
        }
    </style>
    <div class="text-center">
        <button class="btn btn-outline-dark" style="font-size: 20px;"><a href="{{ URL::to('/admin/favorite-products') }}">← Quay về</a></button>
    </div>
@endsection
