import axios from "axios"
export default {
        obtener(data, cb, errorCb) {
            axios.post('crm/medio_contacto/obtener', data)
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
        obtenerClasificacionCliente(data, cb, errorCb) {
            axios.post('crm/medio_contacto/obtener-medio_contacto', data)
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
            axios.post('crm/medio_contacto/registrar', data)
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
            axios.put('crm/medio_contacto/actualizar', data)
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
            axios.put('crm/medio_contacto/activar', data)
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
            axios.put('crm/medio_contacto/desactivar', data)
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
            axios.get('crm/medio_contacto/reporte', data)
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