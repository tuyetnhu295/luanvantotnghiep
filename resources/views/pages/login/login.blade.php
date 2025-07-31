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
    .login-box form input[type="password"] {
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

    .google-btn {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #db4437;
        color: white;
        text-align: center;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        margin-top: 10px;
    }

    .google-btn:hover {
        background-color: #c1351d;
    }
</style>
@extends('layout')
@section('content')
    @if (session('error'))
        <div style="color: red; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <p class="alert alert-success">{{ session('success') }}</p>
        @php
            session()->forget('success');
        @endphp
    @endif
    <div class="breadcrumb" style="margin-left: 200px; margin-top: 20px;">
        <a href="#">Trang chủ</a> &nbsp;/&nbsp; <a href="#">Tài khoản</a> &nbsp;/&nbsp; <span>Đăng nhập</span>
    </div>
    <div class="login-container">
        <div class="login-box">
            <h2>ĐĂNG NHẬP</h2>
            <p class="text-center mb-4"><strong>HOẶC ĐĂNG NHẬP VỚI ...</strong></p>

            <form action="{{ URL::to('/home/account/login-customer') }}" method="POST">
                @csrf
                <input type="text" name="email" placeholder="Nhập email hoặc số điện thoại" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>

                <button type="submit" class="login-btn">ĐĂNG NHẬP</button>

                <div class="forgot-password text-center">
                    <a href="{{ URL::to('/home/account/forget-password') }}">Quên mật khẩu?</a>
                </div>

                <hr>


                <hr>
                <div class="register text-center">
                    <a href="{{ URL::to('/home/account/register') }}">Đăng ký</a>
                </div>
            </form>
        </div>
    </div>
@endsection
