<template>
    <div v-if="unapprovedGames.data != ''">
        <h1>Unaproved Games</h1>

        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th class="d-none d-sm-table-cell">Approved by admin</th>
                    <th class="d-none d-sm-table-cell">Min</th>
                    <th class="d-none d-sm-table-cell">Max</th>
                    <th>Base game</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody  v-for="game in unapprovedGames.data"  :key="game.id" >
                <tr>
                    <td>{{ game.id }}</td>
                    <td>{{ game.name }}</td>
                    <td>{{ game.year }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.approved_by_admin }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.players_min }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.players_max }}</td>
                    <td ><span v-if="game.base_game">{{ game.base_game.name}}</span></td>
                    <td class="options-column">
                        <button class="btn btn-primary" @click.prevent="ApproveGame(game.id)"><i class="fas fa-check"></i></button>
                        <button class="btn btn-primary" @click.prevent="mergeGame(game.id)"><img :src="'images/layout/merge_icon.png'" style="heigth:16px; width:16px"  ></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="100" v-if="showForm == 'mergeGame' && selectedGameId == game.id">
                        <span>
                            <merge-game :selectedGame="game" ></merge-game>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import mergeGame from '../GamePage/merge.vue'

    export default {
        data () {
            return {
                'selectedGameId': 0,
                'showForm': '',
            }
        },

        components: {
            mergeGame
        },

        computed: {
            unapprovedGames(){
                this.showForm = "";
                this.selectedGameId = 0;

                if(this.$store.state.unapprovedGames.data == undefined ){
                    this.$store.dispatch('getUnapprovedGames');
                }
                return this.$store.state.unapprovedGames;
            }
        },

        methods: {
            loadList(){
                this.$store.dispatch('getUnapprovedGames');
            },

            ApproveGame(id){
                apiCall.postData('unapprovedgames/' + id)
                .then(response =>{
                    var message = "The game " + response.data.name + " has been approved";
                    this.$store.dispatch('getMessage', {message: message});
                    this.loadList();
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            mergeGame(id){
                if(this.selectedGameId == id && this.showForm == 'mergeGame'){
                    this.selectedGameId = "";
                    this.showForm = '';
                    return
                }
                this.selectedGameId = id;
                this.showForm = 'mergeGame';
            },
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>

