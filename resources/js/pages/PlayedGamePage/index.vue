<template>
    <div class="container">
        <list :group=group :user=user></list>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import list from '../PlayedGamePage/list.vue';

    export default {
        components: {
            list,
        },

        props: {
            'group': {},
            'user': {},
        },

        data () {
            return {

            }
        },

        methods: {

        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>
