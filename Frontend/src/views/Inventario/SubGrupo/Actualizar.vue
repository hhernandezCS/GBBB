<template>
  <b-card>
    <form>
      <b-row>
        <div class="col-sm-12">
          <b-form-group>
            <label for=""> grupo</label>
            <v-select
                v-model="form.grupo"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="grupos"
                label="descripcion"
            />
            <b-alert show variant="danger">
              <ul class="error-messages">
                <li
                    :key="message"
                    v-for="message in errorMessages.grupo_producto"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>
          </b-form-group>
        </div>


        <div class="col-sm-12">
          <b-form-group>
            <label for="name">*Sub-grupo:</label>
            <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite el grupo">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.descripcion">{{ message }}</li>
              </ul>
            </b-alert>
          </b-form-group>
        </div>


        <div class="col-sm-12">
          <b-form-group>
            <label for="name">* Codigo Sub-grupo:</label>
            <input type="text" class="form-control" v-model="form.codigo" placeholder="Dígite el codigo del grupo">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.codigo">{{ message }}</li>
              </ul>
            </b-alert>

          </b-form-group>
        </div>


      </b-row>

    </form>
    <b-card-footer class="content-box-footer text-right mt-1">

      <b-row>
        <!--        <div class="col-md-6 text-left p-0">
                  <template v-if="form.estado">
                    <b-button type="submit" variant="danger" @click="desactivar(form.id_grupo)">
                      <feather-icon icon="Trash2Icon"></feather-icon> Eliminar
                    </b-button>
                  </template>
                  <template v-else>
                    <b-button type="submit" variant="success" @click="activar(form.id_grupo)">
                      <feather-icon icon="CheckIcon"></feather-icon> Habilitar
                    </b-button>
                  </template>

                </div>-->
        <div class="col-md-6">
          <router-link  :to="{name: 'inventario-subgrupo'}">
            <b-button type="submit" variant="secondary" class="mx-1">
              Cancelar
            </b-button>
          </router-link>


          <b-button type="submit" variant="primary" @click="actualizar" :disabled="btnAction !== 'Actualizar'">
            {{btnAction}}
          </b-button>
        </div>
      </b-row>

    </b-card-footer>
    <template v-if="loading">
      <BlockUI :url="url"></BlockUI>
    </template>
  </b-card>
</template>

<script type="text/ecmascript-6">
import loadingImage from '../../../assets/images/loader/block50.gif'
import {BButton,BAlert,BFormCheckbox,BFormSelect,BCard,BCardFooter, BRow} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import axios from 'axios'
import subgrupo from "@/api/Inventario/subgrupo";
import grupos from "@/api/Inventario/grupo";

export default {
  components:{
    BButton,
    BAlert,
    BFormCheckbox,
    vSelect,
    BFormSelect,
    BCard,
    BCardFooter,
    BRow,
  },
  directives : {
    Ripple,
  },
  data() {
    return {
      loading:false,
      msg: 'Guardando los datos, espere un momento',
      url : loadingImage,   //It is important to import the loading image then use here
      grupos: [],
      form: {
        descripcion : '',

      },
      btnAction: 'Actualizar',
      errorMessages: [],

    }
  },
  methods: {

    obtenerSubgrupo(){
      var self = this;
      subgrupo.obtenersubgrupo({
        id_subgrupo: this.$route.params.id_subgrupo
      }, data =>{
        self.form = data;
        self.loading = false;
      }, err =>{
        this.$toast({
              component : ToastificationContent,
              props : {
                title : 'Notificación',
                icon : 'InfoIcon',
                text : 'Ha ocurrido un error al cargar los datos',
                variant : 'warning',
              }
            },
            {
              position : 'bottom-rigth'
            });
        this.$router.push({
          name : 'inventario-subgrupo'
        })
      })
    },

    actualizar(){
      var self = this
      self.btnAction = 'Guardando, espere....'
      self.loading = true;
      subgrupo.actualizar(self.form, data =>{
            self.loading = false;
            this.$toast({
                  component : ToastificationContent,
                  props: {
                    title : 'Notificación',
                    icon : 'CheckIcon',
                    text : 'Datos actualizados correctamente.',
                    variant : 'success',
                  }
                },
                {
                  position : 'bottom-right'
                });
            this.$router.push({
              name : 'inventario-subgrupo'
            })
          },
          err =>{
            self.loading = false;
            self.errorMessages = err
            self.btnAction = 'Guardar'
            this.$toast({
              component: ToastificationContent,
              props: {
                title : 'Notificación',
                icon : 'InfoIcon',
                text : 'Ha ocurrido un error, intentelo de nuevo',
                variant : 'warning',
                position : 'bottom-right'
              }
            });

          })
    },
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

    /* activar(marcaID){
       var self = this;
       self.$swal({
         title: '¿Esta seguro?',
         text: '¿ Desea activar la marca?',
         icon: 'info',
         showCancelButton: true,
         confirmButtonText : 'Si, confirmar',
         cancelButtonText : 'Cancelar',
         customClass: {
           confirmButton: 'btn btn-primary',
           cancelButton : 'btn btn-danger ml-1',
         },
         buttonsStyling : false,
       }).then((result) => {
         if (result.value){
           self.$swal({
             icon :'success',
             title : '¡Habilitada!',
             text : '¡Marca habilitada!',
             customClass: {
               confirmButton : 'btn btn-success',
             }
           });
           marca.activar({
             id_marca: marcaID
           }, data =>{
             this.$router.push({
               name : 'inventario-marcas'
             })
           }, err =>{
             console.log(err);
             this.$toast({
                   component: ToastificationContent,
                   props: {
                     title : 'Notificación',
                     icon : 'InfoIcon',
                     text : 'Ha ocurrido un error, intentelo de nuevo',
                     variant : 'warning',
                   }
                 },
                 {
                   position : 'bottom-right'
                 });
             this.$router.push({
               name : 'inventario-marcas'
             })
           })
         }else if (result.dismiss === 'cancel'){
           self.$swal({
             title: 'Cancelado',
             text: 'La marca no ha sido habilitada',
             icon: 'error',
             customClass: {
               confirmButton: 'btn btn-success',
             },
           })
         }

       })
     },

     desactivar(marcaID){
       var self = this;
       self.$swal({
         title: 'Info!',
         text: '¿Esta seguro de desactivar la marca?',
         icon: 'info',
         showCancelButton: true,
         confirmButtonText : 'Si, confirmar',
         cancelButtonText : 'Cancelar',
         customClass: {
           confirmButton: 'btn btn-primary',
           cancelButton : 'btn btn-danger ml-1',
         },
         buttonsStyling : false,
       }).then((result) => {
         if (result.value){
           self.$swal({
             icon :'success',
             title : '¡Eliminada!',
             text : '¡Marca eliminada!',
             customClass: {
               confirmButton : 'btn btn-success',
             }
           });
           marca.desactivar({id_marca: marcaID
               },
               data =>{
                 this.$router.push({
                   name : 'inventario-marcas'
                 })
               }, err =>{
                 console.log(err);
                 this.$toast({
                       component: ToastificationContent,
                       props: {
                         title : 'Notificación',
                         icon : 'InfoIcon',
                         text : 'Ha ocurrido un error, intentelo de nuevo',
                         variant : 'warning',
                       }
                     },
                     {
                       position : 'bottom-right'
                     });
                 this.$router.push({
                   name : 'inventario-marcas'
                 })
               })
         }else if (result.dismiss === 'cancel'){
           self.$swal({
             title: 'Cancelado',
             text: 'La marca no ha sido eliminada',
             icon: 'error',
             customClass: {
               confirmButton: 'btn btn-success',
             },
           })
         }
       })
     },*/

  },
  mounted() {
    this.obtenerSubgrupo();
    this.obtenerTodosgrupos();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>
