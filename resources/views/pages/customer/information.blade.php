@extends('layout')
@section('content')
    <style>
        .sidebar {
            background-color: #f0f0f0;
            padding: 20px;
            height: 700px;
            border: 1px solid rgb(216, 216, 216);
        }

        .sidebar h5 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            font-size: 16px;
            margin-bottom: 10px;
            color: #000;
        }

        .sidebar .nav-link.active {
            background-color: #000;
            color: white !important;
        }

        .voucher-badge {
            background: red;
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 5px;
            margin-left: 5px;
        }

        .profile-box {
            background: rgb(249, 244, 244);
            border-radius: 6px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            height: 700px;
            overflow-y: auto;
        }
    </style>

    <div class="container-fluid mt-4 mb-5 ">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h5>Tài khoản của bạn</h5>
                <div class="nav flex-column">
                    <a href="{{ URL::to('/home/account/info/profile') }}"
                        class="nav-link {{ request()->is('home/account/info/profile') ? 'active' : '' }}" active>
                        🧑 Hồ sơ của tôi
                    </a>

                    <a href="{{ URL::to('/home/account/info/my-order') }}"
                        class="nav-link {{ request()->is('home/account/info/my-order') ? 'active' : '' }}">
                        📦 Đơn hàng của tôi
                    </a>

                    <a href="{{ URL::to('/home/account/info/help') }}"
                        class="nav-link {{ request()->is('home/account/info/help') ? 'active' : '' }}">❓ Yêu cầu hỗ
                        trợ</a>

                    <a href="{{ URL::to('/home/account/info/favorite-product') }}" class="nav-link">❤️ Sản phẩm yêu thích</a>

                    <a href="{{ URL::to('/home/account/info/change-password') }}"
                        class="nav-link {{ request()->is('home/account/info/change-password') ? 'active' : '' }}">
                        🔒 Đổi mật khẩu
                    </a>

                </div>
            </div>

            <!-- Profile Form -->
            <div class="col-md-9">
                <div class="profile-box">
                    <div>
                        @yield('profile_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
