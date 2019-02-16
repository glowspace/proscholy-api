@extends('layout.layout_old')

@section('content')
    <a class="btn btn-info" href="{{route('admin.todo.random')}}">Přeskočit a načíst něco jiného</a>
    <a class="btn btn-info" href="{{route('admin.todo')}}">Návrat na seznam</a>
    <hr>

    <h2>Štítkování YT videa</h2>

    <a href="{{route('admin.videos')}}">Zpět do administrace</a>

    <div class="row">
        <div class="col-sm-4">
            {!! $video->getHtml() !!}

            <form action="{{route('admin.video.edit.save')}}" method="post">
                @csrf
                <input autofocus name="url" placeholder="URL videa na YT" value="{{$video->url}}"><br>
                <input type="hidden" name="id" value="{{$video->id}}">

                <input type="submit" value="Uložit nové URL">
            </form>
        </div>
        <div class="col-sm-4">
            <iframe height="500" src="{{route('admin.video.edit.author',['id'=>$video->id])}}"></iframe>
        </div>
        <div class="col-sm-4">
            <iframe height="500" src="{{route('admin.video.edit.translation',['id'=>$video->id])}}"></iframe>
            <a class="btn btn-success" href="{{route('admin.todo.random')}}">Hotovo</a>
        </div>


    </div>

@endsection
