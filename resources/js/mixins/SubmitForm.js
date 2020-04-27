import apiCall from '../services/ApiCall';

export default {
    data() {
        return {
            fields: {},
            errors: {},
            'formData': new FormData(),
            action: '',
            reload:'',
            response: {},
         }
    },

    methods: {
        submitData(){
            apiCall.postData(this.action, this.formData)
            .then(response =>{
                this.response = response;
                if(this.reload != ""){
                    this.$bus.$emit(this.reload);
                }
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
                this.response = response;
                if(this.reload != ""){
                    this.$bus.$emit(this.reload);
                }
                this.errors = {};
                this.formData =  new FormData();
            }).catch(error => {
                    this.errors = error;
            });
        },
    }
  }
