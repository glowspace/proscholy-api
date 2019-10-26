<nav class="navbar navbar-expand-lg navbar-dark justify-content-between absolute-top">
    <div class="container">
        <a class="navbar-brand" href="{{url("") }}"><img src="{{asset('img/logo_v2.png')}}" style="padding: 0 10px 0 0;" width="60" alt="logo">Zpěvník pro scholy</a>
        @if (Auth::check())
            <a class="navbar-text px-3 user-logged-in-btn" href="{{route('admin.dashboard')}}">
                <i class="fas fa-user"></i> {{ Auth::user()->name }}
            @if (Auth::user()->roles()->count() > 0)
                ({{Auth::user()->roles()->first()->name}})
            @endif
            </a>
        @endif
        </a>
    @endif
    <div class="d-inline-flex">
        <a href="{{url("")}}" class="btn btn-secondary"><i class="fas fa-search"></i> Vyhledávání</a>
        {{-- <a href="#" class="btn btn-secondary"><i class="fas fa-book"></i> Zpěvníky</a> --}}
        {{-- <a href="#" class="btn btn-secondary"><i class="fas fa-star"></i> Oblíbené</a> --}}
        <a href="{{route("client.about")}}" class="btn btn-secondary"><i class="fas fa-info"></i> O&nbsp;zpěvníku</a>
        {{-- <a href="#" class="btn btn-secondary"><i class="fas fa-cog"></i> Nastavení</a> --}}
        <a href="{{route("client.account")}}" class="btn btn-secondary"><i class="fas fa-user"></i> Uživatel</a>
        <dark-mode-button v-cloak> Tmavý&nbsp;mód</dark-mode-button>
    </div>
    </div>
</nav>

<a class="invisible btn btn-secondary mobile-padding-button mb-0"><i class="fas fa-search"></i></a>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a href="{{url("")}}" class="btn"><img src="{{asset('img/logo_v2.png')}}" height="24" alt="logo"></a>
        <a href="{{url("")}}" class="btn btn-secondary"><i class="fas fa-search"></i></a>
        {{-- <a href="#" class="btn btn-secondary"><i class="fas fa-book"></i></a> --}}
        {{-- <a href="#" class="btn btn-secondary"><i class="fas fa-star"></i></a> --}}
        <a href="{{route("client.about")}}" class="btn btn-secondary"><i class="fas fa-info"></i></a>
        {{-- <a href="#" class="btn btn-secondary"><i class="fas fa-cog"></i></a> --}}
        <dark-mode-button></dark-mode-button>
        @if (Auth::check())
            <a href="{{route('admin.dashboard')}}" class="btn btn-secondary"><i class="fas fa-user"></i></a>
        @endif
    </div>
</nav>