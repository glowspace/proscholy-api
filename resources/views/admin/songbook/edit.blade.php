@extends('layout.admin')

@section('content-withmenu')
    <div class="content-padding">
        <h2>Úprava zpěvníku</h2>
        <songbook-edit preset-id="{{ $songbook->id }}"></songbook-edit>
    </div>
@endsection

