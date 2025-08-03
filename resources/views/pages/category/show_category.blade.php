@extends('layout')
@section('content')
    <style>
        .brand-banner {
            width: 100%;
            max-height: 300px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .brand-banner img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
            border-radius: 12px;
        }
    </style>
    <div class="container py-4">
        @if ($category_info->banner)
            <div class="brand-banner mb-4">
                <img src="{{ asset('uploads/banner/category/' . $category_info->banner) }}" alt="Banner"
                    class="img-fluid rounded">
            </div>
        @endif

        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold"> {{ $category_info->category_product_name }}</h2>
            </div>
            <div class="col-md-6 text-md-end">
                <form
                    action="{{ url('/home/pages/category/category-product/' . $category_info->slug_category_product . '/filter') }}"
                    method="GET" id="filterForm">
                    <select class="form-select d-inline-block w-auto me-2" name="productFilter" id="productFilter">
                        <option value="">-- Sắp xếp --</option>
                        <option value="best_seller" {{ request('productFilter') == 'best_seller' ? 'selected' : '' }}>Bán
                            chạy nhất</option>
                        <option value="price_asc" {{ request('productFilter') == 'price_asc' ? 'selected' : '' }}>Giá: Tăng
                            dần</option>
                        <option value="price_desc" {{ request('productFilter') == 'price_desc' ? 'selected' : '' }}>Giá:
                            Giảm dần</option>
                        <option value="atoz" {{ request('productFilter') == 'atoz' ? 'selected' : '' }}>Tên: A → Z
                        </option>
                        <option value="ztoa" {{ request('productFilter') == 'ztoa' ? 'selected' : '' }}>Tên: Z → A
                        </option>
                        <option value="stock_desc" {{ request('productFilter') == 'stock_desc' ? 'selected' : '' }}>Tồn kho:
                            Giảm dần</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="row g-3">
            @foreach ($all_product as $product)
                @if ($product->parent_category_product_id == $category_id)
                    <div class="col-6 col-md-2 col-lg-2">
                        <div class="products-card position-relative border rounded shadow-sm"
                            style="width: 100%; overflow: hidden;">
                            <a href="{{ URL::to('/home/pages/product/detail-product/' . $product->slug_product) }}">
                                <!-- Ảnh sản phẩm -->
                                <div class="position-relative product-image-container">
                                    <img src="{{ asset('uploads/products/' . $product->product_image) }}"
                                        alt="{{ $product->product_name }}" class="w-100"
                                        style="height: 220px; object-fit: cover;">

                                    <!-- Nút thêm giỏ + yêu thích -->
                                    <div class="button-overlay d-flex gap-2">
                                        <form action="{{ url('/home/products/favorite/' . $product->slug_product) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->slug_product }}">
                                            <button type="submit"
                                                class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Thông tin sản phẩm -->
                                <div class="p-2 text-center">
                                    <p class="product-name mb-1" style="font-size: 14px;">{{ $product->product_name }}</p>
                                    <div class="d-flex justify-content-center align-items-center gap-3"
                                        style="font-size: 13px;">
                                        <span class="fw-bold text-danger">
                                            {{ number_format($product->product_price, 0, ',', '.') }}₫
                                        </span>

                                        <span class="fw-bold text-danger">
                                            Lượt mua: {{ $product->total_sold }}
                                        </span>

                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!--Phân trang-->
        <div class="text-center mt-4">
            {{ $all_product->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const selects = form.querySelectorAll('select');

            selects.forEach(function(select) {
                select.addEventListener('change', function() {
                    form.submit(); // Gửi form khi người dùng chọn filter
                });
            });
        });
    </script>
@endsection
