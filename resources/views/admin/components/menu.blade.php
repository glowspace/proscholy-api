<div class="navbar-label material-shadow text-warning">Administrace</div>

@include('admin.components.menu-item', [
    'route' => 'admin.dashboard',
    'icon' => 'home',
    'text' => 'Nástěnka'
])

@include('admin.components.menu-item', [
    'route' => 'client.home',
    'icon' => 'arrow-left',
    'text' => 'Návrat na web'
])

@include('admin.components.menu-item', [
    'route' => 'auth.logout',
    'icon' => 'sign-out-alt',
    'text' => 'Odhlásit se'
])

<div class="navbar-label material-shadow text-danger">Plnění obsahem</div>

@include('admin.components.menu-item', [
    'route' => 'admin.song.no-lyric',
    'icon' => 'music',
    'text' => 'Písně bez textu'
])

@include('admin.components.menu-item', [
    'route' => 'admin.song.no-author',
    'icon' => 'music',
    'text' => 'Písně bez autora'
])

@include('admin.components.menu-item', [
    'route' => 'admin.song.no-chord',
    'icon' => 'music',
    'text' => 'Písně bez akordů'
])

@include('admin.components.menu-item', [
    'route' => 'admin.external.no-author',
    'icon' => 'link',
    'text' => 'Odkazy bez autora/písničky'
])

@include('admin.components.menu-item', [
    'route' => 'admin.file.no-author',
    'icon' => 'file',
    'text' => 'Soubory bez autora/písničky'
])

<div class="navbar-label material-shadow text-primary">Úprava položek</div>

@include('admin.components.menu-item', [
    'route' => 'admin.song.index',
    'icon' => 'music',
    'text' => 'Písně'
])

@include('admin.components.menu-item', [
    'route' => 'admin.author.index',
    'icon' => 'pen',
    'text' => 'Autoři'
])

@include('admin.components.menu-item', [
    'route' => 'admin.external.index',
    'icon' => 'link',
    'text' => 'Externí odkazy'
])

@include('admin.components.menu-item', [
    'route' => 'admin.file.index',
    'icon' => 'file',
    'text' => 'Nahrané soubory'
])

@can('manage users')
    @include('admin.components.menu-item', [
        'route' => 'admin.user.index',
        'icon' => 'user',
        'text' => 'Uživatelé'
    ])
@endcan


{{-- <a class="btn btn-secondary" href="{{route('admin.dashboard')}}">
    <i class="fas fa-home"></i> Nástěnka
</a> --}}
{{-- <a class="btn btn-secondary" href="{{route('client.home')}}">
    <i class="fas fa-arrow-left"></i> Návrat na web
</a>
<a class="btn btn-secondary" href="{{route('auth.logout')}}">
    <i class="fas fa-sign-out-alt"></i> Odhlásit se
</a> --}}


{{-- <a class="btn btn-secondary" href="{{route('admin.song.no-lyric')}}">
    <i class="fas fa-music"></i> <span>Písně bez textu</span>
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
</a> --}}

{{-- <div class="navbar-label material-shadow text-primary">Úprava položek</div>

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
@endcan --}}