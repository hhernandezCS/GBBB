<template>
  <b-card>
    <form enctype="multipart/form-data">
      <b-row>

<!--        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Tipo:</label>
            <select v-model.number="form.id_tipo_producto" class="form-control" @change="seleccionaTipo()">
              <option value="1">Producto</option>
              <option value="2">Servicio</option>
              &lt;!&ndash;                        <option value="4">Bienes</option>&ndash;&gt;
            </select>
          </div>
        </div>-->
        <template v-if="form.tipo_producto === 2">
          <div class="col-sm-4">
            <div class="form-group">
              <label for="tipo_servicio"> Tipo de servicio:</label>
              <b-form-select id="tipo_servicio" v-model.number="form.tipo_servicio" disabled>
                <option value="1">Servicios de Procesamiento e Instalación</option>
                <option value="2">Asesoría Logística e importaciones</option>
                <!--                        <option value="4">Bienes</option>-->
              </b-form-select>
            </div>
          </div>
        </template>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="">* Descripción</label>
            <input class="form-control" v-model="form.descripcion" placeholder="Descripción">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.descripcion"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>
        <div class="col-sm-4">
          <b-form-group>
            <label for=""> Unidad de medida</label>
            <v-select

                v-model="form.unidad_medida"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                label="siglas"
                :options="ums"
                :clearable="false"

            />
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.unidad_medida"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </b-form-group>
        </div>
        <div class="col-sm-4">
          <b-form-group>
            <label for=""> Grupos</label>
            <v-select
                @input="seleccionargrupo"
                v-model="form.grupo"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="grupos"
                label="descripcion"
                :clearable="false"
            />
            <b-alert show variant="danger">
              <ul class="error-messages">
                <li
                    :key="message"
                    v-for="message in errorMessages.grupo"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </b-form-group>
        </div>

        <div class="col-sm-4">
          <b-form-group>
            <label for=""> Sub Grupos</label>
            <v-select

                v-model="form.subgrupo"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="subgrupos"
                label="descripcion"
            />
            <b-alert show variant="danger">
              <ul class="error-messages">
                <li
                    :key="message"
                    v-for="message in errorMessages.subgrupo"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </b-form-group>
        </div>

        <div class="col-sm-4">
          <b-form-group>
            <label for=""> Marca</label>
            <v-select
                v-model="form.marca"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                label="descripcion"
                :options="marcas"
            />
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.unidad_medida"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </b-form-group>
        </div>




<!--        <div class="col-sm-3">
          <div class="form-group">
            <label for="">* Nombre {{ form.id_tipo_producto === 1 ? 'Producto' : 'Servicio' }}</label>
            <input class="form-control" v-model="form.nombre_comercial" placeholder="Nombre">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.nombre_comercial"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>-->


             <div class="col-sm-4">
                <div class="form-group">
                  <label for=""> Código Barras</label>
                  <input  class="form-control" v-model="form.codigo_barra"
                         placeholder="Código Barras">
                  <barcode v-bind:value="form.codigo_barra" :textPosition="textPositionx" :format="formatx"
                           :marginTop="marginTopx" :width="widthx" :height="heightx">
                    Generando Código de Barras
                  </barcode>
                  <b-alert variant="danger" show>
                    <ul class="error-messages">
                      <li
                          v-for="message in errorMessages.codigo_barra"
                          :key="message"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>
                </div>
              </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Código</label>
            <input class="form-control" v-model="form.codigo_sistema" placeholder="Código Sistema" readonly>
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.codigo_sistema"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>


        <div class="col-sm-4">
            <div class="form-group">
                <label for=""> Impuesto</label>
                <v-select v-model="form.impuesto_producto" label="descripcion" :options="impuestos" :clearable="false"/>
                <ul class="error-messages">
                    <li v-for="message in errorMessages.impuesto_producto" :key="message" v-text="message"></li>
                </ul>
            </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Existencia Mínima</label>
            <input class="form-control" :disabled="!tipoProducto" type="number" min="0"
                   v-model.number="form.existencia_min" placeholder="Existencia Mínima">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.existencia_min"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Existencia Máxima</label>
            <input class="form-control" :disabled="!tipoProducto" placeholder="Existencia Máxima" type="number"
                   min="0" v-model.number="form.existencia_max">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.existencia_max"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>
        <!--<div class="col-sm-4">
          <label for="aplicar_porcentaje"></label>
          <b-form-checkbox
                  id="aplicar_porcentaje"
                  v-model="form.aplica_porcentaje_ganancia"
                  name="aplica_porcentaje"
                  @input="resetValue()"
          >
            Aplica porcentaje de ganancia
          </b-form-checkbox>
        </div>-->

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Precio de compra C$</label>
            <input class="form-control" placeholder="Costo de compra" min="0" type="number"
                   v-model.number="form.precio_compra">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.precio_compra"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>
        <!--<div class="col-sm-4">
          <div class="form-group">
            <label for=""> Porcentaje Ganacia</label>
            <input class="form-control" min="0" :disabled="!form.aplica_porcentaje_ganancia" placeholder="Porcentaje Ganancia" type="number"
                   v-model.number="form.porcentaje_ganancia" >
            <b-alert show variant="danger">
              <ul class="error-messages">
                <li
                    :key="message"
                    v-for="message in errorMessages.porcentaje_ganancia"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>

        </div>-->

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Precio de venta C$</label>
            <input class="form-control" placeholder="Precio de venta"  min="1" type="number"
                   v-model.number="form.precio_sugerido" >
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.precio_sugerido"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Precio de venta a Distribuidor C$</label>
            <input class="form-control" placeholder="Precio de venta distribuidor" min="1" type="number"
                   v-model.number="form.precio_distribuidor">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                        v-for="message in errorMessages.precio_distribuidor"
                        :key="message"
                        v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>

        <!--<div class="col-sm-3">
            <div class="form-group">
                <label for=""> Inventario Inicial</label>
                <input class="form-control" :disabled="!tipoProducto" type="number" min="0"
                       v-model.number="form.cantidad_inicial" placeholder="Inventario Inicial">
                <ul class="error-messages">
                    <li v-for="message in errorMessages.cantidad_inicial" :key="message" v-text="message"></li>
                </ul>
            </div>
        </div>-->

        <!--<div class="col-sm-3">
            <div class="form-group">
                <label for>Bodega</label>
                <v-select
                        :disabled="!tipoProducto"
                        v-model="form.bodega_inicial"
                        label="descripcion"
                        :options="bodegas"
                ></v-select>
                <ul class="error-messages">
                    <li v-for="message in errorMessages.bodega_inicial" :key="message" v-text="message"></li>
                </ul>
            </div>
        </div>-->

        <div class="col-sm-4">
          <div class="form-group">
            <label for=""> Imagen</label>
            <div class="uploader">
              <b-form-file id="customFile" accept="image/jpeg, image/png, image/gif"
                           @change="handleFileObject" ref="file"/>
            </div>
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    v-for="message in errorMessages.imagen"
                    :key="message"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </div>
        </div>
        <div class="col-sm-4">
          <template v-if="form.imagen">
            <b-container fluid class="p-1 bg-dark">
              <b-row>
                <b-col>
                  <b-img thumbnail fluid-grow :src="get_avatar()" alt="imagen del producto')"/>
                </b-col>
              </b-row>
            </b-container>
          </template>
        </div>
      </b-row>
    </form>

    <b-card-footer>
      <b class="row">
        <div class="col-lg-6 mt-1">
          <div class=" text-lg-left text-center">
            <template v-if="form.estado===1">
              <b-button @click="desactivar(form.id_producto)" variant="danger">
                <feather-icon icon="TrashIcon"></feather-icon>
                Deshabilitar
              </b-button>
            </template>
            <template v-else>
              <b-button @click="activar(form.id_producto)" variant="success">
                <feather-icon icon="CheckIcon"></feather-icon>
                Habilitar
              </b-button>
            </template>
          </div>
        </div>

        <div class="col-lg-6 mt-1">
          <div class=" text-lg-right text-center">
            <router-link :to="{ name: 'inventario-productos' }">
              <b-button class="mx-1" type="button" variant="secondary">Cancelar</b-button>
            </router-link>
            <b-button
                type="button"
                variant="primary"
                @click="actualizarProducto"
                :disabled="btnAction !== 'Guardar' ? true : false"
            >{{ btnAction }}
            </b-button>
          </div>
        </div>
      </b>

    </b-card-footer>
    <template v-if="loading">
      <BlockUI :url="url"></BlockUI>
    </template>
  </b-card>
</template>
<script type="text/ecmascript-6">
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import VueBarCode from 'vue-barcode'
  //import Pagination from '../layout/Pagination'
  import {
    BAlert,
    BButton,
    BCard,
    BCardFooter,
    BCol,
    BContainer,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormFile,
    BFormGroup,
    BFormSelect,
    BImg,
    BPaginationNav,
    BRow,
    VBTooltip
  } from 'bootstrap-vue'
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import vSelect from 'vue-select'
  import producto from "../../../api/Inventario/productos";
  import um from "../../../api/Inventario/unidad_medida";
  import marcas from "../../../api/Inventario/marcas";
  import grupos from "../../../api/Inventario/grupo";
  import subgrupo from "@/api/Inventario/subgrupo";
  import impuestos from "@/api/admon/impuestos";
  import Round from "@/assets/custom-scripts/Round";

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
    BAlert,
    'barcode': VueBarCode,
    BFormFile,
    BImg,
    BContainer,
    BCol,
    BFormSelect
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      loading: true,
      msg: 'Guardando los datos, espere un momento',
      url: loadingImage,   //It is important to import the loading image then use here
      textPositionx: 'center',
      heightx: 25,
      widthx: 1,
      marginTopx: 0,
      formatx: 'CODE39',
      tipoProducto: true,
      grupos: [],
      subgrupos: [],
      impuestos: [],
      ums: [],
      marcas: [],
      bodegas: [],
      imagen_nueva: false,
      form: {
        precio_compra: 0,
        precio_distribuidor: 0,
        codigo_sistema: '',
        codigo_consecutivo: 0,
        nombre_comercial: '',
        descripcion: '',
        costo_estandar: 0,
        precio_sugerido: 0,
        existencia_min: 1,
        existencia_max: 1,
        cantidad_inicial: 0,
        id_tipo_producto: 1,
        codigo_barra: '',
        imagen: '',
        grupo_producto: [],
        impuesto_producto: [],
        unidad_medida: [],
        bodega_inicial: "",
        avatar: '',
        avatarName: '',
        imagen_nueva: false,
        marca: [],
        porcentaje_ganancia:0,
        aplica_porcentaje_ganancia: false,
        grupo: {

          grupo_sub_grupos: []
        },
        subgrupo: []
      },
      uploader: [],
      btnAction: 'Guardar',
      errorMessages: []
    }
  },
  methods: {
    handleFileObject(e) {
      let file = e.target.files[0];
      let reader = new FileReader();

      if (file['size'] < 2111775) {
        reader.onloadend = (file) => {
          //console.log('RESULT', reader.result)
          this.form.avatar = reader.result;
          if (this.form.imagen !== this.form.avatar) {
            this.imagen_nueva = true;
            this.form.imagen_nueva = true;
          }
        }
        reader.readAsDataURL(file);
      } else {
        alert('El tamaño del archivo no puede ser superior a 2 MB')
      }
    },
    //For getting Instant Uploaded Photo
    get_avatar() {
      let self = this;
      if (self.imagen_nueva === true) {

        // let photo = (this.form.avatar.length > 100) ? this.form.avatar : "http://localhost:8001/img/products/"+ this.form.avatar;
        let photo = (this.form.avatar.length > 100) ? this.form.avatar : "http://css.capital.software:8043/img/products/" + this.form.avatar;
        // let photo = (this.form.avatar.length > 100) ? this.form.avatar : "https://backend.capital.software/img/products/"+ this.form.avatar;
        return photo;

      } else {
        // let photo = (this.form.imagen.length > 100) ? this.form.imagen : "http://localhost:8001/img/products/"+ this.form.imagen;
        let photo = (this.form.imagen.length > 100) ? this.form.imagen : "http://css.capital.software:8043/img/products/" + this.form.imagen;
        // let photo = (this.form.imagen.length > 100) ? this.form.imagen : "https://backend.capital.software/img/products/"+ this.form.imagen;
        return photo;
      }

      // let photo = (this.form.imagen.length > 100) ? this.form.imagen : "http://css.capital.software:8043/img/products/"+ this.form.imagen;

    },

    desactivar(productoId) {

      var self = this;
      self.$swal.fire({
        title: 'Cambio de estado',
        text: "Esta seguro de cambiar el estado?",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        confirmButtonText: 'Si, confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.$swal({
            icon: 'success',
            title: 'Desactivado!',
            text: 'El registro ha sido desactivado con éxito.',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          });
          producto.desactivar({
            id_producto: productoId
          }, data => {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Producto desactivado correctamente',
                    variant: 'danger',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: "inventario-productos"
            });
          }, err => {
            console.log(err)
          });
        } else {
          this.$swal({
            title: 'Cancelado',
            text: 'El registro no ha cambiado de estado :)',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
          self.btnAction = "Guardar";
        }
      })
    },
    activar(productoId) {

      var self = this;
      self.$swal.fire({
        title: 'Cambio de estado',
        text: "Esta seguro de cambiar el estado?",
        type: 'warning',
        showCancelButton: true,
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
        confirmButtonText: 'Si, confirmar',
        cancelButtonText: 'Cancelar',
        icon: 'warning'
      }).then((result) => {
        if (result.value) {
          this.$swal({
            icon: 'success',
            title: 'Activado!',
            text: 'El registro ha sido activado con éxito.',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          });
          producto.activar({
            id_producto: productoId
          }, data => {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Producto activado correctamente',
                    variant: 'success',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: "inventario-productos"
            });
          }, err => {
            console.log(err)
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: err,
                    variant: 'danger',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
          });
        } else {
          this.$swal({
            title: 'Cancelado',
            text: 'El registro no ha cambiado de estado :)',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
          self.btnAction = "Guardar";
        }
      })
    },
/*    obtenerCodigo() {
      var self = this;
      producto.obtenerCodigoProducto({}, data => {
        //console.log(data);
        self.form.codigo_consecutivo = data.codigo_siguiente;
        self.obtenerConcatenarCodigo();
      }, err => {
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: err,
                variant: 'danger',
                position: 'bottom-right'
              }
            },
            {
              position: 'bottom-right'
            });
      })
    },*/
    obtenerConcatenarCodigo() {
      let self = this;
      let descripcionGrupo = self.form.grupo.descripcion;
      let codigo;
      codigo = descripcionGrupo.substring(0, 3).toUpperCase();
      self.form.codigo_sistema = codigo + self.form.codigo_consecutivo;
    },

    ObtenerProductoEspecifico() {
      var self = this;
      self.loading = true;
      producto.obtenerProducto(
          {
            id_producto: this.$route.params.id_producto
          },
          data => {
            self.form = data;
            self.form.avatar = self.form.imagen;
            self.loading = false;
            //self.uploader.set_files(data.imagen);
            self.impuestos = data.impuestos;
            self.seleccionaTipo();
            //this.obtenerConcatenarCodigo();
          },
          err => {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: err,
                    variant: 'danger',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
            self.loading = true;
            console.log(err);
          }
      );
    },



    obtenerTodosUnidadMedida() {
      var self = this;
      self.loading = true;
      um.obtenerTodas(data => {
        self.ums = data;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err);
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: err,
                variant: 'danger',
                position: 'bottom-right'
              }
            },
            {
              position: 'bottom-right'
            });
      })
    },
    obtenerTodasMarcas() {
      var self = this;
      self.loading = true;
      marcas.obtenerTodos(data => {
        self.marcas = data;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err);
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: err,
                variant: 'danger',
                position: 'bottom-right'
              }
            },
            {
              position: 'bottom-right'
            });
      })
    },
    /**
     * Obtener todos los grupos
     */
    obtenerTodosgrupos() {
      var self = this;
      self.loading = true;
      grupos.obtenerTodos(data => {
        self.grupos = data;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err);
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: err,
                variant: 'danger',
                position: 'bottom-right'
              }
            },
            {
              position: 'bottom-right'
            });
      })
    },
    /**
     * Obtener todos los subgrupos
     */
    obtenerTodosSubgrupos() {
      var self = this;
      self.loading = true;
      subgrupo.obtenerTodos(data => {
        self.subgrupos = data;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err);
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: err,
                variant: 'danger',
                position: 'bottom-right'
              }
            },
            {
              position: 'bottom-right'
            });
      })
    },
    /**
     * Cargar subgrupo por cada grupo
     */
    obtenerSubrurogrupo(e) {
      const self = this;
      self.loading = true;
      subgrupo.obtenerGrupossubgrupo({
        id_grupo: self.form.grupo,
      }, data => {
        if (data !== null) {
          self.form.grupo.grupo_sub_grupos = data;
          self.form.subgrupo = data[0];
          self.loading = false;
        } else {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'No se encuentran proyectos para este cliente.',
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
          self.loading = false;
        }
        self.loading = false;
      }, err => {
        /*if(err.codigo_bateria){
          self.detalleForm.bateria_busqueda = '';
          self.$refs.bateria.focus();
          alertify.warning("No se encuentra esta batería.",5);
          self.detalleForm.cuentax = '';
        }*/
        self.loading = false;
      });

    },

    /**
     * Seleccionar grupo
     */

    seleccionargrupo(e) {
      const self = this;
        self.obtenerConcatenarCodigo();
      self.obtenerSubrurogrupo();
    },

    seleccionaTipo() {
      var self = this;
      //console.log(self.form.tipo_producto);
      if (self.form.tipo_producto === 1) {
        self.tipoProducto = false;
      } else {
        self.tipoProducto = true;
      }
    },

    obtenerTodosImpuestos() {
      var self = this
      impuestos.obtenerTodosImpuestos(data => {
        self.impuestos = data
      }, err => {
        console.log(err)
      })
    },

    actualizarProducto() {
      const self = this;
      self.loading = true;
      self.btnAction = "Guardando, espere....";
      producto.actualizarProducto(
          self.form,
          data => {
            this.$router.push({
              name: "inventario-productos"
            });
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Producto actualizado con éxito',
                    variant: 'success',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
          },
          err => {
            self.loading = false;
            self.errorMessages = err;
            self.btnAction = "Guardar";
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: err,
                    variant: 'danger',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
          }
      );
    },
    resetValue(){
      //Reiniciar valores de variables al cambio el status de aplica calculo de porcentaje ganancia
      //para evitar recalculos
      this.form.porcentaje_ganancia = 0;
      this.form.precio_sugerido = 0;
    },
  },
  computed:{
/*    calcular_precio_porcentaje() {
      if (this.form.aplica_porcentaje_ganancia) {
        return this.form.precio_sugerido = Round.round(Number((Number(this.form.precio_compra) * Number(this.form.porcentaje_ganancia / 100)) + Number(this.form.precio_compra)), 2);
      }
      return this.form.porcentaje_ganancia = Round.round((Number(this.form.precio_sugerido - this.form.precio_compra) / Number(this.form.precio_compra)) * 100, 2)
    },*/
/*    porcentaje_ganancia_calculado() {
      if (Number(this.form.precio_sugerido) > 0 && Number(this.form.precio_compra) > 0) {
        return this.form.porcentaje_ganancia = Round.round((Number(this.form.precio_sugerido - this.form.precio_compra) / Number(100)) * 100, 2);
      }
      return this.form.porcentaje_ganancia = 0;
    }*/
  },
  mounted() {
/*    this.$watch('form.grupo', (newVal, oldVal) => {
      // Llamar a la función obtenerConcatenarCodigo() cuando se detecte un cambio
      //this.obtenerConcatenarCodigo();
    });*/
    this.obtenerTodosUnidadMedida();
    this.obtenerTodasMarcas();
    this.ObtenerProductoEspecifico();
    this.obtenerTodosgrupos();
    this.obtenerTodosSubgrupos();
    this.obtenerTodosImpuestos();
    this.get_avatar();
  }
}
</script>

<style lang="scss">


@import '@core/scss/vue/libs/vue-select.scss';

</style>
