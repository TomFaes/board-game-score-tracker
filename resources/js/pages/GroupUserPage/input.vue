<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
             <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.firstname"/>
                    <label>Firstname: </label>
                </div>
                <div class="text-danger" v-if="errors.firstname">{{ errors.firstname[0] }}</div>
            </global-layout>
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.name"/>
                    <label>Name: </label>
                </div>
                <div class="text-danger" v-if="errors.name">{{ errors.name[0] }}</div>
            </global-layout>
            <global-layout center="center">
                <button class="btn btn-primary">Save game</button>
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
                    firstname: '',
                    name: ''
                },
                'errors' : [],
                'formData': new FormData(),
            }
        },

        props: {
            'groupUser': {},
            'group': {},
            'submitOption': ""
         },

        methods: {
            setFormData(){
                this.formData.set('firstname', this.fields.firstname ?? null);
                this.formData.set('name', this.fields.name ?? null);
                this.formData.append('group_id', this.group.id);
            },

            submit(){
                if(this.submitOption == 'Create'){
                    this.create();
                    return
                }
                if(this.submitOption == 'Update'){
                    this.update();
                    return;
                }
                this.$store.dispatch('getMessage', {message: "No option given."})
            },

            create(){
                this.setFormData();
                var link = 'group/' + this.group.id + '/user';

                apiCall.postData(link, this.formData)
                .then(response =>{
                    var message = "You've added " + this.fields.firstname + " " + this.fields.name + " to " + this.group.name;
                    this.$store.dispatch('getMessage', {message: message});
                    this.formData =  new FormData();
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                    this.fields = {}; //Clear input fields.
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();
                var link = 'group/' + this.group.id + '/user/' + this.groupUser.id;

                apiCall.updateData(link, this.formData)
                .then(response =>{
                    var message = "You've updated the user " + this.fields.firstname + " " + this.fields.name + " for " + this.group.name;
                    this.$store.dispatch('getMessage', {message: message})

                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                    this.formData =  new FormData();
                }).catch(error => {
                        this.errors = error;
                });
            },

            setData(){
                this.fields.firstname = this.groupUser.firstname;
                this.fields.name = this.groupUser.name;
                this.fields.group_id = this.groupUser.group_id;
            }
        },

        mounted(){
            if(this.groupUser != undefined){
                this.setData();
            }
        }
    }
</script>

<style scoped>

</style>
