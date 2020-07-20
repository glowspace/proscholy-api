@include('admin.components.menu-item', [
    'route' => 'admin.dashboard',
    'icon' => 'tachometer-alt',
    'text' => 'Nástěnka'
])

@include('admin.components.menu-item', [
    'route' => 'client.home',
    'icon' => 'guitar',
    'text' => 'Zpěvník pro scholy'
])

{{-- @include('admin.components.menu-item', [
    'route' => 'client.home',
    'icon' => 'church',
    'text' => 'Regenschori'
]) --}}

{{-- @include('admin.components.menu-item', [
    'route' => 'client.home',
    'icon' => 'user',
    'text' => 'Můj účet'
]) --}}

@include('admin.components.menu-item', [
    'route' => 'auth.logout',
    'icon' => 'lock',
    'text' => 'Odhlásit se'
])

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

<div class="navbar-label material-shadow text-success">Úprava položek</div>

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

{{-- @include('admin.components.menu-item', [
    'route' => 'admin.tag.index',
    'icon' => 'tag',
    'text' => 'Štítky/Kategorie písniček'
]) --}}

@include('admin.components.menu-item', [
    'route' => 'admin.songbook.index',
    'icon' => 'book',
    'text' => 'Zpěvníky'
])

@can('manage users')
    @include('admin.components.menu-item', [
        'route' => 'admin.user.index',
        'icon' => 'user',
        'text' => 'Uživatelé'
    ])
@endcan
