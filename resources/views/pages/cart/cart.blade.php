@extends('layout')
@section('content')
    <div class="container cart-container mt-4">
        <div class="row">
            <!-- Giỏ hàng -->
            <div class="col-lg-8 mb-4" style="background-color: white;">
                <h5>
                    Giỏ hàng:
                    <b style="float:right; text-decoration: underline; text-underline-offset: 4px;">
                        {{ Cart::count() }} Sản phẩm
                    </b>
                </h5>
                <br><br><br>
                <?php
                $content = Cart::content();
                ?>
                <!-- Sản phẩm trong giỏ -->
                @if (Cart::count() == 0)
                    <p style="text-align: center;font-size:20px;">Giỏ hàng của bạn đang trống. Mời bạn mua thêm sản phẩm <a
                            href="" style="text-decoration: none;font-weight: bold;color:black;">tại đây</a></p>
                @endif

                @foreach ($content as $v_content)
                    <div class="cart-item border-bottom pb-3 d-flex align-items-center">
                        <input type="checkbox" name="selected_products[]" value="{{ $v_content->rowId }}"
                            style="margin-right: 15px;">

                        <img src="{{ URL::to('/uploads/products/' . $v_content->options->image) }}"
                            alt="{{ $v_content->name }}"
                            style="width: 100px; height: 130px; object-fit: cover; margin-right: 15px;">

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $v_content->name }}</strong><br>
                                    <small><strong>Mã:</strong>
                                        {{ $v_content->options->product_code ?? 'Null' }}</small><br>
                                    <small><strong>Size:</strong> {{ $v_content->options->size ?? 'Chưa chọn' }}</small><br>
                                    <small><strong>Màu:</strong> {{ $v_content->options->color ?? 'Chưa chọn' }}</small>
                                </div>
                                <div class="remove-item">
                                    <a href="{{ URL::to('/home/pages/cart/delete-cart/' . $v_content->rowId) }}"
                                        style="color: black">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                <div class="qty-box">
                                    <form action="{{ URL::to('/home/pages/cart/update-cart/') }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="rowId" value="{{ $v_content->rowId }}">
                                        <input type="hidden" name="id" value="{{ $v_content->id }}">
                                        <button type="submit" name="action" value="decrease">-</button>

                                        <input type="text" name="quantity" class="form-control text-center mx-1"
                                            style="width: 50px; border:0;" value="{{ $v_content->qty }}" readonly>

                                        <button type="submit" name="action" value="increase">+</button>
                                    </form>
                                </div>
                                @php
                                    $subtotal = $v_content->price * $v_content->qty;
                                @endphp
                                <div><strong>{{ number_format($subtotal * 1.1, 0, ',', '.') }}₫</strong></div>
                            </div>
                        </div>
                    </div>
                @endforeach


                <br>
                <div class="d-flex align-items-center gap-2 mt-2">
                    {{-- <button id="btnSelectAll" type="button"
                        class="btn btn-outline-success btn-sm d-flex align-items-center px-3 py-2"
                        style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-check me-2"></i> Chọn tất cả
                    </button> --}}

                    <form action="{{ URL::to('/home/pages/cart/deleteAll') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center px-3 py-2 m-3"
                            style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-trash-alt me-2"></i> Xoá tất cả
                        </button>
                    </form>
                </div>



            </div>

            <!-- Thông tin đơn hàng -->
            <div class="col-lg-4">
                <h5>Thông tin đơn hàng</h5>
                @php
                    $subtotal = Cart::subtotal(0, ',', '');
                    $count = Cart::count();
                    $subtotal = (float) str_replace(',', '', $subtotal);;
                    $total_discount = 0;

                    if ($count > 5) {
                        $total_discount = $subtotal * 0.1;
                    }
                    $total = $subtotal-$total_discount;
                @endphp
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Tạm tính</span>
                        <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Giảm giá</span>
                        <strong>{{ number_format($total_discount, 0, ',', '.') }}₫</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Tổng tiền</span>
                        <strong>{{ number_format($total, 0, ',', '.') }}₫</strong>
                    </li>
                </ul>

                <!-- Ước tính giao hàng -->
                <div class="mb-3">
                    <label style="font-size: 15px;font-weight: bold;">
                        <i class="fa fa-car" aria-hidden="true"></i> Ước tính thời gian giao hàng
                    </label>

                    <div class="d-flex gap-2">
                        <select class="form-select" id="tinh_tp">
                            <option>Chọn tỉnh/thành phố</option>
                        </select>
                        <select class="form-select" id="quan_huyen">
                            <option>Chọn quận/huyện</option>
                        </select>
                    </div>
                    <div id="estimate-time" class="text-muted" style="font-size: 14px;">
                        Vui lòng chọn tỉnh và quận để xem thời gian giao hàng.
                    </div>
                </div>

                <!-- Ghi chú + mã khuyến mãi -->
                <form action="{{ URL::to('/home/save-note-coupon') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control mb-2" placeholder="Ghi chú" name="shipping_note">
                        @if (session('error_coupon'))
                            <div
                                style="color: red; margin-bottom: 10px; background-color: rgb(226, 189, 189); height:40px;align-content: center;">
                                {{ session('error_coupon') }}
                            </div>
                        @endif
                        <input type="text" class="form-control"
                            placeholder="Nhập mã khuyến mãi (nếu có)"name="coupon_code">
                    </div>
                    @if (session('customer_id'))
                        <button type="submit" class="btn btn-checkout">THANH TOÁN NGAY</button>
                    @else
                        <a class="btn btn-checkout" href="{{ URL::to('/home/account/login') }}" style="color: white;">THANH
                            TOÁN NGAY</a>
                    @endif
                </form>

                <div class="text-center mt-2">
                    <a href="#">↩ Tiếp tục mua hàng</a>
                </div>
            </div>
        </div>

        <div class="container py-5 text-center items-center">
            <h3 class="mb-4">Bạn sẽ cần</h3>
            <div id="relatedCarousel" class="carousel slide mx-auto" data-bs-ride="carousel" style="max-width: 100%;">
                <div class="carousel-inner">
                    @foreach ($product_relate->chunk(4) as $chunkIndex => $productChunk)
                        <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                            <div class="row justify-content-center g-4">
                                @foreach ($productChunk as $related)
                                    <div class="col-12 col-sm-8 col-md-6 col-lg-3">
                                        <div class="products-card related-product-card position-relative border rounded shadow-sm"
                                            style="width: 100%; overflow: hidden; padding: 10px; background: #fff;">
                                            <a href="{{ URL::to('/home/pages/product/detail-product/' . $related->slug_product) }}"
                                                style="text-decoration: none">
                                                <div class="position-relative product-image-container">
                                                    <img src="{{ asset('/uploads/products/' . $related->product_image) }}"
                                                        alt="{{ $related->product_name }}" class="w-100"
                                                        style="height: 220px; object-fit: cover;">

                                                    <!-- Nút overlay -->
                                                    <div class="button-overlay d-flex gap-2">
                                                        <form action="" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $related->product_id }}">
                                                            <button type="submit"
                                                                class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="bi bi-cart"></i>
                                                            </button>
                                                        </form>
                                                        <form action="" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $related->product_id }}">
                                                            <button type="submit"
                                                                class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                <i class="bi bi-heart"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="p-2 text-center">
                                                    <p class="product-name mb-1" style="font-size: 14px;">
                                                        {{ $related->product_name }}</p>
                                                    <div class="d-flex justify-content-center align-items-center gap-1"
                                                        style="font-size: 14px;">
                                                        <span class="fw-bold text-danger">
                                                            {{ number_format($related->product_price, 0, ',', '.') }}₫
                                                        </span>
                                                        <small class="text-muted">
                                                            <del>{{ number_format($related->product_price * 1.1, 0, ',', '.') }}₫</del>
                                                        </small>
                                                        <span class="badge bg-danger" style="font-size: 10px;">-10%</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev " type="button" data-bs-target="#relatedCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Trước</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#relatedCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Tiếp</span>
                </button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('btnSelectAll').addEventListener('click', function() {
            document.querySelectorAll('input[name="selected_products[]"]').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.qty-box').forEach(function(box) {
                const decreaseBtn = box.querySelector('.decreaseBtn');
                const increaseBtn = box.querySelector('.increaseBtn');
                const quantityInput = box.querySelector('.quantityInput');

                decreaseBtn.addEventListener('click', function() {
                    let current = parseInt(quantityInput.value);
                    if (current > 1) quantityInput.value = current - 1;
                });

                increaseBtn.addEventListener('click', function() {
                    let current = parseInt(quantityInput.value);
                    quantityInput.value = current + 1;
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            console.log("Cart page script loaded...");

            // Load danh sách tỉnh/thành phố
            $.get('/home/cart/dia-chi/tinh')
                .done(function(data) {
                    if (Array.isArray(data)) {
                        data.forEach(function(item) {
                            $('#tinh_tp').append(`<option value="${item.code}">${item.name}</option>`);
                        });
                    } else {
                        console.error("Dữ liệu tỉnh không đúng định dạng:", data);
                    }
                })
                .fail(function() {
                    alert("Không thể tải danh sách tỉnh/thành phố.");
                });

            // Khi người dùng chọn tỉnh → load quận/huyện
            $('#tinh_tp').change(function() {
                const tinhCode = $(this).val();
                $('#quan_huyen').html('<option>Đang tải...</option>');
                $('#estimate-time').text('Đang tính toán thời gian giao hàng...');

                $.get(`/home/cart/dia-chi/quan/${tinhCode}`)
                    .done(function(data) {
                        $('#quan_huyen').html('<option>Chọn quận/huyện</option>');
                        if (Array.isArray(data)) {
                            data.forEach(function(item) {
                                $('#quan_huyen').append(
                                    `<option value="${item.code}">${item.name}</option>`);
                            });
                        } else {
                            console.error("Dữ liệu quận/huyện không đúng định dạng:", data);
                        }
                    })
                    .fail(function() {
                        alert("Không thể tải danh sách quận/huyện.");
                    });
            });

            // Cập nhật ước tính khi chọn quận
            $('#quan_huyen').change(function() {
                updateEstimateTime();
            });

            function getDateRange(daysFrom, daysTo) {
                const today = new Date();
                const start = new Date(today);
                const end = new Date(today);

                start.setDate(today.getDate() + daysFrom);
                end.setDate(today.getDate() + daysTo);

                const format = (d) => d.toLocaleDateString('vi-VN');

                return `${format(start)} - ${format(end)}`;
            }

            function updateEstimateTime() {
                const city = $('#tinh_tp option:selected').text();
                const district = $('#quan_huyen option:selected').text();

                if (city !== 'Chọn tỉnh/thành phố' && district !== 'Chọn quận/huyện') {
                    let range = getDateRange(2, 4);
                    if (city.includes('Hồ Chí Minh') || city.includes('Hà Nội')) {
                        range = getDateRange(1, 2);
                    }

                    $('#estimate-time').text(`Dự kiến giao hàng: ${range}`);
                } else {
                    $('#estimate-time').text('Vui lòng chọn tỉnh và quận để xem thời gian giao hàng.');
                }
            }
        });
    </script>
@endsection
