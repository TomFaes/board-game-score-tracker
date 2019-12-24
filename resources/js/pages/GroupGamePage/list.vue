<template>
    <div>
        <hr>


        <add-game :group=group></add-game>

        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th v-for="header in headers"  :key="header.id">{{ header.header }}</th>
                </tr>
            </thead>
            <tbody  v-for="data in dataList.data"  :key="data.id" >
                    <tr>
                        <td v-for="field in fields"  :key="field.field">{{ data[field.field] }}</td>
                        <td>
                            {{ data.game.name }}
                            <i class="fas fa-trash-alt" @click.prevent="deleteGame(data.id)" v-if="group.typeMember == 'Admin'" ></i>
                            <span v-if="data.links.length > 0">
                                <img :src="'images/layout/link_icon.png'"  style="heigth:16px; width:16px"   @click.prevent="showLink(data.id)">
                            </span>
                            <img :src="'images/layout/add_link_icon.png'" style="heigth:16px; width:16px"  @click.prevent="addLink(data.id)" v-if="group.typeMember == 'Admin'" >
                            <br>
                            <add-link  v-if="selectedGroupGameLink == data.id" v-show="displayAddLink == 'addLink'" :group_game="data" :submitOption="'Create'"></add-link>
                            <links-list  v-if="selectedGroupGameLink == data.id" v-show="display == 'showLink'"  :links="data.links" :group="group"></links-list>
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
    import addGame from '../GroupGamePage/addGames.vue';
    import LinksList from '../GroupGameLinkPage/list.vue'
    import addLink from '../GroupGameLinkPage/input.vue'



    export default {
        data () {
            return {
                'dataList' : { },
                headers: [
                    { 'header': 'Id'},
                    { 'header': 'Game'},

                ],
                fields: [
                    { 'field': 'id'},
                ],
                display: "",
                displayAddLink: "",
                selectedGroupGameLink: 0,

            }
        },

        props: {
            'group': {},
        },

        watch:{
            'group'(){
                this.loadList();
            }
         },

        components: {
            VuePagination,
            ButtonInput,
            addGame,
            LinksList,
            addLink,

        },

        methods: {
            loadList(){
                apiCall.getData('group/' + this.group.id + '/group-game?page=' + this.dataList.current_page)
                .then(response =>{
                    this.dataList = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            showLink(id){
                if(this.display == 'showLink'){
                    this.display = 0;
                    this.selectedGroupGameLink = 0;
                }else{
                    this.display = 'showLink';
                    this.selectedGroupGameLink = id;
                }
            },

            addLink(id){
                if(this.displayAddLink == 'addLink' && this.selectedGroupGameLink == id){
                    this.displayAddLink = 0;
                    this.selectedGroupGameLink = 0;
                }else{
                    this.displayAddLink = 'addLink';
                    this.selectedGroupGameLink = id;
                }
            },

            deleteGame(id){
                apiCall.deleteData('group/' + this.group.id + '/group-game/' + id)
                .then(response =>{
                    console.log(response);
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

