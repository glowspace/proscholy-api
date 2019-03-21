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

@can('access todo')
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
        'route' => 'admin.song.no-tag',
        'icon' => 'music',
        'text' => 'Písně bez štítků'
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
@endcan

<div class="navbar-label material-shadow text-danger">Kontrola obsahu</div>

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
@endcan

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

@include('admin.components.menu-item', [
    'route' => 'admin.tag.index',
    'icon' => 'tag',
    'text' => 'Štítky/Kategorie písniček'
])

@can('manage users')
    @include('admin.components.menu-item', [
        'route' => 'admin.user.index',
        'icon' => 'user',
        'text' => 'Uživatelé'
    ])
@endcan