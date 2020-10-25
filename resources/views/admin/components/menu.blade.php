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

@include('admin.components.menu-item', [
    'route' => 'client.regenschori',
    'icon' => 'church',
    'text' => 'Regenschori'
])

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

{{-- <div class="navbar-label material-shadow text-success">Úprava položek</div> --}}

<div class="mt-lg-3"></div>

@include('admin.components.menu-item', [
    'route' => 'admin.song.index',
    'icon' => 'music',
    'text' => 'Písně',
    'plus' => true
])

@include('admin.components.menu-item', [
    'route' => 'admin.author.index',
    'icon' => 'pen',
    'text' => 'Autoři',
    'plus' => true
])

@include('admin.components.menu-item', [
    'route' => 'admin.external.index',
    'icon' => 'link',
    'text' => 'Materiály',
    'plus' => true
])

{{-- @include('admin.components.menu-item', [
    'route' => 'admin.tag.index',
    'icon' => 'tag',
    'text' => 'Štítky/Kategorie písniček'
]) --}}

@include('admin.components.menu-item', [
    'route' => 'admin.songbook.index',
    'icon' => 'book',
    'text' => 'Zpěvníky',
    'plus' => true
])

@include('admin.components.menu-item', [
    'route' => 'admin.news-item.index',
    'icon' => 'info-circle',
    'text' => 'Novinky',
    'plus' => true
])

@can('manage users')
    @include('admin.components.menu-item', [
        'route' => 'admin.user.index',
        'icon' => 'user',
        'text' => 'Uživatelé',
        'plus_route' => 'admin.user.create'
    ])
@endcan
