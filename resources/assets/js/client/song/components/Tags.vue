<template>
    <div class="song-tags p-0 mt-3">
        <div class="d-inline-flex flex-row flex-wrap align-items-start mr-3"
             v-if="tags.officials.length && tags.unofficials.length">
            @foreach ($tags_officials as $tag)
            <a class="tag tag-blue" v-for="tag in tags_officials"
               href="{{route("
               client.search_results")}}?searchString=&tags={{ $tag->id }}&langs=&songbooks=">{{ $tag->name
            }}</a>
            @endforeach
            @foreach ($tags_unofficials as $tag)
            @if ($tag->parent_tag == null)
            {{-- do not display the parent tag as for now --}}
            {{-- <a class="tag tag-green">{{ $tag->name }}</a> --}}
            @else
            <a class="tag tag-green"
               href="{{route("
               client.search_results")}}?searchString=&tags={{ $tag->id }}&langs=&songbooks=">{{ $tag->name
            }}</a>
            @endif
            @endforeach
        </div>

        <!-- Liturgy ČBK approval -->
        <div class="d-inline-flex flex-row flex-wrap align-items-start mr-3"
             v-if="song.liturgy_approval_status">
            <a class="tag tag-blue">{{song.liturgy_approval_status_string_values[song.liturgy_approval_status]}}
                <i class="fas fa-check"></i></a>
        </div>

        <div class="d-inline-flex flex-row flex-wrap align-items-start"
             v-if="songbook_records">
            @foreach ($songbook_records as $record)
            {{-- <a class="tag tag-yellow"
                    title="{{ $record->name }}"
                    href="{{route("
                    client.search_results")}}?searchString=&tags=&langs=&songbooks={{ $record->id }}">{{
            $record->name . ' ' . $record->pivot->number }}</a> --}}
            <a class="tag tag-yellow songbook-tag"
               title="{{ $record->name }}"
               href="{{route("
               client.search_results")}}?searchString=&tags=&langs=&songbooks={{ record.id
            }}"><span class="songbook-name">{{ $record->name }}</span><span class="songbook-number">{{ $record->pivot->number }}</span></a>
            @endforeach
        </div>

    </div>
</template>

<script>
    /**
     * Song tags component.
     *
     * It renders:
     * 1) related tags
     * 2) related songbooks
     */
    export default {
        name: "Tags",

        props: ['song']
    }
</script>

<style scoped>

</style>
