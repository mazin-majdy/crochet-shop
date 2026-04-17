@extends('layouts.master2')
@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex " style="background-color: #f9faf4">
                <div class="row wd-100p mx-auto text-center" style="background-color: #f9faf4">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                        <img src="{{ URL::asset('assets/logo.png') }}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto"
                            alt="logo">
                    </div>
                </div>
            </div>
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="mb-5 d-flex"> <a href="{{ url('/' . ($page = 'index')) }}"><img
                                                src="{{ URL::asset('assets/favicon.png') }}" class="sign-favicon ht-40"
                                                alt="logo"></a>
                                        <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">{{ config('app.name') }}</h1>
                                    </div>
                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h2>أهلا بعودتك</h2>
                                            <h5 class="font-weight-semibold mb-4">قم بتسجيل الدخول </h5>
                                            <form action="{{ route('login') }}" method="POST">
                                                @csrf

                                                <div>
                                                    <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                                                    <x-text-input id="email" class="form-control" type="email"
                                                        name="email" :value="old('email')" required autofocus
                                                        autocomplete="username" />
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                                <!-- Password -->
                                                <div class="my-2">
                                                    <x-input-label for="password" :value="__('كلمة المرور')" />

                                                    <x-text-input id="password" class="form-control" type="password"
                                                        name="password" required autocomplete="current-password" />

                                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                </div>

                                                <button class="btn btn-main-primary btn-block">تسجيل الدخول</button>

                                            </form>
                                            <div class="main-signin-footer mt-5">

                                                @if (Route::has('password.request'))
                                                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                                        href="{{ route('password.request') }}">
                                                        {{ __('هل نسيت كلمة المرور؟') }}
                                                    </a>
                                                @endif

                                                <p>ليس لديك حساب؟ <a href="{{ url('/' . ($page = 'signup')) }}">قم بإنشاء
                                                        حساب</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>
@endsection
@section('js')
@endsection
