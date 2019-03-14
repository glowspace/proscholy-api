<form action="{{ $url }}" method="post" onsubmit="return askForm(this)" class="mb-0">
    @csrf
    @method("delete")

    @if (isset($redirect))
        <input type="hidden" name="redirect" value="{{ $redirect }}">
    @endif

    <button type="submit" class="{{ $class ?? "btn btn-warning" }}">Vymazat</button>
</form>