<form action="{{ $url }}" method="post" onsubmit="return askForm(this)" class="mb-0">
    @csrf
    @method("delete")

    @if (isset($redirect))
        <input type="hidden" name="redirect" value="{{ $redirect }}">
    @endif

    <button type="submit" class="{{ $class ?? "btn btn-warning" }}">Vymazat</button>
</form>

@pushonce('scripts:deletebutton')
	<script>
		// function askDelete(url){
		// 	if (confirm("Opravdu chcete smazat daný záznam?")){
		// 		window.location = url;
		// 	}
		// }

		function askForm(form){
			return confirm('Opravdu chcete smazat daný záznam?');
		}
	</script>
@endpushonce