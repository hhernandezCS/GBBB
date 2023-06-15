<template>
  <b-card>
    <div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <label for="name"><span style="color: red">*</span>  Descripción:</label>
        <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite una descripción">
        <b-alert variant="danger" show>
          <ul class="error-messages">
            <li v-for="message in errorMessages.nombre">{{ message }}</li>
          </ul>
        </b-alert>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="">Monto minimo</label>
        <input type="number" class="form-control" v-model="form.valor">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="">Monto Maximo</label>
        <input type="number" class="form-control" v-model="form.valor_maximo">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="">Cuenta contable</label>
        <v-select
            label="nombre_cuenta_completo"
            v-model="form.cuenta_contable"
            :options="cuentas_contables"
            placeholder="Selecciona una cuenta contable"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

        >
          <template slot="no-options">
            No se han encontrado registros
          </template>
        </v-select>
      </div>
    </div>
  </div>
    <b-card-footer class="content-box-footer text-right mt-1">

      <b-row>
        <div class="col-md-6 text-left p-0">
          <template v-if="form.estado">
            <b-button type="submit" variant="danger" @click="desactivar(form.id_afectacion)">
              <feather-icon icon="Trash2Icon"></feather-icon> Eliminar
            </b-button>
          </template>
          <template v-else>
            <b-button type="submit" variant="success" @click="activar(form.id_afectacion)">
              <feather-icon icon="CheckIcon"></feather-icon> Habilitar
            </b-button>
          </template>

        </div>
        <div class="col-md-6">
          <router-link  :to="{name: 'crm-cajabanco-viaticos'}">
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
import afectacion from "../../../api/CajaBanco/Afectaciones";
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

      },
      btnAction: 'Actualizar',
      errorMessages: [],
      cuentas_contables: []
    }
  },
  methods: {

    obtenerAfectacion(){
      var self = this;
      afectacion.obtenerAfectacion({
        id_afectacion: this.$route.params.id_afectacion
      }, data =>{
        self.form = data.afectacion;
        self.cuentas_contables.forEach((cuenta_contablex, index)=>{
          if (cuenta_contablex.id_cuenta_contable === Number(self.form.id_cuenta_contable)){
            self.form.cuenta_contable = cuenta_contablex;
          }
        });
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
        /*this.$router.push({
          name : 'cajabanco-afectaciones'
        })*/
      })
    },

    actualizar(){
      var self = this;
      self.btnAction = 'Guardando, espere....'
      self.loading = true;
      afectacion.actualizar(self.form, data =>{
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
              name : 'cajabanco-afectaciones'
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

    activar(afectacionID){
      var self = this;
      self.$swal({
        title: '¿Esta seguro?',
        text: '¿Desea activar la afectación?',
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
            text : '¡Afectación habilitada!',
            customClass: {
              confirmButton : 'btn btn-success',
            }
          });
          afectacion.activar({
            id_afectacion : afectacionID
          }, data =>{
            this.$router.push({
              name : 'cajabanco-afectaciones'
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
              name : 'cajabanco-afectaciones'
            })
          })
        }else if (result.dismiss === 'cancel'){
          self.$swal({
            title: 'Cancelado',
            text: 'La afectación no ha sido habilitada',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }

      })
    },

    desactivar(afectacionID){
      var self = this;
      self.$swal({
        title: 'Info!',
        text: '¿Esta seguro de desactivar la afectación?',
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
            text : '¡Afectación eliminada!',
            customClass: {
              confirmButton : 'btn btn-success',
            }
          });
          afectacion.desactivar({id_afectacion: afectacionID
              },
              data =>{
                this.$router.push({
                  name : 'cajabanco-afectaciones'
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
                  name : 'cajabanco-afectaciones'
                })
              })
        }else if (result.dismiss === 'cancel'){
          self.$swal({
            title: 'Cancelado',
            text: 'La afectación no ha sido eliminado',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }
      })
    },
    nuevo(){
      const self = this;
      self.loading = true;
      afectacion.nueva({},data=>{
        self.cuentas_contables = data.cuentas_contables;
        self.loading= false;
      }, err=>{
        console.log(err);
        self.loading = false;
      })
    }

  },
  mounted() {
    this.nuevo();
    this.obtenerAfectacion();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>
