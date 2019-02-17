<h2>Zvolte autora videa</h2>

@foreach($authors as $author)
    <a href="{{route('admin.external.edit.author.save',['id'=>$external->id, 'a_id'=>$author->id])}}">{{$author->name}}</a><br>
@endforeach
