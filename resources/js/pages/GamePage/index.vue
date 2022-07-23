<template>
    <div>
        <list-unapproved></list-unapproved>

        <div class="button-row">
            <button class="btn btn-primary" @click.prevent="CreateShow"><i class="fas fa-plus fa-1x" ></i></button>
        </div><br>

        <div class="row"  v-show="showForm == 'Create'">
            <div class="container">
                <input-form :submitOption="'Create'"></input-form>
            </div>
        </div>

        <game-list></game-list>
    </div>
</template>

<script>
    import inputForm from '../GamePage/input.vue';
    import GameList from '../GamePage/list.vue';
    import listUnapproved from '../GamePage/listUnapprovedGames.vue';

    export default {
        components: {
            GameList,
            inputForm,
            listUnapproved,
        },

        data () {
            return {
                showForm: "",
            }
        },

        methods: {
            CreateShow(){
                if(this.showForm == 'Create'){
                    this.showForm = "";
                    return
                }
                this.showForm = 'Create';
            },
        },

        mounted(){

        }
    }
</script>

<style scoped>
.button-row{
    width: 100%;
    text-align: center;
}
</style>
