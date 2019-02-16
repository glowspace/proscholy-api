<h2>Zvolte překlad, ke kterému external patří.</h2>

@foreach($translations as $translation)
    <a href="{{route('admin.external.edit.translation.save',['id'=>$external->id, 't_id'=>$translation->id])}}">{{$translation->name}}</a><br>
@endforeach
