<template>
    <div class="container">
        <hr>
        <div>
            <add-game :group=group :nonGroupGames="nonGroupGames" v-if="group.typeMember == 'Admin'" ></add-game>
        </div>
        <br>

        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody  v-for="data in dataList.data"  :key="data.id" >
                    <tr>
                        <td>
                            {{ data.game.full_name }}
                        </td>
                        <td class="options-column">
                            <button class="btn btn-info" @click.prevent="showLink(data.id)" v-if="data.links.length > 0"><img :src="getImageUrl('images/layout/link_icon.png')"  style="heigth:14px; width:14px"></button>
                            <button class="btn btn-danger" @click.prevent="deleteGame(data)" v-if="group.typeMember == 'Admin' && data.links.length == 0"><i class="fas fa-trash fa-1x" ></i></button>
                            <button class="btn btn-info" @click.prevent="addLink(data.id)" v-if="group.typeMember == 'Admin'"><img :src="getImageUrl('images/layout/add_link_icon.png')" style="heigth:14px; width:14px"></button>



                            
                        </td>
                    </tr>
                    <tr v-if="selectedGroupGameLinkCreate == data.id">
                        <td colspan="2">
                            <add-link   :group_game="data" :submitOption="'Create'"></add-link>
                        </td>

                    </tr>
                    <tr v-if="selectedGroupGameLinkAdd == data.id">
                        <td colspan="100">
                            <links-list    :group_game="data" :links="data.links" :group="group"></links-list>
                        </td>

                    </tr>
            </tbody>
        </table>
        <vue-pagination  :pagination="dataList" @paginate="loadList()" :offset="4"></vue-pagination>


        <button class="btn btn-primary" @click.prevent="showAddNewGameForm" v-if="group.typeMember == 'Admin'">Add new game</button>
        <add-new-game :submitOption="'CreateFromGroup'" v-if="displayNewGame != ''"></add-new-game>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import VuePagination from '../../components/ui/pagination.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';
    import addGame from '../GroupGamePage/addGames.vue';
    import LinksList from '../GroupGameLinkPage/list.vue';
    import addLink from '../GroupGameLinkPage/input.vue';
    import addNewGame from '../GamePage/input.vue';


    export default {
        data () {
            return {
                'dataList' : { },
                'selectedGroupGameLinkAdd': 0,
                'selectedGroupGameLinkCreate': 0,
                'nonGroupGames': [],
                'displayNewGame': "",
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
            addNewGame,
        },

        methods: {
            loadList(){
                apiCall.getData('group/' + this.group.id + '/group-game?page=' + this.dataList.current_page)
                .then(response =>{
                    this.dataList = response;
                    this.selectedGroupGameLinkAdd = 0;
                    this.selectedGroupGameLinkCreate = 0;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            showAddNewGameForm(){
                if(this.displayNewGame == ""){
                    this.displayNewGame = "Show";
                }else{
                    this.displayNewGame = "";
                }
            },

            showLink(id){
                if(this.selectedGroupGameLinkAdd == id){
                    this.selectedGroupGameLinkAdd = 0;
                }else{
                    this.selectedGroupGameLinkAdd = id;
                     this.selectedGroupGameLinkCreate = 0;
                }
            },

            addLink(id){
                if(this.selectedGroupGameLinkCreate == id){
                    this.selectedGroupGameLinkCreate = 0;
                }else{
                    this.selectedGroupGameLinkCreate = id;
                    this.selectedGroupGameLinkAdd = 0;
                }
            },

            loadNonGroupGames(){
                apiCall.getData('group/' + this.group.id + '/search-non-group-games')
                .then(response =>{
                    this.nonGroupGames = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            deleteGame(groupGame){
                 apiCall.postData('group/' + this.group.id + '/group-game/' + groupGame.id + '/delete')
                .then(response =>{
                    this.loadList();
                    this.loadNonGroupGames();
                    this.message = "You've deleted " + groupGame.game.name + " from " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'red', '2000' );
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            getImageUrl(urlName){
                var localPath = "";
                if(process.env.NODE_ENV == 'development'){
                    localPath = "/boardgametracker/public_html"
                }
                return localPath + '/' + urlName;
            }
        },

        mounted(){
            this.loadList();
            this.loadNonGroupGames();
            this.$bus.$on('reloadGroupGames', () => {
                    this.loadList();
                    this.loadNonGroupGames();
                     this.displayNewGame = "";
            });
        }
    }
</script>

<style scoped>

</style>

