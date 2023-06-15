import axios from "axios";
export default {
    obtenerTodas(cb, errorCb) {
        axios.get('nomina/nivel-estudio/obtener-todas')
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
        axios.post('nomina/nivel-estudio/obtener', data)
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
    obtenerNivelAcademico(data, cb, errorCb) {
        axios.post('nomina/nivel-estudio/obtener-nivel', data)
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
        axios.post('nomina/nivel-estudio/registrar', data)
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
        axios.put('nomina/nivel-estudio/actualizar', data)
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
        axios.put('nomina/nivel-estudio/desactivar', data)
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
        axios.put('nomina/nivel-estudio/activar', data)
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