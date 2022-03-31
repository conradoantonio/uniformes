<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', isset($title) ? $title .' | '.env('APP_NAME') : env('APP_NAME'))</title>
<script src="{{ asset('vendor/jquery/jquery.min.js')}}"></script>
<link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}"/>
<link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png" sizes="16x16">
<link rel="stylesheet" href="{{ asset('vendor/pace/pace.css') }}">
<script src="{{ asset('vendor/pace/pace.min.j') }}s"></script>
<!--vendors-->
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/jquery-scrollbar/jquery.scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/timepicker/bootstrap-timepicker.min.css') }}">
<link href="https://fonts.googleapis.com/css?family=Hind+Vadodara:400,500,600" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('fonts/jost/jost.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<!--Material Icons-->
<link rel="stylesheet" type="text/css" href="{{ asset('fonts/materialdesignicons/materialdesignicons.min.css') }}">
<!--Bootstrap + atmos Admin CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/atmos.min.css') }}">

</head>
<body class="jumbo-page">

<main class="admin-main  bg-pattern">
    <div class="container">
        <div class="row m-h-100 ">
            <div class="col-md-8 col-lg-4  m-auto">
                <div class="card shadow-lg p-t-20 p-b-20">
                    <div class="card-body text-center">
                        <img width="200" alt="image" src="{{asset('img/404.svg')}}">
                        <h1 class="display-1 fw-600 font-secondary">404</h1>
                        @if(auth()->check())
                            <div class="title">Opps, parece que lo que buscas no existe. </div>
                            @if(auth()->user()->role_id == 1)
                                <div class="title"> Regresa a nuestro dashboard. </div>
                                <a href="{{url('dashboard')}}"><button class="btn btn-primary">Menú Principal</button></a>
                            @else
                                <div class="title"> Regresar. </div>
                                <a href="{{url('mi-perfil')}}"><button class="btn btn-primary">Ver mi perfil</button></a>{{-- Cambiar por si hay un dashboard secundario --}}
                            @endif
                        @else
                            <div class="title">Parece que lo que buscas no existe. <br> Inicia sesión para navegar en nuestro panel.</div>
                            <a href="{{url('')}}"><button class="btn btn-primary">Login</button></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{ asset('vendor/popper/popper.js')}}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('vendor/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
<script src="{{ asset('vendor/listjs/listjs.min.js')}}"></script>
<script src="{{ asset('vendor/moment/moment.min.js')}}"></script>
</body>
</html>