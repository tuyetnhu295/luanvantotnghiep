@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm màu sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/product/color/save-color') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="color_name" class="form-label">Tên màu</label>
                <input type="text" name="color_name" class="form-control" id="color_name"
                    placeholder="Tên màu" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="color_status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add" class="btn btn-primary w-100">💾 Lưu</button>
        </form>
    </div>
@endsection
