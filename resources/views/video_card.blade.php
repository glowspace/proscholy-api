<div class="card" style="margin-bottom: 1em;">
    <div class="card-header">
        <i style="color: #db0e0e;" class="fab fa-youtube"></i>
        @if(isset($video->author_id) and isset($video->song_lyric_id))
            {!! $video->author->getLink() !!} - {!! $video->songLyric->getLink() !!}
        @endif
    </div>

    <div class="embed-responsive embed-responsive-16by9">

        {{--<iframe src="{{$video->getEmbedUrl()}}?showinfo=0" frameborder="0"--}}
                {{--allowfullscreen></iframe>--}}
        <iframe src="{{$video->getEmbedUrl()}}" frameborder="0"
                allowfullscreen></iframe>
    </div>
</div>