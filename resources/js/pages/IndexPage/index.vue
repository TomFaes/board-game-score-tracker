<template>
    <div style="width: 100%">
        <div class="container-fluid">
            <global-layout>
                <nav-bar :user=user></nav-bar><br>
                <message-box></message-box>
                <unverified-user v-if="auth == true"></unverified-user>
                <router-view :key="$route.path" :user=user></router-view>
            </global-layout>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import navBar from '../IndexPage/navBar.vue';
    import login from '../IndexPage/login.vue';

    import messageBox from '../../components/tools/messageBar.vue';
    import unverifiedUser from '../GroupUserPage/unverifiedUser.vue';

    export default {
        components: {
            navBar,
            login,
            messageBox,
            unverifiedUser,
        },

        props: {
            'auth': {},
         },

        data () {
            return {
                //user: {},
                selectedGroup: {},
                displayNav: "",
                display: "",
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
            //this.$bus.$emit('showMessage', 'Welcome back, remove this message after testing', 'orange', '1000' );
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
