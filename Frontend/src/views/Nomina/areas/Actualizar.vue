<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for=""> Código</label>
            <input disabled class="form-control" placeholder="Dígite el codigo de area" v-model="form.codigo">
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.codigo" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="">Dirección</label>
            <v-select
                :disabled="true"
                :options="direcciones"
                label="descripcion"
                v-model="form.direccion_area"
            ></v-select>
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.direccion" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for=""> Descripción</label>
            <input class="form-control" placeholder="Dígite el nombre del area" v-model="form.descripcion">
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <b-card-footer class="content-box-footer text-right mt-1">

        <b-row>
          <div class="col-md-6 text-left p-0">
            <template v-if="form.estado">
              <b-button type="submit" variant="danger" @click="desacti(form.id_area)">
                <feather-icon icon="Trash2Icon"></feather-icon>
                Eliminar
              </b-button>
            </template>
            <template v-else>
              <b-button type="submit" variant="success" @click="acti(form.id_area)">
                <feather-icon icon="CheckIcon"></feather-icon>
                Habilitar
              </b-button>
            </template>

          </div>
          <div class="col-md-6">
            <router-link :to="{name: 'nomina-areas'}">
              <b-button type="submit" variant="secondary" class="mx-1">
                Cancelar
              </b-button>
            </router-link>


            <b-button type="submit" variant="primary" @click="actualizar" :disabled="btnAction !== 'Actualizar'">
              {{ btnAction }}
            </b-button>
          </div>
        </b-row>

      </b-card-footer>


      <template v-if="loading">
        <BlockUI :url="url"></BlockUI>
      </template>
    </form>
  </b-card>

</template>

<script type="text/ecmascript-6">
import {BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow} from 'bootstrap-vue'
import area from '@/api/Nomina/areas'
import direccion from '../../../api/Nomina/direcciones'
import cuentas_contables from '../../../api/contabilidad/cuentas-contables'
import loadingImage from '../../../assets/images/loader/block50.gif'
import vSelect from "vue-select";
import grupo from "@/api/Inventario/grupo";
import ToastificationContent from "@core/components/toastification/ToastificationContent";
import zona from "@/api/admon/zonas";

export default {
  components: {
    BButton,
    BAlert,
    BFormCheckbox,
    vSelect,
    BFormSelect,
    BCard,
    BCardFooter,
    BRow,
  },
  data() {
    return {
      loading: true,
      url: loadingImage,   //It is important to import the loading image then use here
      cuentas_contables: [],
      direcciones: [],
      form: {
        area: '',
        direccion_area: '',
        cuenta_contable_area: '',
        estado: '',
        descripcion: '',
        codigo: '',

      },
      btnAction: 'Actualizar',
      errorMessages: []
    }
  },
  methods: {
    obtenerTodasCuentasContables() {
      self = this;
      cuentas_contables.obtenerTodasCuentasContables(
          data => {
            //self.form.cuenta_contable_area = data;
            self.cuentas_contables = data;
          },
          err => {
            console.log(err);
          }
      );

    },

    obtenerTodasDirecciones() {
      self = this;
      direccion.obtenerTodasDirecciones(
          data => {
            //self.form.direccion_area = data;
            self.direcciones = data;

          },
          err => {
            console.log(err);
          }
      );

    },
    obtenerArea() {
      self = this
      area.obtenerArea({
        id_area: self.$route.params.id_area
      }, data => {
        self.form = data
        self.loading = false;
      }, err => {
        alertify.error(err.id_area[0], 5);
        this.$router.push({
          name: 'nomina-areas'
        });
      })


    },
    actualizar() {
      self = this
      self.btnAction = 'Guardando, espere....'
      self.loading = true;
      area.actualizar(self.form, data => {
            self.loading = false;
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'Datos actualizados correctamente.',
                    variant: 'success',
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: 'nomina-areas'
            })
          },
          err => {
            self.loading = false;
            self.errorMessages = err
            self.btnAction = 'Guardar'
            this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: 'Ha ocurrido un error, intentelo de nuevo',
                variant: 'warning',
                position: 'bottom-right'
              }
            });

          })

    },


    desacti(areaId) {
      self = this;
      self.$swal({
        title: 'Info!',
        text: '¿Esta seguro de desactivar el area?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Si, confirmar',
        cancelButtonText: 'Cancelar',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-danger ml-1',
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          self.$swal({
            icon: 'success',
            title: '¡Eliminada!',
            text: '¡El area ha sido desactivada!',
            customClass: {
              confirmButton: 'btn btn-success',
            }
          });
          area.desacti({
                id_area: areaId
              },
              data => {
                this.$router.push({
                  name: 'nomina-areas'
                })
              }, err => {
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Ha ocurrido un error, intentelo de nuevo',
                    variant: 'warning',
                    position: 'bottom-right'
                  }
                });
                this.$router.push({
                  name: 'nomina-areas'
                })
              })
        } else if (result.dismiss === 'cancel') {
          self.$swal({
            title: 'Cancelado',
            text: 'El area no ha sido eliminada',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }
      })
    },

    acti(areaId) {

      self = this;
      self.$swal.fire({
        title: 'Esta seguro de cambiar el estado?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          area.acti({
            id_area: areaId
          }, data => {
            alertify.success("Área activada correctamente", 5);
            this.$router.push({
              name: 'nomina-areas'
            })
          }, err => {
            console.log(err)
          })
        } else {
          self.btnAction = "Actualizar";
        }
      })
    }
  },
  mounted() {
    this.obtenerTodasDirecciones();
    this.obtenerTodasCuentasContables();
    this.obtenerArea();
  }
}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>