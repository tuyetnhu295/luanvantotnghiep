<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/css/styles.css') }}">
</head>

<body>
    @if (Session::has('success'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div class="toast align-items-center text-white bg-success border-0 show shadow" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ Session::get('success') }}
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-card text-center">
                    <h3 class="mb-4">🔑 Admin Login</h3>
                    <form action="{{ URL::to('/admin/dashboard') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="admin_email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3 position-relative">
                            <input type="password" name="admin_password" class="form-control" placeholder="Mật khẩu"
                                id="passwordField" required>
                            <button type="button" onclick="togglePassword()"
                                class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2">👁</button>
                        </div>
                        <div class="mb-3 form-check text-start">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>

                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger p-2">{{ $errors->first() }}</div>
                        @endif
                        <button type="submit" class="btn btn-login w-100">Đăng nhập</button>
                    </form>
                    <p class="mt-3">
                        <a href="{{ url('/admin/forget-password') }}" class="text-muted">Quên mật khẩu?</a>
                    </p>
                    <?php
                    $message = Session::get('message');
                    if ($message) {
                        echo '<p style="color:red;">' . $message . '</p>';
                        Session::put('message', null);
                    }
                    ?>

                    <p class="mt-3">
                        <a href="{{ url('/admin/register') }}" class="btn btn-register w-80 text-muted">Đăng ký</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const field = document.getElementById('passwordField');
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
