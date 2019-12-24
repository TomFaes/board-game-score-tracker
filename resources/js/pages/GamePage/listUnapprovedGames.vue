<template>
    <div v-if="dataList.data != ''">
        <hr>
        Unaproved Games
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
                            <button class="btn btn-primary" @click.prevent="ApproveGame(data.id)">Approve game</button>
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
            }
        },

        components: {
            VuePagination,
            inputForm,
            ButtonInput
        },

        methods: {
            loadList(){
                apiCall.getData('unapprovedgames?page=' + this.dataList.current_page)
                .then(response =>{
                    this.dataList = response;
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

