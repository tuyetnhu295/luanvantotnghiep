@extends('pages.customer.information')

@section('profile_content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4 class="mb-4 text-danger"><strong>🗑️ Yêu cầu xóa tài khoản</strong></h4>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="alert alert-warning">
                <strong>Lưu ý:</strong> Việc xóa tài khoản là vĩnh viễn và không thể khôi phục. Mọi đơn hàng, lịch sử mua
                sắm và thông tin cá nhân sẽ bị xóa.
                <ul>
                    <li>Bạn sẽ không thể đăng nhập hoặc khôi phục tài khoản sau khi xóa.</li>
                    <li>Hệ thống không xử lý nếu bạn còn đơn hàng chưa hoàn tất hoặc tranh chấp.</li>
                    <li>Một số dữ liệu có thể được lưu lại theo chính sách và quy định pháp luật.</li>
                    <li>Việc xóa tài khoản không xóa bỏ trách nhiệm phát sinh trước đó.</li>
                </ul>
            </div>

            <form action="{{ URL::to('/home/account/info/help/delete-request') }}" method="POST"
                onsubmit="return confirm('Bạn chắc chắn muốn xóa tài khoản?');">
                @csrf

                <button type="submit" class="btn btn-danger mt-3">✅ Tôi hiểu và muốn tiếp tục</button>
                <a href="{{ URL::to('/home/account/info') }}" class="btn btn-secondary mt-3 ms-2">❌ Hủy bỏ</a>
            </form>
        </div>
    </div>
@endsection
