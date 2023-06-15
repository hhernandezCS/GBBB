import axios from "axios";
export default {
    obtener(data, cb, errorCb) {
        axios.post('crm/lead/obtener', data)
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
    obtenerLead(data, cb, errorCb) {
        axios.post('crm/lead/obtener-lead', data)
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
    obtenerServicios(data, cb, errorCb) {
        axios.post('crm/lead/obtener-servicios', data)
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
        axios.post('crm/lead/nuevo', data)
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
        axios.post('crm/lead/registrar', data)
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
        axios.put('crm/lead/actualizar', data)
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
        axios.put('crm/lead/activar', data)
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
        axios.put('crm/lead/desactivar', data)
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
        axios.get('crm/lead/reporte', data)
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
  obtenerLeadUsuario(data, cb, errorCb) {
    axios.post('crm/lead/obtener-leads-por-responsable', data)
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
  reasignar(data, cb, errorCb) {
    axios.post('crm/lead/reasignar', data)
        .then(function (response) {
          if (response.data.status === 'success') {
            cb(response.data)
          } else {
            errorCb(response.data)
          }
        })
        .catch(function (error) {
          errorCb(error)
        })
  },
    registroContacto(data, cb, errorCb) {
    axios.post('crm/lead/registro-contacto', data)
        .then(function (response) {
          if (response.data.status === 'success') {
            cb(response.data)
          } else {
            errorCb(response.data)
          }
        })
        .catch(function (error) {
          errorCb(error)
        })
  },
    registroCompania(data, cb, errorCb) {
    axios.post('crm/lead/registro-compania', data)
        .then(function (response) {
          if (response.data.status === 'success') {
            cb(response.data)
          } else {
            errorCb(response.data)
          }
        })
        .catch(function (error) {
          errorCb(error)
        })
  },
    registroContactoyCompania(data, cb, errorCb) {
    axios.post('crm/lead/registro-contacto-compania', data)
        .then(function (response) {
          if (response.data.status === 'success') {
            cb(response.data)
          } else {
            errorCb(response.data)
          }
        })
        .catch(function (error) {
          errorCb(error)
        })
  },
}
