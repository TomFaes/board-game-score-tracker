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
    selectedGroupUsers: {},
    selectedGroupGames: {},
    selectedPlayedGame: {},
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
      state.userGroups = userGroups['data'];
    },

    setSelectedGroup(state, selectedGroup){
      state.selectedGroup = selectedGroup;
    },

    setSelectedGroupUsers(state, selectedGroupUsers){
        state.selectedGroupUsers = selectedGroupUsers;
    },

    setSelectedGroupGames(state, selectedGroupGames){
        state.selectedGroupGames = selectedGroupGames;
    },

    setPlayedGames(state, playedGames){
      state.playedGames = playedGames;
    },

    setSelectedPlayedGame(state, selectedPlayedGame){
        state.selectedPlayedGame = selectedPlayedGame;
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
      axios.get( localPath +  '/api/user-group')
          .then((response) => {
              commit('setUserGroups', response.data);
          })
          .catch((error) => console.error(error));
    },

    getSelectedGroup({commit}, {id}) {
        axios.get( localPath +  '/api/group/' + id)
          .then((response) => {
              commit('setSelectedGroup', response.data);
          })
          .catch((error) => console.error(error));
    },

    getSelectedGroupUsers({commit}, {groupId}){
        axios.get( localPath +  '/api/group/' + groupId + '/users')
          .then((response) => {
              commit('setSelectedGroupUsers', response.data);
          })
          .catch((error) => console.error(error));
    },

    getSelectedGroupGames({commit}, {groupId, pageItems = 0, currentPage = 0}){
        axios({
            method: 'get',
            params: { page_items: pageItems },
            url : localPath +  '/api/group/' + groupId + '/group-game?page=' + currentPage,
        })
        .then((response) => {
            commit('setSelectedGroupGames', response.data);
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

    getSelectedPlayedGame({commit}, {groupId, id}){
        //setSelectedPlayedGame
        axios.get( localPath +  '/api/group/' + groupId + '/played/' + id)
          .then((response) => {
              commit('setSelectedPlayedGame', response.data);
          })
          .catch((error) => console.error(error));
    },


    getLoggedInUser({commit}){
       axios.get( localPath +  '/api/profile')
       .then((response) => {
           commit('setLoggedInUser', response.data);
       })
       .catch((error) => console.error(error));
    },

    resetToDefault({commit}){
      commit('setSelectedGroup', {});
      commit('setPlayedGames', {});
      commit('setSelectedGroupUsers', {});
      commit('setSelectedGroupGames', {});
    },

    resetPlayedGame({commit}){
        commit('setSelectedPlayedGame', {});
    },

  },

  getters: {

  }
})
