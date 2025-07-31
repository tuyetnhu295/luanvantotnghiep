@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️Cập nhật thông tin nhân viên</h4>
        <form action="{{ URL::to('/admin/staffs/update/' . $admin->admin_id) }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="admin_name" class="form-label">Tên nhân viên</label>
                <input type="text" value="{{ $admin->admin_name }}" name="admin_name" class="form-control" id="admin_name"
                    placeholder="Tên danh mục" required>
            </div>

            <div class="mb-3">
                <label for="admin_email" class="form-label">Email</label>
                <input type="text" value="{{ $admin->admin_email }}" name="admin_email" class="form-control"
                    id="admin_email" placeholder="Email" required>
            </div>

            <div class="mb-3">
                <label for="admin_phone" class="form-label">Số điện thoại</label>
                <input type="text" value="{{ $admin->admin_phone }}" name="admin_phone" class="form-control"
                    id="admin_phone" placeholder="Số điện thoại" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary w-100">💾Cập nhật</button>
        </form>
    </div>
@endsection
