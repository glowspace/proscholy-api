@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>{{ $title ?? "Seznam písní"}}</h2>

        <songs-list></songs-list>
    </div>
@endsection

