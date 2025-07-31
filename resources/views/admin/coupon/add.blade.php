@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 700px; margin: 0 auto;">
        <h4 class="mb-4 text-center">➕ Thêm Mã Khuyến Mãi</h4>

        @if (Session::has('message'))
            <p style="color:green;">{{ Session::get('message') }}</p>
            {{ Session::forget('message') }}
        @endif

        <form action="{{ URL::to('/admin/coupon/save-coupon') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="coupon_code" class="form-label">Mã khuyến mãi</label>
                <div class="input-group">
                    <input type="text" name="coupon_code" class="form-control" id="coupon_code"
                        placeholder="Nhập mã hoặc bấm để sinh tự động">
                    <button class="btn btn-outline-secondary" type="button" onclick="generateCoupon()">Tạo mã tự
                        động</button>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" id="description" rows="3"
                    placeholder="Mô tả mã giảm giá (tuỳ chọn)"></textarea>
            </div>

            <div class="mb-3">
                <label for="discount_type" class="form-label">Loại Giảm Giá</label>
                <select name="discount_type" id="discount_type" class="form-select" required>
                    <option value="percentage">Theo phần trăm (%)</option>
                    <option value="fixed">Theo số tiền (VND)</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="discount_value" class="form-label">Giá Trị Giảm Giá</label>
                <input type="number" name="discount_value" class="form-control" id="discount_value" min="1"
                    placeholder="Nhập giá trị giảm giá" required>

            </div>

            <div class="mb-3">
                <label for="min_order_value" class="form-label">Giá Trị Đơn Hàng Tối Thiểu</label>
                <input type="number" step="0.01" name="min_order_value" class="form-control" id="min_order_value"
                    placeholder="Nhập giá trị đơn hàng tối thiểu" value="0" required>
            </div>

            <div class="mb-3">
                <label for="customer_type" class="form-label">Loại Khách Hàng Áp Dụng</label>
                <select name="customer_type" id="customer_type" class="form-select" required>
                    <option value="all">Tất cả khách hàng</option>
                    <option value="new">Khách hàng mới</option>
                    <option value="returning">Khách hàng cũ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Ngày Bắt Đầu</label>
                <input type="date" name="start_date" class="form-control" id="start_date" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Ngày Kết Thúc</label>
                <input type="date" name="end_date" class="form-control" id="end_date" required>
            </div>

            <div class="mb-3">
                <label for="usage_limit" class="form-label">Số Lần Sử Dụng Tối Đa</label>
                <input type="number" name="usage_limit" class="form-control" id="usage_limit" min="1" value="1"
                    required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng Thái Mã Giảm Giá</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="active">Hoạt Động</option>
                    <option value="inactive">Không Hoạt Động</option>
                    <option value="expired">Hết Hạn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100" name="save_coupon">💾 Lưu Mã Giảm Giá</button>
        </form>
    </div>
    <script>
        function generateCoupon(length = 8) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = 'NQ';
            for (let i = 0; i < length; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('coupon_code').value = code;
        }
        document.getElementById('discount_type').addEventListener('change', function() {
            const unit = this.value === 'percentage' ? '%' : 'VNĐ';
            document.getElementById('discount_unit').textContent = unit;
        });
    </script>
@endsection
