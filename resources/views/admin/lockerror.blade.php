@extends('layout.admin')

@section('title-suffixed', 'Uzamčeno')

@section('content-withmenu')
    <div class="content-padding">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h2">Je třeba chvilku počkat…</h1>
                <p>
                    Vypadá to, že
                    {{ isset($song_lyric) ? 'píseň '.$song_lyric->name : (isset($songbook) ? 'zpěvník '.$songbook->name : '' ) }} právě upravuje někdo jiný.
                    <br>Abychom předešli možným problémům, dočasně jsme režim úprav uzamkli.
                    <br><br><a class="btn btn-outline-primary"
                        href="{{ isset($song_lyric) ? route('admin.song.edit', $song_lyric) : (isset($songbook) ? route('admin.songbook.edit', $songbook) : '' ) }}"
                    >Zkusit znovu</a>
                    <br><br><img src="https://thumbs.gfycat.com/FoolishHonorableArgentinehornedfrog-size_restricted.gif" alt="You shall not pass">
                </p>
            </div>
        </div>
    </div>
@endsection
