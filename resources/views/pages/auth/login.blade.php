@extends('layouts.customs.auth')

@php $configData = Helper::applClasses(); @endphp

@section('left-text')
<div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
    <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
        @if ($configData['theme'] === 'dark')
        <img class="img-fluid" src="{{ asset('images/pages/login-v2-dark.svg') }}" alt="Login V2" />
        @else
        <img class="img-fluid" src="{{ asset('images/pages/login-v2.svg') }}" alt="Login V2" />
        @endif
    </div>
</div>
@stop

@section('auth-content')
<!-- Login-->
<div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
    <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
        <h2 class="card-title fw-bold mb-1">Welcome to Vuexy! 👋</h2>
        <x-alert for="auth" type="danger" dismiss="true"></x-alert>
        <x-form class="auth-login-form mt-2" action="{{ route('login.process') }}">
            {{-- Email --}}
            <div class="mb-1">
                <label class="form-label" for="email">Email</label>
                <x-input class="form-control" id="email" type="text" name="email" placeholder="john@example.com"
                    aria-describedby="email" autofocus="" tabindex="1" value="{{ old('email', '') }}" />
                <x-input-error for="email"></x-input-error>
            </div>
            {{-- /Email --}}

            {{-- Password --}}
            <div class="mb-1">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="{{ url('auth/forgot-password-cover') }}">
                        <small>Forgot Password?</small>
                    </a>
                </div>
                <x-input-group class="form-password-toggle" for="password">
                    <x-input class="form-control form-control-merge" id="password" type="password" name="password"
                        placeholder="············" aria-describedby="password" tabindex="2"
                        value="{{ old('password', '') }}" />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </x-input-group>
                <x-input-error for="password"></x-input-error>
            </div>
            {{-- /Password --}}

            {{-- Remember --}}
            <div class="mb-1">
                <div class="form-check">
                    <x-input class="form-check-input" id="remember" name="remember" type="checkbox" tabindex="3"
                        :is-checked="old('remember', false)" />
                    <label class="form-check-label" for="remember"> Remember Me</label>
                </div>
            </div>
            {{-- /Remember --}}

            <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
        </x-form>
        <p class="text-center mt-2">
            <span>New on our platform?</span>
            <a href="{{ route('register.form') }}"><span>&nbsp;Create an account</span></a>
        </p>
    </div>
</div>
<!-- /Login-->
@stop
