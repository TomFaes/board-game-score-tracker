<template>
    <div class="container">
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="Name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-area inputName="description" inputId="description" tekstLabel="Description: " v-model="fields.description" :errors="errors.description" :value='fields.description'></text-area>

            <!-- Admin multiselect -->
            <div class="row" v-if="this.group != undefined">
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <label>Admin: </label>
                    <multiselect
                        v-model="selectedUser"
                        :multiple="false"
                        :options="multigroupUsers"
                        :close-on-select="true"
                        :clear-on-select="true"
                        placeholder="Select admin"
                        label="fullName"
                        track-by="fullName"
                    >
                    </multiselect>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <center>
                        <button class="btn btn-primary">{{buttonText}}</button>
                    </center>
                    <br>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div>

            <div class="row" v-if="this.group != undefined">
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                <div class="col-lg-8 col-md-8 col-sm-12">
                    You can delete your group, your group wont be displayed in your list, the games played wont be deleted
                    <center>
                        <button class="btn btn-danger" @click.prevent="deleteGroup"><i class="fas fa-trash fa-1x" ></i></button>

                        <!--
                        <delete-group :group="group"></delete-group>
                        -->
                    </center>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div>
        </form>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';

    import TextInput from '../../components/ui/form/TextInput.vue';
    import TextArea from '../../components/ui/form/TextAreaInput.vue';
    import Multiselect from 'vue-multiselect';

    import DeleteGroup from '../GroupPage/delete.vue';

    export default {
        components: {
            TextInput,
            TextArea,
            DeleteGroup,
            Multiselect
        },

         data () {
            return {
                'fields' : {
                    name: '',
                    description: '',
                    admin_id: '',
                },
                'errors' : {},
                'action': '',
                'response': {},
                'selectedUser': [],
                'multigroupUsers': [],
                'formData': new FormData(),
                'message': '',
                'buttonText': "Save group",
            }
        },

        props: {
            'group': {}, 
         },


        watch:{
            group(){
                this.loadList();
            }
         },

        methods: {
            loadList(){
                if(this.group != undefined){
                     if(this.group.id != undefined){
                        for(var item in this.group.group_users){
                            if(this.group.group_users[item]['user_id'] > 0){
                                this.multigroupUsers.push(this.group.group_users[item]);
                            }
                        }
                        this.setData();
                    }
                }
            },


            setFormData(){
                if(this.fields.name != undefined){
                    this.formData.set('name', this.fields.name);
                }
                if(this.fields.description != undefined){
                    this.formData.append('description', this.fields.description);
                }
                if(this.selectedUser != undefined){
                    this.formData.set('admin_id', this.selectedUser.user_id);
                }
            },

            submit(){
                if(this.group == undefined){
                    this.create();
                    return
                }
                this.update();
            },

            resetFormData(){
                this.fields = {}; //Clear input fields.
                this.errors = {};
                this.formData =  new FormData();
            },

            create(){
                this.setFormData();
                this.action = "group";

                apiCall.postData(this.action, this.formData)
                .then(response =>{
                    //this.$bus.$emit('reloadGroups');
                    //this.$bus.$emit('display');
                    this.resetFormData();
                    this.message = "Your group " + this.response.name + " has been created";
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.$store.dispatch('getUserGroups');
                    this.$router.push({name: "home"});
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();
                this.action =  'group/' + this.group.id;

                apiCall.updateData(this.action, this.formData)
                .then(response =>{
                    this.message = "Your group " + this.response.name + " has been changed";
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    //console.log(this.group.id)
                    this.$store.dispatch('getSelectedGroup', {id: this.group.id});
                    this.$router.push({name: "allPlayedGames", params: { id: this.group.id },});
                }).catch(error => {
                        this.errors = error;
                });
            },

            deleteGroup(){
                if(confirm('are you sure you want to delete this group ' + this.group.name + '?')){
                    apiCall.postData('group/' + this.group.id + '/delete')
                    .then(response =>{
                        this.message = "Your group " + this.group.name + " has been deleted";
                        this.$bus.$emit('showMessage', this.message,  'red', '2000' );

                        this.$store.commit('setSelectedGroup' , '{}');
                        this.$store.dispatch('getUserGroups');
                        this.$router.push({name: "home"});
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            },

            setData(){
                this.fields.name = this.group.name;
                this.fields.description = this.group.description;

                var adminUser = "";
                var adminId = this.group.admin.id;
                this.group.group_users.forEach(function(item, index){
                    if(adminId == item.user_id){
                        adminUser = index;
                    }
                });
                this.selectedUser = this.group.group_users[adminUser];
            }
        },

        mounted(){
            if(this.group == undefined){
                this.buttonText = "Create group";
            }
        },

        created(){
            if(this.group != undefined){
                if(this.group.id != undefined){
                    this.loadList();
                }
            }
            
        }
    }
</script>

<style scoped>

</style>