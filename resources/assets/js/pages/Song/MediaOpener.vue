<template>
		<div class="card card-green mb-3" v-on:click="mediaDisplay=true; toolsDisplay=false">
			<div class="card-header media-opener py-2">
                <i class="fas fa-music"></i>
                Dostupné nahrávky a videa
            </div>
            <slot></slot>
		</div>
</template>

<script>
    import { store } from "Public/components/store.js";

    export default {
        data() {
            return store;
        }
    }
</script>
