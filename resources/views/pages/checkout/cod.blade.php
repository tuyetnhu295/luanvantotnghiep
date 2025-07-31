@extends('layout')
@section('content')
    <style>
        .breadcrumb {
            margin: 30px 0;
            font-size: 14px;
            text-align: center;
        }

        .breadcrumb a {
            text-decoration: none;
            color: steelblue;
        }

        .cod-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .cod-container h3 {
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .cod-container p {
            margin-bottom: 25px;
            text-align: center;
        }

        .cod-container i {
            font-size: 48px;
            color: green;
        }

    </style>
    <div class="breadcrumb">
        <a href="{{ URL::to('/home') }}">Trang chủ</a> &nbsp;/&nbsp; <strong>Xác nhận đơn hàng</strong>
    </div>
    <div class="cod-container">
        <h3>Xác nhận đơn hàng thành công!</h3>
        <!-- Biểu tượng thành công -->
        <i class="fa fa-check-circle fa-4x"></i>
        <p>Chúng tôi đã nhận được đơn hàng của bạn. Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>
        <!-- Nút quay lại trang chủ -->
        <form action="{{ URL::to('/home/') }}" method="get">
            <button type="submit" class="btn btn-outline-success" style="margin:auto;"
                aria-label="Quay lại trang chủ">Ok</button>
        </form>
    </div>
@endsection
