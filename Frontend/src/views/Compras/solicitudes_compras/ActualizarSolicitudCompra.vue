<template>
    <b-card>
        <b-row>

            <!--< <div class="col-sm-4">
               <div class="form-group">
                 <label for>Tipo Compra</label>
                 <select class="form-control" v-model.number="form.id_tipo_compra">
                   <option value="1">Local</option>
                   <option value="2">Internacional</option>
                 </select>
                 <ul class="error-messages">
                   <li
                           :key="message"
                           v-for="message in errorMessages.id_tipo_compra"
                           v-text="message"
                   ></li>
                 </ul>
               </div>
             </div>-->

            <div class="col-sm-6">
                <div class="form-group">
                    <label for>Usuario Solicitante</label>
                    <v-select
                            :options="trabajadores"
                            label="nombre_completo"
                            v-model="form.solicitud_trabajador"
                    ></v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.solicitud_area" v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>


            <div class="col-sm-6">
                <div class="form-group">
                    <label for>Area Solicitante</label>
                    <v-select
                            :options="areas"
                            label="descripcion"
                            v-model="form.solicitud_area"
                    ></v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.solicitud_area" v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>

            <!--<div class="col-sm-4">
                <div class="form-group">
                    <label for>Sucursal</label>
                    <v-select
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
                            :options="bodegas"
                            label="descripcion"
                            v-model="form.solicitud_bodega"
                    ></v-select>
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.solicitud_bodega" v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for>Moneda</label>
                    <v-select
                            :options="monedas"
                            label="descripcion"
                            v-model="form.solicitud_moneda"
                    ></v-select>
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.solicitud_moneda" v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Fecha Necesidad</label>
                    <b-form-datepicker :format="format" :language="es" :value="new Date()"
                                @selected="onDateSelect"></b-form-datepicker>
                    <b-alert variant="danger" show>
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

            <div class="col-sm-8">
                <div class="form-group">
                    <label for>Observaciones solicitud</label>
                    <b-form-input class="form-control" type="text" v-model="form.observacion"></b-form-input>
                        <b-alert variant="danger" show>
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

        </b-row>
            <div v-if="!form.solicitud_area">
                <b-alert class="mt-2 mb-2" show variant="info">
                    <div class="alert-body">
                        <span>Se requiere seleccionar un area para continuar</span>
                    </div>

                </b-alert>
            </div>

            <b-alert class="mt-2 mb-2" show variant="success">
                <div class="alert-body">
                    <span>Detalles de la solicitud</span>
                </div>

            </b-alert>
            <b-row>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for>Producto</label>
                        <v-select :allow-empty="false" :options="productos"
                                     :searchable="true"
                                     :show-labels="false"
                                     deselect-label="No se puede eliminar este valor"
                                     label="text"
                                     placeholder="Selecciona un producto"
                                     ref="producto"
                                     track-by="id_producto"
                                     v-model="detalleForm.productox"
                                     v-on:input="seleccionarProducto"
                        ></v-select>

                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.productox"
                                        v-text="message"
                                ></li>
                            </ul>
                        </b-alert>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for>Proveedor</label>
                        <v-select
                                :options="proveedores"
                                label="nombre_comercial"
                                v-model="detalleForm.proveedorx"
                        ></v-select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.proveedorx" v-text="message"></li>
                            </ul>
                        </b-alert>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>Cantidad</label>
                        <b-form-input class="form-control" ref="cantidad" type="number" v-model.number="detalleForm.cantidad"></b-form-input>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidadx"
                                        v-text="message"
                                ></li>
                            </ul>
                        </b-alert>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>Precio Info  {{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}</label>
                        <div class="input-group">
                            <b-form-input class="form-control" type="number" v-model.number="detalleForm.precio_info"></b-form-input>
                        </div>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.precio_infox"
                                        v-text="message"
                                ></li>
                            </ul>
                        </b-alert>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>Fecha Necesidad</label>
                        <b-form-datepicker :format="format" :language="es" :value="new Date()"
                                    @selected="onDateSelect2"></b-form-datepicker>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.f_necesidad_productox"
                                        v-text="message"
                                ></li>
                            </ul>
                        </b-alert>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>&nbsp;</label>
                        <b-button @click="agregarDetalle" class="btn-agregar" variant="info" ref="agregar">Agregar
                            Producto
                        </b-button>
                    </div>
                </div>

            </b-row>

            <b-row>
                <div class="col-sm-12">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.solicitud_productos"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>
                    <table class="table table-responsive table-bordered">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Producto</th>
                            <th>Fecha Necesidad</th>
                            <th>Proveedor</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Descuento</th>
                            <th>SubTotal</th>


                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(item, index) in form.solicitud_productos">
                            <tr>
                                <td style="width: 2%">
                                    <b-button variant="danger" @click="eliminarLinea(item, index)">
                                        <feather-icon icon="TrashIcon"></feather-icon>
                                    </b-button>
                                </td>
                                <td>
                                    <b-form-input class="form-control" disabled v-model="item.solicitud_producto.descripcion"></b-form-input>
                                    <b-alert variant="danger" show>
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
                                    <b-form-input class="form-control" v-mask="'####-##-##'"
                                                  v-model="item.f_necesidad_producto"> </b-form-input>
                                    <b-alert variant="danger" show>
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
                                    <b-form-input class="form-control" disabled
                                                  v-model="item.solicitud_proveedor.nombre_comercial"></b-form-input>
                                    <b-alert variant="danger" show>
                                        <ul class="error-messages">
                                            <li
                                                    :key="message"
                                                    v-for="message in errorMessages[`detalleProductos.${index}.solicitud_proveedor.id_proveedor`]"
                                                    v-text="message">
                                            </li>
                                        </ul>
                                    </b-alert>
                                </td>

                                <td>
                                    <b-form-input class="form-control" type="number" v-model.number="item.cantidad"></b-form-input>
                                    <b-alert variant="danger" show>
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
                                    <b-form-input class="form-control" type="number" v-model.number="item.precio_info"></b-form-input>
                                    <b-alert variant="danger" show>
                                        <ul class="error-messages">
                                            <li
                                                    :key="message"
                                                    v-for="message in errorMessages[`solicitud_productos.${index}.precio_info`]"
                                                    v-text="message">
                                            </li>
                                        </ul>
                                    </b-alert>
                                </td>
                                <td>
                                    <b-form-input class="form-control" type="number" v-model.number="item.descuento"></b-form-input>
                                    <b-alert variant="danger" show>
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
                            <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}} {{total_subt |
                                formatMoney(2)}}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td>Descuento</td>
                            <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}} {{total_descuento
                                | formatMoney(2)}}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td>I.V.A.</td>
                            <td><b-form-input class="form-control" max="99" min="0" v-model.number="form.porcentaje_iva"></b-form-input></td>
                            <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}}
                                {{calcular_impuesto | formatMoney(2)}}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="item-footer" colspan="2"> Total Unidades</td>
                            <td class="item-footer">
                                <strong>{{total_cantidad}}</strong>
                            </td>
                            <td></td>
                            <td>Total</td>
                            <td><strong>{{form.solicitud_moneda ? form.solicitud_moneda.codigo : ''}} {{gran_total |
                                formatMoney(2)}}</strong></td>
                        </tr>

                        </tfoot>
                    </table>
                </div>
            </b-row>

            <b-card-footer>
                <div class="col-12 text-md-right text-lg-right text-center">
                    <router-link :to="{ name: 'solicitudes-compras' }" >
                        <b-button variant="secondary" type="button" class="mb-md-0 mb-lg-0  mr-1">Cancelar</b-button>
                    </router-link>
                    <b-button
                            :disabled="btnActionDraft !== 'Guardar Borrador'"
                            @click="form.es_borrador=true;actualizar()"
                            variant="dark"
                            type="button"
                            class="mb-md-0 mb-lg-0  mr-1"
                    >{{ btnActionDraft }}
                    </b-button>
                    <b-button
                            :disabled="btnAction !== 'Actualizar y Solicitar'"
                            @click="form.es_borrador=false;actualizar()"
                            variant="primary"
                            type="button"
                    >{{ btnAction }}
                    </b-button>
                </div>
            </b-card-footer>

            <!--Gif de pantalla de carga-->
            <template v-if="loading">
                <BlockUI>
                    <b-spinner label="cargando..." variant="info"/>
                </BlockUI>
            </template>
    </b-card>
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
    BContainer,
    BDropdown,
    BDropdownItem,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormGroup,
    BFormInput,
    BFormSelect,
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
      BDropdownItem
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        productosBusqueda: {},
        productosBusquedaURL: "/inventario/productos/buscarPS",
        es: es,
        format: "dd-MM-yyyy",
        areas: [],
        proveedores: [],
        productos: [],
        trabajadores: [],
        //sucursales:[],
        monedas: [],
        bodegas: [],
        loading: true,
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

        total_cantidadx: 0,
        total_subtotalx: 0,
        total_ivax: 0,
        total_descuentox: 0,
        total_finalx: 0,

        form: {
          es_borrador: true,
          observacion: "",
          f_necesidad: moment(new Date()).format("YYYY-MM-DD"),
          solicitud_trabajador: "",
          solicitud_sucursal: "",
          solicitud_area: "",
          total: 0,
          porcentaje_iva: 0,
          iva: 0,
          solicitud_productos: [],
        },
        btnAction: "Actualizar y Solicitar",
        btnActionDraft: "Guardar Borrador",
        errorMessages: []
      };
    },
    computed: {
      total_cantidad() {
        this.total_cantidadx = this.form.solicitud_productos.reduce((carry, item) => {
          return (carry + Number(item.cantidad))
        }, 0)
        return this.total_cantidadx;
      },
      total_subt() {
        this.total_subtotalx = Number(this.form.solicitud_productos.reduce((carry, item) => {
              return (carry + Number(Number(item.subtotal).toFixed(2)));
            }
            , 0).toFixed(2))
        return this.total_subtotalx;
      },
      total_descuento() {
        this.total_descuentox = Number(this.form.solicitud_productos.reduce((carry, item) => {
              return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
            }
            , 0).toFixed(2))
        return this.total_descuentox;
      },
      calcular_impuesto() {
        this.form.iva = Number((Number(this.total_subtotalx - this.total_descuentox) * Number(this.form.porcentaje_iva / 100)).toFixed(2));
        this.total_ivax = this.form.iva;
        return this.form.iva;
      },

      gran_total() {

        this.form.total = Number(Number(this.total_subtotalx - this.total_descuentox).toFixed(2));
        this.total_finalx = this.form.total;
        return Number(this.total_finalx.toFixed(2)) + Number(this.total_ivax.toFixed(2));
      },
    },
    methods: {
      seleccionarProducto() {
        const self = this;
        if (self.detalleForm.productox)
          self.detalleForm.cantidad = 1;
        self.detalleForm.precio_info = self.detalleForm.productox.precio_compra;
        self.$refs.cantidad.focus();
      },
      /*seleccionarProducto(e) {
          const productoP = e.target.value;
          var self = this;
          self.detalleForm.productox = productoP;

          if(self.detalleForm.productox)
              self.detalleForm.precio_info = self.detalleForm.productox.precio_compra;

      },*/
      obtenerSolicitud() {
        const self = this;
        solicitud.obtenerSolicitud({
          id_solicitud_compra: this.$route.params.id_solicitud_compra,
          cargar_dependencias: true,
        }, data => {

          //self.sucursales = data.sucursales;
          self.monedas = data.monedas;
          self.bodegas = data.bodegas;
          self.areas = data.areas;
          self.trabajadores = data.trabajadores;
          self.proveedores = data.proveedores;
          self.productos = data.productos;
          self.form = data.solicitud;
          self.loading = false;
          if (self.form.estado !== 99) {
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'La solicitud ya no puede ser actualizada.',
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
        })
      },
      sub_total(itemX) {
        itemX.subtotal = Number((Number(itemX.precio_info) * Number(itemX.cantidad)).toFixed(2));
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
        console.log(this.detalleForm.f_necesidad_producto);
      },
      agregarDetalle() {
        let self = this;
        if (this.detalleForm.productox) {
          if (this.detalleForm.cantidad > 0 && this.detalleForm.precio_info > 0) {

            let i = 0;
            let keyx = 0;
            if (self.form.solicitud_productos) {
              self.form.solicitud_productos.forEach((productox, key) => {
                //console.log('ID_PRODUCTO ',self.detalleForm.productox.id_producto);
                if (self.detalleForm.productox.id_producto === productox.solicitud_producto.id_producto) {
                  i++;
                  keyx = key;
                }
              });
            }

            if (i === 0) {
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
              //this.detalleForm.proveedorx='';
              this.detalleForm.cantidad = 0;
              this.detalleForm.precio_info = 0;
              this.detalleForm.subtotal = 0;
              this.detalleForm.monto_descuento = 0;
              this.detalleForm.descuento = 0;
              this.detalleForm.total = 0;

            } else {
              this.$swal.fire({
                title: 'El producto ya existe en el detalle, desea sumar la nueva cantidad al monto existente?',
                text: "También se sobreescribirá el costo unitario",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, confirmar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                  self.form.solicitud_productos[keyx].cantidad = Number(self.form.solicitud_productos[keyx].cantidad) + self.detalleForm.cantidad;
                  self.form.solicitud_productos[keyx].precio_info = self.detalleForm.precio_info;
                  this.detalleForm.productox = {};
                  this.detalleForm.proveedorx = '';
                  this.detalleForm.cantidad = 0;
                  this.detalleForm.precio_info = 0;
                  this.detalleForm.subtotal = 0;
                  this.detalleForm.monto_descuento = 0;
                  this.detalleForm.descuento = 0;
                  this.detalleForm.total = 0;
                }
              })
            }

          } else {
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'Los valores para cantidad y precio unitario deben ser mayores que cero.',
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
                  text: 'Debe seleccionar un producto.',
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

      actualizar() {
        const self = this;
        if (self.form.estado === 99) {
          self.btnAction = "Registrando, espere....";
          self.loading = true;
          solicitud.actualizar(
              self.form,
              data => {
                if (self.form.es_borrador) {
                  this.$toast({
                      component: ToastificationContent,
                      props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'El borrador de la solicitud fue actualizado correctamente.',
                          variant: 'success',
                      }
                  },
                  {
                      position: 'bottom-right'
                  });
                } else {
                  alertify.success("La solicitud fue actualizada y SOLICITADA correctamente", 5);
                  this.$toast({
                      component: ToastificationContent,
                      props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'La solicitud fue actualizada y solicitada correctamente',
                          variant: 'success',
                      }
                  },
                  {
                      position: 'bottom-right'
                  });
                }
                self.loading = false;
                this.$router.push({
                  name: "solicitudes-compras"
                });
              },
              err => {
                self.loading = false;
                self.errorMessages = err;
                self.btnAction = "Actualizar y Solicitar";
                self.btnActionDraft = "Guardar Borrador";
              }
          );
        } else {
          this.$toast({
              component: ToastificationContent,
              props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'La solicitud ya no puede ser actualizada.',
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
<style>
    .btn-agregar {
        margin-top: 33px;
    }
</style>
