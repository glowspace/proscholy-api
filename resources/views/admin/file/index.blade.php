@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam souborů</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <table class="table table-bordered" id="index_table">
                    <thead>
                        <tr>
                            <th>Jméno souboru</th>
                            <th>Typ</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    @foreach($files as $file)
                    <tr>
                        <td><a href="{{route('admin.file.edit',['id'=>$file->id])}}">{{$file->filename}}</a></td>
                        <td>{{ $file->getTypeString() }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.file.delete',['file' => $file->id ]),
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