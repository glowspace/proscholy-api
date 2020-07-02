@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>{{ $title ?? "Seznam externích zdrojů"}}</h2>
        
        <externals-list></externals-list>
    </div>
@endsection

