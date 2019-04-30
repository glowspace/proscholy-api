@extends('layout.admin')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam externích zdrojů"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.external.create')}}">+ Nový externí zdroj</a>

        @if ($type == "show-all")
            <externals-list></externals-list>
        @elseif ($type == 'show-todo')
            <externals-list v-bind:is-todo="true"></externals-list>
        @endif
    </div>
@endsection

