@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2>Cập nhật đơn hàng #{{ $order->order_id }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ URL::to('/admin/order/manage-order/update-order/' . $order->order_id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="order_status" class="form-label">Trạng thái đơn hàng</label>
                <select name="order_status" id="order_status" class="form-select">
                    @foreach ($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ $order->order_status == $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('order_status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="delivery_id" class="form-label">Người giao hàng</label>
                <select name="delivery_id" id="delivery_id" class="form-select">
                    <option value="">-- Chọn người giao --</option>
                    @foreach ($shippers as $shipper)
                        <option value="{{ $shipper->admin_id }}"
                            {{ $order->delivery_id == $shipper->admin_id ? 'selected' : '' }}>
                            {{ $shipper->admin_name }}
                        </option>
                    @endforeach
                </select>
                @error('delivery_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ url('/admin/order/manage-order') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
