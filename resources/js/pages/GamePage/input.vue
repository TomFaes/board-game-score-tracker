<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="Name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <number-input inputName="year" inputId="year" tekstLabel="Year: " v-model="fields.year" :errors="errors.year" :value='fields.year'></number-input>
            <number-input inputName="players_min" inputId="players_min" tekstLabel="Players min: " v-model="fields.players_min" :errors="errors.players_min" :value='fields.players_min'></number-input>
            <number-input inputName="players_max" inputId="players_max" tekstLabel="Players max: " v-model="fields.players_max" :errors="errors.players_max" :value='fields.players_max'></number-input>
            <div class="row" >
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <label>Select game: </label>
                        <multiselect
                            v-model="chosenGame"
                            :multiple="false"
                            :options="basegame"
                            :close-on-select="true"
                            :clear-on-select="true"
                            placeholder="Add the selected game"
                            label="name"
                            track-by="name"
                            id="game"
                        >
                        </multiselect>
                    </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div><br>
            <center>
                <button-input btnClass="btn btn-primary">Save Game</button-input>
            </center>

        </form>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import TextInput from '../../components/ui/form/TextInput.vue';
    import NumberInput from '../../components/ui/form/NumberInput.vue';
    import DropdownInput from '../../components/ui/form/DropdownInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            TextInput,
            NumberInput,
            DropdownInput,
            ButtonInput,
            Multiselect,
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
                'action': '',
                'formData': new FormData(),
                'basegame': [],
            }
        },

        props: {
            'game': {},
            'submitOption': ""
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

            create(){
                this.setFormData();
                this.action = "game";

                apiCall.postData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadGameList');
                    this.$bus.$emit('resetGameDisplay');
                    this.formData =  new FormData();
                     if(this.submitOption == "CreateFromGroup"){
                        this.$bus.$emit('reloadGroupGames');
                        this.message = response.data.name + " has been created, you can now add it to your game list";
                    }else{
                        this.message = response.data.name + " has been added to the list";
                    }
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();
                this.action =  'game/' + this.game.id,

                apiCall.updateData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadGameList');
                    this.message = response.data.full_name + " has been updated";
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.$bus.$emit('display', 'groupStats');
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

            loadBaseGameList(){
                apiCall.getData('basegame')
                .then(response =>{
                    this.basegame = response.data;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },
        },

        mounted(){
            this.loadBaseGameList();
            if(this.game != undefined){
                this.setData();
            }
        }
    }
</script>

<style scoped>

</style>

