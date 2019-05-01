@extends('layout.client')

@section('title', $song_l->name . ' - soubory')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Ostatní soubory ke stažení k písni {{$song_l->name}}</h1>

        @if($song_l->files()->others()->count() > 0)
            <div class="card">
                <div class="card-header">Dostupné soubory</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Odkaz</th>
                            <th scope="col">Typ</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Zobrazeno</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($song_l->files()->others()->get() as $file)
                                <tr>
                                    <td><i class="fa fa-file-pdf" style="color: #d83027"></i></td>
                                    <td>
                                        <a href="{{$file->download_url}}">{{$file->getPublicName()}}</a>
                                        @if (Auth::check())
                                            <br/><a href="{{ route('admin.file.edit', $file) }}">Upravit soubor</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $file->getTypeString() }}
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
                        </tbody>
                    </table>

                    <hr>
                    Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                </div>
            </div>
        @endif
    </div>
@endsection