@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm biến thể sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/product/product-variants/save-variant') }}" method="post"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <select name="product" class="form-select">
                    @foreach ($product as $key => $value)
                        <option value="{{ $value->product_id }}">{{ $value->product_name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kích thước</label>
                <select name="size" class="form-select">
                    @foreach ($size as $key => $value)
                        <option value="{{ $value->size_id }}">{{ $value->size_name }}
                        </option>
                    @endforeach

                </select>
            </div>

             <div class="mb-3">
                <label class="form-label">Màu</label>
                <select name="color" class="form-select">
                    @foreach ($color as $key=>$value )
                        <option value="{{ $value->color_id }}">{{ $value->color_name }}</option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Số lượng tồn</label>
                <input type="number" name="stock" class="form-control" id="stock" placeholder="Số lượng tồn" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add" class="btn btn-primary w-100">💾 Lưu</button>
        </form>
    </div>
@endsection
