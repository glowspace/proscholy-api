@extends('layout.admin')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam zpěvníků"}}</h2>

        <songbooks-list></songbooks-list>
    </div>
@endsection

