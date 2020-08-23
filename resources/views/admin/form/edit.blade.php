@extends('layout.admin')

@section('title-edit', $title)

@section('content-withmenu')
    <{{$model_name}}-edit preset-id="{{ $model_id }}"></{{$model_name}}-edit>
@endsection
