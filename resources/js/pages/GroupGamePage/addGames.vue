<template>
    <div>
        <global-layout  v-if="nonGroupGames.data">
                <div class="form-floating  mb-3">
                    <multi-select class="form-control"
                        v-model="chosenGame"
                        :multiple="false"
                        :options="nonGroupGames.data"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :hide-selected="false"
                        :preserve-search="true"
                        placeholder="Add the selected game"
                        label="full_name"
                        track-by="full_name"
                        @select="addGame"
                        @close="getRenewedGroupGames"
                    >
                    </multi-select>
                     <template slot="noResult"><strong>Your game wasn't found, at the bottom of the game list is a option to add a new game to the site</strong></template>
                    <label>Add the selected game: </label>
                </div>
            </global-layout>
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
                'formData': new FormData(),
                'chosenGame': '',
            }
        },

        props: {
            'group': {},
            'currentPage': '',
        },

        computed: {
            nonGroupGames(){
                if(this.$store.state.gamesNotInGroup.data == undefined){
                    this.$store.dispatch('getGamesNotInGroup', {groupId: this.group.id});
                }
                return this.$store.state.gamesNotInGroup;
            }
        },

        methods: {
            addGame(selectedGame){
                this.formData.set('group_id', this.group.id);
                this.formData.set('game_id', selectedGame.id);
                var link = "group/" + this.group.id + "/group-game";

                apiCall.postData(link, this.formData)
                .then(response =>{
                    var message = "You've added " + selectedGame.full_name+ " to " + this.group.name;
                    this.$store.dispatch('getMessage', {message: message});
                    this.formData =  new FormData();
                    this.removeGameFromMultiselect(selectedGame);
                    this.chosenGame='';
                }).catch(error => {
                    this.errors = error;
                });
            },

            removeGameFromMultiselect(selectedGame){
                var gameToRemove = "";
                this.nonGroupGames.forEach(function(item, index){
                    if(selectedGame['id'] == item.id){
                        gameToRemove = index;
                    }
                });
                this.nonGroupGames.splice(gameToRemove, 1);
            },

            getRenewedGroupGames(){
                this.$store.dispatch('getSelectedGroupGames', {groupId: this.group.id, pageItems: 20, currentPage: this.currentPage});
                this.$store.dispatch('getGamesNotInGroup', {groupId: this.group.id});
                this.chosenGame='';
            }
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>

