@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️Cập nhật kích thước sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
            <form action="{{ URL::to('/admin/product/size/update-size/' . $edit_size->size_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="size_name" class="form-label">Tên kích thước</label>
                    <input type="text" value="{{ $edit_size->size_name }}" name="size_name"
                        class="form-control" id="size_name" placeholder="Tên kích thước" required>
                </div>

                <button type="submit" name="update" class="btn btn-primary w-100">💾 Cập nhật kích thước</button>
            </form>
    </div>
@endsection
