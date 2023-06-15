<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="name">* Area:</label>
            <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite el nombre del area">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.descripcion">{{ message }}</li>
              </ul>
            </b-alert>

          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="">Dirección</label>
            <v-select
                :options="direcciones"
                label="descripcion"
                v-model="form.direccion"
            ></v-select>
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.direccion">{{ message }}</li>
              </ul>
            </b-alert>

          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for=""> Código</label>
            <input class="form-control" placeholder="Dígite el codigo de area" v-model="form.codigo">
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.codigo" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>


      <!--<div class="col-sm-12">
            <div class="form-group">
              <label for="">Cuenta Contable</label>
              <v-select
                :options="cuentas_contables"
                label="nombre_cuenta_completo"
                v-model="form.cuenta_contable"
              ></v-select>
              <ul class="error-messages">
                <li :key="message" v-for="message in errorMessages.cuenta_contable" v-text="message"></li>
              </ul>
            </div>
          </div>-->


      <div class="content-box-footer text-right">
        <router-link :to="{ name: 'nomina-areas' }" tag="button">
          <button class="btn btn-default" type="button">Cancelar</button>
        </router-link>
        <button :disabled="btnAction !== 'Registrar'" @click="registrar" class="btn btn-primary" type="button">
          {{ btnAction }}
        </button>
      </div>

      <template v-if="loading">
        <BlockUI :message="msg" :url="url"></BlockUI>
      </template>


    </form>
  </b-card>
</template>

<script type="text/ecmascript-6">
import {BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow} from 'bootstrap-vue'
import direccion from '../../../api/Nomina/direcciones'
import cuentas_contables from '@/api/contabilidad/cuentas-contables'
import area from '@/api/Nomina/areas'
import loadingImage from '../../../assets/images/loader/block50.gif'
import vSelect from "vue-select";


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
      loading: false,

      url: loadingImage,   //It is important to import the loading image then use here

      cuentas_contables: [],
      direcciones: [],
      form: {
        codigo: '',
        descripcion: '',
      },
      btnAction: 'Registrar',
      errorMessages: []
    }
  },
  methods: {
    obtenerTodasCuentasContables() {
      self = this;
      cuentas_contables.obtenerTodasCuentasContables(
          data => {
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
            self.direcciones = data;
          },
          err => {
            console.log(err);
          }
      );

    },

    registrar() {
      self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      area.registrar(self.form, data => {
        self.loading = false;
        this.$router.push({
          name: 'nomina-areas'
        })
      }, err => {
        self.loading = false;
        self.errorMessages = err
        self.btnAction = 'Registrar'
      })
    }
  },
  mounted() {
    this.obtenerTodasDirecciones();
    this.obtenerTodasCuentasContables();
  }
}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>