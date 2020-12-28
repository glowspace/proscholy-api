@extends('errors::layout')

@section('code', '403')
@section('title', 'Přístup zamítnut – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Na tuhle stránku bohužel nemáte přístup.</p>
    <a href="/" class="btn btn-secondary mr-2">
        <i class="fas fa-home pr-1"></i> DOMŮ
    </a>
@endsection
