<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="name"><span style="color: red">*</span>  Código Grupo Activos:</label>
            <input type="text" class="form-control" v-model="form.codigo" placeholder="Dígite el código del Grupo Activos">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.descripcion">{{ message }}</li>
              </ul>
            </b-alert>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="name"><span style="color: red">*</span>  Nombre Grupo Activos:</label>
            <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite el nombre del Grupo Activos">
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
            <b-button type="submit" variant="danger" @click="desactivar(form.id_grupo_activo)">
              <feather-icon icon="Trash2Icon"></feather-icon> Eliminar
            </b-button>
          </template>
          <template v-else>
            <b-button type="submit" variant="success" @click="activar(form.id_grupo_activo)">
              <feather-icon icon="CheckIcon"></feather-icon> Habilitar
            </b-button>
          </template>

        </div>
        <div class="col-md-6">
          <router-link  :to="{name: 'activofijo-propietarios'}">
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
import grupo from "../../../api/ActivoFijo/Grupos";
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
        codigo: '',
      },
      btnAction: 'Actualizar',
      errorMessages: [],
      normativas: []
    }
  },
  methods: {

    obtenerGrupo(){
      var self = this;
      grupo.obtenerGrupo({
        id_grupo_activo: this.$route.params.id_grupo_activo
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
          name : 'activofijo-grupos'
        })
      })
    },

    actualizar(){
      var self = this;
      self.btnAction = 'Guardando, espere....'
      self.loading = true;
      grupo.actualizar(self.form, data =>{
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
              name : 'activofijo-grupos'
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

    activar(grupoID){
      var self = this;
      self.$swal({
        title: '¿Esta seguro?',
        text: '¿Desea activar el grupo?',
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
            text : '¡Grupo habilitado!',
            customClass: {
              confirmButton : 'btn btn-success',
            }
          });
          grupo.activar({
            id_grupo_activo : grupoID
          }, data =>{
            this.$router.push({
              name : 'activofijo-grupos'
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
              name : 'activofijo-grupos'
            })
          })
        }else if (result.dismiss === 'cancel'){
          self.$swal({
            title: 'Cancelado',
            text: 'El grupo no ha sido habilitado',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }

      })
    },

    desactivar(grupoID){
      var self = this;
      self.$swal({
        title: 'Info!',
        text: '¿Esta seguro de desactivar el grupo?',
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
            text : '¡Grupo eliminado!',
            customClass: {
              confirmButton : 'btn btn-success',
            }
          });
          grupo.desactivar({id_grupo_activo: grupoID
              },
              data =>{
                this.$router.push({
                  name : 'activofijo-grupos'
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
                  name : 'activofijo-grupos'
                })
              })
        }else if (result.dismiss === 'cancel'){
          self.$swal({
            title: 'Cancelado',
            text: 'El grupo no ha sido eliminado',
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
    this.obtenerGrupo();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>
