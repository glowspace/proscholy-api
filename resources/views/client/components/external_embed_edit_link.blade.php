@if (Auth::check() && !Request::is('admin/*'))
    <div class="card-header">
        <a href="{{ route('admin.external.edit', $external) }}">Upravit externí zdroj</a>
    </div>
@endif