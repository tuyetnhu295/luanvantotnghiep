<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/css/styles.css') }}">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="register-card text-center">
                    <h3 class="mb-4">🔑 Admin Register</h3>

                    <form action="{{ URL::to('/admin/register/add-employee') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="admin_email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="admin_name" class="form-control" placeholder="Họ và tên"
                                required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="admin_phone" class="form-control" placeholder="Số điện thoại"
                                required>
                        </div>
                        <button type="submit" class="btn btn-register w-100">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
