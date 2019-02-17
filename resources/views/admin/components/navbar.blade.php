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

<a class="btn btn-secondary" href="{{route('admin.todo')}}">
    <i class="fas fa-check"></i> Materiály k doplnění
</a>

<div class="navbar-label material-shadow text-primary">Úprava položek</div>

<a class="btn btn-secondary" href="{{route('admin.song.index')}}">
    <i class="fas fa-music"></i> Písně
</a>

<a class="btn btn-secondary" href="{{route('admin.author.index')}}">
    <i class="fas fa-user"></i> Autoři
</a>

<a class="btn btn-secondary" href="{{route('admin.external.index')}}">
    <i class="fas fa-link"></i> Externí zdroje
</a>