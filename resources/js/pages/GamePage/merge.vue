<template>
    <div>
        <hr>
        <form @submit.prevent="update" method="POST" enctype="multipart/form-data" v-if="allGames">
            <!-- the form items -->
            <global-layout  v-if="allGames.data">
                <div class="form-floating  mb-3">
                    <multi-select class="form-control"
                            v-model="chosenGame"
                            :open-direction="'bottom'"
                            :multiple="false"
                            :options="allGames.data"
                            :close-on-select="true"
                            :clear-on-select="true"
                            placeholder="Select the game"
                            label="full_name"
                            track-by="full_name"
                            id="game"
                        >
                        </multi-select>
                    <label>Select the game: </label>
                </div>
            </global-layout>
            <global-layout center="center">
                <button class="btn btn-primary">Merge game</button>
            </global-layout>
        </form>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import multiSelect from '@suadelabs/vue3-multiselect';

    export default {
        components: {
            multiSelect,
        },

         data () {
            return {
                'chosenGame': "",
            }
        },

        props: {
            'selectedGame': {},
            'currentPage': 0,
            'itemsPerPage': 0
         },

        computed: {
            allGames(){
                if(this.$store.state.allGames.data == undefined ){
                     this.$store.dispatch('getAllGames');
                }
                return this.$store.state.allGames;
            },
        },

        methods: {
            update(){
                if(this.chosenGame.id > 0){
                    apiCall.updateData('merge/' + this.selectedGame.id  + '/game/' + this.chosenGame.id)
                    .then(response =>{
                        var message = this.selectedGame.full_name + " is merged with " +  this.chosenGame.full_name;
                        this.$store.dispatch('getMessage', {message: message});
                        this.$store.dispatch('getUnapprovedGames', {currentPage: 0 });
                        this.$store.dispatch('getGames', {currentPage: this.currentPage, itemsPerPage: this.itemsPerPage});
                    }).catch(error => {
                        console.log(error);
                            console.log('listUnapprovedGames: handle server error from here');
                    });
                }
            },
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>
