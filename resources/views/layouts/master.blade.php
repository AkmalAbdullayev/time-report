<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    {!! $meta ?? '' !!}
    <title>{{ $title ?? 'Главная' }} | Attendance Management</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
          rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/swiper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/alertify/css/alertify.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/alertify/css/themes/default.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/tags/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset("app-assets/vendors/css/pickers/pickadate/pickadate.css")}}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset("app-assets/vendors/css/pickers/daterange/daterangepicker.css") }}">
    <link rel="stylesheet" type="text/css" href="{{asset("app-assets/vendors/css/extensions/toastr.css")}}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/extensions/sweetalert2.min.css") }}">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    @stack('css')
    @livewireStyles
</head>
<body class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns  navbar-sticky footer-static  "
      data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="semi-dark-layout">
<!-- BEGIN: Header-->
@include('layouts.partials._header')
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
@include('layouts.partials._sidebar')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div id="app" class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @yield('content')
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0">
        <span class="float-left d-inline-block">{{ now()->format('Y') }} &copy; Attendance Management</span>
        <span class="float-right d-sm-inline-block d-none">Created by <a class="text-uppercase"
                                                                         href="https://smarttechnology.uz"
                                                                         target="_blank">Smart technology</a></span>
        <button class="btn btn-primary btn-icon scroll-top" type="button">
            <i class="bx bx-up-arrow-alt"></i>
        </button>
    </p>
</footer>
<script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/swiper.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/configs/vertical-menu-dark.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/components.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/footer.js') }}"></script>
<script src="{{asset('plugins/tags/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{asset("app-assets/vendors/js/extensions/toastr.min.js")}}"></script>
<script src="{{asset("app-assets/js/scripts/extensions/toastr.js")}}"></script>
<script src="{{asset("app-assets/vendors/js/forms/select/select2.full.js")}}"></script>
<script src="{{asset("app-assets/js/scripts/forms/select/form-select2.js")}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

<script src="{{asset("app-assets/vendors/js/pickers/pickadate/picker.js")}}"></script>
<script src="{{asset("app-assets/vendors/js/pickers/pickadate/picker.date.js")}}"></script>
<script src="{{asset("app-assets/vendors/js/pickers/pickadate/picker.time.js")}}"></script>
<script src="{{asset("app-assets/vendors/js/pickers/pickadate/legacy.js")}}"></script>
<script src="{{asset("app-assets/vendors/js/pickers/daterange/moment.min.js")}}"></script>
<script src="{{asset("app-assets/vendors/js/pickers/daterange/daterangepicker.js")}}"></script>

<script src="{{ asset("app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js") }}"></script>
<script src="{{ asset("app-assets/js/scripts/tooltip/tooltip.js") }}"></script>
<script src="{{ asset("app-assets/js/scripts/pages/bootstrap-toast.js") }}"></script>
<script src="{{ asset("plugins/alertify/alertify.min.js") }}"></script>
<script
    src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
    integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
    crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<!-- END: Theme JS-->
@livewireScripts
<script src="{{ asset('js/custom.js') }}"></script>
@stack('js')
@include('layouts.partials._alerts')
</body>
</html>
