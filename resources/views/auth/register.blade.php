@extends('layout')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card p-4">
            <h3 class="text-center mb-3">Register</h3>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button class="btn btn-success w-100">Daftar</button>
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection