@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h1>Administrace</h1>
        <h2>Kompletování obsahu</h2>
        <a class="btn btn-success" href="{{route('admin.todo')}}">>> Materiály k doplnění <<</a>
    
        <h2>Písně</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>
        <a class="btn btn-outline-primary" href="{{route('admin.song.index')}}">Spravovat písně</a>
    
        <h2>Autoři</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.author.create')}}">+ Nový autor</a>
    
        <h2>Externí zdroje (odkazy)</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.external.create')}}">+ Nový externí zdroj</a>
        <a class="btn btn-outline-primary" href="{{route('admin.external.index')}}">Spravovat zdroje</a>
    </div>

@endsection
