@extends('pages.customer.information')
@section('profile_content')
    <h4 class="mb-4"><strong>THAY ĐỔI MẬT KHẨU</strong></h4>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('message'))
        <div class="alert alert-danger">
            {{ session('message') }}
        </div>
    @endif

    <form action="{{ URL::to('/home/account/info/save-change-password') }}" method="POST">
        @csrf

        <div class="col-md-6">
            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>

        <div class="col-md-6 mt-3">
            <label for="new_password" class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>

        <div class="col-md-6 mt-3">
            <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation"
                required>
        </div>

        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
        </div>
    </form>
@endsection
