export default {
    getData(action) {
        return axios({
            method: 'get',
            url : 'api/' + action
        })
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
    },

    postData(action, fields){
        return axios({
            method: 'POST',
            url : 'api/' + action,
            data: fields,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(function (response) {
            return response.data;
        })
        .catch(error => {
            if (error.response.status === 422) {
               return Promise.reject(error.response.data.errors || {})
            };
        });
    },

    updateData(action, fields){
        return axios({
            method: 'POST',
            url : 'api/' + action,
            data: fields,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(function (response) {
            return response.data;
        })
        .catch(error => {
            if (error.response.status === 422) {
               return Promise.reject(error.response.data.errors || {})
            };
        });
    },

    deleteData(action, id){
        return axios({
            method: 'DELETE',
            url : 'api/' + action,
            data: id,
        })
        .then(function (response) {
            return response.data;
        })
        .catch(error => {
            if (error.response.status === 422) {
               return Promise.reject(error.response.data.errors || {})
            };
        });
    },
}
