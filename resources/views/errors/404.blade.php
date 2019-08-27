@extends('errors::layout')

@section('code', '404')
@section('title', 'Stránka nenalezena – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Stránka nebyla nalezena.<br> Zkuste použít vyhledávání.</p>
	<div class="text-center text-white">
		<a href="/" class="btn btn-outline-light display-all-songs font-weight-bold">
			<i class="fas fa-search pr-1"></i> VYHLEDÁVÁNÍ
		</a>
	</div>
@endsection