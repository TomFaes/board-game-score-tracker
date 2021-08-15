<template>
    <div>
        <table class="table table-hover table-sm " v-if="dataList">
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
            <tbody  v-for="data in dataList.data"  :key="data.id" >
                    <tr>
                        <td nowrap>{{convertDate(data.date)}}</td>
                        <td  class="d-none d-sm-table-cell">{{convertTime(data.time_played)}}</td>
                        <td>{{data.game.name}}  </td>
                         <td  class="d-none d-sm-table-cell">
                            <span  v-if="data.played_expansions != ''">
                                <span v-for="expansion in data.played_expansions"  :key="expansion.id">
                                    - {{ expansion.name }}<br>
                                </span>
                            </span>
                        </td>
                        <td>
                            {{ data.winner.full_name }}
                        </td>

                         <td  class="d-none d-sm-table-cell">{{data.remarks}}</td>
                         <td class="options-column">
                            <button class="btn btn-info" @click.prevent="setViewInput(data.id, 'View')"><i class="fa fa-info" style="heigth:14px; width:14px" ></i></button>
                            <button class="btn btn-primary"  v-if="group.type_member == 'Admin' || data.creator_id == user.id" @click.prevent="setViewInput(data.id, 'Update')"><i class="fa fa-edit" style="heigth:14px; width:14px"></i></button>
                            <button class="btn btn-danger" v-if="group.type_member == 'Admin'" @click.prevent="deletePlayedGame(data)" ><i class="fas fa-trash fa-1x" ></i></button>
                        </td>
                    </tr>
                    <tr v-if="playedGame.id == data.id">
                        <td colspan="100">
                            <edit-played-game :group=group :playedGame=playedGame :groupGames="groupGames" :groupUsers="groupUsers" :submitOption="view"></edit-played-game>
                        </td>
                    </tr>
            </tbody>
        </table>
        <vue-pagination  :pagination="dataList" @paginate="loadList()" :offset="4" v-if="dataList"></vue-pagination>
    </div>
</template>

<script>
    import EditPlayedGame from '../PlayedGamePage/input.vue';
    import apiCall from '../../services/ApiCall.js';
    import VuePagination from '../../components/ui/pagination.vue';
    import Moment from 'moment';

    export default {
        data () {
            return {
                'view': "",
            }
        },

         computed: {
            group(){
                return this.$store.state.selectedGroup;
             },

            dataList(){
                if(this.group.id == undefined){
                    return;
                }
                if(this.$store.state.playedGames.data == undefined ){
                    this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: '1'});
                }
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

        components: {
            EditPlayedGame,
            VuePagination,
            Moment
        },

        methods: {
            loadList(){
                if(this.group.id == undefined){
                    return;
                }
                this.view = "";
                this.$store.dispatch('resetPlayedGame',);
                return this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: this.dataList.current_page ?? '1'});

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
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            }
        },

        mounted(){
            this.$bus.$on('reloadPlayedGameList', () => {
                this.loadList();
            });
        },
    }
</script>

<style scoped>

</style>

