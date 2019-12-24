<template>
    <div>
        <list-unapproved></list-unapproved>

        <hr>
        Game
        <hr>
        <button class="btn btn-primary" @click.prevent="CreateShow" v-show="display == ''">Create</button>
        <button class="btn btn-primary" @click.prevent="CreateHide" v-show="display == 'Create'">Hide create</button>
        <div v-show="display == 'Create'">
            <inputForm  v-if="display == 'Create'" :submitOption="'Create'" :basegame=basegame></inputForm>
        </div>
        <list></list>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';

    import list from '../GamePage/list.vue';
    import listUnapproved from '../GamePage/listUnapprovedGames.vue';
    import inputForm from '../GamePage/input';

    export default {
        components: {
            list,
            inputForm,
            listUnapproved,
        },

        data () {
            return {
                display: "",
                'basegame': {},
            }
        },

        methods: {
            CreateShow(){
                this.display = 'Create';
                this.loadList();
            },

            CreateHide(){
                this.display = '';
            },

             loadList(){
                apiCall.getData('basegame')
                .then(response =>{
                    this.basegame = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },
        },

        mounted(){
            this.loadList();
            this.$bus.$on('resetDisplay', () => {
                    this.display = '';
            });
        }
    }
</script>

<style scoped>

</style>
