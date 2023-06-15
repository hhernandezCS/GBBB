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
        <div class="col-sm-12 text-lg-right">
          <router-link  :to="{name: 'activofijo-propietarios'}">
            <b-button type="submit" variant="secondary" class="mt-1 mr-1">
              Cancelar
            </b-button>
          </router-link>
          <b-button class="mt-1" type="submit" variant="primary" @click="registrar" :disabled="btnAction !== 'Registrar'">
            {{btnAction}}
          </b-button>
        </div>
      </b-row>
    </b-card-footer>
    <template v-if="loading">
      <BlockUI>
        <b-spinner variant="info" label="loading..."/>
      </BlockUI>
    </template>
  </b-card>
</template>

<script type="text/ecmascript-6">
import loadingImage from '../../../assets/images/loader/block50.gif'
import {
  BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow, BSpinner,
  BModal, VBToggle, VBTooltip, BFormGroup, BListGroup, BListGroupItem, BDropdown, VBModal,
  BDropdownDivider, BDropdownItem,BFormInput} from 'bootstrap-vue'
import vSelect from 'vue-select'
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import propietario from "../../../api/ActivoFijo/Propietarios";
import axios from 'axios'
import Ripple from "vue-ripple-directive";

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
    BSpinner,
    BModal,
    VBTooltip,
    VBToggle,
    BFormGroup,
    BListGroup,
    BListGroupItem,
    BDropdown,
    BDropdownItem,
    BDropdownDivider,
    BFormInput
  },
  directives : {
    Ripple,
    'b-tooltip': VBTooltip,
    'b-toggle' : VBToggle,
    'b-modal' : VBModal
  },
  data() {
    return {
      loading:false,
      msg: 'Guardando los datos, espere un momento',
      url : loadingImage,   //It is important to import the loading image then use here
      form: {
        descripcion: '',
        codigo:''
      },
      errorMessages: [],
      btnAction: 'Registrar',
    }
  },
  methods: {
    registrar(){
      var self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      propietario.registrar(self.form, data =>{
            self.loading = false;
            this.$toast({
                  component : ToastificationContent,
                  props: {
                    title : 'Notificación',
                    icon : 'CheckIcon',
                    text : 'Datos registrados correctamente.',
                    variant : 'success',
                  }
                },
                {
                  position : 'bottom-right'
                });
            this.$router.push({
              name : 'activofijo-propietarios'
            })
          },
          err =>{
            self.loading = false;
            self.errorMessages = err
            self.btnAction = 'Registrar'
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
            /*this.$router.push({
                name : 'inventario-tipos-proveedores'
            })*/
          })
    },


  },
  mounted() {

  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
