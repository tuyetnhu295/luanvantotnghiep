@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm thương hiệu sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/save-brand-product') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="brand_product_name" class="form-label">Tên thương hiệu</label>
                <input type="text" name="brand_product_name" class="form-control" id="brand_name"
                    placeholder="Tên danh mục" required>
            </div>

            <div class="mb-3">
                <label for="slug_brand_product" class="form-label">Slug</label>
                <input type="text" name="slug_brand_product" class="form-control" id="slug_brand_product"
                    placeholder="Slug" required>
            </div>

            <div class="mb-3">
                <label for="brand_product_desc" class="form-label">Mô tả thương hiệu</label>
                <textarea name="brand_product_desc" class="form-control" id="brand_desc" rows="3"
                    placeholder="Mô tả ngắn về danh mục..." required></textarea>
            </div>

            <div class="mb-3">
                <label for="banner" class="form-label">Banner</label>
                <input type="file" name="banner" class="form-control" id="banner" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="brand_product_status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add_brand_product" class="btn btn-primary w-100">💾 Lưu thương hiệu</button>
        </form>
    </div>
@endsection
