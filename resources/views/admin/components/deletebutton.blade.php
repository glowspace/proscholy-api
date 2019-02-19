<form action="{{ $url }}" method="post" onsubmit="return askForm(this)">
    @csrf
    @method("delete")

    @if (isset($redirect))
        <input type="hidden" name="redirect" value="{{ $redirect }}">
    @endif

    <button type="submit" class="{{ $class }}">Vymazat</button>
</form>