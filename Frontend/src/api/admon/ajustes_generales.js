import axios from "axios";

export default {
    cargar(cb, errorCb) {
        axios.get('ajustes/cargar')
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error);
            })
    },
    cargar_basico(cb, errorCb) {
        axios.get('ajustes/cargar-basico')
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
    cargar_contabilidad(cb, errorCb) {
        axios.get('ajustes/cargar-contabilidad')
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
    cargar_imagenes(cb, errorCb) {
        axios.get('ajustes/cargar-imagenes')
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
        axios.post('ajustes/guardar', data)
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
    obtenerEstadisticas(cb, errorCb) {
        axios.get('ajustes/estadistica')
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

    obtenerEstadisticasDashboard(cb, errorCb) {
        axios.get('ajustes/dashboard')
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
    cantidadPorProducto(cb, errorCb) {
        axios.get('ajustes/productos')
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
    procesarExcel(data, cb, errorCb) {
        axios.post('ajustes/procesar-excel', data,{
            header:{
                'Content-Type' : 'multipart/form-data'
            }
        })
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },
    importarDatosExcel(data, cb, errorCb) {
        axios.post('ajustes/importar-datos-excel', data)
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data)
                }
            })
            .catch(function (error) {
                errorCb(error)
            })
    },

    cargarRecursos(cb, errorCb) {
        axios.get('obtener-recursos')
            .then(function (response) {
                if (response.data.status === 'success') {
                    cb(response.data.result)
                } else {
                    errorCb(response.data.result)
                }
            })
            .catch(function (error) {
                errorCb(error);
            })
    },
}
