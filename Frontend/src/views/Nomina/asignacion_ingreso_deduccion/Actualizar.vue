<template>
  <b-card>
    <form>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for>Planilla</label>
            <v-select label="planilla" v-model="form.planilla" :disabled="false" :options="planillas"></v-select>
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.planilla" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for>Empleado</label>
            <v-select label="nombre_completo" v-model="form.trabajador" v-on:input="limpiarDetalle()"
                      :options="trabajadores"></v-select>
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.trabajador" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for=""> Area</label>
            <v-select label="descripcion" :disabled="true" v-model="form.trabajador.trabajador_area"
                      :options="trabajadores"></v-select>
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.area" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for=""> Cargo</label>
            <v-select label="descripcion" :disabled="true" v-model="form.trabajador.trabajador_cargo"
                      :options="trabajadores"></v-select>
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.cargo" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for>Ingreso/Deducción</label>
            <v-select label="ingreso" v-model="form.ingreso" :options="ingresos"></v-select>
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.ingreso" v-text="message"></li>
            </ul>
          </div>
        </div>
      </div>

      <b-alert
          class="mb-2 mt-2"
          show
          variant="success"
      >
        <div class="alert-body">
          <span><strong>Detalle de la deduccion</strong></span>
        </div>
      </b-alert>
      <b-row>

        <div class="col-sm-6">
          <div class="form-group">
            <label for=""> Monto</label>
            <input class="form-control" :disabled="false" type="number" min="0" v-model="form.monto">
            <ul class="error-messages">
              <li :key="message" v-for="message in errorMessages.monto" v-text="message"></li>
            </ul>
          </div>
        </div>

        <div class="col-sm-2">
          <div class="form-group">
            <label for>&nbsp;</label>
            <b-button
                @click="agregarDetalle"
                class="btn-agregar"
                ref="agregar"
                v-b-tooltip.hover.top="'Agregar deduccion al detalle'"
                variant="info"
            >
              <feather-icon icon="PlusCircleIcon"></feather-icon>
              Agregar
            </b-button>
          </div>
        </div>

      </b-row>

      <b-row>
        <div class="col-sm-12">
          <b-alert show variant="danger">
            <ul class="error-messages">
              <li
                  :key="message"
                  v-for="message in errorMessages.detalleSolicitud"
                  v-text="message"
              />
            </ul>
          </b-alert>


          <table class="table table-responsive">
            <thead>
            <tr>
              <th class="table-number"></th>
              <!--<th style="min-width:300px" >Tipo de solicitud</th>-->
              <th class="table-number">Ingreso/Deducción</th>
              <th class="table-number">Código</th>
              <th class="table-number">Descripción</th>
              <th class="table-number">Monto</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(item, index) in form.detalleSolicitud">
              <template v-if="item.estado === 1">
                <tr>
                  <td style="width: 1%">
                    <b-button @click="eliminarLinea(item, index)">
                      <feather-icon icon="TrashIcon"/>
                    </b-button>
                  </td>
                  <td>
                    <v-select label="ingreso" :disabled="true"
                              v-model="item.asignacion_ingreso.cve_ingreso_deduccion"
                              :options="ingresos"></v-select>
                    <ul class="error-messages">
                      <li :key="message" v-for="message in errorMessages[`detalleSolicitud.${index}.ingreso`]"
                          v-text="message"></li>
                    </ul>
                  </td>
                  <td>
                    <input class="form-control" :disabled="true" type="number" min="0"
                           v-model="item.asignacion_ingreso.codigo">
                    <ul class="error-messages">
                      <li :key="message" v-for="message in errorMessages[`detalleSolicitud.${index}.codigo`]"
                          v-text="message"></li>
                    </ul>
                  </td>
                  <td>
                    <input class="form-control" :disabled="true" type="text"
                           v-model="item.asignacion_ingreso.descripcion">
                    <ul class="error-messages">
                      <li :key="message" v-for="message in errorMessages[`detalleSolicitud.${index}.descripcion`]"
                          v-text="message"></li>
                    </ul>
                  </td>
                  <td>
                    <input class="form-control" :disabled="true" type="number" min="0" v-model="item.monto">
                    <ul class="error-messages">
                      <li :key="message" v-for="message in errorMessages[`detalleSolicitud.${index}.monto`]"
                          v-text="message"></li>
                    </ul>
                  </td>
                  <!--<td>
                                        <strong> {{sub_total(item) | formatMoney(2)}}</strong>
                                        </td>-->

                </tr>
              </template>
              <tr></tr>
            </template>
            </tbody>
            <tfoot>
            <tr>
              <!--  <td colspan="2"></td>
                <td>Total</td>
                <td> <strong> {{calcular_total | formatMoney(2)}}</strong></td> -->
            </tr>
            </tfoot>
          </table>
        </div>
      </b-row>
      <b-card-footer class="text-lg-right text-center">
        <router-link :to="{ name:'nomina-asignacion-ingresos' }">
          <b-button
              class="mx-lg-1 mx-0"
              type="button"
              variant="secondary"
          >
            Cancelar
          </b-button>
        </router-link>
        <b-button
            :disabled="btnAction !== 'Actualizar'"
            @click="actualizar"
            class="mx-lg-1 mx-0"
            variant="success"
        >{{ btnAction }}
        </b-button>
      </b-card-footer>


      <template v-if="loading">
        <BlockUI :url="url"/>
      </template>
    </form>
  </b-card>








</template>

<script type="text/ecmascript-6">
import solicitud from '@/api/Nomina/asignacion-ingreso-deduccion'
import loadingImage from '../../../assets/images/loader/block50.gif'
import es from "vuejs-datepicker/dist/locale/translations/es";
import {
  BAlert,
  BButton,
  BCard,
  BCardFooter,
  BForm,
  BFormCheckbox,
  BFormCheckboxGroup,
  BFormDatepicker,
  BFormGroup,
  BFormInput,
  BFormSelect,
  BListGroup,
  BListGroupItem,
  BModal,
  BPaginationNav,
  BRow,
  VBModal,
  VBTooltip
} from 'bootstrap-vue'
import vSelect from "vue-select";
import Ripple from "vue-ripple-directive";
import asignacion from "@/api/Nomina/asignacion-ingreso-deduccion";

export default {
  components: {
    BCard,
    BCardFooter,
    BPaginationNav,
    BFormCheckbox,
    BFormGroup,
    BFormInput,
    vSelect,
    BRow,
    BButton,
    BFormCheckboxGroup,
    BFormDatepicker,
    BAlert,
    BFormSelect,
    BModal,
    BListGroup,
    BListGroupItem,
    BForm,
  },

  directives: {
    'b-tooltip': VBTooltip,
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      loading: true,
      url: loadingImage,   //It is important to import the loading image then use here
      es: es,
      format: "dd-MM-yyyy",
      form: {
        detalleSolicitud: [],
        trabajador: [],
        planilla: [],
        ingreso: [],
        monto: 0,
        solicitud_detalle:[],
        num_solicitud: '',
        cantidad_dias: '',
        justificacion: '',
        costo_vacaciones : '',
        total_dias : '',
        saldo_dias : '',
        dias_meses : 30,
        salario_basico : 0,
      },
      trabajadores:[],
      ingresos:[],
      planillas:[],
      btnAction: 'Guardar',
      btnActionConf: 'Confirmar',
      errorMessages: []
    }
  },
  methods: {
    obtenerSolicitud() {
       self = this
      //Array(1,2,3).includes(Number(self.$route.params.id_zona)) ? self.SoloLectura = true : self.SoloLectura = false
      solicitud.obtenerAsignacion({
        id_cat_ingreso_deduccion_trabajador: this.$route.params.id_cat_ingreso_deduccion_trabajador,

      }, data => {
        self.form = data;
        self.trabajadores = data.trabajadores;
        self.form.detalleSolicitud = data.ingresos;
        self.planillas = data.planillas;
        self.form.planilla = data.planilla;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err)
      })
    },






    actualizar(estado) {
      var self = this
      self.loading = true;
      self.btnAction = 'Guardando, espere......'
      self.btnActionConf = 'Guardando, espere......'
      self.form.estado = estado;
      solicitud.actualizar(self.form, data => {
        alertify.success("Datos actualizados correctamente",5);
        this.$router.push({
          name: 'listado-vacaciones'
        })
      }, err => {
        self.loading = false;
        self.errorMessages = err
        self.btnAction = 'Guardar'
        self.btnActionConf = 'Confirmar'
      })
    },
    agregarDetalle() {
      let self = this;
      if(this.form.cantidad_dias){
        let i = 0;
        let keyx = 0;
        if(self.form.solicitud_detalle){
          self.form.solicitud_detalle.forEach((fila, key) => {
            if(self.form.cantidad_dias===fila.cantidad_dias){
              i++;
              keyx = key;
            }
          });
        }
        if(i === 0) {
          this.form.solicitud_detalle.push({
            fecha_desde: this.form.fecha_desdex,
            fecha_hasta: this.form.fecha_hastax,
            cantidad_dias: this.form.cantidad_dias,
            anio: this.form.anio,
            mes: this.form.mes,
          });
          /*	this.form.fecha_desdex='';
                          this.form.fecha_hastax='';*/
          this.form.cantidad_dias='';
          this.form.anio='';
          this.form.mes='';

        }else{
          alertify.warning("Ya existe un registro con la fecha seleccionada",5);
        }

      }else{
        alertify.warning("Los campos no pueden estar vacíos",5);
      }
    },
    eliminarLinea(item, index) {
      if (this.form.solicitud_detalle.length >= 1) {
        this.form.solicitud_detalle.splice(index, 1);

      }else{
        this.form.solicitud_detalle=[];
      }
    },
    limpiarDetalle(){
      this.form.detalleSolicitud=[];
    },
    desactivar(zonaId) {
      var self = this;
      self.$swal.fire({
        title: 'Esta seguro de cambiar el estado?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, confirmar',
        cancelButtonText:'Cancelar'
      }).then((result) => {
        if (result.value) {
          //var self = this
          feriado.desactivar({
            id_feriado: zonaId
          }, data => {
            alertify.warning("Día feriado desactivado correctamente", 5);
            this.$router.push({
              name: 'listado-feriado'
            })
          }, err => {
            console.log(err)
          })
        }else{
          self.btnAction = "Guardar";
        }
      })
    },
    activar(zonaId) {
      var self = this;
      self.$swal.fire({
        title: 'Esta seguro de cambiar el estado?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, confirmar',
        cancelButtonText:'Cancelar'
      }).then((result) => {
        if (result.value) {
          var self = this
          feriado.activar({
            id_feriado: zonaId
          }, data => {
            alertify.success("Nivel estudio activado correctamente", 5);
            this.$router.push({
              name: 'listado-feriado'
            })
          }, err => {
            console.log(err)
          })
        }else{
          self.btnAction = "Guardar";
        }
      })
    }
  },
  mounted() {
    this.obtenerSolicitud()
  }
}
</script>