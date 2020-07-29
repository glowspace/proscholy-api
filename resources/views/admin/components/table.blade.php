<table class="table table-bordered {{ $class ?? '' }}" id="{{ $id }}" style="font-size:13px">
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        {{ $slot }}
    </tbody>

</table>

@include('admin.components.datatable', ['table_id' => $id])
