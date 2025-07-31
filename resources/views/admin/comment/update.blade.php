@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <h2>Cập nhật bình luận #{{ $comment->review_id }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ URL::to('/admin/comments/update-comment/' . $comment->review_id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select">
                @foreach ($statusOptions as $key => $label)
                    <option value="{{ $key }}" {{ $comment->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ url('/admin/comments') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

@endsection
