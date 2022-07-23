<template>
    <div v-if="groupUsers">
       <create-played-game :group=group :groupGames="groupGames" :groupUsers="groupUsers" :submitOption="'Create'"></create-played-game>
    </div>
</template>

<script>
    import createPlayedGame from '../PlayedGamePage/input.vue';

    export default {
        components: {
            createPlayedGame,
        },

        data () {
            return {
                'view': "",
            }
        },

        props: {
            'group': {},
         },

         computed: {
            groupGames(){
                if(this.group.id === undefined){
                    return;
                }

                if(this.$store.state.selectedGroupGames.data == undefined){
                    this.$store.dispatch('getSelectedGroupGames', {groupId: this.group.id});
                }
                return this.$store.state.selectedGroupGames;
            },

            groupUsers(){
                if(this.group.id === undefined){
                    return;
                }

                if(this.$store.state.selectedGroupUsers.data == undefined){
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
                return this.$store.state.selectedGroupUsers;
            }
        },

        methods: {


        },

        mounted(){
            this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});

        },
    }
</script>

<style scoped>

</style>

