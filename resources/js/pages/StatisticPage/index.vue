<template>
    <div class="container">
        <!--
        <div v-if="display == ''">
            -->
        <div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <h2>Statistics</h2>
                </div>
                <div class="col-2"></div>
            </div>

            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <center>
                        <button class="btn btn-primary" @click.prevent="loadGroupData()">All</button>
                        <button class="btn btn-primary" @click.prevent="loadYearData('year')">Year</button>
                        <button class="btn btn-primary" @click.prevent="loadGameData()">Game</button>
                    </center>
                </div>
                <div class="col-1"></div>
            </div>
        </div>

        <!-- Year Statistics -->
        <div v-if="display != ''">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">

                    <!-- Group Statistics -->
                    <div v-if="display == 'all'">
                            <h3>Group Statistics</h3>
                    </div>

                    <!-- Year Statistics -->
                    <div v-if="display == 'year'" >
                            <h3>
                                <button class="btn btn-secondary" @click.prevent="lowerYear()"><i class="fas fa-angle-double-left item-link"></i></button>
                                {{ year }}
                                <button class="btn btn-secondary" @click.prevent="addYear()"><i class="fas fa-angle-double-right item-link"></i></button>
                            </h3>
                    </div>

                    <div v-if="display == 'game'">
                        <h3>Group game Stats</h3>
                        <multiselect
                            :multiple="false"
                            :options="group.base_group_games"
                            :close-on-select="true"
                            :clear-on-select="true"
                            placeholder="Choose a game"
                            label="name"
                            track-by="name"
                            @select="loadGameData"
                        >
                        </multiselect><br>
                    </div>
                </div>
                <div class="col-1"></div>
             </div>
        </div>

        <group-stats :dataList=statsList :group="group"></group-stats>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import Moment from 'moment';

    import DatePicker from 'vuejs-datepicker';
    import groupStats from '../StatisticPage/group.vue';

    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            groupStats,
            Moment,
            DatePicker,
            Multiselect,
        },

        props: {
            'group': {}
        },

        data () {
            return {
                'statsList': {},
                'display' : '',
                'year': Moment().format('YYYY'),
                'gameId': '',
            }
        },

        methods: {
            loadGroupData(){
                apiCall.getData('stats/group/' + this.group.id)
                .then(response =>{
                    this.statsList = response;
                    this.showStat('all');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            loadYearData(){
                apiCall.getData('stats/group/' + this.group.id + '/year/' + Moment(new Date(this.year)).format('YYYY'))
                .then(response =>{
                    this.statsList = response;
                    this.showStat('year');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            loadGameData(game){
                if(game){
                    apiCall.getData('stats/group/' + this.group.id + '/game/' + game['id'])
                    .then(response =>{
                        this.statsList = response;
                        this.showStat('game');
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
                }else{
                     this.showStat('game');
                }
            },

             showStat(value){
                this.display = value;
            },

            addYear(){
                this.year = Moment(new Date(this.year)).add(1, 'years').format('YYYY');
                this.loadYearData();
            },

            lowerYear(){
                this.year = Moment(new Date(this.year)).subtract(1, 'years').format('YYYY');
                this.loadYearData();
            }
        },

        mounted(){
            this.$bus.$on('resetStatsPage', () => {
                this.display = '';
                this.statsList = {};
            });
        }
    }
</script>

<style scoped>
button{
    /*width: 75px;*/
}


.calendar{
    display: inline-block;
    /*width: 100px;*/
}
</style>
