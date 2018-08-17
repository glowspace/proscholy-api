<h2>Zvolte autora videa</h2>

@foreach($authors as $author)
    <a href="{{route('admin.video.edit.author.save',['id'=>$video->id, 'a_id'=>$author->id])}}">{{$author->name}}</a><br>
@endforeach
