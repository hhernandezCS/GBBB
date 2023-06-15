<template>
    <b-row>
        <div class="col-sm-3 col-md-6">
            <b-card>
                <h3>
                    Reporte de ventas
                </h3>
                <div class="col-md-12">
                    <label for="id_grupo">* Grupo:</label>
                    <v-select :options="formFactura.grupos"
                              label="descripcion"
                              v-model="formFactura.gruposSelecionado"
                              id="id_grupo"

                    ></v-select>
                </div>

                <div class="col-md-3.5 mr-1">
                    <label for="fecha_inicial">* Fecha Inicial:</label>
                    <b-form-datepicker
                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                            @selected="onDateSelectRLFInicial"
                            class="mb-0"
                            local="es"
                            placeholder="f.inicial"
                            selected-variant="primary"
                            v-model="formFactura.fecha_inicial"
                    ></b-form-datepicker>
                </div>
                <div class="col-md-3.5 mr-1">
                    <label for="fecha_final">* Fecha Final:</label>
                    <b-form-datepicker
                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                            @selected="onDateSelectRLFFinal"
                            class="mb-0"
                            local="es"
                            placeholder="f.final"
                            selected-variant="primary"
                            v-model="formFactura.fecha_final"

                    ></b-form-datepicker>
                </div>
                <div class="col-md-12 mt-1">
                    <a :disabled="descargando"
                       @click.prevent="downloadReporteListadodeFactura ('pdf',formFactura.gruposSelecionado.id_grupo, formFactura.fecha_inicial,formFactura.fecha_final)"
                       style="color: #ffffff;padding-left: 2px">
                        <b-button :disabled="descargando" class="mt-1 mr-1" v-b-tooltip.hover.top="'Descargar PDF'"
                                  variant="danger"> PDF
                            <feather-icon icon="DownloadCloudIcon"></feather-icon>
                        </b-button>
                    </a>
                    <a :disabled="descargando"
                       @click.prevent="downloadReporteListadodeFactura ('xls', formFactura.gruposSelecionado.id_grupo,formFactura.fecha_final,formFactura.fecha_final)"
                       style="color: #ffffff;padding-left: 2px">
                        <b-button :disabled="descargando" class="mt-1" v-b-tooltip.hover.top="'Descargar XLS'"
                                  variant="success"> XLS
                            <feather-icon icon="DownloadCloudIcon"></feather-icon>
                        </b-button>
                    </a>
                </div>
            </b-card>
        </div>
        <div class="col-sm-3 col-md-6">
            <b-card>
                <h3>
                    Reporte Clientes
                </h3>
                <a :disabled="descargando" @click.prevent="downloadItemClientes ('pdf')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1 mr-1" variant="danger" v-b-tooltip.hover.top="'PDF'">
                        Decargar PDF
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
                <a :disabled="descargando" @click.prevent="downloadItemClientes ('xls')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1" variant="success" v-b-tooltip.hover.top="'XLS'">
                        Decargar XLS
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
            </b-card>
        </div>
        <div class="col-sm-3 col-md-6">
            <b-card>
                <h3>
                    Reporte Vendedores
                </h3>
                <a :disabled="descargando" @click.prevent="downloadItemVendedores ('pdf')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1 mr-1" variant="danger" v-b-tooltip.hover.top="'PDF'">
                        Decargar PDF
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
                <a :disabled="descargando" @click.prevent="downloadItemVendedores ('xls')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1" variant="success" v-b-tooltip.hover.top="'XLS'">
                        Decargar XLS
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
            </b-card>
        </div>
        <div class="col-sm-3 col-md-6">
            <b-card>
                <h3>
                    Reporte Cuentas Bancarias
                </h3>
                <a :disabled="descargando" @click.prevent="downloadItemCuentasBancarias ('pdf')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1 mr-1" variant="danger" v-b-tooltip.hover.top="'PDF'">
                        Decargar PDF
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
                <a :disabled="descargando" @click.prevent="downloadItemCuentasBancarias ('xls')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1" variant="success" v-b-tooltip.hover.top="'XLS'">
                        Decargar XLS
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
            </b-card>
        </div>
        <div class="col-sm-3 col-md-6">
            <b-card>
                <h3>
                    Reporte Tipo Clientes
                </h3>
                <a :disabled="descargando" @click.prevent="downloadItemTipoClientes ('pdf')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1 mr-1" variant="danger" v-b-tooltip.hover.top="'PDF'">
                        Decargar PDF
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
                <a :disabled="descargando" @click.prevent="downloadItemTipoClientes ('xls')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1" variant="success" v-b-tooltip.hover.top="'XLS'">
                        Decargar XLS
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
            </b-card>
        </div>
        <div class="col-sm-3 col-md-6">
            <b-card>
                <h3>
                    Reporte Bancos
                </h3>
                <a :disabled="descargando" @click.prevent="downloadItemBancos ('pdf')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1 mr-1" variant="danger" v-b-tooltip.hover.top="'PDF'">
                        Decargar PDF
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
                <a :disabled="descargando" @click.prevent="downloadItemBancos ('xls')"
                   style="color: #ffffff;padding-left: 2px">
                    <b-button :disabled="descargando" class="mt-1" variant="success" v-b-tooltip.hover.top="'XLS'">
                        Decargar XLS
                        <feather-icon icon="DownloadCloudIcon"></feather-icon>
                    </b-button>
                </a>
            </b-card>
        </div>
    </b-row>
</template>

<script>
    import axios from "axios";
    import {
        BPaginationNav,
        BFormCheckbox,
        BFormGroup,
        BCard,
        BCardFooter,
        VBTooltip,
        BRow,
        BButton,
        BFormCheckboxGroup,
        BFormDatepicker,
        BAlert,
        BFormSelect,
    } from 'bootstrap-vue'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import vSelect from 'vue-select'
    import bodegas from "../../../api/Inventario/bodegas";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import catalogo from "../../../api/contabilidad/reportes_contabilidad";

    export default {
        components: {
            BCard,
            BCardFooter,
            BPaginationNav,
            BFormCheckbox,
            BFormGroup,
            vSelect,
            BRow,
            BButton,
            BFormCheckboxGroup,
            BFormDatepicker,
            BAlert,
            BFormSelect,
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data() {
            return {
                descargando: false,
                loading: true,
                url: loadingImage,   //It is important to import the loading image then use here
                filter: {
                    page: 1,
                    limit: 5,
                    limitOptions: [5, 10, 15, 20],
                    search: {
                        field: "no_documento",
                        value: ""
                    }
                },
                form: {
                    id_bodega: ''
                },
                bodegas: [],
                facturas: [],
                total: 0,
                formFactura: {
                    grupos: [],
                    gruposSelecionado: [],
                    fecha_inicial: moment(new Date()).format("YYYY-MM-DD"),
                    fecha_final: moment(new Date()).format("YYYY-MM-DD")
                },
            };
        },
        methods: {
            obtenerCatalagoFactura() {
                let self = this;
                self.loading = true;
                catalogo.obtenerCatalago(
                    data => {
                        self.loading = false;
                        self.formFactura.grupos = data.grupos;

                        self.formFactura.gruposSelecionado = self.formFactura.grupos[0];


                        //Metodo de notificacion
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: data.messages,
                                variant: 'success',
                            }
                        }, {
                            position: 'bottom-right'
                        });
                        /self.filter.nivel_cuenta=self.niveles_cuenta[1];/
                    },
                    err => {
                        self.loading = false;
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: err.messages,
                                variant: 'danger',
                            }
                        }, {
                            position: 'bottom-right'
                        });
                    }
                );


            },
            /*
      *Reporte ventas semana
      * */
            downloadReporteListadodeFactura(extension, id_grupo, fecha_inicial, fecha_final) {
                const self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'cajabanco/reporte-venta/reporte/' + extension + '/' + id_grupo + '/' + fecha_inicial + '/' + fecha_final;
                    this.descargando = true;
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'ReporteVentas.' + extension;
                            link.click()
                            //this.descargando = false;
                            self.loading = false;
                            self.descargando = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            }, {
                                position: 'bottom-right'
                            });
                        }).catch(function (error) {
                        self.loading = false;
                        self.descargando = false;
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Ha ocurrido un error descargando el reporte',
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        self.descargando = false;
                        self.loading = false;
                    })
                } else {
                    self.loading = false;
                    self.descargando = false;
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Espere que se descargue el Reporte anterior',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },
            downloadItemClientes(extension) {
                const self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'cuentas-por-cobrar/clientes/' + extension;
                    this.descargando = true;
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'FormatoClientes.' + extension;
                            link.click()
                            //this.descargando = false;
                            self.loading = false;
                            self.descargando = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            }, {
                                position: 'bottom-right'
                            });
                        }).catch(function (error) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Error descargando el archivo ' + error,
                                    variant: 'warning',
                                }
                            },
                            {
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
                                icon: 'CheckIcon',
                                text: 'Espere a que se complete la descarga anterior',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },

            downloadItemVendedores(extension) {
                const self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'ventas/vendedores/reporte/' + extension;
                    this.descargando = true;
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'FormatoVendedores.' + extension;
                            link.click()
                            //this.descargando = false;
                            self.loading = false;
                            self.descargando = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            }, {
                                position: 'bottom-right'
                            });
                        }).catch(function (error) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Error descargando el archivo ' + error,
                                    variant: 'warning',
                                }
                            },
                            {
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
                                icon: 'CheckIcon',
                                text: 'Espere a que se complete la descarga anterior',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },
            downloadItemCuentasBancarias(extension) {
                const self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'contabilidad/cuentas-bancarias/reporte/' + extension;
                    this.descargando = true;
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'FormatoCuentasBancarias.' + extension;
                            link.click()
                            //this.descargando = false;
                            self.loading = false;
                            self.descargando = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            }, {
                                position: 'bottom-right'
                            });
                        }).catch(function (error) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Error descargando el archivo ' + error,
                                    variant: 'warning',
                                }
                            },
                            {
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
                                icon: 'CheckIcon',
                                text: 'Espere a que se complete la descarga anterior',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },
            downloadItemTipoClientes(extension) {
                const self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'ventas/tipo-cliente/reporte/' + extension;
                    this.descargando = true;
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'FormatoTipoClientes.' + extension;
                            link.click()
                            //this.descargando = false;
                            self.loading = false;
                            self.descargando = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            }, {
                                position: 'bottom-right'
                            });
                        }).catch(function (error) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Error descargando el archivo ' + error,
                                    variant: 'warning',
                                }
                            },
                            {
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
                                icon: 'CheckIcon',
                                text: 'Espere a que se complete la descarga anterior',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },
            downloadItemBancos(extension) {
                const self = this;
                if (!this.descargando) {
                    self.loading = true;
                    let url = 'cajabanco/bancos/reporte/' + extension;
                    this.descargando = true;
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})

                            extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'FormatoBancos.' + extension;
                            link.click()
                            //this.descargando = false;
                            self.loading = false;
                            self.descargando = false;
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Su archivo se ha descargado correctamente',
                                    variant: 'success',
                                }
                            }, {
                                position: 'bottom-right'
                            });
                        }).catch(function (error) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'Error descargando el archivo ' + error,
                                    variant: 'warning',
                                }
                            },
                            {
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
                                icon: 'CheckIcon',
                                text: 'Espere a que se complete la descarga anterior',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },
            //Selecionar las fecha para reporte de ventas
            onDateSelectRLFInicial(date) {
                this.formFactura.fecha_inicial = moment(date).format("YYYY-MM-DD"); //
            },
            onDateSelectRLFFinal(date) {
                this.formFactura.fecha_final = moment(date).format("YYYY-MM-DD"); //
            },

        },
        mounted() {
            this.obtenerCatalagoFactura();
        }
    }
</script>

<style scoped>

</style>