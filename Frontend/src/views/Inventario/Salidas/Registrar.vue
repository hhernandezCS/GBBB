<template>
    <b-card>
        <b-row>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Tipo salida</label>
                    <v-select
                            :options="tipos_salida"
                            label="descripcion"
                            placeholder="Selecciona un tipo de salida"
                            v-model="form.tipo_salida"
                    >
                        <template slot="no-options">No se encontraron registros.</template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.tipo_salida"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <!--  <div class="col-sm-4">
                <div class="form-group">
                  <label for>Código salida <small>(Automático)</small></label>
                  <input class="form-control" type="text" v-model="form.codigo_salida">
                  <ul class="error-messages">
                    <li
                            :key="message"
                            v-for="message in errorMessages.codigo_salida"
                            v-text="message"
                    ></li>
                  </ul>
                </div>
              </div>-->

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Bodega</label>
                    <v-select
                            :options="bodegas"
                            label="descripcion"
                            placeholder="Selecciona una bodega"
                            v-model="form.bodega"
                            v-on:input="obtenerProductosBodega()"
                    >
                        <template slot="no-options">No se encontraron registros</template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.bodega" v-text="message"></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <!--<div class="col-sm-4">
                <div class="form-group">
                    <label for>Proveedor</label>
                    <v-select
                            :options="proveedores"
                            label="nombre_comercial"
                            v-model="form.proveedor"
                    ></v-select>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.proveedor" v-text="message"></li>
                    </ul>
                </div>
            </div>-->

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Fecha salida</label>

                    <b-form-datepicker
                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                            @selected="onDateSelect"
                            class="mb-0"
                            local="es"
                            placeholder="f.salida"
                            selected-variant="primary"
                            v-model="fechax"
                    >

                    </b-form-datepicker>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.fecha_salida"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Número documento de salida</label>
                    <input class="form-control" type="text" v-model="form.numero_documento">
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.numero_documento"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>
            <div class="col-sm-9">
                <div class="form-group">
                    <label for>Observaciones salida</label>
                    <input class="form-control" type="text" v-model="form.descripcion_salida">
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.descripcion_salida"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

        </b-row>

        <div v-if="!form.bodega">
            <b-alert show variant="success">
                <div class="alert alert-info">
                    <span>Se requiere que seleccione una bodega para continuar.</span>
                </div>
            </b-alert>

            <hr>
        </div>

        <b-alert class="mt-2 mb-2" show variant="success">
            <div class="alert-body">
                <span><strong>Detalle de la salida</strong></span>
            </div>
        </b-alert>

        <b-row>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for>Producto</label>
                    <typeahead :initial="detalleForm.productox" :params="{id_bodega: form.bodega.id_bodega}"
                               :url="productosBodegaBusquedaURL" @input="cargar_detalles_producto"
                               style="width: 100%;"></typeahead>

                    <b-alert show variant="danger">
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

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Cantidad</label>
                    <input @change="detalleForm.cantidad = Math.max(Math.min(detalleForm.cantidad, detalleForm.productox.cantidad_disponible), 0)"
                           @keydown.enter="cambiarFocoCantidad" class="form-control" ref="cantidad" type="number"
                           v-model.number="detalleForm.cantidad">
                    <b-alert show variant="danger">
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


            <!--<div class="col-sm-2">
              <div class="form-group">
                <label for>Costo Promedio</label>
                <div class="input-group">
                  <div class="input-group-addon">C$
                  </div>
                  <input class="form-control" readonly type="number" v-model.number="detalleForm.precio_unitario">
                </div>

                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.precio_unitariox"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>-->

            <div class="col-sm-3">
                <label for=""></label>
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <b-button @click="agregarDetalle" ref="agregar" variant="info">Agregar Producto
                    </b-button>
                </div>
            </div>

        </b-row>

        <b-row>
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

                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <!--  <th >Costo Unitario</th>

                               <th >SubTotal</th>-->

                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(item, index) in form.detalleProductos">
                            <tr>
                                <td style="width: 2%">
                                    <b-button @click="eliminarLinea(item, index)" v-b-tooltip.hover.top="'Eliminar fila'"
                                              variant="danger">
                                        <feather-icon icon="TrashIcon"></feather-icon>
                                    </b-button>
                                </td>
                                <td>
                                    <input class="form-control" disabled v-model="item.productox.descripcion">
                                    <b-alert show variant="danger">
                                        <ul class="error-messages">
                                            <li
                                                    :key="message"
                                                    v-for="message in errorMessages[`detalleProductos.${index}.productox.id_producto`]"
                                                    v-text="message">
                                            </li>
                                        </ul>
                                    </b-alert>

                                </td>


                                <td>
                                    <input @change="item.cantidad = Math.max(Math.min(Math.round(item.cantidad), item.productox.cantidad_disponible), 1)"
                                           class="form-control" type="number" v-model.number="item.cantidad">
                                    <b-alert show variant="danger">
                                        <ul class="error-messages">
                                            <li
                                                    :key="message"
                                                    v-for="message in errorMessages[`detalleProductos.${index}.cantidad`]"
                                                    v-text="message">
                                            </li>
                                        </ul>
                                    </b-alert>

                                </td>


                                <!-- <td>
                                   <input class="form-control" readonly type="number" v-model.number="item.precio_unitario">
                                   <ul class="error-messages">
                                     <li
                                             :key="message"
                                             v-for="message in errorMessages[`detalleProductos.${index}.precio_unitario`]"
                                             v-text="message">
                                     </li>
                                   </ul>
                                 </td>-->

                                <!--<td>
                                     <strong>C$ {{sub_total(item)| formatMoney(2)}}</strong>
                                   </td>-->


                            </tr>
                            <tr></tr>
                        </template>
                        </tbody>
                        <tfoot>

                        <tr>
                            <td colspan="3"></td>
                            <!--<td>Subtotal</td>
                            <td> <strong>C$ {{total_subt | formatMoney(2)}}</strong></td>-->
                        </tr>
                        <tr>
                            <td class="item-footer" colspan="2"> Total Unidades</td>
                            <td class="item-footer">
                                <strong>{{total_cantidad}}</strong>
                            </td>
                            <!-- <td>Total</td>
                             <td> <strong>C$ {{gran_total | formatMoney(2)}}</strong></td>-->
                        </tr>

                        </tfoot>
                    </table>
                </div>

            </div>
        </b-row>

        <b-card-footer class="content-box-footer text-lg-right text-center">
            <router-link :to="{ name: 'inventario-salidas' }">
                <b-button class="mx-1" type="button" variant="secondary">Cancelar</b-button>
            </router-link>
            <b-button
                    :disabled="btnAction != 'Registrar' ? true : false"
                    @click="registrar"
                    type="button"
                    variant="primary"
            >{{ btnAction }}
            </b-button>
        </b-card-footer>
        <template v-if="loading">
            <BlockUI :url="url"></BlockUI>
        </template>

    </b-card>
</template>

<script type="text/ecmascript-6">
  import bodega from "../../../api/Inventario/bodegas";
  import salida from "../../../api/Inventario/salidas";
  //import Datepicker from "vuejs-datepicker";
  import es from 'vuejs-datepicker/dist/locale/translations/es'
  import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import {
    BAlert,
    BButton,
    BCard,
    BCardFooter,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormGroup,
    BPaginationNav,
    BRow,
    VBTooltip,
  } from 'bootstrap-vue'
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import vSelect from 'vue-select'

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
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: false,
        url: loadingImage,   //It is important to import the loading image then use here
        es: es,
        format: "dd-MM-yyyy",
        bodegas: [],
        proveedores: [],
        tipos_salida: [],
        productos: [],
        fechax: new Date(),
        productosBodegaBusquedaURL: "/bodegas/obtener-bodegas-productos-venta",

        detalleForm: {
          productox: {text: 'Escanea el codigo de barras o escribe para buscar un producto'},
          cantidad: 1,
          precio_unitario: 0,
          subtotal: 0,
          total: 0,
        },

        form: {
          codigo_salida: "",
          descripcion_salida: "",
          fecha_salida: moment(new Date()).format("YYYY-MM-DD"),
          tipo_salida: "",
          proveedor: "",
          bodega: "",
          numero_documento: '',
          detalleProductos: []
        },
        btnAction: "Registrar",
        errorMessages: []
      };
    },
    computed: {

      total_cantidad() {
        return this.form.detalleProductos.reduce((carry, item) => {
          return (carry + Number(item.cantidad))
        }, 0)
      },
      total_subt() {
        return this.form.detalleProductos.reduce((carry, item) => {
              return (carry + Number(item.subtotal.toFixed(2)));
            }
            , 0)
      },
      gran_total() {
        return this.form.detalleProductos.reduce((carry, item) => {
              return (carry + Number(item.total.toFixed(2)));
            }
            , 0)
      },
    },
    methods: {
      cambiarFocoCantidad() {
        var self = this;
        self.$refs.agregar.focus()
      },
      sub_total(itemX) {
        itemX.subtotal = Number((Number(itemX.precio_unitario) * Number(itemX.cantidad)).toFixed(2));
        itemX.total = itemX.subtotal;
        if (!isNaN(itemX.total)) {
          return itemX.total;
        } else return 0;
      },
      cargar_detalles_producto(e) {
        const productoP = e.target.value;
        const self = this;
        self.detalleForm.productox = productoP;
        if (self.detalleForm.productox)
          self.detalleForm.cantidad = 1;
        self.detalleForm.precio_unitario = self.detalleForm.productox.costo_promedio;
        self.$refs.cantidad.focus();
      },
      onDateSelect(date) {
        this.form.fecha_salida = moment(date).format("YYYY-MM-DD"); //
      },
      obtenerTodasBodegasProductos() {
        const self = this;
        bodega.obtenerTodasBodegasProductos(
            data => {
              self.bodegas = data.bodegas;
              self.form.bodega = self.bodegas[0];

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
            }
        );
      },

      agregarDetalle() {
        const self = this;
        if (self.detalleForm.productox) {
          if (self.detalleForm.cantidad > 0 /*&& self.detalleForm.precio_unitario > 0*/) {
            let i = 0;
            let keyx = 0;
            if (self.form.detalleProductos) {
              self.form.detalleProductos.forEach((productox, key) => {
                if (self.detalleForm.productox.id_bodega_producto === productox.productox.id_bodega_producto) {
                  i++;
                  keyx = key;
                }
              });
            }
            if (i === 0) {
              self.form.detalleProductos.push({
                productox: self.detalleForm.productox,
                cantidad: self.detalleForm.cantidad,
                precio_unitario: self.detalleForm.precio_unitario,
                subtotal: 0,
                total: 0,
              });

            } else {
              let nuevo_total = self.form.detalleProductos[keyx].cantidad + self.detalleForm.cantidad;
              if (nuevo_total <= self.form.detalleProductos[keyx].productox.cantidad_disponible) {
                self.form.detalleProductos[keyx].cantidad = nuevo_total;
              } else {
                self.form.detalleProductos[keyx].cantidad = self.form.detalleProductos[keyx].productox.cantidad_disponible;
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: 'Notificación',
                        icon: 'CheckIcon',
                        text: 'La cantidad se ha actualizado a la máxima disponible de este producto.',
                        variant: 'warning',
                    }
                },
                {
                    position: 'bottom-right'
                });
              }
            }

            self.detalleForm.productox = {text: 'Escanea el codigo de barras o escribe para buscar un producto'};
            self.detalleForm.cantidad = 0;
            self.detalleForm.precio_unitario = 0;
            self.detalleForm.subtotal = 0;
            self.detalleForm.total = 0;
            this.$refs.producto.$refs.search.focus();

          } else {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Los valores para cantidad y precio unitario deber ser mayores a cero.',
                    variant: 'danger',
                    position: 'bottom-right'
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
                  text: 'Debe seleccionar un producto.',
                  variant: 'danger',
                  position: 'bottom-right'
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

      registrar() {
        const self = this;
        self.btnAction = "Registrando, espere....";

        self.$swal.fire({
          title: 'Esta seguro de guardar la salida?',
          text: "Es posible actualizar la salida luego de guardar",
          icon: 'warning',
          showCancelButton: true,
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger ml-1',
          },
          confirmButtonText: 'Si, confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            self.loading = true;
            salida.registrar(
                self.form,
                data => {
                  self.loading = false;
                  this.$swal.fire(
                      'Salida Registrada!',
                      'La salida fue actualizada correctamente.',
                      'success'
                  );
                  this.$router.push({
                    name: "inventario-salidas"
                  });
                },
                err => {
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
                  self.errorMessages = err;
                  self.btnAction = "Registrar";
                }
            );
          } else {
            this.loading = false;
            self.btnAction = "Registrar";
          }
        });

      },

      nueva() {
        const self = this;
        self.loading = true;
        salida.nueva({}, data => {
              self.tipos_salida = data.tipos_salida;
              self.form.tipo_salida = self.tipos_salida[0];
              //self.proveedores = data.proveedores;
              //self.form.proveedor = self.proveedores[0];

              self.bodegas = data.bodegas;
              self.form.bodega = self.bodegas[0];

              self.loading = false;

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
            })

      },


    },
    mounted() {
      this.nueva()

    }
  };
</script>
<style lang="scss">
    @import '../../../@core/scss/vue/libs/vue-select';

    .btn-agregar {
        margin-top: 33px;
    }
</style>
