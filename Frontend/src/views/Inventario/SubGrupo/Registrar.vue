<template>
  <b-card>
    <form>
      <b-row>
      <div class="col-sm-12">
        <b-form-group>
          <label for="">*Grupo</label>
          <v-select
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

              :options="grupos"
              label="descripcion"
              v-model="form.grupo"

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

      <router-link :to="{name: 'inventario-subgrupo'}">
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
import {BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BFormGroup, BRow} from 'bootstrap-vue'
import vSelect from 'vue-select'
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import axios from 'axios'
import subgrupo from "@/api/Inventario/subgrupo";
import grupos from "@/api/Inventario/grupo";

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
    BFormGroup
  },
  data() {
    return {
      loading: false,
      msg: 'Guardando los datos, espere un momento',
      url: loadingImage,   //It is important to import the loading image then use here
      grupos: [],
      form: {
        codigo: '',
        descripcion: '',

      },
      btnAction: 'Registrar',
      errorMessages: [],

    }
  },
  methods: {
    registrar() {
      var self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      subgrupo.registrar(self.form, data => {

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
              name: 'inventario-subgrupo'
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


  },
  mounted() {

    this.obtenerTodosgrupos();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
