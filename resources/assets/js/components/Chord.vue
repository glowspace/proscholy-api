<template>
    <span class="chord">
        <span class="chord-sign">
            <span class="chord-base">{{base}}</span>
            <span class="chord-variant">{{variant}}</span>
            <span class="chord-bass" v-if="bass.length!==0">/{{bass}}</span>
        </span>
        <span class="chord-text"><slot></slot></span><span class="chord-dash" v-if="isDivided == 1">-</span>
    </span>
</template>

<style lang="scss">
    .chord{
        position: relative;
        transition: 100ms;
        display: inline-block;

        // padding-top: 1.2rem;

        &-sign{
            display: flex;
            justify-content: flex-start;
            transition: 100ms;
            margin-bottom: -0.3rem;
        }

        &-base{
            font-weight: bold;
            margin-right: 0.4rem;
        }

        &-variant{
            font-size: 0.8em;
            color: #0275d8;
            position: relative;
            left: -0.4em;
            top: -0.2em;
        }

        &-bass{
            font-weight: bold;
            // color: #adc6db;
            margin-right: 0.4rem;
            margin-left: -0.2rem;
            transition: 100ms;
        }

        &-text{
            display: inline-block;
            // background: white;
            position: relative;
        }

        &-dash{
            position: absolute;
            bottom: 0;
            display: none;
        }

        &:hover{
            // background: #d0e6f9;
            padding: 0rem 0.2rem 0rem 0.2rem;

            .chord-text{
                // background: #d0e6f9;
            }

            .chord-bass{
                color: black;
            }
        }
    }
</style>

<script>
    export default {
        props: ['base', 'variant', 'transposition', 'bass', 'text', 'isDivided']
    }
</script>
