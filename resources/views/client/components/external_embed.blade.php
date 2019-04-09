<div class="card" style="margin-bottom: 1em;">
    <div class="card-header">
        @if ($external->type == 1)      <i style="color: #277d11;" class="fab fa-spotify"></i>
        @elseif ($external->type == 2)  <i style="color: #ff9500;" class="fab fa-soundcloud"></i>
        @elseif ($external->type == 3)  <i style="color: #db0e0e;" class="fab fa-youtube"></i>
        @elseif ($external->type == 4)  <i style="color: #db0e0e;" class="fas fa-file-pdf"></i>
        @endif

        @component('client.components.external_widget_label', compact('external'))@endcomponent
    </div>

    {{-- edit link if user authorized --}}
    @if (Auth::check() && !Request::is('admin/*'))
        <div class="card-header">
            <a href="{{ route('admin.external.edit', $external) }}">Upravit externí zdroj</a>
        </div>
    @endif

    @if ($external->media_id)
        @if ($external->type == 1)
            {{-- spotify --}}
            <iframe src="https://open.spotify.com/embed/track/{{ $external->media_id }}" width="100%" height="80" frameborder="0"
                allowtransparency="true" allow="encrypted-media"></iframe>

        @elseif ($external->type == 2)
            {{-- soundcloud --}}
            <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
                src="https://w.soundcloud.com/player/?url={{ $external->media_id }}
                &color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>
        
        @elseif ($external->type == 3)
            {{-- youtube --}}
            <div class="embed-responsive embed-responsive-16by9">
                <iframe src="https://www.youtube.com/embed/{{ $external->media_id }}" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        @endif
    @else
        <div class="card-body">
            <a href="{{ $external->url }}">{{ $external->url }}</a>
        </div>
    @endif
</div>



{{-- 

@if($external->type == 1)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #277d11;" class="fab fa-spotify"></i>
            @component('client.components.external_widget_label', compact('external'))@endcomponent
        </div>
        @include('client.components.external_embed_edit_link')
        <iframe src="{{ $external->getEmbedUrl() }}" width="100%" height="80" frameborder="0"
                allowtransparency="true" allow="encrypted-media"></iframe>
    </div>
@elseif($external->type == 2)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #ff9500;" class="fab fa-soundcloud"></i>
            @component('client.components.external_widget_label', compact('external'))@endcomponent
        </div>
        @include('client.components.external_embed_edit_link')
        <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
                src="{{ $external->getEmbedUrl() }}"></iframe>
    </div>
@elseif($external->type == 3)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #db0e0e;" class="fab fa-youtube"></i>
            @component('client.components.external_widget_label', compact('external'))@endcomponent
        </div>
        @include('client.components.external_embed_edit_link')
        <div class="embed-responsive embed-responsive-16by9">
            <iframe src="{{ $external->getEmbedUrl() }}" frameborder="0"
                    allowfullscreen></iframe>
        </div>
    </div>
@elseif($external->type == 4)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #db0e0e;" class="fas fa-file-pdf"></i>
            @component('client.components.external_widget_label', compact('external'))@endcomponent
        </div>
        @include('client.components.external_embed_edit_link')
        <div class="card-body">
            <a href="{{ $external->getEmbedUrl() }}">{{ $external->getEmbedUrl() }}</a>
        </div>
    </div>
@else
    @if (!empty($external->url))
        <div class="card" style="margin-bottom: 1em; padding: 1em;">
            @include('client.components.external_embed_edit_link')
            <div class="card-body">
                Wrong external media type.
            </div>
        </div>
    @endif
@endif --}}