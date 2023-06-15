<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="name"><span style="color: red">*</span>  Descripción del propietario:</label>
            <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite la descripción del propietario">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.descripcion">{{ message }}</li>
              </ul>
            </b-alert>
          </div>
        </div>
      </div>
    </form>
    <b-card-footer class="content-box-footer text-right mt-1">

      <b-row>
        <div class="col-md-6 text-left p-0">
          <template v-if="form.estado">
            <b-button type="submit" variant="danger" @click="desactivar(form.id_tipo_activo)">
              <feather-icon icon="Trash2Icon"></feather-icon> Eliminar
            </b-button>
          </template>
          <template v-else>
            <b-button type="submit" variant="success" @click="activar(form.id_tipo_activo)">
              <feather-icon icon="CheckIcon"></feather-icon> Habilitar
            </b-button>
          </template>

        </div>
        <div class="col-md-6">
          <router-link  :to="{name: 'activofijo-tiposactivos'}">
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
import tipo from "../../../api/ActivoFijo/TiposActivos";
import axios from 'axios'

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
      form: {
        descripcion : '',
      },
      btnAction: 'Actualizar',
      errorMessages: [],
      normativas: []
    }
  },
  methods: {

    obtenerTipo(){
      var self = this;
      tipo.obtenerTipo({
        id_tipo_activo: this.$route.params.id_tipo_activo
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
          name : 'activofijo-tiposactivos'
        })
      })
    },

    actualizar(){
      var self = this;
      self.btnAction = 'Guardando, espere....'
      self.loading = true;
      tipo.actualizar(self.form, data =>{
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
              name : 'activofijo-tiposactivos'
            })
          },
          err =>{
            self.loading = false;
            self.errorMessages = err
            self.btnAction = 'Actualizar'
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
            /*this.$router.push({
                name : 'inventario-unidades-medida'
            })*/
          })
    },

    activar(tipoID){
      var self = this;
      self.$swal({
        title: '¿Esta seguro?',
        text: '¿Desea activar el tipo activo?',
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
            title : '¡Habilitado!',
            text : '¡Tipo activo habilitado!',
            customClass: {
              confirmButton : 'btn btn-success',
            }
          });
          tipo.activar({
            id_tipo_activo : tipoID
          }, data =>{
            this.$router.push({
              name : 'activofijo-tiposactivos'
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
              name : 'activofijo-tiposactivos'
            })
          })
        }else if (result.dismiss === 'cancel'){
          self.$swal({
            title: 'Cancelado',
            text: 'El tipo activo no ha sido habilitado',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }

      })
    },

    desactivar(tipoID){
      var self = this;
      self.$swal({
        title: 'Info!',
        text: '¿Esta seguro de desactivar el tipo activo?',
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
            title : '¡Eliminado!',
            text : '¡Tipo activo eliminado!',
            customClass: {
              confirmButton : 'btn btn-success',
            }
          });
          tipo.desactivar({id_tipo_activo: tipoID
              },
              data =>{
                this.$router.push({
                  name : 'activofijo-tiposactivos'
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
                  name : 'activofijo-tiposactivos'
                })
              })
        }else if (result.dismiss === 'cancel'){
          self.$swal({
            title: 'Cancelado',
            text: 'El tipo activo no ha sido eliminado',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }
      })
    },

  },
  mounted() {
    this.obtenerTipo();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>
