<template>
    <div>
        <table class="table table-hover table-sm ">
            <!--
            <table class="table table-striped table-bordered ">
-->
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
                        <td nowrap>{{convertDate(data['date'])}}</td>
                        <td  class="d-none d-sm-table-cell">{{convertTime(data['time_played'])}}</td>
                        <td>{{data['game']['name']}}  </td>
                         <td  class="d-none d-sm-table-cell">
                            <span  v-if="data.expansion != ''">
                                <span v-for="expansion in data.expansions"  :key="expansion.id">
                                    - {{ expansion.name }}<br>
                                </span>
                            </span>
                        </td>
                        <td>
                            <span v-if="data['winner']['user_id'] > 0">
                                    {{data['winner']['user']['firstname']}}  {{data['winner']['user']['name']}}
                                </span>
                                <span v-else>
                                    {{data['winner']['firstname']}} {{data['winner']['name']}}
                                </span>
                        </td>

                         <td  class="d-none d-sm-table-cell">{{data['remarks']}}</td>
                         <td class="options-column">
                            <button class="btn btn-info" @click.prevent="viewPlayedGame(data.id)"><i class="fa fa-info" style="heigth:14px; width:14px" ></i></button>
                            <button class="btn btn-primary"  v-if="group.typeMember == 'Admin' || data.creator_id == user.id" @click.prevent="editPlayedGame(data.id)"><i class="fa fa-edit" style="heigth:14px; width:14px"></i></button>
                            <button class="btn btn-danger" v-if="group.typeMember == 'Admin'" @click.prevent="deletePlayedGame(data)" ><i class="fas fa-trash fa-1x" ></i></button>
                        </td>
                    </tr>
                    <tr v-if="selectedPlayedGameId == data.id">
                        <td colspan="100">
                            <edit-played-game  v-if="selectedPlayedGameId == data.id" :group=group :playedGame=data :submitOption="view"></edit-played-game>
                        </td>
                    </tr>
            </tbody>
        </table>
        <vue-pagination  :pagination="dataList" @paginate="loadList()" :offset="4"></vue-pagination>
    </div>
</template>

<script>
    import EditPlayedGame from '../PlayedGamePage/input.vue';
    import apiCall from '../../services/ApiCall.js';
    import VuePagination from '../../components/ui/pagination.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';
    import Moment from 'moment';

    export default {
        data () {
            return {
                'selectedPlayedGameId': "",
                fields: [
                    { 'field': 'date'},
                     { 'field': 'time_played'},
                    { 'field': 'game_id'},
                    { 'field': 'expansion'},
                    { 'field': 'winner_id'},
                    { 'field': 'remarks'},
                ],
                'view': "",
            }
        },

        props: {
            'group': {},
            'user': {},
        },

        watch:{
            group(){
                this.loadList();
            }
         },

          computed: {
            dataList(){
                return this.$store.state.playedGames;
            },

            
        },

        

        components: {
            EditPlayedGame,
            VuePagination,
            ButtonInput,
            Moment
        },

        methods: {
            loadList(){
                this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: this.dataList.current_page ?? '1'});
                return;
            },

            viewPlayedGame(id){
                if(this.selectedPlayedGameId == id && this.view == "View"){
                    this.selectedPlayedGameId = id = "";
                    this.view = "";
                }else{
                    this.selectedPlayedGameId = id;
                    this.view = "View";
                }
            },

            editPlayedGame(id){
                if(this.selectedPlayedGameId == id && this.view == "Update"){
                    this.selectedPlayedGameId = id = "";
                   this.view = "";
                }else{
                    this.selectedPlayedGameId = id;
                    this.view = "Update";
                }
            },

            convertDate(value){
                return Moment(value, "YYYY-MM-DD").format('DD-MM-YYYY');
            },

            convertTime(value){
                if(value != null){
                     return Moment(value, "hh:mm:ss").format('hh:mm');
                }
                return "";

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
            
            //this.loadList();
            this.$bus.$on('reloadPlayedGameList', () => {
                this.selectedPlayedGameId = "";
                this.loadList();
            });
        },

        created(){
            if(this.group.id != undefined && this.dataList.data == undefined){
                this.loadList();
            }
        }

    }
</script>

<style scoped>

</style>

