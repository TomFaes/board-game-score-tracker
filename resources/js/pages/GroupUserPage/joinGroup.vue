<template>
    <div>
        <hr>
        <form @submit.prevent="joinGroup" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="code" inputId="code" tekstLabel="Code to join group: " v-model="fields.code" :errors="errors.code" :value='fields.code'></text-input>
            <button-input btnClass="btn btn-primary">Add code</button-input>
        </form>
        <hr>
    </div>
</template>

<script>
     import apiCall from '../../services/ApiCall.js';

    import TextInput from '../../components/ui/form/TextInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';

    export default {
        components: {
            TextInput,
            ButtonInput,
        },

         data () {
            return {
                'fields' : {
                    code: '',
                },
                'errors' : [],
                'formData': new FormData(),
            }
        },

        props: {

         },

        methods: {

            joinGroup(){
                if(this.fields.code == undefined){
                    return;
                }

                this.formData.set('code', this.fields.code);
                apiCall.updateData('join_group', this.formData)
                .then(response =>{
                    if(response == false){
                        this.message = "No group found for that code";
                        this.$bus.$emit('showMessage', this.message,  'red', '2000' );
                        return
                    }
                    this.message = "You have joined the group: ";
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );

                    this.formData =  new FormData();
                     this.$store.dispatch('getUserGroups');

                    this.$router.push({name: "home"});
                }).catch(error => {
                        this.errors = error;
                });
            },
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>
