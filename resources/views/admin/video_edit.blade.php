@extends('layout.layout_old')

@section('content')

    <h2>Úprava videa</h2>

    <a href="{{route('admin.videos')}}">Zpět do administrace</a>
    <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a>

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
        </div>
    </div>

@endsection
