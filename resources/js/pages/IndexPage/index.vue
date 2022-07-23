<template>
    <div style="width: 100%; padding: 5px">
        <nav-bar :user="user"></nav-bar>
        <global-layout>
            <message-box :message="message"></message-box>
            <br>
        </global-layout>
        <global-layout>
            <router-view :key="$route.path" :user="user"></router-view>
        </global-layout>
    </div>
</template>

<script>
    import login from '../IndexPage/login.vue';
    import messageBox from '../../components/tools/messageBar.vue';
    import NavBar from '../IndexPage/navBar.vue';
    import GlobalLayout from '../../components/global/globalLayout.vue';

    export default {
        components: {
            login,
            messageBox,
            NavBar,
            GlobalLayout
        },

        computed: {
            user(){
                return this.$store.state.loggedInUser;
            },

            message(){
                return this.$store.state.message;
            }
        },

        mounted(){
            //this.$store.dispatch('getMessage', {message: "dit is het bericht", color: 'blue', time: 50000});
        },
    }
</script>

<style scoped>

</style>
