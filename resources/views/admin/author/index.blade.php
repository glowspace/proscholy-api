@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam autorů</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.author.create')}}">+ Nový autor</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <table class="table table-bordered" id="index_table">
                    <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Typ</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    @foreach($authors as $author)
                    <tr>
                        <td><a href="{{route('admin.author.edit',['id'=>$author->id])}}">{{$author->name}}</a></td>
                        <td>{{ $author->getTypeText() }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.author.delete',['author' => $author->id ]),
                                'class' => 'btn btn-warning'
                            ])
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@include('admin.components.deletebutton_includes')

@include('admin.components.datatable_includes')
@include('admin.components.datatable', ['table_id' => 'index_table'])