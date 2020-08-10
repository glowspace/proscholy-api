@extends('layout.admin')

@section('title-edit', $songbook->name)

@section('content-withmenu')
    <songbook-edit preset-id="{{ $songbook->id }}"></songbook-edit>
@endsection
