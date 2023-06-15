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
            <v-select label="nombre_completo" v-model="form.trabajador" v-on:input="obtenerIngreso"
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
            :disabled="btnAction !== 'Registrar'"
            @click="registrar"
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
import asignacion from '@/api/Nomina/asignacion-ingreso-deduccion'
import trabajador from '@/api/Nomina/trabajadores'
import loadingImage from '../../../assets/images/loader/block50.gif'
import es from "vuejs-datepicker/dist/locale/translations/es";
import vSelect from "vue-select";
import Ripple from "vue-ripple-directive";
import area from "@/api/Nomina/areas";



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
      loading: false,
      msg: 'Cargando los datos, espere un momento',
      url: loadingImage,   //It is important to import the loading image then use here
      es: es,
      format: "dd-MM-yyyy",
      form: {
        detalleSolicitud: [],
        trabajador: [],
        planilla: [],
        ingreso: [],
        monto: 0,
      },
      trabajadores: [],
      planillas: [],
      ingresos: [],
      btnAction: 'Registrar',
      errorMessages: []
    }
  },
  methods: {
    nuevo() {
      var self = this
      self.loading = true;
      asignacion.nuevo({id_planilla_control: this.$route.params.id_planilla_control}, data => {
        self.trabajadores = data.trabajadores;
        self.planillas = data.planillas;
        self.ingresos = data.ingresos;
        self.form.planilla = data.planilla;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err)
      })
    },
    obtenerIngreso() {
      var self = this
      self.loading = true;
      this.form.detalleSolicitud = [];
      asignacion.obtenerIngreso({
        id_planilla_control: this.$route.params.id_planilla_control,
        id_trabajador: this.form.trabajador.id_trabajador
      }, data => {
        self.form.detalleSolicitud = data.ingresos;
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err)
      })
    },

    registrar() {
      self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      asignacion.registrar(self.form, data => {
        self.loading = false;
        this.$router.push({
          name: 'nomina-asignacion-ingresos'
        })
      }, err => {
        self.loading = false;
        self.errorMessages = err
        self.btnAction = 'Registrar'
      })
    },
    agregarDetalle() {
      let self = this;
      if (this.form.trabajador.id_trabajador > 0 && this.form.monto > 0) {
        if (this.form.planilla.id_planilla_control > 0 && this.form.ingreso.id_cat_ingreso_deduccion > 0) {
          let i = 0;
          let keyx = 0;
          /*if (self.form.detalleSolicitud) {
            self.form.detalleSolicitud.forEach((fila, key) => {
              if (self.form.descripcion === fila.descripcion) {
                i++;
                keyx = key;
              }
            });
          }*/
          if (i === 0) {
            this.form.detalleSolicitud.push({
              id_cat_ingreso_deduccion_trabajador: 0,
              estado: 1,
              id_planilla_control: this.form.planilla.id_planilla_control,
              id_sucursal: this.form.planilla.id_sucursal,
              id_trabajador: this.form.trabajador.id_trabajador,
              asignacion_ingreso: this.form.ingreso,
              codigo: this.form.ingreso.codigo,
              descripcion: this.form.ingreso.descripcion,
              id_cat_ingreso_deduccion: this.form.ingreso.id_cat_ingreso_deduccion,
              monto: this.form.monto,

            });
            this.form.ingreso = '';
            this.form.codigo = '';
            this.form.descripcion = '';
            this.form.monto = '';


          } else {
            alertify.warning("Ya existe un registro con la descripcion seleccionada", 5);
          }
        } else {
          alertify.warning("Verifique que ningún campo está vacío", 5);
        }

      } else {
        alertify.warning("Verifique que ningún campo está vacío", 5);
      }
    },
    eliminarLinea(item, index) {
      if (this.form.detalleSolicitud.length >= 1) {
        //this.form.detalleSolicitud.splice(index, 1);
        item.estado = 0;

      } else {
        this.form.detalleSolicitud = [];
      }
    },
    limpiarDetalle() {
      this.form.detalleSolicitud = [];
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

.btn-agregar {
  margin-top: 1.6rem;
}

.check-label2 {
  margin-top: 30px;
}
</style>