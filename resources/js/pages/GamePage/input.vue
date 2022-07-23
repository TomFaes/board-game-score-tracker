<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.name"/>
                    <label>Name: </label>
                </div>
                <div class="text-danger" v-if="errors.name">{{ errors.name[0] }}</div>
            </global-layout>

            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.year"/>
                    <label>Year: </label>
                </div>
                <div class="text-danger" v-if="errors.year">{{ errors.year[0] }}</div>
            </global-layout>

            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="number" class="form-control" v-model="fields.players_min"/>
                    <label>Minimum players: </label>
                </div>
                <div class="text-danger" v-if="errors.players_min">{{ errors.players_min[0] }}</div>
            </global-layout>
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="number" class="form-control" v-model="fields.players_max"/>
                    <label>Maximum players: </label>
                </div>
                <div class="text-danger" v-if="errors.players_max">{{ errors.players_max[0] }}</div>
            </global-layout>
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="number" class="form-control" v-model="fields.players_max"/>
                    <label>Maximum players: </label>
                </div>
                <div class="text-danger" v-if="errors.players_max">{{ errors.players_max[0] }}</div>
            </global-layout>

             <global-layout  v-if="baseGames.data">
                <div class="form-floating  mb-3">
                    <multi-select class="form-control"
                        v-model="chosenGame"
                        :multiple="false"
                        :options="baseGames.data"
                        :close-on-select="true"
                        :clear-on-select="true"
                        placeholder="Select the base game"
                        label="name"
                        track-by="name"
                        id="game"
                    >
                    </multi-select>
                    <label>Select the base game: </label>
                </div>
            </global-layout>
            <br>
            <global-layout center="center">
                <button class="btn btn-primary">Save game</button>
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
                'fields' : {
                    name: '',
                    year: '',
                    players_min: '',
                    players_max: '',
                },
                'chosenGame': '',
                'errors' : {},
                'formData': new FormData(),
            }
        },

        props: {
            'game': {},
            'submitOption': "",
            'currentPage': 0,
            'itemsPerPage': 0
         },

         computed: {
            baseGames(){
                if(this.$store.state.baseGames.data == undefined ){
                    this.$store.dispatch('getBaseGames');
                }
                return this.$store.state.baseGames;
            }
        },

        methods: {
            setFormData(){
                if(this.fields.name != undefined){
                    this.formData.set('name', this.fields.name);
                }
                if(this.fields.year != undefined){
                    this.formData.append('year', this.fields.year);
                }
                 if(this.fields.players_min != undefined){
                    this.formData.append('players_min', this.fields.players_min);
                }
                if(this.fields.players_max != undefined){
                    this.formData.append('players_max', this.fields.players_max);
                }
                if(this.chosenGame != undefined && this.chosenGame !=""){
                    this.formData.append('base_game_id', this.chosenGame.id);
                }
            },

            submit(){
                if(this.submitOption == 'Create' || this.submitOption == 'CreateFromGroup'){
                    this.create();
                }else if(this.submitOption == 'Update'){
                    this.update();
                }else{
                    console.log('geen optie opgegeven');
                }
            },

            resetFormData(){
                this.fields = {}; //Clear input fields.
                this.errors = {};
                this.formData =  new FormData();
            },

            create(){
                this.setFormData();

                apiCall.postData('game', this.formData)
                .then(response =>{
                    this.formData =  new FormData();
                    var message = response.data.name + " has been added to the list";
                     if(this.submitOption == "CreateFromGroup"){
                        message = response.data.name + " has been created, you can now add it to your game list";
                    }
                    this.resetFormData();
                    this.$store.dispatch('getGames', {currentPage: 1, itemsPerPage: 10});
                    this.$store.dispatch('getMessage', {message: message});
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();
                var link =  'game/' + this.game.id;

                apiCall.updateData(link, this.formData)
                .then(response =>{
                    this.resetFormData();
                    var message = "The game " + response.data.name + " has been updated";
                    this.$store.dispatch('getGames', {currentPage: this.currentPage, itemsPerPage: this.itemsPerPage});
                    this.$store.dispatch('getMessage', {message: message});
                }).catch(error => {
                    this.errors = error;
                });
            },

            setData(){
                this.fields.name = this.game.name;
                this.fields.year = this.game.year;
                this.fields.players_min = this.game.players_min;
                this.fields.players_max = this.game.players_max;
                this.chosenGame = this.game.base_game;
            },
        },

        mounted(){
            if(this.game != undefined){
                this.setData();
            }
        }
    }
</script>

<style scoped>

</style>

