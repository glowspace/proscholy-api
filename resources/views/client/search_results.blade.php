@extends('layout.client-sidepage')

@section('content')
    <div class="container">
        <Search str-prefill="{{ $search_string }}"></Search>
    </div>
@endsection
