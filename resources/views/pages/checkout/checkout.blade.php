@extends('layout')
@section('content')
    <style>
        .breadcrumb {
            margin: 20px 0;
            font-size: 14px;
            text-align: center;
        }

        .breadcrumb a {
            text-decoration: none;
            color: steelblue;
        }

        .checkout-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        .checkout-container {
            display: flex;
            gap: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1140px;
        }

        .checkout-form {
            flex: 2;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background-color: #fff;
        }

        .payment-methods label {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ccc;
            padding: 10px 14px;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .payment-methods input[type="radio"] {
            margin-top: 0;
        }

        .payment-methods img {
            height: 20px;
        }

        .summary-box img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 10px;
        }

        .summary-box .d-flex {
            align-items: center;
        }

        .summary-box p {
            margin: 0;
        }

        .coupon button {
            width: 200px;
            font-size: 20px;
            height: 50px;
            margin-left: 10px;
            text-align: center;
            align-content: center;
            background-color: rgba(8, 8, 251, 0.592);
            color: white;
        }

        .coupon button:hover {
            background-color: rgba(8, 8, 251, 0.812);
        }

        .shipping-methods label {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ccc;
            padding: 10px 14px;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .shipping-methods input[type="radio"] {
            margin-top: 0;
        }
    </style>
    <div class="breadcrumb">
        <a href="{{ URL::to('/home/pages/cart/cart') }}">Giỏ hàng</a> &nbsp;/&nbsp; Thông tin giao hàng
    </div>
    <?php
    $message = Session::get('error');
    if ($message) {
        echo '<p style="color:red;">' . $message . '</p>';
        Session::get('message', null);
    }
    ?>
    <div class="checkout-wrapper">
        <div class="checkout-container">
            <div class="checkout-form">
                <div class="row align-items-center mb-3">
                    <!-- Tiêu đề và lựa chọn nằm cùng dòng -->
                    <div class="col-md-6">
                        <h5 class="mb-0">Thông tin giao hàng</h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="address_chosse d-inline-flex gap-3">
                            <label class="mb-0">
                                <input type="radio" name="address_option" value="default" onclick="selectAddress()"
                                    checked>
                                <span>Mặc định</span>
                            </label>
                            <label class="mb-0">
                                <input type="radio" name="address_option" value="custom" onclick="selectAddress()">
                                <span>Chỉnh sửa</span>
                            </label>
                        </div>
                    </div>
                </div>

                <form action="{{ URL::to('/home/save-checkout-customer') }}" method="post">
                    @csrf
                    <input type="hidden" class="form-control" style="margin-bottom: 15px;"
                        value="{{ session('cart_note') }}" name="shipping_note">

                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" style="margin-bottom: 15px;" placeholder="Số điện thoại"
                        name="shipping_phone" value="{{ $customer->customer_phone }}" id="phone">

                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" style="margin-bottom: 15px;" placeholder="Họ và tên"
                        name="shipping_name" value="{{ $customer->customer_name }}" id="name">

                    <label for="name" class="form-label">Email</label>
                    <input type="text" class="form-control" style="margin-bottom: 15px;" placeholder="Email"
                        value="{{ session('customer_email') }}" name="shipping_email" id="email">

                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" style="margin-bottom: 15px;" placeholder="Địa chỉ cụ thể"
                        name="shipping_address" value="{{ $customer->address }}" id="address">
                    <div class="row">
                        <div class="col">
                            <select class="form-select" style="margin-bottom: 15px;" name="shipping_city" id="tinh">
                                <option>Chọn tỉnh / thành</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" style="margin-bottom: 15px;" name="shipping_district"
                                id="quan">
                                <option>Chọn quận / huyện</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" style="margin-bottom: 15px;" name="shipping_ward" id="xa">
                                <option>Chọn phường / xã</option>
                            </select>
                        </div>
                    </div>

                    <h5>Phương thức vận chuyển</h5>
                    <div class="shipping-methods" style="display: none;">
                        <label>
                            <input type="radio" name="shipping_method" value="internal" onclick="selectShipping()">
                            <span>Giao hàng tiêu chuẩn</span>
                        </label>
                        <label>
                            <input type="radio" name="shipping_method" value="fast" onclick="selectShipping()">
                            <span>Giao hàng nhanh</span>
                        </label>
                    </div>


                    <div class="shipping-placeholder">
                        <p>Vui lòng chọn tỉnh / thành để hiển thị phương thức vận chuyển.</p>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ URL::to('/home/pages/cart/cart') }}" class="btn btn-outline-secondary">Giỏ hàng</a>
                        <button type="submit" class="btn btn-primary">Hoàn tất đơn hàng</button>
                    </div>
                </form>
            </div>
            <?php
            $content = Cart::content();
            ?>
            <!-- Right Side: Order Summary -->
            <div class="summary-box">
                <h5>Đơn hàng</h5>
                @foreach ($content as $v_content)
                    <div class="d-flex mb-3">
                        <img src="{{ URL::to('/uploads/products/' . $v_content->options->image) }}"
                            alt="{{ $v_content->name }}">
                        <div>
                            <p><strong>{{ $v_content->name }}</strong></p>
                            <p>Mã: {{ $v_content->options->product_code }}</p>
                            <p>Số lượng: {{ $v_content->qty }} cái</p>
                            <p>Phân loại: {{ $v_content->options->color }} / {{ $v_content->options->size }}</p>
                            <p>Giá: <strong>{{ number_format($v_content->price, 0, ',', '.') }}₫</strong></p>
                        </div>
                    </div>
                @endforeach

                <!-- Mã giảm giá -->
                <form method="POST" action="{{ URL::to('/home/checkouts/apply-coupon') }}">
                    @csrf
                    <div class="coupon d-flex mb-3">
                        <input type="text" name="coupon_code" placeholder="Mã giảm giá" class="form-control">
                        <button type="submit" style="font-size: 15px;height:40px;width:140px;"
                            class="btn btn-outline-secondary">Sử dụng</button>
                    </div>
                    @if (session('error_coupon'))
                        <div
                            style="color: red; margin-bottom: 10px; background-color: rgb(226, 189, 189); height:40px;align-content: center;">
                            {{ session('error_coupon') }}
                        </div>
                    @elseif (session('success_coupon'))
                        <div
                            style="color: rgb(9, 255, 9); margin-bottom: 10px; background-color: rgb(205, 244, 211); height:40px;align-content: center;">
                            {{ session('error_coupon') }}
                        </div>
                    @endif
                    <a href="#" style="font-size: 14px; color: #2f8ef4; text-decoration: underline;">Xem thêm mã
                        giảm
                        giá</a>
                    <div class="d-flex flex-wrap gap-2 mt-2 mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm">Giảm 100,000₫</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm">Giảm 70,000₫</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm">Giảm 30,000₫</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm">Giảm 10%</button>
                    </div>
                </form>

                <!-- Tạm tính -->
                @php
                    $subtotal = Cart::subtotal(0, ',', '');
                    $count = Cart::count();
                    $subtotal = (float) str_replace(',', '', $subtotal);
                    $coupon = Session::get('coupon');
                    $total_coupon = 0;
                    $total_discount = 0;
                    if (is_array($coupon) && isset($coupon['discount_type'], $coupon['discount'])) {
                        if ($coupon['discount_type'] == 'percentage') {
                            $total_coupon = ($subtotal * $coupon['discount']) / 100;
                        } elseif ($coupon['discount_type'] == 'fixed') {
                            $total_coupon = $coupon['discount'];
                        }
                    }

                    if ($count > 5) {
                        $total_discount = $subtotal * 0.1;
                    }
                    $total = $subtotal - $total_discount - $total_coupon;
                @endphp


                <div class="border-top pt-3">
                    <p>Tạm tính: <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong></p>
                    @if ($total_coupon > 0)
                        <p>
                            Mã giảm giá:
                            <strong>
                                @if ($coupon['discount_type'] == 'percentage')
                                    {{ $coupon['discount'] }}%
                                @else
                                    {{ number_format($coupon['discount'], 0, ',', '.') }}₫
                                @endif
                            </strong>
                        </p>
                        <p>
                            @if ($coupon['discount_type'] == 'percentage')
                                <p>Tổng tiền giảm:<strong
                                        style="color:green;">{{ number_format($total_coupon, 0, ',', '.') }}₫ </strong>
                                </p>
                            @else
                                <p>Tổng tiền giảm:<strong
                                        style="color:green;">{{ number_format($total_coupon, 0, ',', '.') }}₫ </strong>
                                </p>
                            @endif
                        </p>
                    @else
                        <p>
                            Mã giảm giá: <strong style="color:green;">0₫</strong>
                        </p>
                    @endif

                    <hr>
                    <p><strong>Tổng cộng: <span
                                style="font-size: 18px; color: red;">{{ number_format($total, 0, ',', '.') }}₫</span></strong>
                    </p>
                    <input type="hidden" name="total" value="{{ $total }}">
                    <input type="hidden" name="total_coupon" value="{{ $total_coupon }}"">
                </div>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function checkAddressFilled() {
            const city = $('#tinh').val();
            const district = $('#quan').val();
            const ward = $('#xa').val();

            if (city && district && ward) {
                $('.shipping-methods').show();
                $('.shipping-placeholder').hide();
            } else {
                $('.shipping-methods').hide();
                $('.shipping-placeholder').show();
            }
        }

        $(document).ready(function() {
            const selectedCity = "{{ $customer->city }}";
            const selectedDistrict = "{{ $customer->district }}";
            const selectedWard = "{{ $customer->ward }}";

            // Load tỉnh
            $.get('/home/checkouts/dia-chi/tinh')
                .done(function(data) {
                    if (Array.isArray(data)) {
                        data.forEach(function(item) {
                            const selected = (item.code == selectedCity) ? 'selected' : '';
                            $('#tinh').append(
                                `<option value="${item.code}" ${selected}>${item.name}</option>`
                            );
                        });

                        if (selectedCity) {
                            $('#tinh').val(selectedCity).trigger('change');

                            // Tiếp tục load quận sau delay
                            setTimeout(function() {
                                $.get(`/home/checkouts/dia-chi/quan/${selectedCity}`)
                                    .done(function(dataQuan) {
                                        $('#quan').html('<option>Chọn quận / huyện</option>');
                                        if (Array.isArray(dataQuan)) {
                                            dataQuan.forEach(function(item) {
                                                const selected = (item.code ==
                                                    selectedDistrict) ? 'selected' : '';
                                                $('#quan').append(
                                                    `<option value="${item.code}" ${selected}>${item.name}</option>`
                                                );
                                            });

                                            if (selectedDistrict) {
                                                $('#quan').val(selectedDistrict).trigger('change');

                                                // Load xã sau delay tiếp theo
                                                setTimeout(function() {
                                                    $.get(
                                                            `/home/checkouts/dia-chi/xa/${selectedDistrict}`
                                                        )
                                                        .done(function(dataXa) {
                                                            $('#xa').html(
                                                                '<option>Chọn phường / xã</option>'
                                                            );
                                                            if (Array.isArray(dataXa)) {
                                                                dataXa.forEach(function(
                                                                    item) {
                                                                    const
                                                                        selected =
                                                                        (item
                                                                            .code ==
                                                                            selectedWard
                                                                        ) ?
                                                                        'selected' :
                                                                        '';
                                                                    $('#xa')
                                                                        .append(
                                                                            `<option value="${item.code}" ${selected}>${item.name}</option>`
                                                                        );
                                                                });

                                                                if (selectedWard) {
                                                                    $('#xa').val(
                                                                        selectedWard
                                                                    );
                                                                    checkAddressFilled
                                                                        ();
                                                                }
                                                            }
                                                        });
                                                }, 300);
                                            }
                                        }
                                    });
                            }, 300);
                        }
                    }
                })
                .fail(function() {
                    alert("Không thể tải danh sách tỉnh/thành.");
                });

            // Khi chọn tỉnh
            $('#tinh').change(function() {
                const tinhCode = $(this).val();
                if (!tinhCode) return;

                $('#quan').html('<option>Đang tải...</option>');
                $('#xa').html('<option>Chọn phường / xã</option>');

                $.get(`/home/checkouts/dia-chi/quan/${tinhCode}`)
                    .done(function(data) {
                        $('#quan').html('<option>Chọn quận / huyện</option>');
                        if (Array.isArray(data)) {
                            data.forEach(function(item) {
                                $('#quan').append(
                                    `<option value="${item.code}">${item.name}</option>`
                                );
                            });
                        }
                        checkAddressFilled();
                    });
            });

            // Khi chọn huyện
            $('#quan').change(function() {
                const quanCode = $(this).val();
                if (!quanCode) return;

                $('#xa').html('<option>Đang tải...</option>');

                $.get(`/home/checkouts/dia-chi/xa/${quanCode}`)
                    .done(function(data) {
                        $('#xa').html('<option>Chọn phường / xã</option>');
                        if (Array.isArray(data)) {
                            data.forEach(function(item) {
                                $('#xa').append(
                                    `<option value="${item.code}">${item.name}</option>`
                                );
                            });
                        }
                        checkAddressFilled();
                    });
            });

            // Khi chọn xã
            $('#xa').change(function() {
                checkAddressFilled();
            });
        });


        function selectAddress() {
            const selected = $('input[name="address_option"]:checked').val();
            if (selected == 'custom') {
                $('#tinh').html('<option value="">Chọn tỉnh / thành phố</option>');
                $('#quan').html('<option value="">Chọn quận / huyện</option>');
                $('#xa').html('<option value="">Chọn phường / xã</option>');

                $('#phone').val('');
                $('#name').val('');
                $('#email').val('');
                $('#address').val('');

                $('.shipping-methods').hide();
                $('.shipping-placeholder').show();
            } else {

                $('#phone').val('{{ $customer->customer_phone }}');
                $('#name').val('{{ $customer->customer_name }}');
                $('#email').val('{{ $customer->customer_email }}');
                $('#address').val('{{ $customer->address }}');

                const selectedCity = "{{ $customer->city }}";
                const selectedDistrict = "{{ $customer->district }}";
                const selectedWard = "{{ $customer->ward }}";

                // Load tỉnh
                $.get('/home/checkouts/dia-chi/tinh')
                    .done(function(data) {
                        if (Array.isArray(data)) {
                            data.forEach(function(item) {
                                const selected = (item.code == selectedCity) ? 'selected' : '';
                                $('#tinh').append(
                                    `<option value="${item.code}" ${selected}>${item.name}</option>`
                                );
                            });

                            if (selectedCity) {
                                $('#tinh').val(selectedCity).trigger('change');

                                // Tiếp tục load quận sau delay
                                setTimeout(function() {
                                    $.get(`/home/checkouts/dia-chi/quan/${selectedCity}`)
                                        .done(function(dataQuan) {
                                            $('#quan').html('<option>Chọn quận / huyện</option>');
                                            if (Array.isArray(dataQuan)) {
                                                dataQuan.forEach(function(item) {
                                                    const selected = (item.code ==
                                                        selectedDistrict) ? 'selected' : '';
                                                    $('#quan').append(
                                                        `<option value="${item.code}" ${selected}>${item.name}</option>`
                                                    );
                                                });

                                                if (selectedDistrict) {
                                                    $('#quan').val(selectedDistrict).trigger('change');

                                                    // Load xã sau delay tiếp theo
                                                    setTimeout(function() {
                                                        $.get(
                                                                `/home/checkouts/dia-chi/xa/${selectedDistrict}`
                                                            )
                                                            .done(function(dataXa) {
                                                                $('#xa').html(
                                                                    '<option>Chọn phường / xã</option>'
                                                                );
                                                                if (Array.isArray(dataXa)) {
                                                                    dataXa.forEach(function(
                                                                        item) {
                                                                        const
                                                                            selected =
                                                                            (item
                                                                                .code ==
                                                                                selectedWard
                                                                            ) ?
                                                                            'selected' :
                                                                            '';
                                                                        $('#xa')
                                                                            .append(
                                                                                `<option value="${item.code}" ${selected}>${item.name}</option>`
                                                                            );
                                                                    });

                                                                    if (selectedWard) {
                                                                        $('#xa').val(
                                                                            selectedWard
                                                                        );
                                                                        checkAddressFilled
                                                                            ();
                                                                    }
                                                                }
                                                            });
                                                    }, 300);
                                                }
                                            }
                                        });
                                }, 300);
                            }
                        }
                    })
                    .fail(function() {
                        alert("Không thể tải danh sách tỉnh/thành.");
                    });
            }
        }
    </script>
@endsection
