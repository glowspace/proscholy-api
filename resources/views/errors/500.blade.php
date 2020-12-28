@extends('errors::layout')

@section('code', '500')
@section('title-suffixed', 'Chyba serveru')
@section('error-description')
	<p>Ajajaj, na našem serveru se někde stala chyba.</p>
	<a href="/" class="btn btn-secondary mr-2">
        <i class="fas fa-home pr-1"></i> DOMŮ
    </a>
@endsection
