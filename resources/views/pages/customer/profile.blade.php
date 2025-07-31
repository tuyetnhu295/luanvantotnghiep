@extends('pages.customer.information')
@section('profile_content')
    <h4 class="mb-4"><strong>HỒ SƠ CỦA TÔI</strong></h4>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
            @php
                session()->forget('message');
            @endphp
        </div>
    @endif
    <form action="{{ URL::to('/home/account/info/save-info') }}" method="POST">
        @csrf
        <div class="col">
            <div class="col-md-6">
                <label for="lastName" class="form-label">Họ và tên</label>
                <input type="text" class="form-control" id="lastName" name="customer_name"
                    value="{{ $customer->customer_name }}">
            </div>
            {{-- <div class="col-md-6">
                                    <label for="firstName" class="form-label">Tên</label>
                                    <input type="text" class="form-control" id="firstName" placeholder="Tên">
                                </div> --}}
            <div class="col-md-6 mt-3">
                <label for="dob" class="form-label">Sinh nhật</label>
                <input type="date" class="form-control" name="customer_birthday" id="dob" placeholder="dd/mm/yyyy"
                    value="{{ $customer->customer_birthday }}">
            </div>
            <div class="col-md-6 mt-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="customer_phone" id="phone" placeholder="Số điện thoại"
                    value="{{ $customer->customer_phone }}">
            </div>
            <div class="col-12 mt-4">
                <label class="form-label me-3">Giới tính:</label>
                @if ($customer->customer_sex == 0)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customer_sex" id="male" value="1">
                        <label class="form-check-label" for="male">Nam</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customer_sex" id="female" value="0"
                            checked>
                        <label class="form-check-label" for="female">Nữ</label>
                    </div>
                @elseif ($customer->customer_sex == 1)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customer_sex" id="male" value="1"
                            checked>
                        <label class="form-check-label" for="male">Nam</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customer_sex" id="female" value="0">
                        <label class="form-check-label" for="female">Nữ</label>
                    </div>
                @endif
            </div>


            <div class="col-12 mt-4">
                <!-- Tiêu đề -->
                <label class="form-label d-block mb-2">Địa chỉ:</label>

                <!-- Hàng chứa các trường -->
                <div class="row g-3">
                    <!-- Số nhà -->
                    <div class="col-md-3">
                        <label class="form-label">Số nhà:</label>
                        <textarea class="form-control" name="address" rows="1">{{ $customer->address ?? '' }}</textarea>
                    </div>

                    <!-- Xã/Phường -->
                    <div class="col-md-3">
                        <label class="form-label">Xã/Phường:</label>
                        <select class="form-select" id="xa_phuong" name="ward">
                            <option value="">Chọn Xã/Phường</option>
                        </select>
                    </div>

                    <!-- Quận/Huyện -->
                    <div class="col-md-3">
                        <label class="form-label">Quận/Huyện:</label>
                        <select class="form-select" id="quan_huyen" name="district">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>

                    <!-- Tỉnh/Thành phố -->
                    <div class="col-md-3">
                        <label class="form-label">Tỉnh/Thành phố:</label>
                        <select class="form-select" id="tinh_tp" name="city">
                            <option value="">Chọn Tỉnh/Thành phố</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <button type="submit" class="btn btn-dark mt-4 px-4">CẬP NHẬT</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const selectedCity = "{{ $customer->city }}";
            const selectedDistrict = "{{ $customer->district }}";
            const selectedWard = "{{ $customer->ward }}";

            // Load Tỉnh/Thành
            $.get('/home/dia-chi/tinh')
                .done(function(data) {
                    if (Array.isArray(data)) {
                        data.forEach(function(item) {
                            const selected = (item.code == selectedCity) ? 'selected' : '';
                            $('#tinh_tp').append(
                                `<option value="${item.code}" ${selected}>${item.name}</option>`);
                        });

                        if (selectedCity) {
                            $('#tinh_tp').val(selectedCity).trigger('change');
                        }
                    }
                });

            // Khi chọn Tỉnh => load Quận/Huyện
            $('#tinh_tp').change(function() {
                const tinhCode = $(this).val();
                if (!tinhCode) return;

                $('#quan_huyen').html('<option>Đang tải...</option>');
                $('#xa_phuong').html('<option value="">Chọn phường / xã</option>');

                $.get(`/home/dia-chi/quan/${tinhCode}`)
                    .done(function(data) {
                        $('#quan_huyen').html('<option value="">Chọn quận / huyện</option>');
                        if (Array.isArray(data)) {
                            data.forEach(function(item) {
                                const selected = (item.code == selectedDistrict) ? 'selected' :
                                    '';
                                $('#quan_huyen').append(
                                    `<option value="${item.code}" ${selected}>${item.name}</option>`
                                );
                            });

                            if (selectedDistrict) {
                                $('#quan_huyen').val(selectedDistrict).trigger('change');
                            }
                        }
                    });
            });

            // Khi chọn Quận/Huyện => load Xã/Phường
            $('#quan_huyen').change(function() {
                const quanCode = $(this).val();
                if (!quanCode) return;

                $('#xa_phuong').html('<option>Đang tải...</option>');

                $.get(`/home/dia-chi/xa/${quanCode}`)
                    .done(function(data) {
                        $('#xa_phuong').html('<option value="">Chọn phường / xã</option>');
                        if (Array.isArray(data)) {
                            data.forEach(function(item) {
                                const selected = (item.code == selectedWard) ? 'selected' : '';
                                $('#xa_phuong').append(
                                    `<option value="${item.code}" ${selected}>${item.name}</option>`
                                );
                            });

                            if (selectedWard) {
                                $('#xa_phuong').val(selectedWard);
                            }
                        }
                    });
            });
        });
    </script>
@endsection
