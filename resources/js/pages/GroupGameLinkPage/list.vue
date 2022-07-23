<template>
    <div>
        <br>
        <table class="table table-sm table-info">
            <thead>
                <tr>
                    <th>Link</th>
                     <th>Description</th>
                     <th>Options</th>
                </tr>
            </thead>
            <tbody v-for="link in links.data" :key="link.id" >
                <tr>
                    <td><a v-bind:href="link.link" target="_blank"> {{link.name}} </a></td>
                    <td>{{ link.description }}</td>
                    <td class="options-column">
                        <span v-if="group.type_member == 'Admin'">
                            <button class="btn btn-primary" @click.prevent="editLink(link.id)"><img :src="getImageUrl('images/layout/edit_link_icon.png')"  style="heigth:14px; width:14px"></button>
                            <button class="btn btn-danger" @click.prevent="deleteLink(link.id, link.group_game_id)"><img :src="getImageUrl('images/layout/delete_link_icon.png')"  style="heigth:14px; width:14px"></button>
                        </span>
                    </td>
                </tr>
                <tr v-if="selectedGroupGameLink == link.id">
                    <td colspan="100">
                        <update-link :group_game_link="link" :group_game="group_game" :currentPage="currentPage" :submitOption="'Update'"></update-link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import updateLink from '../GroupGameLinkPage/input.vue'

    export default {
        data () {
            return {
                display: "",
                selectedGroupGameLink: 0,
            }
        },

        props: {
            'links': {},
            'group': {},
            'group_game': {},
            'currentPage': '',
         },

        components: {
            updateLink,
        },

        methods: {
            editLink(id){
                if(this.selectedGroupGameLink == id){
                    this.selectedGroupGameLink = 0;
                }else{
                    this.selectedGroupGameLink = id;
                }
            },

            deleteLink(id, group_game_id){
                if(confirm('are you sure you want to delete this link?')){
                    apiCall.updateData('group/game/' + group_game_id +'/link/' + id + '/delete')
                    .then(response =>{
                        this.$store.dispatch('getSelectedGroupGames', {groupId: this.group.id, pageItems: 20, currentPage: this.currentPage});
                        var message = "You've deleted a link from " + this.group_game.game.name;
                        this.$store.dispatch('getMessage', {message: message, color: 'red', time: 5000});
                        this.selectedGroupGameLink = 0;
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            },

            getImageUrl(urlName){
                if(process.env.NODE_ENV == 'development'){
                    return "/boardgametracker/public_html/" + urlName;
                }
                return '/' + urlName;
            }
        },

        mounted(){

        }
    }
</script>

<style scoped>

</style>

