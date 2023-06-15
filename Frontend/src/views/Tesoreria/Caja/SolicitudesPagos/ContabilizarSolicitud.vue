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
                                            <p><strong>{{solicitud_original.beneficiario}}</strong></p>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Monto
                                                {{solicitud_original.moneda_solicitud?solicitud_original.moneda_solicitud.codigo:"N/A"}}: </p>
                                            <p><strong>{{solicitud_original.monto|formatMoney(2)}}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Monto en letras: </p>
                                            <p><strong>{{solicitud_original?obtener_monto_letras(solicitud_original.monto,solicitud_original.moneda_solicitud):"N/A"}}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Concepto: </p>
                                            <p><strong>{{solicitud_original.concepto}}</strong></p>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group d-flex">
                                            <p class="mr-2">Fecha: </p>
                                            <p><strong>{{moment(solicitud_original.fecha_solicitud).format("YYYY-MM-DD")}}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </b-collapse>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Cuenta bancaria</label>
                                    <v-select
                                            :options="cuentas_bancarias"
                                            label="numero_cuenta"
                                            v-model="form.cuenta_bancaria"
                                            :disabled="true"
                                            v-on:change="limpiarMonto"
                                    ></v-select>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.cuenta_bancaria"
                                            v-text="message"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Tipo Pago</label>
                                    <select class="form-control" v-model.number="form.tipo_pago">
                                        <option value="1">Cheque</option>
                                        <option value="2">Transferencia</option>
                                    </select>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.tipo_beneficiario" :key="message"
                                            v-text="message"></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for>Fecha Emision</label>
                                    <b-form-datepicker :format="format" :language="es" @selected="onDateSelect"
                                                       @keyup.enter="$refs.moneda.focus"
                                                       v-model="fechax" ref="fecha"></b-form-datepicker>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.fecha_emision"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Monto Solicitado ({{moneda_solicitada}})</label>
                                    <input class="form-control" readonly ref="valor"
                                           v-model.number="solicitud_original.monto">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Monto Neto (<strong>{{form.cuenta_bancaria.moneda_cuenta_bancaria?form.cuenta_bancaria.moneda_cuenta_bancaria.descripcion:"N/A"}}</strong>)</label>
                                    <input disabled class="form-control" placeholder="Monto del cheque" type="number"
                                           v-model.number="form.monto_neto">
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Valor en letras</label>
                                    <input class="form-control" :placeholder="form.monto" readonly
                                           v-model="form.monto_letras">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for>Concepto</label>
                                    <input @keyup.enter="$refs.centro_costo_ingreso.$refs.search.focus"
                                           class="form-control"
                                           ref="concepto" type="text" v-model="form.concepto">
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

                        <div v-if="!form.tipoDocumento">
                            <div class="alert alert-info">
                                <span>Se requiere que seleccione un tipo de documento para continuar.</span>
                            </div>
                            <hr>
                        </div>

                        <br>

                        <div class="row">


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for>Cuenta Contable</label>
                                    <v-select
                                            :options="cuentasContables"
                                            label="nombre_cuenta_completo"
                                            v-model="detalleForm.cuentaContable"
                                            @input="seleccionarCuentaContable"
                                            @search="onSearch"
                                    ></v-select>
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages.cuentaContablex"
                                                :key="message"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for>Centro de Costo</label>
                                    <v-select
                                            :disabled="centro_costo_deshabilitado"
                                            :options="centros_costos_ingresos"
                                            label="descripcion"
                                            ref="centro_costo_ingreso"
                                            v-model="detalleForm.centro_costo_ingreso"
                                            v-on:change="centroCostoEvento()"
                                    ></v-select>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.centro_costo_ingresox"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>


                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for>Concepto</label>
                                    <input @keyup.enter="revisarConceptoMov" class="form-control" ref="concepto_mov"
                                           type="text" v-model="detalleForm.concepto">
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages.conceptox"
                                                :key="message"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for>Moneda</label>
                                    <v-select
                                            :options="monedas"
                                            label="descripcion"
                                            ref="moneda_x"
                                            v-model="detalleForm.moneda_x"
                                    ></v-select>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.moneda_x"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>


                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for>Debe</label>
                                    <input @focus="$event.target.select()" @keyup.enter="$refs.haber.focus"
                                           min="0" class="form-control" ref="debe" type="number"
                                           v-model.number="detalleForm.debe">
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages.debex"
                                                :key="message"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>


                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for>Haber</label>
                                    <input @focus="$event.target.select()" @keyup.enter="$refs.agregar.focus"
                                           class="form-control" ref="haber" type="number" min="0"
                                           v-model.number="detalleForm.haber">
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages.haberx"
                                                :key="message"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for>&nbsp;</label>
                                    <button @click="agregarDetalle" @keyup.enter="agregarEvento" class="btn btn-info"
                                            ref="agregar">Agregar Línea
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.detalleMovimientos"
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
                                    <template v-for="(item, index) in form.detalleMovimientos">
                                        <tr>
                                            <td style="width: 20%">
                                                <v-select
                                                        v-model="item.centro_costo_ingreso"
                                                        label="descripcion"
                                                        :options="centros_costos_ingresos"
                                                        :disabled="item.centro_costo_ingreso_deshabilitada"
                                                ></v-select>
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages[`detalleMovimientos.${index}.centro_costo_ingreso.id_centro`]"
                                                            :key="message"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </td>


                                            <td style="width: 25%">
                                                {{item.cuentaContable.nombre_cuenta_completo}}
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages[`detalleMovimientos.${index}.cuentaContable.id_cuenta_contable`]"
                                                            :key="message"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </td>
                                            <td style="width: 21%">
                                                <input type="text" class="form-control" v-model="item.concepto">
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages[`detalleMovimientos.${index}.concepto`]"
                                                            :key="message"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </td>

                                            <td style="width: 20%">
                                                <v-select
                                                        v-model="item.monedaMov"
                                                        label="descripcion"
                                                        :options="monedas"
                                                        :disabled="true"
                                                ></v-select>
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages[`detalleMovimientos.${index}.monedaMov.id_moneda`]"
                                                            :key="message"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </td>

                                            <td style="width: 16%">
                                                <input :disabled="true" min="0" type="number" class="form-control"
                                                       v-model.number="item.debeORG"
                                                       @change="item.partida_valida = ((item.debeORG === 0 &&  item.haberORG > 0)||(item.haberORG === 0 &&  item.debeORG > 0))">
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages[`detalleMovimientos.${index}.debe`]"
                                                            :key="message"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </td>

                                            <td style="width: 16%">
                                                <input :disabled="true" min="0" type="number" class="form-control"
                                                       v-model.number="item.haberORG"
                                                       @change="item.partida_valida = ((item.haberORG === 0 &&  item.debeORG > 0)||(item.debeORG === 0 &&  item.haberORG > 0))">
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages[`detalleMovimientos.${index}.haber`]"
                                                            :key="message"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </td>

                                            <td>
                                                <strong>{{monto_debe_cordobas(item)}}</strong>
                                            </td>

                                            <td>
                                                <strong>{{monto_haber_cordobas(item)}}</strong>
                                            </td>

                                            <td style="width: 2%">
                                                <button :disabled="!item.editable" @click="eliminarLinea(item, index)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td style="width: 15%" colspan="2">
                                        </td>
                                        <td style="width: 30%" class="text-right" colspan="4">Totales C$</td>
                                        <td style="width: 20%" class="item-footer" colspan="1">
                                            <strong class="item-dark">{{total_debe | formatMoney(2)}}</strong>
                                        </td>
                                        <td style="width: 20%" class="item-footer" colspan="1">
                                            <strong class="item-dark">{{total_haber | formatMoney(2)}}</strong>
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
                            <b-button class="mt-1" type="submit" ref="registrar" variant="primary" @click="revisarSolicitud"
                                      :disabled="btnAction !== 'Registrar' ? true : false">
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
    import solicitud_pago from '../../../../api/Tesoreria/SolicitudesPagos';
    import es from 'vuejs-datepicker/dist/locale/translations/es';
    import numberasstring from '../../../../assets/custom-scripts/NumberAsString';
    // import cuenta from "../../../../api/contabilidad/cuentas_por_pagar";
    import roundNumber from '../../../../assets/custom-scripts/Round';
    import moment from "../../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import loadingImage from '../../../../assets/images/loader/block50.gif';
    import vSelect from 'vue-select';
    import {
        BFormDatepicker,
        BRow,
        BCol,
        BCard,
        BCardFooter,
        BPaginationNav,
        BButton,
        VBTooltip,
        BFormCheckbox,
        BFormGroup,
        BBadge,
        BTab,
        BTabs,
        BSpinner,
        BTable,
        BFormSelect,
        BCollapse,
        VBToggle,
        BAlert
    } from 'bootstrap-vue';
    import ToastificationContent from "../../../../@core/components/toastification/ToastificationContent";
    import axios from "axios";

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
                cuentasContables: [],
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
                    tipo_pago: 1,
                    codigo_documento: 1,
                    monto_neto: 0,
                    monto_ir: 0,
                    num_documento: "",
                    fecha_emision: moment(new Date()).format("YYYY-MM-DD"),
                    concepto: "",
                    valor: 0,
                    detalleMovimientos: [],
                    cuenta_bancaria: [],
                    monto_letras: '',
                },
                detalleForm: {
                    centro_costo_ingreso: "",
                    debe: 0,
                    haber: 0,
                    concepto: "",
                    cuentaContable: {},
                },
                btnAction: "Registrar",
                errorMessages: []
            };
        },
        computed: {
            total_debe() {
                return this.form.detalleMovimientos.reduce((carry, item) => {
                    return (carry + (Number(item.debe)))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                }, 0)
            },
            total_haber() {
                return this.form.detalleMovimientos.reduce((carry, item) => {
                    return (carry + (Number(item.haber)))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                }, 0)
            },
        },
        methods: {
            onSearch(search, loading) {
                if (search.length) {
                    loading(true);
                    this.search(loading, search, this)
                }
            },
            search: _.debounce((loading, search, vm) => {
                const self = this;
                axios.post(`contabilidad/cuentas-contables/buscarf?q=${escape(search)}`).then(res => {
                    vm.cuentasContables = res.data.results;
                    loading(false)
                })
            }, 10),
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
                        if (self.form.cuenta_bancaria.id_moneda === 2) {
                            monto_maximo = Number((self.solicitud_original.monto / tasax).toFixed(2));
                        } else {
                            monto_maximo = Number((self.solicitud_original.monto * tasax).toFixed(2));
                        }
                    }
                    self.form.monto_neto = Number((Math.max(Math.min(Number(self.form.monto_neto), monto_maximo), 1)).toFixed(2));

                    this.form.detalleMovimientos[0].debe = 0;
                    this.form.detalleMovimientos[0].haber = 0;
                    this.form.detalleMovimientos[0].debeORG = 0;
                    this.form.detalleMovimientos[0].haberORG = 0;

                    (this.total_debe - this.total_haber) > 0 ? self.form.monto_neto = (this.total_debe - this.total_haber) : self.form.monto_neto = 0;

                    if (self.form.monto_neto > Number(monto_maximo)) {
                        self.form.monto_neto = Number(monto_maximo);
                    }

                    this.form.detalleMovimientos[0].debe = 0;
                    this.form.detalleMovimientos[0].haber = Number((self.form.monto_neto * tasax).toFixed(2));
                    this.form.detalleMovimientos[0].debeORG = 0;
                    this.form.detalleMovimientos[0].haberORG = Number((self.form.monto_neto).toFixed(2));

                    if (self.form.cuenta_bancaria.moneda_cuenta_bancaria) {
                        self.form.monto_letras = self.obtener_monto_letras(self.form.monto_neto, self.form.cuenta_bancaria.moneda_cuenta_bancaria).trim();
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
                let tasa_cambio = 1;
                if (itemX.monedaMov.id_moneda === 2) {
                    tasa_cambio = this.form.t_cambio;
                }

                itemX.debe = Number((itemX.debeORG * tasa_cambio).toFixed(2));
                if (!isNaN(itemX.debe)) {
                    return itemX.debe;
                } else return 0;
            },

            monto_haber_cordobas(itemX) {
                let tasa_cambio = 1
                if (itemX.monedaMov.id_moneda === 2) {
                    tasa_cambio = this.form.t_cambio;
                }

                itemX.haber = Number((itemX.haberORG * tasa_cambio).toFixed(2));
                if (!isNaN(itemX.haber)) {
                    return itemX.haber;
                } else return 0;
            },

            seleccionarCuentaContable(e) {
                // const cuenta = e.target.value;
                var self = this;
                // self.detalleForm.cuentaContable = cuenta;
                if ([4, 5, 6].indexOf(Number(self.detalleForm.cuentaContable.id_tipo_cuenta)) < 0) {
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
                            self.monto_org = self.solicitud_original.monto;

                            self.moneda_solicitada = self.solicitud_original.moneda_solicitud.descripcion;
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
                            this.$router.push({
                                name: "caja-solicitudes-de-pago"
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
                        this.$router.push({
                            name: "caja-solicitudes-de-pago"
                        });
                    }
                    //self.form = data
                }, err => {
                })
            },

            validarCuentaContableBanco() {
                var self = this;
                let valido = false;
                self.form.detalleMovimientos.forEach((cuenta, fila) => {
                    //console.log('cuenta.cuentaContable.id_cuenta_contable ');
                    //console.log(cuenta.cuentaContable.id_cuenta_contable );
                    //console.log('self.form.cuenta_bancaria.id_cuenta_contable');
                    //console.log(self.form.cuenta_bancaria.id_cuenta_contable);
                    if (cuenta.cuentaContable.id_cuenta_contable === self.form.cuenta_bancaria.id_cuenta_contable) {
                        valido = true;
                    }
                });
                return valido;
            },

            agregarDetalle() {

                //  if(this.detalleForm.sucursal){
                //  this.errorMessages.sucursalx = ['correcto']
                if (this.detalleForm.cuentaContable && this.detalleForm.moneda_x) {
                    if (Number(this.detalleForm.debe.toFixed(2)) > 0 || Number(this.detalleForm.haber.toFixed(2)) > 0) {
                        if (Number(this.detalleForm.debe.toFixed(2)) > 0 && Number(this.detalleForm.haber.toFixed(2)) > 0) {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'No se puede agregar monto en ambos campos.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                        } else {
                            let tasa_cambio = 1;
                            if (this.detalleForm.moneda_x.id_moneda === 2) {
                                tasa_cambio = this.form.t_cambio;
                            } else {
                                tasa_cambio = 1;
                            }
                            let tipox = 0;//1- tipo debe 2- tipo haber
                            if (this.detalleForm.debe > this.detalleForm.haber) {
                                tipox = 1;
                            } else {
                                tipox = 2;
                            }

                            this.form.detalleMovimientos.push({
                                cuentaContable: this.detalleForm.cuentaContable,
                                centro_costo_ingreso: this.detalleForm.centro_costo_ingreso,
                                centro_costo_ingreso_deshabilitada: this.centro_costo_deshabilitado,
                                monedaMov: this.detalleForm.moneda_x,
                                debe: Number((this.detalleForm.debe * tasa_cambio).toFixed(2)),
                                haber: Number((this.detalleForm.haber * tasa_cambio).toFixed(2)),
                                debeORG: Number(this.detalleForm.debe.toFixed(2)),
                                haberORG: Number(this.detalleForm.haber.toFixed(2)),
                                concepto: this.detalleForm.concepto,
                                tipo: tipox,
                                editable: true
                            });

                            //this.total_debe - this.total_haber

                            this.detalleForm.cuentaContable = {};
                            // this.detalleForm.sucursal=''
                            this.detalleForm.debe = 0;
                            this.detalleForm.haber = 0;
                            this.detalleForm.debeORG = 0;
                            this.detalleForm.haberORG = 0;
                            //this.detalleForm.concepto = '';
                            this.detalleForm.centro_costo_ingreso = null;
                            this.centro_costo_deshabilitado = true;
                            //this.total_debe > this.total_haber ? this.form.valor = this.total_debe : this.form.valor = this.total_haber;
                            this.calcular_monto();

                        }
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'El monto debe ser mayor a cero.',
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
                                text: 'Verifique que tiene una cuenta contable y una moneda seleccionada.',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },

            eliminarLinea(item, index) {
                if (this.form.detalleMovimientos.length >= 1) {
                    this.form.detalleMovimientos.splice(index, 1);

                } else {
                    this.form.detalleMovimientos = [];
                }
                this.calcular_monto();
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


            revisarSolicitud() {
                var self = this;

                if ((self.total_debe > 0 && self.total_haber > 0) && (self.total_debe === self.total_haber)) {
                    //if(self.total_debe === self.form.valor){
                    //  if(self.validarCuentaContableBanco()){
                    self.btnAction = "Registrando, espere....";

                    self.$swal.fire({
                        title: 'Esta seguro de confirmar la contabilización de la solicitud de pago?',
                        text: "Revise los detalles de la solicitud ",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, registrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            solicitud_pago.contabilizar(
                                self.form,
                                data => {
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'CheckIcon',
                                                text: 'Documento registrado correctamente.',
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
                                    self.loading = false;
                                    self.errorMessages = err;
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Por favor revisar los datos faltantes',
                                                variant: 'warning',

                                            }
                                        },
                                        {
                                            position: 'bottom-right'
                                        });
                                    self.btnAction = "Registrar";
                                }
                            );
                        } else {
                            self.btnAction = "Registrar";
                        }
                    })

                    /*}else{
                        self.loading = false;
                        alertify.warning("La cuenta contable de la cuenta bancaria seleccionada no esta presente en el detalle",5);
                    }*/

                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'La sumatoria de las columnas debe y haber tienen que ser iguales',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    self.loading = false;
                }
            },

            nuevaContabilizacion() {
                var self = this;
                solicitud_pago.nuevaContabilizacion({
                        id_solicitud_pago: this.$route.params.id_solicitud_pago
                    }, data => {
                        if (data.solicitud.estado === 2) {
                            self.cuentas_bancarias = data.cuentas_bancarias;
                            self.form.cuenta_bancaria = data.cuenta_bancaria_seleccionada;
                            self.centros_costos_ingresos = data.centro_costos_ingresos;
                            self.solicitud_original = data.solicitud;
                            self.tipos_documentos = data.tipos_documentos;
                            self.form.tipoDocumento = self.tipos_documentos[0];
                            self.monedas = data.monedas;
                            self.form.moneda = self.monedas[0];
                            self.form.t_cambio = Number(data.t_cambio.tasa);

                            //self.detalleForm.moneda_x = self.monedas[0];

                            self.form.concepto = data.solicitud.concepto;
                            self.detalleForm.concepto = data.solicitud.concepto;
                            self.form.valor = 0;
                            self.form.id_solicitud_pago = this.$route.params.id_solicitud_pago;
                            self.monto_org = self.solicitud_original.monto;
                            self.moneda_solicitada = self.solicitud_original.moneda_solicitud.descripcion;

                            self.form.detalleMovimientos.push({
                                cuentaContable: this.form.cuenta_bancaria.cuenta_contable_cuenta_bancaria,
                                centro_costo_ingreso: null,
                                centro_costo_ingreso_deshabilitada: true,
                                monedaMov: this.form.cuenta_bancaria.moneda_cuenta_bancaria,
                                debe: 0,
                                haber: 0,
                                debeORG: 0,
                                haberORG: 0,
                                concepto: self.form.concepto,
                                tipo: 2,
                                editable: false
                            });


                            self.loading = false;
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Esta solicitud ya fue modificada previamente',
                                        variant: 'warning',

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
            this.nuevaContabilizacion();
            //this.$refs.banco.$refs.search.focus();
        }
    };
</script>
