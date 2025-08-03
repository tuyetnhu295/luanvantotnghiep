@extends('layout')
@section('content')
    @if (Session::has('message') || Session::has('error'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div class="toast align-items-center text-white {{ Session::has('message') ? 'bg-success' : 'bg-danger' }} border-0 shadow"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ Session::get('message') ?? Session::get('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toastEl = document.querySelector('.toast');
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl, {
                        delay: 5000
                    });
                    toast.show();
                }
            });
        </script>
    @endif
    <!-- Carousel -->
    <div class="carousel-container mt-2">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('fontend/images/image1.png') }}">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('fontend/images/image2.png') }}">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('fontend/images/image2.png') }}">
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="container mt-4">
        <div class="container mt-5">
            <h4 class="fw-bold">
                <span style="border-left: 5px solid black; padding-left: 10px;">ƯU ĐÃI DÀNH CHO BẠN</span>
            </h4>

            <div class="voucher-container">
                @foreach ($coupon as $value)
                    @if (
                        // Nếu KH đã mua → chỉ hiện mã dành cho "returning" hoặc "all"
                        ($order && in_array($value->customer_type, ['returning', 'all'])) ||
                            // Nếu KH chưa mua → chỉ hiện mã dành cho "new" hoặc "all"
                            (!$order && in_array($value->customer_type, ['new', 'all'])))
                        @php
                            $today = \Carbon\Carbon::today();
                        @endphp
                        @if ($today->between($value->start_date, $value->end_date))
                            <div class="voucher-card">
                                <div class="voucher-left">{{ $value->coupon_code }}</div>
                                <div class="voucher-right">
                                    <h5>
                                        @if ($value->discount_type == 'percentage')
                                            Giảm {{ $value->discount_value }}%
                                        @else
                                            Giảm {{ number_format($value->discount_value, 0, ',', '.') }}₫
                                        @endif
                                    </h5>
                                    <p>Giá trị đơn hàng tối thiểu {{ number_format($value->min_order_value, 0, ',', '.') }}₫
                                    </p>
                                    <small>Mã: <b>{{ $value->coupon_code }}</b></small> <br>
                                    <small>HSD: {{ \Carbon\Carbon::parse($value->start_date)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($value->end_date)->format('d/m/Y') }}</small> <br>
                                    <small>Áp dụng cho:
                                        @if ($value->customer_type == 'new')
                                            Khách hàng mới
                                        @elseif ($value->customer_type == 'returning')
                                            Khách hàng thân thiết
                                        @else
                                            Tất cả khách hàng
                                        @endif
                                    </small><br>
                                    <input type="hidden" name="coupon_code" value="{{ $value->coupon_code }}">
                                    <button type="button" class="copy-btn" data-code="{{ $value->coupon_code }}">Lưu
                                        mã</button>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

        </div>

        <!--Hàng bán chạy-->
        <div class="container mt-5">
            <h4 class="fw-bold">
                <span style="border-left: 5px solid black; padding-left: 10px;">HÀNG BÁN CHẠY</span>
            </h4>
            @php
                $count = 0;
            @endphp
            <div class="bestsell-container">
                <div class="row g-3">
                    @foreach ($products as $product)
                        @if ($count < 7)
                            <div class="col-6 col-md-2 col-lg-2">
                                <div class="products-card position-relative border rounded shadow-sm"
                                    style="width: 100%; overflow: hidden;">
                                    <a
                                        href="{{ URL::to('/home/pages/product/detail-product/' . $product->slug_product) }}">
                                        <div class="position-relative product-image-container">
                                            <img src="{{ asset('uploads/products/' . $product->product_image) }}"
                                                alt="{{ $product->product_name }}" class="w-100"
                                                style="height: 220px; object-fit: cover;">

                                            <div class="button-overlay d-flex gap-2">
                                                <form action="{{ url('/home/products/favorite/' . $product->slug_product) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $product->slug_product }}">
                                                    <button type="submit"
                                                        class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-heart"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="p-2 text-center">
                                            <p class="product-name mb-1" style="font-size: 14px;">
                                                {{ $product->product_name }}</p>
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
                            @php $count++; @endphp
                        @else
                            @break
                        @endif
                    @endforeach
                </div>
                <!-- Nút Xem tất cả -->
                <div class="text-center my-4">
                    <a href="{{ URL::to('/home/pages/best-selling') }}" class="btn btn-dark px-4 py-2 rounded-pill">Xem tất
                        cả »</a>
                </div>
            </div>

        </div>
        <!--Hàng mới-->
        <div class="container mt-5">
            <h4 class="fw-bold">
                <span style="border-left: 5px solid black; padding-left: 10px;">SẢN PHẨM MỚI</span>
            </h4>
            @php
                $count = 0;
            @endphp
            <div class="bestsell-container">
                <div class="row g-3">
                    @foreach ($products_new as $product)
                        @if ($count < 7)
                            <div class="col-6 col-md-2 col-lg-2">
                                <div class="products-card position-relative border rounded shadow-sm"
                                    style="width: 100%; overflow: hidden;">
                                    <a
                                        href="{{ URL::to('/home/pages/product/detail-product/' . $product->slug_product) }}">
                                        <div class="position-relative product-image-container">
                                            <img src="{{ asset('uploads/products/' . $product->product_image) }}"
                                                alt="{{ $product->product_name }}" class="w-100"
                                                style="height: 220px; object-fit: cover;">

                                            <div class="button-overlay d-flex gap-2">
                                                <form action="{{ url('/home/products/favorite/' . $product->slug_product) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $product->slug_product }}">
                                                    <button type="submit"
                                                        class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-heart"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="p-2 text-center">
                                            <p class="product-name mb-1" style="font-size: 14px;">
                                                {{ $product->product_name }}</p>
                                            <div class="d-flex justify-content-center align-items-center gap-1"
                                                style="font-size: 14px;">
                                                <span class="fw-bold text-danger">
                                                    {{ number_format($product->product_price, 0, ',', '.') }}₫
                                                </span>


                                            </div>
                                        </div>
                                        <div class="p-2 text-center">
                                            <p class="product-name mb-1" style="font-size: 14px;">
                                                {{ $product->product_name }}</p>
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
                            @php $count++; @endphp
                        @else
                            @break
                        @endif
                    @endforeach
                </div>
                <!-- Nút Xem tất cả -->
                <div class="text-center my-4">
                    <a href="{{ URL::to('/home/pages/new-products') }}" class="btn btn-dark px-4 py-2 rounded-pill">Xem
                        tất cả »</a>
                </div>
            </div>

        </div>
        <!-- Products -->
        <div class="container mt-4">
            @foreach ($category as $key => $cate_pro)
                <h4 class="fw-bold mt-4">
                    <span class="category-title">{{ $cate_pro->category_product_name }}</span>
                </h4>

                @php
                    $count = 0;
                @endphp

                <div class="row g-3">
                    @foreach ($all_product as $product)
                        @if ($product->parent_category_product_id == $cate_pro->category_product_id)
                            @if ($count < 19)
                                <div class="col-6 col-md-2 col-lg-2">
                                    <div class="products-card position-relative border rounded shadow-sm"
                                        style="width: 100%; overflow: hidden;">
                                        <a
                                            href="{{ URL::to('/home/pages/product/detail-product/' . $product->slug_product) }}">
                                            <div class="position-relative product-image-container">
                                                <img src="{{ asset('uploads/products/' . $product->product_image) }}"
                                                    alt="{{ $product->product_name }}" class="w-100"
                                                    style="height: 220px; object-fit: cover;">

                                                <div class="button-overlay d-flex gap-2">
                                                    <form action="{{ url('/home/products/favorite/' . $product->slug_product) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->slug_product }}">
                                                        <button type="submit"
                                                            class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="bi bi-heart"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="p-2 text-center">
                                                <p class="product-name mb-1" style="font-size: 14px;">
                                                    {{ $product->product_name }}</p>
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
                                @php $count++; @endphp
                            @else
                                @break
                            @endif
                        @endif
                    @endforeach
                </div>
                <!-- Nút Xem tất cả -->
                <div class="text-center my-4">
                    <a href="{{ URL::to('/home/pages/category/category-product/' . $cate_pro->slug_category_product) }}"
                        class="btn btn-dark px-4 py-2 rounded-pill">Xem tất cả »</a>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('.copy-btn');

            copyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const code = this.getAttribute('data-code');

                    const tempInput = document.createElement('input');
                    tempInput.value = code;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);


                    this.textContent = "✓ Đã lưu";
                    setTimeout(() => {
                        this.textContent = "Lưu mã";
                    }, 2000);
                });
            });
        });
    </script>

@endsection
