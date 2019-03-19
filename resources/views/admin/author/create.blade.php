@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Nový autor</h2>
        <div class="row">
            <div class="col-sm-12">
                <form action="{{route('admin.author.store')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="name" placeholder="jméno autora"><br>
                    </div>

                    {{-- <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Typ autora</label>
                        </div>
                        <select class="custom-select" name="type" title="">
                            <option value="0">autor</option>
                            <option value="1">hudební uskupení</option>
                            <option value="2">schola</option>
                            <option value="3">kapela</option>
                            <option value="4">sbor</option>
                        </select>
                    </div> --}}

                    {{-- <div class="input-group"> --}}
                        <button type="submit" class="btn btn-outline-primary" name="redirect" value="create">Uložit</button>
                        <button type="submit" class="btn btn-outline-primary" name="redirect" value="edit">Uložit a upravit</button>
                    {{-- </div> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
