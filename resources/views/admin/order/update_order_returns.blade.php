@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2>Cập nhật đơn hàng hoàn trả #{{ $returns->return_code }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ URL::to('/admin/order/manage-order-returns/update-order-returns/' . $returns->return_code) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái đơn hàng hoàn trả</label>
                <select name="status" id="status" class="form-select">
                    @foreach ($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ $returns->status == $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('order_status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ url('/admin/order/manage-order-returns') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
