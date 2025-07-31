@extends('pages.customer.information')

@section('profile_content')

    <style>
        .button-remove {
            border-radius: 10px;
            margin-top: 10px;
            height:50px;
            background-color:tomato;
            color:white;
        }
    </style>
    @if (Session::has('message'))
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
                    <div class="toast align-items-center text-white bg-danger border-0 show shadow" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ Session::get('message') }}
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
    
    
    
    <div class="container py-4">

        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">Sản phẩm yêu thích </h2>
            </div>
            <div class="col-md-6 text-md-end">
                <form action="{{ url('/home/account/info/favorite-product/filter') }}" method="GET" id="filterForm">
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


        <div class="row g-4 product-list">

            @if ($favorite->count() > 0)
                @foreach ($favorite as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="products-card position-relative border rounded shadow-sm"
                            style="width: 100%; overflow: hidden;">
                            <a href="{{ URL::to('/home/pages/product/detail-product/' . $product->slug_product) }}">
                                <!-- Ảnh sản phẩm -->
                                <div class="position-relative product-image-container">
                                    <img src="{{ asset('uploads/products/' . $product->product_image) }}"
                                        alt="{{ $product->product_name }}" class="w-100 img-fluid"
                                        style="height: 150px; object-fit: cover;">

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
                                    <form action="{{ url('/home/unloved-product/'.$product->product_id) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="button-remove">Bỏ yêu thích</button>
                                    </form>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">Không tìm thấy sản phẩm nào.</div>
            @endif
        </div>

        <!--Phân trang-->
        <div class="text-center mt-4">
            {{ $favorite->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const selects = form.querySelectorAll('select');

            selects.forEach(function(select) {
                select.addEventListener('change', function() {
                    form.submit();
                });
            });
        });
    </script>


@endsection
