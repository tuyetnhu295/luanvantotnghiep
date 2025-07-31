@extends('pages.customer.information')

@section('profile_content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4 class="mb-4 text-primary">
                <strong>📦 Yêu cầu trả hàng cho đơn #{{ $order->order_code }}</strong>
                <a href="{{ URL::to('/home/account/info/my-order') }}"
                    style="float:right;text-decoration:none;color:black;font-size:20px;">← Quay lại</a>
            </h4>

            <form action="{{ URL::to('/home/account/info/my-order/submit-return/'. $order->order_code) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">📄 Lý do trả hàng</label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do cụ thể..." required></textarea>
                </div>

                <h5 class="text-dark mb-3">🛍️ Chọn sản phẩm cần trả</h5>

                @foreach ($order->orderDetails as $item)
                    <div class="card border mb-3">
                        <div class="row g-0">
                            <div class="col-md-2 text-center d-flex align-items-center justify-content-center p-2">
                                <img src="{{ $item->image }}" class="img-fluid rounded"
                                    alt="{{ $item->product_name }}">
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $item->product_name }}</h6>
                                    <p class="mb-1 text-muted">Màu: {{ $item->product_color }} | Size:
                                        {{ $item->product_size }}</p>
                                    <p class="mb-2">Số lượng đã mua: <strong>{{ $item->product_sales_quantity }}</strong>
                                    </p>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="returnProduct{{ $item->product_id }}"
                                            name="products[{{ $item->product_id }}][selected]" value="1">
                                        <label class="form-check-label" for="returnProduct{{ $item->product_id }}">
                                            Chọn sản phẩm trả
                                        </label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">🔢 Số lượng muốn trả</label>
                                            <input type="number" name="products[{{ $item->product_id }}][quantity]"
                                                min="0" max="{{ $item->product_sales_quantity }}"
                                                class="form-control" placeholder="Ví dụ: 1">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">📦 Tình trạng hàng hóa</label>
                                            <input type="text" name="products[{{ $item->product_id }}][condition]"
                                                class="form-control" placeholder="Chưa mở, lỗi kỹ thuật...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-send-check me-1"></i> Gửi yêu cầu trả hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
