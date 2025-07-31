
@extends('admin_layout')
@section('admin_content')

<div class="container mt-2">
    <h3 class="mb-3">Thông tin cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" name="name" value="" class="form-control" id="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" value="" class="form-control" id="email" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" value="" class="form-control" id="phone">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
    </form>
</div>

@endsection
