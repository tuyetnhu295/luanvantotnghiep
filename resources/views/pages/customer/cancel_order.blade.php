@extends('pages.customer.information')

@section('profile_content')
    <h4 class="mb-4"><strong>XÁC NHẬN HỦY ĐƠN HÀNG #{{ $order->order_code }}</strong></h4>

    <div class="card">
        <div class="card-body">
            <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ url('/home/account/info/my-order') }}" class="btn btn-secondary">Quay lại</a>

                <form action="{{ url('/home/account/info/my-order/cancel-order/submit/' . $order->order_code) }}"
                    method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Xác nhận hủy đơn</button>
                </form>
            </div>
        </div>
    </div>
@endsection
