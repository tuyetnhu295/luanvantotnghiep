
@extends('admin_layout')
@section('admin_content')
    @if (Session::has('message') || Session::has('error'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div class="toast align-items-center text-white {{ Session::has('message') ? 'bg-success' : 'bg-danger' }} border-0 shadow"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ Session::get('message') ?? Session::get('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toastEl = document.querySelector('.toast');
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl, {
                        delay: 5000
                    });
                    toast.show();
                }
            });
        </script>
    @endif
<div class="container mt-2">
    <h3 class="mb-3">Thông tin cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ url('/admin/edit-info/'.$admin->admin_id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Họ và tên</label>
            <input type="text" name="name" value="{{ $admin->admin_name }}" class="form-control" id="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" value="{{ $admin->admin_email }}" class="form-control" id="email" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" value="{{ $admin->admin_phone }}" class="form-control" id="phone" required>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
    </form>
</div>

@endsection
