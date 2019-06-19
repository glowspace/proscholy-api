@extends('layout.admin')

@section('content')
    <div class="content-padding">
        <div class="row">
            <div class="col-md-6">
                @if (isset($song_lyric))
                    <h2>Je třeba chvilku počkat..</h2>
                    <p>Vypadá to, že píseň {{ $song_lyric->name }} právě upravuje někdo jiný.
                    <br>Abychom předešli možným problémům, tak je úprava dočasně uzamčená.
                    <br><br>
                    <a class="btn btn-outline-primary" href="{{ route('admin.song.edit', $song_lyric) }}">ZKUSIT ZNOVU UPRAVIT</a>
                    <br><br>
                    <img src="https://thumbs.gfycat.com/FoolishHonorableArgentinehornedfrog-size_restricted.gif" alt="You shall not pass">
                    </p>
                @endif

                @if (isset($songbook))
                    <h2>Je třeba chvilku počkat..</h2>
                    <p>Vypadá to, že zpěvník {{ $songbook->name }} právě upravuje někdo jiný.
                    <br>Abychom předešli možným problémům, tak je úprava dočasně uzamčená.
                    <br><br>
                    <a class="btn btn-outline-primary" href="{{ route('admin.songbook.edit', $songbook) }}">ZKUSIT ZNOVU UPRAVIT</a>
                    <br><br>
                    <img src="https://thumbs.gfycat.com/FoolishHonorableArgentinehornedfrog-size_restricted.gif" alt="You shall not pass">
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
