@extends('layout.admin')

@section('title-suffixed', 'Autoři')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2 mb-3">Autoři</h1>
        <authors-list></authors-list>
    </div>
@endsection
