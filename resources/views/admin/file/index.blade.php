@extends('layout.admin')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam souborů"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>

        <files-list></files-list>
    </div>
@endsection

