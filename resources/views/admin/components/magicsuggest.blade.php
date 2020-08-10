<input id="{{$field_name}}_magicsuggest" class="form-control" name="{{$field_name}}[]"/>

@push('scripts')
    <script>
        $(function() {
            Object.getPrototypeOf($("#{{$field_name}}_magicsuggest")).size = function () { return this.length; }
            $("#{{$field_name}}_magicsuggest").magicSuggest({
                data: @json($list_all),
                valueField: "{{ $value_field }}",
                displayField: '{{ $display_field}}',
                @if($is_single)
                    maxSelection: 1,
                @endif
                @if(isset($allow_free_entries) && $allow_free_entries == false)
                    allowFreeEntries: false,
                @else
                    allowFreeEntries: true,
                @endif
                value: @json($list_selected),
                useCommaKey: false,
                disabled: {{ $disabled ? "true" : "false" }}
            });
        });
    </script>
@endpush

@pushonce('head_links:magicsuggest')
    <link href="{{asset('_admin/css/magicsuggest.css')}}" rel="stylesheet">
@endpushonce

@pushonce('scripts:magicsuggest')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('_admin/js/magicsuggest.js')}}"></script>
@endpushonce
