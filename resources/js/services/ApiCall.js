import axios from 'axios';

//create a variable local path, in production there will be antohter path
var localPath = "";
if(process.env.NODE_ENV == 'development'){
    localPath= "/boardgametracker/public_html"
}

export default {
    getData(action, pageItems = 0) {
        return axios({
            method: 'get',
            params: { page_items: pageItems },
            url : localPath +  '/api/' + action,
        })
        .then(function (response) {
            return response;
        })
        .catch(function (error) {
            return Promise.reject(error.response || {});
        });
    },

    postData(action, fields){
        return axios({
            method: 'POST',
            url : localPath +  '/api/' + action,
            data: fields,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(function (response) {
            return response;
        })
        .catch(error => {
            if (error.response.status === 422) {
               return Promise.reject(error.response.data.errors || {})
            };
            return Promise.reject(error.response || {});
        });
    },

    updateData(action, fields){
        return axios({
            method: 'POST',
            url : localPath +  '/api/' + action,
            data: fields,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(function (response) {
            return response;
        })
        .catch(error => {
            if (error.response.status === 422) {
               return Promise.reject(error.response.data.errors || {})
            };
            return Promise.reject(error.response || {});
        });
    },

    deleteData(action, id){
        return axios({
            method: 'DELETE',
            url : localPath +  '/api/' + action,
            data: id,
        })
        .then(function (response) {
            return response;
        })
        .catch(error => {
            if (error.response.status === 422) {
               return Promise.reject(error.response.data.errors || {})
            };
            return Promise.reject(error.response || {});
        });
    },
}
