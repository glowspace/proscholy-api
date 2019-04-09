<div class="card" style="margin-bottom: 1em;">
    <div class="card-header">
        <p>Náhled souboru (klikněte na obrázek pro stažení)</p>
        <i style="color: #db0e0e;" class="fas fa-file-pdf"></i>

        <span>{{$file->getPublicName()}}</span>
    </div>

    {{-- edit link if user authorized --}}
    @if (Auth::check() && !Request::is('admin/*'))
        <div class="card-header">
            <a href="{{ route('admin.file.edit', $file) }}">Upravit soubor</a>
        </div>
    @endif

    <div class="card-body">
        <a href="{{ $file->download_url }}">
            <img src="{{ $file->thumbnail_url }}" alt="{{ $file->getPublicName() }}" class="img-fluid">
        </a>
    </div>
</div>