@extends('layout.admin')

@section('title-suffixed', $title)

@section('content-withmenu')
    <{{$model_name}}s-list></{{$model_name}}s-list>
@endsection
