import { createWebHistory, createRouter } from "vue-router";
import store from '../services/store';
import axios from 'axios';

//All components that will be used in the router
import Home from '../pages/IndexPage/listOfUserGroups.vue';
import Guest from '../pages/IndexPage/guest.vue';
import Login from '../pages/IndexPage/login.vue';
import Profile from '../pages/ProfilePage/index.vue';

import GameList from '../pages/GamePage/index.vue';

import GroupDetail from '../pages/GroupPage/details.vue';
import EditGroup from '../pages/GroupPage/input.vue';
import NewGroup from '../pages/GroupPage/input.vue';

import GroupUsers from '../pages/GroupUserPage/list.vue';
import JoinGroup from '../pages/GroupUserPage/joinGroup.vue';
import AddPlayedGame from '../pages/PlayedGamePage/create.vue';
import AllPlayedGames from '../pages/PlayedGamePage/list.vue';
import GroupStats from '../pages/StatisticPage/index.vue';
import GroupGames from '../pages/GroupGamePage/list.vue';


//create a variable local path, in production there will be antohter path
var localPath = "";
if(process.env.NODE_ENV == 'development'){
    localPath= "/boardgametracker/public_html"
}

 const router = createRouter({
        mode: 'history',
        base: process.env.BASE_URL,
        linkActiveClass: 'active',
        transitionOnLoad: true,
        history: createWebHistory(),
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
            path: localPath + '/game_list',
            name: 'gameList',
            component: GameList,
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
            path: localPath + '/join_group',
            name: 'joinGroup',
            component: JoinGroup,
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
                        groupDetails: GroupStats
                    }
                },

                {
                    name: 'addPlayedGame',
                    path: 'add_played_game',
                    props: true,
                    components: {
                        groupDetails: AddPlayedGame
                    }
                },

                {
                    name: 'allPlayedGames',
                    path: 'all_played_games',
                    props: true,
                    components: {
                        groupDetails: AllPlayedGames
                    }
                },

                {
                    name: 'editGroup',
                    path: 'edit',
                    props: true,
                    components: {
                        groupDetails: EditGroup
                    }
                },

                {
                    name: 'groupUsers',
                    path: 'group_users',
                    props: true,
                    components: {
                        groupDetails: GroupUsers
                    }
                },

                {
                    name: 'groupGames',
                    path: 'group_games',
                    props: true,
                    components: {
                        groupDetails: GroupGames
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
            if(store.state.loggedInUser == ""){
                var user = axios({
                    method: 'get',
                    url : localPath + '/api/profile'
                })
                .then(function (response) {
                    store.commit("setAuthentication", true);
                    store.commit("setRole", response.data.role);
                    store.commit("setLoggedInUser", response.data);
                    return response.data;
                })
                .catch(function (error) {
                    console.log("Router error: " + error);
                });
            }else{
               var user = store.state.loggedInUser;
            }
            resolve(user)
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
