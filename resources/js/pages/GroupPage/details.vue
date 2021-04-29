<template>
    <div>
        <h1>{{  group.name}}
            <i class="fas fa-star" style="color:yellow;" @click.prevent="changeFavorite()" v-if="group.id == user.favorite_group_id"></i>
            <i class="far fa-star" @click.prevent="changeFavorite()" v-else></i>
        </h1>
        <div class="button-row">
             <button class="btn btn-primary" @click="navigation('home')"><i class="fas fa-home fa-1x" ></i></button>
            <button class="btn btn-primary" @click="navigation('addPlayedGame')"><i class="fas fa-plus fa-1x" ></i></button>
            <button class="btn btn-primary" @click="navigation('allPlayedGames')"><i class="fas fa-list-ul fa-1x" ></i></button>
            <button class="btn btn-primary" @click="navigation('groupStats')"><i class="fas fa-chart-pie fa-1x" ></i></button>
            <button class="btn btn-primary" @click="navigation('editGroup')" v-if="group.typeMember == 'Admin'"><i class="fas fa-pencil-alt fa-1x" ></i></button>
            <button class="btn btn-primary" @click="navigation('groupUsers')"><i class="fas fa-users fa-1x" ></i></button>
            <button class="btn btn-primary" @click="navigation('groupGames')"><i class="fas fa-dice fa-1x" ></i></button>
        </div>
        <router-view v-if="group != '' " name="groupDetails" :group=group></router-view>
    </div>
</template>

<script>

    import apiCall from '../../services/ApiCall.js';
    export default {
        components: {

        },

        data () {
            return {

            }
        },

        props: {
            'id': '',
         },

         computed: {
            group(){
                return this.$store.state.selectedGroup;
            },

            user(){ 
                return this.$store.state.LoggedInUser;
            }
        },

        methods: {
           getGroup(){
               if(this.group.id === undefined){
                   this.$store.dispatch('getSelectedGroup', {id: this.id});
                   return;
               }
               if(this.group.id == this.id){
                   return;
               }
               this.$store.dispatch('getSelectedGroup', {id: this.id});
               return;
           },

           navigation(name){
                if(this.$route.name == name){
                    return;
                }
                if(name == 'home'){
                    console.log('go home');
                    this.$store.dispatch('resetToDefault');   
                }
                this.$router.push({name: name, params: { id: this.id },})
           },

           changeFavorite(){
                apiCall.updateData( 'group/' + this.group.id + "/changeFavoriteGroup")
                .then(response =>{
                    this.$store.dispatch('getLoggedInUser');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },
        },

        mounted(){
            this.getGroup();
            this.$bus.$on('reloadGroup', () => {
                this.getGroup();
            });
        }
    }
</script>

<style scoped>
button {
    margin: 3px;
}

.button-row{
    width: 100%;
    text-align: center;
}

</style>
