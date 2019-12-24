<template>
    <div>
            <div v-if="dataList.length > 0">
                Your account has been added to {{ dataList.length }} groups, if this is correct validate the user. If unvalidaded your account id will be disconnected from the suggested groups
                <ul v-for="data in dataList"  :key="data.id">
                    <li>
                        {{ data.group['name'] }}
                        <button class="btn btn-primary" @click.prevent="approveVerifyUser(data.id)">Approve</button>
                        <button class="btn btn-primary" @click.prevent="disapproveVerifyUser(data.id)">Disapprove</button>

                    </li>
                </ul>

            </div>
        <hr>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';
    import SubmitForm from '../../mixins/SubmitForm.js';


    export default {
        components: {

        },

        mixins: [ SubmitForm],

         data () {
            return {
                'dataList' : { },
            }
        },

        methods: {
            loadList(){
                apiCall.getData('unverified-group-user')
                .then(response =>{
                    this.dataList = response;
                    this.updateField = '';
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            approveVerifyUser(id){
                apiCall.updateData('unverified-group-user/' + id)
                .then(response =>{
                    this.loadList();
                    this.$bus.$emit('reloadList');
                }).catch(() => {
                    console.log('handle server error from here');
                });
            },

            disapproveVerifyUser(id){
                 console.log("verifie user disapprovedfor id " + id);
                apiCall.deleteData('unverified-group-user/' + id)
                .then(response =>{
                    this.loadList();
                }).catch(() => {
                    console.log('handle server error from here');
                });
            }
        },

        mounted(){
            this.loadList();
        }
    }
</script>

<style scoped>

</style>
