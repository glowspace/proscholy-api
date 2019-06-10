<div class="card card-green"
     style="margin-bottom: 1em;">
    <div class="card-header py-2">
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

        @if (Auth::check() && !Request::is('admin/*'))
            @if ($source instanceof App\External)
                <a href="{{ route('admin.external.edit', ['external' => $source->id ]) }}" class="text-warning text-uppercase"> - upravit</a>
            @endif
            
            @if ($source instanceof App\File)
                <a href="{{ route('admin.file.edit', ['file' => $source->id ]) }}" class="text-warning text-uppercase"> - upravit</a>
            @endif
        @endif

        <a href="{{ $source->url }}" class="float-right" target="_blank"><i class="fas fa-external-link-alt"></i></a>
    </div>

    <external-view url="{{ $source->url }}"  media-id="{{ $source->getMediaId() }}" :type="{{ $source->getSourceType() }}"></external-view>


    {{-- @if (isset($external->media_id) && $external->media_id)
        <external-view media-id="{{ $external->media_id }}" :type="{{ $external->type }}"></external-view>
    @else
        <external-view url="{{ $external->url }}" :type="{{ $external->type }}"></external-view>
        
    @endif --}}
</div>