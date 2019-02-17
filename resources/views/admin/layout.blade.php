@extends('layout.master')

@section('navbar-brand_href', route('admin.dashboard'))

@section('navbar')
    @include('admin.components.navbar')
@endsection