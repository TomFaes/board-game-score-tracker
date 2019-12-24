<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <number-input inputName="year" inputId="year" tekstLabel="year: " v-model="fields.year" :errors="errors.year" :value='fields.year'></number-input>
            <number-input inputName="players_min" inputId="players_min" tekstLabel="players_min: " v-model="fields.players_min" :errors="errors.players_min" :value='fields.players_min'></number-input>
            <number-input inputName="players_max" inputId="players_max" tekstLabel="players_max: " v-model="fields.players_max" :errors="errors.players_max" :value='fields.players_max'></number-input>
            <dropdown-input inputName="base_game_id" inputId="base_game_id" tekstLabel="Base game: " v-model="fields.base_game_id" :errors="errors.base_game_id" :value='fields.base_game_id' :options="basegame"></dropdown-input>
            <button-input btnClass="btn btn-primary">Save Game</button-input>
        </form>
        <hr>
    </div>
</template>

<script>
    import TextInput from '../../components/ui/form/TextInput.vue';
    import NumberInput from '../../components/ui/form/NumberInput.vue';
    import DropdownInput from '../../components/ui/form/DropdownInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';

    import SubmitForm from '../../mixins/SubmitForm.js';

    export default {
        components: {
            TextInput,
            NumberInput,
            DropdownInput,
            ButtonInput
        },

        mixins: [ SubmitForm],

         data () {
            return {
                'fields' : {
                    name: '',
                    year: '',
                    players_min: '',
                    player_max: '',

                },
                'errors' : [],
                'action': '',
                'formData': new FormData(),
            }
        },

        props: {
            'game': {},
            'basegame': {},
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
                if(this.fields.base_game_id != undefined){
                    this.formData.append('base_game_id', this.fields.base_game_id);
                }
            },

            submit(){
                if(this.submitOption == 'Create'){
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
                this.submitData();
            },

            update(){
                this.setFormData();
                this.action =  'game/' + this.game.id,
                this.updateData();
            },

            setData(){
                this.fields.name = this.game.name;
                this.fields.year = this.game.year;
                this.fields.players_min = this.game.players_min;
                this.fields.players_max = this.game.players_max;
                this.fields.base_game_id = this.game.base_game_id;
            }
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

