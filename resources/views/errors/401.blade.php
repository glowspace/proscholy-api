@extends('errors::layout')

@section('code', '401')
@section('title', 'Nemáte dostatečná oprávnění – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Je nám líto, chybí vám oprávnění pro přístup na tuto stránku.</p>
	<a href="/" class="btn btn-secondary border mr-2">
        <i class="fas fa-home pr-1"></i> DOMŮ
    </a>
@endsection
