@extends('layout.admin')

@section('title-edit', $songbook->name)

@section('content-withmenu')
    <div class="content-padding">
        <h1 class="h2">Úprava zpěvníku</h1>
        <songbook-edit preset-id="{{ $songbook->id }}"></songbook-edit>
    </div>
@endsection

