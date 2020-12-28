@extends('layout.admin')

@section('title-suffixed', 'Nový uživatel')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1>Nový uživatel</h1>
        <div class="row">
            <div class="col-sm-12">
                <form action="{{route('admin.user.store')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="email" type="email" placeholder="E-mail"><br>
                    </div>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="edit">Uložit a upravit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
