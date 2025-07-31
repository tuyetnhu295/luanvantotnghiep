@extends('layout')

@section('content')
    @if (session('error'))
        <div style="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <form method="POST" action="{{ route('vnpay.create') }}" class="border p-4 rounded shadow-sm bg-light"
            style="width: 500px;">
            @csrf
            <h3 class="mb-4 text-center text-primary">Thanh toán qua VNPAY</h3>

            <div class="mb-3">
                <label class="form-label fw-semibold">Số tiền (VNĐ):</label>
                <input type="text" class="form-control" value="{{ number_format($order->order_total, 0, ',', '.') }} VNĐ"
                    readonly>
                <input type="hidden" name="amount" value="{{ $order->order_total }}">
                <input type="hidden" name="order_code" value="{{ $order_code }}">
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">Thanh toán ngay</button>
        </form>

    </div>
@endsection
