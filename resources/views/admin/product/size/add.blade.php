@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm kích cỡ sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/product/size/save-size') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="size_name" class="form-label">Tên kích cỡ</label>
                <input type="text" name="size_name" class="form-control" id="size_name"
                    placeholder="Tên kích cỡ" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="size_status" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" name="add" class="btn btn-primary w-100">💾 Lưu</button>
        </form>
    </div>
@endsection
