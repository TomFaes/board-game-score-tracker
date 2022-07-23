<template>
    <div class="container">
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data" v-if="group.id != undefined">

            <!-- Basic Game Details -->
            <global-layout  v-if="groupGames.data">
                <div class="form-floating  mb-3">
                    <multiselect
                        class="form-control"
                        v-model="selectedGame"
                        :multiple="false"
                        :options="groupGames.data"
                        :close-on-select="true"
                        :clear-on-select="true"
                        :disabled=disabled
                        placeholder="Add the selected game"
                        label="name"
                        track-by="name"
                        @select="setSelectedGame"
                        id="game"
                    >
                    </multiselect>
                    <label>Select game: </label>
                </div>
            </global-layout>

            <global-layout >
                <div class="form-floating  mb-3">
                    <input :disabled="disabled" type="date" class="form-control" v-model="fields.date" />
                    <label>Date: </label>
                </div>
                <div class="text-danger" v-if="errors.date">{{ errors.date[0] }}</div>
            </global-layout>
             <global-layout >
                <div class="form-floating  mb-3">
                    <input :disabled="disabled" type="time" class="form-control" v-model="fields.time_played" />
                    <label>Time played: </label>
                </div>
                <div class="text-danger" v-if="errors.date">{{ errors.time_played[0] }}</div>
            </global-layout>
            <global-layout >
                <div class="form-floating  mb-3">
                    <textarea :disabled=disabled type="text" style="height: 100px" class="form-control" v-model="fields.remarks"/>
                    <label>Remarks: </label>
                </div>
                <div class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</div>
            </global-layout>

            <!-- Expansion details -->
            <global-layout v-if="gameExpansions.expansions">
                <h3>Expansion</h3>
                <div class="form-check" v-for="(expansion) in gameExpansions.expansions" :key="expansion.id">
                    <input :disabled="disabled" type="checkbox" class="form-check-input" :id="expansion.id" :value="expansion.id" v-model="selectedExpansions" >
                    <label :for="expansion.id">{{ expansion.name }}</label>
                </div>
            </global-layout>

            <!-- Score details -->
            <global-layout >
                <h3>add players & scores</h3>
            </global-layout>

            <!-- search user list -->
            <global-layout  v-if="groupGames.data">
                <div class="form-floating  mb-3">
                    <multiselect
                        class="form-control"
                        :multiple="false"
                        :options="multigroupUsers"
                        :reset-after="true"
                        :close-on-select="false"
                        :clear-on-select="true"
                        :disabled=disabled
                        placeholder="Add the selected user"
                        label="full_name"
                        track-by="full_name"
                        @select="showUser"
                    >
                    </multiselect>
                    <label>Add the selected user: </label>
                </div>
            </global-layout>

            <div v-if="groupUsers">
                <table class="table table-borderless table-sm" v-if="groupUsers.data">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Score</th>
                            <th scope="col">Place</th>
                            <th scope="col">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(groupUser) in groupUsers.data" :key="groupUser.id">
                            <th scope="row" nowrap v-if="groupUser.score_display == true">
                                <button class="btn btn-danger"  @click="removeUser(groupUser)"  v-if="disabled == false"> <i class="fas fa-minus-circle" style="heigth:14px; width:14px"></i></button>
                                {{ groupUser.full_name }}
                            </th>
                            <td style="width: 20%" v-if="groupUser.score_display == true">
                                <input :disabled="disabled" class="form-control" type="number" name="score" v-model="groupUser['score_score']">
                            </td>
                            <td  style="width: 20%" v-if="groupUser.score_display == true">
                                <input :disabled="disabled" class="form-control" type="number" name="place" v-model="groupUser['score_place']">
                            </td>
                            <td  style="width: 40%" v-if="groupUser.score_display == true">
                                <input :disabled="disabled" class="form-control" type="text" name="remarks" v-model="groupUser['score_remarks']">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="form-check" v-if="submitOption == 'Update'">
                    <input :disabled="disabled" type="checkbox" class="form-check-input" :id="renewPlaces" :value="renewPlaces" v-model="renewPlaces" >
                    <label :for="renewPlaces">Recalculate scores</label>
                </div>
            </div>

            <global-layout center="center">
                <button class="btn btn-primary" v-if="disabled == false">Save played game</button>
            </global-layout>
        </form>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import Multiselect from '@suadelabs/vue3-multiselect';

    import Moment from 'moment';

    export default {

        components: {
            Multiselect,
            Moment,
        },

         data () {
            return {
                'selectedGame': [],
                'selectedExpansions': [],
                'gameExpansions': {},
                'disabled': false,
                'errors': [],
                'fields' : {
                    game_id: '',
                    time_played: '',
                    date: Moment().format('YYYY-MM-DD'),
                },
                'formData': new FormData(),
                'multigroupUsers': [],
                'renewPlaces': false,
            }
        },

        props: {
            'group': {},
            'playedGame': {},
            'submitOption': "",
            'groupGames' : {},
            'groupUsers': {},
            'currentPage': 0,
         },

         watch: {
             submitOption(){
                if(this.submitOption == 'View'){
                    this.disabled = true;
                    return
                }
                 this.disabled = false;
            },

             groupUsers(){
                 if(this.submitOption == "Create"){
                    this.prepareGroupUsers();
                    return;
                 }
                this.setData();
                return;
             },
         },

        methods: {
            setSelectedGame(game){
                this.gameExpansions = game.game;

                this.fields.game_id = game.game_id;
                this.selectedExpansions = [];

                //if there are less users then the max of the game, display all users
                if(this.groupUsers.data.length <= game.game.players_max){
                    for(var item in this.groupUsers.data){
                        this.groupUsers.data[item]['score_display'] = true;
                    }
                    this.multigroupUsers = [];
                }
            },

            prepareGroupUsers(){
                for(var item in this.groupUsers.data){
                    this.groupUsers.data[item]['score_played_game_id'] = false;
                    this.groupUsers.data[item]['score_display'] = false;
                    this.groupUsers.data[item]['score_score'] = 0;
                    this.groupUsers.data[item]['score_place'] = 0;
                    this.groupUsers.data[item]['score_remarks'] = "";
                }

                if(this.groupUsers.data){
                    this.multigroupUsers = JSON.parse(JSON.stringify(this.groupUsers.data));
                }
            },

            showUser(user){
                //remove the user from the groupuserlist
                var userToRemove = "";
                this.groupUsers.data.forEach(function(item, index){
                    if(user['id'] == item.id){
                        userToRemove = index;
                    }
                });
                this.groupUsers.data[userToRemove]['score_display'] = true;

                this.multigroupUsers.forEach(function(item, index){
                    if(user['id'] == item.id){
                        userToRemove = index;
                    }
                });
                this.multigroupUsers.splice(userToRemove, 1);
            },

            removeUser(user){
                var userToRemove = "";
                this.groupUsers.data.forEach(function(item, index){
                    if(user['id'] == item.id){
                        userToRemove = index;
                    }
                });
                //reset values to base
                this.groupUsers.data[userToRemove]['score_display'] = false;
                this.groupUsers.data[userToRemove]['score_score'] = 0;
                this.groupUsers.data[userToRemove]['score_place'] = 0;
                this.groupUsers.data[userToRemove]['score_remarks'] = "";

                this.multigroupUsers.push(this.groupUsers.data[userToRemove]);
            },

            setData(){
                this.fields.game_id = this.playedGame.game_id;
                this.fields.date = this.playedGame.date;
                this.fields.time_played = this.playedGame.time_played;

                this.fields.remarks = this.playedGame.remarks;
                if(this.groupUsers.data == undefined){
                    return;
                }

                //prepare extra data for group user
                this.prepareGroupUsers();

                //rewrite this part to avoid all these loops
                for(var item in this.playedGame.scores){
                   for(var item2 in this.groupUsers.data){
                       if(this.playedGame.scores[item].group_user_id != this.groupUsers.data[item2]['id']){
                           continue;
                       }

                        this.groupUsers.data[item2]['score_played_game_id'] = this.playedGame.scores[item].id;
                        this.groupUsers.data[item2]['score_display'] = true;
                        this.groupUsers.data[item2]['score_score'] = this.playedGame.scores[item].score;
                        this.groupUsers.data[item2]['score_place'] = this.playedGame.scores[item].place;
                        if(this.playedGame.scores[item].remarks != null){
                            this.groupUsers.data[item2]['score_remarks'] = this.playedGame.scores[item].remarks;
                        }
                        //adjust the multiselect drop down to have te users who haven't had a score
                        for(var item3 in this.multigroupUsers){
                            if(this.multigroupUsers[item3].id == this.groupUsers.data[item2]['id']){
                                this.multigroupUsers.splice(item3, 1);
                            }
                        }
                   }
                }
            },

            submit(){
                 if(this.submitOption == 'View'){
                     return;
                 }
                if(this.submitOption == 'Update'){
                    this.update();
                    return;
                }
                this.create();
            },

            create(){
                this.setFormData();
                this.action = 'group/' + this.group.id + '/played';

                 apiCall.postData(this.action, this.formData)
                .then(response =>{
                    var message = "You've updated the game for " + this.group.name;
                    this.$store.dispatch('getMessage', {message: message});
                    this.formData =  new FormData();
                    this.prepareGroupUsers();
                    this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: 1});
                    this.$router.push({name: "allPlayedGames", params: { id: this.group.id },});
                }).catch(error => {
                    this.errors = error;
                });
            },

            update(){
                this.setFormData();
                this.action = 'group/' + this.group.id + '/played/' + this.playedGame.id;

                apiCall.updateData(this.action, this.formData)
                .then(response =>{
                    console.log()
                    var message = "You've updated the game " + this.playedGame.game.name + " in " + this.group.name;
                    this.$store.dispatch('getMessage', {message: message})
                    this.formData =  new FormData();
                    this.$store.dispatch('getPlayedGames', {id: this.group.id, current_page: this.currentPage ?? '1'});
                    this.prepareGroupUsers();
                }).catch(error => {
                    if(error.status === 401){
                        this.$store.dispatch('getMessage', {message: error.data});
                        this.formData =  new FormData();
                    }
                    this.errors = error;
                });
            },

            setFormData(){
                this.formData.append('group_id',  this.group.id);
                if(this.fields.game_id != undefined){
                    this.formData.set('game_id', this.fields.game_id);
                }
                if(this.fields.date != undefined){
                    this.formData.append('date', this.fields.date);
                }
                if(this.fields.time_played != undefined){
                    this.formData.append('time_played', this.fields.time_played);
                }
                if(this.fields.remarks != undefined){
                    this.formData.append('remarks', this.fields.remarks);
                }
                if(this.selectedExpansions != undefined){
                    this.formData.append('expansions', this.selectedExpansions);
                }
                for(var item in this.groupUsers.data){
                    if(this.groupUsers.data[item]['score_score'] == undefined && this.groupUsers.data[item]['score_place'] == undefined){
                        continue;
                    }

                    var userId = this.groupUsers.data[item]['id'];
                    if(this.playedGame != undefined){
                        this.formData.append('players[' + userId + '][id]', this.groupUsers.data[item]['score_played_game_id']);
                    }
                    this.formData.append('players[' + userId + '][group_user_id]', userId);
                    this.formData.append('players[' + userId + '][score]', this.groupUsers.data[item]['score_score']);

                    //make option so places will be recalculated
                    if(this.renewPlaces === true){
                        this.formData.append('players[' + userId + '][place]', 0);
                    }else{
                        if(this.groupUsers.data[item]['score_place'] == undefined || this.groupUsers.data[item]['score_place'] == ""){
                            this.groupUsers.data[item]['score_place'] = 0;
                        }
                        this.formData.append('players[' + userId + '][place]', this.groupUsers.data[item]['score_place']);
                    }

                    if(this.groupUsers.data[item]['score_remarks'] != undefined){
                        this.formData.append('players[' + userId + '][remarks]', this.groupUsers.data[item]['score_remarks']);
                    }else{
                        this.formData.append('players[' + userId + '][remarks]', "");
                    }
                }
            },
        },

        mounted(){
            if(this.submitOption == 'View'){
                this.disabled = true;
            }

            if(this.playedGame != undefined){
                this.selectedGame = this.playedGame.game;
                this.gameExpansions = this.playedGame.game;
                //set selected expansions
                this.selectedExpansions = [];

                this.playedGame.played_expansions.map((expansions) => {
                    this.selectedExpansions.push(expansions.id);
                });

                if(this.groupUsers != undefined){
                    this.setData();
                }
                return;
            }
        },
    }

</script>


<style scoped>

</style>





