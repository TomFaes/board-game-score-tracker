<template>
    <div class="container">
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data" v-if="group.id != undefined">

            <!-- Basic Game Details -->
            <div class="row" v-if="groupGames.data">
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <label>Select game: </label>
                        <multiselect
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
                    </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div>

            <date-input :disabled="disabled" inputName="date" inputId="date" tekstLabel="Date: " v-model="fields.date" :errors="errors.date" :value='fields.date'></date-input>
            <time-input :disabled="disabled" inputName="time_played" inputId="time_played" tekstLabel="Time played: " v-model="fields.time_played" :errors="errors.time_played" :value='fields.time_played'></time-input>
            <text-area :disabled="disabled" inputName="remarks" inputId="remarks" tekstLabel="Remarks: " v-model="fields.remarks" :errors="errors.remarks" :value='fields.remarks'></text-area>

            <!-- Expansion details -->
            <div v-if="gameExpansions.expansions" class="form-group">
                <div class="row" v-if="gameExpansions.expansions.length > 0">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8">
                        <h3>Expansion</h3>
                        <div v-for="(expansion) in gameExpansions.expansions" :key="expansion.id">
                            <input :disabled="disabled" type="checkbox"  :id="expansion.id" :value="expansion.id" v-model="seletectedExpansions" >
                            <label :for="expansion.id">{{ expansion.name }}</label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2"></div>
                </div>
            </div>

            <!-- Score details -->
            <div class="row">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <h3>add players & scores</h3>
                </div>
                <div class="col-lg-2 col-md-2"></div>
            </div>

            <!-- search user list -->
            <div class="row" v-if="multigroupUsers">
                <div class="col-2"></div>
                    <div class="col-8">
                        <multiselect
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
                    </div>
                <div class="col-2"></div>
            </div>

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
                        <tr v-for="(groupUser) in groupUsers.data" :key="groupUser.id" v-if="groupUser.score_display == true">
                            <th scope="row" nowrap>
                                <button class="btn btn-danger"  @click="removeUser(groupUser)"  v-if="disabled == false"> <i class="fas fa-minus-circle" style="heigth:14px; width:14px"></i></button>
                                {{ groupUser.full_name }}
                            </th>
                            <td style="width: 20%">
                                <input :disabled="disabled" class="form-control" type="number" name="score" value="groupUser['score_score']" v-model="groupUser['score_score']">
                            </td>
                            <td  style="width: 20%">
                                <input :disabled="disabled" class="form-control" type="number" name="place" value="groupUser['score_place']" v-model="groupUser['score_place']">
                            </td>
                            <td  style="width: 40%">
                                <input :disabled="disabled" class="form-control" type="text" name="remarks" value="groupUser['score_remarks']" v-model="groupUser['score_remarks']">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <span v-if="submitOption == 'Update'">
                    <input :disabled="disabled" type="checkbox"  :id="renewPlaces" :value="renewPlaces" v-model="renewPlaces" >
                <label :for="renewPlaces">Recalculate scores</label>
                </span>
            </div>

            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <center>
                        <button class="btn btn-primary" v-if="disabled == false">Add played game</button>
                    </center>
                    <br>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
            </div>
        </form>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';

    import TextArea from '../../components/ui/form/TextAreaInput.vue';
    import DateInput from '../../components/ui/form/DateInput.vue';
    import TimeInput from '../../components/ui/form/TimeInput.vue';
    import Multiselect from 'vue-multiselect';

    import Moment from 'moment';

    export default {

        components: {
            TextArea,
            DateInput,
            TimeInput,
            Multiselect,
            Moment,
        },

         data () {
            return {
                'selectedGame': [],
                'seletectedExpansions': [],
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
                this.seletectedExpansions = [];

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
                //this.multigroupUsers.forEach(function(item, index){
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
                       if(this.playedGame.scores[item].group_user_id == this.groupUsers.data[item2]['id']){
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
                    this.$bus.$emit('reloadPlayedGameList');
                    this.message = "You've updated the game for " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
                    this.prepareGroupUsers();
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
                    this.$bus.$emit('reloadPlayedGameList');
                    this.message = "You've updated the game for " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
                    this.prepareGroupUsers();
                }).catch(error => {
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

                if(this.seletectedExpansions != undefined){
                    this.formData.append('expansions', this.seletectedExpansions);
                }

                for(var item in this.groupUsers.data){
                    if(this.groupUsers.data[item]['score_score'] == undefined && this.groupUsers.data[item]['score_place'] == undefined){
                        continue;
                    }

                    var userId = this.groupUsers.data[item]['id'];
                    if(this.playedGame != undefined){
                        this.formData.append('player[' + userId + '][id]', this.groupUsers.data[item]['score_played_game_id']);
                    }
                    this.formData.append('player[' + userId + '][group_user_id]', userId);
                    this.formData.append('player[' + userId + '][score]', this.groupUsers.data[item]['score_score']);

                    //make option so places will be recalculated
                    if(this.renewPlaces === true){
                        //this.formData.append('player[' + userId + '][place]', 0);
                        this.formData.append('player[' + userId + '][place]', 0);
                    }else{
                        if(this.groupUsers.data[item]['score_place'] == undefined || this.groupUsers.data[item]['score_place'] == ""){
                            this.groupUsers.data[item]['score_place'] = 0;
                        }
                        this.formData.append('player[' + userId + '][place]', this.groupUsers.data[item]['score_place']);
                    }

                    if(this.groupUsers.data[item]['score_remarks'] != undefined){
                        this.formData.append('player[' + userId + '][remarks]', this.groupUsers.data[item]['score_remarks']);
                    }else{
                        this.formData.append('player[' + userId + '][remarks]', "");
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
                this.seletectedExpansions = [];

                this.playedGame.played_expansions.map((expansions) => {
                    this.seletectedExpansions.push(expansions.id);
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





