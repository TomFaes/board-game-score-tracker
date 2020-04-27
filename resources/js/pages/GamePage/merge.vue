<template>
    <div>
        <hr>
        <form @submit.prevent="update" method="POST" enctype="multipart/form-data" v-if="games">
            <!-- the form items -->
            <div class="row" >
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <label>Select game: </label>
                        <multiselect
                            v-model="chosenGame"
                            :open-direction="'bottom'"
                            :multiple="false"
                            :options="games"
                            :close-on-select="true"
                            :clear-on-select="true"
                            placeholder="Add the selected game"
                            label="full_name"
                            track-by="full_name"
                            id="game"
                        >
                        </multiselect>
                    </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div><br>
            <button-input btnClass="btn btn-primary">Merge game</button-input>
        </form>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import DropdownInput from '../../components/ui/form/DropdownInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            DropdownInput,
            ButtonInput,
            Multiselect,
        },

         data () {
            return {
                'games': [],
                'chosenGame': "",
            }
        },

        props: {
            'gameId' : 0,
         },

        methods: {
            loadList(){
                apiCall.getData('game')
                .then(response =>{
                    this.games = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            update(){
                if(this.chosenGame.id > 0){
                    apiCall.updateData('merge/' + this.gameId  + '/game/' + this.chosenGame.id)
                    .then(response =>{

                        this.$bus.$emit('reloadList');
                        this.message = this.chosenGame.full_name +  " is merged with" +  games.full_name;
                        this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    }).catch(error => {
                            this.errors = error;
                    });
                }
            },
        },

        mounted(){
            this.loadList();
        }
    }
</script>

<style scoped>

</style>
