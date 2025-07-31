@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️Cập nhật thương hiệu sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/update-brand-product/' . $edit_brand_product->brand_product_id) }}" method="post"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="brand_product_name" class="form-label">Tên thương hiệu</label>
                <input type="text" value="{{ $edit_brand_product->brand_product_name }}" name="brand_product_name"
                    class="form-control" id="brand_name" placeholder="Tên thương hiệu" required>
            </div>

            <div class="mb-3">
                <label for="slug_brand_product" class="form-label">Slug</label>
                <input type="text" value="{{ $edit_brand_product->slug_brand_product }}" name="slug_brand_product"
                    class="form-control" id="brand_name" placeholder="Slug" required>
            </div>

            <div class="mb-3">
                <label for="brand_product_desc" class="form-label">Mô tả thương hiệu</label>
                <textarea name="brand_product_desc" class="form-control" id="brand_desc" rows="3" required>{{ $edit_brand_product->brand_product_desc }}</textarea>
            </div>

            <div class="mb-3">
                <label for="banner" class="form-label">Banner</label>
                <input type="file" name="banner" class="form-control" id="banner">

                @if ($edit_brand_product->banner)
                    <div class="mt-3">
                        <label for="current_image" class="form-label">Banner hiện tại:</label>
                        <img src="{{ asset('uploads/banner/brand/' . $edit_brand_product->banner) }}" width="150"
                            alt="Current Banner">
                    </div>
                @endif
            </div>

            <button type="submit" name="update_brand_product" class="btn btn-primary w-100">💾 Cập nhật thương
                hiệu</button>
        </form>
    </div>
@endsection
