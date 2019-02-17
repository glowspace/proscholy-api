<input id="{{$field_name}}_magicsuggest" class="form-control" name="{{$field_name}}[]"/>

@push('scripts')
    <script>
        $(function() {
            Object.getPrototypeOf($("#{{$field_name}}_magicsuggest")).size = function () { return this.length; }
            $("#{{$field_name}}_magicsuggest").magicSuggest({
                data: @json($list_all),
                valueField: "{{ $value_field }}",
                displayField: '{{ $display_field}}',
                allowFreeEntries: true,
                value: @json($list_selected)
            });
        });
    </script>
@endpush