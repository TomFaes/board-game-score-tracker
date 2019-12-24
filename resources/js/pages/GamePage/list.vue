<template>
    <div>
        <hr>
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th v-for="header in headers"  :key="header.id">{{ header.header }}</th>
                </tr>
            </thead>
            <tbody  v-for="data in dataList.data"  :key="data.id" >
                    <tr>
                        <td v-for="field in fields"  :key="field.field">{{ data[field.field] }}</td>
                        <td ><span v-if="data.base_game">{{ data.base_game.name}}</span></td>
                        <td>
                            <button class="btn btn-primary" v-if="updateField != data.id" @click.prevent="updateRow(data.id)">Update</button>
                            <button class="btn btn-primary" v-if="updateField == data.id" @click.prevent="hideUpdate">Hide update</button>
                            <button class="btn btn-danger" @click.prevent="deleteRow(data.id)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="updateField == data.id">
                        <td colspan="100%">
                            <inputForm  v-if="updateField == data.id" :game=data :basegame=basegame :submitOption="'Update'"></inputForm>
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
                    { 'header': 'Base game'},
                ],
                fields: [
                    { 'field': 'id'},
                    { 'field': 'name'},
                    { 'field': 'year'},
                    { 'field': 'approved_by_admin'},
                    { 'field': 'players_min'},
                    { 'field': 'players_max'},
                ],
                'updateField' : 0,
            }
        },

        components: {
            VuePagination,
            inputForm,
            ButtonInput
        },

        methods: {
            loadList(){
                apiCall.getData('game?page=' + this.dataList.current_page)
                .then(response =>{
                    this.dataList = response;
                    this.updateField = '';
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

            updateRow(id){
                this.updateField = id;
            },

            hideUpdate(){
                this.updateField = 0;
            },

            deleteRow(id){
                apiCall.deleteData('game/' + id)
                .then(response =>{
                     this.loadList();
                }).catch(() => {
                    console.log('handle server error from here');
                });
            }
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

