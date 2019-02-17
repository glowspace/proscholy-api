@extends('layout.master')

@section('navbar')
    <a class="btn btn-secondary" href="{{route('client.home')}}">
        <i class="fas fa-search"></i> Nové vyhledávání
    </a>
@endsection

@section('content')
    <div class="content-padding">
        <h1>Team</h1>

        <p>Zpěvník připravuje</p>

        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Michael Dojčár</h5>
                        {{--<p class="card-text"</p>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection