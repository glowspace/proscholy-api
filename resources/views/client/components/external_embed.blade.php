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
        @if (Auth::check() && !Request::is('admin/*'))
            <a href="{{ route('admin.external.edit', $external) }}" class="text-warning text-uppercase"> - upravit</a>
        @endif
    </div>


    @if ($external->media_id)
        @if ($external->type == 1)
            {{-- spotify --}}
            <iframe src="https://open.spotify.com/embed/track/{{ $external->media_id }}"
                    width="100%"
                    height="80"
                    frameborder="0"
                    allowtransparency="true"
                    allow="encrypted-media"></iframe>

        @elseif ($external->type == 2)
            {{-- soundcloud --}}
            <iframe width="100%"
                    height="166"
                    scrolling="no"
                    frameborder="no"
                    allow="autoplay"
                    src="https://w.soundcloud.com/player/?url={{ $external->media_id }}
                            &color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>

        @elseif ($external->type == 3)
            {{-- youtube --}}
            <div class="embed-responsive embed-responsive-16by9">
                <iframe src="https://www.youtube.com/embed/{{ $external->media_id }}"
                        frameborder="0"
                        allowfullscreen></iframe>
            </div>
        @endif
    @else
        @if ($external->canHaveThumbnail())
            <div class="card-body">
                <a href="{{ $external->download_url }}">
                    <img src="{{ $external->thumbnail_url }}"
                         alt="{{ $external->public_name }}"
                         class="img-fluid">
                </a>
            </div>
        @else
            <div class="card-body">
                <a href="{{ $external->url }}">{{ $external->url }}</a>
            </div>
        @endif
    @endif
</div>