@extends('admin_layout')
@section('admin_content')
    <style>
        .print {
            height: 50px;
            width: 100px;
            font-size: 20px;
            font-weight: bold;
            float: right;
        }
    </style>
    <div class="container mt-4">
        <h2 class="mb-4">Chi tiết đơn hàng #{{ $order_info->order_code }}</h2>
        {{-- Thong tin khach hang --}}
        <div class="mb-4">
            <h5>👤 Thông tin người đặt</h5>
            <p><strong>Họ và tên:&nbsp;</strong>{{ $order_info->customer_name }}</p>
            <p><strong>Email:&nbsp;</strong>{{ $order_info->customer_email }}</p>
            <p><strong>Số điện thoại:&nbsp;</strong>{{ $order_info->customer_phone }}</p>
        </div>

        {{-- Dia chi giao hang --}}
        <div class="mb-4">
            <h5>📦 Thông tin giao hàng</h5>
            <p><strong>Địa chỉ:</strong> {{ $order_info->shipping_address }}, {{ $wardName }}, {{ $districtName }},
                {{ $cityName }}</p>
            <p><strong>Ghi chú:</strong> {{ $order_info->shipping_note }}</p>
        </div>

        {{-- Thanh toan --}}
        <div class="mb-4">
            <h5>💳 Thanh toán</h5>

            <p><strong>Phương thức:</strong> {{ $order_info->payment_method }}</p>
            <p><strong>Mã giao dịch:</strong> {{ $order_info->transaction_code }}</p>
        </div>

        {{-- Danh sach san pham --}}
        <div class="table-responsive">
            <table id="productTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Màu</th>
                        <th scope="col">Kích thước</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    ?>
                    @php
                        $order_subtotal = 0;
                        $order_total_coupon = 0;
                    @endphp
                    @foreach ($order_details as $item)
                        @php
                            $subtotal = $item->product_price * $item->product_sales_quantity;
                            $order_subtotal += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td><img src="{{ $item->image }}" alt="{{ $item->image }}" width="60" height="60"></td>
                            <td>{{ number_format($item->product_price, 0, ',', '.') }}₫</td>
                            <td>{{ $item->product_color }}</td>
                            <td>{{ $item->product_size }}</td>
                            <td>{{ $item->product_sales_quantity }}</td>
                            <td>{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                        </tr>
                    @endforeach
                    @php

                        $order_total_coupon = 0;

                        if ($order_info->discount_type == 'percentage') {
                            $order_total_coupon = ($order_subtotal * $order_info->discount_value) / 100;
                        } elseif ($order_info->discount_type == 'fixed') {
                            $order_total_coupon = $order_info->discount_value;
                        }

                        $shipping_fee = $order_info->shipping_fee;

                        $order_total = $order_subtotal - $order_total_coupon + $shipping_fee;

                    @endphp

                    <tr>
                        <td colspan="7" class="text-end"><strong>Tạm tính:</strong></td>
                        <td><strong>{{ number_format($order_subtotal * 1.1, 0, ',', '.') }}₫</strong></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-end"><strong>Giảm giá:</strong></td>
                        <td><strong>-{{ number_format($order_total_coupon, 0, ',', '.') }}₫</strong></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-end"><strong>Phí vận chuyển:</strong></td>
                        <td><strong>{{ number_format($shipping_fee, 0, ',', '.') }}₫</strong></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td><strong>{{ number_format($order_total * 1.1, 0, ',', '.') }}₫</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
