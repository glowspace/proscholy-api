@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>Nový štítek</h2>
        <div class="row">
            <div class="col-sm-12">
                <form action="{{route('admin.tag.store')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="name" placeholder="název štítku (v množném čísle - např. dětské písně)"><br>
                    </div>

                    @can('manage official tags')
                        <div class="input-group mb-3">
                            <div class="input-group-append mr-3">
                                <label class="input-group-text">Typ štítku</label>
                            </div>
                            <select class="custom-select" name="type" title="">
                                @foreach(App\Tag::$type_string_values as $key => $value)
                                    <option value="{{ $key }}" {{ $key === 0  ? 'selected' : "" }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan

                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="create">Uložit</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="edit">Uložit a upravit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
