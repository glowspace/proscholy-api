<div class="card card-green"
     style="margin-bottom: 1em;">
    <div class="card-header">
        @if ($external->type == 1)      <i style="color: #262b2f;"
                                           class="fab fa-spotify"></i>
        @elseif ($external->type == 2)  <i style="color: #ff9500;"
                                           class="fab fa-soundcloud"></i>
        @elseif ($external->type == 3)  <i style="color: #db0e0e;"
                                           class="fab fa-youtube"></i>
        @elseif ($external->type == 4)  <i style="color: #db0e0e;"
                                           class="fas fa-file-pdf"></i>
        @endif

        @component('client.components.external_widget_label', compact('external'))@endcomponent

        {{-- edit link if user authorized --}}
        @if (Auth::check() && !Request::is('admin/*') && isset($external->id))
            <a href="{{ route('admin.external.edit', ['external' => $external->id ]) }}" class="text-warning text-uppercase"> - upravit</a>
        @endif
    </div>


    @if (isset($external->media_id) && $external->media_id)
        <external-view media-id="{{ $external->media_id }}" type="{{ $external->type }}"></external-view>
    @else
        <external-view url="{{ $external->url }}" type="{{ $external->type }}"></external-view>
        <div class="card-body">
            <a href="{{ $external->url }}" target="_blank">Klikněte pro zobrazení v novém okně</a>
        </div>
        {{-- @if ($external->canHaveThumbnail())
            <div class="card-body">
                <a href="{{ $external->download_url }}">
                    <external-view src="{{ $external->src }}" thumbnail-url="{{ $external->thumbnail_url }}" type="{{ $external->type }}"></external-view>
                </a>
            </div>
        @else
            <div class="card-body">
                <a href="{{ $external->url }}">{{ $external->url }}</a>
            </div>
        @endif --}}
    @endif
</div>