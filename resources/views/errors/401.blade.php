@extends('errors::layout')

@section('code', '401')
@section('title-suffixed', 'Nemáte dostatečná oprávnění')
@section('error-description')
	<p>Je nám líto, chybí vám oprávnění pro přístup na tuto stránku.</p>
	<a href="/" class="btn btn-secondary mr-2">
        <i class="fas fa-home pr-1"></i> DOMŮ
    </a>
@endsection
