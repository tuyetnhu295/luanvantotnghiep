@extends('pages.customer.information')

@section('profile_content')
    <h4 class="mb-4"><strong>CHI TIẾT ĐƠN HÀNG #{{ $order_info->order_code }}</strong></h4>

    {{-- Thông tin chung của đơn hàng --}}
    <div class="border rounded p-3 mb-4 bg-light">
        <p><strong>Ngày đặt hàng:</strong> {{ \Carbon\Carbon::parse($order_info->created_at)->format('d/m/Y') }}</p>
        <p><strong>Trạng thái đơn hàng:</strong>
            @if ($order_info->order_status == 'Đang chờ xử lý')
                <span class="badge bg-secondary text-dark">Đang chờ xử lý</span>
            @elseif ($order_info->order_status == 'Đã xác nhận')
                <span class="badge bg-info">Đã xác nhận</span>
            @elseif ($order_info->order_status == 'Đang giao')
                <span class="badge bg-primary">Đang giao</span>
            @elseif ($order_info->order_status == 'Đã giao hàng')
                <span class="badge bg-success">Đã giao hàng</span>
            @elseif ($order_info->order_status == 'cancelled')
                <span class="badge bg-warning">Đã hủy</span>
            @elseif ($order_info->order_status == 'returned')
                <span class="badge bg-danger">Đã hoàn trả</span>
            @else
                {{ ucfirst($order->order_status) }}
            @endif
        </p>
        <p><strong>Phương thức thanh toán:</strong> {{ $order_info->payment_method ?? 'Chưa cập nhật' }}</p>
        <p><strong>Tổng tiền:</strong> <span
                class="text-danger fw-bold">{{ number_format($order_info->order_total, 0, ',', '.') }}₫</span></p>
    </div>

    {{-- Thông tin người nhận --}}
    <div class="border rounded p-3 mb-4">
        <h5 class="mb-3">Thông tin người nhận</h5>
        <p><strong>Họ tên:</strong> {{ $order_info->customer_name }}</p>
        <p><strong>Điện thoại:</strong> {{ $order_info->customer_phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order_info->shipping_address }}, {{ $wardName }}, {{ $districtName }},
            {{ $cityName }}</p>
        <p><strong>Email:</strong> {{ $order_info->customer_email }}</p>
    </div>

    <h5 class="mb-3">Sản phẩm đã mua</h5>

    @foreach ($order_details as $item)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex flex-wrap align-items-start">
                <img src="{{ asset('uploads/products/' . $item->product_image) }}" alt="{{ $item->product_name }}" width="100"
                    height="100" class="me-4 rounded border">
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                    <p class="mb-1">Số lượng: <strong>{{ $item->product_sales_quantity }}</strong></p>
                    <p class="mb-1">Giá bán:
                        <strong>{{ number_format($item->product_price, 0, ',', '.') }}₫</strong>
                    </p>
                    <p class="mb-0 text-muted">Tổng:
                        {{ number_format($item->product_sales_quantity * $item->product_price, 0, ',', '.') }}₫
                    </p>
                </div>

                <div class="mt-3 mt-md-0 ms-auto text-end">
                    <a href="{{ URL::to('/home/pages/product/detail-product/' . $item->slug_product) }}"
                        class="btn btn-sm btn-outline-primary me-2">Mua lại</a>
                    <a href="{{ URL::to('/home/pages/product/detail-product/' . $item->slug_product) }}"
                        class="btn btn-sm btn-outline-success">Bình luận</a>
                </div>
            </div>
        </div>
    @endforeach


    {{-- Ghi chú đơn hàng nếu có --}}
    @if (!empty($order->order_note))
        <div class="alert alert-secondary mt-4">
            <strong>Ghi chú đơn hàng:</strong> {{ $order_info->order_note }}
        </div>
    @endif

    {{-- Tổng kết --}}
    <div class="d-flex justify-content-between border-top pt-3 mt-3 fw-bold">
        <span>Tổng thanh toán:</span>
        <span class="text-danger">{{ number_format($order_info->order_total, 0, ',', '.') }}₫</span>
    </div>
@endsection
