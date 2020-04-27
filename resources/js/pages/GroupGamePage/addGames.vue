<template>
    <div>
        <div class="row" >
           <div class="col-lg-2 col-md-2 col-sm-0"></div>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <multiselect
                    v-model="value"
                    :options="nonGroupGames"
                    :options-limit="20"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :hide-selected="false"
                    :preserve-search="true"
                    placeholder="Add the selected game"
                    label="full_name"

                    track-by="full_name"
                    @select="addGame"
                >
                    <template slot="noResult"><strong>Your game wasn't found, at the bottom of the game list is a option to add a new game to the site</strong></template>
                </multiselect>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';


    //https://vue-multiselect.js.org/v1/index.html#props
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect,
        },

         data () {
            return {
                'action': '',
                'formData': new FormData(),
               'value': '',
            }
         },

         props: {
            'group': {},
            'nonGroupGames': {},
         },

        methods: {
            test(){
                console.log("TEST");
            },

            addGame(selectedGame){
                this.formData.set('group_id', this.group.id);
                this.formData.set('game_id', selectedGame.id);
                this.action = "group/" + this.group.id + "/group-game";

                apiCall.postData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadGroupGames');
                    this.$bus.$emit('reloadGroups');
                    this.message = "You've added " + selectedGame.full_name+ " to " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();

                    this.removeGameFromMultiselect(selectedGame);
                    this.value='';
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
            }
        },

        mounted(){

        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">

</style>

