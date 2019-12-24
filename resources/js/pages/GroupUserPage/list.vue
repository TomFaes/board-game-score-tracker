<template>
    <div>
        <i v-if="group.typeMember == 'Admin'" class="fa fa-user-plus fa-2x" @click.prevent="addGroupUser" ></i><br>

        <div v-show="display == 'showAddUserToGroup'">
            <addUserToGroup :submitOption="'Create'" :group=group></addUserToGroup>
        </div>

        <span  v-for="data in groupUsers"  :key="data.id" >

            <span v-if="data.user_id > 0">
                {{ data.user.firstname }} {{ data.user.name }}<br>
            </span>
            <span v-else>
                {{data.firstname}} {{data.name}} <i v-if="group.typeMember == 'Admin'" class="fa fa-edit" @click.prevent="updateGroupUser(data)" ></i>
                <i class="fas fa-trash-alt" @click.prevent="deleteGroupUser(data.id)" v-if="group.typeMember == 'Admin'" ></i><br>
            </span>
        </span>

        <div v-show="display == 'showEditGroupUser'">
            <addUserToGroup v-if="selectedGroupUser.id > 0" :group=group :groupUser=selectedGroupUser :submitOption="'Update'" :key=selectedGroupUser.id></addUserToGroup>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import addUserToGroup from '../GroupUserPage/input';

    export default {
        data () {
            return {
                display: "",
                selectedGroupUser: 0,
            }
        },

        props: {
            'groupUsers': {},
            'group': {},
         },

        components: {
            addUserToGroup,
        },

        methods: {

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

            deleteGroupUser(id){
                apiCall.deleteData('group/' + this.group.id + '/user/' + id)
                .then(response =>{
                    this.$bus.$emit('reloadList');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            }


            /*
            deleteLink(id){
                apiCall.deleteData('group/game/link/' + id)
                .then(response =>{
                      this.$bus.$emit('reloadList');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            }
            */
        },

        mounted(){
            this.$bus.$on('reloadList', () => {
                    this.display = "";
            });
        }
    }
</script>

<style scoped>

</style>

