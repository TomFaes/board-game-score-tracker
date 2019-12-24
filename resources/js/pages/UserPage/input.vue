<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="firstname" inputId="firstname" tekstLabel="firstname: " v-model="fields.firstname" :errors="errors.firstname" :value='fields.firstname'></text-input>
            <text-input inputName="name" inputId="name" tekstLabel="name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-input inputName="email" inputId="email" tekstLabel="email: " v-model="fields.email" :errors="errors.email" :value='fields.email'></text-input>
            <button-input btnClass="btn btn-primary">Save user</button-input>
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
                    firstname: '',
                    name: '',
                    email: '',
                },
                'errors' : [],
                'action': '',
                'formData': new FormData(),
            }
        },

        props: {
            'user': {},
            'submitOption': ""
         },

        methods: {
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
                this.action = "user";
                this.submitData();
            },

            update(){
                this.setFormData();
                this.action =  'user/' + this.user.id,
                this.updateData();
            },

            setData(){
                this.fields.firstname = this.user.firstname;
                this.fields.name = this.user.name;
                this.fields.email = this.user.email;
            }
        },

        mounted(){
            if(this.user != undefined){
                this.setData();
            }
        }
    }
</script>

<style scoped>

</style>

