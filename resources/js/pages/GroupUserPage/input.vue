<template>
    <div>
        <hr>
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
             <text-input inputName="firstname" inputId="firstname" tekstLabel="firstname: " v-model="fields.firstname" :errors="errors.firstname" :value='fields.firstname'></text-input>
            <text-input inputName="name" inputId="name" tekstLabel="name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <button class="btn btn-primary">Save user</button>
        </form>
        <hr>
    </div>
</template>

<script>
     import apiCall from '../../services/ApiCall.js';

    import TextInput from '../../components/ui/form/TextInput.vue';

    export default {
        components: {
            TextInput,
        },

         data () {
            return {
                'fields' : {
                    firstname: '',
                    name: '',
                    email: ''
                },
                'errors' : [],
                'action': '',
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
                if(this.fields.firstname != undefined){
                    this.formData.set('firstname', this.fields.firstname);
                }
                if(this.fields.name != undefined){
                    this.formData.set('name', this.fields.name);
                }
                if(this.fields.group_id > 0){
                         this.formData.append('group_id', this.fields.group_id);
                }else{
                    this.formData.append('group_id', this.group.id);
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
                this.action = 'group/' + this.group.id + '/user';

                apiCall.postData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadList');
                    this.message = "You've added " + this.fields.firstname + " " + this.fields.name + " to " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                    this.fields = {}; //Clear input fields.
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();
                this.action = 'group/' + this.group.id + '/user/' + this.groupUser.id;

                apiCall.updateData(this.action, this.formData)
                .then(response =>{
                    this.$bus.$emit('reloadList');
                    this.message = "You've updated the user " + this.fields.firstname + " " + this.fields.name + " for " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
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
