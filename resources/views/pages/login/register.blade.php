<style>

    .breadcrumb {
        margin-bottom: 20px;
        font-size: 14px;
        margin-left: 20px;
        margin-top: 20px;
    }

    .breadcrumb a {
        text-decoration: none;
        color: #000;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }

    .login-box {
        background-color: #fff;
        padding: 30px;
        border-radius: 6px;
        width: 600px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.777);
    }

    .login-box h2 {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .login-box form input[type="text"],
    .login-box form input[type="password"],
    .login-box form input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 12px;
        border: 1px solid #c5c4c4;
        border-radius: 4px;
    }

    .login-btn {
        width: 100%;
        padding: 12px;
        background-color: #000;
        color: #fff;
        border: none;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
    }

    .login-btn:hover {
        background-color: #333;
    }

    .forgot-password {
        margin-top: 12px;
        font-size: 13px;
    }

    .register {
        margin-top: 10px;
        font-size: 20px;
    }

    .register a {
        text-decoration: none;
        font-weight: bold;
        color: #000;
    }
</style>
@extends('layout')
@section('content')
    @if ($errors->any())
        <div style="color:red; margin-bottom:10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="breadcrumb" style="margin-left: 200px; margin-top: 20px;">
        <a href="#">Trang chủ</a> &nbsp;/&nbsp; <a href="#">Tài khoản</a> &nbsp;/&nbsp; <span>Đăng ký</span>
    </div>
    <div class="login-container">
        <div class="login-box">
            <h2>TẠO TÀI KHOẢN</h2>
            <form action="{{ URL::to('/home/account/add-customer') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Họ và tên" required>
                <input type="text" name="phone" placeholder="Nhập số điện thoại" required>
                <input type="email" name="email" placeholder="Nhập địa chỉ email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <input type="password" name="password_confirmation" placeholder="Xác nhận lại mật khẩu" required>
                <button type="submit" class="login-btn">ĐĂNG KÝ</button>
                <hr>
                <div class="register text-center">
                    <a href="{{ URL::to('/home/account/login') }}">← Quay về</a>
                </div>
            </form>
        </div>
    </div>
@endsection
