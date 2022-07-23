<template>
    <div>
        <br>
        <!-- Default actions: Create new group, Join group -->
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <img :src="storageURL + 'groupImages/DefaultGroepImage_1.jpg'" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title center">New Group</h3>
                        <p class="card-text">You can create a new group here</p>
                    </div>
                    <div class="card-footer center">
                        <small class="text-muted">
                            <router-link :to="{ name: 'newGroup'}" class="btn btn-primary">New group </router-link>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col col-lg-4 col-md-6 col-sm-12 g-4">
                <div class="card">
                    <img :src="storageURL + 'groupImages/DefaultGroepImage.jpg'" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title center">Join group</h3>
                        <p class="card-text">You can join a group here</p>
                    </div>
                    <div class="card-footer center">
                        <small class="text-muted">
                            <router-link :to="{ name: 'joinGroup'}" class="btn btn-primary">Join group</router-link>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <!-- Default actions: List of all groups -->
       <div class="row row-cols-1 row-cols-md-3  g-4">
            <div class="card" v-for="(data) in groupsOfUser" :key="data.id">
                <img :src="storageURL + 'groupImages/DefaultGroepImage_1.jpg'" class="card-img-top">
                <div class="card-body">
                    <h3 class="card-title center">{{data.name}}</h3>
                    <p class="card-text">{{data.description}}</p>
                </div>
                <div class="card-footer center">
                    <small class="text-muted">
                        <router-link :to="{ name: 'allPlayedGames', params: { id: data.id }}" class="btn btn-primary">Go to {{  data.name}}</router-link>
                    </small>
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
                "storageURL": "",
            }
        },

        computed: {
            groupsOfUser(){
                return this.$store.state.groupsOfUser;;
            },
        },

        props: {

         },

        methods: {
            getGroups(){
                if(this.$store.state.groupsOfUser[0] != undefined){
                    return;
                }
                this.$store.dispatch('getGroupsOfUser');
            },
        },

        mounted(){
            this.getGroups();
            this.storageURL = process.env.MIX_STORAGE_URL;
        }
    }
</script>

<style scoped>
    a {
        text-decoration: none;
    }

    .card-img-top {
        width: 100%;
        height: 250px;
    }
</style>
