<template>
    <b-card>
        <div class="panel">
            <div class="panel-heading">
                <b-row>
                    <b-col cols="6" md="6" sm="12">
                        <span class="panel-title">Solicitud de Pago No.: {{form.id_solicitud_pago}}</span>
                    </b-col>
                    <b-col cols="6" md="6" sm="12" class="d-flex justify-content-end align-content-end">
                            <div class="btn-group ml-3">
                                <a @click.prevent="descargarReporteSolicitud" class="btn btn-primary">
                                    <feather-icon icon="DownloadIcon"></feather-icon>
                                    Descargar</a>
                                <a v-if="form.estado===4" @click.prevent="descargarReporteCheque"
                                   class="btn btn-primary">
                                    <feather-icon icon="DownloadIcon"></feather-icon>
                                    Descargar Cheque</a>
                            </div>
                    </b-col>
                </b-row>
            </div>

            <div class="panel-body pt-5">
                <div class="document p-4 border-primary">
                    <div class="document-heading">
                        <div class="row text-center">
                            <div class="col col-12 text-center">
                                <div class="company-name">{{nombre_empresa}}</div>
                                <div class="company-address">{{direccion_empresa}}</div>
                                <div class="company-address"><strong>Solicitud de Pago No. :
                                    {{form.id_solicitud_pago}}</strong></div>
                            </div>
                        </div>
                        <div class="row mt-3">

                            <div class="col col-6">
                                <p>
                                    <strong>Beneficiario:</strong>
                                </p>
                                <span>{{form.beneficiario}}</span>

                                <p class="pt-2">
                                    <strong>Monto
                                        {{form.moneda_solicitud?form.moneda_solicitud.codigo:"N/A"}}: </strong>
                                </p>
                                <span>{{form.monto|formatMoney(2)}} ({{form.monto?obtener_monto_letras(form.monto,form.moneda_solicitud):"N/A"}})</span>

                                <p class="pt-2">
                                    <strong>En concepto de:</strong>
                                </p>
                                <span>{{form.concepto}}</span>
                                <br>
                            </div>
                            <!-- Actualizar en estados de cheques:
                             Revisado (Pasa por contabilidad),
                             Firmado (En gerencia se firma),
                             Disponible,
                             Retenido,
                             Pagado (Entregado) ,
                             Archivado (Ultimo estado))-->
                            <div class="col col-6 d-flex flex-column align-content-end text-right">
                                <table class="document-summary">
                                    <tbody>
                                    <tr>
                                        <td>Estado actual</td>
                                        <td>
                                            <div v-if="form.estado===0">
                                                    <span class="badge badge-danger"
                                                          style="font-size: 100%;">Anulado</span>
                                            </div>

                                            <div v-if="form.estado===1">
                                                <span class="badge badge-primary"
                                                      style="font-size: 100%;">Registrado</span>
                                            </div>

                                            <div v-if="form.estado===2">
                                                <span class="badge badge-success"
                                                      style="font-size: 100%;">Autorizado</span>
                                            </div>
                                            <div v-if="form.estado===3">
                                            <span class="badge badge-success"
                                                  style="font-size: 100%;">Contabilizado</span>
                                            </div>

                                            <div v-if="form.estado===4">
                                                <span class="badge badge-success"
                                                      style="font-size: 100%;">Revisado</span>
                                            </div>

                                            <div v-if="form.estado===5">
                                                <span class="badge badge-success"
                                                      style="font-size: 100%;">Firmado</span>
                                            </div>

                                            <div v-if="form.estado===6">
                                                <span class="badge badge-success"
                                                      style="font-size: 100%;">Retenido</span>
                                            </div>
                                            <div v-if="form.estado===7">
                                                <span class="badge badge-success"
                                                      style="font-size: 100%;">Disponible</span>
                                            </div>
                                            <div v-if="form.estado===8">
                                                    <span class="badge badge-success"
                                                          style="font-size: 100%;">Pagado</span>
                                            </div>
                                            <div v-if="form.estado===9">
                                                <span class="badge badge-success"
                                                      style="font-size: 100%;">Archivado</span>
                                            </div>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Número solicitud de pago::</td>
                                        <td>{{form.id_solicitud_pago}}</td>
                                    </tr>
                                    <tr>
                                        <td>Fecha Solicitud:</td>
                                        <td>{{ form.fecha_solicitud}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <template v-if="form.estado >= 2">
                        <div class="document-heading pt-3">
                            <div class="row">
                                <div class="col col-12 text-center">
                                    <div class="company-address"><strong>Detalle de Autorización de Pago</strong></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">

                            <div class="col col-4">
                                <p>
                                    <strong>Cuenta Bancaria Autorizada:</strong>
                                </p>
                                <span>{{form.solicitud_cuenta_bancaria.numero_cuenta}}</span>
                            </div>
                            <div class="col col-4">
                                <p>
                                    <strong>Fecha Autorización:</strong>
                                </p>
                                <span v-if="form.f_aprobacion">{{ form.f_aprobacion}}</span>
                            </div>
                            <div class="col col-4">
                                <p>
                                    <strong>Método de Pago:</strong>
                                </p>
                                <span v-if="form.tipo_pago">{{form.tipo_pago===1?'Cheque':'Transferencia'}}</span>
                                <span v-else>{{'Pendiente de Definir'}}</span>
                            </div>
                            <div class="col col-4 pt-2">
                                <p>
                                    <strong>Usuario Autoriza:</strong>
                                </p>
                                <span>{{form.usuario_aprueba.trabajador.nombre_completo+'('+ form.u_aprobacion + ')'}}</span>
                            </div>
                        </div>
                        <br>
                    </template>
                    <br>
                    <template v-if="form.estado >= 3">

                        <div class="document-heading">
                            <div class="row">
                                <div class="col col-12 text-center">
                                    <div class="company-address"><strong>Detalle de Contabilización</strong></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col col-12">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><strong>Número Documento:</strong></td>
                                        <td>
                                            <span class="badge badge-primary" style="font-size: 100%;">{{form.documento_solicitud.num_documento}}</span>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td><strong>Concepto:</strong></td>
                                        <td>{{form.documento_solicitud.concepto}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha Contabilización:</strong></td>
                                        <td>{{ form.documento_solicitud.fecha_emision}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover table-responsive">
                            <thead>
                            <tr>
                                <th style="min-width:200px">Centro C/I</th>
                                <th style="min-width:200px">Cuenta Contable</th>
                                <th style="min-width:200px">Debe C$</th>
                                <th style="min-width:200px">Haber C$</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="form.documento_solicitud">
                                <template v-for="(item, index) in form.documento_solicitud.movimientos_cuentas">
                                    <tr>
                                        <td style="width: 20%">
                                            {{item.id_centro?item.centro_costo.descripcion:"N/A"}}
                                        </td>

                                        <td style="width: 25%">
                                            {{item.cuenta_contable.nombre_cuenta_completo}}
                                        </td>

                                        <td>
                                            {{item.debe}}
                                        </td>

                                        <td>
                                            {{item.haber}}
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td style="width: 30%" colspan="2" class="text-right">Totales C$</td>
                                <td style="width: 20%" class="item-footer">
                                    <strong class="item-dark form-control">{{total_debe | formatMoney(2)}}</strong>
                                </td>
                                <td style="width: 20%" class="item-footer">
                                    <strong class="item-dark form-control">{{total_haber | formatMoney(2)}}</strong>
                                </td>
                            </tr>
                            </tfoot>
                        </table>

                    </template>
                </div>
            </div>


            <template v-if="loading">
                <BlockUI>
                    <b-spinner variant="info" label="loading..."/>
                </BlockUI>
            </template>

        </div>
    </b-card>
</template>
<script type="text/ecmascript-6">
    import solicitud_pago from '../../../../api/Tesoreria/SolicitudesPagos';
    import loadingImage from '../../../../assets/images/loader/block50.gif';
    import numberasstring from "../../../../assets/custom-scripts/NumberAsString";
    import axios from 'axios';
    import {
        BBadge,
        BButton,
        BCard,
        BCardFooter,
        BCol,
        BFormCheckbox,
        BFormDatepicker, BFormGroup,
        BPaginationNav,
        BRow, BSpinner, BTab, BTable, BTabs, VBTooltip
    } from "bootstrap-vue";
    import ToastificationContent from "../../../../@core/components/toastification/ToastificationContent";

    export default {
        components: {
            BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
            BBadge,
            BTab,
            BTabs,
            BSpinner,
            BTable
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data() {
            return {
                loading: true,
                msg: 'Cargando el contenido espere un momento',
                url: '/public' + loadingImage,
                nombre_empresa: '',
                direccion_empresa: '',
                format: "dd-MM-yyyy",
                form: {
                    num_documento: "",
                    documento_cuenta: [],
                    solicitud_cuenta_bancaria: [],
                    monto_letras: '',
                },
                btnAction: "Registrar",
                errorMessages: []
            };
        },
        methods: {
            descargarReporteSolicitud() {
                if (!this.loading) {
                    const self = this;
                    self.loading = false;
                    const extensionx = 'pdf';
                    let url = 'tesoreria/solicitudespagos/reporte/' + self.form.id_solicitud_pago + '/' + extensionx;

                    axios.get(url, {responseType:'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})
                            extensionx === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'ReporteSolicitudPago.' + extensionx;
                            link.click();
                            this.loading = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            },{
                                position:'bottom-right'
                            });
                        }).catch(function (error) {
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Error descargando archivo ...' + error,
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        }, {
                            position: 'bottom-right'
                        });
                        self.loading = false;
                    })
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Espere a que se complete la descarga anterior',
                            variant: 'warning',
                            position: 'bottom-right'
                        }
                    }, {
                        position: 'bottom-right'
                    });
                }
            },

            descargarReporteCheque() {
                if (!this.loading) {
                    var self = this;
                    self.loading = true;
                    var extensionx = 'pdf';
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Descargando datos, espere un momento ...',
                            variant: 'success',
                            position: 'bottom-right'
                        }
                    }, {
                        position: 'bottom-right'
                    });
                    axios.post('bancos/solicitud-pago/reporte-cheque',
                        {
                            id_solicitud_pago: self.form.id_solicitud_pago,
                            extension: extensionx
                        }, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})
                            extensionx === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'ReporteCheque.' + extensionx;
                            link.click()
                            this.loading = false;
                        }).catch(function (error) {
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Error descargando archivo ...',
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        }, {
                            position: 'bottom-right'
                        });
                        self.loading = false;
                    })
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Espere a que se complete la descarga anterior',
                            variant: 'warning',
                            position: 'bottom-right'
                        }
                    }, {
                        position: 'bottom-right'
                    });
                }
            },

            obtener_monto_letras(montox, moneda) {
                let valor_letras_solicitud = '';
                if (montox > 0) {
                    valor_letras_solicitud = numberasstring.numberasstring(montox, moneda.descripcion_singular.toUpperCase(), moneda.descripcion.toUpperCase(), true);
                } else {
                    valor_letras_solicitud = 'SELECCIONE UN MONTO MAYOR QUE CERO';
                }
                return valor_letras_solicitud;
            },
            downloadItem(extension, id_importacionx) {
                var self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'inventario/importaciones/reporte/' + extension + '/' + id_importacionx;
                    this.descargando = true;
                    // alertify.success("Descargando datos, espere un momento.....", 5);
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Descargando datos, espere un momento ...',
                            variant: 'success',
                            position: 'bottom-right'
                        }
                    }, {
                        position: 'bottom-right'
                    });
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'FormatoImportacion.' + extension;
                            link.click()
                            this.descargando = false;
                            self.loading = false;
                        }).catch(function (error) {
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Error descargando archivo ...',
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        }, {
                            position: 'bottom-right'
                        });
                        self.descargando = false;
                        self.loading = false;
                    })
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Espere a que se complete la descarga anterior',
                            variant: 'warning',
                            position: 'bottom-right'
                        }
                    }, {
                        position: 'bottom-right'
                    });
                }
            },

            obtenerSolicitudCompleta() {
                var self = this;
                solicitud_pago.obtenerSolicitudPago({
                        id_solicitud_pago: this.$route.params.id_solicitud_pago
                    }, data => {
                        self.form = data.solicitud;
                        self.nombre_empresa = data.nombre_empresa;
                        self.direccion_empresa = data.direccion_empresa;
                        self.form.numero_comprobante_pago = '';
                        if (self.form.tipo_pago === 1) {
                            self.form.numero_comprobante_pago = self.form.solicitud_cuenta_bancaria.numeracion_chequera.toString();
                        }
                        self.loading = false;
                    },
                    err => {
                        console.log(err);
                    })

            },
        },
        computed: {
            total_debe() {
                if (this.form.documento_solicitud) {
                    return this.form.documento_solicitud.movimientos_cuentas.reduce((carry, item) => {
                        return (carry + (Number(item.debe)))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                    }, 0)
                } else {
                    return 0;
                }
            },
            total_haber() {
                if (this.form.documento_solicitud) {
                    return this.form.documento_solicitud.movimientos_cuentas.reduce((carry, item) => {
                        return (carry + (Number(item.haber)))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                    }, 0)
                } else {
                    return 0;
                }
            },
            total_cantidad_recibida() {
                return this.form.entrada_productos.reduce((carry, item) => {
                    return (carry + Number(item.cantidad_recibida))
                }, 0)
            },

            total_subt() {
                return this.form.entrada_productos.reduce((carry, item) => {
                        return (carry + Number((Number(item.cantidad_recibida * item.precio_unitario).toFixed(2))));
                    }
                    , 0)
            },
            gran_total() {
                return this.form.entrada_productos.reduce((carry, item) => {
                        return (carry + Number((Number(item.cantidad_recibida * item.precio_unitario).toFixed(2))));
                    }
                    , 0)
            },
        },
        mounted() {
            this.obtenerSolicitudCompleta();
        }
    };
</script>
<style>
    table tr td {
        padding-bottom: 1rem;
    }
</style>