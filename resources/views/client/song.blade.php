@extends('layout.client-sidepage')

@section('title', $song_l->name . ' – píseň ve zpěvníku ProScholy.cz')

@section('content')
    <div class="container">
        <div class="d-flex flex-md-row justify-content-between flex-wrap mt-4">
                <div>
                    <h1 class="m-0 pb-0">{{$song_l->name}}</h1>
                    <p class="song-author"> @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent</p>
                </div>
                <div class="song-tags d-flex flex-row flex-wrap align-items-start justify-content-md-end mb-3">
                    @foreach ($tags_officials as $tag)
                        <a class="tag tag-blue">{{ $tag->name }}</a>
                    @endforeach
                    @foreach ($tags_unofficials as $tag)
                        @if ($tag->parent_tag == null)
                            <a class="tag tag-green">{{ $tag->name }}</a>
                        @else
                            <a class="tag tag-yellow">{{ $tag->name }}</a>
                        @endif
                    @endforeach
                </div>
            </div>

            <song-view>
                {!! $song_l->getFormattedLyrics() !!}
                <template v-slot:media>
                        @if($song_l->youtubeVideos()->count() > 0 || $song_l->spotifyTracks()->count() > 0 || $song_l->soundcloudTracks()->count() > 0 || $song_l->audioFiles()->count() > 0)
                            <div class="row pt-2">
                            @if($song_l->spotifyTracks()->count() > 0)
                                @foreach($song_l->spotifyTracks as $external)
                                <div class="col-md-6">
                                    @component('client.components.media_widget', ['source' => $external])@endcomponent
                                </div>
                                @endforeach
                            @endif

                            @if($song_l->soundcloudTracks()->count() > 0)
                                @foreach($song_l->soundcloudTracks as $external)
                                <div class="col-md-6">
                                    @component('client.components.media_widget', ['source' => $external])@endcomponent
                                </div>
                                @endforeach
                            @endif

                            @if($song_l->audioFiles()->count() > 0)
                                @foreach($song_l->audioFiles as $external)
                                <div class="col-md-6">
                                    @component('client.components.media_widget', ['source' => $external])@endcomponent
                                </div>
                                @endforeach
                            @endif

                            @if($song_l->youtubeVideos()->count() > 0)
                                @foreach($song_l->youtubeVideos as $external)
                                <div class="col-md-6">
                                    @component('client.components.media_widget', ['source' => $external])@endcomponent
                                </div>
                                @endforeach
                            @endif
                            </div>
                        @endif
                    </template>
            </song-view>
@endsection