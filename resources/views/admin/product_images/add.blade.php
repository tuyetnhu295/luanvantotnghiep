@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm hình ảnh sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/save-images') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <select id="product_id" name="product_id" class="form-select">
                    <option value="">Chọn sản phẩm</option>
                    @foreach (collect($variant)->unique('product_id') as $product)
                        <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown màu (ban đầu để trống, sẽ được cập nhật bằng JS) -->
            <div class="mb-3">
                <label class="form-label">Màu sản phẩm</label>
                <select id="color_id" name="color_id" class="form-select">
                    <option value="">Chọn màu</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Loại hình:</label>
                <select id="image_type" name="image_type" class="form-select">
                    <option value="">Chọn loại</option>
                    <option value="thumbnail">thumbnail</option>
                    <option value="gallery">gallery</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Hình ảnh</label>
                <input type="file" name="product_image" class="form-control" id="product_image" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="product_image_status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add" class="btn btn-primary w-100">💾 Lưu </button>
        </form>
    </div>
    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            const productId = this.value;
            const colorSelect = document.getElementById('color_id');
            colorSelect.innerHTML = '<option value="">Đang tải...</option>';

            fetch(`/admin/get-product-colors/${productId}`)
                .then(res => res.json())
                .then(colors => {
                    let options = '<option value="">Chọn màu</option>';
                    colors.forEach(color => {
                        options += `<option value="${color.color_id}">${color.color_name}</option>`;
                    });
                    colorSelect.innerHTML = options;
                })
                .catch(err => {
                    colorSelect.innerHTML = '<option value="">Không có màu</option>';
                    console.error('Lỗi khi lấy màu:', err);
                });
        });
    </script>
@endsection
