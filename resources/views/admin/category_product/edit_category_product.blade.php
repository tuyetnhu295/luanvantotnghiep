@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️Cập nhật danh mục sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/update-category-product/' . $edit_category_product->category_product_id) }}"
            method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="category_product_name" class="form-label">Tên danh mục</label>
                <input type="text" value="{{ $edit_category_product->category_product_name }}"
                    name="category_product_name" class="form-control" id="category_product_name" placeholder="Tên danh mục"
                    required>
            </div>
            <div class="mb-3">
                <label for="slug_category_product" class="form-label">Slug</label>
                <input type="text" value="{{ $edit_category_product->slug_category_product }}"
                    name="slug_category_product" class="form-control" id="slug_category_product" placeholder="Slug"
                    required>
            </div>

            <div class="mb-3">
                <label for="category_product_desc" class="form-label">Mô tả danh mục</label>
                <textarea name="category_product_desc" class="form-control" id="category_desc" rows="3" required>{{ $edit_category_product->category_product_desc }}</textarea>
            </div>

            <div class="mb-3">
                <label for="banner" class="form-label">Banner</label>
                <input type="file" name="banner" class="form-control" id="banner">

                @if ($edit_category_product->banner)
                    <div class="mt-3">
                        <label for="current_image" class="form-label">Banner hiện tại:</label>
                        <img src="{{ asset('uploads/banner/category/' . $edit_category_product->banner) }}" width="150"
                            alt="Current Banner">
                    </div>
                @endif
            </div>
            <button type="submit" name="update_category_product" class="btn btn-primary w-100">💾 Cập nhật danh
                mục</button>
        </form>
    </div>
@endsection
