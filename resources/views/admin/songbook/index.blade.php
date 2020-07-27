@extends('layout.admin')

@section('title-suffixed', 'Seznam zpěvníků')

@section('content-withmenu')
    <div class="content-padding">
        <h1 class="h2">{{ $title ?? "Seznam zpěvníků"}}</h1>
        <songbooks-list></songbooks-list>
    </div>
@endsection

