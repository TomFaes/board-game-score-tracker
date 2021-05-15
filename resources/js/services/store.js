import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

var localPath = "";
if(process.env.NODE_ENV == 'development'){
    localPath= "/boardgametracker/public_html"
}

export default new Vuex.Store({

  state: {
    LoggedInUser: "",
    userGroups: {},
    selectedGroup: {},
    playedGames: {},
    firstLoad: 0,

    authenticated: false,
    role: '',
    errorMessage: '',
  },


  mutations: {
    setAuthentication(state, status){
      state.authenticated = status;
    },

    setLoggedInUser(state, user){
      state.LoggedInUser = user;
    },

    setRole(state, role){
      state.role = role;
    },

    setErrorMessage(state, message){
      state.errorMessage = message;
    },

    setUserGroups(state, userGroups){
      state.userGroups = userGroups;
    },

    setSelectedGroup(state, selectedGroup){
      state.selectedGroup = selectedGroup;
    },

    setPlayedGames(state, playedGames){
      state.playedGames = playedGames;
    },

    setFirstLoad(state){
      state.firstLoad = 1;
    },

  },

  actions: {
    setFirstLoadToOne({commit}){
      commit('setFirstLoad');
    },

    getUserGroups({commit}) {
      // fetch the groups of a user
      axios.get( localPath +  '/api/user-group')
          .then((response) => {
              commit('setUserGroups', response.data);
          })
          .catch((error) => console.error(error));
    },

    getSelectedGroup({commit}, {id}) {
      // fetch the selected group
      axios.get( localPath +  '/api/group/' + id)
          .then((response) => {
              commit('setSelectedGroup', response.data);
          })
          .catch((error) => console.error(error));
    },

    getPlayedGames({commit}, {id, current_page}) {
      axios.get( localPath +  '/api/group/' + id + '/played?page=' + current_page)
          .then((response) => {
              commit('setPlayedGames', response.data);
          })
          .catch((error) => console.error(error));
    },



    getLoggedInUser({commit}){
       // fetch the selected group
       axios.get( localPath +  '/api/profile')
       .then((response) => {
           commit('setLoggedInUser', response.data);
       })
       .catch((error) => console.error(error));
    },

    resetToDefault({commit}){
      commit('setSelectedGroup', {});
      commit('setPlayedGames', {});
    },

  },

  getters: {

  }
})
