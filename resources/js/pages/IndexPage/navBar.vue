<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color:lightblue">
            <div class="container-fluid">

                <router-link :to="{ name: 'home' }" class="navbar-brand">Beta Version</router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item" data-target=".navbar-collapse.show" v-if="user.role == 'Admin'" >
                             <router-link :to="{ name: 'gameList' }"  class="nav-link js-scroll-trigger item-link">Game list</router-link>
                        </li>
                        <li class="nav-item" v-if="user.id  > 0">
                            <router-link :to="{ name: 'profile' }"><i class="fa fa-user fa-2x"></i></router-link>
                        </li>
                        <li class="nav-item"  v-if="user.id  > 0">
                            <a class="nav-link item-link" @click.prevent="logout">Logout</a>
                        </li>
                        <li class="nav-item" v-else>
                            <router-link :to="{ name: 'login' }">Login</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <br><br> <br>


    </div>
</template>

<script>

    export default {
        data (){
            return {
                'envirement': "",
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

           logout(){
                if(this.envirement == "development"){
                    return window.location.href = "http://localhost/boardgametracker/public_html/logout";
                }
                return window.location.href = "https://boardgametracker.000webhostapp.com/logout";
            }
        },

        mounted () {
            this.envirement = process.env.NODE_ENV;
        }
    }

</script>
<style scoped>
    a {
        color: rgba(255, 255, 255, 0.5);
        padding-right: 0,5rem;
        padding-left: 0,5rem;
    }
    a :hover{
        filter: brightness(50%);
    }
</style>
