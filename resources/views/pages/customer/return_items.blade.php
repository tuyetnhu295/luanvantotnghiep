@extends('pages.customer.information')

@section('profile_content')
    <h4 class="mb-4">
        <strong>CHI TIẾT TRẢ HÀNG CỦA ĐƠN HÀNG #{{ $order->order_code }}</strong>
    </h4>

    {{-- Thông tin đơn hàng --}}
    <div class="border rounded p-3 mb-4 bg-light">
        <p><strong>Ngày đặt hàng:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</p>
        <p><strong>Trạng thái đơn hàng:</strong>
            <span class="badge bg-info text-dark">{{ ucfirst($order->order_status) }}</span>
        </p>
        <p><strong>Tổng tiền:</strong> {{ number_format($order->order_total, 0, ',', '.') }}₫</p>
    </div>

    {{-- Thông tin trả hàng --}}
    @if ($return)
        <div class="border rounded p-3 mb-4 bg-warning-subtle">
            <p><strong>Ngày yêu cầu trả:</strong> {{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</p>
            <p><strong>Tổng số lượng sản phẩm trả về:</strong> {{ $return->quantity }}</p>
            <p><strong>Lý do trả hàng:</strong> {{ $return->reason }}</p>
            <p><strong>Trạng thái xử lý:</strong>
                @if ($return->status == 'pending')
                    <span class="badge bg-warning text-dark">Đang chờ xử lý</span>
                @elseif($return->status == 'approved')
                    <span class="badge bg-success">Đã chấp nhận</span>
                @elseif($return->status == 'rejected')
                    <span class="badge bg-danger">Từ chối</span>
                @else
                    <span class="badge bg-danger">Đang xử lý</span>
                @endif
            </p>
        </div>
    @else
        <div class="alert alert-info">
            Đơn hàng này chưa có yêu cầu hoàn trả.
        </div>
    @endif

    <h5 class="mb-3">Sản phẩm đã đặt:</h5>
    @foreach ($order->orderDetails as $item)
        <div class="d-flex align-items-start mb-3 p-2 border rounded">
            <img src="{{ $item->image }}" alt="{{ $item->product_name }}" class="me-3" width="80" height="80">
            <div>
                <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                <p class="mb-1">Số lượng: {{ $item->product_sales_quantity }}</p>
                <p class="mb-0">Giá: {{ number_format($item->product_price, 0, ',', '.') }}₫</p>
            </div>
        </div>
    @endforeach


    <h5 class="mb-3">Sản phẩm hoàn trả:</h5>
    @foreach ($return_item as $item)
        <div class="d-flex align-items-start mb-3 p-2 border rounded">
            <img src="{{ URL::to('/uploads/products/'. $item->product_image) }}" alt="{{ $item->product_name }}" class="me-3" width="80" height="80">
            <div>
                <p class="mb-1"><strong>{{ $item->product_name }}</strong></p>
                <p class="mb-1">Số lượng: {{ $item->quantity }}</p>
                <p class="mb-1">Lý do: {{ $item->condition }}</p>
            </div>
        </div>
    @endforeach
@endsection
