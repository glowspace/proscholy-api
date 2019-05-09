<div class="card" style="margin-bottom: 1em;">
    <div class="card-header">
        <p>Náhled souboru (klikněte na obrázek pro stažení)</p>
        <i style="color: #db0e0e;" class="fas fa-file-pdf"></i>

        <span>{{$instance->public_name}}</span>
    </div>

    {{-- edit link if user authorized --}}
    @if (Auth::check() && !Request::is('admin/*'))
        <div class="card-header">
            @if ($instance instanceof \App\File)
                <a href="{{ route('admin.file.edit', ['file' => $instance]) }}">Upravit soubor</a>
            @else
                <a href="{{ route('admin.external.edit', ['external' => $instance]) }}">Upravit externí odkaz</a>
            @endif
        </div>
    @endif

    <div class="card-body">
        <a href="{{ $instance instanceof \App\File ? $instance->download_url : $instance->url }}" target="_blank">
            <img src="{{ $instance->thumbnail_url }}" alt="{{ $instance->public_name }}" class="img-fluid">
        </a>
    </div>
</div>