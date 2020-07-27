@extends('layout.admin')

@section('title-edit', 'Odkaz '.$external->id)

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2 mb-3">Úprava externího odkazu</h1>
        <external-edit preset-id="{{ $external->id }}"></external-edit>
    </div>
@endsection
