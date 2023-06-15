import axios from "axios";
export default {
    obtener(data, cb, errorCb) {
        axios.post('admon/estados/obtener', data)
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
    obtenerEstado(data, cb, errorCb) {
        axios.post('admon/estados/obtener-estado', data)
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
    nuevo(data, cb, errorCb) {
        axios.post('admon/estados/nuevo', data)
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
        axios.post('admon/estados/registrar', data)
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
        axios.put('admon/estados/actualizar', data)
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
        axios.put('admon/estados/activar', data)
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
        axios.put('admon/estados/desactivar', data)
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
    generarReporte(data, cb, errorCb) {
        axios.get('admon/estados/reporte', data)
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
