<template>
    <tr style="transform:scale(1)">
        <td>
            <a v-if="!percent && link" :href="'/admin/' + link" class="stretched-link" v-html="title"></a>
            <span v-else v-html="title"></span>
        </td>
        <td><span v-if="percent">{{ percent }}&nbsp;%</span></td>
        <td style="width: 50%">
            <div class="progress rounded" style="height:15px" v-if="percent">
                <div class="progress-bar rounded"
                    role="progressbar"
                    :style="'width:' + percent + '%'"
                    :aria-valuenow="percent"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>
            </div>
        </td>
        <td>
            <b v-html="number"></b>
        </td>
        <td>
            <a v-if="link && percent" :href="'/admin/' + link" class="stretched-link">doplnit</a>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['title', 'link', 'number', 'percent']
}
</script>
