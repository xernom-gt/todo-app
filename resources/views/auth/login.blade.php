@extends('layout')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card p-4">
            <h3 class="text-center mb-3">Login</h3>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Masuk</button>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}">Belum punya akun? Daftar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection