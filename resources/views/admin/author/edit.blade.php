@extends('layout.admin')

@section('title-edit', $author->name)

@section('content-withmenu')
    <div class="content-padding">
        <h1 class="h2">Úprava autora</h1>
        <author-edit preset-id="{{ $author->id }}"></author-edit>
    </div>
@endsection
