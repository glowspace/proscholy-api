<template>
    <div>
        <div>Velikost písma</div>
        <div class="btn-group m-0" role="group">
            <a
                class="btn btn-secondary"
                @click="setFontSizePercent(fontSizePercent - 10)"
                >-</a
            >
            <a
                class="btn btn-secondary bg-light transpose-window"
                @click="setFontSizePercent(100)"
                >{{ (fontSizePercent - 100) / 10 }}</a
            >
            <a
                class="btn btn-secondary"
                @click="setFontSizePercent(fontSizePercent + 10)"
                >+</a
            >
        </div>
    </div>
</template>

<script>
export default {
    props: ["value"],

    data() {
        return {
            fontSizePercent: 100,
            sl_doc: document.getElementById("song-lyrics"),
            sl_refresh_handler: null
        };
    },

    methods: {
        setFontSizePercent: function(val) {
            let sl = document.getElementById("song-lyrics");

            if (!sl.style.height) {
                sl.style.height = sl.clientHeight + "px";
            }

            if (this.sl_refresh_handler) {
                clearTimeout(this.sl_refresh_handler);
            }

            this.sl_refresh_handler = setTimeout(() => {
                sl.style.height = null;
            }, 1000);

            if (val > 70) {
                this.fontSizePercent = val;
                this.$emit("input", val);
            }
        }
    }
};
</script>
