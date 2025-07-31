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
            color: #dc3545;
        }

        .cod-container p {
            margin-bottom: 25px;
            text-align: center;
        }

        .cod-container i {
            font-size: 48px;
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>

    <div class="breadcrumb">
        <a href="{{ URL::to('/home') }}">Trang chủ</a> &nbsp;/&nbsp; <strong>Thanh toán thất bại</strong>
    </div>

    <div class="cod-container">
        <h3>Thanh toán thất bại</h3>
        <!-- Icon thất bại -->
        <i class="fa fa-times-circle fa-4x"></i>
        <p>{{ $message ?? 'Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại sau.' }}</p>
        <a href="">Thử lại</a>
    </div>
@endsection
