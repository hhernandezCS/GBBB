<template>
    <div class="main">
        <div class="row">
            <div class="col-md-12">
                <b-card class="content-box">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for>Usuario Solicitante</label>
                                <v-select :disabled="true"
                                          :options="trabajadores"
                                          label="nombre_completo"
                                          v-model="form.solicitud_trabajador"
                                ></v-select>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.solicitud_area"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for>Area Solicitante</label>
                                <v-select :disabled="true"
                                          :options="areas"
                                          label="descripcion"
                                          v-model="form.solicitud_area"
                                ></v-select>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.solicitud_area"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label for>Sucursal</label>
                                <v-select :disabled="true"
                                        label="descripcion"
                                        v-model="form.solicitud_sucursal"
                                        :options="sucursales"
                                ></v-select>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.solicitud_sucursal" v-text="message"></li>
                                </ul>
                            </div>
                        </div>-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for>Bodega</label>
                                <v-select
                                        :disabled="true"
                                        :options="bodegas"
                                        label="descripcion"
                                        v-model="form.solicitud_bodega"
                                ></v-select>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.solicitud_bodega"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for>Moneda</label>
                                <v-select
                                        :disabled="true"
                                        :options="monedas"
                                        label="descripcion"
                                        v-model="form.solicitud_moneda"
                                ></v-select>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.solicitud_moneda"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>Fecha Necesidad</label>
                                <b-form-datepicker :disabled="true" :format="format" :language="es" :value="new Date()"
                                                   @selected="onDateSelect"></b-form-datepicker>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.f_necesidad"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>

                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for>Observaciones solicitud</label>
                                <b-form-input class="form-control" type="text"
                                              v-model="form.observacion"></b-form-input>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.observacion"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>

                    </div>

                    <div v-if="!form.solicitud_area">
                        <b-alert class="mt-2 mb-2" show variant="success">
                            <div class="alert-body">
                                <span>Se requiere que seleccione una area para continuar.</span>
                            </div>
                        </b-alert>
                        <hr>
                    </div>

                    <b-alert class="mt-2 mb-2" show variant="success">
                        <div class="alert-body">
                            <span>Detalle de productos.</span>
                        </div>
                    </b-alert>
                    <!--<div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>Producto</label>

                                <typeahead style="width: 100%;" :initial="detalleForm.productox" :trim="80" :url="productosBusquedaURL" @input="seleccionarProducto"></typeahead>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.productox"
                                            v-text="message"
                                    ></li>
                                </ul>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.productox"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>Proveedor</label>
                                <v-select
                                        label="nombre_comercial"
                                        v-model="detalleForm.proveedorx"
                                        :options="proveedores"
                                ></v-select>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.proveedorx" v-text="message"></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>Cantidad</label>
                                <input class="form-control" ref="cantidad" type="number" v-model.number="detalleForm.cantidad">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.cantidadx"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>Precio Info</label>
                                <div class="input-group">
                                    <div class="input-group-addon">C$
                                    </div>
                                    <input class="form-control" type="number" v-model.number="detalleForm.precio_info">
                                </div>

                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.precio_infox"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </div>
                        </div>
                    <div class="col-sm-3">
                            <div class="form-group">
                                <label for>Fecha Necesidad</label>
                                <datepicker :format="format" :language="es" :value="new Date()" @selected="onDateSelect2"></datepicker>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.f_necesidad_productox"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </div>
                        </div>



                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for>&nbsp;</label>
                                <button @click="agregarDetalle" class="btn btn-info btn-agregar" ref="agregar">Agregar Producto</button>
                            </div>
                        </div>

                    </div>-->

                    <div class="row">
                        <div class="col-sm-12 table table-responsive text-nowrap">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.solicitud_productos"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Producto</th>
                                    <th>Fecha Necesidad</th>
                                    <th>Proveedor</th>
                                    <th>Cantidad {{form.estado === 1||form.estado === 2? 'Cotizado': ''}}</th>
                                    <th>Precio Unitario {{form.estado === 1 || form.estado === 2? 'Cotizado': ''}}</th>
                                    <th>Descuento</th>
                                    <th>SubTotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(item, index) in form.solicitud_productos">
                                    <tr>
                                        <td style="width: 2%">
                                            <b-button @click="eliminarLinea(item, index)" disabled variant="danger">
                                                <feather-icon icon="TrashIcon"></feather-icon>
                                            </b-button>
                                        </td>
                                        <td>
                                            {{item.solicitud_producto.descripcion}}
                                            <b-alert show variant="danger">
                                                <ul class="error-messages">
                                                    <li
                                                            :key="message"
                                                            v-for="message in errorMessages[`solicitud_productos.${index}.solicitud_producto.id_producto`]"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </b-alert>

                                        </td>

                                        <td>
                                            <b-form-input class="form-control" disabled v-mask="'####-##-##'"
                                                          v-model="item.f_necesidad_producto"></b-form-input>
                                            <b-alert show variant="danger">
                                                <ul class="error-messages">
                                                    <li
                                                            :key="message"
                                                            v-for="message in errorMessages[`solicitud_productos.${index}.f_necesidad_producto`]"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </b-alert>

                                        </td>

                                        <td>
                                            {{item.solicitud_proveedor.nombre_comercial}}
                                            <b-alert show variant="danger"></b-alert>
                                            <ul class="error-messages">
                                                <li
                                                        :key="message"
                                                        v-for="message in errorMessages[`detalleProductos.${index}.solicitud_proveedor.id_proveedor`]"
                                                        v-text="message">
                                                </li>
                                            </ul>
                                        </td>

                                        <template v-if="form.estado !== 1">

                                            <td>
                                                <b-form-input :disabled="form.estado !== 1" class="form-control"
                                                              type="number"
                                                              v-model.number="item.cantidad_cotizado"></b-form-input>
                                                <b-alert show variant="danger">
                                                    <ul class="error-messages">
                                                        <li
                                                                :key="message"
                                                                v-for="message in errorMessages[`solicitud_productos.${index}.cantidad`]"
                                                                v-text="message">
                                                        </li>
                                                    </ul>
                                                </b-alert>
                                            </td>


                                            <td>
                                                <b-form-input :disabled="form.estado !== 1" class="form-control"
                                                              type="number"
                                                              v-model.number="item.precio_cotizado"></b-form-input>
                                                <b-alert show variant="danger">
                                                    <ul class="error-messages">
                                                        <li
                                                                :key="message"
                                                                v-for="message in errorMessages[`solicitud_productos.${index}.precio_info`]"
                                                                v-text="message">
                                                        </li>
                                                    </ul>
                                                </b-alert>

                                            </td>

                                        </template>
                                        <template v-else>

                                            <td>
                                                <b-form-input :disabled="form.estado !== 1" class="form-control"
                                                              type="number"
                                                              v-model.number="item.cantidad"></b-form-input>
                                                <b-alert show variant="danger">
                                                    <ul class="error-messages">
                                                        <li
                                                                :key="message"
                                                                v-for="message in errorMessages[`solicitud_productos.${index}.cantidad`]"
                                                                v-text="message">
                                                        </li>
                                                    </ul>
                                                </b-alert>

                                            </td>


                                            <td>
                                                <b-form-input :disabled="form.estado !== 1" class="form-control"
                                                              type="number"
                                                              v-model.number="item.precio_info"></b-form-input>
                                                <b-alert show variant="danger">
                                                    <ul class="error-messages">
                                                        <li
                                                                :key="message"
                                                                v-for="message in errorMessages[`solicitud_productos.${index}.precio_info`]"
                                                                v-text="message">
                                                        </li>
                                                    </ul>
                                                </b-alert>

                                            </td>

                                        </template>


                                        <td>
                                            <b-form-input :disabled="form.estado !== 1" class="form-control"
                                                          type="number" v-model.number="item.descuento"></b-form-input>
                                            <b-alert show variant="danger">
                                                <ul class="error-messages">
                                                    <li
                                                            :key="message"
                                                            v-for="message in errorMessages[`solicitud_productos.${index}.descuento`]"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </td>


                                        <td>
                                            <strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}
                                                {{sub_total(item)| formatMoney(2)}}</strong>
                                        </td>


                                    </tr>
                                    <tr></tr>
                                </template>
                                </tbody>
                                <tfoot>

                                <tr>
                                    <td colspan="6"></td>
                                    <td>Subtotal</td>
                                    <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}
                                        {{total_subt | formatMoney(2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td>Descuento</td>
                                    <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}
                                        {{total_descuento | formatMoney(2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td>I.V.A.</td>
                                    <td>
                                        <b-form-input :disabled="form.estado !== 1" class="form-control" max="99"
                                                      min="0" v-model.number="form.porcentaje_iva"></b-form-input>
                                    </td>
                                    <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}
                                        {{calcular_impuesto | formatMoney(2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="item-footer" colspan="2"> Total Unidades</td>
                                    <td class="item-footer">
                                        <strong>{{total_cantidad}}</strong>
                                    </td>
                                    <td></td>
                                    <td>Total</td>
                                    <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}
                                        {{gran_total | formatMoney(2)}}</strong></td>
                                </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <b-card-footer>
                        <b-row>
                            <b-col lg="6" md="6" sm="12" class="text-md-left text-lg-left text-center">
                                <!--Seccion de cancelar-->
                                <router-link :to="{ name: 'solicitudes-compras' }">
                                    <b-button class="mb-md-0 mb-lg-0  mr-1" variant="secondary">Cancelar</b-button>
                                </router-link>
                            </b-col>
                            <b-col lg="6" md="6" sm="12" class="text-md-right text-lg-right text-center">
                                <!--Seccion de autorizaciones-->

                                        <b-dropdown v-if="form.estado === 1 || form.estado === 2" text="Cambiar estado" variant="info">
                                            <b-dropdown-item :disabled="btnAction !== 'Confirmar'"
                                                             @click="confirmar(2)"
                                                             v-if="form.estado === 1">

                                                Confirmar Cotización

                                            </b-dropdown-item>
                                            <b-dropdown-item :disabled="btnAction !== 'Confirmar'"
                                                             @click="confirmar(0)"
                                                             v-if="form.estado === 1">
                                                Rechazar

                                            </b-dropdown-item>
                                            <b-dropdown-item :disabled="btnAction !== 'Confirmar'"
                                                             @click="confirmar(3)"
                                                             v-if="form.estado === 2">
                                                Autorizar Solicitud

                                            </b-dropdown-item>
                                            <b-dropdown-item :disabled="btnAction !== 'Confirmar'"
                                                             @click="confirmar(0)"
                                                             v-if="form.estado === 2">

                                                Rechazar

                                            </b-dropdown-item>
                                        </b-dropdown>


                            </b-col>
                        </b-row>
                        <!--Gif de pantalla de carga-->
                        <template v-if="loading">
                            <BlockUI>
                                <b-spinner label="cargando..." variant="info"/>
                            </BlockUI>
                        </template>
                    </b-card-footer>
                </b-card>

            </div>
        </div>
    </div>
</template>


<script type="text/ecmascript-6">
  import solicitud from "../../../api/Compras/solicitudes_compras";
  import es from 'vuejs-datepicker/dist/locale/translations/es'
  import {
    BAlert,
    BBadge,
    BButton,
    BButtonGroup,
    BCard,
    BCardFooter,
    BCol,
    BContainer,
    BDropdown,
    BDropdownItem,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BInputGroup,
    BPagination,
    BPaginationNav,
    BRow,
    BSpinner,
    BTable,
    VBTooltip
  } from 'bootstrap-vue'
  import vSelect from 'vue-select'
  import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
  import {ValidationObserver, ValidationProvider} from 'vee-validate';
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";

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
      BContainer,
      BFormInput,
      BButtonGroup,
      BSpinner,
      BTable,
      BPagination,
      ValidationProvider,
      ValidationObserver,
      BBadge,
      BDropdown,
      BDropdownItem,
      BInputGroup,
      BCol
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: true,
        productosBusqueda: {},
        productosBusquedaURL: "/inventario/productos/buscarPS",
        es: es,
        format: "dd-MM-yyyy",
        areas: [],
        proveedores: [],
        productos: [],
        trabajadores: [],
        //	sucursales:[],
        monedas: [],
        bodegas: [],


        detalleForm: {
          productox: {},
          proveedorx: "",
          cantidad: 1,
          monto_descuento: 0,
          precio_info: 0,
          subtotal: 0,
          total: 0,
          f_necesidad_producto: moment(new Date()).format("YYYY-MM-DD"),
        },

        form: {
          es_borrador: true,
          observacion: "",
          f_necesidad: moment(new Date()).format("YYYY-MM-DD"),
          solicitud_trabajador: "",
          solicitud_sucursal: "",
          solicitud_area: "",
          total: 0,
          iva: 0,
          porcentaje_iva: 0,
          solicitud_productos: [],
        },
        btnAction: "Confirmar",
        errorMessages: []
      };
    },
    computed: {
      total_cantidad() {
        return this.form.solicitud_productos.reduce((carry, item) => {

          if (this.form.estado !== 1) {
            return (carry + Number(item.cantidad_cotizado))
          } else {
            return (carry + Number(item.cantidad))
          }


        }, 0)
      },
      total_subt() {
        return this.form.solicitud_productos.reduce((carry, item) => {
              if (this.form.estado !== 1) {
                return (carry + Number((Number(item.cantidad_cotizado * item.precio_cotizado).toFixed(2))));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2))));
              }
            }
            , 0)
      },
      total_descuento() {
        return this.form.solicitud_productos.reduce((carry, item) => {
              if (this.form.estado !== 1) {
                return (carry + Number((Number(item.cantidad_cotizado * item.precio_cotizado).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
              }

            }
            , 0)
      },

      calcular_impuesto() {
        //console.log(this.form.estado);
        this.form.iva = Number((this.form.solicitud_productos.reduce((carry, item) => {
              //return(carry + Number(Number(item.subtotal*0.15).toFixed(2)));

              if (this.form.estado > 2) {
                return (carry + Number(Number(item.subtotal) * Number(this.form.porcentaje_iva / 100)));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2)) * (Number(this.form.porcentaje_iva / 100)) * Number(1 - (item.descuento / 100)).toFixed(2)));
              }
            }
            , 0)).toFixed(2));

        return Number((this.form.iva).toFixed(2));
      },

      gran_total() {
        this.form.total = Number(this.form.solicitud_productos.reduce((carry, item) => {
              if (this.form.estado > 2) {
                return (carry + Number((Number(item.cantidad_cotizado * item.precio_cotizado).toFixed(2)) * Number(1 - (item.descuento / 100)).toFixed(2)));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2)) * Number(1 - (item.descuento / 100)).toFixed(2)));
              }
            }
            , 0).toFixed(2))

        return this.form.total + this.form.iva;
      },
    },
    methods: {

      confirmar(estadox) {
        let titulox = '';
        if (estadox === 0) {
          titulox = 'Esta seguro de rechazar ésta solicitud de compra?';
        } else if (estadox === 2) {
          titulox = 'Esta seguro de cambiar el estado a Cotizado?';
        } else if (estadox === 3) {
          titulox = 'Esta seguro de autorizar esta solicitud de compra?';
        }

        this.$swal.fire({
          title: titulox,
          text: "Revise bien los datos, esta acción no se puede deshacer.",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            this.cambiarEstado(estadox);
          }
        })
      },

      seleccionarProducto(e) {
        const productoP = e.target.value;
        const self = this;
        self.detalleForm.productox = productoP;

        if (self.detalleForm.productox)
          self.detalleForm.precio_info = self.detalleForm.productox.costo_estandar;

      },
      obtenerSolicitud() {
        const self = this;
        solicitud.obtenerSolicitud({
          id_solicitud_compra: self.$route.params.id_solicitud_compra,
          cargar_dependencias: true,
        }, data => {

          //self.sucursales = data.sucursales;
          self.monedas = data.monedas;
          self.bodegas = data.bodegas;
          self.areas = data.areas;
          self.trabajadores = data.trabajadores;
          self.proveedores = data.proveedores;
          self.form = data.solicitud;

          if (self.form.estado === 99 || self.form.estado === 4 || self.form.estado === 0) {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'La solicitud no puede ser revisada',
                    variant: 'warning',
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: "solicitudes-compras"
            });
          }
          self.loading = false;
        })
      },
      sub_total(itemX) {

        if (this.form.estado !== 1) {
          itemX.subtotal = Number((Number(itemX.precio_cotizado) * Number(itemX.cantidad_cotizado)).toFixed(2));
        } else {
          itemX.subtotal = Number((Number(itemX.precio_info) * Number(itemX.cantidad)).toFixed(2));
        }


        itemX.monto_descuento = Number(Number(itemX.subtotal * Number(itemX.descuento / 100)).toFixed(2));
        itemX.total = itemX.subtotal - itemX.monto_descuento;
        if (!isNaN(itemX.total)) {
          return itemX.total;
        } else return 0;
      },

      onDateSelect(date) {
        this.form.f_necesidad = moment(date).format("YYYY-MM-DD"); //
      },
      onDateSelect2(date) {
        this.detalleForm.f_necesidad_producto = moment(date).format("YYYY-MM-DD"); //
      },
      agregarDetalle() {
        if (this.detalleForm.productox) {
          if (this.detalleForm.cantidad > 0 && this.detalleForm.precio_info > 0) {
            this.detalleForm.productox.descripcion = this.detalleForm.productox.text;
            this.form.solicitud_productos.push({
              solicitud_producto: this.detalleForm.productox,
              solicitud_proveedor: this.detalleForm.proveedorx,
              id_producto: this.detalleForm.productox.id_producto,
              id_proveedor: this.detalleForm.proveedorx.id_proveedor,
              cantidad: this.detalleForm.cantidad,
              precio_info: this.detalleForm.precio_info,
              f_necesidad_producto: this.detalleForm.f_necesidad_producto,
              descuento: 0,
              monto_descuento: Number(0),
              subtotal: 0,
              total: 0,
            });

            this.detalleForm.productox = {};
            this.detalleForm.proveedorx = '';
            this.detalleForm.cantidad = 0;
            this.detalleForm.precio_info = 0;
            this.detalleForm.subtotal = 0;
            this.detalleForm.monto_descuento = 0;
            this.detalleForm.descuento = 0;
            this.detalleForm.total = 0;
          } else {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'Los valores para cantidad y precio unitario deben ser mayores que cero',
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
                  icon: 'CheckIcon',
                  text: 'Debe seleccionar un producto',
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
        }
      },
      eliminarLinea(item, index) {
        if (this.form.solicitud_productos.length >= 1) {
          this.form.solicitud_productos.splice(index, 1);

        } else {
          this.form.solicitud_productos = [];
        }
      },


      cambiarEstado(estadox) {
        const self = this;
        self.loading = true;
        if ((self.form.estado === 1 && (estadox === 2 || estadox === 0)) || (self.form.estado === 2 && (estadox === 3 || estadox === 0) || (self.form.estado === 3 && (estadox === 4 || estadox === 0)))) {
          self.btnAction = "Registrando, espere.....";

          solicitud.cambiarEstado({
                id_solicitud_compra: this.$route.params.id_solicitud_compra,
                estado: estadox,
                productos: self.form.solicitud_productos,
              },
              data => {
                if (estadox === 2) {
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'El estado de la solicitud ha cambiado a Cotizado',
                          variant: 'success',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                } else if (estadox === 3) {
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'El estado de la solicitud ha cambiado a Autorizado',
                          variant: 'success',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                }
                self.loading = false;
                this.$router.push({
                  name: "mostrar-solicitudes-compras",
                  params: {id_solicitud_compra: self.form.id_solicitud_compra}
                });
              },
              err => {
                self.errorMessages = err;
                self.btnAction = "Confirmar";
              }
          );
        } else {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'La solicitud no puede ser actualizada',
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
        }
      }
    },
    mounted() {
      this.obtenerSolicitud();
    }
  };
</script>
<style lang="scss">
    @import '../../../@core/scss/vue/libs/vue-wizard';
    @import '../../../@core/scss/vue/libs/vue-select';

    .btn-agregar {
        margin-top: 33px;
    }

    .table {
        white-space: nowrap;
        overflow-x: auto;
    }
</style>
