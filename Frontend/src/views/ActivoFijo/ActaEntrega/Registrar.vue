<template>
  <b-card>
    <form>
      <template>
        <div class="demo-spacing-0 mb-1">
          <b-alert
              variant="primary"
              show
          >
            <div class="alert-body">
              <span>Datos del trabajador.</span>
            </div>
          </b-alert>
        </div>
      </template>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Área Origen</label>
            <v-select
                v-model="area_origen"
                label="descripcion"
                :options="areas"
                @input="obtenerTrabajadoresOrigen"
                placeholder="Selecciona una área"
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
            <label for="">Trabajador Origen</label>
            <v-select
                v-model="trabajador_origen"
                label="nombre_completo"
                :options="trabajadores"
                @input="obtenerActivosTrabajador"
                placeholder="Selecciona un empleado"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

            >
              <template slot="no-options">
                No se han encontrado registros
              </template>
            </v-select>
          </div>
        </div>
      <template>
        <div class="">
          <div class="col-md-12 mt-2 ">
            <table class="table table-bordered table-hover table-responsive">
              <thead>
              <tr>
                <!--<th></th>-->
                <th>Código</th>
                <th>Descripción</th>
                <th>Tipo Activo</th>
                <th>Grupo Activo</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(activo_fijo, index) in activos_trabajador" :key="activo_fijo.id_activo">
                <!--<td style="width: 2%">
                    <button @click="eliminarLinea(activo_fijo, index)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>-->
                <td>{{ activo_fijo.codigo }}</td>
                <td>{{ activo_fijo.descripcion }}</td>
                <td>{{ activo_fijo.activo_tipo_activo?activo_fijo.activo_tipo_activo.descripcion :'N/A'}}</td>
                <td>{{ activo_fijo.activo_grupo?activo_fijo.activo_grupo.descripcion :'N/A'}}</td>
              </tr>
              <tr v-if="!activos_trabajador.length">
                <td class="text-center" colspan="5">El trabajador seleccionado no tiene activos asignados</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>
      </div>
    </form>
    <b-card-footer class="content-box-footer text-right mt-1">
      <b-row>
        <div class="col-sm-12 text-lg-right">
<!--          <router-link  :to="{name: 'cajabanco-SolicitudesPagos'}">
            <b-button type="submit" variant="secondary" class="mt-1 mr-1">
              Cancelar
            </b-button>
          </router-link>-->
          <b-button class="mt-1" type="submit" variant="primary" @click="descargarActaEntrega" :disabled="btnAction !== 'Generar'">
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
import numberasstring from '@/assets/custom-scripts/NumberAsString'
import roundNumber  from '@/assets/custom-scripts/Round'
import {
  BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow, BSpinner,
  BModal, VBToggle, VBTooltip, BFormGroup, BListGroup, BListGroupItem, BDropdown, VBModal,
  BDropdownDivider, BDropdownItem,BFormInput,BFormDatepicker} from 'bootstrap-vue'
import vSelect from 'vue-select'
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import activos from "../../../api/ActivoFijo/ActivoFijo";
import activo_fijo from "../../../api/ActivoFijo/ActivoFijo";
import axios from 'axios'
import Ripple from "vue-ripple-directive";
import es from 'vuejs-datepicker/dist/locale/translations/es'

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
    BFormInput,
    BFormDatepicker,
  },
  directives : {
    Ripple,
    'b-tooltip': VBTooltip,
    'b-toggle' : VBToggle,
    'b-modal' : VBModal
  },
  data() {
    return {
      loading:true,
      msg: 'Cargando los datos, espere un momento',
      url : loadingImage,   //It is important to import the loading image then use here
      mostrar_detalles_vehiculo:false,

      areas:[],
      area_origen:[],
      area_destino:[],

      trabajadores:[],
      trabajador_origen:[],

      trabajadores_destino:[],
      trabajador_destino:[],

      activos_trabajador:[],
      tipo_traslado:'',
      formatoActa:'pdf',

      es: es,
      format: "dd-MM-yyyy",

      btnAction: 'Generar',
      errorMessages: []
    }
  },
  methods: {
    eliminarLinea(item, index) {
      var self = this;
      if (this.activos_trabajador.length >= 1) {
        this.activos_trabajador.splice(index, 1);
      }else{
        this.activos_trabajador=[];
      }
    },


    obtenerTrabajadores(){
      var self = this;
      self.trabajadores_destino=[];
      self.trabajador_destino=[];
      if(self.area_destino){
        self.trabajadores_destino = self.area_destino.trabajadores_area;
      }
    },

    obtenerTrabajadoresOrigen(){
      var self = this;
      self.activos_trabajador=[];
      self.trabajadores=[];
      self.trabajador_origen=[];
      if(self.area_origen){
        self.trabajadores = self.area_origen.trabajadores_area;
      }
    },

    obtenerActivosTrabajador() {
      var self = this
      self.loading=true;
      self.activos_trabajador=[];
      if(self.trabajador_origen){
        activo_fijo.obtenerActivosTrabajador({
          id_trabajador: self.trabajador_origen.id_trabajador
        }, data => {
          self.activos_trabajador = data;
          self.loading = false;
        }, err => {
          alertify.error(err.id_trabajador[0], 5);
          this.$router.push({
            name: 'listado-activos-fijos'
          });
        })
      }
    },

    reasignar() {
      var self = this
      self.btnAction = 'Guardando, espere......'
      if(self.activos_trabajador.length){
        if(self.trabajador_origen.id_trabajador !== self.trabajador_destino.id_trabajador){
          self.$swal.fire({
            title: 'Esta seguro de trasladar todos los activos al trabajador especificado?',
            text: "Se registrarán los cambios",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, confirmar',
            cancelButtonText:'Cancelar'
          }).then((result) => {
            if (result.value) {
              self.loading = true;
              activo_fijo.reasignarLote(
                  {
                    activos: self.activos_trabajador,
                    activo_trabajador_destino:self.trabajador_destino,
                  },
                  data => {
                    alertify.success("Activo Fijo actualizado correctamente", 5);
                    this.$router.push({
                      name: 'listado-activos-fijos'
                    })
                  }, err => {
                    self.loading = false;
                    self.errorMessages = err
                    self.btnAction = 'Guardar'
                  })
            }else{
              self.loading = false;
              self.btnAction = "Guardar";
            }
          })
        }else{
          alertify.warning("Se deben seleccionar trabajadores distintos", 5);
          self.loading = false;
          self.btnAction = 'Guardar'
        }

      }else{
        alertify.warning("El detalle de activos debe contener al menos un elemento", 5);
        self.loading = false;
        self.btnAction = 'Guardar'
      }
    },
    descargarActaEntrega () {
      if(this.trabajador_origen.id_trabajador){
        if(!this.loading){
          var self=this;
          self.loading=true;
          alertify.success("Descargando datos, espere un momento.....",5);
          axios.post('activosfijos/acta-entrega/reporte',
              {
                id_trabajador : self.trabajador_origen.id_trabajador,
                extension : self.formatoActa
              }, { responseType: 'blob' })
              .then(({ data }) => {
                let blob = new Blob([data], { type: 'application/pdf' })
                self.formatoActa === 'xls' ? blob = new Blob([data], { type: 'application/octet-stream' }) : blob = new Blob([data], { type: 'application/pdf' });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob)
                link.download = 'ActaEntregaActivo.'+ self.formatoActa;
                link.click()
                this.loading=false;
              }) .catch(function (error) {
            alertify.error("Error Descargando archivo.....",5);
            self.loading=false;
          })
        }else{
          alertify.warning("Espere a que se complete la descarga anterior",5);
        }
      }else{ alertify.warning("Por favor seleccione un trabajador.",5);}
    },
    nuevo() {
      var self = this;
      activos.nuevo({}, data => {
            self.areas = data.areas;
            self.loading = false;
          },
          err => {
            console.log(err);
          })

    },
  },
  mounted() {
    this.nuevo();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>
