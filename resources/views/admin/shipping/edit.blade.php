@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2>Cập nhật đơn hàng #{{ $order->order_id }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ URL::to('/admin/delivery/orders/update-order/' . $order->order_id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="order_status" class="form-label">Trạng thái đơn hàng</label>
                <select name="order_status" id="order_status" class="form-select">
                        <option value="Đã giao hàng">Đã giao hàng
                </select>
                @error('order_status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
             <div class="mb-3">
                <label for="payment_status" class="form-label">Trạng thái đơn hàng</label>
                <select name="payment_status" id="payment_status" class="form-select">
                        <option value="success">Thành công
                </select>
                @error('payment_status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ url('/admin/delivery/orders/') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
