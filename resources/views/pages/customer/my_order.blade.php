@extends('pages.customer.information')

@section('profile_content')
    <h4 class="mb-4"><strong>ĐƠN HÀNG CỦA TÔI</strong></h4>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <ul class="nav nav-tabs mb-3" id="orderStatusTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#allOrders" role="tab">Tất cả</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#pending" role="tab">Chờ xác nhận</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#confirmed" role="tab">Đã xác nhận</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#shipping" role="tab">Đang giao</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#delivered" role="tab">Đã giao</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#cancelled" role="tab">Đã hủy</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#return" role="tab">Đơn hoàn trả</a>
        </li>
    </ul>

    <div class="tab-content" id="orderTabContent">
        <div class="tab-pane fade show active" id="allOrders" role="tabpanel">
            @forelse($orders as $order)
                <div class="border p-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Mã đơn: <strong>#{{ $order->order_code }}</strong></span>
                        @switch($order->order_status)
                            @case('Đã xác nhận')
                                <span class="badge bg-info text-dark">{{ $order->order_status }}</span>
                            @break

                            @case('Đang giao hàng')
                                <span class="badge bg-primary">{{ $order->order_status }}</span>
                            @break

                            @case('Đã giao hàng')
                                <span class="badge bg-success">{{ $order->order_status }}</span>
                            @break

                            @case('Đã huỷ')
                                <span class="badge bg-warning">{{ $order->order_status }}</span>
                            @break

                            @case('Đang chờ xử lý')
                                <span class="badge bg-secondary">{{ $order->order_status }}</span>
                            @break

                            @default
                                <span class="badge bg-danger">{{ $order->order_status }}</span>
                        @endswitch
                    </div>
                    @foreach ($order->orderDetails as $item)
                        <div class="d-flex mb-2">
                            <img src="{{ $item->image }}" width="80" height="80" class="me-3" alt="">
                            <div class="flex-grow-1">
                                <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-2">
                        <span><strong>Tổng tiền:</strong></span>
                        <span class="text-danger fw-bold">{{ number_format($order->order_total, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="text-end mt-3">
                        @if ($order->order_status == 'Đang chờ xử lý')
                            <a href="{{ URL::to('/home/account/info/my-order/cancel-order/' . $order->order_code) }}"
                                class="btn btn-outline-danger btn-sm">Hủy đơn</a>
                        @elseif($order->order_status == 'Đang giao hàng')
                            <a href="{{ url('/home/account/info/my-order/confirm/' . $order->order_code) }}"
                                class="btn btn-outline-success btn-sm">Đã nhận</a>
                        @elseif($order->order_status == 'Đã giao hàng')
                            <a href="{{ url('/home/account/info/my-order/return/' . $order->order_code) }}"
                                class="btn btn-outline-danger btn-sm">Trả hàng</a>
                        @elseif($order->order_status == 'Hoàn trả')
                            <a href="{{ URL::to('/home/account/info/my-order/return-items/' . $order->order_code) }}"
                                class="btn btn-outline-info btn-sm">Xem</a>
                        @endif
                        <a href="{{ URL::to('/home/account/info/my-order/order-details/' . $order->order_code) }}"
                            class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                    </div>
                </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
            </div>

            <div class="tab-pane fade show" id="pending" role="tabpanel">
                @forelse($pendingOrders as $order)
                    <div class="border p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã đơn: <strong>#{{ $order->order_code }}</strong></span>
                            <span class="badge bg-secondary">{{ $order->order_status }}</span>
                        </div>
                        @foreach ($order->orderDetails as $item)
                            <div class="d-flex mb-2">
                                <img src="{{ $item->image }}" width="80" height="80" class="me-3" alt="">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                    <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                    <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between mt-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span class="text-danger fw-bold">{{ number_format($order->order_total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="text-end mt-3">
                            @if ($order->order_status == 'Đang chờ xử lý')
                                <a href="{{ URL::to('/home/account/info/my-order/cancel-order/' . $order->order_code) }}"
                                    class="btn btn-outline-danger btn-sm">Hủy đơn</a>
                                <a href="{{ URL::to('/home/account/info/my-order/order-details/' . $order->order_code) }}"
                                    class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $pendingOrders->links('pagination::bootstrap-5') }}</div>
            </div>

            <div class="tab-pane fade show" id="confirmed" role="tabpanel">
                @forelse($confirmedOrders as $order)
                    <div class="border p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã đơn: <strong>#{{ $order->order_code }}</strong></span>
                            <span class="badge bg-info text-dark">{{ $order->order_status }}</span>
                        </div>
                        @foreach ($order->orderDetails as $item)
                            <div class="d-flex mb-2">
                                <img src="{{ $item->image }}" width="80" height="80"
                                    class="me-3" alt="">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                    <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                    <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between mt-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span class="text-danger fw-bold">{{ number_format($order->order_total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ URL::to('/home/account/info/my-order/order-details/' . $order->order_code) }}"
                                class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $confirmedOrders->links('pagination::bootstrap-5') }}</div>
            </div>

            <div class="tab-pane fade show" id="shipping" role="tabpanel">
                @forelse($shippingOrders as $order)
                    <div class="border p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã đơn: <strong>#{{ $order->order_code }}</strong></span>
                            <span class="badge bg-primary">{{ $order->order_status }}</span>
                        </div>
                        @foreach ($order->orderDetails as $item)
                            <div class="d-flex mb-2">
                                <img src="{{ $item->image }}" width="80" height="80"
                                    class="me-3" alt="">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                    <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                    <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between mt-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span class="text-danger fw-bold">{{ number_format($order->order_total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ URL::to('/home/account/info/my-order/order-details/' . $order->order_code) }}"
                                class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                            <a href="{{ url('/home/account/info/my-order/confirm/' . $order->order_code) }}"
                                class="btn btn-outline-success btn-sm">Đã nhận</a>
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $shippingOrders->links('pagination::bootstrap-5') }}</div>
            </div>

            <div class="tab-pane fade show" id="delivered" role="tabpanel">
                @forelse($deliveredOrders as $order)
                    <div class="border p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã đơn: <strong>#{{ $order->order_code }}</strong></span>
                            <span class="badge bg-success">{{ $order->order_status }}</span>
                        </div>
                        @foreach ($order->orderDetails as $item)
                            <div class="d-flex mb-2">
                                <img src="{{ $item->image }}" width="80" height="80"
                                    class="me-3" alt="">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                    <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                    <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between mt-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span class="text-danger fw-bold">{{ number_format($order->order_total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="text-end mt-3">
                            @if ($order->order_status == 'Đã giao hàng')
                                <a href="{{ URL::to('/home/account/info/my-order/order-details/' . $order->order_code) }}"
                                    class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                                <a href="{{ url('/home/account/info/my-order/return/' . $order->order_code) }}"
                                    class="btn btn-outline-danger btn-sm">Trả hàng</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $deliveredOrders->links('pagination::bootstrap-5') }}</div>
            </div>

            <div class="tab-pane fade show" id="cancelled" role="tabpanel">
                @forelse($cancelledOrders as $order)
                    <div class="border p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã đơn: <strong>#{{ $order->order_code }}</strong></span>
                            <span class="badge bg-warning">{{ $order->order_status }}</span>
                        </div>
                        @foreach ($order->orderDetails as $item)
                            <div class="d-flex mb-2">
                                <img src="{{ $item->image }}" width="80" height="80"
                                    class="me-3" alt="">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                    <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                    <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between mt-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span class="text-danger fw-bold">{{ number_format($order->order_total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ URL::to('/home/account/info/my-order/order-details/' . $order->order_code) }}"
                                class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $cancelledOrders->links('pagination::bootstrap-5') }}</div>
            </div>

            <div class="tab-pane fade show" id="return" role="tabpanel">
                @forelse($returnOrders as $order)
                    <div class="border p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Mã đơn: <strong>#{{ $order->order->order_code }}</strong></span>
                            <span class="badge bg-danger">Hoàn trả</span>
                        </div>
                        @foreach ($order->order->orderDetails as $item)
                            <div class="d-flex mb-2">
                                <img src="{{ $item->image }}" width="80" height="80"
                                    class="me-3" alt="">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                                    <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                                    <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between mt-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span
                                class="text-danger fw-bold">{{ number_format($order->order->order_total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ URL::to('/home/account/info/my-order/return-items/' . $order->order->order_code) }}"
                                class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                @empty
                    <p>Bạn chưa có đơn hàng nào.</p>
                @endforelse

                <div class="mb-3">{{ $cancelledOrders->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
        </div>
    @endsection
