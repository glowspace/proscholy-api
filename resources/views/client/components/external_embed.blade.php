@if($external->type == 0)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #db0e0e;" class="fab fa-youtube"></i>
            @if($external->author !== null and $external->song_lyric !== null)
                {!! $external->author->getLink() !!} - <a
                        href="{{$external->song_lyric->getLink()}}">{{$external->song_lyric->name}}</a>
            @endif
        </div>

        <div class="embed-responsive embed-responsive-16by9">
            <iframe src="{{$external->getEmbedUrl()}}" frameborder="0"
                    allowfullscreen></iframe>
        </div>
    </div>
@elseif($external->type == 1)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #277d11;" class="fab fa-spotify"></i>
            @if(isset($external->author_id) and isset($external->song_lyric_id))
                {!! $external->author->getLink() !!} - <a
                        href="{{$external->song_lyric->getLink()}}">{{$external->song_lyric->name}}</a>
            @endif
        </div>
        <iframe src="{{$external->url}}" width="100%" height="80" frameborder="0"
                allowtransparency="true" allow="encrypted-media"></iframe>
    </div>

@elseif($external->type == 2)
    <div class="card" style="margin-bottom: 1em;">
        <div class="card-header">
            <i style="color: #ff9500;" class="fab fa-soundcloud"></i>
            @if(isset($external->author_id) and isset($external->song_lyric_id))
                {!! $external->author->getLink() !!} - <a
                        href="{{$external->song_lyric->getLink()}}">{{$external->song_lyric->name}}</a>
            @endif
        </div>
        <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
                src="https://w.soundcloud.com/player/?url={{$external->url}}&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>
    </div>
@else
    <div class="card" style="margin-bottom: 1em;">Wrong external media type.</div>
@endif