@extends('admin.layout')

@section('content')
    <div class="content-padding">
    <h2>Úprava štítku</h2>
        <div class="row">
            <div class="col-sm-6">
                <form action="{{ route('admin.tag.update', ['tag' => $tag->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="name" placeholder="název štítku (v množném čísle - např. dětské písně)" value="{{$tag->name}}">
                    </div>

                    @can('manage official tags')
                        <div class="input-group mb-3">
                            <div class="input-group-append mr-3">
                                <label class="input-group-text">Typ štítku</label>
                            </div>
                            <select class="custom-select" name="type" title="">
                                @foreach($tag->type_string as $key => $value)
                                    <option value="{{ $key }}" {{ $tag->type ===  $key  ? 'selected' : "" }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan

                    <label>Rodičovský štítek:</label>
                    @include('admin.components.magicsuggest', [
                        'field_name' => 'parent_tag',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $available_tags,
                        'list_selected' => $parent_tag,
                        'is_single' => true,
                        'disabled' => false
                    ])
                    <br>
                    
                    <label>Popis štítku</label>
                    <textarea rows="20" name="description" class="form-control" title="">{{$tag->description}}</textarea>
                    <br/>

                    <div class="input-group">
                        <button type="submit" class="btn btn-outline-primary">Uložit</button>
                    </div>
                </form>

                @include('admin.components.deletebutton', [
                    'url' => route('admin.tag.delete', $tag),
                    'class' => 'btn btn-outline-warning',
                    'redirect' => route('admin.tag.index')
                ])
            </div>
        </div>
    </div>
@endsection

