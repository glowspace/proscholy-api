@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Nový externí zdroj</h2>

        <div class="row">
            <div class="col-sm-6">
                    
                <a href="{{route('admin.external.index')}}">Zpět do administrace</a>
                {{-- <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a> --}}
        
                <form action="{{route('admin.external.create')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input class="form-control" required autofocus name="url" placeholder="Adresa URL zdroje">
                    </div>
        
                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Typ odkazu</label>
                        </div>
                        <select class="custom-select" name="type" title="">
                            <option value="0">youtube</option>
                            <option value="1">spotify</option>
                        </select>
                    </div>
        
                    <div class="input-group">
                        <button type="submit" name="redirect" value="create" class="btn btn-outline-primary mr-3">Uložit</button>
                        <button type="submit" name="redirect" value="edit" class="btn btn-outline-primary">Uložit a upravit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
