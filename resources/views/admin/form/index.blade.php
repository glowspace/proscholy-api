@extends('layout.admin')

@section('title-suffixed', $title)
@section('wrapper-classes', 'list')

@section('content-withmenu')
    <{{$model_name}}s-list></{{$model_name}}s-list>
@endsection
