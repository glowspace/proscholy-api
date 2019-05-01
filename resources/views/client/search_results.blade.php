@extends('layout.client')

@section('navbar')
    @include('client.components.menu_main')
@endsection

@section('content')
    <v-app>
        <div class="content-padding">
            <h1>Vyhledávání</h1>

            {{-- <form method="POST"
                  action="{{route('client.search')}}">
                @csrf
                <input class="form-control search-basic"
                       name="query"
                       placeholder="Zadejte název písně, část textu nebo jméno autora"
                       value="{{$phrase}}"
                       autofocus>
                <button type="submit"
                        class="btn btn-primary">
                    <i class="fa fa-search"></i>
                </button>
            </form> --}}
            <search-input str-prefill="svorni"></search-input>

            <div class="row">
                <div class="col-sm-8">
                    <div class="card card-blue">
                        <div class="card-header">Písně</div>
                        <div class="card-body">
                            <songs-list></songs-list>
                        </div>
                    </div>

                    <div class="card card-green">
                        <div class="card-header">Autoři</div>
                        <div class="card-body">
                            <table class="table">
                                @forelse($authors as $author)
                                    <tr>
                                        <td>
                                            <a href="{{route('client.author',$author)}}">{{$author->getSearchTitle()}}</a>
                                            - {{$author->getSearchText()}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <i>Žádný autor nebyl nalezen.</i>
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card card-red">
                        <div class="card-header">Možnosti vyhledávání</div>
                        <div class="card-body">
                            <tags></tags>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-app>
@endsection
