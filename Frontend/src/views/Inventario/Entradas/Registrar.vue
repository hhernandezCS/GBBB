<template>
    <b-card>
        <b-row>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Tipo entrada</label>
                    <v-select
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="tipos_entrada"
                            label="descripcion"
                            placeholder="Selecciona un tipo de entrada"
                            v-model="form.tipo_entrada"
                    >
                        <template slot="no-options">
                            No se han encontrado registros
                        </template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.tipo_entrada"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <!--  <div class="col-sm-4">
                <div class="form-group">
                  <label for>Código entrada <small>(Automático)</small></label>
                  <input class="form-control" type="text" v-model="form.codigo_entrada">
                  <ul class="error-messages">
                    <li
                            :key="message"
                            v-for="message in errorMessages.codigo_entrada"
                            v-text="message"
                    ></li>
                  </ul>
                </div>
              </div>-->

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Bodega</label>
                    <v-select
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="bodegas"
                            label="descripcion"
                            placeholder="Selecciona una bodega"
                            v-model="form.bodega"
                    >
                        <template slot="no-options">
                            No se han encontrado registros
                        </template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.bodega" v-text="message"></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Proveedor</label>
                    <v-select
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="proveedores"
                            @input="mapProveedores"
                            label="nombre_comercial"
                            multiple
                            placeholder="Selecciona un proveedor"
                            v-model="form.proveedor"
                    >
                        <template slot="no-options">
                            No se han encontrado registros
                        </template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.proveedor" v-text="message"></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Fecha entrada</label>
                    <b-form-datepicker
                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                            @input="onDateSelect()"
                            class="mb-0"
                            local="es"
                            placeholder="f.entrada"
                            selected-variant="primary"
                            v-model="form.fecha_entrada_x"
                    >
                    </b-form-datepicker>

                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.fecha_entrada"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Número documento de entrada</label>
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
                    <label for>Observaciones entrada</label>
                    <input class="form-control" type="text" v-model="form.descripcion_entrada">
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.descripcion_entrada"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

        </b-row>

        <div v-if="!form.bodega">
            <b-alert class="mt-2 mb-2" show variant="success">
                <div class="alert-body">
                    <span>Se requiere que seleccione una bodega para continuar.</span>
                </div>

            </b-alert>
            <hr>
        </div>

        <div class="alert alert-success">
            <b-alert class="mt-2 mb-2" show variant="success">
                <div class="alert-body">
                    <span>Detalle de la entrada.</span>
                </div>

            </b-alert>
        </div>

        <b-row>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Producto:</label>
<!--                    <v-select :filterable="false"
                              :options="productos"
                              @input="cargar_detalles_producto"
                              @search="onSearch"
                              deselect-label="No se puede eliminar este valor"
                              label="descripcion"
                              placeholder="Selecciona un producto"
                              ref="producto"
                              track-by="id_producto"
                              v-model="detalleForm.productox"
                    >
                        <template slot="no-options">
                            Escriba para buscar
                        </template>
                    </v-select>-->

                    <typeahead :initial="detalleForm.productox" :trim="80" :url="productosBusquedaURL"
                               @input="cargar_detalles_producto" style="width: 100%;"></typeahead>
                </div>
            </div>


            <div class="col-sm-2">
                <div class="form-group">
                    <label for>Cantidad</label>
                    <input @keydown.enter="cambiarFocoCantidad" class="form-control" min="0"
                           ref="cantidad" type="number" v-model.number="detalleForm.cantidad">
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


            <div class="col-sm-3">
                <div class="form-group">
                    <label for>Precio Compra</label>
                    <div class="input-group">
                        <input @keydown.enter="cambiarFocoPrecioUnitario" class="form-control" min="0"
                               ref="precio_unitario" type="number"
                               v-model.number="detalleForm.precio_unitario">
                    </div>

                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.precio_unitariox"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-3">
                <label for=""></label>
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <b-button @click="agregarDetalle" ref="agregar" variant="info">Agregar
                        Producto
                    </b-button>

                </div>
            </div>

        </b-row>

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
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <!--<th>Precio Unitario</th>
                        <th>SubTotal</th>-->

                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.detalleProductos">
                        <tr>
                            <td style="width: 2%">
                                <b-button @click="eliminarLinea(item, index)" v-b-tooltip.hover.top="'Eliminar línea!'"
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
                                <input class="form-control" min="0" type="number" v-model.number="item.cantidad">
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

                            <td class="text-center">
                                <div v-if="item.registrada">
                                    <feather-icon aria-hidden="true" icon="CheckIcon"
                                                  style="color: green" v-b-tooltip.hover.top="'Producto registrado'"> Guardado
                                    </feather-icon>
                                </div>
                                <div v-else>
                                    <!--<i @click="registrarBateria(item)" v-tooltip="'Registrar bateria manualmente'"
                                       aria-hidden="true" class="fa fa-save" :style="'color:blue;'"></i>-->
                                    <feather-icon @click="autosave(item)"
                                                  aria-hidden="true"
                                                  icon="SaveIcon" style="color:blue; cursor: pointer"
                                                  v-b-tooltip.hover.top="'Guardar producto manualmente'">
                                        {{item.guardadoEnProgreso?'Guardando....':' Pendiente'}}
                                    </feather-icon>
                                </div>
                            </td>


                            <!--<td>
                                <input class="form-control" type="number" min="0"
                                       v-model.number="item.precio_unitario">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages[`detalleProductos.${index}.precio_unitario`]"
                                            v-text="message">
                                    </li>
                                </ul>
                            </td>

                            <td>
                                <strong>C$ {{sub_total(item)| formatMoney(2)}}</strong>
                            </td>-->


                        </tr>
                        <tr></tr>
                    </template>
                    </tbody>
                    <tfoot>

                    <!--<tr>
                        <td colspan="3"></td>
                        <td>Subtotal</td>
                        <td><strong>C$ {{total_subt | formatMoney(2)}}</strong></td>
                    </tr>-->
                    <tr>
                        <td class="item-footer" colspan="2"> Total Unidades</td>
                        <td class="item-footer">
                            <strong>{{total_cantidad}}</strong>
                        </td>
                        <!--<td>Total</td>
                        <td><strong>C$ {{gran_total | formatMoney(2)}}</strong></td>-->
                    </tr>

                    </tfoot>
                </table>
            </div>
        </div>

        <b-card-footer class="text-lg-right text-center ">
            <router-link :to="{ name: 'inventario-entradas' }">
                <b-button class="mx-1 mt-sm-1" type="button" variant="secondary">Cancelar</b-button>
            </router-link>
            <!--            <b-button-->
            <!--                    :disabled="btnActionDraft !== 'Guardar Borrador'"-->
            <!--                    @click="form.es_borrador=true;registrar()"-->
            <!--                    variant="dark"-->
            <!--                    type="button"-->
            <!--                    class="mx-1 mt-sm-1"-->
            <!--            >{{ btnActionDraft }}-->
            <!--            </b-button>-->
            <b-button
                    :disabled="btnAction !== 'Registrar'"
                    @click="form.es_borrador=false;registrar()"
                    class="mx-1 mt-sm-1"
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
  import tipo from "../../../api/Inventario/tipos_entradas";
  import tc from '../../../api/contabilidad/tasas-cambio'
  //
  import proveedor from "../../../api/Inventario/proveedores";
  import entrada from "../../../api/Inventario/entradas";
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
  import axios from "axios";


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
        registrandoProducto: false,
        total_pendientes: 0,
        url: loadingImage,   //It is important to import the loading image then use here
        es: es,
        format: "dd-MM-yyyy",
        bodegas: [],
        proveedores: [],
        tipos_entrada: [],
        productos: [],
        productosBusquedaURL: "/inventario/productos/buscar",


        detalleForm: {
          productox: {text: 'Escanea el codigo de barras o escribe para buscar un producto'},
          cantidad: 1,
          precio_unitario: 0,
          subtotal: 0,
          total: 0,
        },
        consolidadoProductos: [],

        form: {
          codigo_entrada: "",
          descripcion_entrada: "",
          fecha_entrada: moment(new Date()).format("YYYY-MM-DD"),
          fecha_entrada_x: new Date(),
          tipo_entrada: "",
          proveedor: "",
          bodega: "",
          detalleProductos: [],
          es_borrador: false,
          numero_documento: '',
          id_entrada: '',
          mapProveedor: '',
          t_cambio: 0
        },
        btnAction: "Registrar",
        btnActionDraft: "Guardar Borrador",
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

      mapProveedores() {
        return this.form.mapProveedor = this.form.proveedor.map((proveedor) => [proveedor.id_proveedor]).join(",");
      },


      labelproducto(item) {
        let cod_producto = item.codigo_barra == null ? 'N/D' : item.codigo_barra;
        // return `${item.descripcion} - ${cod_barra}`
        return `( ${cod_producto}) - ${item.descripcion}`
      },

      cambiarFocoAgregar() {
        var self = this;
        this.$refs.producto.$refs.search.focus();
      },
      cambiarFocoCantidad() {
        var self = this;
        self.$refs.precio_unitario.focus();
      },
      cambiarFocoPrecioUnitario() {
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
        if (self.detalleForm.productox) {
          self.detalleForm.precio_unitario = self.detalleForm.productox.precio_compra;
          self.$refs.cantidad.focus();
        } else {
          console.log('producto no valido');
        }
      },
      onDateSelect(date) {
        this.form.fecha_entrada = this.form.fecha_entrada_x; //

        this.obtenerTC();
      },
      obtenerTodosProveedores() {
        const self = this;
        self.loading = true;
        proveedor.obtenerTodosProveedores(
            data => {
              self.proveedores = data;
              /*self.form.proveedor = self.proveedores[0];*/
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'InfoIcon',
                      text: 'Ha ocurrido un error al cargar los datos.',
                      variant: 'danger',
                      position: 'bottom-right'
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
              console.log(err);
            }
        );
      },
      obtenerTodasBodegas() {
        const self = this;
        self.loading = true;
        bodega.obtenerTodasBodegas(
            data => {
              self.bodegas = data;
              self.form.bodega = self.bodegas[0];
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'InfoIcon',
                      text: 'Ha ocurrido un error al cargar los datos.',
                      variant: 'danger',
                      position: 'bottom-right'
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
              console.log(err);
            }
        );
      },
      obtenerTodosTiposEntrada() {
        const self = this;
        self.loading = true;
        tipo.obtenerTodosTiposEntrada(
            data => {
              self.tipos_entrada = data;
              self.form.tipo_entrada = self.tipos_entrada[0];
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'InfoIcon',
                      text: 'Ha ocurrido un error al cargar los datos.' + err,
                      variant: 'danger',
                      position: 'bottom-right'
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
              // console.log(err);
            }
        );
      },

      agregarDetalle() {
        let self = this;
        if (this.detalleForm.productox) {
          if (this.detalleForm.cantidad > 0 && this.detalleForm.precio_unitario > 0) {

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
              this.form.detalleProductos.unshift({
                productox: this.detalleForm.productox,
                cantidad: this.detalleForm.cantidad,
                precio_unitario: this.detalleForm.precio_unitario,
                precio_unitario_me: Number(this.detalleForm.precio_unitario / self.form.t_cambio).toFixed(4),
                subtotal: 0,
                total: 0,
                registrada: false,
                guardadoEnProgreso: false,
                estado: 1,
                id_entrada_producto: null
              });
              //this.$refs.bateria.$el.selectedValue = null;
              //this.$refs.bateria.$el.focus();

              this.detalleForm.productox = {text: 'Escanea el codigo de barras o escribe para buscar un producto'};
              this.detalleForm.cantidad = 0;
              this.detalleForm.precio_unitario = 0;
              this.detalleForm.subtotal = 0;
              this.detalleForm.total = 0;


              this.$refs.producto.$refs.search.focus();

            } else {
              this.$swal.fire({
                title: 'El producto ya existe en el detalle, desea sumar la nueva cantidad al monto existente?',
                text: "También se sobreescribirá el costo unitario",
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
                  self.form.detalleProductos[keyx].cantidad = self.form.detalleProductos[keyx].cantidad + self.detalleForm.cantidad;
                  self.form.detalleProductos[keyx].precio_unitario = self.detalleForm.precio_unitario;
                  this.detalleForm.productox = '';
                  this.detalleForm.cantidad = 0;
                  this.detalleForm.precio_unitario = 0;
                  this.detalleForm.subtotal = 0;
                  this.detalleForm.total = 0;
                  this.$refs.producto.$refs.search.focus();
                }
              })
            }

            if (self.cantidad_sin_registrar() >= 3) {
              self.form.detalleProductos.forEach((productox, key) => {
                if (!productox.registrada) {
                  self.autosave(productox)
                }
              });
            }
            self.cantidad_sin_registrar();
            // self.agruparProductos();

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
          if (this.form.detalleProductos[index].id_entrada_producto > 0) {
            this.form.detalleProductos[index].estado = 0;
            this.form.detalleProductos[index].registrada = false;
          } else {
            this.form.detalleProductos.splice(index, 1);
          }

        } else {
          this.form.detalleProductos = [];
        }
      },

      registrar() {
        const self = this;
        self.btnAction = "Registrando, espere....";
        self.btnActionDraft = "Guardando, espere....";

        if (self.form.es_borrador === false) {
          self.$swal.fire({
            title: 'Esta seguro de guardar la entrada?',
            text: "Es posible actualizar la entrada luego de guardar",
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
              entrada.registrar(
                  self.form,
                  data => {
                    self.loading = false;
                    // alertify.success("Entrada registrada correctamente",5);
                    this.$swal.fire(
                        'Entrada Registrada!',
                        'La Entrada fue actualizada correctamente.',
                        'success'
                    );
                    this.$router.push({
                      name: "inventario-entradas"
                    });
                  },
                  err => {
                    self.btnAction = "Registrar";
                    self.btnActionDraft = "Guardar Borrador";
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
                  }
              );
            } else {
              self.loading = false;
              self.btnAction = "Registrar";
              self.btnActionDraft = "Guardar Borrador";
            }
          })
        } /*else {

                    entrada.registrar(
                        self.form,
                        data => {
                            self.loading = false;
                            // alertify.success("Entrada registrada correctamente",5);
                            this.$swal.fire(
                                'Borrador de Entrada Registrado!',
                                data.messages,
                                'success'
                            )
                            this.$router.push({
                                name: "inventario-entradas"
                            });
                        },
                        err => {
                            self.loading = false;
                            self.btnAction = "Emitir";
                            self.btnActionDraft = "Guardar Borrador";
                            this.$swal.fire(
                                'Ha ocurrido un error!',
                                err.messages,
                                'warning'
                            );
                            self.errorMessages = err.result;
                        }
                    );
                }*/
      },
      nuevo() {
        var self = this;
        self.loading = true;
        entrada.nuevo({}, data => {
              self.form.id_entrada = data.id_entrada;
              self.form.t_cambio = Number(data.t_cambio.tasa);
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'InfoIcon',
                      text: 'Ha ocurrido un error cargando los datos.',
                      variant: 'danger',
                      position: 'bottom-right'
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
              // console.log(err);
            })

      },
      autosave(producto) {
        const self = this;
        if (!self.registrandoProducto && !producto.registrada /*&& bateria.estado===1*/) {
          //self.registrandoBateria = true;
          producto.guardadoEnProgreso = true;
          entrada.autosaveEntradaProducto(
              {
                id_entrada: self.form.id_entrada,
                id_entrada_producto: producto.id_entrada_producto,
                id_producto: producto.productox.id_producto,
                codigo_sistema: producto.productox.codigo_sistema,
                descripcion: producto.productox.descripcion,
                unidad_medida: producto.productox['unidad_medida']['descripcion'],  //$producto['productox']['unidad_medida']['descripcion']
                precio_unitario: producto.precio_unitario,
                cantidad_solicitada: producto.cantidad,
                estado: producto.estado,
                id_bodega: self.form.bodega['id_bodega'],
                numero_documento: self.form.numero_documento
              },
              data => {
                //self.form = data.orden;
                producto.registrada = true;
                producto.id_entrada_producto = data.id_entrada_producto;
                //self.registrandoBateria = false;
                producto.guardadoEnProgreso = false;
              },
              err => {
                producto.registrada = false;
                self.errorMessages = err;
                // console.log(err);
                //self.registrandoBateria = false;
                producto.guardadoEnProgreso = false;
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'InfoIcon',
                        text: 'Se requiere un Numero de documento para guardar el producto.',
                        variant: 'danger',
                        position: 'bottom-right'
                      }
                    },
                    {
                      position: 'bottom-right'
                    });
              }
          );
        }
      },
      cantidad_sin_registrar() {
        var self = this;
        if (self.form.detalleProductos) {
          let i = 0;

          self.form.detalleProductos.forEach((productox, key) => {
            if (!productox.registrada) {
              i++;
            }
          });

          self.total_pendientes = i;

          return self.total_pendientes
        } else {
          return 0;
        }
      },
      agruparProductos() {
        let self = this;

        var counts = self.form.detalleProductos.reduce((p, c) => {
          var name = c.productox.descripcion; //agregar text - Concatenado codigo de barra y descripcion

          if (!p.hasOwnProperty(name)) {
            p[name] = 0;
          }
          if (c.estado === 1) {
            p[name]++;
          }
          return p;
        }, {});

        self.consolidadoProductos = Object.keys(counts).map(k => {
          return {nombre_producto: k, cantidad: counts[k]};
        });
      },
      obtenerTC() {
        // console.log('ejecutando obtener tc con fecha: ' + this.form.fecha_entrada_x);
        var self = this;
        tc.obtenerTC({
          fecha: self.form.fecha_entrada
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
    },
    mounted() {
      this.nuevo();
      // this.obtenerTC();
      this.obtenerTodasBodegas();
      this.obtenerTodosProveedores();
      this.obtenerTodosTiposEntrada();
    }
  };
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';

    .btn-agregar {
        margin-top: 33px;
    }
</style>
