<template>
    <div>
        <span>
            <h3>Welcome back dear player</h3>
            you can login to check your board game scores and other things.
             If you don't have an account, one will be created for you.
        </span><br><br>
        <div class="center">
            <a class="social btn btn-block btn-social btn-sm btn-google me-2" :href="baseURL+'login/google'"><i class="fab fa-google-plus-square"></i>Sign in with Google</a>
            <a class="social btn btn-block btn-social btn-sm btn-microsoft me-2" :href="baseURL+'login/microsoft'"><i class="fab fa-windows"></i>Sign in with Microsoft</a>
            <a class="social btn btn-block btn-social btn-sm btn-facebook me-2" :href="baseURL+'login/facebook'"><i class="fab fa-facebook-square"></i>Sign in with Facebook</a>
        </div>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                "baseURL": '',
            }
        },

        props: {

         },

        methods: {

        },

        mounted () {
            this.baseURL = process.env.MIX_APP_URL;
        }
    }

</script>
<style scoped>

</style>
