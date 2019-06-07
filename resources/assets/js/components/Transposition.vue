<template>
    <span>
        <span v-if="chordMode !== 0">
			<div class="d-inline-block m-1 py-1 px-2 border rounded text-center">
				<div>Transpozice</div>
				<div class="btn-group m-0" role="group">
					<a class="btn btn-secondary" v-on:click="transpose(-1)">-</a>
					<a class="btn btn-secondary bg-light transpose-window" v-on:click="transposition = 0; displayTransp = 0">{{ displayTransp }}</a>
					<a class="btn btn-secondary" v-on:click="transpose(1)">+</a>
				</div>
			</div>
			<div class="d-inline-block m-1 py-1 px-2 border rounded text-center">
				<div>Posuvky</div>
				<div class="btn-group m-0 bg-light" role="group">
					<a class="btn btn-secondary"
					v-bind:class="{'chosen': !useFlatScale}" v-on:click="useFlatScale = false">#</a>
					<a class="btn btn-secondary"
					v-bind:class="{'chosen': useFlatScale}" v-on:click="useFlatScale = true">♭</a>
				</div>
			</div>
        </span>
        <span v-if="nChordModes > 1">
			<div class="d-inline-block m-1 py-1 px-2 border rounded text-center">
				<div>Akordy</div>
				<div class="btn-group m-0 bg-light" role="group">
					<a class="btn btn-secondary" v-bind:class="{'chosen': chordMode == 0}"
					v-on:click="chordMode = 0"><i class="far fa-eye-slash"></i></a>
					<a class="btn btn-secondary" v-bind:class="{'chosen': chordMode == 1}"
					v-on:click="chordMode = 1"><i class="far fa-eye"></i></a>
					<a class="btn btn-secondary" v-if="nChordModes > 2" v-bind:class="{'chosen': chordMode == 2}"
					v-on:click="chordMode = 2"><i class="fas fa-eye"></i></a>
				</div>
			</div>
        </span>
    </span>
</template>

<script>
    import { store } from "./store.js";

    export default {
        data() {
            return store;
        },

        methods:{
            transpose: function(val) {
                this.transposition = (this.transposition + val) % 12;
                this.displayTransp = (this.displayTransp + val) % 12;
                if (this.transposition < 0) {
                    this.transposition = 12 + this.transposition;
				}
            }
        }
    }
</script>
