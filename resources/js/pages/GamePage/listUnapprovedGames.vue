<template>
    <div v-if="dataList.data != ''">
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
            <tbody  v-for="data in dataList.data"  :key="data.id" >
                <tr>
                    <td>{{ data.id }}</td>
                    <td>{{ data.name }}</td>
                    <td>{{ data.year }}</td>
                    <td class="d-none d-sm-table-cell">{{ data.approved_by_admin }}</td>
                    <td class="d-none d-sm-table-cell">{{ data.players_min }}</td>
                    <td class="d-none d-sm-table-cell">{{ data.players_max }}</td>
                    <td ><span v-if="data.base_game">{{ data.base_game.name}}</span></td>
                    <td class="options-column">
                        <button class="btn btn-primary" @click.prevent="ApproveGame(data.id)"><i class="fas fa-check"></i></button>
                        <button class="btn btn-primary" @click.prevent="mergeGame(data.id)"><img :src="'images/layout/merge_icon.png'" style="heigth:16px; width:16px"  ></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="100" v-if="display == 'mergeGame' && selectedId == data.id">
                        <span>
                            <merge-game :gameId="selectedId" ></merge-game>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
         <vue-pagination  :pagination="dataList" @paginate="loadList()" :offset="4"></vue-pagination>

    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import VuePagination from '../../components/ui/pagination.vue';

    import ButtonInput from '../../components/ui/form/ButtonInput.vue';

    import inputForm from '../GamePage/input';
    import mergeGame from '../GamePage/merge.vue'

    export default {
        data () {
            return {
                'dataList' : { },
                headers: [
                    { 'header': 'Id'},
                    { 'header': 'Name'},
                    { 'header': 'Year'},
                    { 'header': 'Approved by admin'},
                    { 'header': 'Min'},
                    { 'header': 'Max'},
                    { 'header': 'Base game'},
                    { 'header': 'Options'},
                ],
                fields: [
                    { 'field': 'id'},
                    { 'field': 'name'},
                    { 'field': 'year'},
                    { 'field': 'approved_by_admin'},
                    { 'field': 'players_min'},
                    { 'field': 'players_max'},
                ],
                'selectedId': 0,
                'display': '',
            }
        },

        components: {
            VuePagination,
            inputForm,
            ButtonInput,
            mergeGame
        },

        methods: {
            loadList(){
                apiCall.getData('unapprovedgames?page=' + this.dataList.current_page)
                .then(response =>{
                    this.dataList = response;
                    this.selectedId = "";
                    this.display = "";
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            ApproveGame(id){
                apiCall.getData('unapprovedgames/' + id)
                .then(response =>{
                     this.loadList();
                     this.$bus.$emit('reloadList');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            mergeGame(id){
                if(this.selectedId == id && this.display == 'mergeGame'){
                    this.selectedId = "";
                    this.display = '';
                }else{
                    this.selectedId = id;
                    this.display = 'mergeGame';
                }
            },
        },

        mounted(){
            this.loadList();
            this.$bus.$on('reloadList', () => {
                    this.loadList();
            });
        }
    }
</script>

<style scoped>

</style>

