@extends('admin_layout')

@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Cập nhật hình ảnh sản phẩm</h4>

        @if (Session::has('message'))
            <p style="color:green;">{{ Session::get('message') }}</p>
            @php
                Session::forget('message');
            @endphp
        @endif

        @foreach ($images as $edit_images)
            <form action="{{ URL::to('/admin/update/' . $edit_images->product_image_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <select id="product_id" name="product_id" class="form-select" required>
                        <option value="">Chọn sản phẩm</option>
                        @foreach (collect($variant)->unique('product_id') as $value)
                            <option value="{{ $value->product_id }}"
                                {{ $value->product_id == $edit_images->product_id ? 'selected' : '' }}>
                                {{ $value->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Màu sản phẩm</label>
                    <select id="color_id" name="color_id" class="form-select" required>
                        <option value="">Chọn sản phẩm</option>
                        @foreach (collect($variant)->unique('color_id') as $value)
                            <option value="{{ $value->color_id }}"
                                {{ $value->color_id == $edit_images->color_id ? 'selected' : '' }}>
                                {{ $value->color_name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Loại hình ảnh</label>
                    <select name="image_type" class="form-select" required>
                        <option value="">Chọn loại hình ảnh</option>
                        <option value="thumbnail" {{ $edit_images->image_type == 'thumbnail' ? 'selected' : '' }}>Ảnh thu
                            nhỏ</option>
                        <option value="gallery" {{ $edit_images->image_type == 'gallery' ? 'selected' : '' }}>Thư viện
                        </option>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="product_image" class="form-label">Hình ảnh</label>
                    <input type="file" name="product_image" class="form-control" id="product_image">

                    <!-- Display current image if exists -->
                    @if ($edit_images->product_image)
                        <div class="mt-3">
                            <label for="current_image" class="form-label">Hình ảnh hiện tại:</label>
                            <img src="{{ asset('uploads/products/' . $edit_images->product_image) }}" width="150"
                                alt="Current Product Image">
                        </div>
                    @endif
                </div>

                <button type="submit" name="edit_product" class="btn btn-primary w-100">💾 Cập nhật</button>
            </form>
        @endforeach
    </div>
@endsection
