<template>
    <div>
        <table class="table table-hover table-sm" v-if="dataList">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th class="d-none d-sm-table-cell">Approved by admin</th>
                    <th class="d-none d-sm-table-cell">Min</th>
                    <th class="d-none d-sm-table-cell">Max</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody  v-for="data in dataList.data"  :key="data.id" >
                <tr>
                    <td>{{ data.id }}</td>
                    <td>{{ data.full_name }}</td>
                    <td>{{ data.year }}</td>
                    <td class="d-none d-sm-table-cell">{{ data.approved_by_admin }}</td>
                    <td class="d-none d-sm-table-cell">{{ data.players_min }}</td>
                    <td class="d-none d-sm-table-cell">{{ data.players_max }}</td>
                    <td class="options-column">
                        <button class="btn btn-primary" @click.prevent="editGame(data.id)"><i class="fas fa-pencil-alt fa-1x" ></i></button>
                        <button class="btn btn-primary" @click.prevent="mergeGame(data.id)"><img :src="'images/layout/merge_icon.png'" style="heigth:16px; width:16px"  ></button>
                        <button class="btn btn-danger" @click.prevent="deleteRow(data.id)"><i class="fas fa-trash fa-1x" ></i></button>
                    </td>
                </tr>
                <tr v-if="selectedId == data.id">
                    <td colspan="100%">
                        <inputForm  v-if="display == 'editGame'" :game=data :basegame=basegame :submitOption="'Update'"></inputForm>
                        <span v-if="display == 'mergeGame'">
                            <merge-game :gameId="data.id" ></merge-game>
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
                'basegame': {},
                headers: [
                    { 'header': 'Id'},
                    { 'header': 'Name'},
                    { 'header': 'Year'},
                    { 'header': 'Approved by admin'},
                    { 'header': 'Min'},
                    { 'header': 'Max'},
                    { 'header': 'Options'},
                ],
                fields: [
                    { 'field': 'id'},
                    { 'field': 'full_name'},
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
            mergeGame,
            ButtonInput
        },

        methods: {
            loadList(){
                apiCall.getData('game?page=' + this.dataList.current_page + '&page_items=40')
                .then(response =>{
                    this.dataList = response;
                    this.updateField = '';
                    this.display = '';
                }).catch(() => {
                    console.log('handle server error from here');
                });

                apiCall.getData('basegame')
                .then(response =>{
                    this.basegame = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            editGame(id){
                if(this.selectedId == id && this.display == 'editGame'){
                    this.selectedId = "";
                    this.display = '';

                }else{
                    this.selectedId = id;
                    this.display = 'editGame';
                }
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

            updateRow(id){
                this.updateField = id;
            },

            hideUpdate(){
                this.updateField = 0;
            },

            deleteRow(id){
                if(confirm('are you sure you want to delete this game?')){
                    apiCall.postData('game/' + id + '/delete')
                    .then(response =>{
                        this.loadList();
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            }
        },

        mounted(){
            this.loadList();
            this.$bus.$on('reloadGameList', () => {
                    this.loadList();
            })
        }
    }
</script>

<style scoped>

</style>

