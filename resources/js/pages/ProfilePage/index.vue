<template>
    <div>
        <hr>
        Profile
        <hr>
        <form @submit.prevent="update" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="firstname" inputId="firstname" tekstLabel="firstname: " v-model="fields.firstname" :errors="errors.firstname" :value='fields.firstname'></text-input>
            <text-input inputName="name" inputId="name" tekstLabel="name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-input inputName="email" inputId="email" tekstLabel="email: " v-model="fields.email" :errors="errors.email" :value='fields.email'></text-input>
            <button-input btnClass="btn btn-primary">Save profile</button-input>
        </form>

        <hr>
        If you want to remove your account click the delete button, all your data will be randomised and you wont be able to use this account.<br>
        <button class="btn btn-danger" @click.prevent="removeProfile()">Delete</button>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import TextInput from '../../components/ui/form/TextInput.vue';
    import DropdownInput from '../../components/ui/form/DropdownInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';

    import SubmitForm from '../../mixins/SubmitForm.js';

    export default {
        components: {
            TextInput,
            DropdownInput,
            ButtonInput
        },

        mixins: [ SubmitForm],

         data () {
            return {
                'fields' : {
                    firstname: '',
                    name: '',
                    email: '',
                },
                'user': {},
                'errors' : [],
                'action': '',
                'formData': new FormData(),
            }
        },

        methods: {
            loadProfile(){
                apiCall.getData('profile')
                .then(response =>{
                    this.user = response;
                    this.setData();
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            setFormData(){
                if(this.fields.firstname != undefined){
                    this.formData.set('firstname', this.fields.firstname);
                }
                if(this.fields.name != undefined){
                    this.formData.append('name', this.fields.name);
                }
                if(this.fields.email != undefined){
                    this.formData.set('email', this.fields.email);
                }
            },

            update(){
                this.setFormData();
                this.action =  'profile',
                this.updateData();
                window.location.href = "./";
            },

            removeProfile(){
                if(confirm('are you sure?')){
                    apiCall.deleteData('profile')
                    .then(response =>{
                        window.location.href = "./logout";
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            },

            setData(){
                this.fields.firstname = this.user.firstname;
                this.fields.name = this.user.name;
                this.fields.email = this.user.email;
            }
        },

        mounted(){
            this.loadProfile();
        }
    }
</script>

<style scoped>

</style>

