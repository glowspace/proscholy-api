@extends('layout.master')

@section('title', $song_l->name . ' - nahrávky')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Nahrávky písně {{$song_l->name}}</h1>

        @if($song_l->externals()->audio()->count())
            <div class="card">
                <div class="card-header">V online streamovacích službách</div>
                <div class="card-body">
                    <div class="row">
                        @foreach($song_l->externals()->audio()->get() as $external)
                            <div class="col-sm-4">
                                @component('client.components.external_embed', ['external'=> $external])@endcomponent
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                </div>
            </div>
        @endif

        @if ($song_l->files()->audio()->count() > 0)
            <div class="card">
                <div class="card-header">Nahrávky ke stažení</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Název souboru</th>
                                <th scope="col">Autor</th>
                                <th scope="col">Staženo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($song_l->files()->audio()->get() as $file)
                                <tr>
                                    <td>
                                        <a href="{{ $file->download_url }}">{{ $file->getPublicName() }}</a>
                                        @if (Auth::check())
                                            <br/><a href="{{ route('admin.file.edit', $file) }}">Upravit soubor</a>
                                        @endif
                                    </td>
                                    <td>{{ $file->author == null ? "-" : $file->author->name }}</td>
                                    <td>{{ $file->downloads }}x</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
@endpush
