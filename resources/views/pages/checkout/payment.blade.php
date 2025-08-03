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

        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .payment-container h3 {
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .cart-item {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-details strong {
            font-size: 16px;
        }

        .item-price {
            font-size: 16px;
            font-weight: bold;
            color: #d32f2f;
            text-align: right;
        }

        .item-meta small {
            display: block;
            color: #666;
        }

        .payment-methods label {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ccc;
            padding: 10px 14px;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .payment-methods input[type="radio"] {
            margin-top: 0;
        }

        .payment-methods img {
            height: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-outline-secondary {
            border: 1px solid #ccc;
            color: #333;
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            background-color: #f2f2f2;
        }
    </style>

    <div class="breadcrumb">
        <a href="{{ URL::to('/home/pages/cart/cart') }}">Giỏ hàng</a> &nbsp;/&nbsp; <strong>Xác nhận đơn hàng</strong>
    </div>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="payment-container">
        <form action="{{ URL::to('/home/place-order') }}" method="post">
            @csrf
            <h3>Xem lại đơn hàng</h3>

            <?php $content = Cart::content(); ?>

            @foreach ($content as $v_content)
                <div class="cart-item">
                    <img src="{{ URL::to('/uploads/products/' . $v_content->options->image) }}" alt="{{ $v_content->name }}">
                    <div class="item-details">
                        <strong>{{ $v_content->name }}</strong>
                        <div class="item-meta">
                            <small>Mã: {{ $v_content->options->product_code ?? 'Không có mã' }}</small>
                            <small>Số lượng: {{ $v_content->qty }}</small>
                            <small>Màu: {{ $v_content->options->color }} / Size: {{ $v_content->options->size }}</small>
                        </div>
                    </div>
                    <?php $subtotal = $v_content->price * $v_content->qty; ?>
                    <div class="item-price">
                        {{ number_format($subtotal, 0, ',', '.') }}₫
                    </div>
                </div>
            @endforeach
            <h5 class="mt-4">Mã giảm giá</h5>
            <div class="border-top border-bottom py-3">
                @if (Session::has('cart_coupon'))
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-success text-uppercase">
                                {{ Session::get('cart_coupon')['code'] ?? Session::get('cart_coupon') }}
                            </span>
                            <small class="text-muted ms-2">
                                Áp dụng thành công
                            </small>
                        </div>
                        <button id="delete-coupon" class="btn btn-sm btn-outline-danger">Xoá</button>
                    </div>
                    @if (Session::get('cart_coupon')['discount'] ?? false)
                        <div class="mt-2 text-muted">
                            Giảm:
                            {{ Session::get('cart_coupon')['discount'] }}{{ Session::get('cart_coupon')['discount_type'] == 'percentage' ? '%' : '₫' }}
                        </div>
                    @endif
                @else
                    <span class="text-muted">Chưa có mã giảm giá nào được áp dụng.</span>
                @endif
                </ <br>
            </div>
            <h5>Số tiền thanh toán</h5>

            <div class="border-top pt-3">
                @php
                    $subtotal = Cart::subtotal(0, ',', '');
                    $subtotal = (float) str_replace(',', '', $subtotal);
                    $coupon = Session::get('cart_coupon');
                    $count = Cart::count();
                    $total_discount = 0;
                    $total_coupon = 0;

                    if (is_array($coupon) && isset($coupon['discount_type'], $coupon['discount'])) {
                        if ($coupon['discount_type'] == 'percentage') {
                            $total_coupon = ($subtotal * $coupon['discount']) / 100;
                        } elseif ($coupon['discount_type'] == 'fixed') {
                            $total_coupon = $coupon['discount'];
                        }
                    }
                    if ($count > 5) {
                        $total_discount = $subtotal * 0.1;
                    }
                    $total = $subtotal - $total_discount - $total_coupon;
                @endphp

                <div class="d-flex justify-content-between">
                    <span>Tạm tính</span>
                    <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                </div>
                <div class="d-flex justify-content-between">
                    <span>Giảm giá theo số lượng mua:</span>
                    <strong>{{ number_format($total_discount, 0, ',', '.') }}₫</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Giảm giá:</span>
                    <strong>{{ number_format($total_coupon, 0, ',', '.') }}₫</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Phí vận chuyển</span>
                    <strong>
                        @php
                            $shipping_fee = 0;
                            $free_shipping_threshold = 500000;

                            $shipping_method = Session::get('shipping_method'); // 'internal', 'fast'
                            $city_code = Session::get('shipping_city');
                            $district_code = Session::get('shipping_district');

                            $inner_districts = ['001', '004', '007', '009']; // Quận nội thành HCM
                            $is_inner = $city_code == '79' && in_array($district_code, $inner_districts);

                            if ($subtotal >= $free_shipping_threshold) {
                                // Đủ điều kiện miễn phí
                                if ($shipping_method === 'internal') {
                                    $shipping_fee = 0;
                                    $shipping_method = 'free';
                                    Session::put('shipping_method', 'free');
                                } elseif ($shipping_method === 'fast') {
                                    // Hỏa tốc nhưng chỉ tính phụ phí nhỏ
                                    $shipping_fee = $is_inner ? 15000 : 25000;
                                } else {
                                    $shipping_fee = 0; // fallback
                                }
                            } else {
                                // Chưa đạt miễn phí
                                if ($shipping_method === 'internal') {
                                    $shipping_fee = $is_inner ? 15000 : 25000;
                                } elseif ($shipping_method === 'fast') {
                                    $shipping_fee = $is_inner ? 65000 : 75000;
                                } else {
                                    $shipping_fee = 0;
                                }
                            }
                            echo number_format($shipping_fee, 0, ',', '.') . '₫';
                        @endphp
                        <input type="hidden" name="shipping_fee" value="{{ $shipping_fee }}">
                    </strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Tổng cộng</span>
                    <?php
                    $cartTotal = (float) str_replace(',', '', $total);
                    $tong = $cartTotal + $shipping_fee;
                    ?>
                    <strong class="text-danger">{{ number_format($tong, 0, ',', '.') }}₫</strong>
                    <input type="hidden" name="total" value="{{ $tong }}">
                </div>
            </div>

            <h5 class="mt-4">Phương thức thanh toán</h5>
            <div class="payment-methods">
                <label>
                    <input type="radio" name="payment" value="cod" required>
                    <span>Thanh toán khi giao hàng (COD)</span>
                </label>
                <label>
                    <input type="radio" name="payment" value="vnpay">
                    <img src="https://sandbox.vnpayment.vn/paymentv2/images/logo_vnpay.png" alt="VNPAY">
                    <span>Thanh toán VNPAY</span>
                </label>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ URL::to('/home/pages/cart/cart') }}" class="btn btn-outline-secondary">Quay lại giỏ
                    hàng</a>
                <button type="submit" class="btn btn-primary" style="margin-left: 10px;">
                    Xác nhận thanh toán
                </button>
            </div>
    </div>

    <script>
        document.getElementById('delete-coupon').addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ url('/home/place-order/delete-coupon') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        });
    </script>
@endsection
