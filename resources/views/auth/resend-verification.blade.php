@extends('auth.layouts.app')

@section('title', $page_title)

@section('content')
    <div class="login-box">
        <div class="login-logo" style="width: 370px">
            <b>Resend Verification Email</b>
        </div>
        <div class="card-body login-box-body">
            <p class="text-muted mb-3">Enter the email address you used to register. We&apos;ll send you a new verification link.</p>
            @if (Session::has('error'))
                <p class="alert alert-danger">{{ Session::get('error') }}</p>
            @endif
            <form method="POST" action="{{ route('resend-verification.send') }}">
                @csrf
                <div class="form-group has-feedback">
                    <label for="email">{{ __('Email Address') }}</label>
                    <input class="form-control" placeholder="Email address" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="row mb-0">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100">Send Verification Email</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-0"><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
@endsection
