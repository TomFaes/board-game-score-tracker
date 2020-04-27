<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background-color:lightblue">
            <span class="navbar-brand js-scroll-trigger item-link"  @click="displayIndex">Beta Version</span>

            <div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            </div>

            <div class="collapse navbar-collapse" id="navbarResponsive" style="width: 100%">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item" data-toggle="collapse" data-target=".navbar-collapse.show" v-if="user.role == 'Admin'" >
                        <span class="nav-link js-scroll-trigger item-link"  @click="displayGameIndex">Add game</span>
                    </li>
                    <li class="nav-item"  data-toggle="collapse" data-target=".navbar-collapse.show" v-if="user.id > 0">
                         <span class="nav-link js-scroll-trigger item-link"  @click="displayProfile"><i class="fa fa-user fa-2x"></i></span>
                    </li>
                    <li class="nav-item"  data-toggle="collapse" data-target=".navbar-collapse.show" v-if="user.id > 0">
                          <a class="nav-link js-scroll-trigger" href="logout">Logout</a>
                    </li>
                    <li class="nav-item" data-toggle="collapse" data-target=".navbar-collapse.show" v-else>
                          <span class="nav-link js-scroll-trigger item-link"  @click="displayLogin">Login</span>
                    </li>
                </ul>
            </div>
        </nav>
        <br>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    export default {
        data (){
            return {
                'userOld': {},
            }
        },

        props: {
            'user': {},
         },

        methods: {
            logout(){
                console.log("logout");
                this.$router.push('logout');
            },

            displayGameIndex(){
                this.$bus.$emit('displayNav', 'adminGame');
            },

            displayProfile(){
                 this.$bus.$emit('displayNav', 'profile');
            },

            displayIndex(){
                this.$bus.$emit('displayNav', '');
            },

            displayLogin(){
                 this.$bus.$emit('displayNav', 'login');
            }


        },

        mounted () {

        }
    }

</script>
<style scoped>


</style>
