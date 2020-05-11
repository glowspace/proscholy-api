<template>
    <tr>
        <td
            :class="[
                translation.lang == 'cs' ? 'invisible' : 'text-secondary',
                'text-right text-uppercase small align-middle pr-0'
            ]"
            :title="translation.lang_string"
        >
            {{ translation.lang }}
        </td>
        <td class="align-middle">
            <a
                :href="translation.public_url"
                :class="{
                    'font-weight-bolder': translation.name == original_name
                }"
                >{{ translation.name }}</a
            >
        </td>
        <td class="align-middle">
            <span class="d-none d-sm-inline">{{ typeString }}</span
            ><span class="d-sm-none">{{ typeChar }}</span>
        </td>
        <td class="align-middle">
            <span
                v-for="(author, authorIndex) in translation.authors"
                :key="authorIndex"
                ><span v-if="authorIndex">,</span>
                <a :href="author.public_url" class="text-secondary">{{
                    author.name
                }}</a>
            </span>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['translation', 'original_name'],
    computed: {
        typeString() {
            let typeStrings = ['originál', 'překlad', 'autorizovaný překlad'];
            return typeStrings[this.translation.type];
        },
        typeChar() {
            let typeChars = ['O', 'P', 'AP'];
            return typeChars[this.translation.type];
        }
    }
};
</script>
