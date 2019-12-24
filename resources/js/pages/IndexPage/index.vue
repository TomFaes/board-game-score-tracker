<template>
    <div>
        <div class="container">
            <div class="row">
                <unverifiedUser></unverifiedUser>
            </div>

            <div class="row">
                <div>
                    <span>
                            <button class="btn btn-primary" @click.prevent="showCreateGroup()">New group</button>
                    </span>
                    <span  v-for="(data, index) in userGroups"  :key="data.id" >
                        <button class="btn group" @click.prevent="setGroup(index)">{{data.name}}</button>
                    </span>
                </div>
            </div>
            <div class="row">

                <!-- Group Options-->
                <div v-if="selectedGroup.id > 0">
                    Selected group: {{ selectedGroup.name }}<br>
                </div>
            </div>

            <div class="row">
                <!-- Group edit -->
                <div v-show="display == 'showCreateGroup'">
                    <h2>Create new group</h2>
                    <inputGroup :submitOption="'Create'" ></inputGroup>
                </div>
            </div>
        </div>

        <div class="container" v-if="selectedGroup.id > 0">
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item">
                    <a class="nav-link" @click.prevent="setActive('home')" :class="{ active: isActive('home') }" href="#home">Score</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click.prevent="setActive('games')" :class="{ active: isActive('games') }" href="#games">Games</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click.prevent="setActive('users')" :class="{ active: isActive('users') }" href="#users">Users</a>
                </li>
                <li class="nav-item" v-if="selectedGroup.typeMember == 'Admin'">
                    <a class="nav-link" @click.prevent="setActive('edit')" :class="{ active: isActive('edit') }" href="#games">Edit Group</a>
                </li>
            </ul>

            <div class="tab-content py-3" id="myTabContent">
                <div class="tab-pane fade" :class="{ 'active show': isActive('home') }" id="home">
                    The scores of the played games will be showed here.
                </div>

                <div class="tab-pane fade" :class="{ 'active show': isActive('games') }" id="games">
                    <group-game-list v-if="selectedGroup.id > 0" :group=selectedGroup></group-game-list>
                </div>

                <div class="tab-pane fade" :class="{ 'active show': isActive('users') }" id="users">
                    <group-user-list :groupUsers="selectedGroup.group_users" :group="selectedGroup"></group-user-list>
                </div>

                <div class="tab-pane fade" :class="{ 'active show': isActive('edit') }" id="edit" v-if="selectedGroup.typeMember == 'Admin'">
                    <inputGroup v-if="selectedGroup.id > 0" :group=selectedGroup :submitOption="'Update'" :key=selectedGroup.id></inputGroup>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"></div>
                        <div class="col-lg-8 col-md-8">
                            <button class="btn btn-danger" @click.prevent="deleteGroup"><i class="fas fa-trash fa-2x" ></i></button>
                        </div>
                        <div class="col-lg-2 col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import inputGroup from '../GroupPage/input';

    import unverifiedUser from '../GroupUserPage/unverifiedUser';

    import groupGameList from '../GroupGamePage/list.vue';
    import groupUserList from '../GroupUserPage/list.vue';

    import addGames from '../GroupGamePage/addGames.vue';

    export default {
        components: {
            inputGroup,
            unverifiedUser,
            groupGameList,
            groupUserList,

            addGames,
        },

        data () {
            return {
                user: {},
                display: "",
                groupIndex: -1,
                userGroups: {},
                selectedGroup: {},
                selectedGroupUser: {},
                activeItem: 'home'
            }
        },

        methods: {
            isActive (menuItem) {
                return this.activeItem === menuItem
            },
            setActive (menuItem) {
                this.activeItem = menuItem
            },

            showCreateGroup(){
                if(this.display == 'showCreateGroup'){
                    this.hide();
                }else{
                    this.selectedGroup = {};
                    this.groupIndex = -1;
                    this.display = 'showCreateGroup';
                }
            },

            //can be used for all hide option
            hide(){
                this.display = '';
            },

            setGroup(id){
                this.selectedGroup = this.userGroups[id];
                this.groupIndex = id;
                this.display = '';
                if(this.selectedGroup.typeMember != "Admin" && this.activeItem == "edit"){
                    this.activeItem = 'home';
                }
            },

            getGroups(){
                apiCall.getData('user-group')
                .then(response =>{
                    this.userGroups = response;
                    if(this.groupIndex >= 0){
                        this.setGroup(this.groupIndex);
                        if(this.activeItem == "edit"){
                            this.activeItem = 'home';
                        }

                    }
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            deleteGroup(){
                if(confirm('are you sure you want to delete ' + this.selectedGroup.name + '?')){
                    apiCall.deleteData('group/' + this.selectedGroup.id)
                    .then(response =>{
                        console.log(response);
                        this.getGroups();
                        this.selectedGroup = {};
                        this.groupIndex = -1;
                    }).catch(error => {
                        console.log(error);
                        console.log('handle server error from here');
                    });
                }

            }
        },

        mounted(){
            this.getGroups();
            this.$bus.$on('reloadList', () => {
                this.hide();
                this.getGroups();
            });
        }
    }
</script>

<style scoped>
.group {
    background-color: orange;
    margin: 5px;
}

</style>
