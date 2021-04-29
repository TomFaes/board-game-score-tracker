import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../services/store';
import axios from 'axios';

Vue.use(VueRouter);

//All components that will be used in the router
import Home from '../pages/GroupPage/listOfUserGroups.vue';
import Guest from '../pages/IndexPage/guest.vue';
import Login from '../pages/IndexPage/login.vue';
import Profile from '../pages/ProfilePage/index.vue';
import Roadmap from '../pages/RoadmapPage/index.vue';
import AddGame from '../pages/GamePage/index.vue';
import NewGroup from '../pages/GroupPage/input.vue';

import GroupDetail from '../pages/GroupPage/details.vue';
import groupStats from '../pages/StatisticPage/index.vue';
import addPlayedGame from '../pages/PlayedGamePage/input.vue';
import allPlayedGames from '../pages/PlayedGamePage/list.vue';
import editGroup from '../pages/GroupPage/input.vue';
import groupUsers from '../pages/GroupUserPage/list.vue';
import groupGames from '../pages/GroupGamePage/list.vue';


//create a variable local path, in production there will be antohter path
var localPath = "";
if(process.env.NODE_ENV == 'development'){
    localPath= "/boardgametracker/public_html"
}

const router = new VueRouter({
//export default new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    linkActiveClass: 'active',
    transitionOnLoad: true,
    history: true,
    routes:[
        {
            path: localPath + '/',
            name: 'home',
            component: Home,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: localPath + '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresAuth: false,
            },
        },

        {
            path: localPath + '/profile',
            name: 'profile',
            component: Profile,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: localPath + '/roadmap',
            name: 'roadmap',
            component: Roadmap,
            meta: {
                requiresAuth: false,
            },
        },
        
        {
            path: localPath + '/add_game',
            name: 'addGame',
            component: AddGame,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: localPath + '/new_group',
            name: 'newGroup',
            component: NewGroup,
            meta: {
                requiresAuth: true,
            },
        },

        {
            path: localPath + '/group/:id',
            name: 'groupDetail',
            component: GroupDetail,
            props: true,
            meta: {
                requiresAuth: true
            },
            children: [
                {
                    name: 'groupStats',
                    path: 'stats',
                    props: true,
                    components: {
                        //default: GroupDetail,
                        groupDetails: groupStats
                    }
                },
                
                {
                    name: 'addPlayedGame',
                    path: 'add_played_game',
                    props: true,
                    components: {
                        //default: addPlayedGame,
                        groupDetails: addPlayedGame
                    }
                },

                {
                    name: 'allPlayedGames',
                    path: 'all_played_games',
                    props: true,
                    components: {
                        groupDetails: allPlayedGames
                    }
                },

                {
                    name: 'editGroup',
                    path: 'edit',
                    props: true,
                    components: {
                        groupDetails: editGroup
                    }
                },

                {
                    name: 'groupUsers',
                    path: 'group_users',
                    props: true,
                    components: {
                        groupDetails: groupUsers
                    }
                },

                {
                    name: 'groupGames',
                    path: 'group_games',
                    props: true,
                    components: {
                        groupDetails: groupGames
                    }
                },
            ]
        },

        {
            path: localPath + '/guest',
            name: 'guest',
            component: Guest, 
        },
    ]
});

router.beforeEach((to, from, next) => {
    //check if authentication is needen for the route
    if(to.meta.requiresAuth == true){
        //get the user: from the database or from the store
        let getUser = new Promise((resolve, reject) => {
            if(store.state.LoggedInUser == ""){
                var user = axios({
                    method: 'get',
                    url : localPath + '/api/profile'
                })
                .then(function (response) {
                    //console.log(response);
                    store.commit("setAuthentication", true);
                    store.commit("setRole", response.data.role);
                    store.commit("setLoggedInUser", response.data);
                    return response.data;
                })
                .catch(function (error) {
                    console.log("Router error: " + error);
                });
            }else{
               var user = store.state.LoggedInUser;
            }
            resolve(user)  // Yay! Everything went well!
        }, 250) ;

        getUser.then((user) =>{
            if(user == undefined){
                next({ name: 'guest'});
            }
            if(to.meta.requiresRole != undefined && to.meta.requiresRole != ""){
                if(to.meta.requiresRole == "Admin" && to.meta.requiresRole  == user.role){
                    next();
                }else if(to.meta.requiresRole == "User" && (user.role == 'User' || user.role == "Admin" ) ){
                    next();
                }else{
                    store.commit("setErrorMessage", "Je hebt geen toegang tot deze pagina");
                    next({ name: 'guest'});
                }
            } else {
                next();
            }
        });
    }else{
         next();
    }

});
export default router;
