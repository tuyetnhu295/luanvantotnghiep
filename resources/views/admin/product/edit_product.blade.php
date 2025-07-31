@extends('admin_layout')

@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Cập nhật sản phẩm</h4>

        @if (Session::has('message'))
            <p style="color:green;">{{ Session::get('message') }}</p>
            @php
                Session::forget('message');
            @endphp
        @endif

            <form action="{{ URL::to('/admin/update-product/' . $edit_product->product_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="product_name" class="form-label">Tên sản phẩm</label>
                    <input type="text" value="{{ $edit_product->product_name }}" name="product_name" class="form-control"
                        id="product_name" placeholder="Tên sản phẩm" required>
                </div>
                <div class="mb-3">
                    <label for="slug_product" class="form-label">Slug</label>
                    <input type="text" value="{{ $edit_product->slug_product }}" name="slug_product" class="form-control"
                        id="slug_product" placeholder="Slug" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Loại sản phẩm</label>
                    <select name="subcategory_product_id" class="form-select" required>
                        <option value="">Chọn loại sản phẩm</option>
                        @foreach ($subcate_product as $subcate)
                            <option value="{{ $subcate->subcategory_product_id }}"
                                {{ $subcate->subcategory_product_id == $edit_product->subcategory_product_id ? 'selected' : '' }}>
                                {{ $subcate->subcategory_product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thương hiệu sản phẩm</label>
                    <select name="brand_product_id" class="form-select" required>
                        <option value="">Chọn thương hiệu</option>
                        @foreach ($brand_product as $brand)
                            <option value="{{ $brand->brand_product_id }}"
                                {{ $brand->brand_product_id == $edit_product->brand_product_id ? 'selected' : '' }}>
                                {{ $brand->brand_product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="product_desc" class="form-label">Mô tả sản phẩm</label>
                    <textarea name="product_desc" class="form-control" id="product_desc" rows="3"
                        placeholder="Mô tả ngắn về sản phẩm..." required>{{ $edit_product->product_desc }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="product_content" class="form-label">Nội dung sản phẩm</label>
                    <textarea name="product_content" class="form-control" id="product_content" rows="3"
                        placeholder="Mô tả nội dung sản phẩm..." required>{{ $edit_product->product_content }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="product_material" class="form-label">Chất liệu sản phẩm</label>
                    <input type="text" name="product_material" class="form-control" id="product_material"
                        value="{{ $edit_product->product_material }}" placeholder="Chất liệu sản phẩm" required>
                </div>
                <div class="mb-3">
                    <label for="product_price" class="form-label">Giá tiền</label>
                    <input type="text" value="{{ $edit_product->product_price }}" name="product_price"
                        class="form-control" id="product_price" placeholder="Giá tiền sản phẩm" required>
                </div>

                <div class="mb-3">
                    <label for="product_image" class="form-label">Hình ảnh</label>
                    <input type="file" name="product_image" class="form-control" id="product_image">

                    <!-- Display current image if exists -->
                    @if ($edit_product->product_image)
                        <div class="mt-3">
                            <label for="current_image" class="form-label">Hình ảnh hiện tại:</label>
                            <img src="{{ asset('uploads/products/' . $edit_product->product_image) }}" width="150"
                                alt="Current Product Image">
                        </div>
                    @endif
                </div>

                <button type="submit" name="edit_product" class="btn btn-primary w-100">💾 Cập nhật sản phẩm</button>
            </form>
    </div>
@endsection
