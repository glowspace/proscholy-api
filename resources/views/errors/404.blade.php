@extends('errors::layout')

@section('code', '404')
@section('title', 'Stránka nenalezena – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Stránka nebyla nalezena.</p>
	<a href="/" class="btn btn-secondary border mr-2">
        <i class="fas fa-home pr-1"></i> DOMŮ
    </a>
@endsection
