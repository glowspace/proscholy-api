@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam uživatelských štítků"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.tag.create')}}">+ Nový štítek</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'unofficial_table',
                    'columns' => ['Jméno', 'Typ', 'Nadřazený štítek', 'Akce']
                ])
                    @foreach($tags_unofficials as $tag)
                    <tr>
                        <td><a href="{{route('admin.tag.edit',['id'=>$tag->id])}}">{{$tag->name}}</a></td>
                        <td>{{ $tag->getTypeText() }}</td>
                        <td>
                            @if ($tag->parent_tag == NULL)
                                -
                            @else
                                {{ $tag->parent_tag->name }}
                            @endif
                        </td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.tag.delete',['tag' => $tag->id ]),
                            ])
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>
        </div>

        <h2>Seznam oficiálních štítků</h2>
        <h4>(tyto je možné spravovat pouze z administrátorského účtu)</h4>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'official_table',
                    'columns' => ['Jméno', 'Typ', 'Akce']
                ])
                    @foreach($tags_officials as $tag)
                    <tr>
                        <td><a href="{{route('admin.tag.edit',['id'=>$tag->id])}}">{{$tag->name}}</a></td>
                        <td>{{ $tag->getTypeText() }}</td>
                        <td>
                            @can('manage official tags')
                                @include('admin.components.deletebutton', [
                                    'url' => route('admin.tag.delete',['tag' => $tag->id ]),
                                ])
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

