@extends('layout.admin')

@section('title-edit', 'Odkaz '.$external->id)

@section('content-withmenu')
    <external-edit preset-id="{{ $external->id }}"></external-edit>
@endsection
