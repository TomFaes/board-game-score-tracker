<template>
    <div>
        <span v-for="link in links" :key="link.id">
            <a v-bind:href="link.link" target="_blank"> {{link.name}} </a>
            <span v-if="group.typeMember == 'Admin'">
                <img :src="'images/layout/edit_link_icon.png'"  style="heigth:16px; width:16px"   @click.prevent="editLink(link.id)">
                <update-link v-show="display == 'update'" v-if="selectedGroupGameLink == link.id" :group_game_link="link" :submitOption="'Update'"></update-link>
                <img :src="'images/layout/delete_link_icon.png'"  style="heigth:16px; width:16px"   @click.prevent="deleteLink(link.id, link.group_game_id)">
            </span>

            <br>
            <i>{{link.description}}</i>
            <hr>
        </span>
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
         },

        components: {
            updateLink,
        },

        methods: {
            editLink(id){
                if(this.display == 'update'){
                    this.display = '';
                    this.selectedGroupGameLink = 0;
                }else{
                    this.display = 'update';
                    this.selectedGroupGameLink = id;
                }
            },

            deleteLink(id, group_game_id){
                apiCall.deleteData('group/game/' + group_game_id +'/link/' + id)
                .then(response =>{
                      this.$bus.$emit('reloadList');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            }
        },

        mounted(){
            this.$bus.$on('reloadList', () => {
                    this.display = "";
            });
        }
    }
</script>

<style scoped>

</style>

