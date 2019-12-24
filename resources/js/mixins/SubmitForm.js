import apiCall from '../services/ApiCall';

export default {
    data() {
        return {
            fields: {},
            errors: {},
            'formData': new FormData(),
            action: '',
         }
    },

    methods: {
        submitData(){
            apiCall.postData(this.action, this.formData)
            .then(response =>{
                this.$bus.$emit('reloadList');
                //this.$bus.$emit('resetDisplay');
                this.fields = {}; //Clear input fields.
                this.errors = {};
                this.formData =  new FormData();
            }).catch(error => {
                    this.errors = error;
            });
        },

        updateData(){
            apiCall.updateData(this.action, this.formData)
            .then(response =>{
                this.$bus.$emit('reloadList');
                this.errors = {};
                this.formData =  new FormData();
            }).catch(error => {
                    this.errors = error;
            });
        },
    }
  }
