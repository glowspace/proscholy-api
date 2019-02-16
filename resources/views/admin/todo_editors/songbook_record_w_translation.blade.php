@extends('layout.layout_old')

@section('content')
    <a class="btn btn-info" href="{{route('admin.todo.random')}}">Přeskočit a načíst něco jiného</a>
    <a class="btn btn-info" href="{{route('admin.todo')}}">Návrat na seznam</a>
    <hr>

    @if(isset($record->placeholder))
        <h2>Píseň ve zpěvníku {{$record->songbook->name }} - č.{{$record->number}} {{ $record->placeholder }}</h2>

        <p>Je přiřadit píseň k položce ve zpěvníku. Pokud není v seznamu, přidejte zde a přeskočte.</p>
    @else
        <h2>Píseň č. {!! $record->number !!} ve zpěvníku {{$record->songbook->name }}</h2>

        <p>Je potřeba zjistit, co je to za píseň. Pokud není v seznamu, přidejte ji v okně vlevo a klikněte na přeskočit.</p>
    @endif

    <div class="row">
        <div class="col-md-6">
            <iframe width="100%" height="600" src="{{route('admin.song.new')}}">

            </iframe>
        </div>
        <div class="col-md-6">
            <div style="height: 600px; overflow-y: scroll;">
                @foreach($translations as $translation)
                    <a href="{{route('admin.todo.setRecordTranslation',['record_id'=>$record->id, 'translation_id'=>$translation->id])}}">{{$translation->name}}</a>
                    <br>
                @endforeach
            </div>
        </div>
    </div>

    {{--<form method="get"--}}
    {{--action="{{route('admin.todo.setNewRecordTranslation', ['record_id'=>$record->id]}}">--}}
    {{--<input value="" placeholder="jméno nového autora" name="translation_name">--}}
    {{--<input type="submit">--}}
    {{--</form>--}}






@endsection
