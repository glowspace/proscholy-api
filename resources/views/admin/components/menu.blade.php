<div class="navbar-label material-shadow text-warning">Administrace</div>
<a class="btn btn-secondary" href="{{route('admin.dashboard')}}">
    <i class="fas fa-home"></i> Nástěnka

<a class="btn btn-secondary" href="{{route('client.home')}}">
    <i class="fas fa-arrow-left"></i> Návrat na web
</a>
<a class="btn btn-secondary" href="{{route('auth.logout')}}">
    <i class="fas fa-sign-out-alt"></i> Odhlásit se
</a>

<div class="navbar-label material-shadow text-danger">Plnění obsahem</div>

<a class="btn btn-secondary" href="{{route('admin.song.no-lyric')}}">
    <i class="fas fa-music"></i> <span>Písně bez textu</span>
    {{-- <span class="badge badge-warning badge-pill">{{  }}</span> --}}
</a>

<a class="btn btn-secondary" href="{{route('admin.song.no-author')}}">
    <i class="fas fa-music"></i> <span>Písně bez autora</span>
</a>

<a class="btn btn-secondary" href="{{route('admin.song.no-chord')}}">
    <i class="fas fa-music"></i> <span>Písně bez akordů</span>
</a>

<a class="btn btn-secondary" href="{{route('admin.external.no-author')}}">
    <i class="fas fa-link"></i> <span>Odkazy bez autora/písničky</span>
</a>

<a class="btn btn-secondary" href="{{route('admin.file.no-author')}}">
    <i class="fas fa-file"></i> <span>Soubory bez autora/písničky</span>
</a>

<div class="navbar-label material-shadow text-primary">Úprava položek</div>

<a class="btn btn-secondary" href="{{route('admin.song.index')}}">
    <i class="fas fa-music"></i> Písně
</a>

<a class="btn btn-secondary" href="{{route('admin.author.index')}}">
    <i class="fas fa-pen"></i> Autoři
</a>

<a class="btn btn-secondary" href="{{route('admin.external.index')}}">
    <i class="fas fa-link"></i> Externí zdroje
</a>

<a class="btn btn-secondary" href="{{route('admin.file.index')}}">
    <i class="fas fa-file"></i> Soubory
</a>

@can('manage users')
    <a class="btn btn-secondary" href="{{route('admin.user.index')}}">
        <i class="fas fa-user"></i> Uživatelé
    </a>
@endcan