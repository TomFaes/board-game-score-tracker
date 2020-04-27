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
            <tbody v-for="link in group_game.links" :key="link.id" >
                <tr>
                    <td><a v-bind:href="link.link" target="_blank"> {{link.name}} </a></td>
                    <td>{{ link.description }}</td>
                    <td class="options-column">
                        <span v-if="group.typeMember == 'Admin'">
                            <button class="btn btn-primary" @click.prevent="editLink(link.id)"><img :src="'images/layout/edit_link_icon.png'"  style="heigth:14px; width:14px"></button>
                            <button class="btn btn-danger" @click.prevent="deleteLink(link.id, link.group_game_id)"><img :src="'images/layout/delete_link_icon.png'"  style="heigth:14px; width:14px"></button>
                        </span>
                    </td>
                </tr>
                <tr v-if="selectedGroupGameLink == link.id">
                    <td colspan="100">
                        <update-link :group_game_link="link" :group_game="group_game" :submitOption="'Update'"></update-link>
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
                    apiCall.deleteData('group/game/' + group_game_id +'/link/' + id)
                    .then(response =>{
                        this.$bus.$emit('reloadGroupGames');
                        this.message = "You've deleted a link from " + this.group_game.game.name;
                        this.$bus.$emit('showMessage', this.message,  'red', '2000' );
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }
            }
        },

        mounted(){
            this.$bus.$on('hideLinkList', () => {
                    this.display = "";
            });

        }
    }
</script>

<style scoped>

</style>

