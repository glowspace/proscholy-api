<form action="{{ $url }}" method="post" onsubmit="return askForm(this)">
    @csrf
    @method("delete")

    <button type="submit" class="btn btn-warning">Vymazat</button>
</form>