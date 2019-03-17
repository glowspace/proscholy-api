@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam štítků"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.tag.create')}}">+ Nový štítek</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Jméno', 'Typ', 'Akce']
                ])
                    @foreach($tags as $tag)
                    <tr>
                        <td><a href="{{route('admin.tag.edit',['id'=>$tag->id])}}">{{$tag->name}}</a></td>
                        <td>{{ $tag->getTypeText() }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.tag.delete',['tag' => $tag->id ]),
                            ])
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

