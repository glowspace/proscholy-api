@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Nová píseň</h2>

        <form action="{{route('admin.song.store')}}" method="post">
            @csrf
            <input required class="form-control" autofocus name="name" placeholder="Název písně"><br>
            @if (!Auth::user()->can('publish songs'))
                <p>
                    Upozornění: přidáváte píseň z účtu, který neumožňuje přímé publikování písně.
                    Píseň si uložíme, ale bude potřeba počkat na schválení některým z editorů.
                </p>
            @endif

            <button type="submit" class="btn btn-outline-primary" name="redirect" value="create">Uložit</button>
            <button type="submit" class="btn btn-outline-primary" name="redirect" value="edit">Uložit a upravit</button>
        </form>
    </div>
@endsection
