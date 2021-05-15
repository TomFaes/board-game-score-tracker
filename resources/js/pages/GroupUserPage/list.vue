<template>
    <div class="container">
        <center>
            <button class="btn btn-primary" v-if="group.typeMember == 'Admin'" @click.prevent="addGroupUser" > <i class="fa fa-user-plus"></i></button><br><br>
        </center>

        <!-- Add user input -->
        <div v-show="display == 'showAddUserToGroup'">
            <addUserToGroup :submitOption="'Create'" :group=group></addUserToGroup>
        </div>

        <!-- list of all users -->
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody  v-for="data in group.group_users"  :key="data.id" >
                    <tr>
                        <td>
                            <div v-if="data.user_id > 0">
                                {{ data.user.firstname }} {{ data.user.name }}
                            </div>
                            <div v-else>
                                {{data.firstname}} {{data.name}}
                            </div>
                        </td>
                        <td v-if="group.typeMember == 'Admin'">
                            {{data.code}}
                        </td>
                        <td v-if="group.typeMember == 'Admin'" class="options-column">
                            <button class="btn btn-primary"  v-if="!data.user_id" @click.prevent="updateGroupUser(data)"> <i class="fa fa-edit" style="heigth:14px; width:14px"></i></button>
                            <button class="btn btn-danger" @click.prevent="deleteGroupUser(data)" ><i class="fas fa-trash-alt" style="heigth:14px; width:14px" ></i></button>
                            <button class="btn btn-secondary" v-if="!data.user_id" @click.prevent="regenerateCode(data)" ><i class="fas fa-sync" style="heigth:14px; width:14px" ></i></button>


                        </td>
                    </tr>
            </tbody>
        </table>

        <div v-show="display == 'showEditGroupUser'">
            <addUserToGroup v-if="selectedGroupUser.id > 0" :group=group :groupUser=selectedGroupUser :submitOption="'Update'" :key=selectedGroupUser.id></addUserToGroup>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import addUserToGroup from '../GroupUserPage/input.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';

    export default {
        data () {
            return {
                display: "",
                selectedGroupUser: 0,
                'groupUsers': {},
            }
        },

        components: {
            addUserToGroup,
            ButtonInput
        },

        computed: {
            group(){
                return this.$store.state.selectedGroup;
            },
        },

        methods: {
            setGroupUsers(){
                this.groupUsers = this.group.group_users;
                this.display = "";
            },

            updateGroupUser(groupUser){
                if(this.display == 'showEditGroupUser'){
                    if(this.selectedGroupUser.id == groupUser.id){
                        this.display = '';
                    }else if(this.selectedGroupUser.id != groupUser.id){
                        this.selectedGroupUser = groupUser;
                    }else{
                        this.display = '';
                    }
                }else{
                    this.display = 'showEditGroupUser';
                    this.selectedGroupUser = groupUser;
                }
            },

            addGroupUser(){
                if(this.display == 'showAddUserToGroup'){
                    this.display = '';
                }else{
                    this.display = 'showAddUserToGroup';
                }
            },

            regenerateCode(data){
                apiCall.postData('group/' + this.group.id + '/user/' + data.id + '/regenerate_code')
                    .then(response =>{
                         this.message = "code is regenerated";

                        this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                        this.$store.dispatch('getSelectedGroup', {id: this.group.id});
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
            },

            deleteGroupUser(data){
                if(confirm('are you sure you want to remove this user from ' + this.group.name  + '?')){
                    apiCall.postData('group/' + this.group.id + '/user/' + data.id + '/delete')
                    .then(response =>{
                        if(response.status == 403){
                            this.message = "This user couldn't be delete from this group";
                        }else{
                            this.message = data.fullName + " is deleted from this group";
                            this.$bus.$emit('reloadGroups');
                        }
                        this.$bus.$emit('showMessage', this.message,  'red', '2000' );
                        this.$store.dispatch('getSelectedGroup', {id: this.group.id});
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            }
        },

        mounted(){
            this.setGroupUsers();
            this.$bus.$on('reloadList', () => {
                this.display = "";
            });
        }
    }
</script>

<style scoped>

</style>

