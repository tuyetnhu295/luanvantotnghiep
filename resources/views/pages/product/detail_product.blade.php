@extends('layout')
@section('content')
    <style>
        .comment {
            text-align: justify;
        }

        .hidden {
            display: none;
        }
    </style>

    {{-- @if (session('error'))
        <p class="alert alert-danger">{{ session('error') }}</p>
        @php
            session()->forget('error');
        @endphp
    @endif --}}
    <div class="container py-5 mt-2" style="background-color: white;">
        <div class="row justify-content-center">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-12 col-md-6 mb-4">
                <!-- Carousel sản phẩm -->
                <div id="productCarousel" class="carousel slide mx-auto" data-bs-ride="carousel">
                    <div class="carousel-inner rounded border">
                        <!-- Hình chính -->
                        <div class="carousel-item active">
                            <img src="{{ asset('/uploads/products/' . $product->product_image) }}"
                                class="d-block w-100 img-fluid" alt="Ảnh chính" style="object-fit: cover; height: 450px;">
                        </div>

                        <!-- Các hình phụ -->
                        @foreach ($images as $image)
                            <div class="carousel-item">
                                <img src="{{ asset('/uploads/products/' . $image->product_image) }}"
                                    lass="d-block w-100 img-fluid" alt="Ảnh phụ" style="object-fit: cover; height: 400px;">
                            </div>
                        @endforeach
                    </div>

                    <!-- Thumbnail điều hướng -->
                    <div class="mt-3 d-flex justify-content-center flex-wrap">
                        <!-- Thumbnail ảnh chính -->
                        <img src="{{ asset('/uploads/products/' . $product->product_image) }}" class="img-thumbnail mx-1"
                            width="60" data-bs-target="#productCarousel" data-bs-slide-to="0" style="cursor:pointer;">

                        <!-- Thumbnail ảnh phụ -->
                        @foreach ($images as $key => $image)
                            <img src="{{ asset('/uploads/products/' . $image->product_image) }}" class="img-thumbnail mx-1"
                                width="60" data-bs-target="#productCarousel" data-bs-slide-to="{{ $key + 1 }}"
                                style="cursor:pointer;">
                        @endforeach
                    </div>
                </div>


                <div class="d-flex align-content-center gap-3 text-secondary mt-4 fl justify-content-center"
                    style="font-size: 17px; margin-bottom:10px;">

                    <div class="d-flex align-items-center gap-2"
                        style="border-right: 2px solid silver; padding-right:10px;">
                        <button type="button" style="border:0px;"><i
                                class="bi bi-chat-left-text-fill text-primary"></i></button>
                        <span>Bình luận: {{ $total }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if (Session::has('error'))
                            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
                                <div class="toast align-items-center text-white bg-danger border-0 show shadow"
                                    role="alert">
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            {{ Session::get('error') }}
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                            data-bs-dismiss="toast" aria-label="Đóng"></button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const toastEl = document.querySelector('.toast');
                                    if (toastEl) {
                                        new bootstrap.Toast(toastEl, {
                                            delay: 3000
                                        }).show();
                                    }
                                });
                            </script>
                        @endif
                        <input type="hidden" value="{{ $product->product_id }}">
                        <form action="{{ url('/home/favorite/' . $product->product_id) }}" method="POST">
                            @csrf
                            <button style="border:0px;"><i class="bi bi-heart-fill text-danger"></i></button>
                        </form>
                        <span>Lượt thích ({{ $favorite }})</span>
                    </div>

                </div>

            </div>

            <!-- Thông tin sản phẩm -->

            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="mb-0">{{ $product->product_name }}</h2>
                    @php
                        $currentProductVariants = $variant->where('product_id', $product->product_id);
                        $inStock = $currentProductVariants->where('stock', '>', 0)->isNotEmpty();
                    @endphp
                    <span class="{{ $inStock ? 'text-success' : 'text-danger' }}">
                        {{ $inStock ? '✔ Còn hàng' : '✖ Hết hàng' }}
                    </span>
                </div>

                <p class="text-muted">Mã:&nbsp;{{ $product->product_code }}</p>
                <h6>Loại:&nbsp;{{ $product->subcategory_product_name }}</h6>

                <p class="text-muted">Thương hiệu:&nbsp;{{ $product->brand_product_name }}</p>
                <h4 class="text-danger">{{ number_format($product->product_price, 0, ',', '.') }}₫</h4>
                <div class="bg-light p-3 mb-3 rounded">
                    <h6>Ưu đãi:</h6>
                    <ul class="mb-0">
                        <li>Khi khách hàng có hóa đơn trên 500k thì sẽ được free ship</li>
                        <li>Khi mua hóa đơn trên 5 sản phẩm sẽ được giảm 10% tổng đơn</li>
                        <li>Nhiều phiếu giảm giá có ưu đãi hấp dẫn tùy vào loại khách hàng</li>
                    </ul>
                </div>

                <form action="{{ URL::to('/home/pages/cart/save-cart') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <strong>Màu sắc:</strong><br>
                        <div class="btn-group-lg-2" role="group" id="colorButtons">
                            @foreach (collect($variant)->groupBy('color_id') as $val)
                                @php
                                    $stock = $val->where('stock', '>', 0)->isNotEmpty();
                                    $colorName = $val->first()->color_name;
                                @endphp
                                @if ($stock)
                                    <button type="button" class="button-color mx-1" data-color="{{ $val }}">
                                        {{ $colorName }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        <input type="hidden" name="selected_color" id="selectedColor">
                    </div>

                    <div class="mb-3">
                        <strong>Kích thước:
                            <span id="selectedSizeText">Chưa chọn </span>
                        </strong><br>
                        <div class="btn-group mt-2" role="group" id="sizeButtons">
                            @foreach (collect($variant)->groupBy('size_id') as $val)
                                @php
                                    $stock = $val->where('stock', '>', 0)->isNotEmpty();
                                    $sizeName = $val->first()->size_name;
                                @endphp
                                @if ($stock)
                                    <button type="button" class="button-size mx-3" data-size="{{ $val }}">
                                        {{ $sizeName }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        <input type="hidden" name="selected_size" id="selectedSize">
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <div class="d-flex align-items-center border rounded px-2 py-1"
                            style="width: 150px; height: 38px;margin-right: 10px;">
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <button type="button"
                                class="btn btn-sm text-dark border-0 p-0 d-flex align-items-center justify-content-center"
                                id="decreaseBtn" style="width: 30px; height: 100%;font-size: 25px;"><b>-</b></button>
                            <input type="text" id="quantityInput" name="quantityInput" value="1"
                                class="form-control text-center border-0 p-0 mx-1" style="width: 60px;font-weight: bold;">
                            <button type="button"
                                class="btn btn-sm text-dark border-0 p-0 d-flex align-items-center justify-content-center"
                                id="increaseBtn" style="width: 30px; height: 100%; font-size: 25px;"><b>+</b></button>
                        </div>
                        {{-- <input name="productid_hidden" type="hidden" value="{{ $product->variants_id }}"> --}}
                        <button type="submit" class="btn-cart" style="width: 200px;margin-right: 10px;"
                            name="add_cart">Thêm
                            vào
                            giỏ</button>
                        <button type="submit" class="btn-buy-now" style="width: 200px;margin-right: 10px;"
                            name="buy_now">Mua
                            ngay</button>
                    </div>
                </form>
                <div class="d-flex justify-content-between text-center">
                    <div><img src="icon1.png" width="30"><br><small>Đổi trả 15 ngày</small></div>
                    <div><img src="icon2.png" width="30"><br><small>Freeship đơn hàng</small></div>
                    <div><img src="icon3.png" width="30"><br><small>Bảo hành 30 ngày</small></div>
                    <div><img src="icon4.png" width="30"><br><small>Giao toàn quốc</small></div>
                </div>
            </div>
        </div>

        <!-- Tabs mô tả sản phẩm -->
        <div class="mt-5">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc"
                        type="button" role="tab">Mô tả</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content"
                        type="button" role="tab">Nội dung</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                        type="button" role="tab">Chính sách giao hàng</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return"
                        type="button" role="tab">Chính sách đổi hàng</button>
                </li>
            </ul>

            <div class="tab-content p-3 border border-top-0" id="productTabContent">
                <div class="tab-pane fade show active" id="desc" role="tabpanel">
                    {!! $product->product_desc !!}
                </div>
                <div class="tab-pane fade" id="content" role="tabpanel">
                    {!! $product->product_content !!}
                </div>
                <div class="tab-pane fade" id="shipping" role="tabpanel">
                    <p>Miễn phí giao hàng cho đơn từ 500.000₫. Giao nhanh toàn quốc trong 2-4 ngày làm việc.</p>
                </div>

                <div class="tab-pane fade" id="return" role="tabpanel">
                    <p>Hỗ trợ đổi trả trong vòng 15 ngày kể từ khi nhận hàng. Sản phẩm phải còn nguyên tag và chưa qua
                        sử
                        dụng.</p>
                </div>


            </div>
        </div>
    </div>

    <div class="container py-5" id="comment">
        <h4 class="mb-4 fw-bold">Viết bình luận</h4>


        @if (Session::has('message'))
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
                <div class="toast align-items-center text-white bg-success border-0 show shadow" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ Session::get('message') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Đóng"></button>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toastEl = document.querySelector('.toast');
                    if (toastEl) {
                        new bootstrap.Toast(toastEl, {
                            delay: 3000
                        }).show();
                    }
                });
            </script>
        @endif

        {{-- Form bình luận --}}
        <form method="POST" action="{{ URL::to('/home/comments') }}" class="bg-light p-4 rounded shadow-sm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->variants_id }}">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Chọn size:</label>
                    <select name="size" id="size" class="form-select" required>
                        <option value="">-- Chọn size --</option>
                        @foreach (collect($variant)->groupBy('size_id') as $val)
                            @php
                                $stock = $val->where('stock', '>', 0)->isNotEmpty();
                                $sizeName = $val->first()->size_name;
                            @endphp
                            @if ($stock)
                                <option value="{{ $sizeName }}">{{ $sizeName }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Chọn màu:</label>
                    <select name="color" id="color" class="form-select" required>
                        <option value="">-- Chọn màu --</option>
                        @foreach (collect($variant)->groupBy('color_id') as $val)
                            @php
                                $stock = $val->where('stock', '>', 0)->isNotEmpty();
                                $colorName = $val->first()->color_name;
                            @endphp
                            @if ($stock)
                                <option value="{{ $colorName }}">{{ $colorName }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nội dung bình luận:</label>
                <textarea class="form-control" name="comment" rows="3" placeholder="Viết gì đó..." required></textarea>
            </div>

            <button type="submit" class="btn btn-dark w-100">Gửi bình luận</button>
        </form>

        <hr class="my-5">

        {{-- Danh sách bình luận --}}
        <h4 class="mb-4 fw-bold">Bình luận gần đây ({{ $total }})</h4>
        @forelse ($comment as $comments)
            <div class="border-bottom pb-3 mb-3">
                <strong>{{ $comments->customer_name ?? 'Ẩn danh' }}</strong><br>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($comments->created_at)->format('d/m/Y') }}
                    ({{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }})
                </small>
                <p class="mt-2 mb-0">{{ $comments->review_text }}</p>
            </div>
        @empty
            <p class="text-muted">Chưa có bình luận nào.</p>
        @endforelse

        <div class="mt-4">
            {{ $comment->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="container py-5 text-center items-center" style="background-color: white;">
        <h3 class="mb-4">Sản phẩm liên quan</h3>
        <div id="relatedCarousel" class="carousel slide mx-auto mt-4" data-bs-ride="carousel" style="max-width: 100%;">
            <div class="carousel-inner" style="background-color: white;">
                @foreach ($product_relate->chunk(4) as $chunkIndex => $productChunk)
                    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                        <div class="row justify-content-center g-4">
                            @foreach ($productChunk as $related)
                                <div class="col-6 col-sm-6 col-md-4 col-lg-3 px-3 mb-4">
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
                                                            class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="bi bi-heart"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="p-2 text-center">
                                                <p class="product-name mb-1" style="font-size: 14px;">
                                                    {{ $product->product_name }}</p>
                                                <div class="d-flex justify-content-center align-items-center gap-3"
                                                    style="font-size: 13px;">
                                                    <span class="fw-bold text-danger">
                                                        {{ number_format($product->product_price, 0, ',', '.') }}₫
                                                    </span>

                                                    <span class="fw-bold text-danger">
                                                        Lượt mua: {{ $product->total_sold }}
                                                    </span>

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
            <button class="carousel-control-next" type="button" data-bs-target="#relatedCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Tiếp</span>
            </button>
        </div>
    </div>

    <script>
        const variants = @json($variant);
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorButtons = document.querySelectorAll('#colorButtons button');
            const sizeButtons = document.querySelectorAll('#sizeButtons button');
            const selectedColorInput = document.getElementById('selectedColor');
            const selectedSizeInput = document.getElementById('selectedSize');
            const selectedSizeText = document.getElementById('selectedSizeText');

            let selectedColor = null;
            let selectedSize = null;

            function updateSizeOptions() {
                sizeButtons.forEach(button => {
                    const sizeName = button.textContent.trim();
                    const available = selectedColor ?
                        variants.some(v =>
                            v.color_name === selectedColor &&
                            v.size_name === sizeName &&
                            v.stock > 0
                        ) :
                        variants.some(v =>
                            v.size_name === sizeName &&
                            v.stock > 0
                        );

                    button.disabled = !available;
                    button.classList.toggle('hidden', !available);

                    // Gỡ active nếu không còn hợp lệ
                    if (!available && button.classList.contains('active')) {
                        button.classList.remove('active');
                        selectedSize = null;
                        selectedSizeInput.value = '';
                        selectedSizeText.textContent = 'Chưa chọn';
                    }
                });
            }


            function updateColorOptions() {
                colorButtons.forEach(button => {
                    const colorName = button.textContent.trim();
                    const available = selectedSize ?
                        variants.some(v =>
                            v.color_name === colorName &&
                            v.size_name === selectedSize &&
                            v.stock > 0
                        ) :
                        variants.some(v =>
                            v.color_name === colorName &&
                            v.stock > 0
                        );

                    button.disabled = !available;
                    button.classList.toggle('hidden', !available);

                    if (!available && button.classList.contains('active')) {
                        button.classList.remove('active');
                        selectedColor = null;
                        selectedColorInput.value = '';
                    }
                });
            }


            colorButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (button.disabled) return;

                    const colorName = button.textContent.trim();

                    if (button.classList.contains('active')) {
                        // Nếu đang active, click lần 2 để nhả
                        button.classList.remove('active');
                        selectedColor = null;
                        selectedColorInput.value = '';
                        updateSizeOptions();
                    } else {
                        colorButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');
                        selectedColor = colorName;
                        selectedColorInput.value = selectedColor;
                        updateSizeOptions();
                    }
                });
            });


            sizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (button.disabled) return;

                    const sizeName = button.textContent.trim();

                    if (button.classList.contains('active')) {
                        // Nếu đang active, click lần 2 để nhả
                        button.classList.remove('active');
                        selectedSize = null;
                        selectedSizeInput.value = '';
                        selectedSizeText.textContent = 'Chưa chọn';
                        updateColorOptions();
                    } else {
                        sizeButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');
                        selectedSize = sizeName;
                        selectedSizeInput.value = selectedSize;
                        selectedSizeText.textContent = selectedSize;
                        updateColorOptions();
                    }
                });
            });


            // Validate trước khi submit
            const form = document.querySelector('form[action*="/save-cart"]');
            form.addEventListener('submit', function(e) {
                const size = selectedSizeInput.value.trim();
                const color = selectedColorInput.value.trim();
                const valid = variants.some(v => v.size_name === size && v.color_name === color && v.stock >
                    0);

                if (!size || !color || !valid) {
                    e.preventDefault();
                    alert('Vui lòng chọn màu sắc và kích thước hợp lệ trước khi thêm vào giỏ hàng.');
                }
            });

            // Tăng giảm số lượng
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            const quantityInput = document.getElementById('quantityInput');

            decreaseBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) quantityInput.value = currentValue - 1;
            });

            increaseBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });
        });
    </script>
@endsection
