@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>Nový uživatel</h2>
        <div class="row">
            <div class="col-sm-12">
                <form action="{{route('admin.user.store')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="email" type="email" placeholder="email"><br>
                    </div>

                    {{-- <div class="input-group"> --}}
                        <button type="submit" class="btn btn-outline-primary" name="redirect" value="edit">Uložit a upravit</button>
                    {{-- </div> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
