@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Nová píseň</h2>

        <form action="{{route('admin.song.store')}}" method="post">
            @csrf
            <input required class="form-control" autofocus name="name" placeholder="Název písně"><br>

            <button type="submit" name="redirect" value="create">Uložit</button>
            <button type="submit" name="redirect" value="edit">Uložit a upravit</button>
        </form>
    </div>
@endsection
