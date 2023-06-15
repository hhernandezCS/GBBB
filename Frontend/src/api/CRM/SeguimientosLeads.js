import axios from "axios";

export default {
    obtener(data, cb, errorCb) {
        axios.post('crm/seguimiento-lead/obtener', data)
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
    obtenerSeguimiento(data, cb, errorCb) {
        axios.post('crm/seguimiento-lead/obtener-seguimiento', data)
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
    obtenerSeguimientoEspecifico(data, cb, errorCb) {
        axios.post('crm/seguimiento-lead/obtener-seguimiento-especifico', data)
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
        axios.post('crm/seguimiento-lead/registrar', data)
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
        axios.put('crm/seguimiento-lead/actualizar', data)
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
        axios.put('crm/seguimiento-lead/desactivar', data)
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
