@extends('layout.admin')

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

                    <p>Tento štítek je nastaven jako: {{ $tag->getTypeText() }}</p>
                    <br>

                    @if ($tag->child_tags()->count() > 0)
                        <p>Tento štítek je nadřazen následujícím štítkům:</p>
                        <ul>
                            @foreach ($tag->child_tags as $child)
                                <li>{{ $child->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        @if ($tag->type == 0)
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
                        @endif
                    @endif

                    <br>
                    
                    <label>Popis štítku</label>
                    <textarea rows="20" name="description" class="form-control" title="">{{$tag->description}}</textarea>
                    <br/>

                    <div class="input-group">
                        <button type="submit" class="btn btn-outline-primary">Uložit</button>
                    </div>
                </form>

                @include('admin.components.deletebutton', [
                    'url' => route('admin.tag.destroy', $tag),
                    'class' => 'btn btn-outline-warning',
                    'redirect' => route('admin.tag.index')
                ])
            </div>
        </div>
    </div>
@endsection

