<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-0"></div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                        <router-link :to="{ name: 'newGroup'}" >
                        <div class="tile blue">
                            <div>
                                New group
                            </div>
                        </div>
                    </router-link>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-0">
                    <router-link :to="{ name: 'joinGroup'}" >
                        <div class="tile blue">
                            <div>
                                Join group
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12"  v-for="(data) in userGroups"  :key="data.id">
                        <router-link :to="{ name: 'allPlayedGames', params: { id: data.id }}" >
                        <div class="tile orange">
                            <div>
                                {{data.name}}
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        components: {

        },

        data () {
            return {

            }
        },

        computed: {
            userGroups(){
                return this.$store.state.userGroups;
            },
        },

        props: {
            'auth': {},
         },

        methods: {
            getGroups(){
                if(this.$store.state.userGroups[0] != undefined){
                    return;
                }
                this.$store.dispatch('getUserGroups');
            },
        },

        mounted(){
            this.getGroups();
        }
    }
</script>

<style scoped>
a {
    text-decoration: none;
 }
</style>
