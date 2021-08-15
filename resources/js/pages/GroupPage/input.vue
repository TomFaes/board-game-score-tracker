<template>
    <div class="container">
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <!-- the form items -->
            <text-input inputName="name" inputId="name" tekstLabel="Name: " v-model="fields.name" :errors="errors.name" :value='fields.name'></text-input>
            <text-area inputName="description" inputId="description" tekstLabel="Description: " v-model="fields.description" :errors="errors.description" :value='fields.description'></text-area>

            <!-- Admin multiselect -->
            <div class="row" v-if="this.groupUsers != undefined">
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
                        label="full_name"
                        track-by="full_name"
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

    export default {
        components: {
            TextInput,
            TextArea,
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
            },

         },

         computed: {
             groupUsers(){
                if(this.group == undefined){
                    return;
                }
                if(this.group.id == undefined){
                    return;
                }
                this.setMultiGroupUsers();
                return this.$store.state.selectedGroupUsers;
            },
         },

        methods: {
            setData(){
                this.fields.name = this.group.name;
                this.fields.description = this.group.description;
            },

            setMultiGroupUsers(){
                for(var item in this.$store.state.selectedGroupUsers.data){
                    if(this.$store.state.selectedGroupUsers.data[item].user_id <= 0){
                        return
                    }

                    this.multigroupUsers.push(this.$store.state.selectedGroupUsers.data[item]);
                    if(this.group.admin.id == this.$store.state.selectedGroupUsers.data[item].user_id){
                        this.selectedUser = this.$store.state.selectedGroupUsers.data[item];
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

            loadList(){
                if(this.group == undefined){
                    return;
                }
                if(this.group.id == undefined){
                    return;
                }
                if(this.$store.state.selectedGroupUsers.data == undefined){
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
                this.setData();
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

                apiCall.postData('group', this.formData)
                .then(response =>{
                    this.resetFormData();
                    this.message = "Your group " + response.name + " has been created";
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.$store.dispatch('getUserGroups');
                    this.$router.push({name: "home"});
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();

                apiCall.updateData('group/' + this.group.id, this.formData)
                .then(response =>{
                    this.message = "Your group " + response.name + " has been changed";
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
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
            }
        },

        mounted(){
            if(this.group == undefined){
                this.buttonText = "Create group";
            }
        },

        created(){
            this.loadList();
        }
    }
</script>

<style scoped>

</style>
