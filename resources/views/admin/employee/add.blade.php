@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm nhân viên</h4>
        <form action="{{ URL::to('/admin/staffs/save-admin') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="admin_name" class="form-label">Họ và tên</label>
                <input type="text" name="admin_name" class="form-control" id="admin_name" placeholder="Nhập họ tên"
                    required>
            </div>

            <div class="mb-3">
                <label for="admin_email" class="form-label">Email</label>
                <input type="email" name="admin_email" class="form-control" id="admin_email" placeholder="Nhập email"
                    required>
            </div>

            <div class="mb-3">
                <label for="admin_phone" class="form-label">Số điện thoại</label>
                <input type="text" name="admin_phone" class="form-control" id="admin_phone"
                    placeholder="Nhập số điện thoại" required>
            </div>

            <div class="mb-3">
                <label for="admin_role" class="form-label">Vai trò</label>
                <select name="admin_role" class="form-select" id="admin_role" required>
                    <option value="superadmin">Quản trị cấp cao</option>
                    <option value="manager">Quản lý</option>
                    <option value="staff">Nhân viên</option>
                    <option value="shipper">Shipper</option>
                </select>
            </div>

            <button type="submit" name="add_admin" class="btn btn-primary w-100">💾 Lưu nhân viên</button>
        </form>
    </div>
@endsection
