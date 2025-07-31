@extends('layout')
@section('content')
    <style>
        .breadcrumb {
            margin: 20px 0;
            font-size: 14px;
            text-align: center;
        }

        .breadcrumb a {
            text-decoration: none;
            color: steelblue;
        }
    </style>
    <div class="breadcrumb">
        <a href="{{ URL::to('/home') }}">Trang chủ</a> &nbsp;/&nbsp; Tìm kiếm
    </div>
    <div class="container mt-2">
        <div class="container mt-4">
            <h4 class="fw-bold mt-4 text-center">
                <span class="search-title">TÌM KIẾM</span>
            </h4>
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h5 class="m-0">Kết quả tìm kiếm cho: <strong>{{ request()->input('keyword') }}</strong></h5>
                <div class="btn-group mt-2 mt-md-0">
                    <button class="btn btn-light">SẢN PHẨM ({{ count($product) }})</button>
                </div>
            </div>
            <div class="row row-cols-2 row-cols-md-4 g-4">
                @foreach ($product as $key => $value)
                    <div class="col">
                        <a href="{{ URL::to('/home/pages/product/detail-product/' . $value->slug_product) }}"
                            class="text-decoration-none text-dark">
                            <div class="products-card position-relative border rounded shadow-sm h-100">
                                <!-- Hình ảnh -->
                                <div class="position-relative product-image-container">
                                    <img src="{{ asset('uploads/products/' . $value->product_image) }}"
                                        alt="{{ $value->product_name }}" class="w-100"
                                        style="height: 220px; object-fit: cover;">
                                    <div class="button-overlay d-flex ">
                                        <form method="POST" >@csrf
                                            <input type="hidden" name="product_id" value="{{ $value->product_id }}">
                                            <button type="submit" class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <!-- Thông tin sản phẩm -->
                                <div class="p-2 text-center">
                                    <p class="product-name mb-1" style="font-size: 14px;">{{ $value->product_name }}</p>
                                    <div class="d-flex justify-content-center align-items-center gap-3"
                                        style="font-size: 13px;">
                                        <span class="fw-bold text-danger">
                                            {{ number_format($value->product_price, 0, ',', '.') }}₫
                                        </span>

                                        <span class="fw-bold text-danger">
                                            Lượt mua: {{ $value->total_sold }}
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
