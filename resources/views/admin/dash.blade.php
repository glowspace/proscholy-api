@extends('layout.layout_old')

@section('content')

    <h1>Administrace</h1>
    <h2>Kompletování obsahu</h2>
    <a class="btn btn-success" href="{{route('admin.todo')}}">>> Materiály k doplnění <<</a>

    <h2>Písně</h2>
    <a class="btn btn-outline-primary" href="{{route('admin.song.new')}}">+ Nová píseň</a>
    <a class="btn btn-outline-primary" href="{{route('admin.songs')}}">Spravovat písně</a>

    <h2>Autoři</h2>
    <a class="btn btn-outline-primary" href="{{route('admin.author.new')}}">+ Nový autor</a>

    <h2>Videa</h2>
    <a class="btn btn-outline-primary" href="{{route('admin.external.new')}}">+ Nové external</a>
    <a class="btn btn-outline-primary" href="{{route('admin.externals')}}">Spravovat videa</a>

@endsection
