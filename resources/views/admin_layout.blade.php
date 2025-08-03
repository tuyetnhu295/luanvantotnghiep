<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    @yield('head')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar .nav-link {
            padding-left: 1rem;
        }

        .sidebar .collapse .nav-link {
            padding-left: 2rem;
        }

        .sidebar .collapse .collapse .nav-link {
            padding-left: 3rem;
        }

        .profile-wrapper {
            margin-left: 250px;
            padding: 20px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.05);
        }

        .profile {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {

            .profile-wrapper,
            .content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Navbar toggle -->
    <nav class="navbar navbar-light bg-light d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-dark" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasSidebar">
                ☰ Menu
            </button>
        </div>
    </nav>

    <!-- Sidebar Offcanvas cho Mobile -->
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Admin Panel</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        @php
            $role = Session::get('admin_role');
        @endphp
        <div class="offcanvas-body">
            @if (in_array($role, ['superadmin', 'manager']))
                <a class="nav-link text-white" href="{{ URL::to('/admin/dashboard') }}">📊 Dashboard</a>

                <!-- DANH MỤC -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileCategoryMenu" role="button"
                        aria-expanded="false" aria-controls="mobileCategoryMenu">
                        📂 Danh mục <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="mobileCategoryMenu">
                        <!-- Danh mục cha -->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileParentMenu" role="button"
                            aria-expanded="false" aria-controls="mobileParentMenu">
                            📁 Danh mục sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileParentMenu">
                            <a href="{{ URL::to('/admin/add-category-product') }}" class="nav-link text-white">➕ Thêm
                                danh
                                mục</a>
                            <a href="{{ URL::to('/admin/all-category-product') }}" class="nav-link text-white">📋 Liệt
                                kê
                                danh mục</a>
                        </div>

                        <!-- Danh mục con -->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileSubMenu" role="button"
                            aria-expanded="false" aria-controls="mobileSubMenu">
                            👕 Loại sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileSubMenu">
                            <a href="{{ URL::to('/admin/add-subcategory-product') }}" class="nav-link text-white">➕ Thêm
                                loại sản phẩm</a>
                            <a href="{{ URL::to('/admin/all-subcategory-product') }}" class="nav-link text-white">📋
                                Liệt kê
                                loại sản phẩm</a>
                        </div>
                    </div>
                </div>

                <!-- THUONG HIEU -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#brandMenu" role="button"
                        aria-expanded="false" aria-controls="brandMenu">
                        🏷️ Thương hiệu sản phẩm <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="brandMenu">
                        <a href="{{ URL::to('/admin/add-brand-product') }}" class="nav-link text-white">➕ Thêm thương
                            hiệu</a>
                        <a href="{{ URL::to('/admin/all-brand-product') }}" class="nav-link text-white">📋 Liệt kê
                            thương
                            hiệu</a>
                    </div>
                </div>

                <!-- SẢN PHẨM -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileProductMenu" role="button"
                        aria-expanded="false" aria-controls="mobileProductMenu">
                        🛍️ Sản phẩm <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="mobileProductMenu">
                        <!-- Sản phẩm -->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileProDuctMenu"
                            role="button" aria-expanded="false" aria-controls="mobileProDuctMenu">
                            🛍️ Sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileProDuctMenu">
                            <a href="{{ URL::to('/admin/add-product') }}" class="nav-link text-white">➕ Thêm sản
                                phẩm</a>
                            <a href="{{ URL::to('/admin/all-product') }}" class="nav-link text-white">📋 Liệt kê
                                danh sản phẩm</a>
                        </div>

                        <!-- Hình ảnh sản phẩm -->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileImagesMenu"
                            role="button" aria-expanded="false" aria-controls="mobileImagesMenu">
                            🖼️ Hình ảnh sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileImagesMenu">
                            <a href="{{ URL::to('/admin/add-product-images') }}" class="nav-link text-white">➕ Thêm
                                hình
                                ảnh</a>
                            <a href="{{ URL::to('/admin/all-product-images') }}" class="nav-link text-white">📋 Liệt
                                kê
                                hình ảnh</a>
                        </div>

                        <!-- Size -->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileSizeMenu"
                            role="button" aria-expanded="false" aria-controls="mobileSizeMenu">
                            📏 Kích cỡ sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileSizeMenu">
                            <a href="{{ URL::to('/admin/product/size/add-product-size') }}"
                                class="nav-link text-white">➕
                                Thêm kích cỡ
                            </a>
                            <a href="{{ URL::to('/admin/product/size/all-product-size') }}"
                                class="nav-link text-white">📋 Liệt kê
                                kích cỡ </a>
                        </div>

                        <!-- Color-->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileColorMenu"
                            role="button" aria-expanded="false" aria-controls="mobileColorMenu">
                            🎨 Màu sắc sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileColorMenu">
                            <a href="{{ URL::to('/admin/product/color/add-product-color') }}"
                                class="nav-link text-white">➕ Thêm màu
                                sắc</a>
                            <a href="{{ URL::to('/admin/product/color/all-product-color') }}"
                                class="nav-link text-white">📋 Liệt kê
                                màu sắc</a>
                        </div>

                        <!-- Variants-->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileVariantMenu"
                            role="button" aria-expanded="false" aria-controls="mobileVariantMenu">
                            🧬 Biến thể sản phẩm
                        </a>
                        <div class="collapse ms-3" id="mobileVariantMenu">
                            <a href="{{ URL::to('/admin/product/product-variants/add-product-variant') }}"
                                class="nav-link text-white">➕ Thêm biến thể</a>
                            <a href="{{ URL::to('/admin/product/product-variants/all-product-variant') }}"
                                class="nav-link text-white">📋 Liệt kê
                                biến thể</a>
                        </div>

                    </div>
                </div>
            @endif
            @if (in_array($role, ['superadmin', 'manager', 'staff']))
                <!--DON HANG-->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileOrderMenu" role="button"
                        aria-expanded="false" aria-controls="mobileOrderMenu">
                        📦 Đơn hàng <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="mobileOrderMenu">
                        <!-- Đơn hàng -->
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#mobileOrdersMenu"
                            role="button" aria-expanded="false" aria-controls="mobileOrdersMenu">
                            📦 Đơn hàng
                        </a>
                        <div class="collapse ms-3" id="mobileOrdersMenu">
                            <a href="{{ URL::to('/admin/order/manage-order') }}" class="nav-link text-white">📋 Quản
                                lý
                                đơn hàng</a>
                            <a href="{{ URL::to('/admin/order/manage-order-returns') }}"
                                class="nav-link text-white">📋
                                Quản lý
                                đơn hàng trả về</a>

                        </div>
                    </div>
                </div>
            @endif

            @if (in_array($role, ['superadmin', 'manager']))
                <!--MA KHUYEN MAI-->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#couponMenu" role="button"
                        aria-expanded="false" aria-controls="couponMenu">
                        🎟️ Mã khuyến mãi <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="couponMenu">
                        <a href="{{ URL::to('/admin/add-coupon') }}" class="nav-link text-white">➕ Thêm mã khuyến
                            mãi</a>
                        <a href="{{ URL::to('/admin/all-coupon') }}" class="nav-link text-white">📋 Danh sách mã
                            khuyến
                            mãi</a>
                    </div>
                </div>
            @endif

            @if ($role === 'superadmin')
                <!-- NGƯỜI DÙNG (NHÂN VIÊN/ADMIN) -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#adminUserMenu" role="button"
                        aria-expanded="false" aria-controls="adminUserMenu">
                        🧑‍💻 Nhân viên <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="adminUserMenu">
                        <a href="{{ URL::to('/admin/staffs') }}" class="nav-link text-white">📋 Danh sách nhân
                            viên</a>
                        <a href="{{ URL::to('/admin/staffs/create') }}" class="nav-link text-white">➕ Thêm nhân
                            viên</a>
                    </div>
                </div>

                <!-- KHÁCH HÀNG -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#customerMenu" role="button"
                        aria-expanded="false" aria-controls="customerMenu">
                        🧑‍💼 Khách hàng <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="customerMenu">
                        <a href="{{ URL::to('/admin/customers') }}" class="nav-link text-white">📋 Danh sách khách
                            hàng</a>
                    </div>
                </div>
            @endif

            @if (in_array($role, ['superadmin', 'staff']))
                <!-- COMMENT -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#commentMenu" role="button"
                        aria-expanded="false" aria-controls="commentMenu">
                        💬 Bình luận <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="commentMenu">
                        <a href="{{ URL::to('/admin/comments') }}" class="nav-link text-white">📋 Danh sách bình
                            luận</a>
                    </div>
                </div>
            @endif

            @if (in_array($role, ['superadmin', 'shipper']))
                <!-- SHIPPER -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#shipperMenu" role="button"
                        aria-expanded="false" aria-controls="shipperMenu">
                        🚚 Giao hàng <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="shipperMenu">
                        <a href="{{ URL::to('/admin/delivery/orders') }}" class="nav-link text-white">🚚 Đơn hàng</a>
                    </div>
                </div>
            @endif

            @if (in_array($role, ['superadmin', 'manager', 'staff']))
                <!-- LOVE -->
                <div class="dropdown">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#loveMenu" role="button"
                        aria-expanded="false" aria-controls="loveMenu">
                        ❤️ Yêu thích <i class="bi bi-caret-down-fill ms-1"></i>
                    </a>
                    <div class="collapse ms-3" id="loveMenu">
                        <a href="{{ URL::to('/admin/favorite-products') }}" class="nav-link text-white">📋 Danh sách
                            sản
                            phẩm yêu thích</a>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- Sidebar Desktop -->
    <div class="sidebar d-none d-md-block">
        <h4 class="text-center">Admin Panel</h4>
        @if (in_array($role, ['superadmin', 'manager']))
            <a class="active nav-link" href="{{ URL::to('/admin/dashboard') }}">📊 Dashboard</a>

            <!-- DANH MỤC -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopCategoryMenu" role="button"
                    aria-expanded="false" aria-controls="desktopCategoryMenu">
                    📂 Danh mục <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="desktopCategoryMenu">
                    <!-- Danh mục cha -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopParentMenu"
                        role="button" aria-expanded="false" aria-controls="desktopParentMenu">
                        📁 Danh mục sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopParentMenu">
                        <a href="{{ URL::to('/admin/add-category-product') }}" class="nav-link text-white">➕ Thêm
                            danh
                            mục</a>
                        <a href="{{ URL::to('/admin/all-category-product') }}" class="nav-link text-white">📋 Liệt kê
                            danh mục</a>
                    </div>

                    <!-- Danh mục con -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopSubMenu" role="button"
                        aria-expanded="false" aria-controls="desktopSubMenu">
                        👕Loại sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopSubMenu">
                        <a href="{{ URL::to('/admin/add-subcategory-product') }}" class="nav-link text-white">➕ Thêm
                            loại
                            sản phẩm</a>
                        <a href="{{ URL::to('/admin/all-subcategory-product') }}" class="nav-link text-white">📋 Liệt
                            kê
                            loại sản phẩm</a>
                    </div>
                </div>
            </div>

            <!-- THUONG HIEU -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#brandMenu" role="button"
                    aria-expanded="false" aria-controls="brandMenu">
                    🏷️ Thương hiệu sản phẩm <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="brandMenu">
                    <a href="{{ URL::to('/admin/add-brand-product') }}" class="nav-link text-white">➕ Thêm thương
                        hiệu</a>
                    <a href="{{ URL::to('/admin/all-brand-product') }}" class="nav-link text-white">📋 Liệt kê thương
                        hiệu</a>
                </div>
            </div>

            <!-- SẢN PHẨM -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopProductMenu" role="button"
                    aria-expanded="false" aria-controls="desktopProductMenu">
                    🛍️ Sản phẩm <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="desktopProductMenu">
                    <!-- Sản phẩm cha -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopProDuctMenu"
                        role="button" aria-expanded="false" aria-controls="desktopProDuctMenu">
                        🛍️ Sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopProDuctMenu">
                        <a href="{{ URL::to('/admin/add-product') }}" class="nav-link text-white">➕ Thêm sản phẩm</a>
                        <a href="{{ URL::to('/admin/all-product') }}" class="nav-link text-white">📋 Liệt kê
                            danh sản phẩm</a>
                    </div>

                    <!-- Hình ảnh sản phẩm -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopImagesMenu"
                        role="button" aria-expanded="false" aria-controls="desktopImagesMenu">
                        🖼️ Hình ảnh sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopImagesMenu">
                        <a href="{{ URL::to('/admin/add-product-images') }}" class="nav-link text-white">➕ Thêm hình
                            ảnh</a>
                        <a href="{{ URL::to('/admin/all-product-images') }}" class="nav-link text-white">📋 Liệt kê
                            hình ảnh</a>
                    </div>

                    <!-- Kích cỡ sản phẩm -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopSizeMenu" role="button"
                        aria-expanded="false" aria-controls="desktopSizeMenu">
                        📏 Kích cỡ sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopSizeMenu">
                        <a href="{{ URL::to('/admin/product/size/add-product-size') }}" class="nav-link text-white">➕
                            Thêm kích cỡ</a>
                        <a href="{{ URL::to('/admin/product/size/all-product-size') }}"
                            class="nav-link text-white">📋
                            Liệt kê
                            kích cỡ</a>
                    </div>

                    <!-- Màu sắc sản phẩm -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopColorMenu" role="button"
                        aria-expanded="false" aria-controls="desktopColorMenu">
                        🎨 Màu sắc sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopColorMenu">
                        <a href="{{ URL::to('/admin/product/color/add-product-color') }}"
                            class="nav-link text-white">➕
                            Thêm màu sắc</a>
                        <a href="{{ URL::to('/admin/product/color/all-product-color') }}"
                            class="nav-link text-white">📋
                            Liệt kê màu sắc</a>
                    </div>

                    <!-- Variants sản phẩm -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopVariantMenu"
                        role="button" aria-expanded="false" aria-controls="desktopVariantMenu">
                        🧬 Biến thể sản phẩm
                    </a>
                    <div class="collapse ms-3" id="desktopVariantMenu">
                        <a href="{{ URL::to('/admin/product/product-variants/add-product-variant') }}"
                            class="nav-link text-white">➕ Thêm biến thể</a>
                        <a href="{{ URL::to('/admin/product/product-variants/all-product-variant') }}"
                            class="nav-link text-white">📋 Liệt kê biến thể</a>
                    </div>
                </div>
            </div>
        @endif

        @if (in_array($role, ['superadmin', 'staff', 'manager']))
            <!-- ĐƠN HÀNG -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopOrderMenu" role="button"
                    aria-expanded="false" aria-controls="desktopOrderMenu">
                    📦 Đơn hàng <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="desktopOrderMenu">
                    <!-- Đơn hàng -->
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#desktopOrdersMenu"
                        role="button" aria-expanded="false" aria-controls="desktopOrdersMenu">
                        📦 Đơn hàng
                    </a>
                    <div class="collapse ms-3" id="desktopOrdersMenu">
                        <a href="{{ URL::to('/admin/order/manage-order') }}" class="nav-link text-white">📋 Quản lý
                            đơn
                            hàng</a>
                        <a href="{{ URL::to('/admin/order/manage-order-returns') }}" class="nav-link text-white">📋
                            Quản
                            lý đơn
                            hàng trả về</a>
                    </div>

                </div>
            </div>
        @endif

        @if (in_array($role, ['superadmin', 'manager']))
            <!-- MA KHUYEN MAI -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#couponMenu" role="button"
                    aria-expanded="false" aria-controls="couponMenu">
                    🎟️ Mã khuyến mãi <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="couponMenu">
                    <a href="{{ URL::to('/admin/add-coupon') }}" class="nav-link text-white">➕ Thêm mã khuyến mãi</a>
                    <a href="{{ URL::to('/admin/all-coupon') }}" class="nav-link text-white">📋 Danh sách mã khuyến
                        mãi</a>
                </div>
            </div>
        @endif

        @if ($role === 'superadmin')
            <!-- NGƯỜI DÙNG (NHÂN VIÊN/ADMIN) -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#adminUserMenu" role="button"
                    aria-expanded="false" aria-controls="adminUserMenu">
                    🧑‍💻 Nhân viên <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="adminUserMenu">
                    <a href="{{ URL::to('/admin/staffs') }}" class="nav-link text-white">📋 Danh sách nhân viên</a>
                    <a href="{{ URL::to('/admin/staffs/create') }}" class="nav-link text-white">➕ Thêm nhân viên</a>
                </div>
            </div>

            <!-- KHÁCH HÀNG -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#customerMenu" role="button"
                    aria-expanded="false" aria-controls="customerMenu">
                    🧑‍💼 Khách hàng <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="customerMenu">
                    <a href="{{ URL::to('/admin/customers') }}" class="nav-link text-white">📋 Danh sách khách
                        hàng</a>
                </div>
            </div>
        @endif

        @if (in_array($role, ['superadmin', 'staff']))
            <!-- COMMENT -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#commentMenu" role="button"
                    aria-expanded="false" aria-controls="commentMenu">
                    💬 Bình luận <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="commentMenu">
                    <a href="{{ URL::to('/admin/comments') }}" class="nav-link text-white">📋 Danh sách bình luận</a>
                </div>
            </div>
        @endif

        @if (in_array($role, ['superadmin', 'shipper']))
            <!-- SHIPPER -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#shipperMenu" role="button"
                    aria-expanded="false" aria-controls="shipperMenu">
                    🚚 Giao hàng <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="shipperMenu">
                    <a href="{{ URL::to('/admin/delivery/orders') }}" class="nav-link text-white">🚚 Đơn hàng</a>
                </div>
            </div>
        @endif

        @if (in_array($role, ['superadmin', 'manager', 'staff']))
            <!-- LOVE -->
            <div class="dropdown">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#loveMenu" role="button"
                    aria-expanded="false" aria-controls="loveMenu">
                    ❤️ Yêu thích <i class="bi bi-caret-down-fill ms-1"></i>
                </a>
                <div class="collapse ms-3" id="loveMenu">
                    <a href="{{ URL::to('/admin/favorite-products') }}" class="nav-link text-white">
                        📋 Danh sách sản phẩm yêu thích
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Profile -->
    <div class="profile">
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                aria-expanded="false" style="margin:auto;">

                @php
                    $name = Session::get('admin_name');
                    $id=Session::get('admin_id');
                @endphp
                👤{{ $name ?? 'Chưa có tên' }}
            </button>

            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ url('/admin/info' ) }}"><i class="fi fi-ss-user"></i> Thông tin cá nhân</a></li>
                <li><a class="dropdown-item text-danger" href="{{ URL::to('/admin/logout') }}">🚪 Đăng xuất</a></li>
            </ul>
        </div>
    </div>


    <!-- Nội dung chính -->
    <div class="content">
        @yield('admin_content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="{{asset('backend/ckeditor/ckeditor.js')}}"></script> --}}
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>


    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        CKEDITOR.replace('product_desc');
        CKEDITOR.replace('product_content');
        CKEDITOR.replace('category_desc');
        CKEDITOR.replace('brand_desc');
        CKEDITOR.replace('subcategory_desc');
    </script>


    @yield('scripts')



</body>

</html>
