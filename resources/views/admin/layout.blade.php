@extends('layout.master')

@section('navbar-brand_href', route('admin.dashboard'))

@section('navbar')
    @include('admin.components.menu')
@endsection

@section('app-js')
    <script type="text/javascript" src="{{ mix('_admin/js/app.js') }}"></script>
@endsection