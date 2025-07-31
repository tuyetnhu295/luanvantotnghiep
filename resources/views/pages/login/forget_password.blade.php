@extends('layout')
@section('content')
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
            text-align: center;
            margin-top: 20px;
        }

        .register a {
            text-decoration: none;
            color: #000000;
            font-weight: bold;
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
    <div class="breadcrumb" style="margin-left: 200px; margin-top: 20px;">
        <a href="{{ URL::to('/home') }}">Trang chủ</a> &nbsp;/&nbsp; <a href="{{ URL::to('/home/account/login') }}">Tài
            khoản</a> &nbsp;/&nbsp; <span>Quên mật khẩu</span>
    </div>
    @if (session('status'))
        <div class="alert alert-success" style="margin-left: 200px; margin-top: 20px;">
            {{ session('status') }}
        </div>
    @endif
    <div class="login-container">
        <div class="login-box">
            <h2>QUÊN MẬT KHẨU</h2>

            <form action="{{ URL::to('/home/account/password-email') }}" method="POST">
                @csrf
                <input type="text" name="email" placeholder="Nhập email" required>

                <button type="submit" class="login-btn">GỬI LINK ĐẶT LẠI MẬT KHẨU</button>

                <hr>


                <hr>

                <div class="register">
                    <a href="{{ URL::to('/home/account/login') }}">← Trở về đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
@endsection
