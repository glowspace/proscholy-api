@push('scripts')
	<script>
		function askDelete(url){
			if (confirm("Opravdu chcete smazat daný záznam?")){
				window.location = url;
			}
		}

		function askForm(form){
			return confirm('Opravdu chcete smazat daný záznam?');
		}
	</script>
@endpush