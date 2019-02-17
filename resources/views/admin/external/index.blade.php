@extends('admin.layout')

@section('content')
    <div class="content-padding">

        <h2>Externí zdroje</h2>

        <h3 style="margin-bottom: 5px;">Zdroje k přiřazení</h3>
        <span class="text-warning" style="display: inline-block; margin-bottom: 20px">Externí zdroje, které nemají přiřazeného autora nebo píseň.</span>

        <table class="table table-bordered">
            @forelse($todo as $external)
                <tr>
                    <td>
                        <a href="{{route('admin.external.edit', ['external'=>$external->id])}}">{{$external->generateTitle()}}</a>
                    </td>
                    <td>
                        @include('admin.components.deletebutton', ['url' => route('admin.external.delete', ['external' => $external->id] )])
                    </td>
                </tr>
                @empty
                <div class="text-success">Hurá, všechny externí zdroje jsou přiřazené.</div>
            @endforelse
        </table>

        <h3 style="margin-bottom: 5px;">Přiřazené zdroje</h3>
        <span style="display: inline-block;margin-bottom: 20px">Tyto média už mají přiřazeného autora i píseň.</span>

        <table class="table table-bordered">

            @foreach($rest as $external)
                <tr>
                    <td>
                        <a href="{{route('admin.external.edit',['external'=>$external->id])}}">{{$external->generateTitle()}}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.external.delete',['external'=>$external->id]) }}"
                           class="btn btn-warning">Vymazat</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection

@include('admin.components.deletebutton_includes')
