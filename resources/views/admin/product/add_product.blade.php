@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }

        ?>
        @if (session('error'))
            <p class="alert alert-danger">{{ session('error') }}</p>
            @php
                session()->forget('error');
            @endphp
        @endif
        <form action="{{ URL::to('/admin/save-product') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="product_name" class="form-label">Tên sản phẩm</label>
                <input type="text" name="product_name" class="form-control" id="product_name" placeholder="Tên sản phẩm"
                    required>
            </div>

            <div class="form-group">
                <label for="subcategory_product_id">Loại sản phẩm</label>
                <select id="subcategory_product_id" name="subcategory_product_id" class="form-control">
                    <option value="">-- Chọn danh mục con --</option>
                    @foreach ($subcategory as $key => $value)
                        <option value="{{ $value->subcategory_product_id }}">{{ $value->subcategory_product_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Thương hiệu sản phẩm</label>
                <select name="brand_product_id" class="form-select" required>
                    <option value="">Chọn thương hiệu</option>
                    @foreach ($brand_product as $key => $brand)
                        <option value="{{ $brand->brand_product_id }}">{{ $brand->brand_product_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="slug_product" class="form-label">Slug</label>
                <input type="text" name="slug_product" class="form-control" id="slug_product" placeholder="Slug"
                    required>
            </div>

            <div class="mb-3">
                <label for="product_desc" class="form-label">Mô tả sản phẩm</label>
                <textarea name="product_desc" class="form-control" id="product_desc" rows="3"
                    placeholder="Mô tả ngắn về sản phẩm..." required></textarea>
            </div>

            <div class="mb-3">
                <label for="product_content" class="form-label">Nội dung sản phẩm</label>
                <textarea name="product_content" class="form-control" id="product_content" rows="3"
                    placeholder="Mô tả nội dung sản phẩm..." required></textarea>
            </div>
            <div class="mb-3">
                <label for="product_material" class="form-label">Chất liệu sản phẩm</label>
                <input type="text" name="product_material" class="form-control" id="product_material"
                    placeholder="Chất liệu sản phẩm" required>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Giá tiền</label>
                <input type="text" name="product_price" class="form-control" id="product_price"
                    placeholder="Giá tiền sản phẩm" required>
            </div>

            <div class="mb-3">
                <label for="product_image" class="form-label">Hình ảnh</label>
                <input type="file" name="product_image" class="form-control" id="product_image" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="product_status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add_product" class="btn btn-primary w-100">💾 Lưu sản phẩm</button>
        </form>
    </div>
@endsection
