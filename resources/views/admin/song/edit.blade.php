@extends('admin.layout')

@section('content')
    <div class="content-padding">

        <div class="row">
            <div class="col-sm-6">
                <h2>Úprava písně</h2>
                <form action="{{ route('admin.song.update', ['song_lyric' => $song_lyric->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" value="{{$song_lyric->id}}">

                    <label>Název</label>
                    <input class="form-control" required autofocus name="name" placeholder="Název písně" value="{{$song_lyric->name}}"><br>

                    <label>Autoři</label><br>

                    @include('admin.components.magicsuggest', [
                        'field_name' => 'authors',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $all_authors,
                        'list_selected' => $assigned_authors,
                        'is_single' => false,
                        'disabled' => false
                    ])
                    <br>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" {{ $song_lyric->has_anonymous_author ? 'checked' : "" }}
                            name="has_anonymous_author" id="check_has_anonymous_author" value="1">
                        <label class="form-check-label" for="check_has_anonymous_author">
                            Autor neznámý (nezobrazovat v to-do listu)
                        </label>
                    </div>
                    <br>

                    <label>Typ</label>
                    <select class="form-control" name="is_original" title="">
                        <option value="1" @if($song_lyric->is_original)
                        selected
                                @endif>Originál
                        </option>
                        <option value="0" @if(!$song_lyric->is_original)
                        selected
                                @endif>Překlad
                        </option>
                    </select>
                    <br>

                    @if ($assigned_song_disabled)
                        <p>Píseň je označena jako originál následujících písní: </p>
                        @foreach ($song_lyric->getSiblings()->get() as $item)
                            {{ $item->name }}<br/>
                        @endforeach
                        <br/>
                    @else
                    <label>Jedná se o překlad následující písně:</label>
                        @include('admin.components.magicsuggest', [
                            'field_name' => 'assigned_song_lyrics',
                            'value_field' => 'id',
                            'display_field' => 'name',
                            'list_all' => $all_song_lyrics,
                            'list_selected' => $assigned_song_lyrics,
                            'is_single' => true,
                            'disabled' => $assigned_song_disabled
                        ])
                    @endif
                    <br>

                    <label>Autorizovaný překlad</label>
                    <select class="form-control" name="is_authorized" title="">
                        <option value="1" @if($song_lyric->is_authorized)
                        selected
                                @endif>Ano
                        </option>
                        <option value="0" @if(!$song_lyric->is_authorized)
                        selected
                                @endif>Ne
                        </option>
                    </select>
                    <br>
                    <label>Jazyk</label>
                    <select class="custom-select" name="lang" title="">
                        @foreach($song_lyric->lang_string as $key => $value)
                            <option value="{{ $key }}" {{ $song_lyric->lang === $key ? 'selected' : "" }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    <br>

                    <br>

                    <label>Text</label>
                    {{-- opensong uploading --}}
                    <a id="file_select" class="btn btn-primary">Nahrát ze souboru OpenSong</a>
                    <input type="file" class="d-none" id="input_opensong" onchange="handleOpensongFile(this.files)">

                    <textarea rows="20" name="lyrics" class="form-control" title="" id="input_lyrics">{{$song_lyric->lyrics}}</textarea>

                    <br>

                    {{-- <input class="btn btn-outline-primary" type="submit" value="create"> --}}

                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="save">Uložit</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="save_show">Uložit a zobrazit ve zpěvníku</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="add_external">Uložit a přidat externí odkaz</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="add_file">Uložit a přidat soubor</button>
                </form>

                @include('admin.components.deletebutton', [
                    'url' => route('admin.song.delete', ['song_lyric' => $song_lyric->id]),
                    'class' => 'btn btn-outline-warning',
                    'redirect' => route('admin.song.index')
                ])
            </div>
            <div class="col-sm-6 edit-description">
                <h2>Pár informací</h2>

                <h5>Název (povinná položka)</h5>
                <p>Název písně ve zvoleném jazyce (anglická píseň tedy bude mít anglický název). Může obsahovat název interpreta v závorkách, pokud existuje
                    více písní se stejným názvem.<br>
                    Konvence u anglických názvů je psaní všech slov kromě předložek velkými písmeny.
                </p>

                <h5>Autoři</h5>
                <p>Začněte zadávat jméno autora (textu, hudby, interpreta, ...) a pokud se vám během psaní zobrazí vyskakovací nabídka s hledaným jménem,
                    tak jej označte kliknutím nebo Enterem. Pokud se autor v nabídce nenachází, znamená to, že ještě nebyl přidán do databáze. To ale ničemu nevadí,
                    stačí správě napsat jméno (resp. více jmen), potvrdit Enterem a autor (autoři) se po uložení písně automaticky vytvoří.<br>
                    V současné verzi zpěvníku pro jednoduchost zatím nerozlišujeme vztah autora k písni.
                </p>

                @if ($assigned_song_disabled)
                    <h5>Překlad</h5>
                    <p>Tato písnička již je označená jako originál několika dalších písniček (viz vlevo). Propojení můžete smazat v editaci těchto písniček.</p>
                @else
                    <h5>Překlad</h5>
                    <p>Jako příklad nechť poslouží písnička Oceans od kapely Hillsong, která má hned několik českých překladů.</p>
                    <p>
                        Pokud právě přidáváte originál písničky (tedy Oceans / Hillsong), je třeba krom obvyklého navíc v políčku <i>Typ</i> změnit hodnotu na <i>Originál</i>.
                    </p>
                    <p>Pokud se jedná o píseň, která byla přeložena, popř. zaranžována ze známého originálu (Oceány / Adorare),
                        uveďte prosím originál (Oceans) do druhého políčka, abychom věděli, co k čemu patří.
                        <br>
                        Stejně jako u přidávání autorů se vám během psaní začnou zobrazovat již uložené písničky. Pokud originál (Oceans) nenajdete, tak po zadání celého jména
                        stiskněte Enter a po dokončení editace se zároveň vytvoří nová píseň (Oceans), která bude automaticky označena jako originál.
                        <br>
                        Ve zpěvníku také chceme rozlišovat překlady, které jsou (nějakým způsobem) schváleny jako oficiální, pokud tedy víte, o co jde, tak upravte hodnotu v políčku <i>Autorizovaný překlad</i>.
                    </p>
                @endif

                <h5>Text</h5>

                <p>Text písně je možné zadávat i s akordy v tzv. formátu ChordPro. Tedy např. <b>[E], [Cm], [Emaj7]</b> apod.
                    <br>Akordy pojmenovávejte českými značkami: H dur: <b>[H]</b>, B dur: <b>[B]</b>, B moll: <b>[Bm]</b>
                    <br>Sloky označujte číslicí, tečkou a mezerou: 1. Text první sloky
                    <br>Refrén velkým R, dvojtečkou a mezerou: R: Text refrénu (při opakování už nepsat znovu text)
                    <br>Bridge velkým B, dvojtečkou a mezerou: B: Text bridge
                </p>
            </div>
        </div>
    </div>
@endsection

@include('admin.components.magicsuggest_includes')
@include('admin.components.deletebutton_includes')

@push('scripts')
    {{-- each 25 seconds send a GET request in order to preserve the lock state --}}
    <script>
        $(document).ready(function() {
            setInterval(function() {
                $.get( "{{ route('admin.song.refresh_updating', $song_lyric) }}")
            }, 25000);
        });

        const input_lyrics = document.getElementById('input_lyrics');
        input_lyrics.onkeyup = function(e) {
            if (e.altKey && e.which == 189) {
                input_lyrics.value += "[%]";
            }
        }

        // $('#input_lyrics').keypress(function(e) {
        //     var c = String.fromCharCode(e.which);
        //     if (c.toUpperCase() === c && c.toLowerCase() !== c && !e.shiftKey) {
        //         $('#message').show();
        //     } else {
        //         $('#message').hide();
        //     }
        // });
    </script>

    {{-- handle opensong file uploading --}}
    <script>
        const file_select = document.getElementById('file_select'),
            input_opensong = document.getElementById('input_opensong');

        file_select.addEventListener('click', function (e) {
            if (input_opensong) {
                input_opensong.click();
            }
        }, false);


        function handleOpensongFile(files) {
            file = files[0];

            var reader = new FileReader();
            reader.onload = function(e) {
                console.log("file loaded succesfully");

                $.post('{{ route("api.parse.opensong") }}', {
                    'file_contents': e.target.result,
                    "_token": "{{ csrf_token() }}"
                }, function onSuccess(data) {
                    var input_lyrics = document.getElementById('input_lyrics');
                    input_lyrics.value = data;
                });
            };

            reader.readAsText(file);
        }
    </script>

    {{-- <script>
        function onKeyDown(){
            // TODO implement a shortcut for inserting a chord in brackets [] or deleting a chord if the cursor is inside brackets
            // TODO implement a shortcut for jumping between chords - i.e. ctrl + something
        }
    </script> --}}
@endpush