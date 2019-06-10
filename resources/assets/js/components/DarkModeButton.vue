<template>
	<a tabindex="0" v-on:click="darkModeToggle" class="btn btn-secondary dark-mode-button"><i class="fas fa-moon"></i>{{ text }}</a>
</template>

<script>
    export default {
        props: ["text"],

        methods:{
            darkModeToggle: function() {
				document.getElementsByTagName("body")[0].classList.toggle("dark");
                if (window.localStorage) {
                    if (localStorage.getItem("dark") === "true") {
                        localStorage.setItem("dark", false);
                    } else {
                        localStorage.setItem("dark", true);
                    }
                }
            },
            darkModeInit: function() {
                if (window.localStorage) {
                    if (localStorage.getItem("dark") === "true") {
                        document.getElementsByTagName("body")[0].className = "dark";
                    }
                }
            }
        },

        created(){
            this.darkModeInit()
        }
    }
</script>
