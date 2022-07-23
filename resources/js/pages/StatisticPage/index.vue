<template>
    <div class="container">
        <global-layout>
            <h2>Statistics</h2>
        </global-layout>

        <global-layout center="center" sizeForm="static page">
            <button class="btn btn-primary" @click.prevent="loadGroupData()">All</button>
            <button class="btn btn-primary" @click.prevent="loadYearData('year')">Year</button>
            <button class="btn btn-primary" @click.prevent="loadGameData()">Game</button>
        </global-layout>

        <!-- Year Statistics -->
        <global-layout center="center" sizeForm="static page" v-if="display != ''">
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
                    :options="groupGames.data"
                    :close-on-select="true"
                    :clear-on-select="true"
                    placeholder="Choose a game"
                    label="name"
                    track-by="name"
                    @select="loadGameData"
                >
                </multiselect><br>
            </div>
        </global-layout>

        <group-stats :dataList=statsList :group="group" :groupUsers="groupUsers" :groupGames="groupGames"></group-stats>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import Moment from 'moment';
    import groupStats from '../StatisticPage/group.vue';
    import Multiselect from '@suadelabs/vue3-multiselect';

    export default {
        components: {
            groupStats,
            Moment,
            Multiselect,
        },

        props: {
            'group': {},
        },

        computed: {
            groupGames(){
                if(this.group.id === undefined){
                    return;
                }

                if(this.$store.state.selectedGroupGames.data == undefined){
                    this.$store.dispatch('getSelectedGroupGames', {groupId: this.group.id});
                }
                return this.$store.state.selectedGroupGames;
            },

            groupUsers(){
                if(this.group.id === undefined){
                    return;
                }

                if(this.$store.state.selectedGroupUsers.data == undefined){
                    this.$store.dispatch('getSelectedGroupUsers', {groupId: this.group.id});
                }
                return this.$store.state.selectedGroupUsers;
            }
        },

        data () {
            return {
                'statsList': {},
                'display' : 'all',
                'year': Moment().format('YYYY'),
                'gameId': '',
            }
        },

        methods: {
            loadGroupData(){
                apiCall.getData('stats/group/' + this.group.id)
                .then(response =>{
                    this.statsList = response.data;
                    this.showStat('all');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            loadYearData(){
                apiCall.getData('stats/group/' + this.group.id + '/year/' + Moment(new Date(this.year)).format('YYYY'))
                .then(response =>{
                    this.statsList = response.data;
                    this.showStat('year');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            loadGameData(game){
                if(game){
                    apiCall.getData('stats/group/' + this.group.id + '/game/' + game.game_id)
                    .then(response =>{
                        this.statsList = response.data;
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
            if(this.display == 'all'){
                this.loadGroupData();
            }

        }
    }
</script>

<style scoped>

</style>
