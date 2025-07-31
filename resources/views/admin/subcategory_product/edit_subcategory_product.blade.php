@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️ Cập nhật danh mục con sản phẩm</h4>

        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message');
        }
        ?>

        @foreach ($edit_subcategory_product as $key => $edit_value)
            <form action="{{ URL::to('/admin/update-subcategory-product/' . $edit_value->subcategory_product_id) }}"
                method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="subcategory_product_name" class="form-label">Tên danh mục</label>
                    <input type="text" value="{{ $edit_value->subcategory_product_name }}" name="subcategory_product_name"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="slug_subcategory_product" class="form-label">Slug</label>
                    <input type="text" value="{{ $edit_value->slug_subcategory_product }}"
                        name="slug_subcategory_product" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="subcategory_product_desc" class="form-label">Mô tả danh mục</label>
                    <textarea name="subcategory_product_desc" class="form-control" rows="3" required>{{ $edit_value->subcategory_product_desc }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh mục sản phẩm</label>
                    <select name="parent_category_product_id" class="form-select" required>
                        @foreach ($parentcategory as $parent)
                            <option value="{{ $parent->category_product_id }}"
                                {{ $parent->category_product_id == $edit_value->parent_category_product_id ? 'selected' : '' }}>
                                {{ $parent->category_product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="banner" class="form-label">Banner</label>
                    <input type="file" name="banner" class="form-control" id="banner">

                    @if ($edit_value->banner)
                        <div class="mt-3">
                            <label for="current_image" class="form-label">Banner hiện tại:</label>
                            <img src="{{ asset('uploads/banner/subcategory' . $edit_value->banner) }}"
                                width="150" alt="Current Banner">
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary w-100">💾 Cập nhật danh mục</button>
            </form>
        @endforeach
    </div>
@endsection
