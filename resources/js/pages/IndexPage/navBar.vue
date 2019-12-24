<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background-color:lightblue">

            <a class="navbar-brand js-scroll-trigger" href="home"><b>Logo</b></a>

            <div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">        <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarResponsive" style="width: 100%">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="profile"><i class="fa fa-user fa-2x"></i></a>
                    </li>
                    <li class="nav-item" v-if="user.id > 0">
                          <a class="nav-link js-scroll-trigger" href="logout">Logout</a>
                    </li>
                    <li class="nav-item" v-else>
                          <a class="nav-link js-scroll-trigger" href="login">Login</a>
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
                'user': {},
            }
        },

        methods: {
            logout(){
                console.log("logout");
                this.$router.push('logout');
            },

            getUser(){
                apiCall.getData('profile')
                .then(response =>{
                    this.user = response;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },
        },

        mounted () {
            this.getUser();
        }
    }
</script>
