@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Úprava videa</h2>
            
        <a href="{{route('admin.externals')}}">Zpět do administrace</a>
        <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a>

        <div class="row">
            <div class="col-sm-4">
                {!! $external->getHtml() !!}

                <form action="{{route('admin.external.update')}}" method="post">
                    @csrf
                    <input autofocus name="url" placeholder="URL videa na YT" value="{{$external->url}}"><br>
                    <input type="hidden" name="id" value="{{$external->id}}">

                    <input type="submit" value="Uložit nové URL">
                </form>
            </div>
            <div class="col-sm-4">
                <iframe height="500" src="{{route('admin.external.edit.author',['id'=>$external->id])}}"></iframe>
            </div>
            <div class="col-sm-4">
                <iframe height="500" src="{{route('admin.external.edit.translation',['id'=>$external->id])}}"></iframe>
            </div>
        </div>
    </div>
@endsection
