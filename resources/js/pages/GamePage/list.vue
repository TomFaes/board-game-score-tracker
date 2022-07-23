<template>
    <div>
        <table class="table table-hover table-sm" v-if="games.data">
            <thead >
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th class="d-none d-sm-table-cell">Approved by admin</th>
                    <th class="d-none d-sm-table-cell">Min</th>
                    <th class="d-none d-sm-table-cell">Max</th>
                    <th class="d-none d-sm-table-cell">Expansions</th>
                    <th class="d-none d-sm-table-cell">In group</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody v-for="game in games.data"  :key="game.id" >
                <tr>
                    <td>{{ game.id }}</td>
                    <td>{{ game.full_name }}</td>
                    <td>{{ game.year }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.approved_by_admin }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.players_min }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.players_max }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.total_expansions }}</td>
                    <td class="d-none d-sm-table-cell">{{ game.total_games_in_group_games }}</td>
                    <td class="options-column">
                        <button class="btn btn-primary" @click.prevent="showEditFormType(game.id, 'Edit game')"><i class="fas fa-pencil-alt fa-1x" ></i></button>
                        <button class="btn btn-primary" @click.prevent="showEditFormType(game.id, 'Merge game')"><img :src="'images/layout/merge_icon.png'" style="heigth:16px; width:16px"  ></button>
                        <button class="btn btn-danger" @click.prevent="deleteRow(game.id)"><i class="fas fa-trash fa-1x" ></i></button>
                    </td>
                </tr>
                <tr v-if="selectedGameId == game.id">
                    <td colspan="100%">
                        <input-form  v-if="showForm == 'Edit game'" :game=game :submitOption="'Update'" :currentPage="games.current_page" :itemsPerPage="itemsPerPage"></input-form>
                        <merge-game  v-if="showForm == 'Merge game'" :selectedGame="game" :currentPage="games.current_page" :itemsPerPage="itemsPerPage" ></merge-game>
                    </td>
                </tr>
            </tbody>
        </table>
         <vue-pagination  :pagination="games" @paginate="loadList()" :offset="4" v-if="games"></vue-pagination>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import VuePagination from '../../components/ui/pagination.vue';
    import inputForm from '../GamePage/input.vue';
    import mergeGame from '../GamePage/merge.vue'

    export default {
        data () {
            return {
                'itemsPerPage': 10,
                'showForm': '',
                'selectedGameId': 0,
            }
        },

        components: {
            VuePagination,
            inputForm,
            mergeGame
        },

        computed: {
            user(){
                return this.$store.state.loggedInUser;
            },

            games(){
                this.showForm = "";
                this.selectedGameId = 0;

                if(this.$store.state.games.data == undefined ){
                    this.$store.dispatch('getGames', {currentPage: '1', itemsPerPage: this.itemsPerPage});
                }
                return this.$store.state.games;
            },
        },

        methods: {
            loadList(){
                this.$store.dispatch('getGames', {currentPage: this.games.current_page ?? 1, itemsPerPage: this.itemsPerPage });
            },

            showEditFormType(id, showForm = ""){
                if(showForm == ""){
                    return;
                }
                if(this.selectedGameId == id && this.showForm == showForm){
                    this.selectedGameId = 0;
                    this.showForm = '';
                    return;
                }
                this.selectedGameId = id;
                this.showForm = showForm;
            },

            deleteRow(id){
                if(confirm('are you sure you want to delete this game?')){
                    apiCall.postData('game/' + id + '/delete')
                    .then(response =>{
                        if(response.status === 200){
                            this.$store.dispatch('getMessage', {message: response.data, color: "red", time:5000});
                            return;
                        }
                        this.loadList();
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            }
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>

