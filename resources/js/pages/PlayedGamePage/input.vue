<template>
    <div class="container">
        <form @submit.prevent="submit" method="POST" enctype="multipart/form-data" v-if="group.id != undefined">
            <!-- Basic Game Details -->
            
            <div class="row" >
                <div class="col-lg-2 col-md-2 col-sm-0"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <label>Select game: </label>
                        <multiselect
                            v-model="selectedGame"
                            :multiple="false"
                            :options="group.base_group_games"
                            :close-on-select="true"
                            :clear-on-select="true"
                            :disabled=disabled
                            placeholder="Add the selected game"
                            label="name"
                            track-by="name"
                            @select="getExpansions"
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
            <div v-if="gameExpansions.length > 0" class="form-group">
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8">
                        <h3>Expansion</h3>
                        <div v-for="(expansion) in gameExpansions" :key="expansion.id">
                            <input :disabled="disabled" type="checkbox"  :id="expansion.id" :value="expansion.id" v-model="seletectedExpansions" >
                            <label :for="expansion.id">{{ expansion.name }}</label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2"></div>
                </div>
            </div>

            <!-- Score details -->
            <div v-if="fields.game_id > 0" class="form-group">
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8">
                        <h3>add players & scores</h3>
                    </div>
                    <div class="col-lg-2 col-md-2"></div>
                </div>

                <!-- search user list -->
                <div class="row" >
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
                                label="fullName"
                                track-by="fullName"
                                @select="showUser"
                        >
                        </multiselect>
                        </div>
                    <div class="col-2"></div>
                </div>

                <div>
                    <table class="table table-borderless table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Score</th>
                                <th scope="col">Place</th>
                                <th scope="col">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(group_user) in groupUsers" :key="group_user.id" v-if="group_user['ScoreDisplay'] == true">
                                <th scope="row" nowrap>
                                     <button class="btn btn-danger"  @click="removeUser(group_user)"  v-if="disabled == false"> <i class="fas fa-minus-circle" style="heigth:14px; width:14px"></i></button>
                                    <span v-if="group_user.user_id > 0">
                                        {{ group_user.user.firstname }} {{ group_user.user.name }}
                                    </span>
                                    <span v-else>
                                        {{group_user.firstname}} {{group_user.name}}
                                    </span>
                                </th>
                                <td style="width: 20%">
                                    <input :disabled="disabled" class="form-control" type="number" name="score" value="group_user['ScoreScore']" v-model="group_user['ScoreScore']">
                                </td>
                                <td  style="width: 20%">
                                    <input :disabled="disabled" class="form-control" type="number" name="place" value="group_user['ScorePlace']" v-model="group_user['ScorePlace']">
                                </td>
                                <td  style="width: 40%">
                                    <input :disabled="disabled" class="form-control" type="text" name="remarks" value="group_user['ScoreRemarks']" v-model="group_user['ScoreRemarks']">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span v-if="submitOption == 'Update'">
                        <input :disabled="disabled" type="checkbox"  :id="renewPlaces" :value="renewPlaces" v-model="renewPlaces" >
                    <label :for="renewPlaces">Recalculate scores</label>
                    </span>
                </div>
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

    import TextInput from '../../components/ui/form/TextInput.vue';
    import TextArea from '../../components/ui/form/TextAreaInput.vue';
    import NumberInput from '../../components/ui/form/TextInput.vue';
    import DateInput from '../../components/ui/form/DateInput.vue';
    import TimeInput from '../../components/ui/form/TimeInput.vue';
    import ButtonInput from '../../components/ui/form/ButtonInput.vue';
    import Multiselect from 'vue-multiselect';

    import Moment from 'moment';

    export default {
        components: {
            TextInput,
            TextArea,
            NumberInput,
            ButtonInput,
            DateInput,
            TimeInput,
            Multiselect,
            Moment,
        },

         data () {
            return {
                'gameExpansions': {},
                'seletectedExpansions': [],
                'selectedGame': [],

                'renewPlaces': false,
                'fields' : {
                    game_id: '',
                    group_id: '',
                    time_played: '',
                    date: Moment().format('YYYY-MM-DD'),
                },

                'errors' : [],
                'action': '',
                'formData': new FormData(),
                'disabled':false,

                 'multigroupUsers': [],
                 'groupUsers': [],
                 'test': '',

                 
            }
        },

        watch: {
            submitOption(){
                if(this.submitOption == 'View'){
                    this.disabled = true;
                }else if(this.submitOption == 'Update'){
                    this.disabled = false;
                }
            },

            group(){
                //set score properties
                if(this.group != undefined){
                    
                    this.groupUsers = JSON.parse(JSON.stringify(this.group.group_users));
                    for(var item in this.group_users){
                        this.groupUsers[item]['ScorePlayedGameId'] = false;
                        this.groupUsers[item]['ScoreDisplay'] = false;
                        this.groupUsers[item]['ScoreScore'] = 0;
                        this.groupUsers[item]['ScorePlace'] = 0;
                        this.groupUsers[item]['ScoreRemarks'] = "";
                    }
                }
            }
        },

        props: {
            'group': {},
            'playedGame': {},
            'submitOption': ""
         },

        methods: {
            showUser(user){
                //remove the user from the groupuserlist
                var userToRemove = "";
                //this.multigroupUsers.forEach(function(item, index){
                this.groupUsers.forEach(function(item, index){
                    if(user['id'] == item.id){
                        userToRemove = index;
                    }
                });
                this.groupUsers[userToRemove]['ScoreDisplay'] = true;

                this.multigroupUsers.forEach(function(item, index){
                    if(user['id'] == item.id){
                        userToRemove = index;
                    }
                });
                this.multigroupUsers.splice(userToRemove, 1);
            },

            removeUser(user){
                var userToRemove = "";
                this.groupUsers.forEach(function(item, index){
                    if(user['id'] == item.id){
                        userToRemove = index;
                    }
                });
                //reset values to base
                this.groupUsers[userToRemove]['ScoreDisplay'] = false;
                this.groupUsers[userToRemove]['ScoreScore'] = 0;
                this.groupUsers[userToRemove]['ScorePlace'] = 0;
                this.groupUsers[userToRemove]['ScoreRemarks'] = "";

                this.multigroupUsers.push(this.groupUsers[userToRemove]);
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

                for(var item in this.groupUsers){
                    var userId = this.groupUsers[item]['id'];
                    if(this.playedGame != undefined){
                        this.formData.append('player[' + userId + '][id]', this.groupUsers[item]['ScorePlayedGameId']);
                    }
                    this.formData.append('player[' + userId + '][group_user_id]', userId);
                    this.formData.append('player[' + userId + '][score]', this.groupUsers[item]['ScoreScore']);

                    //make option so places will be recalculated
                    if(this.renewPlaces === true){
                        this.formData.append('player[' + userId + '][place]', 0);
                        this.formData.append('player[' + userId + '][place]', 0);
                    }else{
                        this.formData.append('player[' + userId + '][place]', this.groupUsers[item]['ScorePlace']);
                    }
                    if(this.groupUsers[item]['ScoreRemarks'] != undefined){
                        this.formData.append('player[' + userId + '][remarks]', this.groupUsers[item]['ScoreRemarks']);
                    }else{
                        this.formData.append('player[' + userId + '][remarks]', "");
                    }
                    
                }
            },

            submit(){
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
                    this.$bus.$emit('display', 'playedGames');
                    this.message = "You've added a game for " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
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
                    this.$bus.$emit('display', 'playedGames');
                    this.message = "You've updated the game for " + this.group.name;
                    this.$bus.$emit('showMessage', this.message,  'green', '2000' );
                    this.formData =  new FormData();
                }).catch(error => {
                        this.errors = error;
                });

            },

            setData(){
               this.fields.game_id = this.playedGame.game_id;
               this.fields.date = this.playedGame.date;
               this.fields.time_played = this.playedGame.time_played;

               this.fields.remarks = "";
               this.fields.remarks = this.playedGame.remarks;

               if(this.groupUsers == ""){
                    this.groupUsers = JSON.parse(JSON.stringify(this.group.group_users));
                    for(var item in this.group.group_users){
                        this.groupUsers[item]['ScorePlayedGameId'] = false;
                        this.groupUsers[item]['ScoreDisplay'] = false;
                        this.groupUsers[item]['ScoreScore'] = 0;
                        this.groupUsers[item]['ScorePlace'] = 0;
                        this.groupUsers[item]['ScoreRemarks'] = "";
                    }
               }
               this.multigroupUsers = JSON.parse(JSON.stringify(this.group.group_users));

               for(var item in this.playedGame.scores){
                   for(var item2 in this.groupUsers){
                       if(this.playedGame.scores[item].group_user_id == this.groupUsers[item2]['id']){
                            this.groupUsers[item2]['ScorePlayedGameId'] = this.playedGame.scores[item].id;
                            this.groupUsers[item2]['ScoreDisplay'] = true;
                            this.groupUsers[item2]['ScoreScore'] = this.playedGame.scores[item].score;
                            this.groupUsers[item2]['ScorePlace'] = this.playedGame.scores[item].place;
                            if(this.playedGame.scores[item].remarks != null){
                                this.groupUsers[item2]['ScoreRemarks'] = this.playedGame.scores[item].remarks;
                            }
                            //adjust the multiselect drop down to have te users who haven't had a score
                            this.multigroupUsers.splice(item2, 1);
                       }
                   }
                }
            },

            getExpansions(game){
                this.fields.game_id = game.id;
                this.seletectedExpansions = [];
                this.selectedGame =  game.game;
                this.gameExpansions = game.game.expansions;

                //add users to the dynamic groiup
                this.multigroupUsers = JSON.parse(JSON.stringify(this.group.group_users));

                //if there are less users then the max of the game, display all users
                if(this.groupUsers.length <= this.selectedGame.players_max){
                    for(var item in this.groupUsers){
                        this.groupUsers[item]['ScoreDisplay'] = true;
                    }
                    this.multigroupUsers = [];
                }else{
                    for(var item in this.groupUsers){
                        this.groupUsers[item]['ScoreDisplay'] = false;
                    }
                }
            },
        },

        mounted(){
            if(this.submitOption == 'View'){
                this.disabled = true;
            }

            if(this.playedGame != undefined){
                this.setData();
                this.selectedGame = this.playedGame.game;
                this.gameExpansions = this.playedGame.game.expansions;
                //set selected expansions
                this.seletectedExpansions = [];
                this.playedGame.expansions.map((expansionsTest) => {
                    this.seletectedExpansions.push(expansionsTest.id);
                });    
            }
        },

        created(){
            if(this.group.id != undefined){
                //set score properties
                if(this.group != undefined){
                    
                    this.groupUsers = JSON.parse(JSON.stringify(this.group.group_users));
                    for(var item in this.group_users){
                        this.groupUsers[item]['ScorePlayedGameId'] = false;
                        this.groupUsers[item]['ScoreDisplay'] = false;
                        this.groupUsers[item]['ScoreScore'] = 0;
                        this.groupUsers[item]['ScorePlace'] = 0;
                        this.groupUsers[item]['ScoreRemarks'] = "";
                    }
                }
            }
        }
    }
</script>


<style scoped>

</style>
