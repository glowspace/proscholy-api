@pushonce('head_links:datatable')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" />
@endpushonce

@pushonce('scripts:datatable')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
@endpushonce

@push('scripts')
    <script>
        $(document).ready( function () {
            $("#{{ $table_id }}").DataTable( {
                pageLength: 50,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Czech.json'
                }
            } );
        } );
    </script>
@endpush
