@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <a href="{{route('admin.dashboard')}}">Zpět do administrace</a>

        <h2>Zvolte external, které chcete upravit:</h2>

        <h3>TO-DO</h3>
        @foreach($todo as $external)
            <a href="{{route('admin.external.edit',['id'=>$external->id])}}">{{$external->generateTitle()}}</a><br>
        @endforeach

        <h3>Všechna videa</h3>

        @foreach($externals as $external)
            <a href="{{route('admin.external.edit',['id'=>$external->id])}}">{{$external->generateTitle()}}</a><br>
        @endforeach
    </div>

@endsection
