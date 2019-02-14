@extends('layout.layout_old')

@section('content')
    @if(empty($song))
        <h2>Nový autor</h2>

        <div style="width: 400px; margin: 0 auto">
            <form action="{{route('admin.author.new.save')}}" method="post">
                @csrf
                <input class="form-control" autofocus name="name" placeholder="jméno autora"><br>
                <br>

                <label>Typ autora</label>
                <select name="type" title="">
                    <option value="0">autor</option>
                    <option value="1">hudební uskupení</option>
                    <option value="2">schola</option>
                    <option value="3">kapela</option>
                    <option value="4">sbor</option>
                </select>

                <br>
                <input type="submit">
            </form>
        </div>
    @else

    @endif

@endsection
