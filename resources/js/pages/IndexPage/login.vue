<template>
    <div>
        <span>
            <h3>Welcome back dear player</h3>
            you can login to check your board game scores and other things.
             If you don't have an account, one will be created for you.
        </span><br><br>

        <a class="social btn btn-block btn-social btn-sm btn-google" href="http://localhost/boardgametracker/public_html/login/google"><i class="fab fa-google-plus-square"></i>Sign in with Google</a>
        <a class="social btn btn-block btn-social btn-sm btn-microsoft" href="http://localhost/boardgametracker/public_html/login/microsoft"><i class="fab fa-windows"></i>Sign in with Microsoft</a>
        <a class="social btn btn-block btn-social btn-sm btn-facebook" href="http://localhost/boardgametracker/public_html/login/facebook"><i class="fab fa-facebook-square"></i>Sign in with Facebook</a>
    </div>
</template>

<script>
import apiCall from '../../services/ApiCall.js';

    export default {
        data (){
            return {

            }

        },

        props: {

         },

        methods: {

        },

        mounted () {

        }
    }

</script>
<style scoped>

</style>
