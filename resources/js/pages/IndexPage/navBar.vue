<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background-color:lightblue">
            <!--
            <span class="navbar-brand js-scroll-trigger item-link"  @click="displayIndex">Beta Version</span>
            -->
            <router-link :to="{ name: 'home' }">Beta Version</router-link>

            <div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            </div>

            <div class="collapse navbar-collapse" id="navbarResponsive" style="width: 100%">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item" data-toggle="collapse" data-target=".navbar-collapse.show" v-if="user.role == 'Admin'" >
                         <span class="nav-link js-scroll-trigger item-link">
                            <router-link :to="{ name: 'addGame' }">Add game</router-link>
                        </span>
                    </li>
                    <li class="nav-item" data-toggle="collapse" data-target=".navbar-collapse.show">
                        <span class="nav-link js-scroll-trigger item-link">
                            <router-link :to="{ name: 'roadmap' }"><i class="fa fa-road fa-2x"></i></router-link>
                        </span>
                    </li>

                    <li class="nav-item"  data-toggle="collapse" data-target=".navbar-collapse.show" v-if="user.id > 0">
                         <span class="nav-link js-scroll-trigger item-link">
                            <router-link :to="{ name: 'profile' }"><i class="fa fa-user fa-2x"></i></router-link>
                         </span>
                    </li>

                    <li class="nav-item"  data-toggle="collapse" data-target=".navbar-collapse.show" v-if="user.id > 0">
                          <a class="nav-link js-scroll-trigger nav-link js-scroll-trigger item-link" @click.prevent="logout">Logout</a>
                    </li>
                    <li class="nav-item" data-toggle="collapse" data-target=".navbar-collapse.show" v-else>
                        <span class="nav-link js-scroll-trigger item-link">
                            <router-link :to="{ name: 'login' }">Login</router-link>
                         </span>
                         <!--
                          <span class="nav-link js-scroll-trigger item-link"  @click="logout">Login</span>
                          -->
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
               console.log("envirement: " + this.envirement);
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
