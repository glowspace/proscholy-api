@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <h2>Externí zdroje</h2>
        
                <div style="float: right">
                    <a href="{{route('admin.external.create')}}" class="btn btn-outline-primary">Přidat zdroj</a>
                </div>
        
                <h3 style="margin-bottom: 5px;">Zdroje k přiřazení</h3>
                <span class="text-warning" style="display: inline-block; margin-bottom: 20px">Externí zdroje, které nemají přiřazeného autora nebo píseň.</span>
        
                @if ($todo->count() > 0)
                    <table class="table table-bordered" id="table_1">
                        <thead>
                            <tr>
                                <th>Odkaz</th>
                                <th>Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todo as $external)
                                <tr>
                                    <td>
                                        <a href="{{route('admin.external.edit', ['external'=>$external->id])}}">{{$external->generateTitle()}}</a>
                                    </td>
                                    <td>
                                        @include('admin.components.deletebutton', [
                                            'url' => route('admin.external.delete', ['external' => $external->id] ),
                                            'class' => 'btn btn-warning'
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-success">Hurá, všechny externí zdroje jsou přiřazené.</div>
                @endif
                <br>
        
                <h3 style="margin-bottom: 5px;">Přiřazené zdroje</h3>
                <span style="display: inline-block;margin-bottom: 20px">Tyto média už mají přiřazeného autora i píseň.</span>
        
                <table class="table table-bordered" id="table_2">
                    <thead>
                        <tr>
                            <th>Odkaz</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rest as $external)
                            <tr>
                                <td>
                                    <a href="{{route('admin.external.edit',['external'=>$external->id])}}">{{$external->generateTitle()}}</a>
                                </td>
                                <td>
                                    @include('admin.components.deletebutton', [
                                        'url' => route('admin.external.delete', ['external' => $external->id] ),
                                        'class' => 'btn btn-warning'
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@include('admin.components.deletebutton_includes')

@include('admin.components.datatable_includes')

@include('admin.components.datatable', ['table_id' => 'table_1'])
@include('admin.components.datatable', ['table_id' => 'table_2'])