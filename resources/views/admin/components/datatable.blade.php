@push('scripts')
    <script>
        $(document).ready( function () {
            $("#{{ $table_id }}").DataTable();
        } );

        $("#{{ $table_id }}").DataTable( {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Czech.json'
            }
        } );
    </script>
@endpush
