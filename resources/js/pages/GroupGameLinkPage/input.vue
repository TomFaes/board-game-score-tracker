<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="Name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-input inputName="link" inputId="link" tekstLabel="Link: " v-model="fields.link" :errors="errors.link" :value='fields.link'></text-input>
            <text-area inputName="description" inputId="description" tekstLabel="Description: " v-model="fields.description" :errors="errors.description" :value='fields.description'></text-area>
            <center>
                <button class="btn btn-primary">Save link</button>
            </center>
        </form>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import TextArea from '../../components/ui/form/TextAreaInput.vue';
    import TextInput from '../../components/ui/form/TextInput.vue';

    export default {
        components: {
            TextInput,
            TextArea,
        },

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

                apiCall.postData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadGroupGames');
                    this.$bus.$emit('hideLinkList');
                    this.message = "You've added a new link to " + this.group_game.game.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.formData.set('group_game_id', this.group_game_link.group_game_id);
                this.setFormData();
                this.action = "group/game/" + this.group_game_link.group_game_id + "/link/"+ this.group_game_link.id;

                 apiCall.updateData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadGroupGames');
                    this.$bus.$emit('hideLinkList');
                    this.message = "You've updated a link from " + this.group_game.game.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
                }).catch(error => {
                        this.errors = error;
                });
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

