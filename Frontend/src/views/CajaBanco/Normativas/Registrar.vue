<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label for="name"><span style="color: red">*</span>  Concept:</label>
            <input type="text" class="form-control" v-model="form.concepto" placeholder="Dígite el concepto">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.nombre">{{ message }}</li>
              </ul>
            </b-alert>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Monto Maximo</label>
            <input type="number" class="form-control" v-model="form.monto_minimo">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Monto Maximo</label>
            <input type="number" class="form-control" v-model="form.monto_maximo">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="">Regulación de tiempo</label>
            <input type="text" class="form-control" v-model="form.tiempo">
          </div>
        </div>
      </div>
    </form>
    <b-card-footer class="content-box-footer text-right mt-1">
      <b-row>
        <div class="col-sm-12 text-lg-right">
          <router-link  :to="{name: 'cajabanco-Normativas'}">
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
import normativa from "../../../api/CajaBanco/Normativas";
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
        concepto: '',
        monto_minimo: '',
        monto_maximo: '',
        tiempo: ''
      },
      companias:[],
      users: [],
      medio_contactos: [],
      tipo_contactos: [],
      clasificacion_clientes: [],
      btnAction: 'Registrar',
      errorMessages: [],
      submitteTelTrab: [],
      submitteTelMovil: [],
      submitteMail:[],
      tel_number:'',
      tel_number2:'',
      mail:'',
      numberState:null,
      numberState2:null,
      mailState:null,
    }
  },
  methods: {
    registrar(){
      var self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      normativa.registrar(self.form, data =>{
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
              name : 'cajabanco-Normativas'
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
