@extends('layout.admin')

@section('content-withmenu')
    <div class="content-padding">
    <h2>Úprava autora</h2>
        <author-edit preset-id="{{ $author->id }}"></author-edit>
    </div>
@endsection

