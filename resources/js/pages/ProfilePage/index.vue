<template>
    <div>
        <div class="row">
                <h2>Profile</h2>
        </div>

        <form @submit.prevent="update" method="POST" enctype="multipart/form-data">
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="user.firstname"/>
                    <label>Firstname: </label>
                </div>
                <div class="text-danger" v-if="errors.firstname">{{ errors.firstname[0] }}</div>
            </global-layout>
            <global-layout>
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="user.name"/>
                    <label>Name: </label>
                </div>
                <div class="text-danger" v-if="errors.name">{{ errors.name[0] }}</div>
            </global-layout>
            <global-layout>
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="user.email"/>
                    <label>Email: </label>
                </div>
                <div class="text-danger" v-if="errors.email">{{ errors.email[0] }}</div>
            </global-layout>
            <global-layout center="center">
                <button class="btn btn-primary">Safe profile</button>
            </global-layout>
            <hr>
            <div class="form-group">
                <global-layout center="center">
                        If you want to remove your account click the delete button, all your data will be randomised and you wont be able to use this account again.<br>
                        <button class="btn btn-danger" @click.prevent="removeProfile()"><i class="fas fa-trash fa-1x" ></i></button>
                </global-layout>
            </div>
        </form>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';

    export default {
        components: {

        },

         data () {
            return {
                'errors' : [],
                'formData': new FormData(),
            }
        },

        computed: {
            user(){
                return this.$store.state.loggedInUser;
            }
        },

        methods: {
            setFormData(){
                this.formData.set('firstname', this.user.firstname ?? null);
                this.formData.append('name', this.user.name ?? null);
                this.formData.set('email', this.user.email ?? null);
            },

            update(){
                this.setFormData();

                apiCall.postData('profile', this.formData)
                .then(response =>{
                    this.$store.dispatch('getMessage', {message: "You've updated your profile"});
                    this.formData =  new FormData();
                    this.$router.push({name: "home"});
                }).catch(error => {
                    this.errors = error;
                });
            },

            removeProfile(){
                if(confirm('Are you sure you want to remove your profile?')){
                    apiCall.postData('profile/delete')
                    .then(response =>{
                        window.location.href = "./logout";
                    }).catch(error  => {
                        if(error.status === 401){
                            this.$store.dispatch('getMessage', {message: error.data});
                        }
                        console.log('handle server error from here');
                    });
                }
            },
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>

