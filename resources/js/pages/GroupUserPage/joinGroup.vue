<template>
    <div>
        <hr>
        <form @submit.prevent="joinGroup" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.code"/>
                    <label>Add code: </label>
                </div>
                <div class="text-danger" v-if="errors.code">{{ errors.code[0] }}</div>
            </global-layout>
            <global-layout center="center" >
                <button class="btn btn-primary">Add code</button>
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
                    code: '',
                },
                'errors' : {},
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
                    var message = "You have joined the group: " + response.data.group.name;
                    if(response == false){
                        message = "No group found for that code";
                        this.$store.dispatch('getMessage', {message: message, color: 'red', time: 2000});
                        return
                    }
                    this.$store.dispatch('getMessage', {message: message});

                    this.formData =  new FormData();
                    this.$store.dispatch('getGroupsOfUser');
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
