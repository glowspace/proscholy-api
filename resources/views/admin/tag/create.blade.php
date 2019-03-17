@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Nový štítek</h2>
        <div class="row">
            <div class="col-sm-12">
                <form action="{{route('admin.tag.create')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="name" placeholder="název štítku (v množném čísle - např. dětské písně)"><br>
                    </div>

                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="create">Uložit</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="edit">Uložit a upravit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
