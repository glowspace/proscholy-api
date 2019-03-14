@extends('admin.layout')

@section('content')
    <div class="content-padding">
            <h2>{{ $title ?? "Seznam externích zdrojů"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.author.create')}}">+ Nový externí zdroj</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Odkaz', 'Akce']
                ])
                    @foreach($externals as $external)
                    <tr>
                        <td>
                            <a href="{{route('admin.external.edit', ['external'=>$external->id])}}">{{$external->generateTitle()}}</a>
                        </td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.external.delete', $external ),
                            ])
                        </td>
                    </tr>
                @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

