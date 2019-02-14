<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @if(isset($page_title))
            {{$page_title}} - křesťanský zpěvník - ProScholy.cz
        @else
            Křesťanský zpěvník - ProScholy.cz
        @endif
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Fonts awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/solid.css"
          integrity="sha384-wnAC7ln+XN0UKdcPvJvtqIH3jOjs9pnKnq9qX68ImXvOGz2JuFoEiCjT8jyZQX2z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/regular.css"
          integrity="sha384-zkhEzh7td0PG30vxQk1D9liRKeizzot4eqkJ8gB3/I+mZ1rjgQk+BSt2F6rT2c+I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css"
          integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/brands.css"
          integrity="sha384-nT8r1Kzllf71iZl81CdFzObMsaLOhqBU1JD2+XoAALbdtWaXDOlWOZTR4v1ktjPE" crossorigin="anonymous">

    {{-- CSS --}}
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark static-top">

        <img src="{{asset('img/logo_v2.png')}}" width="60" style="padding: 0 10px 0 0">
        <a class="navbar-brand mr-1" href="/"> Zpěvník pro scholy</a>

    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar bg-dark"></div>
            <div class="col-md-10 background-home">

            </div>
        </div>
    </div>

    @yield('scripts')

    {{-- Main JS built with Laravel's mix --}}
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</body>
</html>
