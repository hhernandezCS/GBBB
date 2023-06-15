<template>
    <div class="main">
        <div class="row">
            <div class="col-md-12">
                <div class="content-box">
                    <div class="content-box-header">
                        <div class="box-title">Notas de Crédito</div>
                        <div class="box-description">Formulario para registrar notas de Crédito</div>
                    </div>

                    <div class="alert alert-success">
                        <span><strong>Datos de la nota</strong></span>
                    </div>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for=""> Proveedor</label>
                                <div class="form-group">
                                    <typeahead :initial="form.nota_credito_proveedor" :params="{es_deudor: false}" :trim="80" :url="proveedoresBusquedaURL"
                                               @input="seleccionarProveedor" style="width: 100%;"></typeahead>
                                </div>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.nota_credito_proveedor"
                                        v-text="message"></li>
                                </ul>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for>T/C</label>
                                <input class="form-control" disabled type="text" v-model="form.t_cambio">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for>Fecha</label>
                                <datepicker  :format="format" :language="es"
                                            :value="new Date()"></datepicker>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.fecha_emision"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for=""> Moneda</label>
                                <v-select
                                        :options="monedas"
                                        label="descripcion"
                                        v-model="form.nota_credito_moneda"
                                        v-on:change="limpiar"
                                ></v-select>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.nota_credito_moneda"
                                        v-text="message"></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-3">
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
                        </div>

                    </div>
                    <div v-if="!form.nota_credito_proveedor">
                        <div class="alert alert-info">
                            <span>Se requiere que seleccione un proveedor para continuar.</span>
                        </div>
                        <hr>
                    </div>


                    <div class="alert alert-success">
                        <span><strong>Detalles de la nota</strong></span>
                    </div>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for=""> Listado de Facturas de Compra</label>
                                <div class="form-group">
                                    <multiselect :allow-empty="false" :options="compras"
                                                 :searchable="true"
                                                 :show-labels="false"
                                                 deselect-label="No se puede eliminar este valor"
                                                 label="text"
                                                 placeholder="Selecciona una cuenta para pagar"
                                                 ref="factura"
                                                 track-by="identificador"
                                                 v-model="detalleForm.nota_credito_factura"
                                    ></multiselect>
                                </div>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.nota_credito_factura" v-text="message"></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for=""> Tipo de Nota</label>
                                <div class="form-group">
                                    <multiselect :allow-empty="false" :options="tipos_notas"
                                                 :searchable="true"
                                                 :show-labels="false"
                                                 deselect-label="No se puede eliminar este valor"
                                                 label="descripcion"
                                                 placeholder="Selecciona un tipo de nota"
                                                 ref="tipo-nota"
                                                 track-by="id_tipo_nota_credito"
                                                 v-model="detalleForm.nota_credito_tipo"
                                    ></multiselect>
                                </div>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.nota_credito_factura" v-text="message"></li>
                                </ul>
                            </div>
                        </div>

                    <div class="col-sm-3">
                        <div class="form-group">

                            <label > Monto (<strong>{{form.nota_credito_moneda?form.nota_credito_moneda.descripcion:"N/A"}}</strong>)</label>
                            <input :disabled="!form.nota_credito_moneda" class="form-control" placeholder="Monto" @change="calcular_monto"   v-model.number="detalleForm.monto">

                        </div>
                    </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="check-label2"><input type="checkbox"
                                                                  v-model="detalleForm.aplicaIVA">Aplica IVA</label>
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>&nbsp;</label>
                                <button @click="agregarDetalle" class="btn btn-info btn-agregar" ref="agregar">Agregar Factura
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                                    <th>Monto {{form.nota_credito_moneda?form.nota_credito_moneda.descripcion:"N/A"}}</th>
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
                                            <input class="form-control" disabled v-model="item.nota_credito_factura.numero_factura">
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
                                        <strong><strong>{{form.nota_credito_moneda?form.nota_credito_moneda.codigo:"N/A"}}</strong> {{total_cargo|formatMoney(2)}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right"> <strong> IVA</strong></td>
                                    <td  class="text-center">
                                        <strong>{{form.nota_credito_moneda?form.nota_credito_moneda.codigo:"N/A"}} {{form.monto_total_iva|formatMoney(2)}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right"> <strong> SubTotal </strong></td>
                                    <td  class="text-center">
                                        <strong>{{form.nota_credito_moneda?form.nota_credito_moneda.codigo:"N/A"}} {{form.monto_total|formatMoney(2)}}</strong>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <br>

                    <div class="content-box-footer text-right">
                        <router-link :to="{ name: 'notas-credito-cp' }" tag="button">
                            <button class="btn btn-default" type="button">Cancelar</button>
                        </router-link>
                        <button
                                :disabled="btnAction !== 'Registrar'"
                                @click="registrar"
                                class="btn btn-primary"
                                type="button"
                        >{{ btnAction }}
                        </button>
                    </div>


                    <template v-if="loading">
                        <BlockUI :message="msg" :url="url"></BlockUI>
                    </template>


                </div>
            </div>
        </div>


    </div>


</template>


<script type="text/ecmascript-6">
    import notas_credito from "../../api/notas_credito_cp";

    import es from 'vuejs-datepicker/dist/locale/translations/es'

    import loadingImage from '../../assets/images/block50.gif'

    export default {
        /*components: {
            SweetModal
        },*/
        data() {
            return {
                loading: true,
                msg: 'Cargando el contenido espere un momento',
                url: '/public' + loadingImage,

                es: es,
                format: "dd-MM-yyyy",

                proveedoresBusquedaURL: "/proveedores/buscar",

                tipos_notas:[],
                monedas:[],
                compras: [],
                compras_baterias: [],
                compras_servicios: [],


                form: {
                    numero_factura: "",
                    fecha_emision: moment(new Date()).format("YYYY-MM-DD"),
                    nota_credito_proveedor: {},
                    nota_credito_factura:'',
                    nota_credito_moneda:'',
                    concepto: "",
                    t_cambio: 0,
                    monto_total_me: 0,
                    monto_total: 0,
                    detalleFacturas: [],

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
                limpiar(){
                    let self = this;
                    self.detalleForm.monto = 0;
                    self.form.detalleFacturas=[];
                },

                calcular_monto(){
                    console.log('calcula rmonto')
                    let self = this;
                    if(self.form.nota_credito_moneda && self.detalleForm.nota_credito_factura){
                        let tasax = 1;
                        let monto_maximo = Number(self.detalleForm.nota_credito_factura.total);
                        if(self.form.nota_credito_moneda.id_moneda !== self.detalleForm.nota_credito_factura.id_moneda){
                            tasax=self.form.t_cambio;
                            if(self.form.nota_credito_moneda.id_moneda === 3){
                                monto_maximo = Number((self.detalleForm.nota_credito_factura.total/tasax).toFixed(2));
                            }else{
                                monto_maximo = Number((self.detalleForm.nota_credito_factura.total*tasax).toFixed(2));
                            }
                        }
                        self.detalleForm.monto = Number((Math.max(Math.min(Number(self.detalleForm.monto), monto_maximo), 1)).toFixed(2))

                        return self.detalleForm.monto;
                    }else{
                        self.detalleForm.monto = 0;
                        return self.detalleForm.monto ;
                    }
                },


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
                                    if (
                                        (self.detalleForm.nota_credito_factura.identificador === cuentax.nota_credito_factura.identificador)
                                       && (self.detalleForm.nota_credito_factura.tipo === cuentax.nota_credito_factura.tipo)
                                        ) {
                                        i++;
                                        keyx = key;
                                    }
                                });
                            }
                            if (i === 0) {

                                let descripcionx = '';
                                if(self.detalleForm.monto === Number(self.detalleForm.nota_credito_factura.total)){
                                    descripcionx = 'Se acredita al proveedor el monto total de la factura No.'+self.detalleForm.nota_credito_factura.numero_factura+', por concepto de: '+self.detalleForm.nota_credito_tipo.descripcion;
                                }else{
                                    descripcionx = 'Se acredita al proveedor el monto parcial de la factura No.'+self.detalleForm.nota_credito_factura.numero_factura+', por concepto de: '+self.detalleForm.nota_credito_tipo.descripcion;
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
                                if (nuevo_total <= Number(self.form.detalleFacturas[keyx].nota_credito_factura.total)) {
                                    self.form.detalleFacturas[keyx].monto = nuevo_total;
                                    //self.form.detalleFacturas[keyx].monto_me = Number((self.detalleForm.monto/self.form.t_cambio).toFixed(2));
                                    alertify.success("Factura agregada!", 5);

                                    if(nuevo_total === Number(self.form.detalleFacturas[keyx].nota_credito_factura.total)){
                                        self.form.detalleFacturas[keyx].descripcion = 'Se acredita al proveedor el monto total de la factura No.'+self.form.detalleFacturas[keyx].nota_credito_factura.numero_factura+', por concepto de: '+self.form.detalleFacturas[keyx].nota_credito_tipo.descripcion;
                                    }else{
                                        self.form.detalleFacturas[keyx].descripcion = 'Se acredita al proveedor el monto parcial de la factura No.'+self.form.detalleFacturas[keyx].nota_credito_factura.numero_factura+', por concepto de: '+self.form.detalleFacturas[keyx].nota_credito_tipo.descripcion;
                                    }

                                } else {

                                   /* let tasax = 1;
                                    let monto_maximo = Number(self.form.detalleFacturas[keyx].nota_credito_factura.total);
                                    if(self.form.nota_credito_moneda.id_moneda !== self.form.detalleFacturas[keyx].nota_credito_factura.id_moneda){
                                        tasax=self.form.t_cambio;
                                        if(self.form.nota_credito_moneda.id_moneda === 3){
                                            monto_maximo = Number((self.form.detalleFacturas[keyx].nota_credito_factura.total/tasax).toFixed(2));
                                        }else{
                                            monto_maximo = Number((self.form.detalleFacturas[keyx].nota_credito_factura.total*tasax).toFixed(2));
                                        }
                                    }


                                    self.form.detalleFacturas[keyx].monto = monto_maximo;*/

                                    alertify.warning("Ya se ha agregado esa factura, si desea modificarla, borre la fila antes", 5);
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

            seleccionarProveedor(e) {
                const clienteP = e.target.value;
                var self = this;
                self.form.nota_credito_proveedor = clienteP;
                self.form.nombre_persona = self.form.nota_credito_proveedor.nombre_comercial;

                self.loading = true;
                self.form.nota_credito_factura = '';

                notas_credito.obtenerFacturasCompraProveedor({
                    id_proveedor: self.form.nota_credito_proveedor.id_proveedor
                }, data => {
                    if (data !== null) {

                        self.compras = [];
                        self.compras_baterias = [];
                        self.compras_servicios = [];

                        self.compras_baterias = data.ordenes_compra_baterias;
                        self.compras_servicios = data.ordenes_compra_servicios;

                        self.compras_baterias.forEach((producto, key) => {self.compras.push(producto)});
                        self.compras_servicios.forEach((servicio, key) => {self.compras.push(servicio)});

                        self.form.nota_credito_factura = '';
                    } else {
                        alertify.warning("No se encuentran facturas para este proveedor.", 5);
                        self.form.nota_credito_factura = '';
                    }
                    self.loading = false;
                }, err => {
                    /*if(err.codigo_bateria){
                      self.detalleForm.bateria_busqueda = '';
                      self.$refs.bateria.focus();
                      alertify.warning("No se encuentra esta batería.",5);
                      self.form.nota_credito_factura = '';
                    }*/
                    self.loading = false;
                })

            },

            nueva() {
                var self = this;
                notas_credito.nueva({}, data => {
                        self.compras = [];
                        self.tipos_notas = data.tipos_notas;
                        self.monedas = data.monedas;
                        self.form.nota_credito_moneda=self.monedas[0];
                        self.form.t_cambio = Number(data.t_cambio.tasa);
                        self.loading = false;
                    },
                    err => {
                        console.log(err);
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
                                        name: "notas-credito-cp"
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





