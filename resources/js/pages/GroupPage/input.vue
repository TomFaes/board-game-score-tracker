<template>
    <div class="container">
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data">
            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.name"/>
                    <label>Name: </label>
                </div>
                <div class="text-danger" v-if="errors.name">{{ errors.name[0] }}</div>
            </global-layout>

            <global-layout >
                <div class="form-floating  mb-3">
                    <input type="text" class="form-control" v-model="fields.description"/>
                    <label>Description: </label>
                </div>
                <div class="text-danger" v-if="errors.description">{{ errors.description[0] }}</div>
            </global-layout>

            <global-layout  v-if="multiGroupUsers">
                <div class="form-floating  mb-3">
                    <multi-select class="form-control"
                        v-model="fields.admin"
                        :multiple="false"
                        :options="multiGroupUsers"
                        :close-on-select="true"
                        :clear-on-select="true"
                        placeholder="Select admin"
                        label="full_name"
                        track-by="full_name"
                    >
                    </multi-select>
                    <label>Select group admin: </label>
                </div>
            </global-layout>
            <br>
            <global-layout center="center">
                <button class="btn btn-primary">Save group</button>
            </global-layout>
            <hr>

            <global-layout center="center" v-if="this.group != undefined">
                You can delete your group, your group wont be displayed in your list, the games played wont be deleted
                <br>
                <button class="btn btn-danger" @click.prevent="deleteGroup"><i class="fas fa-trash fa-1x" ></i></button>
            </global-layout>
        </form>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import multiSelect from '@suadelabs/vue3-multiselect';

    export default {
        components: {
            multiSelect
        },

         data () {
            return {
                'fields' : {
                    name: '',
                    description: '',
                    admin: {},
                },
                'errors' : {},
                'formData': new FormData(),
            }
        },

        props: {
            'group': {},
         },

        watch:{
            group() {
                this.setData();
            },
         },

         computed: {
             groupUsers(){
                if(this.group == undefined) {
                    return;
                }
                if(this.$store.state.selectedGroupUsers.data == undefined) {
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
                return this.$store.state.selectedGroupUsers.data;
            },

            multiGroupUsers(){
                if(this.groupUsers === undefined) {
                    return;
                }
                if(this.group.id != this.groupUsers[0].group_id) {
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
                var users = [];
                for(var item in this.groupUsers) {
                    if(this.groupUsers[item]['user_id'] > 0) {
                         users.push(this.groupUsers[item]);
                    }
                }
                return users;
            }
         },

        methods: {
            setData() {
                this.fields.name = this.group.name;
                this.fields.description = this.group.description;
                this.fields.admin = this.group.admin;
            },

            setFormData() {
                this.formData.set('name', this.fields.name ?? null);
                this.formData.set('description', this.fields.description ?? null);
                if(this.fields.admin.id != undefined){
                    this.formData.set('admin_id', this.fields.admin.id ?? null);
                }
            },

            submit(){
                if(this.group == undefined) {
                    this.create();
                    return
                }
                this.update();
            },

            resetFormData() {
                this.fields = {}; //Clear input fields.
                this.errors = {};
                this.formData =  new FormData();
            },

            create() {
                this.setFormData();
                apiCall.postData('group', this.formData)
                .then(response => {
                    this.resetFormData();
                    this.$store.dispatch('getMessage', {
                            message: "Your group " + response.data.name + " has been created",
                        });
                    this.$store.dispatch('getGroupsOfUser');
                    this.$router.push({name: "home"});
                }).catch(error => {
                    this.$store.dispatch('getMessage', {message: error});
                });
            },

            update() {
                this.setFormData();
                apiCall.updateData('group/' + this.group.id, this.formData)
                .then(response => {
                    this.$store.dispatch('getMessage', {
                            message: "Your group " + response.data.name + " has been changed",
                        });
                    this.$store.dispatch('getSelectedGroup', {id: this.group.id});
                    this.$router.push({name: "allPlayedGames", params: { id: this.group.id }});
                }).catch(error => {
                    this.$store.dispatch('getMessage', {message: error});
                    console.log(error);
                });
            },

            deleteGroup(){
                if(confirm('are you sure you want to delete this group ' + this.group.name + '?')){
                    apiCall.postData('group/' + this.group.id + '/delete')
                    .then(response => {
                        this.$store.dispatch('getMessage', {
                            message: "Your group " + this.group.name + " has been deleted",
                        });
                        this.$store.dispatch('resetToDefault');
                        this.$store.dispatch('getGroupsOfUser');
                        this.$router.push({name: "home"});
                    }).catch(error => {
                        this.$store.dispatch('getMessage', {message: error});
                    });
                }
            }
        },

        mounted(){
            if(this.group != undefined) {
                this.setData();
            }
        },
    }
</script>

<style scoped>

</style>
