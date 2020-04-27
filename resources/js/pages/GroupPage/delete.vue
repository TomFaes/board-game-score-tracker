<template>
    <div>
        <button class="btn btn-danger" @click.prevent="deleteRow()" ><i class="fas fa-trash fa-1x" ></i></button>
    </div>
</template>

<script>
    import apiCall from '../../services/ApiCall.js';

    export default {
         data () {
            return {
                'response': {},
                'message': '',
            }
        },

        props: {
            'group': {},
         },

        methods: {
           deleteRow(){
               if(confirm('are you sure you want to delete this group ' + this.group.name + '?')){
                    apiCall.deleteData('group/' + this.group.id)
                    .then(response =>{
                        this.$bus.$emit('reloadGroups');
                        this.$bus.$emit('displayNav', "" );
                        this.message = "Your group " + this.group.name + " has been deleted";
                        this.$bus.$emit('showMessage', this.message,  'red', '2000' );
                    }).catch(() => {
                        console.log('handle server error from here');
                    });
               }
            },
        },
    }
</script>

<style scoped>

</style>
