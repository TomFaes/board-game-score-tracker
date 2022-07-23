import { createStore } from 'vuex';

var localPath = "";
if(process.env.NODE_ENV == 'development'){
    localPath= "/boardgametracker/public_html"
}

export default new createStore({
  state: {
    loggedInUser: "",
    games: {},
    allGames: {},
    baseGames: {},
    unapprovedGames: {},
    groupsOfUser: {},
    selectedGroup: {},
    gamesNotInGroup: {},
    selectedGroupUsers: {},
    selectedGroupGames: {},
    selectedGroupGameLinks: {},
    selectedPlayedGame: {},
    playedGames: {},
    firstLoad: 0,

    authenticated: false,
    role: '',
    errorMessage: '',
    message: '',
  },


  mutations: {
    setAuthentication(state, status){
      state.authenticated = status;
    },

    setGames(state, games){
        state.games = games;
    },

    setAllGames(state, games){
        state.allGames = games;
    },

    setBaseGames(state, games){
        state.baseGames = games;
    },

    setUnapprovedGames(state, games){
        state.unapprovedGames = games;
    },

    setLoggedInUser(state, user){
      state.loggedInUser = user;
    },

    setRole(state, role){
      state.role = role;
    },

    setErrorMessage(state, message){
      state.errorMessage = message;
    },

    setGroupsOfUser(state, groupsOfUser){
      state.groupsOfUser = groupsOfUser['data'];
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

    setSelectedGroupGameLinks(state, selectedGroupGameLinks){
        state.selectedGroupGameLinks = selectedGroupGameLinks;
    },

    setGamesNotInGroup(state, gamesNotInGroup){
        state.gamesNotInGroup = gamesNotInGroup;
    },

    setPlayedGames(state, playedGames){
      state.playedGames = playedGames;
    },

    setSelectedPlayedGame(state, selectedPlayedGame){
        state.selectedPlayedGame = selectedPlayedGame;
    },

    setMessage(state, message){
        state.message = message;
    },

    setFirstLoad(state){
      state.firstLoad = 1;
    },
  },

  actions: {
    setFirstLoadToOne({commit}){
      commit('setFirstLoad');
    },

    getGames({commit, dispatch}, {currentPage, itemsPerPage = 0} ){
        var link = '/api/game';

        if(currentPage > 0 && itemsPerPage > 0){
            link = '/api/game?page=' + currentPage + '&page_items=' + itemsPerPage;
        }

        axios.get( localPath +  link)
        .then((response) => {
          commit('setGames', response.data);
        })
        .catch(error => {
            if(error.response.status == 401){
                dispatch('getMessage', {message: error.response.data, color: 'red'});
            }
        });
    },

    getAllGames({commit}){
        var link = '/api/game';

        axios.get( localPath +  link)
        .then((response) => {
          commit('setAllGames', response.data);
        })
        .catch(error => {
            if(error.response.status == 401){
                dispatch('getMessage', {message: error.response.data, color: 'red'});
            }
        });
    },

    getBaseGames({commit}) {
        axios.get( localPath +  '/api/basegame')
        .then((response) => {
            commit('setBaseGames', response.data);
        })
        .catch((error) => console.error(error));
    },

    getUnapprovedGames({commit}){
        axios.get( localPath +  '/api/unapprovedgames')
        .then((response) => {
            commit('setUnapprovedGames', response.data);
        })
        .catch((error) => console.error(error));
    },

    getGroupsOfUser({commit}) {
      axios.get( localPath +  '/api/user-group')
          .then((response) => {
              commit('setGroupsOfUser', response.data);
          })
          .catch((error) => console.error(error));
    },

    getSelectedGroup({commit, dispatch}, {id}) {
        axios.get( localPath +  '/api/group/' + id)
          .then((response) => {
              commit('setSelectedGroup', response.data);
          })
          .catch(error => {
            if(error.response.status == 401){
                dispatch('getMessage', {message: error.response.data, color: 'red'});
            }
          });
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

    getSelectedGroupGameLinks({commit}, {groupId, gameId}){
        axios({
            method: 'get',
            url : localPath +  '/api/group/' + groupId + '/game/' + gameId + '/link',
        })
        .then((response) => {
            commit('setSelectedGroupGameLinks', response.data);
        })
        .catch((error) => console.error(error));
    },

    getGamesNotInGroup({commit}, {groupId}){
        axios.get( localPath +  '/api/group/' + groupId + '/search-non-group-games')
          .then((response) => {
              commit('setGamesNotInGroup', response.data);
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

    getMessage({commit}, {message, color = 'green', time = 5000}){
        var response = {};
        response['message'] = message;
        response['color'] = color;
        response['time'] = time;
        commit('setMessage', response);
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
