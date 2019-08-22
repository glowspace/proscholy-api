@extends('errors::layout')

@section('code', '500')
@section('title', 'Chyba serveru – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Ajajaj, na našem serveru se někde stala chyba.<br> Zkuste použít vyhledávání.</p>
	<div class="text-center text-white">
		<a href="/" class="btn btn-outline-light display-all-songs font-weight-bold">
			<i class="fas fa-search pr-1"></i> VYHLEDÁVÁNÍ
		</a>
	</div>
@endsection