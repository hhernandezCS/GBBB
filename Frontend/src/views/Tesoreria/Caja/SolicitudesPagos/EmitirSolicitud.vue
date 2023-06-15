<template>
    <b-card>
        <div class="main">
            <div class="row">
                <div class="col-md-12">
                    <div class="content-box">
                        <p>
                            <b-button v-b-toggle.collapseExample variant="primary">
                                Ver Detalles de Solicitud de Pago
                            </b-button>
                        </p>
                        <b-collapse class="collapse" id="collapseExample">
                            <div class="card card-body border-success" style=" padding-left: 20px;">

                                <br>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Beneficiario:</p>
                                            <p><strong>{{form.beneficiario}}</strong></p>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Monto
                                                {{form.moneda_solicitud?form.moneda_solicitud.codigo:"N/A"}}: </p>
                                            <p><strong>{{form.monto|formatMoney(2)}}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Monto en letras: </p>
                                            <p><strong>{{form.monto?obtener_monto_letras(form.monto,form.moneda_solicitud):"N/A"}}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Concepto: </p>
                                            <p><strong>{{form.concepto}}</strong></p>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Fecha: </p>
                                            <p><strong>{{moment(form.fecha_solicitud).format("YYYY-MM-DD")}}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </b-collapse>
                        <div class="row">

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for>No. Documento</label>
                                    <input v-if="form.documento_solicitud" class="form-control" readonly type="text"
                                           v-model="form.documento_solicitud.num_documento">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.num_documento"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for>Fecha Emision</label>
                                    <input class="form-control" readonly v-model.number="form.fecha_solicitud">

                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Monto Solicitado
                                        ({{form.moneda_solicitud?form.moneda_solicitud.codigo:"N/A"}})</label>
                                    <input class="form-control" readonly ref="valor" v-model.number="form.monto">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Cuenta bancaria</label>
                                    <input v-if="form.solicitud_cuenta_bancaria" class="form-control" readonly
                                           v-model.number="form.solicitud_cuenta_bancaria.numero_cuenta">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.cuenta_bancaria"
                                            v-text="message"></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Monto Neto del Cheque (<strong>{{form.moneda_aprobada_solicitud?form.moneda_aprobada_solicitud.descripcion:"N/A"}}</strong>)</label>
                                    <input :disabled="true" class="form-control" placeholder="Monto del cheque"
                                           v-model.number="form.monto_neto">
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Valor en letras</label>
                                    <input class="form-control" :placeholder="form.monto" readonly
                                           v-model="form.monto_letras_neto">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for>Concepto</label>
                                    <input readonly @keyup.enter="$refs.centro_costo_ingreso.$refs.search.focus"
                                           class="form-control" ref="concepto" type="text" v-model="form.concepto">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.concepto"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>


                        </div>

                        <br>
                        <div class="alert alert-success">
                            <span>Datos de comprobante de pago</span>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Tipo Pago</label>
                                    <select disabled v-on:change="limpiarDatos" class="form-control"
                                            v-model.number="form.tipo_pago">
                                        <option value="1">Cheque</option>
                                        <option value="2">Transferencia</option>
                                    </select>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.tipo_pago" :key="message"
                                            v-text="message"></li>
                                    </ul>
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label v-if="form.tipo_pago===2">Número Transferencia</label>
                                    <label v-if="form.tipo_pago===1">Número Cheque</label>
                                    <input v-if="form.solicitud_cuenta_bancaria" class="form-control"
                                           v-model="form.numero_comprobante_pago">
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.numero_comprobante_pago" :key="message"
                                            v-text="message"></li>
                                    </ul>
                                </div>
                            </div>

                            <div v-if="form.tipo_pago===2" class="col-sm-4">
                                <div class="form-group">
                                    <label>Correo Electrónico Beneficiario</label>
                                    <input class="form-control" v-model.number="form.correo_electronico_beneficiario">
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.correo_electronico_beneficiario"
                                            :key="message"
                                            v-text="message"></li>
                                    </ul>
                                </div>
                            </div>


                        </div>


                        <br>
                        <div class="alert alert-success">
                            <span>Datos del comprobante contable</span>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.movimientos_cuentas"
                                            :key="message"
                                            v-text="message"
                                    ></li>
                                </ul>
                                <table class="table table-bordered table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th style="min-width:200px">Centro C/I</th>
                                        <th style="min-width:200px">Cuenta Contable</th>
                                        <th style="min-width:200px">Concepto</th>
                                        <th style="min-width:200px">Moneda Original</th>
                                        <th style="min-width:200px">Debe Moneda Original</th>
                                        <th style="min-width:200px">Haber Moneda Original</th>
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
                                                <td style="width: 21%">
                                                    {{item.concepto}}
                                                </td>

                                                <td style="width: 20%">
                                                    {{item.id_moneda===3?"Dólares":"Córdobas"}}
                                                </td>

                                                <td style="width: 16%">
                                                    {{item.debe_org}}
                                                </td>

                                                <td style="width: 16%">
                                                    {{item.haber_org}}
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
                                        <td style="width: 15%" colspan="2">
                                        </td>
                                        <td style="width: 30%" class="text-right" colspan="4">Totales C$</td>
                                        <td style="width: 20%" class="item-footer" colspan="1">
                                            <strong class="item-dark form-control">{{total_debe |
                                                formatMoney(2)}}</strong>
                                        </td>
                                        <td style="width: 20%" class="item-footer" colspan="1">
                                            <strong class="item-dark form-control">{{total_haber |
                                                formatMoney(2)}}</strong>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="content-box-footer text-right">
                            <router-link :to="{name: 'caja-solicitudes-de-pago'}">
                                <b-button type="submit" variant="secondary" class="mt-1 mr-1">
                                    Cancelar
                                </b-button>
                            </router-link>
                            <b-button class="mt-1" type="submit" ref="registrar" variant="primary"
                                      @click="emitirSolicitud"
                                      :disabled="btnAction !== 'Confirmar Revisión'">
                                {{btnAction}}
                            </b-button>
                        </div>
                    </div>

                    <template v-if="loading">
                        <BlockUI>
                            <b-spinner variant="info" label="loading..."/>
                        </BlockUI>
                    </template>


                </div>
            </div>
        </div>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import documento_contable from "../../../../api/contabilidad/documentos_contables";
    import tc from '../../../../api/contabilidad/tasas-cambio';
    import es from 'vuejs-datepicker/dist/locale/translations/es'
    import solicitud_pago from '../../../../api/Tesoreria/SolicitudesPagos';
    import loadingImage from '../../../../assets/images/loader/block50.gif';
    import numberasstring from "../../../../assets/custom-scripts/NumberAsString";
    import moment from "../../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import ToastificationContent from "../../../../@core/components/toastification/ToastificationContent";
    import {
        BAlert,
        BBadge,
        BButton,
        BCard,
        BCardFooter,
        BCol, BCollapse,
        BFormCheckbox,
        BFormDatepicker, BFormGroup, BFormSelect,
        BPaginationNav,
        BRow, BSpinner, BTab, BTable, BTabs, VBToggle, VBTooltip
    } from "bootstrap-vue";
    import vSelect from "vue-select";

    export default {
        components: {
            BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
            BBadge,
            BTab,
            BTabs,
            BSpinner,
            BTable,
            BFormSelect,
            vSelect,
            BCollapse,
            BAlert
        },
        directives: {
            'b-tooltip': VBTooltip,
            'b-toggle': VBToggle
        },
        data() {
            return {
                loading: true,
                url: loadingImage,
                cuentasBusqueda: {},
                cuentasBusquedaURL: "/contabilidad/cuentas-contables/buscarf",
                contador: 0,
                es: es,
                format: "d MMMM yyyy",
                monedas: [],
                centro_costo_deshabilitado: true,
                banco: [],
                cuentas_bancarias: [],
                tipos_documentos: [],
                solicitud_original: [],
                moneda_solicitada: '',
                moneda_aprobada: '',
                tasa_cambio: [],
                centros_costos_ingresos: [],
                bancos: [],
                monto_org: 0,
                monto_cord: 0,
                monto_ir_cord: 0,

                fechax: new Date(),
                form: {
                    num_documento: "",
                    documento_cuenta: [],
                    solicitud_cuenta_bancaria: [],
                    monto_letras: '',
                },
                detalleForm: {
                    centro_costo_ingreso: "",
                    debe: 0,
                    haber: 0,
                    concepto: "",
                    cuentaContable: {},
                },
                btnAction: "Confirmar Revisión",
                errorMessages: []
            };
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
        },
        methods: {
            limpiarDatos() {
                let self = this;
                if (self.form.tipo_pago === 1) {
                    self.form.numero_comprobante_pago = self.form.solicitud_cuenta_bancaria.numeracion_chequera;
                } else {
                    self.form.numero_comprobante_pago = '';
                }
            },
            limpiarMonto() {
                let self = this;
                self.form.monto_letras = '';
                self.form.monto_neto = 0;
            },
            calcular_monto() {
                let self = this;
                if (self.form.cuenta_bancaria) {
                    let tasax = 1;
                    let monto_maximo = self.solicitud_original.monto;
                    if (self.form.cuenta_bancaria.id_moneda !== self.solicitud_original.id_moneda) {
                        tasax = self.form.t_cambio;
                        if (self.form.cuenta_bancaria.id_moneda === 3) {
                            monto_maximo = Number((self.solicitud_original.monto / tasax).toFixed(2));
                        } else {
                            monto_maximo = Number((self.solicitud_original.monto * tasax).toFixed(2));
                        }
                    }
                    self.form.monto_neto = Number((Math.max(Math.min(Number(self.form.monto_neto), monto_maximo), 1)).toFixed(2))
                    if (self.form.cuenta_bancaria.moneda_cuenta_bancaria) {
                        self.form.monto_letras = self.obtener_monto_letras(self.form.monto_neto, self.form.cuenta_bancaria.moneda_cuenta_bancaria);
                    } else {
                        self.form.monto_letras = '';
                    }
                    return self.form.monto_neto;
                } else {
                    self.form.monto_letras = '';
                    return 0;
                }
            },
            moment: function () {
                return moment();
            },
            obtenerCuentasBancarias() {
                var self = this
                if (self.banco.cuentas_bancarias_banco)
                    self.cuentas_bancarias = self.banco.cuentas_bancarias_banco
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


            monto_debe_cordobas(itemX) {
                let tasa_cambio = 1
                if (itemX.monedaMov.id_moneda === 3) {
                    tasa_cambio = this.form.t_cambio;
                }

                itemX.debe = Number((itemX.debeORG * tasa_cambio).toFixed(4));
                if (!isNaN(itemX.debe)) {
                    return itemX.debe;
                } else return 0;
            },

            monto_haber_cordobas(itemX) {
                let tasa_cambio = 1
                if (itemX.monedaMov.id_moneda === 3) {
                    tasa_cambio = this.form.t_cambio;
                }

                itemX.haber = Number((itemX.haberORG * tasa_cambio).toFixed(4));
                if (!isNaN(itemX.haber)) {
                    return itemX.haber;
                } else return 0;
            },

            seleccionarCuentaContable(e) {
                const cuenta = e.target.value;
                var self = this;
                self.detalleForm.cuentaContable = cuenta;
                if ([4, 5, 6].indexOf(Number(cuenta.id_tipo_cuenta)) < 0) {
                    self.detalleForm.centro_costo_ingreso = null;
                    self.centro_costo_deshabilitado = true;
                    self.$refs.concepto_mov.focus();
                } else {
                    self.centro_costo_deshabilitado = false;
                    self.$refs.centro_costo_ingreso.$refs.search.focus();
                }

            },

            calcularTotales() {
                var self = this;
                // console.log(self.solicitud_original.id_moneda);
                if (self.form.cuenta_bancaria.id_moneda === 2 && self.solicitud_original.id_moneda === 2) {
                    self.monto_cord = Number(self.monto_org * self.tasa_cambio.tasa).toFixed(2);
                    self.monto_ir_cord = Number(self.form.monto_ir * self.tasa_cambio.tasa).toFixed(2);
                    self.form.monto_neto = Number(self.form.valor - self.form.monto_ir).toFixed(2);
                } else {
                    self.monto_cord = self.monto_org;
                    self.monto_ir_cord = self.form.monto_ir;
                    self.form.monto_neto = Number(self.monto_cord - self.monto_ir_cord).toFixed(2);
                }
                self.form.valor = Number(self.monto_cord);
                //self.monto_letras()
            },

            onDateSelect(date) {
                var self = this;
                self.form.fecha_emision = moment(date).format("YYYY-MM-DD");
                tc.obtenerTC({
                    fecha: self.form.fecha_emision
                }, data => {
                    if (data.tasa !== null) {
                        self.tasa_cambio = data;
                        // console.log(self.solicitud_original.id_moneda);
                        if (self.solicitud_original.id_moneda === 2) {
                            self.monto_cord = Number(self.monto_org * self.tasa_cambio.tasa).toFixed(2);
                            self.monto_ir_cord = Number(self.form.monto_ir * self.tasa_cambio.tasa).toFixed(2);
                            self.form.monto_neto = Number(self.monto_org - self.form.monto_ir).toFixed(2);
                        } else {
                            self.monto_cord = Number(self.monto_org);
                            self.monto_ir_cord = Number(self.form.monto_ir);
                            self.form.monto_neto = Number(self.monto_cord - self.monto_ir_cord).toFixed(2);
                        }
                        self.form.valor = Number(self.monto_cord);
                        //self.monto_letras()
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encuentra tasa de cambio para esta fecha.',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                }, err => {
                    if (err.fecha[0]) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: err.fecha[0],
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                })

            },
            obtenerTC() {
                var self = this;
                tc.obtenerTC({
                    fecha: self.form.fecha_emision
                }, data => {
                    if (data.tasa !== null) {
                        self.tasa_cambio = data;

                        if (self.solicitud_original.id_moneda === 2) {
                            //console.log(self.form.monto_ir);
                            /* console.log(self.tasa_cambio.tasa);*/
                            self.monto_cord = Number(self.monto_org * self.tasa_cambio.tasa).toFixed(2);
                            self.monto_ir_cord = Number(self.form.monto_ir * self.tasa_cambio.tasa).toFixed(2);
                            self.form.monto_neto = Number(self.monto_cord - self.monto_ir_cord).toFixed(2);

                        } else {
                            self.monto_cord = Number(self.monto_org);
                            self.monto_ir_cord = Number(self.form.monto_ir);
                            self.form.monto_neto = Number(self.monto_org - self.form.monto_ir).toFixed(2);
                        }
                        self.form.valor = Number(self.monto_cord);
                        //self.monto_letras()

                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encuentra tasa de cambio para esta fecha.',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                }, err => {
                    console.log(err);
                    if (err.fecha.length > 0) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: err.fecha[0],
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                })
            }
            ,
            obtenerSolicitud() {
                var self = this
                solicitud_pago.obtenerSolicitudPago({
                    id_solicitud_pago: this.$route.params.id_solicitud_pago
                }, data => {
                    if (data.solicitud.estado === 1) {
                        if (data.solicitud.documento_solicitud === null) {
                            //console.log(data);
                            self.solicitud_original = data.solicitud;
                            //self.form.moneda = data.moneda_solicitud;
                            self.form.concepto = data.solicitud.concepto;
                            self.detalleForm.concepto = data.solicitud.concepto;
                            self.form.valor = 0;
                            self.form.id_solicitud_pago = this.$route.params.id_solicitud_pago;
                            self.monto_org = self.solicitud_original.monto_solicitado;
                            self.moneda_solicitada = self.solicitud_original.moneda_solicitud.descripcion;
                            self.form.numero_comprobante_pago = '';
                            if (self.form.tipo_pago === 1) {
                                self.form.numero_comprobante_pago = self.form.solicitud_cuenta_bancaria.numeracion_chequera;
                            }

                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Esta solicitud ya tiene un documento contable registrado.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                        }
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Esta solicitud ya fue revisada anteriormente.',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                    //self.form = data
                }, err => {
                    console.log(err)
                })
            },

            eliminarLinea(item, index) {
                if (this.form.documento_solicitud.movimientos_cuentas.length >= 1) {
                    this.form.documento_solicitud.movimientos_cuentas.splice(index, 1);

                } else {
                    this.form.documento_solicitud.movimientos_cuentas = [];
                }
            },

            obtenerNumeroDocumento() {
                var self = this;
                if (self.form.tipoDocumento) {
                    self.form.num_documento = self.form.tipoDocumento.prefijo + '-' + self.form.codigo_documento;
                }
            },


            cambiarFoco() {
                var self = this;
                if (self.contador > 0) {
                    self.$refs.moneda.$refs.search.focus();
                }
                self.contador++;
            },

            revisarConceptoMov() {
                var self = this;
                if (self.detalleForm.concepto !== '') {
                    self.$refs.debe.focus();
                } else {
                    self.$refs.concepto_mov.focus();
                }
            },

            centroCostoEvento() {
                var self = this;
                if (self.contador > 0) {

                }

            },


            conceptoEvento() {
                var self = this;
                if (self.contador > 0) {
                    self.$refs.cuenta_contable.open();
                    //self.$refs.sucursal.$refs.search.focus();
                }
                //console.log(self.$refs.cuenta_contable);
            },

            agregarEvento() {
                var self = this;
                self.$refs.cuenta_contable.open();
            },

            emitirSolicitud() {
                var self = this;
                if ((self.total_debe > 0 && self.total_haber > 0) && (self.total_debe === self.total_haber)) {
                    //if(self.total_debe === self.form.valor){
                    self.btnAction = "Registrando, espere....";
                    solicitud_pago.emitir(
                        self.form,
                        data => {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Solicitud de pago emitida correctamente.',
                                        variant: 'success',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            this.$router.push({
                                name: "caja-solicitudes-de-pago"
                            });
                        },
                        err => {
                            self.errorMessages = err;
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Por favor revisar los datos faltantes.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            self.btnAction = "Confirmar Revisión";
                        }
                    );
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'La sumatoria de las columnas debe y haber tienen que ser iguales.',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }


            },
            obtenerCodigo() {
                var self = this;
                self.loading = true;
                documento_contable.obtenerCodigo({
                    id_tipo_doc: self.form.tipoDocumento.id_tipo_doc,
                    fecha_doc: self.form.fecha_emision
                }, data => {
                    //console.log(data);
                    self.form.codigo_documento = data.codigo.secuencia;
                    self.form.t_cambio = Number(data.t_cambio.tasa);
                    self.obtenerNumeroDocumento();
                    self.loading = false;
                }, err => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: err,
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    self.loading = false;
                })
            },
            obtenerSolicitudCompleta() {
                var self = this;
                solicitud_pago.obtenerSolicitudPago({
                        id_solicitud_pago: this.$route.params.id_solicitud_pago
                    }, data => {
                        // console.log(data);
                        if (data.solicitud.estado === 3) {
                            self.form = data.solicitud;
                            self.form.numero_comprobante_pago = '';
                            if (self.form.tipo_pago === 1) {
                                self.form.numero_comprobante_pago = self.form.solicitud_cuenta_bancaria.numeracion_chequera.toString();
                            }
                            self.loading = false;
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Esta solicitud no puede ser emitida.',
                                        variant: 'success',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            this.$router.push({
                                name: "caja-solicitudes-de-pago"
                            });
                        }
                    },
                    err => {
                        console.log(err);
                    })

            },
        },
        mounted() {
            //this.obtenerTiposDocumentosTodos() ;
            //this.obtenercentros_costosTodas();
            //this.obtenerMonedas();
            //this.obtenerTodosBancos();
            //this.obtenerSolicitud();
            //this.obtenerTC();
            this.obtenerSolicitudCompleta();
            //this.$refs.banco.$refs.search.focus();
        }
    };
</script>
