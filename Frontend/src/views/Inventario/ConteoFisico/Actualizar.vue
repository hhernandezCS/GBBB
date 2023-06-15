<template>
    <b-card>
        <b-row>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Area Solicitante</label>
                    <v-select
                            :options="areas"
                            label="descripcion"
                            placeholder="Selecciona un area"
                            v-model="form.conteo_area"
                    >
                        <template slot="no-options"> No se encontraron registros</template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.conteo_area"
                                v-text="message"></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Sucursal</label>
                    <v-select
                            :options="sucursales"
                            label="descripcion"
                            placeholder="Seleccione una sucursal"
                            v-model="form.conteo_sucursal"
                    >
                        <template slot="no-options"> No se encontraron registros</template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.conteo_sucursal"
                                v-text="message"></li>
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
                            placeholder="Seleccione una bodega"
                            v-model="form.conteo_bodega"
                    >
                        <template slot="no-options"> No se encontraron registros</template>
                    </v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.conteo_bodega"
                                v-text="message"></li>
                        </ul>
                    </b-alert>

                </div>
            </div>


            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Fecha Conteo</label>
                    <b-form-datepicker
                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                            @selected="onDateSelect"
                            class="mb-0"
                            local="es"
                            placeholder="f. conteo"
                            selected-variant="primary"
                            v-model="fechax"
                    >

                    </b-form-datepicker>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.f_inventario"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>


            <div class="col-sm-4">
                <label for="hour-inicio-input">Hora Inicio</label>
                <b-input-group class='mb-1'>
                    <b-form-input id='hour-inicio-input' placeholder='HH:mm' type='text' v-mask="'##:##'"
                                  v-model='form.hora_inicio'/>
                    <b-input-group-append>
                        <b-form-timepicker aria-controls='hour-inicio-input' button-only button-variant="outline-primary"
                                           locale='en' right size="sm" v-model='form.hora_inicio'/>
                    </b-input-group-append>
                </b-input-group>
                <b-alert show variant="danger">
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.hora_inicio"
                                v-text="message"
                        ></li>
                    </ul>
                </b-alert>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Hora Finalización</label>
                    <b-input-group class='mb-1'>
                        <b-form-input id='hour-fin-input' placeholder='HH:mm' type='text' v-mask="'##:##'"
                                      v-model='form.hora_fin'/>
                        <b-input-group-append>
                            <b-form-timepicker aria-controls='hour-fin-input' button-only button-variant="outline-primary"
                                               locale='es' right size="sm" v-model='form.hora_fin'/>
                        </b-input-group-append>
                    </b-input-group>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.hora_fin"
                                    v-text="message"
                            ></li>
                        </ul>
                    </b-alert>

                </div>
            </div>

        </b-row>

        <div v-if="!form.conteo_bodega">
            <b-alert class="mb-2 mt-2" show variant="info">
                <div class="alert-body">
                    <span>Debe seleccionar una bodega para poder continuar.</span>
                </div>
            </b-alert>

        </div>

        <b-alert class="mb-2 mt-2" show variant="success">
            <div class="alert-body">
                <span><strong>Detalle de productos</strong></span>
            </div>
        </b-alert>

        <b-row>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Producto</label>

                    <!--<typeahead style="width: 100%;" :initial="detalleForm.productox" :trim="80" :url="productosBusquedaURL" @input="seleccionarProducto"></typeahead>-->

                    <v-select :allow-empty="false" :options="productos"
                              :searchable="true"
                              label="text"
                              placeholder="Selecciona un producto"
                              ref="producto"
                              track-by="id_producto"
                              v-model="detalleForm.productox"
                              v-on:keydown.enter.native="seleccionarProducto"
                    >
                        <template slot="no-options">No se encontraron registros</template>
                    </v-select>

                    <typeahead :initial="detalleForm.productox" :trim="80" :url="productosBusquedaURL"
                               @input="seleccionarProducto" style="width: 100%;"></typeahead>

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

            <div class="col-sm-4">
                <div class="form-group">
                    <label for>Cantidad</label>
                    <input class="form-control" min="1" ref="cantidad" type="number"
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


            <div class="col-sm-4">
                <label for=""></label>
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <b-button @click="agregarDetalle" ref="agregar" variant="info">
                        Agregar
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
                                v-for="message in errorMessages.conteo_productos"
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

                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(item, index) in form.conteo_productos">
                            <tr>
                                <td style="width: 2%">
                                    <b-button @click="eliminarLinea(item, index)" variant="danger">
                                        <feather-icon icon="TrashIcon"></feather-icon>
                                    </b-button>
                                </td>
                                <td>
                                    <input class="form-control" disabled v-model="item.descripcion">
                                    <b-alert show variant="danger">
                                        <ul class="error-messages">
                                            <li
                                                    :key="message"
                                                    v-for="message in errorMessages[`conteo_productos.${index}.productox.id_producto`]"
                                                    v-text="message">
                                            </li>
                                        </ul>
                                    </b-alert>

                                </td>


                                <td>
                                    <input class="form-control" min="1" type="number"
                                           v-model.number="item.cantidad">
                                    <b-alert show variant="danger">
                                        <ul class="error-messages">
                                            <li
                                                    :key="message"
                                                    v-for="message in errorMessages[`conteo_productos.${index}.cantidad`]"
                                                    v-text="message">
                                            </li>
                                        </ul>
                                    </b-alert>

                                </td>

                            </tr>
                            <tr></tr>
                        </template>
                        </tbody>
                        <tfoot>


                        <tr>
                            <td class="item-footer" colspan="2"> Total Unidades</td>
                            <td class="item-footer">
                                <strong>{{total_cantidad}}</strong>
                            </td>
                        </tr>


                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

        <b-card-footer class="content-box-footer text-lg-right text-center">
            <router-link :to="{ name: 'inventario-conteo-fisico' }">
                <b-button class="mx-lg-1" type="button" variant="secondary">Cancelar</b-button>
            </router-link>
            <b-button
                    :disabled="btnActionDraft !== 'Guardar Borrador'"
                    @click="form.es_borrador=true;actualizar()"
                    class="mx-lg-1"
                    type="button" variant="dark"
            >{{ btnActionDraft }}
            </b-button>
            <b-button
                    :disabled="btnAction !== 'Actualizar y Emitir'"
                    @click="form.es_borrador=false;actualizar()"
                    class="mx-lg-1"
                    type="button" variant="primary"
            >{{ btnAction }}
            </b-button>
        </b-card-footer>

        <template v-if="loading">
            <BlockUI :url="url"></BlockUI>
        </template>

    </b-card>

</template>


<script type="text/ecmascript-6">
  import conteox from "../../../api/Inventario/conteo-fisico";
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
    BFormInput,
    BFormTimepicker,
    BInputGroup,
    BInputGroupAppend,
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
      BFormTimepicker,
      BInputGroup,
      BFormInput,
      BInputGroupAppend,
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        es: es,
        format: "dd-MM-yyyy",
        loading: true,
        msg: 'Cargando el contenido espere un momento',
        url: loadingImage,
        productosBusquedaURL: "/inventario/productos/buscar",

        areas: [],
        productos: [],
        sucursales: [],
        bodegas: [],
        fechax: new Date(),

        detalleForm: {
          productox: '',
          cantidad: 1,
        },

        form: {
          hora_inicio: "",
          hora_fin: "",
          f_inventario: moment(new Date()).format("YYYY-MM-DD"),
          conteo_sucursal: "",
          conteo_bodega: "",
          conteo_area: "",
          conteo_productos: [],
          es_borrador: false
        },

        btnAction: "Actualizar y Emitir",
        btnActionDraft: "Guardar Borrador",
        errorMessages: []
      };
    },
    computed: {

      total_cantidad() {
        return this.form.conteo_productos.reduce((carry, item) => {
          return (carry + Number(item.cantidad))
        }, 0)
      },
    },
    methods: {

      seleccionarProducto(e) {
        const productoP = e.target.value;
        const self = this;
        self.detalleForm.productox = productoP;
        if (self.detalleForm.productox) {
          self.detalleForm.cantidad = 1;
          self.detalleForm.precio_info = 1;
          self.$refs.cantidad.focus();
        }

      },

      onDateSelect(date) {
        //console.log(date); //
        this.form.f_inventario = moment(date).format("YYYY-MM-DD"); //
      },


      agregarDetalle() {
        let self = this;
        if (this.detalleForm.productox) {
          if (this.detalleForm.cantidad > 0 && this.detalleForm.precio_info > 0) {


            let i = 0;
            let keyx = 0;
            if (self.form.conteo_productos) {
              self.form.conteo_productos.forEach((productox, key) => {
                //console.log('ID_PRODUCTO ',self.detalleForm.productox.id_producto);
                if (self.detalleForm.productox.id_producto === productox.id_producto) {
                  i++;
                  keyx = key;
                }
              });
            }

            if (i === 0) {
              this.form.conteo_productos.push({
                productox: this.detalleForm.productox,
                id_producto: this.detalleForm.productox.id_producto,
                codigo_barra: this.detalleForm.productox.codigo_barra,
                descripcion: this.detalleForm.productox.text,
                cantidad: this.detalleForm.cantidad,
              });
              this.detalleForm.productox = '';
              this.detalleForm.cantidad = 0;

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
                  self.form.conteo_productos[keyx].cantidad = Number(self.form.conteo_productos[keyx].cantidad) + self.detalleForm.cantidad;
                  this.detalleForm.productox = '';
                  this.detalleForm.cantidad = 0;
                }
              })
            }

          } else {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'El valor para cantidad y precio unitario debe ser mayor a cero.',
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
                  text: 'Se debe seleccionar un producto.',
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
        if (this.form.conteo_productos.length >= 1) {
          this.form.conteo_productos.splice(index, 1);

        } else {
          this.form.conteo_productos = [];
        }
      },

      obtenerConteo() {
        var self = this
        conteox.obtenerConteo({
          id_inventario_fisico: this.$route.params.id_inventario_fisico,
          cargar_dependencias: true,
        }, data => {

          self.sucursales = data.sucursales;
          self.bodegas = data.bodegas;
          self.areas = data.areas;
          self.productos = data.productos;
          self.form = data.conteo;
          self.loading = false;
          if (self.form.estado !== 99) {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'El conteo ya no puede ser actualizado.',
                    variant: 'danger',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: "inventario-conteo-fisico"
            });
          }
        })
      },

      actualizar() {
        var self = this;
        if (self.form.estado === 99) {
          self.btnAction = "Registrando, espere....";
          self.btnActionDraft = "Registrando, espere....";
          self.loading = true;
          conteox.actualizar(
              self.form,
              data => {
                if (self.form.es_borrador) {
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'InfoIcon',
                          text: 'Registro actualizado correctamente.',
                          variant: 'success',
                          position: 'bottom-right'
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
                          icon: 'InfoIcon',
                          text: 'El Registro fue EMITIDO correctamente.',
                          variant: 'success',
                          position: 'bottom-right'
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                }
                self.loading = false;
                this.$router.push({
                  name: "inventario-conteo-fisico"
                });
              },
              err => {
                self.loading = false;
                self.errorMessages = err;
                self.btnAction = "Actualizar y Emitir";
                self.btnActionDraft = "Guardar Borrador";
              }
          );
        } else {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: 'El conteo de inventario no puede ser actualizado.',
                  variant: 'danger',
                  position: 'bottom-right'
                }
              },
              {
                position: 'bottom-right'
              });
        }
      }
    },
    mounted() {
      this.obtenerConteo();
    }
  };
</script>
<style lang="scss">
    @import "src/@core/scss/vue/libs/vue-select";

    .btn-agregar {
        margin-top: 33px;
    }
</style>
