{{--@include('admin.components.menu-item', [--}}
{{--    'route' => 'admin.dashboard',--}}
{{--    'icon' => 'tachometer-alt',--}}
{{--    'text' => 'Nástěnka'--}}
{{--])--}}


{{-- @include('admin.components.menu-item', [
    'route' => 'client.home',
    'icon' => 'user',
    'text' => 'Můj účet'
]) --}}



{{--
@can('access todo')
    <div class="navbar-label material-shadow text-danger">Plnění obsahem</div>
@endcan --}}

{{-- <div class="navbar-label material-shadow text-danger">Kontrola obsahu</div>

@can('publish songs')
    @include('admin.components.menu-item', [
        'route' => 'admin.song.to-publish',
        'icon' => 'music',
        'text' => 'Písně k publikování'
    ])
@endcan

@can('approve songs')
    @include('admin.components.menu-item', [
        'route' => 'admin.song.to-approve',
        'icon' => 'music',
        'text' => 'Písně k autorskému schválení'
    ])
@endcan --}}

{{-- <div class="navbar-label material-shadow text-success">Úprava položek</div> --}}

<div class="mt-lg-3"></div>

@include('admin.components.menu-item', [
    'route' => 'admin.dashboard',
    'icon' => 'home',
    'text' => 'Nástěnka'
])

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
    'text' => 'Materiály'
])

@include('admin.components.menu-item', [
    'route' => 'admin.tag.index',
    'icon' => 'tag',
    'text' => 'Štítky'
])

@include('admin.components.menu-item', [
    'route' => 'admin.songbook.index',
    'icon' => 'book',
    'text' => 'Zpěvníky'
])

@include('admin.components.menu-item', [
    'route' => 'admin.news-item.index',
    'icon' => 'info-circle',
    'text' => 'Novinky'
])

@can('manage users')
    @include('admin.components.menu-item', [
        'route' => 'admin.user.index',
        'icon' => 'user',
        'text' => 'Uživatelé'
    ])
@endcan

@include('admin.components.menu-item', [
    'route' => 'auth.logout',
    'icon' => 'lock',
    'text' => 'Odhlásit se'
])
