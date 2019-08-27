@extends('errors::layout')

@section('code', '419')
@section('title', 'Stránka už neplatí – Zpěvník ProScholy.cz')
@section('error-description')
	<p>Omlouváme se, vaše relace vypršela.<br> Obnovte stránku nebo zkuste použít vyhledávání.</p>
	<div class="text-center text-white">
		<a href="/" class="btn btn-outline-light display-all-songs font-weight-bold">
			<i class="fas fa-search pr-1"></i> VYHLEDÁVÁNÍ
		</a>
	</div>
@endsection
