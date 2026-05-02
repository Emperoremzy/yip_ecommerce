@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-body">
                <h1 class="h4 mb-3">Register</h1>
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <button class="btn btn-primary w-100">Create Account</button>
                </form>
            </div>
        </div>
    </div>
@endsection
