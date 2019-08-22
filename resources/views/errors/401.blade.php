@extends('errors::layout')

@section('code', '401')
@section('title', 'Nemáte dostatečná oprávnění – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Je nám líto, chybí vám oprávnění pro přístup na tuto stránku.<br> Zkuste použít vyhledávání, to žádná zvláštní oprávnění nevyžaduje.</p>
	<div class="text-center text-white">
		<a href="/" class="btn btn-outline-light display-all-songs font-weight-bold">
			<i class="fas fa-search pr-1"></i> VYHLEDÁVÁNÍ
		</a>
	</div>
@endsection