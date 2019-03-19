@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2></h2>
        @can('add tags')
            <a class="btn btn-outline-primary" href="{{route('admin.tag.create')}}">+ Nový štítek</a>
        @endcan
        <div class="row justify-content-between">
            <div class="col-xs-12 col-lg-6">
                <h2>{{ $title ?? "Seznam uživatelských štítků"}}</h2>
                @component('admin.components.table', [
                    'id' => 'unofficial_table',
                    'columns' => ['Jméno', 'Nadřazený štítek', 'Akce']
                ])
                    @foreach($tags_unofficials as $tag)
                    <tr>
                        <td>
                            @can('manage tags')
                                <a href="{{route('admin.tag.edit',['id'=>$tag->id])}}">{{$tag->name}}</a>
                            @else
                                {{$tag->name}}
                            @endcan
                        </td>
                        <td>
                            @if ($tag->parent_tag == NULL)
                                -
                            @else
                                {{ $tag->parent_tag->name }}
                            @endif
                        </td>
                        <td>
                            @can('manage tags')
                                @include('admin.components.deletebutton', [
                                    'url' => route('admin.tag.destroy',['tag' => $tag->id ]),
                                ])
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>

            <div class="col-xs-12 col-lg-6 col-xl-5">
                <h2>Seznam oficiálních štítků</h2>
                <h4>(tyto je možné spravovat pouze z administrátorského účtu)</h4>
                @component('admin.components.table', [
                    'id' => 'official_table',
                    'columns' => ['Jméno', 'Akce']
                ])
                    @foreach($tags_officials as $tag)
                    <tr>
                        <td><a href="{{route('admin.tag.edit',['id'=>$tag->id])}}">{{$tag->name}}</a></td>
                        <td>
                            @can('manage official tags')
                                @include('admin.components.deletebutton', [
                                    'url' => route('admin.tag.destroy',['tag' => $tag->id ]),
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

