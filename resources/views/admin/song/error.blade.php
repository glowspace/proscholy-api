@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Musíme to opravit :(</h2>
        <p>Ok, přihodila se nám nemilá věc:</p>
        <form action="" method="post">
            @if ($error === App\Song::ERR_NO_ORIGINAL)
                <p>Vyskytlo se tu pár překladů jedné písničky, ale žádný z nich není označený jako originál:</p>
                @foreach ($song->song_lyrics as $song_l)
                    {{ $song_l->name }}<br>
                @endforeach
                <br>
                <p>Není to tak závačná chyba, aby se s tím něco muselo dělat.</p>
                <button type="input"></button>

            @elseif ($error === App\Song::ERR_MORE_ORIGINALS)
                <p>Vyskytl se nám tu problém, že máme označených víc originálů jedné písničky,<br>
                    konkrétně se jedná o následující položky:
                </p>
                @foreach ($song->song_lyrics()->where('is_original', 1)->get() as $song_l)
                    {{ $song_l->name }}<br>
                @endforeach
            @else
                {{-- this shouldn't really happen :)  --}}
                <p>no sám nevím..</p>
            @endif
        </form>
    </div>
@endsection
