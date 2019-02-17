@extends('admin.layout')

@section('content')
    <div class="content-padding">
    <h2>Úprava autora</h2>
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('admin.author.update', ['author' => $author->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="name" placeholder="jméno autora" value="{{$author->name}}">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Typ autora</label>
                        </div>
                        <select class="custom-select" name="type" title="">
                            @foreach($author->type_string as $value)
                                <option value="{{$loop->iteration}}" {{ $author->type == $loop->iteration ? 'selected' : "" }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group">
                        <button type="submit" class="btn btn-outline-primary">Uložit</button>
                    </div>
                    {{-- <input type="submit" value="Uložit"> --}}
                </form>
            </div>
        </div>
    </div>
@endsection