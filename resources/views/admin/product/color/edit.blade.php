@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️Cập nhật màu sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
            <form action="{{ URL::to('/admin/product/color/update-color/' . $edit_color->color_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="color_name" class="form-label">Tên màu</label>
                    <input type="text" value="{{ $edit_color->color_name }}" name="color_name"
                        class="form-control" id="color_name" placeholder="Tên màu" required>
                </div>

                <button type="submit" name="update" class="btn btn-primary w-100">💾 Cập nhật màu</button>
            </form>
    </div>
@endsection
