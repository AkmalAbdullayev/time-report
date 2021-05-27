@extends('layouts.app')
@section('content')
    <!-- login page start -->
    <section id="auth-login" class="row flexbox-container">
        <div class="col-xl-8 col-11">
            <div class="card bg-authentication mb-0">
                <div class="row m-0">
                    <!-- left section-login -->
                    <div class="col-md-6 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="text-center mb-2">Авторизация</h4>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('login') }}" method="POST" autocomplete="off">
                                        @csrf
                                        <div class="form-group mb-50">
                                            <label class="text-bold-600" for="exampleInputEmail1">Email</label>
                                            <input type="email" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="exampleInputEmail1"
                                                   placeholder="Введите ваш email"
                                                   value=""
                                                   required autofocus
                                            >
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="text-bold-600" for="exampleInputPassword1">Пароль</label>
                                            <input type="password" name="password" value=""
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="exampleInputPassword1" placeholder="Введите ваш пароль" required>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div
                                            class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                            <div class="text-left">
                                                <div class="checkbox checkbox-sm">
                                                    <input type="checkbox" name="remember" class="form-check-input"
                                                           id="exampleCheck1"
                                                           {{ old('remember') ? 'checked' : '' }} checked>
                                                    <label class="checkboxsmall"
                                                           for="exampleCheck1"><small>Запомнить</small></label>
                                                </div>
                                            </div>
                                            <!-- <div class="text-right"><a href="auth-forgot-password.html" class="card-link"><small>Забыли пароль?</small></a></div> -->
                                        </div>

{{--                                        <div class="form-group">--}}
{{--                                            <input type="hidden" name="recaptcha" id="recaptcha">--}}
{{--                                        </div>--}}

                                        <button type="submit" class="btn btn-primary glow w-100 position-relative">
                                            Отправить<i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- right section image -->
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                        <div class="card-content">
                            <img class="img-fluid" src="{{ asset('app-assets/images/pages/login.png') }}"
                                 alt="branding logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

{{--    <script src="https://www.google.com/recaptcha/api.js?render={{ env("RECAPTCHA_SITE_KEY") }}"></script>--}}
{{--    <script>--}}
{{--        grecaptcha.ready(function () {--}}
{{--            grecaptcha.execute(`{{ env("RECAPTCHA_SITE_KEY") }}`, {action: 'login'}).then(function (token) {--}}
{{--                if (token) {--}}
{{--                    document.getElementById("recaptcha").value = token;--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
    <!-- login page ends -->
@endsection
