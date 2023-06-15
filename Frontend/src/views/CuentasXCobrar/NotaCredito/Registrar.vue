<template>
    <b-card>
        <b-alert show variant="success">
            <div class="alert-body">
                <span><strong>Datos de la nota de crédito</strong></span>
            </div>
        </b-alert>
        <b-row>
            <b-col cols="12" md="4">
                <div class="form-group">
                    <label> Cliente</label>
                    <div class="form-group">
                        <v-select
                                :filterable="false"
                                :options="clientes"
                                @input="seleccionarCliente"
                                @search="onSearch"
                                label="text"
                                style="width: 100%;"
                                v-model="form.factura_cliente"
                        >
                            <template slot="no-options">
                                Escriba para buscar un cliente
                            </template>
                        </v-select>
                    </div>
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.factura_cliente"
                                    v-text="message"
                            />
                        </ul>
                    </b-alert>

                </div>
            </b-col>

            <b-col cols="12" md="2">
                <div class="form-group">
                    <label for>T/C</label>
                    <input class="form-control" disabled type="text" v-model="form.t_cambio">
                </div>
            </b-col>

            <b-col cols="12" md="2">
                <div class="form-group">
                    <label for>Fecha Emitida</label>
                    <b-form-datepicker
                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                            :disabled="false"
                            class="mb-0"
                            local="es"
                            placeholder="f.emision"
                            selected-variant="primary"
                            v-model="form.fecha_emision"
                    />
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.fecha_emision"
                                    v-text="message"
                            />
                        </ul>
                    </b-alert>
                </div>
            </b-col>
            <b-col cols="12" md="4">
                <div class="form-group">
                    <label for>Concepto</label>
                    <input class="form-control" type="text" v-model="form.concepto">
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.concepto"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </b-col>
        </b-row>
        <div v-if="!form.nota_credito_cliente">
            <div class="alert alert-info">
                <span>Se requiere que seleccione un cliente para continuar.</span>
            </div>
            <hr>
        </div>

        <b-alert show variant="success">
            <div class="alert-body">
                <span><strong>Detalles de la nota de crédito</strong></span>
            </div>
        </b-alert>
        <b-row>
            <b-col cols="12" md="4">
                <div class="form-group">
                    <label for=""> Listado de facturas</label>
                    <v-select
                            :disabled="false"
                            :options="facturas"
                            label="descripcion"
                            v-model="detalleForm.nota_credito_factura"
                            placeholder="Selecciona una cuenta para pagar"
                    >
                        <template slot="no-options">
                            Selecciona una cuenta para pagar
                        </template>
                    </v-select>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.nota_credito_factura" v-text="message"></li>
                    </ul>
                </div>
            </b-col>

            <b-col cols="12" md="3">
                <div class="form-group">
                    <label for=""> Tipo de Nota</label>
                    <v-select
                            :disabled="false"
                            :options="tipos_notas"
                            label="descripcion"
                            v-model="detalleForm.nota_credito_tipo"
                            placeholder="Selecciona un tipo de nota"
                    >
                        <template slot="no-options">
                            Selecciona un tipo de nota
                        </template>
                    </v-select>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.nota_credito_tipo" v-text="message"></li>
                    </ul>
                </div>
            </b-col>

            <b-col cols="12" md="3">
                <div class="form-group">
                    <label for>Monto Córdobas</label>
                    <input @change="detalleForm.monto = detalleForm.nota_credito_factura? Math.max(Math.min(Number(!isNaN(detalleForm.monto)?detalleForm.monto.toFixed(2):0), Number(Number(detalleForm.nota_credito_factura.mt_total).toFixed(2))), 1):0"
                           class="form-control" ref="monto" type="number"
                           v-model.number="detalleForm.monto">
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.monto"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </b-col>

            <b-col cols="12" md="2" style="margin-top: 10px">
                <b-form-checkbox
                        class="mt-sm-2"
                        v-model="detalleForm.aplicaIVA"
                >
                    Aplica IVA
                </b-form-checkbox>
            </b-col>


            <b-col cols="12" md="3">
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <button @click="agregarDetalle" class="btn btn-info btn-agregar" ref="agregar">Agregar Factura
                    </button>
                </div>
            </b-col>
        </b-row>

        <b-row>
            <div class="col-sm-12">
                <ul class="error-messages">
                    <li
                            :key="message"
                            v-for="message in errorMessages.detalleFacturas"
                            v-text="message"
                    ></li>
                </ul>
                <table class="table table-bordered"  >
                    <thead>
                    <tr>
                        <th></th>
                        <th>No Factura</th>
                        <th>Tipo N/C</th>
                        <th>Descripción</th>
                        <th>Monto C$</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.detalleFacturas">
                        <tr>
                            <td style="width: 5%">
                                <button @click="eliminarLinea(item, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <td style="width: 10%">
                                <input class="form-control" disabled v-model="item.nota_credito_factura.no_documento">
                            </td>

                            <td style="width: 10%">
                                <input disabled class="form-control" v-model="item.nota_credito_tipo.descripcion">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleFacturas.${index}.descripcion`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>

                            <td style="width: 20%">
                                <input class="form-control" v-model="item.descripcion">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleFacturas.${index}.descripcion`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>

                            <td class="text-center" style="width: 12%">
                                <input disabled class="form-control" v-model.number="item.monto">
                            </td>

                        </tr>
                        <tr></tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-right"> <strong> SubTotal</strong></td>
                        <td  class="text-center">
                            <strong>C$ {{total_cargo|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-right"> <strong> IVA</strong></td>
                        <td  class="text-center">
                            <strong>C$ {{form.monto_total_iva|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-right"> <strong> SubTotal</strong></td>
                        <td  class="text-center">
                            <strong>C$ {{form.monto_total|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </b-row>

        <b-card-footer class="content-box-footer text-right mt-1">
            <b-row>
                <b-col class="text-lg-right" cols="12" md="12">
                    <router-link :to="{ name: 'notas-credito' }">
                        <b-button type="submit" variant="secondary" class="mt-1 mr-1">
                            Cancelar
                        </b-button>
                    </router-link>
                    <b-button class="mt-1" type="submit" variant="primary" @click="registrar" :disabled="btnAction !== 'Registrar'">
                        {{ btnAction }}
                    </b-button>
                </b-col>
            </b-row>
        </b-card-footer>
        <template v-if="loading">
            <BlockUI :message="msg" :url="url"></BlockUI>
        </template>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import notas_credito from "../../../api/CuentasXCobrar/notas_credito";
    import factura from "../../../api/CajaBanco/facturas";
    import cliente from '../../../api/Ventas/clientes'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";

    import es from 'vuejs-datepicker/dist/locale/translations/es'

    //import {SweetModal} from 'sweet-modal-vue'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import {
        BAlert,
        BButton,
        BCard,
        BCardFooter,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        BFormGroup,
        BFormSelect,
        BPaginationNav,
        BRow,
        BCol,
        VBTooltip,
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import axios from "axios";
    export default {
        /*components: {
            SweetModal
        },*/
        components: {
            BCard,
            BCardFooter,
            BPaginationNav,
            BFormCheckbox,
            BFormGroup,
            vSelect,
            BRow,
            BCol,
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
                loading: true,
                msg: 'Cargando el contenido espere un momento',
                url: '/public' + loadingImage,

                es: es,
                format: "dd-MM-yyyy",

                clientes: [],
                facturas: [],
                tipos_notas:[],

                form: {
                    no_documento: "",
                    fecha_emision: moment(new Date()).format("YYYY-MM-DD"),
                    nota_credito_cliente: {},
                    nota_credito_factura:'',
                    concepto: "",
                    t_cambio: 0,
                    monto_total_me: 0,
                    monto_total: 0,
                    detalleFacturas: [],
                    factura_cliente: [],
                    es_deudor: false
                },

                detalleForm: {
                    nota_credito_factura: '',
                    nota_credito_tipo: '',
                    monto: 0,
                    descripcion: '',
                    aplicaIVA:true,
                },

                btnAction: "Registrar",
                errorMessages: []
            }

        },
        computed: {
            total_cargo() {
                let self = this;
                self.form.monto_total = Number(this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.monto))
                }, 0).toFixed(2));

                self.form.monto_total_iva = Number(this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.monto_iva))
                }, 0).toFixed(2));

                self.form.subtotal = Number(this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.subtotal))
                }, 0).toFixed(2));

                return this.form.subtotal;
            },
        },
            methods: {
                eliminarLinea(item, index) {
                    if (this.form.detalleFacturas.length >= 1) {
                        this.form.detalleFacturas.splice(index, 1);

                    }else{
                        this.form.detalleFacturas=[];
                    }
                },

            agregarDetalle() {
                var self = this;
                if (self.detalleForm.nota_credito_factura && self.detalleForm.nota_credito_tipo) {
                    if (self.detalleForm.monto > 0) {
                        let validacion = 0;

                        if(validacion===0){
                            let i = 0;
                            let keyx = 0;

                            if (self.form.detalleFacturas) {
                                self.form.detalleFacturas.forEach((cuentax, key) => {
                                    if (self.detalleForm.nota_credito_factura.id_factura === cuentax.nota_credito_factura.id_factura) {
                                        i++;
                                        keyx = key;
                                    }
                                });
                            }
                            if (i === 0) {

                                let descripcionx = '';
                                if(self.detalleForm.monto === Number(self.detalleForm.nota_credito_factura.mt_total)){
                                    descripcionx = 'Se acredita al cliente el monto total de la factura No.'+self.detalleForm.nota_credito_factura.no_documento+', por concepto de: '+self.detalleForm.nota_credito_tipo.descripcion;
                                }else{
                                    descripcionx = 'Se acredita al cliente el monto parcial de la factura No.'+self.detalleForm.nota_credito_factura.no_documento+', por concepto de: '+self.detalleForm.nota_credito_tipo.descripcion;
                                }

                                let monto_iva = 0,subtotal = 0;
                                if(self.detalleForm.aplicaIVA){
                                    monto_iva = Number((self.detalleForm.monto-Number(self.detalleForm.monto)/1.15).toFixed(2));
                                    subtotal = Number(self.detalleForm.monto - monto_iva);
                                }else{
                                    monto_iva = 0;
                                    subtotal = Number(self.detalleForm.monto);
                                }

                                self.form.detalleFacturas.push({
                                    nota_credito_factura: self.detalleForm.nota_credito_factura,
                                    nota_credito_tipo: self.detalleForm.nota_credito_tipo,
                                    monto: Number(self.detalleForm.monto),
                                    subtotal:subtotal,
                                    monto_iva: monto_iva,
                                    descripcion:descripcionx,
                                });
                                alertify.success("Factura agregada!", 5);
                            } else {
                                let nuevo_total = self.form.detalleFacturas[keyx].monto + self.detalleForm.monto;
                                if (nuevo_total <= Number(self.form.detalleFacturas[keyx].nota_credito_factura.mt_total)) {
                                    self.form.detalleFacturas[keyx].monto = nuevo_total;
                                    //self.form.detalleFacturas[keyx].monto_me = Number((self.detalleForm.monto/self.form.t_cambio).toFixed(2));
                                    alertify.success("Factura agregada!", 5);

                                    if(nuevo_total === Number(self.form.detalleFacturas[keyx].nota_credito_factura.mt_total)){
                                        self.form.detalleFacturas[keyx].descripcion = 'Se acredita al cliente el monto total de la factura No.'+self.form.detalleFacturas[keyx].nota_credito_factura.no_documento+', por concepto de: '+self.form.detalleFacturas[keyx].nota_credito_tipo.descripcion;
                                    }else{
                                        self.form.detalleFacturas[keyx].descripcion = 'Se acredita al cliente el monto parcial de la factura No.'+self.form.detalleFacturas[keyx].nota_credito_factura.no_documento+', por concepto de: '+self.form.detalleFacturas[keyx].nota_credito_tipo.descripcion;
                                    }

                                } else {
                                    self.form.detalleFacturas[keyx].monto = Number(self.form.detalleFacturas[keyx].nota_credito_factura.mt_total);

                                    alertify.warning("Se ha agregado el monto maximo disponible para cancelar esta cuenta", 5);
                                }
                            }

                            self.detalleForm.nota_credito_factura = null;
                            self.detalleForm.nota_credito_tipo = null;
                            //self.detalleForm.monto_me = 0;
                            self.detalleForm.monto = 0;
                            self.detalleForm.descripcion = '';
                            //this.$refs.cuenta.$el.focus();

                        }
                    } else {
                        alertify.warning("El monto del abono debe ser mayor que cero", 5);
                    }
                } else {
                    alertify.warning("Debe seleccionar una cuenta para realizar el abono", 5);
                }
            },

            onSearch(search, loading) {
                if (search.length) {
                    loading(true)
                    this.search(loading, search, this)
                }
            },

            search: _.debounce((loading, search, vm) => { // /ventas/clientes/buscar
                const self = this
                axios.get(`/cuentas-por-cobrar/clientes/buscar?q=${escape(search)}&es_deudor=${vm.form.es_deudor}`).then(res => {
                    vm.options = res.data.results
                    vm.clientes = res.data.results
                    loading(false)
                })
            }, 100),
                
            seleccionarCliente(e) {
                const clienteP = e.target.value;
                var self = this;
                self.form.nota_credito_cliente = clienteP;
                self.form.nombre_persona = self.form.nota_credito_cliente.nombre_comercial;
                self.loading = true;
                self.form.nota_credito_factura = '';

                factura.obtenerFacturasCliente({
                    id_cliente: self.form.nota_credito_cliente.id_cliente
                }, data => {
                    if (data !== null) {
                        self.facturas = data;
                        self.form.nota_credito_factura = '';
                    } else {
                        alertify.warning("No se encuentran facturas para este cliente.", 5);
                        self.form.nota_credito_factura = '';
                    }
                    self.loading = false;
                }, err => {

                    self.loading = false;
                })

            },
            nueva() {
                var self = this;
                notas_credito.nueva({}, data => {
                        self.facturas = [];
                        self.tipos_notas = data.tipos_notas;
                        self.form.t_cambio = Number(data.t_cambio.tasa);
                        self.loading = false;
                    },
                    err => {
                       // console.log(err);
                    })
            },


            registrar() {
                var self = this;
                self.btnAction = "Registrando, espere....";
                if (self.form.monto_total <= 0) {
                    alertify.warning("El monto de la nota debe ser mayor que cero", 5);
                    self.btnAction = "Registrar";
                } else {
                    self.$swal.fire({
                        title: 'Esta seguro de procesar la nota de crédito?',
                        text: "Revise los Detalles de la nota de crédito: ",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, registrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            notas_credito.registrar(
                                self.form,
                                data => {
                                    this.$swal.fire(
                                        'Nota de Crédito Registrada!',
                                        'La nota de crédito fue registrada correctamente',
                                        'success'
                                    );
                                    this.$router.push({
                                        name: "notas-credito"
                                    });
                                },
                                err => {
                                    self.errorMessages = err;
                                    alertify.warning("Por favor revise los datos faltantes", 5);
                                    self.btnAction = "Registrar";
                                }
                            );
                        } else {
                            self.btnAction = "Registrar";
                        }
                    })
                }
            },

        },
        mounted() {
            this.nueva();
        }
    };
</script>
<style>
    .btn-agregar {
        margin-top: 33px;
    }
    .check-label2 {
        margin-top: 40px;
    }
</style>





