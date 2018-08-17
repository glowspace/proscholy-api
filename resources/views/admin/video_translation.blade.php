<h2>Zvolte překlad, ke kterému video patří.</h2>

@foreach($translations as $translation)
    <a href="{{route('admin.video.edit.translation.save',['id'=>$video->id, 't_id'=>$translation->id])}}">{{$translation->name}}</a><br>
@endforeach
