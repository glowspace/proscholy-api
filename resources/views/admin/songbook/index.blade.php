@extends('layout.admin')

@section('title-suffixed', 'Zpěvníky')

@section('content-withmenu')
    <div class="content-padding">
        <h1 class="h2 mb-3">Zpěvníky</h1>
        <songbooks-list></songbooks-list>
    </div>
@endsection
