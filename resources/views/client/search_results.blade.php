@extends('layout.client')

@section('content')
    <div class="container">
        <Search str-prefill="{{ $search_string }}"></Search>
    </div>
@endsection
