@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam autorů"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.author.create')}}">+ Nový autor</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Jméno', 'Typ', 'Akce']
                ])
                    @foreach($authors as $author)
                    <tr>
                        <td><a href="{{route('admin.author.edit',['id'=>$author->id])}}">{{$author->name}}</a></td>
                        <td>{{ $author->getTypeText() }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.author.delete',['author' => $author->id ]),
                            ])
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

