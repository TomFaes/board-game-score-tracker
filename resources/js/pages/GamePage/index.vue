<template>
    <div>
        <list-unapproved></list-unapproved>

        <div class="row" >
            <button class="btn btn-primary" @click.prevent="CreateShow"><i class="fas fa-plus fa-1x" ></i></button>
        </div><br>

        <div class="row"  v-show="display == 'Create'">
            <div class="container">
                <inputForm  v-if="display == 'Create'" :submitOption="'Create'"></inputForm>
            </div>
        </div>

        <div>
            <list></list>
        </div>
    </div>
</template>

<script>
    import inputForm from '../GamePage/input.vue';
    import list from '../GamePage/list.vue';
    import listUnapproved from '../GamePage/listUnapprovedGames.vue';

    export default {
        components: {
            list,
            inputForm,
            listUnapproved,
        },

        data () {
            return {
                display: "",
            }
        },

        methods: {
            CreateShow(){
                if(this.display == 'Create'){
                    this.display = "";
                }else{
                    this.display = 'Create';
                }
            },
        },

        mounted(){
            this.$bus.$on('resetGameDisplay', () => {
                    this.display = '';
            });
        }
    }
</script>

<style scoped>
.title{
    width: 90%;
    text-align: center;
}
</style>
