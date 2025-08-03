<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Tuyet Nhu">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('fontend/images/logo.png') }}">

    <title>NQ fashion</title>


    <!-- Bootstrap 5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('fontend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('fontend/css/sweetalert.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
</head>

<body>
    <div id="loading" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div class="top-bar">
        <div class="marquee">
            @foreach ($coupon as $value)
                @if ($value->discount_type == 'fixed')
                    <span>
                        VOUCHER {{ number_format($value->discount_value, 0, ',', '.') }}₫ — ĐƠN TỪ
                        {{ number_format($value->min_order_value, 0, ',', '.') }}₫
                    </span>
                @elseif ($value->discount_type == 'percentage')
                    <span>
                        VOUCHER {{ $value->discount_value }}% — ĐƠN TỪ
                        {{ number_format($value->min_order_value, 0, ',', '.') }}₫
                    </span>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Header - Container chính -->
    <nav class="header-main">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Logo -->
            <a href="{{ URL::to('/home') }}"><img src="{{ asset('fontend/images/logo.png') }}" alt="Logo" height="60" width="100"
                    style="margin-right:20px;"></a>

            <!-- Thanh tìm kiếm -->
            <form class="search-box d-flex" action="{{ URL::to('/home/search') }}" method="post">
                @csrf
                <div class="input-group" style="width:100%;">
                    <input type="text" class="form-control rounded-start-5 border-end-0"
                        placeholder="Bạn đang tìm gì?" name="keyword">
                    <button type="submit" class="btn btn-outline-light border-start-0" name="search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

            </form>

            @if (Session::has('success'))
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
                    <div class="toast align-items-center text-white bg-success border-0 show shadow" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ Session::get('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Đóng"></button>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toastEl = document.querySelector('.toast');
                        if (toastEl) {
                            new bootstrap.Toast(toastEl, {
                                delay: 10000
                            }).show();
                        }
                    });
                </script>
            @endif

            <!-- Biểu tượng cửa hàng, đăng nhập, giỏ hàng -->
            <div class="header-icons d-flex align-items-center">
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#"
                        id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> Ngôn ngữ
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" style="color: black" href="?lang=vi">🇻🇳 Tiếng Việt</a>
                        </li>
                        <li>
                            <a class="dropdown-item" style="color: black" href="?lang=en">🇺🇸 English</a>
                        </li>
                    </ul>
                </div>

                @if (session('customer_id'))
                    <div class="dropdown">
                        <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#"
                            id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            Hi, {{ session('customer_phone') }}
                        </a>
                        <ul class="dropdown-menu account-dropdown" aria-labelledby="userDropdown">
                            <li class="dropdown-header text-center">
                                <strong>THÔNG TIN TÀI KHOẢN</strong>
                                <div class="small mt-1">Số điện thoại:
                                    <strong>{{ session('customer_phone') }}</strong>
                                </div>
                            </li>
                            <li class="dropdown-actions d-flex justify-content-around mt-3 px-3">
                                <a href="{{ URL::to('/home/account/info/profile') }}" class="btn btn-dark w-45">Xem
                                    chi
                                    tiết</a>
                                <a href="{{ URL::to('/home/logout') }}" class="btn btn-dark w-45">Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ URL::to('/home/account/login') }}"><i class="bi bi-person"></i>
                        Đăng nhập
                    </a>
                @endif
                <a href="{{ URL::to('/home/pages/cart/cart') }}"><i class="bi bi-cart"></i> Giỏ hàng</a>
            </div>
        </div>
    </nav>

    <!-- Menu điều hướng -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ URL::to('/home/pages/new-products') }}">HÀNG
                            MỚI</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ URL::to('/home/pages/all-product') }}"
                            role="button" data-bs-toggle="dropdown">SẢN
                            PHẨM</a>
                        <ul class="dropdown-menu" data-bs-auto-close="outside">
                            <li><a class="dropdown-item" href="{{ URL::to('/home/pages/all-product') }}">TẤT CẢ SẢN
                                    PHẨM</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ URL::to('/home/pages/best-selling') }}">HÀNG BÁN
                                    CHẠY</a>
                            </li>
                            @foreach ($category as $key => $cate_pro)
                                <li><a class="dropdown-item"
                                        href="{{ URL::to('/home/pages/category/category-product/' . $cate_pro->slug_category_product) }}">{{ $cate_pro->category_product_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @foreach ($category as $key => $cate_pro)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle"
                                href="{{ URL::to('/home/pages/category/category-product/' . $cate_pro->slug_category_product) }}"
                                role="button" data-bs-toggle="dropdown">
                                {{ $cate_pro->category_product_name }}
                            </a>
                            <ul class="dropdown-menu" data-bs-auto-close="outside">
                                @foreach ($subcategory as $subcate_pro)
                                    @if ($subcate_pro->parent_category_product_id == $cate_pro->category_product_id)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ URL::to('/home/pages/subcategory/subcategory-product/' . $subcate_pro->slug_subcategory_product) }}">
                                                {{ $subcate_pro->subcategory_product_name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endforeach

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ URL::to('/home/pages/brand/brand-product') }}"
                            role="button" data-bs-toggle="dropdown">THƯƠNG HIỆU</a>
                        <ul class="dropdown-menu" data-bs-auto-close="outside">
                            @foreach ($brand as $key => $brand_pro)
                                <li><a class="dropdown-item"
                                        href="{{ URL::to('/home/pages/brand/brand-product/' . $brand_pro->slug_brand_product) }}">{{ $brand_pro->brand_product_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    {{-- <li class="nav-item"><a class="nav-link" href="">GIÁ MỚI</a></li>
                    <li class="nav-item"><a class="nav-link" href="">TIN THỜI TRANG</a></li> --}}
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    @php
        $email = Session::get('customer_email');
        $secret = 'dd3a40f4c8de86feb2c2bdfc7252919f6c7b31d6'; // Secure mode secret key
        $hash = hash_hmac('sha256', $email, $secret);
    @endphp


    {{-- @if (Session::has('customer_id'))
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();

            // Xóa localStorage và cookie của Tawk.to để bắt đầu session mới
            function clearTawkData() {
                Object.keys(localStorage).forEach(function(key) {
                    if (key.startsWith('tawk_')) {
                        localStorage.removeItem(key);
                    }
                });

                document.cookie.split(";").forEach(function(c) {
                    document.cookie = c.replace(/^ +/, "")
                        .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
                });
            }

            (function() {
                clearTawkData(); // Xóa dữ liệu cũ

                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/684ebd776b4335190a6462ae/1itpp87af';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();

            // Khi Tawk.to load xong, reset session và set name + email
            Tawk_API.onLoad = function() {
                if (typeof Tawk_API.reset === 'function') {
                    Tawk_API.reset(); // reset session cũ
                }

                Tawk_API.setAttributes({
                    'name': "{{ Session::get('customer_name') }}",
                    'email': "{{ Session::get('customer_email') }}"
                }, function(error) {
                    if (error) console.error('Tawk.to setAttributes error:', error);
                });
            };
        </script>
    @endif --}}

    {{-- <--CHAT B ->> --}}
    @if (Session::has('customer_id'))
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();

            Tawk_API.visitor = {
                name: "{{ Session::get('customer_name') }}",
                email: "{{ Session::get('customer_email') }}",
                hash: "{{ $hash }}"
            };

            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/684ebd776b4335190a6462ae/1itpp87af';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    @endif



    <!--End of Tawk.to Script-->

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-3 mb-3">
                    <h6 class="fw-bold">🛍 NQ Fashion Store</h5>
                        <p>Thời trang phong cách, trẻ trung và đẳng cấp.</p>
                        <p><i class="bi bi-envelope"></i> naruto25864@gmail.com</p>
                        <p><i class="bi bi-phone"></i> 0398 098 795</p>
                </div>

                <div class="col-md-3 mb-3">
                    <h6 class="fw-semibold">Bản đồ cửa hàng</h6>
                    <div class="ratio ratio-4x3">
                        <iframe src="https://www.google.com/maps/embed?..."></iframe>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <h6 class="fw-semibold">Thanh toán</h6>
                    <img src="https://cdn.brandfetch.io/idV02t6WJs/w/820/h/249/theme/dark/logo.png?c=1bxid64Mup7aczewSAYMX&t=1750645747861" width="90" class="me-2">
                    <img src="https://cdn.brandfetch.io/idQhfAEHMK/w/432/h/175/theme/dark/logo.png?c=1bxid64Mup7aczewSAYMX&t=1745929332351" width="90">
                </div>

                <div class="col-md-3 mb-3">
                    <h6 class="fw-semibold">Kết nối</h6>
                    <a href="#" class="text-white fs-5 me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-5 me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            <hr class="my-3 border-light">

            <div class="text-center small">
                &copy; 2025 NQ Fashion Store. All rights reserved.
            </div>
        </div>
    </footer>



    <!-- Bootstrap JS Bundle (bao gồm Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{ asset('fontend/js/script.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js "></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.add_cart').click(function() {

            });
        });
    </script>
</body>

</html>
