<div class="card card-green"
     style="margin-bottom: 1em;">
    <div class="card-header">
        @if ($source->getSourceType() == 1)      <i style="color: #262b2f;"
                                           class="fab fa-spotify"></i>
        @elseif ($source->getSourceType() == 2)  <i style="color: #ff9500;"
                                           class="fab fa-soundcloud"></i>
        @elseif ($source->getSourceType() == 3)  <i style="color: #db0e0e;"
                                           class="fab fa-youtube"></i>
        @elseif ($source->getSourceType() == 4)  <i style="color: #db0e0e;"
                                           class="fas fa-file-pdf"></i>
        @endif

        @foreach ($source->authors()->get() as $author)
            <a href="{{route('client.author', $author)}}">{{$author->name}}</a>@if (!$loop->last), @endif
        @endforeach

        @if ($source->song_lyric()->get())
        - <a href="{{ $source->song_lyric()->get()->public_url }}">{{$source->song_lyric()->get()->name}}</a>
        @endif

        @if (Auth::check() && !Request::is('admin/*'))
            @if ($source instanceof App\External)
                <a href="{{ route('admin.external.edit', ['external' => $source->getId() ]) }}" class="text-warning text-uppercase"> - upravit</a>
            @endif
            
            @if ($source instanceof App\File)
                <a href="{{ route('admin.file.edit', ['file' => $source->getId() ]) }}" class="text-warning text-uppercase"> - upravit</a>
            @endif
        @endif
    </div>

    <external-view url="{{ $source->url }}"  media-id="{{ $source->getMediaId() }}" :type="{{ $source->getSourceType() }}"></external-view>

    <div class="card-body">
        <a href="{{ $source->url }}" target="_blank">Klikněte pro zobrazení v novém okně</a>
    </div>


    {{-- @if (isset($external->media_id) && $external->media_id)
        <external-view media-id="{{ $external->media_id }}" :type="{{ $external->type }}"></external-view>
    @else
        <external-view url="{{ $external->url }}" :type="{{ $external->type }}"></external-view>
        
    @endif --}}
</div>