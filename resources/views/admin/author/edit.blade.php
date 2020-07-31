@extends('layout.admin')

@section('title-edit', $author->name)

@section('content-withmenu')
    <author-edit preset-id="{{ $author->id }}"></author-edit>
@endsection
