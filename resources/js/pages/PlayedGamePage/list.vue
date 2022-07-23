<template>
    <div>
        <table class="table table-hover table-sm " v-if="listPlayedGames">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="d-none d-sm-table-cell">Time played</th>
                    <th >Game</th>
                    <th class="d-none d-sm-table-cell">Expansion</th>
                    <th>Winner</th>
                    <th class="d-none d-sm-table-cell">Remarks</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody  v-for="game in listPlayedGames.data"  :key="game.id" >
                    <tr>
                        <td nowrap>{{convertDate(game.date)}}</td>
                        <td  class="d-none d-sm-table-cell">{{convertTime(game.time_played)}}</td>
                        <td>{{game.game.name}}  </td>
                         <td  class="d-none d-sm-table-cell">
                            <span  v-if="game.played_expansions != ''">
                                <span v-for="expansion in game.played_expansions"  :key="expansion.id">
                                    - {{ expansion.name }}<br>
                                </span>
                            </span>
                        </td>
                        <td>
                            {{ game.winner.full_name }}
                        </td>

                         <td  class="d-none d-sm-table-cell">{{game.remarks}}</td>
                         <td class="options-column">
                            <button class="btn btn-info" @click.prevent="setViewInput(game.id, 'View')"><i class="fa fa-info" style="heigth:14px; width:14px" ></i></button>
                            <button class="btn btn-primary"  v-if="group.type_member == 'Admin' || game.creator_id == user.id" @click.prevent="setViewInput(game.id, 'Update')"><i class="fa fa-edit" style="heigth:14px; width:14px"></i></button>
                            <button class="btn btn-danger" v-if="group.type_member == 'Admin'" @click.prevent="deletePlayedGame(game)" ><i class="fas fa-trash fa-1x" ></i></button>
                        </td>
                    </tr>
                    <tr v-if="playedGame.id == game.id">
                        <td colspan="100">
                            <edit-played-game :group=group :playedGame=playedGame :groupGames="groupGames" :groupUsers="groupUsers" :submitOption="view" :currentPage="listPlayedGames.current_page"></edit-played-game>
                        </td>
                    </tr>
            </tbody>
        </table>
        <vue-pagination  :pagination="listPlayedGames" @paginate="loadList()" :offset="4" v-if="listPlayedGames"></vue-pagination>
    </div>
</template>

<script>
    import EditPlayedGame from '../PlayedGamePage/input.vue';
    import apiCall from '../../services/ApiCall.js';
    import VuePagination from '../../components/ui/pagination.vue';
    import Moment from 'moment';

    export default {
        components: {
            EditPlayedGame,
            VuePagination,
            Moment
        },

        data () {
            return {
                'view': "",
            }
        },

         computed: {
            group(){
                return this.$store.state.selectedGroup;
             },

            user(){
                return this.$store.state.loggedInUser;
            },

            listPlayedGames(){
                if(this.group.id == undefined){
                    return;
                }
                if(this.$store.state.playedGames.data == undefined ){
                    this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: '1'});
                }
                this.$store.dispatch('resetPlayedGame');

                return this.$store.state.playedGames;
            },

            playedGame(){
                return this.$store.state.selectedPlayedGame;
            },

            groupGames(){
                return this.$store.state.selectedGroupGames;

            },

            groupUsers(){
                return this.$store.state.selectedGroupUsers;
            }
        },

        methods: {
            loadList(){
                if(this.group.id == undefined){
                    return;
                }
                this.view = "";
                this.$store.dispatch('resetPlayedGame',);
                return this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: this.listPlayedGames.current_page ?? '1'});

            },

            setSelectedGame(id){
                this.$store.dispatch('getSelectedPlayedGame', {groupId: this.group.id,  id: id});
                if(this.$store.state.selectedGroupGames.data == undefined){
                    this.$store.dispatch('getSelectedGroupGames', {groupId: this.group.id});
                }
                if(this.$store.state.selectedGroupUsers.data == undefined){
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
            },

            setViewInput(id, view = ""){
                if(this.view == view && this.playedGame.id == id){
                    this.view = "";
                    return this.$store.dispatch('resetPlayedGame');
                }

                this.view = view;
                if(this.playedGame.id == undefined){
                    this.setSelectedGame(id);
                    return;
                }

                if(this.playedGame.id != id){
                    this.setSelectedGame(id);
                    return;
                }
                return;
            },

            convertDate(value){
                return Moment(value, "YYYY-MM-DD").format('DD-MM-YYYY');
            },

            convertTime(value){
                if(value == null){
                    return;
                }
                return Moment(value, "hh:mm:ss").format('hh:mm');
            },

            deletePlayedGame(data){
                if(confirm('are you sure you want to delete this result of ' + data.game.name + '?')){
                    apiCall.postData('group/' + this.group.id + '/played/' + data.id + '/delete')
                    .then(response =>{
                        this.loadList();
                    }).catch(error => {
                        if(error.status === 401){
                            this.$store.dispatch('getMessage', {message: error.data});
                        }
                    });
                }
            }
        },

        mounted(){

        },
    }
</script>

<style scoped>

</style>

