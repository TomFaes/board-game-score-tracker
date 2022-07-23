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
                    <input type="text" class="form-control" v-model="fields.link"/>
                    <label>Link: </label>
                </div>
                <div class="text-danger" v-if="errors.link">{{ errors.link[0] }}</div>
            </global-layout>
            <global-layout >
                <div class="form-floating  mb-3">
                    <textarea type="text" style="height: 100px" class="form-control" v-model="fields.description"/>
                    <label>Description: </label>
                </div>
                <div class="text-danger" v-if="errors.description">{{ errors.description[0] }}</div>
            </global-layout>
            <global-layout center="center">
                <button class="btn btn-primary">Save link</button>
            </global-layout>
        </form>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';

    export default {
        components: {

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
            'submitOption': "",
            'currentPage': ''
         },

        methods: {
            setFormData(){
                this.formData.set('name', this.fields.name ?? null);
                this.formData.set('link', this.fields.link ?? null);
                this.formData.set('description', this.fields.description ?? null);
                this.formData.append('group_game_id', this.group_game.id);
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
                this.action = "group/" + this.group_game.group_id + "/game/" + this.group_game.id + "/link";

                apiCall.postData(this.action, this.formData)
                .then(response =>{
                    this.$store.dispatch('getSelectedGroupGames', {groupId: this.group_game.group_id, pageItems: 20, currentPage: this.currentPage});
                    var message = "You've added a new link to " + this.group_game.game.name;
                    this.$store.dispatch('getMessage', {message: message});
                    this.formData =  new FormData();
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.formData.set('group_game_id', this.group_game_link.group_game_id);
                this.setFormData();

                this.action = "group/" + this.group_game.group_id + "/game/" + this.group_game.id + "/link/"+ this.group_game_link.id;
                 apiCall.updateData(this.action, this.formData)
                .then(response =>{
                    this.$store.dispatch('getSelectedGroupGames', {groupId: this.group_game.group_id, pageItems: 20, currentPage: this.currentPage});
                    var message = "You've updated a link from " + this.group_game.game.name;
                    this.$store.dispatch('getMessage', {message: message});
                    this.formData =  new FormData();
                }).catch(error => {
                    console.log(error);
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

