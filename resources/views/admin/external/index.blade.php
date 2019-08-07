@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>{{ $title ?? "Seznam externích zdrojů"}}</h2>
        @if ($type == "show-all")
            <externals-list></externals-list>
        @elseif ($type == 'show-todo')
            <externals-list v-bind:is-todo="true"></externals-list>
        @endif
    </div>
@endsection

