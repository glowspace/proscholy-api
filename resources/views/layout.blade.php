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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/solid.css"
          integrity="sha384-wnAC7ln+XN0UKdcPvJvtqIH3jOjs9pnKnq9qX68ImXvOGz2JuFoEiCjT8jyZQX2z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/regular.css"
          integrity="sha384-zkhEzh7td0PG30vxQk1D9liRKeizzot4eqkJ8gB3/I+mZ1rjgQk+BSt2F6rT2c+I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css"
          integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/brands.css"
          integrity="sha384-nT8r1Kzllf71iZl81CdFzObMsaLOhqBU1JD2+XoAALbdtWaXDOlWOZTR4v1ktjPE" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
</head>
<body>
    <div class="">
        <div class="container" style="text-align: center">
            <a href="{{route('home')}}" style="color: #484848;">
                <h1>Zpěvník <span style="color: #085371c2">Pro</span>Scholy.cz</h1>
            </a>

            @yield('content')

            <p style="margin-top: 30px">
                <i style="font-size: 14px;" class="fas fa-heart"></i>
                <br>S láskou
                <br><a href="{{route('home')}}">ProScholy.cz</a>
            </p>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
