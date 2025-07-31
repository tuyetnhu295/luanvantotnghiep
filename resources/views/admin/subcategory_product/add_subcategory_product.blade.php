@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm danh mục con sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/save-subcategory-product') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="subcategory_product_name" class="form-label">Tên danh mục con</label>
                <input type="text" name="subcategory_product_name" class="form-control" id="subcategory_name"
                    placeholder="Tên danh mục" required>
            </div>
            <div class="mb-3">
                <label for="slug_subcategory_product" class="form-label">Slug</label>
                <input type="text" name="slug_subcategory_product" class="form-control" id="slug_subcategory_product"
                    placeholder="Slug" required>
            </div>
            <div class="mb-3">
                <label for="subcategory_product_desc" class="form-label">Mô tả danh mục con</label>
                <textarea name="subcategory_product_desc" class="form-control" id="subcategory_desc" rows="3"
                    placeholder="Mô tả ngắn về danh mục..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Danh mục sản phẩm</label>
                <select name="parent_category_product_id" class="form-select">
                    @foreach ($parentcategory as $key => $parent_cate)
                        <option value="{{ $parent_cate->category_product_id }}">{{ $parent_cate->category_product_name }}
                        </option>
                    @endforeach

                </select>
            </div>
            <div class="mb-3">
                <label for="banner" class="form-label">Banner</label>
                <input type="file" name="banner" class="form-control" id="banner" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="subcategory_product_status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add_subcategory_product" class="btn btn-primary w-100">💾 Lưu danh mục</button>
        </form>
    </div>
@endsection
