import axios from "axios";
export default {
    obtener(data, cb, errorCb) {
        axios.post('grupo/obtener', data)
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    obtenerTodos(cb, errorCb) {
        axios.get('grupo/obtener-grupo-todos')
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },

    obtenergrupo(data, cb, errorCb) {
        axios.post('grupo/obtener-grupo', data)
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    registrar(data, cb, errorCb) {
        axios.post('grupo/registrar', data)
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    actualizar(data, cb, errorCb) {
        axios.put('grupo/actualizar', data)
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },



}