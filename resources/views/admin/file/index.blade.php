@extends('layout.admin')

@section('title-suffixed', 'Nahrané soubory')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2 mb-3">Nahrané soubory</h1>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>
        <files-list></files-list>
    </div>
@endsection
