@extends('admin_layout')
@section('admin_content')
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mt-3" style="background-color: rgb(122, 199, 160)">
        <div class="container-fluid">
            <h1 style="color: rgb(67, 36, 36);">Dashboard</h1>
        </div>
    </nav>

    <!-- Cards thống kê -->
    <div class="row mt-3" style="background-color: rgb(235, 239, 237)">
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng Sản Phẩm</h5>
                    <p class="card-text">
                        {{ $product }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng Đơn Hàng</h5>
                    <p class="card-text">{{ $order }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Khách Hàng</h5>
                    <p class="card-text">{{ $customer }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Doanh Thu</h5>
                    <p class="card-text">{{ number_format($money, 0, ',', '.') }}₫</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row" style="background-color: rgb(235, 250, 242)">
        <div class="col-lg-6 col-md-12 mb-3">
            <canvas id="ordersChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    </div>

    <!-- Scripts -->
    <script>
        const orderLabels = @json($orderLabels);
        const orderData = @json($orderData);
        const revenueData = @json($revenueData);


        // Biểu đồ đơn hàng
        new Chart(document.getElementById('ordersChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: orderLabels,
                datasets: [{
                    label: 'Đơn hàng',
                    data: orderData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Biểu đồ doanh thu theo tháng/năm
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: orderLabels,
                datasets: [{
                    label: 'Doanh thu ($)',
                    data: revenueData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }]
            }
        });
    </script>
@endsection
