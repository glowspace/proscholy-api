@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Nové external</h2>
            
        <a href="{{route('admin.externals')}}">Zpět do administrace</a>
        <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a>

        <form action="{{route('admin.external.new.save')}}" method="post">
            @csrf
            <input autofocus name="url" placeholder="URL videa na YT"><br>

            <input type="submit" value="Uložit nové URL">
        </form>
    </div>
@endsection
