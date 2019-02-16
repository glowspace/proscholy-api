@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Nový externí zdroj</h2>
            
        <a href="{{route('admin.external.index')}}">Zpět do administrace</a>
        <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a>

        <form action="{{route('admin.external.create')}}" method="post">
            @csrf
            <input autofocus name="url" placeholder="url odkaz"><br>

            <label>Typ odkazu</label>
            <select name="type" title="">
                <option value="0">a</option>
                <option value="1">b</option>
            </select>

            <input type="submit" value="Uložit nový zdroj">
        </form>
    </div>
@endsection
