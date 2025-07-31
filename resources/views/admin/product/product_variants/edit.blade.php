@extends('admin_layout')
@section('admin_content')
    <div class="container mt-5" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-4 text-center">✏️Cập nhật biến thể sản phẩm</h4>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>
        <form action="{{ URL::to('/admin/product/product-variants/update-variant/' . $edit_variant->variants_id) }}"
            method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <select name="product" class="form-select" required>
                    @foreach ($product as $val)
                        <option value="{{ $val->product_id }}"
                            {{ $val->product_id == $edit_variant->product_id ? 'selected' : '' }}>
                            {{ $val->product_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kích thước</label>
                <select name="size" class="form-select" required>
                    @foreach ($size as $val)
                        <option value="{{ $val->size_id }}"
                            {{ $val->size_id == $edit_variant->size_id ? 'selected' : '' }}>
                            {{ $val->size_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Màu</label>
                <select name="color" class="form-select" required>
                    @foreach ($color as $val)
                        <option value="{{ $val->color_id }}"
                            {{ $val->color_id == $edit_variant->color_id ? 'selected' : '' }}>
                            {{ $val->color_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Số lượng tồn</label>
                <input type="number" value="{{ $edit_variant->stock }}" name="stock" class="form-control"
                    id="stock" placeholder="Số lượng tồn" required>
            </div>

            <button type="submit" name="update" class="btn btn-primary w-100">💾 Cập nhật</button>
        </form>
    </div>
@endsection
