import axios from "axios";
export default {
    obtenerTodas(cb, errorCb) {
        axios.get('nomina/areas/obtener-todas')
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

    obtener(data, cb, errorCb) {
        axios.post('nomina/areas/obtener', data)
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
    obtenerArea(data, cb, errorCb) {
        axios.post('nomina/areas/obtener-area', data)
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
        axios.post('nomina/areas/registrar', data)
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
        axios.put('nomina/areas/actualizar', data)
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

    desactivar(data, cb, errorCb) {
        axios.put('nomina/areas/desactivar', data)
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
    activar(data, cb, errorCb) {
        axios.put('nomina/areas/activar', data)
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
    }
}