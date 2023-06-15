<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label for="name"><span style="color: red">*</span>  Descripcion:</label>
            <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite una descripción">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li v-for="message in errorMessages.descripcion">{{ message }}</li>
              </ul>
            </b-alert>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Normativa</label>
            <v-select
                label="concepto"
                v-model="form.normativa"
                :options="normativas"
                placeholder="Selecciona una normativa"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

            >
              <template slot="no-options">
                No se han encontrado registros
              </template>
            </v-select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Monto</label>
            <input type="number" class="form-control" v-model="form.monto">
          </div>
        </div>
      </div>
    </form>
    <b-card-footer class="content-box-footer text-right mt-1">
      <b-row>
        <div class="col-sm-12 text-lg-right">
          <router-link  :to="{name: 'cajabanco-viaticos'}">
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
import viatico from "../../../api/CajaBanco/Viaticos";
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
        normativa: '',
        monto: ''
      },
      normativas:[],
      errorMessages: [],
      btnAction: 'Registrar',
    }
  },
  methods: {
    registrar(){
      var self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      viatico.registrar(self.form, data =>{
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
              name : 'cajabanco-viaticos'
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
    nuevo(){
      const self = this;
      self.loading = true;
      viatico.nuevo({},data=>{
        self.normativas = data.normativas;
        self.loading= false;
      }, err=>{
        console.log(err);
        self.loading = false;
      })
    }


  },
  mounted() {
    this.nuevo();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
