import axios from "axios";
export default {
    obtenerTodos(cb, errorCb) {
        axios.get('activosfijos/colores/obtener-todos')
            .then(function (response) {
                if (response.data.status == 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },

    obtener(data, cb, errorCb) {
        axios.post('activosfijos/colores/obtener', data)
            .then(function (response) {
                if (response.data.status == 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    obtenerColor(data, cb, errorCb) {
        axios.post('activosfijos/colores/obtener-color', data)
            .then(function (response) {
                if (response.data.status == 'success') {
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
        axios.post('activosfijos/colores/registrar', data)
            .then(function (response) {
                if (response.data.status == 'success') {
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
        axios.put('activosfijos/colores/actualizar', data)
            .then(function (response) {
                if (response.data.status == 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    desactivar(data, cb, errorCb) {
        axios.put('activosfijos/colores/desactivar', data)
            .then(function (response) {
                if (response.data.status == 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    activar(data, cb, errorCb) {
        axios.put('activosfijos/colores/activar', data)
            .then(function (response) {
                if (response.data.status == 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    }
}