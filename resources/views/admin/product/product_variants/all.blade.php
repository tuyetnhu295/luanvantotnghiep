@extends('admin_layout')
@section('admin_content')
    <div class="container mt-4">
        <h2 class="mb-4">Biến thể sản phẩm</h2>
        <?php
        $message = Session::get('message');
        if ($message) {
            echo '<p style="color:green;">' . $message . '</p>';
            Session::forget('message', null);
        }
        ?>

        <p class="text-muted">Bạn có thể tìm kiếm theo tên sản phẩm hoặc ngày thêm.</p>

        <div class="table-responsive">
            <table id="categoryTable" class="table table-striped table-bordered align-middle ">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Tên kích thước</th>
                        <th scope="col">Tên màu</th>
                        <th scope="col">Số lượng tồn</th>
                        <th scope="col">Hiển thị</th>
                        <th scope="col">Ngày thêm</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_variant as $key => $value)
                        <tr>
                            <td>{{ $value->variants_id }}</td>
                            <td>
                                @foreach ($product as $val)
                                    @if ($val->product_id == $value->product_id)
                                        {{ $val->product_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($size as $val)
                                    @if ($val->size_id == $value->size_id)
                                        {{ $val->size_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($color as $val)
                                    @if ($val->color_id == $value->color_id)
                                        {{ $val->color_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{$value->stock}}</td>
                            <td>
                                <?php
                                if ($value->status == 0) {
                                ?>
                                <a
                                    href="{{ URL::to('/admin/product/product-variants/all-product-variant/unactive-variant/' . $value->variants_id) }}">
                                    <span class="fa-thumb-styling fa fa-thumbs-up"
                                        style="font-size: 18px;color:blue;"></span>
                                </a>
                                <?php
                                    } else {
                                ?>
                                <a
                                    href="{{ URL::to('/admin/product/product-variants/all-product-variant/active-variant/' . $value->variants_id) }}">
                                    <span class="fa fa-thumbs-down" style="font-size: 18px;color:red;"></span>
                                </a>
                                <?php
                                }
                                ?>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align:
                                        center">
                                <a href="{{ URL::to('/admin/product/product-variants/all-product-variant/edit-variant/' . $value->variants_id) }}"
                                    class="text-primary me-2"><i class="bi bi-pencil-square"></i></a>
                                <a onclick="return confirm('Bạn có chắc là muốn xóa biến thể sản phẩm này không ?')"
                                    href="{{ URL::to('/admin/product/product-variants/all-product-variant/delete-variant/' . $value->variants_id) }}"
                                    class="text-danger"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        div.dataTables_filter {
            margin-bottom: 1rem;
            /* Tạo khoảng cách giữa ô tìm kiếm và bảng */
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                language: {
                    search: "🔍 Tìm kiếm danh mục:",
                    lengthMenu: "Hiển thị _MENU_ dòng",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                    paginate: {
                        previous: "Trước",
                        next: "Sau"
                    },
                    zeroRecords: "Không tìm thấy dữ liệu phù hợp",
                    infoEmpty: "Không có dữ liệu",
                    infoFiltered: "(lọc từ _MAX_ mục)"
                }
            });
        });
    </script>
@endsection
