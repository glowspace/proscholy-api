@extends('layout.master')

@section('title', $song_l->name . ' - noty')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Hudební podklady písně {{$song_l->name}}</h1>

        @if($song_l->scoresCount() > 0)
            <div class="card">
                <div class="card-header">Odkazy na noty na internetu</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Odkaz</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Zobrazeno</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($song_l->scoreFiles as $file)
                                <tr>
                                    <td><i class="fa fa-file-pdf" style="color: #d83027"></i></td>
                                    <td>
                                        <a href="{{$file->download_url}}">{{$file->getPublicName()}}</a>
                                        @if (Auth::check())
                                            <br/><a href="{{ route('admin.file.edit', $file) }}">Upravit soubor</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($file->author))
                                            <a href="{{route('client.author', $file->author)}}">{{$file->author->name}}</a>
                                        @else
                                            (neznámý)
                                        @endif
                                    </td>
                                    <td>{{$file->downloads}} x</td>
                                </tr>
                            @endforeach

                            @foreach($song_l->scoreExternals as $external)
                                <tr>
                                    <td><i class="fa fa-file-pdf" style="color: #d83027"></i></td>
                                    <td>
                                        <a href="{{$external->getEmbedUrl()}}">{{$external->getEmbedUrl()}}</a>
                                        @if (Auth::check())
                                            <br/><a href="{{ route('admin.external.edit', $external) }}">Upravit externí odkaz</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($external->author))
                                            <a href="{{route('client.author', $external->author)}}">{{$external->author->name}}</a>
                                        @else
                                            (neznámý)
                                        @endif
                                    </td>
                                    <td>{{$external->visits}} x</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="row">

                    </div>

                    <hr>
                    Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
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
