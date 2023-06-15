<template>
    <b-card>
        <b-row>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Proveedor</label>
                    <v-select :options="proveedores"

                              label="nombre_comercial"
                              v-model="form.orden_compra_proveedor"

                    ></v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.orden_compra_proveedor"
                                v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>RUC Proveedor</label>
                    <template v-if="form.orden_compra_proveedor">
                        <b-form-input class="form-control" type="text"
                                      v-model="form.orden_compra_proveedor.numero_ruc"></b-form-input>
                    </template>
                </div>
            </div>

            <!--<div class="col-sm-4">
                <div class="form-group">
                    <label for>Atención:</label>
                    <b-form-input class="form-control" disabled type="text" v-model="form.atencion"></b-form-input>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.atencion"
                                    v-text="message"
                            ></li>
                        </ul>

                    </b-alert>
                </div>
            </div>-->

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Email Proveedor:</label>
                    <b-form-input class="form-control" type="text"
                                  v-model="form.email_proveedor"></b-form-input>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.email_proveedor"
                                    v-text="message"
                            ></li>
                        </ul>

                    </b-alert>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Télefono Proveedor:</label>
                    <b-form-input class="form-control" type="text"
                                  v-model="form.telefono_proveedor"></b-form-input>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.telefono_proveedor"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>
                </div>
            </div>

            <!--            <div class="col-sm-4">
                            <div class="form-group">
                                <label for>Referencia:</label>
                                <b-form-input class="form-control" disabled type="text"
                                              v-model="form.referencia_solicitud"></b-form-input>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.referencia_solicitud"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for>Area / Departamento:</label>
                                <b-form-input class="form-control" disabled type="text"
                                              v-model="form.area_proveedor"></b-form-input>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.area_proveedor"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for>Solicitud de Compra:</label>
                                <b-form-input class="form-control" disabled type="text"
                                              v-model="form.id_solicitud_compra"></b-form-input>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.id_solicitud_compra"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>-->

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Fecha Orden Compra</label>
                    <flat-pickr
                            @selected="onDateSelect"
                            class="form-control"
                            id="f_compra"
                            v-model="form.f_orden_compra"
                    >

                    </flat-pickr>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.f_orden_compra"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>No. Factura</label>
                    <b-form-input class="form-control" type="text"
                                  v-model="form.numero_factura"></b-form-input>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.numero_factura"
                                    v-text="message"
                            ></li>
                        </ul>

                    </b-alert>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Bodega</label>
                    <v-select
                            :options="bodegas"
                            label="descripcion"
                            v-model="form.orden_compra_bodega"
                    ></v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.orden_compra_bodega"
                                v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Moneda</label>
                    <v-select
                            :options="monedas"
                            label="descripcion"
                            v-model="form.orden_compra_moneda"
                    ></v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.orden_compra_moneda"
                                v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>


        </b-row>

        <!--        <b-alert show variant="success">
                    <div class="alert alert-success">
                        <span><strong>DETALLES DE LA ORDEN</strong></span>
                    </div>
                </b-alert>-->

        <!--        <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for>Dirección</label>
                            <b-form-input class="form-control" disabled type="text" v-model="form.direccion"></b-form-input>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.direccion"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label> Condición Pago:</label>
                            <b-form-select :disabled="form.estado !== 2" @change="cambiarTipoPago" class="form-control"
                                           v-model.number="form.id_condicion_pago">
                                <option value="1">Contado</option>
                                <option value="2">Crédito</option>
                                <option value="3">Consignación</option>
                            </b-form-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.id_condicion_pago"
                                            v-text="message"
                                    ></li>
                                </ul>

                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Días Crédito</label>
                            <b-form-select :disabled="form.estado !== 2" class="form-control"
                                           v-model.number="form.plazo_credito">
                                <option :disabled="form.id_condicion_pago!==1" value=0>Contado</option>
                                <option :disabled="form.id_condicion_pago===1" value=8>Trámite de Cheque (8 días)</option>
                                <option :disabled="form.id_condicion_pago===1" value=15>Crédito (15 días)</option>
                                <option :disabled="form.id_condicion_pago===1" value=30>Crédito (30 días)</option>
                                <option :disabled="form.id_condicion_pago===1" value=45>Crédito (45 días)</option>
                            </b-form-select>

                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.plazo_credito" v-text="message"></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Medio Pago:</label>
                            <b-form-select class="form-control" disabled v-model.number="form.id_medio_pago">
                                <option value="1">Crédito</option>
                                <option value="2">Transferencia</option>
                                <option value="3">Cheque</option>
                                <option value="4">Minuta Depósito</option>
                                <option value="5">Efectivo</option>
                                <option value="6">Tarjeta</option>
                            </b-form-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.id_medio_pago"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for>Condición envío</label>
                            <b-form-select class="form-control" disabled v-model.number="form.condicion_envio">
                                <option value="Envío">Envío</option>
                                <option value="Retiro en tienda"> Retiro en tienda</option>
                            </b-form-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.condicion_envio"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for>Tiempo de Entrega</label>
                            <b-form-input class="form-control" disabled type="text"
                                          v-model.number="form.tiempo_entrega"></b-form-input>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.tiempo_entrega"
                                            v-text="message"
                                    ></li>
                                </ul>

                            </b-alert>
                        </div>
                    </div>


                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for>Nota</label>
                            <b-form-input class="form-control" disabled type="text" v-model="form.nota"></b-form-input>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.nota"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>


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
                                      v-model.number="detalleForm.precio_sugerido" ></b-form-input>
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
            <div class="col-lg-4 form-inline">
                <div class="form-group">
                    <label class="check-label2"><input class="mx-sm-1" type="checkbox" v-model="detalleForm.aplicaIva">
                        Aplica IVA</label>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <button @click="agregarDetalle" class="btn btn-info btn-agregar" ref="agregar">Agregar
                    </button>
                </div>
            </div>

        </div>
        <table class="table table-responsive table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>Producto</th>
                <th>Cantidad {{form.estado === 2||form.estado === 3? 'Facturada': ''}}</th>
                <th>Precio compra C${{form.estado === 2 || form.estado === 3? 'Facturada': ''}}</th>
                <th>Precio compra ${{form.estado === 2 || form.estado === 3? 'Facturada': ''}}</th>
                <th>Precio venta ${{form.estado === 2 || form.estado === 3? 'Facturada': ''}}</th>
                <th>Impuesto C$</th>
                <th>Descuento</th>
                <th>SubTotal</th>


            </tr>
            </thead>
            <tbody>
            <template v-for="(item, index) in form.orden_compra_productos">
                <tr>
                    <td style="width: 2%">
                        <b-button @click="eliminarLinea(item, index)" variant="danger">
                            <feather-icon icon="TrashIcon"></feather-icon>
                        </b-button>
                    </td>
                    <td>
                        {{item.orden_compra_producto.descripcion}}
                        <!-- <b-form-input class="form-control" disabled
                                       v-model="item.orden_compra_producto.descripcion"></b-form-input>-->
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages[`orden_compra_productos.${index}.orden_compra_producto.id_producto`]"
                                        v-text="message">
                                </li>
                            </ul>
                        </b-alert>
                    </td>


                    <td>
                        <b-form-input class="form-control" type="number"
                                      v-model.number="item.cantidad"></b-form-input>
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages[`orden_compra_productos.${index}.cantidad`]"
                                        v-text="message">
                                </li>
                            </ul>
                        </b-alert>
                    </td>


                    <td>
                        <b-form-input class="form-control" type="number"
                                      v-model.number="item.precio_cord"></b-form-input>
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages[`orden_compra_productos.${index}.precio`]"
                                        v-text="message">
                                </li>
                            </ul>
                        </b-alert>
                    </td>
                    <td>
                        {{precio_unitario_dol(item)| formatMoney(2)}}
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages[`orden_compra_productos.${index}.precio_cord`]"
                                        v-text="message">
                                </li>
                            </ul>
                        </b-alert>
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
                        {{impuestoCord(item)| formatMoney(2)}}
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages[`orden_compra_productos.${index}.precio_cord`]"
                                        v-text="message">
                                </li>
                            </ul>
                        </b-alert>
                    </td>


                    <td>
                        <b-form-input class="form-control" disabled type="number"
                                      v-model.number="item.descuento"></b-form-input>
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages[`orden_compra_productos.${index}.descuento`]"
                                        v-text="message">
                                </li>
                            </ul>
                        </b-alert>
                    </td>

                    <td>
                        <strong>C$ {{sub_total(item)| formatMoney(2)}}</strong>
                    </td>
                </tr>
                <tr></tr>
            </template>
            </tbody>
            <tfoot>

            <tr>
                <td colspan="7"></td>
                <td>Subtotal</td>
                <td><strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} {{total_subt |
                    formatMoney(2)}}</strong></td>
            </tr>
            <tr>
                <td colspan="7"></td>
                <td>Descuento</td>
                <b-form-input class="form-control" type="number"
                              v-model.number="form.porcentaje_descuento"></b-form-input>

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
                <td></td>
                <td><strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}}
                    {{total_descuento | formatMoney(2)}}</strong></td>
            </tr>
            <tr>
                <td class="item-footer" colspan="2"> Total Unidades</td>
                <td class="item-footer">
                    <strong>{{total_cantidad}}</strong>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

                <td colspan="">Total</td>
                <td><strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} {{gran_total
                    |formatMoney(2)}}</strong></td>
            </tr>

            </tfoot>
        </table>


        <b-card-footer>

            <b-row>
                <b-col sm="6">
                    <div class="content-box-footer text-left">
                        <router-link :to="{ name: 'ordenes-compras' }">
                            <b-button type="button" variant="secondary">Cancelar</b-button>
                        </router-link>
                    </div>
                </b-col>
                <b-col sm="6">
                    <div class="content-box-footer text-right">
                        <template v-if="form.estado === 99">
                            <b-button
                                    :disabled="btnActionActualizar !== 'Actualizar'"
                                    @click="form.es_borrador=true;actualizar()"
                                    type="button"
                                    variant="primary"
                            >Actualizar
                            </b-button>
                            <b-button
                                    :disabled="btnAction !== 'Confirmar'"
                                    @click="confirmar(4)"
                                    class="mx-1"
                                    type="button"
                                    variant="primary"
                            >Confirmar
                            </b-button>
                            <b-button
                                    :disabled="btnAction !== 'Confirmar'"
                                    @click="confirmar(0)"
                                    type="button"
                                    variant="danger"
                            >Rechazar
                            </b-button>

                        </template>
                    </div>
                </b-col>
            </b-row>

        </b-card-footer>

        <template v-if="loading">
            <BlockUI>
                <b-spinner label="loading..." variant="info"/>
            </BlockUI>
        </template>
    </b-card>
</template>


<script type="text/ecmascript-6">
  import orden from "../../../api/Compras/orden_compras";
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
  import axios from "axios";
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
      flatPickr,
      BCol
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: true,
        productosBusqueda: {},
        productosBusquedaURL: "/inventario/productos/buscar",
        format: "dd-MM-yyyy",
        areas: [],
        proveedores: [],
        productos: [],
        trabajadores: [],
        //sucursales:[],
        monedas: [],
        bodegas: [],

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
          total_descuento: 0,
          total_descuento_me: 0
        },

        form: {
          porcentaje_descuento: 0,
          numero_factura: "",
          observacion: "",
          f_orden_compra: moment(new Date()).format("YYYY-MM-DD"),
          proveedor: "",
          referencia_solicitud: "",
          telefono_proveedor: "",
          email_proveedor: "",
          atencion: "",
          area_proveedor: "",
          total: 0,
          orden_compra_productos: [],
          es_borrador: false,
          detalleProductos: [],
          subtotal: 0,
          subtotal_me: 0,
          descuento: 0,
          descuento_me: 0,
        },
        btnAction: "Confirmar",
        btnActionActualizar: "Actualizar",
        errorMessages: []
      };
    },
    computed: {
      total_cantidad() {
        return this.form.orden_compra_productos.reduce((carry, item) => {

          if (this.form.estado > 2) {
            return (carry + Number(item.cantidad))
          } else {
            return (carry + Number(item.cantidad))
          }


        }, 0)
      },
      total_subt() {
        return this.form.orden_compra_productos.reduce((carry, item) => {
              if (this.form.estado > 2) {
                const subtotal = Number(item.subtotal.toFixed(2));
                this.form.subtotal = carry + Math.abs(subtotal);
                return this.form.subtotal;
              } else {
                const subtotal = Number(item.subtotal.toFixed(2));
                this.form.subtotal = carry + Math.abs(subtotal);
                return this.form.subtotal;
              }
            }
            , 0)
      },
      total_linea() {
        return this.form.orden_compra_productos.length;
      },
      total_descuento() {
        if(this.form.porcentaje_descuento > 0){
          this.form.descuento = Number(Number(this.form.subtotal * Number(this.form.porcentaje_descuento / 100)).toFixed(2));
          // return (carry + Number(Number(item.monto_descuento).toFixed(2)));
          if(!isNaN(this.form.descuento)){
            return this.form.descuento;
          }
          return this.form.descuento = 0;
        }
        return this.form.descuento = 0;

      },


      calcular_impuesto() {
        //console.log(this.form.estado)
        this.form.iva = Number((this.form.orden_compra_productos.reduce((carry, item) => {
              //return(carry + Number(Number(item.subtotal*0.15).toFixed(2)));

              if (this.form.estado > 2) {
                // return (carry + Number(Number(item.subtotal) * Number(this.form.porcentaje_iva / 100)));
                return (carry + Number((Number(item.cantidad * item.precio_cord).toFixed(2)) * (Number(this.form.porcentaje_iva / 100)) * Number(1 - (this.form.descuento / 100)).toFixed(2)));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_cord).toFixed(2)) * (Number(this.form.porcentaje_iva / 100)) * Number(1 - (this.form.descuento / 100)).toFixed(2)));
              }
            }
            , 0)).toFixed(2));

        return Number((this.form.iva).toFixed(2));
      },

      gran_total() {
        this.form.total = Number(this.form.subtotal - this.form.descuento);
        console.log(this.form.subtotal + ' ' + this.form.descuento );
        if(!isNaN(this.form.total)){
          return this.form.total;
        }
        return 0;
      },
    },
    methods: {
      /*
      Author: omorales (c)
      live search
      14/03/22    */
      onSearch(search, loading) {
        if (search.length) {
          loading(true);
          this.search(loading, search, this)
        }
      },
      search: _.debounce((loading, search, vm) => {
        const self = this;
        axios.get(`inventario/productos/buscar?q=${escape(search)}`).then(res => {
          vm.productos = res.data.results;
          loading(false);
        })
      }, 250),
      agregarDetalle() {
        let self = this;
        if (this.detalleForm.productox) {
          if (this.detalleForm.cantidad > 0 && this.detalleForm.precio_cord > 0) {

            let i = 0;
            let keyx = 0;
            if (self.form.orden_compra_productos) {
              self.form.orden_compra_productos.forEach((productox, key) => {
                //console.log('ID_PRODUCTO ',self.detalleForm.productox.id_producto);
                if (self.detalleForm.productox.id_producto === productox.orden_compra_producto.id_producto) {
                  i++;
                  keyx = key;
                }
              });
            }

            if (i === 0) {
              this.form.orden_compra_productos.push({
                orden_compra_producto: this.detalleForm.productox,
                id_producto: this.detalleForm.productox.id_producto,
                cantidad: this.detalleForm.cantidad,
                precio_cord: this.detalleForm.precio_cord,
                precio_dol: Number(this.detalleForm.precio_cord / this.form.t_cambio).toFixed(2),
                precio_sugerido: this.detalleForm.precio_sugerido,
                porcentaje_ganancia: Round.round(( (this.detalleForm.precio_sugerido - this.detalleForm.precio_cord) / this.detalleForm.precio_cord) *100,2 ),
                descuento: 0,
                monto_descuento: Number(0),
                aplicaIva: this.detalleForm.aplicaIva,
                impuesto_cord: Number(0),
                impuesto_dol: Number(0),
                subtotal: 0,
                total: 0,
              });
              this.detalleForm.productox = {};
              this.detalleForm.cantidad = 0;
              this.detalleForm.precio = 0;
              this.detalleForm.precio_dol = 0;
              this.detalleForm.subtotal = 0;
              this.detalleForm.monto_descuento = 0;
              this.detalleForm.descuento = 0;
              this.detalleForm.total = 0;
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
                  self.form.orden_compra_productos[keyx].cantidad = Number(self.form.orden_compra_productos[keyx].cantidad) + self.detalleForm.cantidad;
                  self.form.orden_compra_productos[keyx].precio = self.detalleForm.precio;
                  this.detalleForm.productox = {};
                  this.detalleForm.cantidad = 0;
                  this.detalleForm.precio = 0;
                  this.detalleForm.precio_dol = 0;
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
      cambiarTipoPago() {
        let self = this;
        if (self.form.id_condicion_pago === 1) {
          self.form.plazo_credito = 0;
        } else {
          self.form.plazo_credito = 8;
        }
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
          itemX.impuesto_dol = Number(((itemX.precio_dol * 15) / 100));
          if (!isNaN(itemX.impuesto_dol)) {
            return itemX.impuesto_dol
          }
          return 0;
        }
      },
      onDateSelect(date) {
        //console.log(date); //
        this.form.f_orden_compra = moment(date).format("YYYY-MM-DD"); //
      },
      confirmar(estadox) {
        let titulox = '';
        if (estadox === 0) {
          titulox = 'Esta seguro de rechazar ésta orden de compra?';
        } else if (estadox === 2) {
          titulox = 'Esta seguro de cambiar el estado a Ordenado?';
        } else if (estadox === 3) {
          titulox = 'Esta seguro de cambiar el estado a Facturado?';
        } else if (estadox === 4) {
          titulox = 'Esta seguro de cambiar el estado a Enviado?';
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
      actualizar() {
        const self = this;
        if (self.form.estado === 99) {
          self.btnActionActualizar = "Registrando, espere....";
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
                orden.actualizar(
                    self.form,
                    data => {
                      if (self.form.es_borrador) {
                        this.$toast({
                              component: ToastificationContent,
                              props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Orden de compra actualizada correctamente',
                                variant: 'success',
                              }
                            },
                            {
                              position: 'bottom-right'
                            });
                      } else {
                        this.$toast({
                              component: ToastificationContent,
                              props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Orden de compra actualizada correctamente',
                                variant: 'success',
                              }
                            },
                            {
                              position: 'bottom-right'
                            });
                      }
                      this.$router.push({
                        name: "ordenes-compras"
                      });
                    },
                    err => {
                      self.errorMessages = err;
                      self.btnActionActualizar = "Actualizar";
                    }
                );
              } else {
                self.btnActionActualizar = "Actualizar";
              }
            })
          } else {

            orden.actualizar(
                self.form,
                data => {
                  if (self.form.es_borrador) {
                    this.$toast({
                          component: ToastificationContent,
                          props: {
                            title: 'Notificación',
                            icon: 'CheckIcon',
                            text: 'La orden de compra fue actualizada correctamente.',
                            variant: 'success',
                          }
                        },
                        {
                          position: 'bottom-right'
                        });
                  } else {
                    this.$toast({
                          component: ToastificationContent,
                          props: {
                            title: 'Notificación',
                            icon: 'CheckIcon',
                            text: 'La orden de compra fue actualizada correctamente.',
                            variant: 'success',
                          }
                        },
                        {
                          position: 'bottom-right'
                        });
                  }
                  this.$router.push({
                    name: "ordenes-compras"
                  });
                },
                err => {
                  self.errorMessages = err;
                  self.btnAction = "Actualizar";
                }
            );
          }
        } else {
          self.btnActionActualizar = "Actualizar";
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'La orden ya no puede ser actualizada.',
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
        }
      },

      seleccionarProducto(e) {
        const productoP = e.target.value;
        const self = this;
        self.detalleForm.productox = productoP;
        if (this.detalleForm.productox)
          this.detalleForm.precio_cord = this.detalleForm.productox.precio_compra;
          this.detalleForm.precio_sugerido = this.detalleForm.productox.precio_sugerido;

      },
      obtenerOrdenCompra() {
        var self = this;
        self.loading = true;
        orden.obtenerOrdenCompra({
          id_orden_compra: this.$route.params.id_orden_compra,
          cargar_dependencias: true,
        }, data => {

          self.proveedores = data.proveedores;
          self.form = data.orden;
          self.form.porcentaje_descuento = Number(data.orden.porcentaje_descuento);
          self.monedas = data.monedas;
          self.bodegas = data.bodegas;
          self.form.t_cambio = Number(data.tasa.tasa);
          self.loading = false;

          if (self.form.estado === 4 || self.form.estado === 0) {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'La orden ya no puede ser actualizada',
                    variant: 'warning',
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: "ordenes-compras"
            });
            self.loading = false;
          }
        })
      },
      sub_total(itemX) {
        // itemX.subtotal = Number((Number((itemX.precio_cord) + (Number(itemX.impuesto_cord))) * Number(itemX.cantidad)).toFixed(2));
        itemX.subtotal = Number( ( (Number(itemX.precio_cord) + Number(itemX.impuesto_cord)) * Number(itemX.cantidad) ).toFixed(2) );

        // itemX.monto_descuento = Number(Number(itemX.subtotal * Number(itemX.descuento / 100)).toFixed(2));
        itemX.monto_descuento = 0;
        itemX.total = itemX.subtotal - itemX.monto_descuento;
        if (!isNaN(itemX.total)) {
          return itemX.total;
        } else return 0;
      },
      precio_unitario_dol(itemX) {
        itemX.precio_dol = Number(itemX.precio_cord / this.form.t_cambio).toFixed(2);
        if (!isNaN(itemX.precio_dol)) {
          return itemX.precio_dol
        } else return 0
      },
      eliminarLinea(item, index) {
        if (this.form.orden_compra_productos.length >= 1) {
          this.form.orden_compra_productos.splice(index, 1);

        } else {
          this.form.orden_compra_productos = [];
        }
      },


      cambiarEstado(estadox) {
        const self = this;
        if ((self.form.estado === 1 && (estadox === 2 || estadox === 0)) ||
            (self.form.estado === 2 && (estadox === 3 || estadox === 0) ||
                (self.form.estado === 99 && (estadox === 4 || estadox === 0)))) {
          self.btnAction = "Registrando, espere....";

          orden.cambiarEstado({
                id_orden_compra: this.$route.params.id_orden_compra,
                productos: self.form.orden_compra_productos,
                porcentaje_iva: Number(self.form.porcentaje_iva),
                total: Number(self.form.total),
                plazo_credito: Number(self.form.plazo_credito),
                id_condicion_pago: Number(self.form.id_condicion_pago),
                /*     iva: Number(self.form.iva.toFixed(2)),*/
                numero_factura: self.form.numero_factura,
                estado: estadox
              },
              data => {
                if (estadox === 2) {
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'El estado de la orden de compra ha cambiado a Ordenado',
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
                          text: 'El estado de la orden de compra ha cambiado a facturado.',
                          variant: 'success',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                } else if (estadox === 4) {
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'El estado de la orden de compra ha cambiado a Enviado.',
                          variant: 'success',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                }
                this.$router.push({
                  /*name: "mostrar-orden-compra",*/
                  name: "ordenes-compras"
                  /*params: {id_orden_compra: self.form.id_orden_compra}*/
                });
              },
              err => {
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
                self.errorMessages = err;
                self.btnAction = "Confirmar";
              }
          );
        } else {
          self.btnAction = "Confirmar";
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'La orden de compra no puede ser actualizada',
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
      this.obtenerOrdenCompra();
    }
  };
</script>
<style lang="scss">
    .btn-agregar {
        margin-top: 33px;
    }

    @import '../../../@core/scss/vue/libs/vue-flatpicker';
</style>
