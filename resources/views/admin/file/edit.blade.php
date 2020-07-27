@extends('layout.admin')

@section('title-edit', $file->name ? $file->name : $file->filename)

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2 mb-3">Úprava nahraného souboru</h1>
        <file-edit preset-id="{{ $file->id }}"></file-edit>
    </div>
@endsection
