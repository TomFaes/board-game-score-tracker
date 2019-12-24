<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-input inputName="description" inputId="description" tekstLabel="description: " v-model="fields.description" :errors="errors.description" :value='fields.description'></text-input>
            create dropdown with groupmembers to change the admin of the group
            <button-input btnClass="btn btn-primary">Save group</button-input>
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
                    description: '',
                    admin_id: '',
                },
                'errors' : [],
                'action': '',
                'formData': new FormData(),
            }
        },

        props: {
            'group': {},
            'submitOption': ""
         },

        methods: {
            setFormData(){
                if(this.fields.name != undefined){
                    this.formData.set('name', this.fields.name);
                }
                if(this.fields.description != undefined){
                    this.formData.append('description', this.fields.description);
                }
                if(this.fields.admin_id != undefined){
                    this.formData.set('admin_id', this.fields.admin_id);
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
                this.action = "group";
                this.submitData();
            },

            update(){
                this.setFormData();
                this.action =  'group/' + this.group.id,
                this.updateData();
            },

            setData(){
                this.fields.name = this.group.name;
                this.fields.description = this.group.description;
                this.fields.admin_id = this.group.admin_id;
            }
        },

        mounted(){
            if(this.group != undefined){
                this.setData();
            }
        }
    }
</script>

<style scoped>

</style>
