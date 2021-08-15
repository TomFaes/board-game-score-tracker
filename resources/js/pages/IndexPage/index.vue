<template>
    <div style="width: 100%">
        <div class="container-fluid">
            <global-layout>
                <nav-bar :user=user></nav-bar><br>
                <message-box></message-box>
                <router-view :key="$route.path" :user=user></router-view>
            </global-layout>
        </div>
    </div>
</template>

<script>
    import navBar from '../IndexPage/navBar.vue';
    import login from '../IndexPage/login.vue';

    import joinGroup from '../GroupUserPage/joinGroup.vue'

    import messageBox from '../../components/tools/messageBar.vue';

    export default {
        components: {
            navBar,
            login,
            messageBox,
            joinGroup
        },

        props: {
            'auth': {},
         },

        data () {
            return {

            }
        },

        computed: {
            user(){
                return this.$store.state.LoggedInUser;
            }
        },

        methods: {

        },

        mounted(){

        },
    }
</script>

<style scoped>

button {
    margin: 5px;
}

.button-row{
    width: 100%;
    text-align: center;
}

.group {
    background-color: orange;
    margin: 5px;
}

button{
    width: 10%;
}
</style>
