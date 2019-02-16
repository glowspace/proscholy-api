@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Úprava externího zdroje</h2>
            
        <a href="{{route('admin.externals')}}">Zpět do administrace</a>
        <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a>

        <div class="row">
            <div class="col-sm-4">
                {!! $external->getHtml() !!}

                <form action="{{route('admin.external.update')}}" method="post">
                    @csrf
                    @method('PUT')

                    <input autofocus name="url" placeholder="url odkaz"><br>

                    <label>Typ odkazu</label>
                    <select name="type" title="">
                        <option value="0">a</option>
                        <option value="1">b</option>
                    </select>

                    <input type="submit" value="Uložit nový zdroj">
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
