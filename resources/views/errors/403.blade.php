@extends('errors::layout')

@section('code', '403')
@section('title', 'Přístup zamítnut – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Na tuhle stránku bohužel nemáte přístup.<br> Zkuste použít vyhledávání, tam přístup určitě máte.</p>
	<div class="text-center text-white">
		<a href="/" class="btn btn-outline-light display-all-songs font-weight-bold">
			<i class="fas fa-search pr-1"></i> VYHLEDÁVÁNÍ
		</a>
	</div>
@endsection