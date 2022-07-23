<template>
    <div class="container">
        <global-layout center="center">
            <button class="btn btn-primary" v-if="group.type_member == 'Admin'" @click.prevent="addGroupUser" > <i class="fa fa-user-plus"></i></button><br><br>
        </global-layout>

        <!-- Add user input-->
        <div v-show="display == 'showAddUserToGroup'">
            <add-user-to-group :submitOption="'Create'" :group="group"></add-user-to-group>
        </div>

        <!-- list of all users -->
        <table class="table table-hover table-sm" v-if="groupUsers">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody  v-for="groupuser in groupUsers.data"  :key="groupuser.id" >
                <tr>
                    <td>
                        <div v-if="groupuser.user_id > 0">
                            {{ groupuser.user.full_name  }}
                        </div>
                        <div v-else>
                            {{ groupuser.full_name }}
                        </div>
                    </td>
                    <td v-if="group.type_member == 'Admin'">
                            <a class="mailtoui"
                                :href="createLink(groupuser.code)"
                                v-if="groupuser.code != null"
                                >{{groupuser.code}}</a>
                    </td>
                    <td v-if="group.type_member == 'Admin'" class="options-column">
                        <button class="btn btn-primary"  v-if="!groupuser.user_id" @click.prevent="updateGroupUser(groupuser)"> <i class="fa fa-edit" style="heigth:14px; width:14px"></i></button>
                        <button class="btn btn-danger" @click.prevent="deleteGroupUser(groupuser)" ><i class="fas fa-trash-alt" style="heigth:14px; width:14px" ></i></button>
                        <button class="btn btn-secondary" v-if="!groupuser.user_id" @click.prevent="regenerateCode(groupuser)" ><i class="fas fa-sync" style="heigth:14px; width:14px" ></i></button>
                    </td>
                </tr>
                <tr v-if="selectedGroupUser.id == groupuser.id" >
                    <td  colspan="100">
                        <add-user-to-group v-if="selectedGroupUser.id > 0" :group=group :groupUser="selectedGroupUser" :submitOption="'Update'" :key="selectedGroupUser.id"></add-user-to-group>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import addUserToGroup from '../GroupUserPage/input.vue';

    export default {
        data () {
            return {
                display: "",
                selectedGroupUser: 0,
            }
        },

        components: {
            addUserToGroup,
        },

        computed: {
            group(){
                return this.$store.state.selectedGroup;
            },

            groupUsers(){
                this.display = "";
                this.selectedGroupUser = "";
                if(this.group.id === undefined){
                    return;
                }

                if(this.$store.state.selectedGroupUsers.data == undefined){
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
                return this.$store.state.selectedGroupUsers;
            }
        },

        methods: {
            updateGroupUser(groupUser){
                if(this.selectedGroupUser.id == groupUser.id){
                    this.selectedGroupUser = 0;
                    return;
                }
                this.selectedGroupUser = groupUser;
            },

            addGroupUser(){
                if(this.display == 'showAddUserToGroup'){
                    this.display = '';
                    return
                }
                this.display = 'showAddUserToGroup';
            },

            regenerateCode(data){
                apiCall.postData('group/' + this.group.id + '/user/' + data.id + '/regenerate_code')
                    .then(response =>{
                        this.$store.dispatch('getMessage', {message: "Code is regenerated"});
                        this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
            },

            deleteGroupUser(groupuser){
                if(confirm('are you sure you want to remove this user from ' + this.group.name  + '?')){
                    apiCall.postData('group/' + this.group.id + '/user/' + groupuser.id + '/delete')
                    .then(response =>{
                        var message = groupuser.full_name + " is deleted from this group";
                        if(response.status == 403){
                            message = "This user couldn't be delete from this group";
                        }
                        this.$store.dispatch('getMessage', {message: message});
                        this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            },

            createLink(code){
                var subject = "Your invitation code for the boardgametracker";
                subject = subject.replace(/\s/g, '%20');

                var body = "%0D%0A You have been invited to join " + this.group.name
                + ". After logging in you can enter this code to join the group: " + code;

                body = body.replace(/\s/g, '%20');

                var maillink = "mailto:?" +
                    "subject=" + subject +
                    "&body=" + body
                    ;
                return maillink;
            }
        },

        mounted(){
            this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
        }
    }
</script>

<style scoped>

</style>

