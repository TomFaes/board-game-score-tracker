<template>
    <div>
        <list-unapproved></list-unapproved>

        <div class="button-row">
            <button class="btn btn-primary" @click.prevent="CreateShow"><i class="fas fa-plus fa-1x" ></i></button>
        </div><br>

        <div class="row"  v-show="display == 'Create'">
            <div class="container">
                <inputForm :submitOption="'Create'"></inputForm>
            </div>
        </div>

        <list></list>

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
.button-row{
    width: 100%;
    text-align: center;
}
</style>
