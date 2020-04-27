<template>
    <div style="width: 100%">

        <div class="container">
            <nav-bar :user=user></nav-bar>
        </div>

         <!-- view of  the login options-->
        <div class="container"  v-if="auth == false" >
            <login v-if="displayNav == 'login' "></login>
        </div>

         <!-- Message box -->
        <div class="container">
            <message-box></message-box>
        </div>

        <!-- view of all groups-->
        <div class="container" v-if="user && displayNav == ''">
            <unverified-user></unverified-user>
        </div>

        <!-- view of all groups-->
        <div class="container" v-if="auth == true" v-show="displayNav == ''">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-0"></div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="tile blue" @click.prevent="showCreateGroup()">
                        <div>New group</div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-0"></div>
            </div>

            <!-- Create a new group-->
            <div v-if="display == 'showCreateGroup'">
                <h2>Create new group</h2>
                <div class="row">
                    <input-group  :submitOption="'Create'"></input-group>
                </div>
                <br>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12"  v-for="(data, index) in userGroups"  :key="data.id">
                    <div class="tile orange" @click.prevent="setGroup(index)">
                        <div>{{data.name}}</div>
                    </div>
                </div>
            </div>
        </div>

         <!-- administration view of the games-->
        <div class="container">
             <div v-if="displayNav == 'adminGame'">
                 <admin-game :user=user></admin-game>
            </div>
        </div>

        <div class="container">
            <div v-if="displayNav == 'profile'">
                <profile-page  ></profile-page>
            </div>
        </div>

        <div class="container">
             <div class="row" v-show="displayNav == 'Group'">
                 <h1>{{ selectedGroup.name }}</h1>
                    <div class="button-row">
                            <div>
                                <button class="btn btn-primary" @click.prevent="setDisplayNav()"><i class="fas fa-home fa-1x" ></i></button>
                                <button class="btn btn-primary" @click.prevent="setDisplay('addPlayedGame')"><i class="fas fa-plus fa-1x" ></i></button>
                                <button class="btn btn-primary" @click.prevent="setDisplay('playedGames')"><i class="fas fa-list-ul fa-1x" ></i></button>
                                <button class="btn btn-primary" @click.prevent="setDisplay('groupStats')"><i class="fas fa-chart-pie fa-1x" ></i></button>
                                <button class="btn btn-primary" @click.prevent="setDisplay('editGroup')" v-if="selectedGroup.typeMember == 'Admin'"><i class="fas fa-pencil-alt fa-1x" ></i></button>
                                <button class="btn btn-primary" @click.prevent="setDisplay('groupUsers')"><i class="fas fa-users fa-1x" ></i></button>
                                <button class="btn btn-primary" @click.prevent="setDisplay('groupGames')"><i class="fas fa-dice fa-1x" ></i></button>
                            </div>
                    </div>
            </div>
        </div>

        <div class="container">
            <!-- Group Options-->
            <div v-if="display == 'addPlayedGame'">
                <h2>Add a new played game</h2>
                <div class="row">
                    <add-played-game :group=selectedGroup :submitOption="'Create'"></add-played-game>
                </div>
            </div>

            <div v-if="display == 'playedGames'">
                <h2>Played Games</h2>
                <div class="row">
                    <played-game :group=selectedGroup :user=user ></played-game>
                </div>
            </div>

            <div v-if="display == 'groupStats'">
                <div class="row">
                    <group-stat :group=selectedGroup></group-stat>
                </div>
            </div>

            <div v-if="display == 'editGroup' && selectedGroup.typeMember == 'Admin'">
                <h2>Edit group</h2>
                <div class="row">
                    <input-group v-if="selectedGroup.id > 0" :group=selectedGroup :submitOption="'Update'" :key=selectedGroup.id></input-group>
                </div>
            </div>

            <div v-if="display == 'groupUsers'">
                <h2>Group users</h2>
                <div class="row">
                    <group-user-list :group="selectedGroup"></group-user-list>
                </div>
            </div>

            <div v-if="display == 'groupGames'">
                <h2>Group games</h2>
                <div class="row">
                    <group-game-list v-if="selectedGroup.id > 0" :group=selectedGroup></group-game-list>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import navBar from '../IndexPage/navBar.vue';
    import login from '../IndexPage/login.vue';
    import unverifiedUser from '../GroupUserPage/unverifiedUser';
    import messageBox from '../../components/tools/messageBar.vue';
    import adminGame from '../GamePage/index.vue';
    import profilePage from '../ProfilePage/index.vue'

    import inputGroup from '../GroupPage/input';
    import groupGameList from '../GroupGamePage/list.vue';
    import groupUserList from '../GroupUserPage/list.vue';
    import playedGame from '../PlayedGamePage/index.vue';
    import groupStat from '../StatisticPage/index.vue';

    import addPlayedGame from '../PlayedGamePage/input.vue';

    export default {
        components: {
            messageBox,
            unverifiedUser,
            login,
            navBar,
            adminGame,
            profilePage,

            inputGroup,
            groupGameList,
            groupUserList,
            playedGame,
            addPlayedGame,
            groupStat
        },

        props: {
            'auth': {},
         },

        data () {
            return {
                user: {},
                displayNav: "",
                display: "",
                userGroups: {},
                selectedGroup: {},
                groupIndex: -1,
            }
        },

        methods: {
            //Navigation options
            setDisplay(display = ""){
                this.display = display;
                this.$bus.$emit('resetStatsPage', '');
            },

            setDisplayNav(display = ""){
                if(display == ""){
                    this.groupIndex = -1;
                }
                this.displayNav = display;
                this.setDisplay();
            },

             showCreateGroup(){
                if(this.display == 'showCreateGroup'){
                    this.setDisplay()
                }else{
                    this.selectedGroup = {};
                    this.groupIndex = -1;
                    this.display = 'showCreateGroup';
                }
            },

            setGroup(id){
                this.selectedGroup = this.userGroups[id];
                this.groupIndex = id;
                this.setDisplayNav("Group");
                this.setDisplay('playedGames');
            },

            /**
             * Api Calls for
             */
            getLoggedInUser(){
                apiCall.getData('profile')
                .then(response =>{
                    this.user = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            getGroups(){
                apiCall.getData('user-group')
                .then(response =>{
                    this.userGroups = response;
                    if(this.groupIndex >= 0){
                        this.selectedGroup = this.userGroups[this.groupIndex];
                    }
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },
        },

        mounted(){
            if(this.auth == true){
                this.getLoggedInUser();
                this.getGroups();
            }


            this.$bus.$on('display', value => {
                this.setDisplay(value);
            });

            this.$bus.$on('displayNav', value => {
                this.setDisplayNav(value);
            });

            this.$bus.$on('reloadGroups', () => {
                this.getGroups();
            });

            //this.$bus.$emit('showMessage', 'Welcome back, remove this message after testing', 'orange', '1000' );
        }
    }
</script>

<style scoped>

button {
    margin: 5px;
}

.button-row{
    width: 100%;
    text-align: center;
}

.group {
    background-color: orange;
    margin: 5px;
}

button{
    width: 10%;
}
</style>
