@extends('layout.admin')

@section('title-edit', $file->name ? $file->name : $file->filename)

@section('content-withmenu')
    <file-edit preset-id="{{ $file->id }}"></file-edit>
@endsection
