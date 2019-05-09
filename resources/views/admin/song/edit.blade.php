@extends('layout.admin')

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

                    <label>Autoři (u překladu uvést autora překladu)</label><br>

                    @include('admin.components.magicsuggest', [
                        'field_name' => 'authors',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $all_authors,
                        'list_selected' => $assigned_authors,
                        'is_single' => false,
                        'disabled' => false,
                        'allow_free_entries' => Auth::user()->can('add authors')
                    ])
                    <br>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" {{ $song_lyric->has_anonymous_author ? 'checked' : "" }}
                            name="has_anonymous_author" id="check_has_anonymous_author" value="1">
                        <label class="form-check-label" for="check_has_anonymous_author">
                            Autor neznámý
                            @can('access todo')
                             (nezobrazovat v to-do listu)
                            @endcan
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
                        @if ($song_lyric->is_original)
                            <p>Píseň je označena jako originál následujících písní: </p>
                        @else
                            <p>Píseň je označena jako verze následujících písní: </p>
                        @endif
                        @foreach ($song_lyric->getSiblings()->get() as $item)
                            {{ $item->name }}<br/>
                        @endforeach
                        <br/>
                    @else
                        <label>Jedná se o verzi následující písně:</label>
                        @include('admin.components.magicsuggest', [
                            'field_name' => 'assigned_song_lyrics',
                            'value_field' => 'id',
                            'display_field' => 'name',
                            'list_all' => $all_song_lyrics,
                            'list_selected' => $assigned_song_lyrics,
                            'is_single' => true,
                            'disabled' => $assigned_song_disabled
                        ])
                        <br>
                        {{-- checkbox for linking --}}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="set_linked_dong" id="exampleRadios1" value="do_nothing" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Neprovádět nic
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="set_linked_dong" id="exampleRadios2" value="set_original">
                            <label class="form-check-label" for="exampleRadios2">
                                Zajistit, aby nalinkovaná písnička byla označena jako originál
                            </label>
                          </div>
                          <div class="form-check disabled">
                            <input class="form-check-input" type="radio" name="set_linked_dong" id="exampleRadios3" value="set_translation">
                            <label class="form-check-label" for="exampleRadios3">
                                Zajistit, aby nalinkovaná písnička byla označena jako překlad
                            </label>
                          </div>
                        <br>
                    @endif

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

                    <label>Píseň je vhodná pro následující části mše sv.:</label>
                    @include('admin.components.magicsuggest', [
                        'field_name' => 'official_tags',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $official_tags,
                        'list_selected' => $assigned_official_tags,
                        'is_single' => false,
                        'disabled' => false,
                        'allow_free_entries' => false
                    ])
                    <br>

                    <label>Uživatelské štítky:@can('manage tags') (lze přidávat nové)@endcan</label>
                    @include('admin.components.magicsuggest', [
                        'field_name' => 'unofficial_tags',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $unofficial_tags,
                        'list_selected' => $assigned_unofficial_tags,
                        'is_single' => false,
                        'disabled' => false,
                        'allow_free_entries' => Auth::user()->can('manage tags')
                    ])
                    <br>


                    <label>Jazyk</label>
                    <select class="custom-select" name="lang" title="">
                        @foreach(\App\SongLyric::$lang_string as $key => $value)
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

                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="save">Uložit</button>
                    @if (!$song_lyric->is_published)
                        @can('publish songs')
                            <button type="submit" class="btn btn-outline-secondary" name="redirect" value="save_publish">Uložit a schválit k publikaci</button>
                        @endcan
                    @endif
                    @if (!$song_lyric->is_approved_by_author &&
                            Auth::user()->can('approve songs'))
                          {{--    (Auth::user()->can('approve songs') || SongLyric::forceRestricted()->where('id', $song_lyric->id)->count() > 0)) --}}
                            {{-- todo: enable editors with associated authors approve songs as well--}}
                        @can('approve songs')
                            <button type="submit" class="btn btn-outline-secondary" name="redirect" value="save_approve">Uložit a autorsky schválit</button>
                        @endcan
                    @endif
                    <br>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="save_show">Uložit a zobrazit ve zpěvníku</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="add_external">Uložit a přidat externí odkaz</button>
                    <button type="submit" class="btn btn-outline-primary" name="redirect" value="add_file">Uložit a přidat soubor</button>
                </form>

                @include('admin.components.deletebutton', [
                    'url' => route('admin.song.destroy', $song_lyric),
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
                <p>Začněte zadávat jméno autora (textu nebo hudby) a pokud se vám během psaní zobrazí vyskakovací nabídka s hledaným jménem,
                    tak jej označte kliknutím nebo Enterem. Pokud se autor v nabídce nenachází, znamená to, že ještě nebyl přidán do databáze.
                    @can('add authors')To ale ničemu nevadí, stačí správně napsat jméno (resp. více jmen), potvrdit Enterem
                    a autor (autoři) se po uložení písně automaticky vytvoří.
                    @else Je potřeba požádat administrátory o vytvoření nového autora @endcan
                    <br>
                    V současné verzi zpěvníku pro jednoduchost zatím nerozlišujeme vztah autora k písni.
                </p>

                @if ($assigned_song_disabled)
                    @if ($song_lyric->is_original)
                        <h5>Originál</h5>
                        <p>Píseň je označena jako originál následujících písní: </p>
                    @else
                        <h5>Verze</h5>
                        <p>Píseň je označena jako verze následujících písní (zřejmě není známo, která z nich je originál): </p>
                    @endif
                @else
                    <h5>Verze</h5>
                    <p>V políčku zvolte píseň, ke které se aktuálně upravovaná nějakým z následujících způsobů vztahuje:</p>
                    <ul>
                        <li>originál-překlad</li>
                        <li>verze-verze</li>
                    </ul>
                    {{-- <p>(originál-překlad nebo verze-verze pokud není znám originál)</p> --}}
                    Ve zpěvníku také chceme rozlišovat překlady, které jsou (nějakým způsobem) schváleny jako oficiální, pokud tedy víte, o co jde, tak upravte hodnotu v políčku <i>Autorizovaný překlad</i>.
                    <br><br>
                @endif

                <h5>Text</h5>

                <p>Text písně je možné zadávat i s akordy v tzv. formátu ChordPro. Tedy např. <b>[E], [C#m] nebo [Cism], [Fmaj7]</b> apod.
                    <br>Akordy pište českými značkami: H dur: <b>[H]</b>, B dur: <b>[B]</b>, B moll: <b>[Bm]</b>
                    <br>Akordy v pozdějších slokách nepište přímo - můžete je označovat zástupným znakem [%], nakopírují se automaticky z první sloky
                    <br>Sloky označujte číslicí, tečkou a mezerou: 1. Text první sloky
                    <br>Refrén velkým R, dvojtečkou a mezerou: R: Text refrénu (při opakování už nepsat znovu text)
                    <br>Bridge velkým B, dvojtečkou a mezerou: B: Text bridge
                    <br>Coda velkým C, dvojtečkou a mezerou: C: Text cody
                </p>

                @if (isset($score_file))
                    <h5>Nahrané noty - náhled ({{ $score_file->getPublicName() }})</h5>
                    <a href="{{ $score_file->download_url }}"><img src="{{ $score_file->thumbnail_url }}" alt="noty_náhled" class="img-fluid mb-4"></a>
                @elseif (isset($score_external))
                    <h5>Externí noty - náhled ({{ $score_external->getPublicName() }})</h5>
                    <a href="{{ $score_external->download_url }}"><img src="{{ $score_external->thumbnail_url }}" alt="noty_náhled" class="img-fluid mb-4"></a>
                @endif

                @if ($song_lyric->externals()->count() + $song_lyric->files()->count())
                    <h5>Přehled všech materiálů</h5>
                    <ul>
                        @foreach ($song_lyric->externals as $external)
                            <li>Externí odkaz ({{ $external->type_string }}): <a target="_blank" href="{{ route('admin.external.edit', $external) }}">{{ $external->url }}</a></li>                    
                        @endforeach
                        @foreach ($song_lyric->files as $file)
                            <li>Soubor ({{ $file->type_string }}): <a target="_blank" href="{{ route('admin.file.edit', $file) }}">{{$file->getPublicName()}}</a></li>                    
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection


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
            // Alt + = shortcut
            if (e.altKey && e.which == 187) {
                input_lyrics.value += "[%]";
            }
        }
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