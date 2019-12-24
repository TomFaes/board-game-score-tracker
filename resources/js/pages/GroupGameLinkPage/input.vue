<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="Name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-input inputName="link" inputId="link" tekstLabel="Link: " v-model="fields.link" :errors="errors.link" :value='fields.link'></text-input>
            <text-input inputName="description" inputId="description" tekstLabel="Description: " v-model="fields.description" :errors="errors.description" :value='fields.description'></text-input>
            <button-input btnClass="btn btn-primary">Save Game</button-input>
        </form>
        <hr>
    </div>
</template>

<script>
    import TextInput from '../../components/ui/form/TextInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';

    import SubmitForm from '../../mixins/SubmitForm.js';

    export default {
        components: {
            TextInput,
            ButtonInput
        },

        mixins: [ SubmitForm],

         data () {
            return {
                'fields' : {
                    name: '',
                    link: '',
                    description: '',
                },
                'errors' : [],
                'action': '',
                'formData': new FormData(),
            }
        },

        props: {
            'group_game': {},
            'group_game_link' : {},
            'submitOption': ""
         },

        methods: {
            setFormData(){
                if(this.fields.name != undefined){
                    this.formData.set('name', this.fields.name);
                }
                if(this.fields.link != undefined){
                    this.formData.set('link', this.fields.link);
                }
                if(this.fields.description != undefined){
                    this.formData.append('description', this.fields.description);
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
                this.formData.set('group_game_id', this.group_game.id);
                this.setFormData();
                this.action = "group/game/" + this.group_game.id + "/link";
                this.submitData();
            },

            update(){
                this.formData.set('group_game_id', this.group_game_link.group_game_id);
                this.setFormData();
                 this.action = "group/game/" + this.group_game_link.group_game_id + "/link/"+ this.group_game_link.id;
                //this.action = "group/game/link/" + this.group_game_link.id;
                this.updateData();
            },

            setData(){
                this.fields.name = this.group_game_link.name;
                this.fields.link = this.group_game_link.link;
                this.fields.description = this.group_game_link.description;
            }
        },

        mounted(){
            if(this.group_game_link != undefined){
                this.setData();
            }
        }
    }
</script>

<style scoped>

</style>

