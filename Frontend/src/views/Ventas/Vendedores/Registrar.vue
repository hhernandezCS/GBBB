<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label for="name">* Nombre Completo:</label>
            <input type="text" class="form-control" v-model="form.nombre_completo"
                   placeholder="Dígite el nombre y apellido">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.nombre_completo">{{ message }}</li>
              </ul>
            </b-alert>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label for="name">* Dirección:</label>
            <input type="text" class="form-control" v-model="form.direccion" placeholder="Dígite una dirección">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.direccion">{{ message }}</li>
              </ul>
            </b-alert>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label for="name">* Teléfono:</label>
            <input type="tel" class="form-control" v-model="form.telefono" placeholder="Dígite un número de teléfono">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.telefono">{{ message }}</li>
              </ul>
            </b-alert>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label for="name">* E-mail:</label>
            <input type="email" class="form-control" v-model="form.email" placeholder="Dígite un correo electrónico ">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.email">{{ message }}</li>
              </ul>
            </b-alert>

          </div>
        </div>
        <div class="col-sm-3">
          <b-form-group>
            <label for=""> Usuario</label>
            <v-select
                    @input="seleccionarusuario"
                    v-model="form.user"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="usuarios"
                    label="name"
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
      </div>

    </form>
    <b-card-footer class="content-box-footer text-right mt-1">

      <router-link :to="{name: 'ventas-vendedores'}">
        <b-button type="submit" variant="secondary" class="mx-1">
          Cancelar
        </b-button>
      </router-link>


      <b-button type="submit" variant="primary" @click="registrar" :disabled="btnAction !== 'Registrar'">
        {{ btnAction }}
      </b-button>


    </b-card-footer>
    <template v-if="loading">
      <BlockUI :url="url"></BlockUI>
    </template>
  </b-card>
</template>

<script type="text/ecmascript-6">
import loadingImage from '../../../assets/images/loader/block50.gif'
import {BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BFormGroup,BRow} from 'bootstrap-vue'
import vSelect from 'vue-select'
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import marca from "../../../api/Inventario/marcas";
import axios from 'axios'
import vendedor from "../../../api/Ventas/vendedores";
import grupos from "@/api/Inventario/grupo";
import usuarios from "@/api/admon/usuarios";

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
    BFormGroup,
  },
  data() {
    return {
      loading: false,
      msg: 'Guardando los datos, espere un momento',
      url: loadingImage,   //It is important to import the loading image then use here
      form: {
        nombre_completo: '',
        direccion: '',
        telefono: '',
        email: '',
        user: {}

      },

      usuarios:[],
      btnAction: 'Registrar',
      errorMessages: [],

    }
  },
  methods: {
    registrar() {
      var self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      vendedor.registrar(self.form, data => {
            self.loading = false;
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'Datos registrados correctamente.',
                    variant: 'success',
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.$router.push({
              name: 'ventas-vendedores'
            })
          },
          err => {
            self.loading = false;
            self.errorMessages = err
            self.btnAction = 'Registrar'
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Ha ocurrido un error, intentelo de nuevo',
                    variant: 'warning',

                  }
                },
                {
                  position: 'bottom-right'
                });
            /*this.$router.push({
                name : 'inventario-tipos-proveedores'
            })*/
          })
    },
    obtenerUsuarios() {
      var self = this;
      self.loading = true;
      usuarios.obtenerTodos(data => {
        self.usuarios = data;
        self.loading = false;
      }, err => {
        self.loading = false;

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
    seleccionarusuario(e) {
      const self = this;

      self.obtenerUsuarios();
    },


  },
  mounted() {
    this.obtenerUsuarios();
  }
}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
