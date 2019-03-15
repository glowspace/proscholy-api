@pushonce('head_links:datatable')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.css"/>
@endpushonce

@pushonce('scripts:datatable')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
@endpushonce

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