@extends('errors::layout')

@section('code', '429')
@section('title-suffixed', 'Příliš mnoho požadavků')
@section('error-description')
	<p>Je nám líto, zaslali jste na náš server příliš mnoho požadavků. Zkuste chvilku počkat.</p>
@endsection
