@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <a href="{{route('admin.dashboard')}}">Zpět do administrace</a>

        <h2>Zvolte externí zdroj, které chcete upravit:</h2>

        <h3>TO-DO</h3>
        <table class="table table-bordered">
            @foreach($todo as $external)
            <tr>
                <td><a href="{{route('admin.external.edit',['external'=>$external->id])}}">{{$external->generateTitle()}}</a></td>
                <td>
                    @include('admin.components.deletebutton', ['url' => route('admin.external.delete',['external' => $external->id] )])
                </td>
            </tr>
            @endforeach
        </table>
        
        <h3>Ostatní zdroje</h3>

        <table class="table table-bordered">

            @foreach($rest as $external)
            <tr>
                <td><a href="{{route('admin.external.edit',['external'=>$external->id])}}">{{$external->generateTitle()}}</a></td>
                <td>
                    <a href="{{ route('admin.external.delete',['external'=>$external->id]) }}" class="btn btn-warning">Vymazat</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

@endsection

@include('admin.components.deletebutton_includes')
