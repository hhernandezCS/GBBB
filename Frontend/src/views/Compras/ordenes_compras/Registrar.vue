<template>
    <b-card>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for=""> Tipo Orden Compra:</label>
                    <b-form-select class="form-control" v-model.number="form.tipo_compra">
                        <option value="1">Nacional</option>
                        <option value="2">Internacional</option>
                    </b-form-select>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.tipo_compra"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Proveedor</label>
                    <v-select
                            :options="proveedores"
                            label="nombre_comercial"
                            v-model="form.proveedor"
                            v-on:input="cargar_detalles_proveedor()"
                    ></v-select>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.proveedor" v-text="message"></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>RUC Proveedor</label>
                    <b-form-input class="form-control" disabled type="text"
                                  v-model="form.numero_ruc_proveedor"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.numero_ruc_proveedor"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <!--<div class="col-sm-4">
                <div class="form-group">
                    <label for>Atención:</label>
                    <b-form-input class="form-control" type="text" v-model="form.atencion"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.atencion"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Email Proveedor:</label>
                    <b-form-input class="form-control" type="text" v-model="form.email_proveedor"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.email_proveedor"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Télefono Proveedor:</label>
                    <b-form-input class="form-control" type="text" v-model="form.telefono_proveedor"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.telefono_proveedor"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Referencia:</label>
                    <b-form-input class="form-control" type="text" v-model="form.referencia_solicitud"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.referencia_solicitud"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Area / Departamento:</label>
                    <b-form-input class="form-control" type="text" v-model="form.area_proveedor"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.area_proveedor"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>-->

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Bodega</label>
                    <v-select
                            :options="bodegas"
                            label="descripcion"
                            v-model="form.bodega"
                    ></v-select>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.proveedor" v-text="message"></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Moneda</label>
                    <v-select
                            :options="monedas"
                            label="descripcion"
                            v-model="form.moneda"
                    ></v-select>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.proveedor" v-text="message"></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for>No. Factura</label>
                    <b-form-input class="form-control" type="text"
                                  v-model="form.numero_factura"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.numero_ruc_proveedor"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Fecha Orden Compra</label>
                    <flat-pickr
                            @selected="onDateSelect"
                            class="form-control"
                            id="f_compra"
                            v-model="form.f_orden_compra"
                    >

                    </flat-pickr>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.f_orden_compra"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

        </div>

        <!--<b-alert
                class="mb-2 mt-2"
                show
                variant="success">

            <div class="alert alert-success">
                <span><strong>DETALLES DE LA ORDEN</strong></span>
            </div>
        </b-alert>

        <div class="row">

            <div class="col-sm-12">
                <div class="form-group">
                    <label for>Dirección</label>
                    <b-form-input class="form-control" type="text" v-model="form.direccion"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.direccion"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for=""> Tipo Pago:</label>
                    <b-form-select class="form-control" v-model.number="form.id_condicion_pago">
                        <option value="1">Contado</option>
                        <option value="2">Crédito</option>
                        <option value="3">Consignación</option>
                    </b-form-select>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.id_condicion_pago"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for=""> Tipo:</label>
                    <b-form-select class="form-control" v-model.number="form.id_medio_pago">
                        <option value="1">Crédito</option>
                        <option value="2">Transferencia</option>
                        <option value="3">Cheque</option>
                        <option value="4">Minuta Depósito</option>
                        <option value="5">Efectivo</option>
                        <option value="6">Tarjeta</option>
                    </b-form-select>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.id_medio_pago"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Condición envío</label>
                    <b-form-select class="form-control" v-model.number="form.condicion_envio">
                        <option value="Envío">Envío</option>
                        <option value="Retiro en tienda"> Retiro en tienda</option>
                    </b-form-select>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.condicion_envio"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Tiempo de Entrega</label>
                    <b-form-input class="form-control" type="text" v-model.number="form.tiempo_entrega"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.tiempo_entrega"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="form-group">
                    <label for>Nota</label>
                    <b-form-input class="form-control" type="text" v-model="form.nota"></b-form-input>
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.nota"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

        </div>

        <div v-if="!form.proveedor">
            <div class="alert alert-info">
                <span>Se requiere que seleccione una proveedor para continuar.</span>
            </div>
            <hr>
        </div>-->

        <b-alert
                class="mb-2 mt-2"
                show
                variant="success">
            <div class="alert alert-success">
                <span><strong>Detalle de productos</strong></span>
            </div>

        </b-alert>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Producto</label>

                    <typeahead :initial="detalleForm.productox" :trim="80" :url="productosBusquedaURL"
                               @input="seleccionarProducto" style="width: 100%;"></typeahead>


                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.productox"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Cantidad</label>
                    <b-form-input class="form-control" ref="cantidad" type="number"
                                  v-model.number="detalleForm.cantidad"></b-form-input>

                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.cantidadx"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Precio compra C$</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        </div>
                        <b-form-input class="form-control" type="number"
                                      v-model.number="detalleForm.precio_cord"></b-form-input>
                    </div>

                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.preciox"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Precio venta C$</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                        </div>
                        <b-form-input class="form-control" type="number"
                                      v-model.number="detalleForm.precio_sugerido"></b-form-input>
                    </div>

                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.precio_sugerido"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 form-inline">
                <div class="form-group">
                    <label class="check-label2"><input class="mx-sm-1" type="checkbox" v-model="detalleForm.aplicaIva">
                        Aplica IVA</label>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <button @click="agregarDetalle" class="btn btn-info btn-agregar" ref="agregar">Agregar
                    </button>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-12">
                <b-alert show variant="danger">
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.detalleProductos"
                                v-text="message"
                        ></li>
                    </ul>
                </b-alert>

                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario C$</th>
                        <th>Precio Unitario $</th>
                        <th>Precio venta C$</th>
                        <th>Impuesto C$</th>
                        <th>Descuento</th>
                        <th>SubTotal</th>


                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.detalleProductos">
                        <tr>
                            <td style="width: 2%">
                                <b-button @click="eliminarLinea(item, index)" variant="danger">
                                    <feather-icon icon="TrashIcon"></feather-icon>
                                </b-button>
                            </td>
                            <td>
                                {{item.productox.text}}
                                <!--<b-form-input class="form-control" disabled
                                              v-model="item.productox.text"></b-form-input>-->
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.productox.id_producto`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>

                            <td>
                                <b-form-input class="form-control" type="number"
                                              v-model.number="item.cantidad"></b-form-input>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.cantidad`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>


                            <td>
                                <b-form-input class="form-control" type="number"
                                              v-model.number="item.precio_cord"></b-form-input>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.precio_cord`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>
                            <td>
                                {{ precio_unitario_dol(item)| formatMoney(2) }}
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.precio_cord`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>
                            <td>
                                {{ item.precio_sugerido| formatMoney(2) }}
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.precio_sugerido`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>
                            <td>
                                {{ impuestoCord(item)| formatMoney(2) }}
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.precio_cord`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>


                            <td>
                                <b-form-input class="form-control" disabled type="number"
                                              v-model.number="item.descuento"></b-form-input>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.descuento`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>

                            <td>
                                <strong>C$ {{ sub_total(item)| formatMoney(2) }}</strong>
                            </td>
                        </tr>
                        <tr></tr>
                    </template>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="6"></td>
                        <td>Subtotal</td>
                        <td><strong>C$ {{ total_subt | formatMoney(2) }}</strong></td>
                        <td><strong>$ {{ total_subt_me | formatMoney(2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td>Descuento %</td>
                        <td>
                            <b-form-input class="form-control" type="number"
                                          v-model.number="form.porcentaje_descuento"></b-form-input>
                        </td>
                        <!--            <td><strong>C$ {{ form.descuento | formatMoney(2) }}</strong></td>-->
                        <!--            <td><strong>$ {{ form.descuento_me | formatMoney(2) }}</strong></td>-->
                    </tr>
                    <tr>
                        <td class="item-footer" colspan="2"> Total Linea</td>
                        <td class="item-footer">
                            <strong>{{ total_linea }}</strong>
                        </td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><strong>C$ {{ total_descuento | formatMoney(2) }}</strong></td>
                        <td><strong>$ {{ total_descuento_me | formatMoney(2) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="item-footer" colspan="2"> Total Unidades</td>
                        <td class="item-footer">
                            <strong>{{ total_cantidad }}</strong>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td><strong>C$ {{ gran_total | formatMoney(2) }}</strong></td>
                        <td><strong>$ {{ gran_total_me | formatMoney(2) }}</strong></td>
                    </tr>

                    </tfoot>
                </table>
            </div>
        </div>
        <!--Loading screen-->
        <template v-if="loading">
            <BlockUI>
                <b-spinner label="loading..." variant="info"/>
            </BlockUI>
        </template>
        <b-card-footer>
            <div class="content-box-footer text-right">
                <router-link :to="{ name: 'ordenes-compras' }">
                    <b-button variant="secondary">Cancelar</b-button>
                </router-link>
                <b-button
                        :disabled="btnActionDraft !== 'Guardar Borrador'"
                        @click="form.es_borrador=true;registrar()"
                        class="mx-1"
                        variant="dark"
                >{{ btnActionDraft }}
                </b-button>
                <b-button
                        :disabled="btnAction !== 'Registrar'"
                        @click="form.es_borrador=true;registrar()"
                        variant="primary"
                >{{ btnAction }}
                </b-button>
            </div>
        </b-card-footer>


    </b-card>

</template>

<script type="text/ecmascript-6">
  import orden from "../../../api/Compras/orden_compras";
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
  import flatPickr from 'vue-flatpickr-component'
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import tc from "../../../api/contabilidad/tasas-cambio";
  import * as Round
    from "../../../../../Backend/vendor/phpunit/php-code-coverage/src/Report/Html/Renderer/Template/js/d3.min";


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
      flatPickr
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        productosBusqueda: {},
        productosBusquedaURL: "/inventario/productos/buscar",
        es: es,
        format: "dd-MM-yyyy",
        proveedores: [],
        loading: false,
        query: '',

        detalleForm: {
          productox: {text: 'Escanea el codigo de barras o escribe para buscar un producto'},
          cantidad: 1,
          item_com: 1,
          monto_descuento: 0,
          precio: 0,
          subtotal: 0,
          total: 0,
          totalme: 0,
          aplicaIva: false,
          precio_sugerido: 0,
          porcentaje_ganancia: 0
        },

        form: {
          porcentaje_descuento: 0,
          aplica_porcentaje_ganancia: false,
          observacion: "",
          f_orden_compra: moment(new Date()).format("YYYY-MM-DD"),
          proveedor: "",
          referencia_solicitud: "",
          telefono_proveedor: "",
          email_proveedor: "",
          atencion: "",
          area_proveedor: "",
          t_cambio: 0,

          direccion: "",
          condicion_envio: "Envío",
          nota: "",
          tiempo_entrega: 15,

          id_condicion_pago: 1,
          id_medio_pago: 4,
          tipo_compra: 1,
          total: 0,
          totalme: 0,
          detalleProductos: [],
          es_borrador: false,
          numero_factura: '',
          subtotal: 0,
          subtotal_me: 0,
          descuento: 0,
          descuento_me: 0,
        },
        productos: [],
        monedas: [],
        bodegas: [],
        btnAction: "Registrar",
        btnActionDraft: "Guardar Borrador",
        errorMessages: []
      };
    },
    computed: {
      resultsVisibility() {
        this.productos.length > 0 ? 'show' : 'false'
      },

      total_cantidad() {
        return this.form.detalleProductos.reduce((carry, item) => {
          return (carry + Number(item.cantidad))
        }, 0)
      },
      total_linea() {
        return this.form.detalleProductos.length;
      },
      total_subt() {
        return this.form.detalleProductos.reduce((carry, item) => {
              const subtotal = Number(item.subtotal.toFixed(2));
              this.form.subtotal = carry + Math.abs(subtotal);
              if (!isNaN(this.form.subtotal)) {
                return this.form.subtotal;
              }
              return 0;
            }
            , 0)
      },
      total_subt_me() {
        return this.form.detalleProductos.reduce((carry, item) => {
          this.form.subtotal_me = carry + Number(item.subtotal).toFixed(2);
          if (!isNaN(this.form.subtotal_me)) {
            return this.form.subtotal_me / this.form.t_cambio;
          }
          return 0;
        }, 0);

      },
      total_descuento() {
        if (this.form.porcentaje_descuento > 0) {
          this.form.descuento = Number(Number(this.form.subtotal * Number(this.form.porcentaje_descuento / 100)).toFixed(2));
          // return (carry + Number(Number(item.monto_descuento).toFixed(2)));
          if (!isNaN(this.form.descuento)) {
            return this.form.descuento;
          }
          return this.form.descuento = 0;
        }
        return this.form.descuento = 0;
      },

      total_descuento_me() {
        if (this.form.porcentaje_descuento > 0) {
          this.form.descuento_me = Number(Number((this.form.subtotal * Number(this.form.porcentaje_descuento / 100)) / this.form.t_cambio).toFixed(2));
          if (!isNaN(this.form.descuento_me)) {
            return this.form.descuento_me;
          }
          return 0;
        }
        return 0;
      },
      gran_total() {
        this.form.total = Number(this.form.subtotal - this.form.descuento);
        if (!isNaN(this.form.total)) {
          return this.form.total;
        }
        return 0;

      },

      gran_total_me() {

        this.form.totalme = Number((this.form.subtotal - this.form.descuento) / this.form.t_cambio);
        if (!isNaN(this.form.totalme)) {
          return this.form.totalme;
        }
        return 0;
      },
      calcular_precio_porcentaje() {
        if (this.form.aplica_porcentaje_ganancia) {
          return this.detalleForm.precio_sugerido = Round.round(Number((Number(this.detalleForm.precio_cord) * Number(this.detalleForm.porcentaje_ganancia / 100)) + Number(this.detalleForm.precio_cord)), 2);
        }
        return this.detalleForm.porcentaje_ganancia = Round.round((Number(this.detalleForm.precio_sugerido - this.detalleForm.precio_cord) / this.detalleForm.precio_cord) * 100, 2);
      },
    },
    methods: {
      resetValue() {
        //Reiniciar valores de variables al cambio el status de aplica calculo de porcentaje ganancia
        //para evitar recalculos
        this.detalleForm.porcentaje_ganancia = 0;
        this.detalleForm.precio_sugerido = 0;
      },
      precio_unitario_dol(itemX) {
        itemX.precio_dol = Number(itemX.precio_cord / this.form.t_cambio).toFixed(2);
        if (!isNaN(itemX.precio_dol)) {
          return itemX.precio_dol
        } else return 0
      },
      /*
        Author: omorales (c)
        live search
        14/03/22    */
      /*onSearch() {
        axios.post('inventario/productos/buscar',{
          q: this.query
        }).then( res => {
          this.productos = res.data.results;
        }).catch( err => {
          this.$toast({
              component: ToastificationContent,
              props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: err,
                  variant: 'warning',
              }
          },
          {
              position: 'bottom-right'
          });
        })
      },
      search: _.debounce((loading, search, vm) => {
        const self = this;

        axios.get(`inventario/productos/buscar?q=${escape(this.query)}`).then(res => {
          vm.productos = [];
          vm.productos = res.data.results;
          loading(false);
        })
      }, 350),*/
      seleccionarProducto(e) {
        const productoP = e.target.value;
        const self = this;
        self.detalleForm.productox = productoP;
        this.detalleForm.precio_cord = this.detalleForm.productox.precio_compra;
        this.detalleForm.porcentaje_ganancia = this.detalleForm.productox.porcentaje_ganancia;
        this.detalleForm.precio_sugerido = this.detalleForm.productox.precio_sugerido;
      },

      sub_total(itemX) {
        // itemX.subtotal = Number((Number((itemX.precio_cord) + (Number(itemX.impuesto_cord))) * Number(itemX.cantidad)).toFixed(2));
        itemX.subtotal = Number(((Number(itemX.precio_cord) + Number(itemX.impuesto_cord)) * Number(itemX.cantidad)).toFixed(2));

        // itemX.monto_descuento = Number(Number(itemX.subtotal * Number(itemX.descuento / 100)).toFixed(2));
        itemX.monto_descuento = 0;
        itemX.total = itemX.subtotal - itemX.monto_descuento;
        if (!isNaN(itemX.total)) {
          return itemX.total;
        } else return 0;
      },
      impuestoCord(itemX) {
        if (itemX.aplicaIva) {
          itemX.impuesto_cord = Number(itemX.precio_cord * 15) / 100;
        }
        if (!isNaN(itemX.impuesto_cord)) {
          return itemX.impuesto_cord
        }
        return 0;
      },
      impuestoDol(itemX) {
        if (itemX.aplicaIva) {
          itemX.impuesto_dol = Number(((itemX.precio_cord / this.form.t_cambio) * 15) / 100);
        }
        if (!isNaN(itemX.impuesto_dol)) {
          return itemX.impuesto_dol
        }
        return 0;
      },
      onDateSelect(date) {
        //console.log(date); //
        this.form.f_orden_compra = moment(date).format("YYYY-MM-DD"); //
        this.obtenerTC();
      },
      obtenerTC() {
        const self = this;
        tc.obtenerTC({
          fecha: self.form.fecha_emision
        }, data => {
          if (data.tasa !== null) {
            self.form.t_cambio = Number(data.tasa);
          } else {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'No se encuentran tasas de cambia para esta fecha.',
                    variant: 'warning',

                  }
                },
                {
                  position: 'bottom-right'
                });
            self.form.t_cambio = 0;
          }
          self.loading = false;
        }, err => {
          if (err.fecha) {
            self.form.t_cambio = 0;
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'No se encuentran tasas de cambia para esta fecha.',
                    variant: 'warning',

                  }
                },
                {
                  position: 'bottom-right'
                });
            self.loading = false;
          }
        })
      },

      cargar_detalles_proveedor() {
        var self = this
        if (self.form.proveedor)

          self.form.telefono_proveedor = self.form.proveedor.telefono_contacto;
        self.form.email_proveedor = self.form.proveedor.correo_contacto;
        self.form.atencion = self.form.proveedor.contacto_proveedor;
        self.form.numero_ruc_proveedor = self.form.proveedor.numero_ruc;
        self.form.direccion_proveedor = self.form.proveedor.direccion;

      },
      agregarDetalle() {
        let self = this;
        if (this.detalleForm.productox) {
          if (this.detalleForm.cantidad > 0 && this.detalleForm.precio_cord > 0) {

            let i = 0;
            let keyx = 0;
            if (self.form.detalleProductos) {
              self.form.detalleProductos.forEach((productox, key) => {
                //console.log('ID_PRODUCTO ',self.detalleForm.productox.id_producto);
                if (self.detalleForm.productox.id_producto === productox.productox.id_producto) {
                  i++;
                  keyx = key;
                }
              });
            }

            if (i === 0) {
              this.form.detalleProductos.push({
                productox: this.detalleForm.productox,
                cantidad: this.detalleForm.cantidad,
                precio_dol: Number(this.detalleForm.precio_cord / this.form.t_cambio).toFixed(2),
                precio_cord: this.detalleForm.precio_cord,
                precio_sugerido: this.detalleForm.precio_sugerido,
                porcentaje_ganancia: Round.round(((this.detalleForm.precio_sugerido - this.detalleForm.precio_cord) / this.detalleForm.precio_cord) * 100, 2),
                descuento: 0,
                monto_descuento: 0,
                aplicaIva: this.detalleForm.aplicaIva,
                impuesto_cord: 0,
                impuesto_dol: 0,
                subtotal: 0,
                total: 0,
              });
              this.detalleForm.productox = {};
              this.detalleForm.cantidad = 0;
              this.detalleForm.precio = 0;
              this.detalleForm.precio_dol = Number(this.detalleForm.precio_cord / this.form.t_cambio).toFixed(2);
              this.detalleForm.subtotal = 0;
              this.detalleForm.monto_descuento = 0;
              this.detalleForm.descuento = 0;
              this.detalleForm.total = 0;
              this.detalleForm.precio_sugerido = 0;
              this.detalleForm.aplicaIva = false;

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
                  self.form.detalleProductos[keyx].cantidad = Number(self.form.detalleProductos[keyx].cantidad) + self.detalleForm.cantidad;
                  self.form.detalleProductos[keyx].precio = self.detalleForm.precio;
                  this.detalleForm.productox = {};
                  this.detalleForm.cantidad = 0;
                  this.detalleForm.precio = 0;
                  this.detalleForm.precio_dol = Number(this.detalleForm.precio_cord / this.form.t_cambio).toFixed(2);
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
                    text: 'Los valores para cantidad y precio unitario deben ser mayores a cero.',
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
        if (this.form.detalleProductos.length >= 1) {
          this.form.detalleProductos.splice(index, 1);

        } else {
          this.form.detalleProductos = [];
        }
      },


      nueva() {
        const self = this;
        orden.nueva({}, data => {
              self.proveedores = data.proveedores;
              self.monedas = data.monedas;
              self.bodegas = data.bodegas;
              self.form.t_cambio = Number(data.tasa.tasa);
              self.form.proveedor = self.proveedores[0];
              self.form.bodega = self.bodegas[0];
              self.form.moneda = self.monedas[0];
              self.cargar_detalles_proveedor();
            },
            err => {
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Ha ocurrido un error al cargar los datos',
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            })

      },

      registrar() {
        const self = this;
        self.btnAction = "Registrando, espere....";
        self.btnActionDraft = "Guardando, espere....";
        if (self.form.es_borrador === false) {
          self.$swal.fire({
            title: 'Esta seguro de guardar y emitir la orden de compra?',
            text: "Esta acción no se puede deshacer",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, confirmar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.value) {
              self.loading = true;
              orden.registrar(
                  self.form,
                  data => {
                    this.$toast({
                          component: ToastificationContent,
                          props: {
                            title: 'Notificación',
                            icon: 'CheckIcon',
                            text: 'Orden de compra emitida correctamente.',
                            variant: 'success',
                          }
                        },
                        {
                          position: 'bottom-right'
                        });
                    self.loading = false;
                    this.$router.push({
                      name: "ordenes-compras"
                    });
                  },
                  err => {
                    self.errorMessages = err;
                    self.loading = false;
                    this.$toast({
                          component: ToastificationContent,
                          props: {
                            title: 'Notificación',
                            icon: 'CheckIcon',
                            text: err,
                            variant: 'warning',
                          }
                        },
                        {
                          position: 'bottom-right'
                        });
                    self.btnAction = "Registrar";
                    self.btnActionDraft = "Guardar Borrador";
                  }
              );
            }
          })
        } else {
          self.loading = true;
          orden.registrar(
              self.form,
              data => {
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'CheckIcon',
                        text: 'Orden de compra emitida correctamente.',
                        variant: 'success',
                      }
                    },
                    {
                      position: 'bottom-right'
                    });
                self.loading = false;
                this.$router.push({
                  name: "ordenes-compras"
                });
              },
              err => {
                self.errorMessages = err;
                self.loading = false;
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'CheckIcon',
                        text: err,
                        variant: 'warning',
                      }
                    },
                    {
                      position: 'bottom-right'
                    });
                self.btnAction = "Registrar";
                self.btnActionDraft = "Guardar Borrador";
              }
          );
        }

      }
    },
    mounted() {
      this.nueva();
    }
  };
</script>
<style lang="scss">
    @import '../../../@core/scss/vue/libs/vue-select';

    .btn-agregar {
        margin-top: 33px;
    }


    @import '../../../@core/scss/vue/libs/vue-flatpicker';
</style>
