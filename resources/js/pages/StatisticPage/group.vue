<template>
    <div class="container">
        <div  v-if="dataList.data" >
           <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <center>
                        <div>
                            <button  class="btn btn-primary testclass d-none d-md-inline-block" @click.prevent="showStat('all')">All</button>
                            <button class="btn btn-primary testclass" @click.prevent="showStat('scores')">Scores</button>
                            <button class="btn btn-primary testclass" @click.prevent="showStat('places')">Places</button>
                            <button class="btn btn-primary testclass" @click.prevent="showStat('victories')">Vicories</button>
                       </div>
                    </center>
                    <br>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Game</th>
                            <th class="d-none d-sm-table-cell">Expansions</th>

                            <template v-if="display == 'scores' || display == 'all'">
                                <th v-for="user in  groupUsers.data" :key="user.id" style="background-color: yellow"><center>{{ user.firstname }}</center></th>
                            </template>
                            <template v-if="display == 'places' || display == 'all'">
                                <th v-for="user in  groupUsers.data" :key="user.id" style="background-color: LightGreen" ><center> {{ user.firstname }} </center></th>
                            </template>
                            <template v-if="display == 'victories' || display == 'all'">
                                <th v-for="user in  groupUsers.data" :key="user.id"  style="background-color: PaleVioletRed"><center> {{ user.firstname }} </center></th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <tr  v-for="data in dataList.data"  :key="data.id">
                            <td nowrap>{{ convertDate(data.date) }}</td>
                            <td>{{ data.name }}</td>
                            <td  class="d-none d-sm-table-cell">
                                <span >
                                    <span v-for="expansion in data.expansions"  :key="expansion.id">
                                        - {{ expansion.name }}<br>
                                    </span>
                                </span>
                            </td>

                            <template v-if="display == 'scores' || display == 'all'">
                                <td v-for="user in data.users" :key="user.id"><center>{{ user.score}}</center></td>
                            </template>
                            <template v-if="display == 'places' || display == 'all'">
                                <td v-for="user in data.users" :key="user.id"><center>{{ user.place}}</center></td>
                            </template>
                            <template v-if="display == 'victories' || display == 'all'">
                                <td v-for="user in data.users" :key="user.id"><center>{{ user.victory}}</center></td>
                            </template>
                        </tr>
                        <tr v-if="dataList.total && display != 'scores'">
                            <td class="d-none d-sm-table-cell"></td>
                            <td colspan="2" ><b>Total: </b></td>

                            <template v-if="display == 'scores' || display == 'all'">
                                <td v-for="data in dataList.total.place" :key="data.id"></td>
                            </template>
                            <template v-if="display == 'places' || display == 'all'">
                                <td v-for="data in dataList.total.place" :key="data.id"><center>{{ data}}</center></td>
                            </template>
                            <template v-if="display == 'victories' || display == 'all'">
                                <td v-for="data in dataList.total.victory" :key="data.id"><center>{{ data}}</center></td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import Moment from 'moment';

    export default {
        data () {
            return {
                'display' : 'scores',
            }
        },

        props: {
            'group': {},
            'groupUsers': {},
            'dataList': {},
            'groupGames': {},
        },

        components: {
            Moment,
        },

        methods: {
            convertDate(value){
                return Moment(value, "YYYY-MM-DD").format('DD-MM-YYYY');
            },

            showStat(value){
                this.display = value;
            }
        },

        mounted(){
        }
    }
</script>

<style scoped>
    td, th {
        font-size: 0.7rem;
    }
</style>
