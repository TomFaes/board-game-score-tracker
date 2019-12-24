<template>
    <div>
        <div class="row">
            <div class="col-lg-6">
                <input type="text" class="form-control " @input="isTyping = true" v-model="searchQuery" placeholder="Search game to add to group(min 3 chars)">
            </div>
        </div>

        <div class="row">
            <div align="center" v-if="isLoading">
                <span>Searching...</span>
            </div>
            <ul class="list-group">
                <li v-if="dataList.length > 0" class="list-group-item" @click="closeList">Close list</li>
                <li class="list-group-item" v-for="(item,i) in dataList" :key="item.id">
                    <i class="fa fa-plus-circle" aria-hidden="true" @click.prevent="addGame(i)"> {{ item.name }}</i>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import SubmitForm from '../../mixins/SubmitForm.js';

    export default {

        mixins: [ SubmitForm],

         data () {
            return {
                dataList: [],
                result: [],
                searchQuery: "",
                isTyping: false,
                searchResult: [],
                isLoading: false,
                'action': '',
                'formData': new FormData(),
            }
         },

         props: {
            'group': {},
         },

         watch: {
            searchQuery: _.debounce(function() {
                this.isTyping = false;
            }, 1000),
            isTyping: function(value) {
                if (!value && this.searchQuery.length > 2) {
                    this.loadList(this.searchQuery);
                }
            }
        },

        methods: {
            loadList(){
                this.isLoading = true;
                apiCall.getData('group/' + this.group.id + '/search-non-group-games?search=' + this.searchQuery)
                .then(response =>{
                    this.dataList = response;
                    this.isLoading = false;
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            addGame(index){
                this.formData.set('group_id', this.group.id);
                this.formData.set('game_id', this.dataList[index]['id']);

                this.action = "group/" + this.group.id + "/group-game";
                this.submitData();

                this.dataList.splice(index, 1);
            },

            closeList(){
                this.dataList = [];
                this.searchQuery = "";
            }
        },
    }
</script>

<style scoped>

</style>

