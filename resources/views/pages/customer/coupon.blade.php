@extends('pages.customer.information')

@section('profile_content')

    {{-- Thông báo Toast --}}
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
            document.addEventListener('DOMContentLoaded', function () {
                const toastEl = document.querySelector('.toast');
                if (toastEl) {
                    new bootstrap.Toast(toastEl, {
                        delay: 10000
                    }).show();
                }
            });
        </script>
    @endif

    {{-- Nội dung trang --}}
    <div class="container py-4">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">🎁 Mã giảm giá</h2>
            </div>
        </div>

        <div class="row g-4">
            @if ($coupons->count() > 0)
                @foreach ($coupons as $coupon)
                    @php
                        $today = \Carbon\Carbon::today();
                        $valid = $today->between($coupon->start_date, $coupon->end_date);
                        $isReturning = isset($order);
                        $isEligible = in_array($coupon->customer_type, ['all', $isReturning ? 'returning' : 'new']);
                    @endphp

                    @if ($valid && $isEligible)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="products-card-item position-relative border rounded shadow-sm"
                                style="width: 100%; overflow: hidden;">
                                <div class="voucher-card-item">
                                    <div class="voucher-left-item">{{ $coupon->coupon_code }}</div>
                                    <div class="voucher-right-item">
                                        <h5>
                                            @if ($coupon->discount_type == 'percentage')
                                                Giảm {{ $coupon->discount_value }}%
                                            @else
                                                Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}₫
                                            @endif
                                        </h5>
                                        <p>Đơn tối thiểu: {{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</p>
                                        <small>HSD: {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</small><br>
                                        <small>Đối tượng:
                                            @if ($coupon->customer_type == 'new')
                                                Khách mới
                                            @elseif ($coupon->customer_type == 'returning')
                                                Khách thân thiết
                                            @else
                                                Mọi khách hàng
                                            @endif
                                        </small><br>
                                        <button type="button" class="copy-btn-item" data-code="{{ $coupon->coupon_code }}">
                                            Lưu mã
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center">Không tìm thấy mã giảm giá nào.</div>
            @endif
        </div>

        {{-- Phân trang --}}
        <div class="text-center mt-4">
            {{ $coupons->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- CSS --}}
   <style>
    .voucher-card-item {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        height: auto;
        transition: all 0.3s ease;
        min-height: 90px;
    }

    .voucher-left-item {
        font-size: 16px;
        font-weight: bold;
        background-color: #dc3545;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        min-width: 100px;
        text-align: center;
        margin-right: 16px;
    }

    .voucher-right-item {
        flex: 1;
    }

    .voucher-right-item h5 {
        margin: 0 0 4px;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .voucher-right-item p,
    .voucher-right-item small {
        margin: 0;
        font-size: 13px;
        color: #555;
    }

    .copy-btn-item {
        margin-top: 6px;
        padding: 5px 10px;
        font-size: 12px;
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 5px;
        transition: 0.3s;
    }

    .copy-btn-item:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .products-card-item {
        padding: 10px;
    }
</style>


    {{-- Copy mã --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-btn-item').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const code = this.dataset.code;
                    navigator.clipboard.writeText(code).then(() => {
                        alert('✅ Đã sao chép mã: ' + code);
                    });
                });
            });
        });
    </script>

@endsection
