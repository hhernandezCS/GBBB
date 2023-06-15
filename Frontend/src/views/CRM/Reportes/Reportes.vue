<template>
  <b-row>
    <div class="col-md-6">
      <b-card>
        <h3>
          Reporte de llamadas
        </h3>
        <b-form-group
          class="mt-1"
          label="Formato"
          label-for="h-formato-report"
          label-cols-md="4"
        >
          <select id="h-formato-report" v-model="form.format" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
            <option value="pdf">PDF</option>
            <option value="xls">XLS</option>
          </select>
        </b-form-group>
        <b-form-group
            label="Fecha inicial"
            label-for="h-f_inicial"
            label-cols-md="4"
        >
          <b-form-datepicker
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              local="es"
              @selected="onDateSelect"
              v-model="form.fecha_inicial"
              selected-variant="primary"
              class="mb-0"
              placeholder="f Inicinal"
          ></b-form-datepicker>
        </b-form-group>
        <b-form-group
            label="Fecha final"
            label-for="h-f_final"
            label-cols-md="4"
        >
          <b-form-datepicker
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              local="es"
              @selected="onDateSelect2"
              v-model="form.fecha_final"
              selected-variant="primary"
              class="mb-0"
              placeholder="f. Final"
          ></b-form-datepicker>
        </b-form-group>
        <b-col offset-md="4">
          <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              @click="downloadItem(form.format, form.fecha_inicial,form.fecha_final)"
          >
            Descargar
          </b-button>
          <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="reset"
              variant="outline-secondary"
              @click="cleanInputs"
          >
            Limpiar campos
          </b-button>
        </b-col>
      </b-card>
    </div>
    <!--Reporte de leads descartados-->
    <div class="col-md-6">
      <b-card>
        <h3>
          Reporte de leads descartados
        </h3>
        <b-form-group
          class="mt-1"
          label="Formato"
          label-for="h-formato-report"
          label-cols-md="4"
        >
          <select id="h-formato-report" v-model="formLeadsDescartados.format" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
            <option value="pdf">PDF</option>
            <option value="xls">XLS</option>
          </select>
        </b-form-group>
        <b-form-group
            label="Fecha inicial"
            label-for="h-f_inicial"
            label-cols-md="4"
        >
          <b-form-datepicker
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              local="es"
              @selected="onDateSelectDescartadosInicial"
              v-model="formLeadsDescartados.fecha_inicial"
              selected-variant="primary"
              class="mb-0"
              placeholder="f Inicinal"
          ></b-form-datepicker>
        </b-form-group>
        <b-form-group
            label="Fecha final"
            label-for="h-f_final"
            label-cols-md="4"
        >
          <b-form-datepicker
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              local="es"
              @selected="onDateSelectDescatadosFinal"
              v-model="formLeadsDescartados.fecha_final"
              selected-variant="primary"
              class="mb-0"
              placeholder="f.Final"
          ></b-form-datepicker>
        </b-form-group>
        <b-col offset-md="4">
          <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              @click="downloadReporteLeadsDescartados(formLeadsDescartados.format, formLeadsDescartados.fecha_inicial,formLeadsDescartados.fecha_final)"
          >
            Descargar
          </b-button>
          <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="reset"
              variant="outline-secondary"
              @click="cleanInputsDescartados"
          >
            Limpiar campos
          </b-button>
        </b-col>
      </b-card>
    </div>
    <!--Reporte de leads seguimientos-->
    <div class="col-md-6">
      <b-card>
        <h3>
          Reporte de seguimiento de leads
        </h3>
        <b-form-group
          class="mt-1"
          label="Formato"
          label-for="h-formato-report"
          label-cols-md="4"
        >
          <select id="h-formato-report" v-model="formLeadsSeguimiento.format" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
            <option value="pdf">PDF</option>
            <option value="xls">XLS</option>
          </select>
        </b-form-group>
        <b-form-group
            label="Fecha inicial"
            label-for="h-f_inicial"
            label-cols-md="4"
        >
          <b-form-datepicker
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              local="es"
              @selected="onDateSelectSeguimientoInicial"
              v-model="formLeadsSeguimiento.fecha_inicial"
              selected-variant="primary"
              class="mb-0"
              placeholder="f Inicinal"
          ></b-form-datepicker>
        </b-form-group>
        <b-form-group
            label="Fecha final"
            label-for="h-f_final"
            label-cols-md="4"
        >
          <b-form-datepicker
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
              local="es"
              @selected="onDateSelectSeguimientoFinal"
              v-model="formLeadsSeguimiento.fecha_final"
              selected-variant="primary"
              class="mb-0"
              placeholder="f. Final"
          ></b-form-datepicker>
        </b-form-group>
        <b-col offset-md="4">
          <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1"
              @click="downloadReporteLeadsSeguimiento(formLeadsSeguimiento.format, formLeadsSeguimiento.fecha_inicial,formLeadsSeguimiento.fecha_final)"
          >
            Descargar
          </b-button>
          <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="reset"
              variant="outline-secondary"
              @click="cleanInputsSeguimiento"
          >
            Limpiar campos
          </b-button>
        </b-col>
      </b-card>
    </div>
    <template v-if="loading">
      <BlockUI>
        <b-spinner variant="info" label="loading..."/>
      </BlockUI>
    </template>
  </b-row>
</template>

<script type="text/ecmascript-6">
import {
  BFormDatepicker,
  BRow,
  BCol,
  BCard,
  BCardFooter,
  BPaginationNav,
  BButton,
  VBTooltip,
  BFormCheckbox,
  BFormGroup,
  BBadge,
  BFormInput,
  BForm,
  BSpinner
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import servicio from '../../../api/CRM/Servicios'
import loadingImage from '../../../assets/images/loader/block50.gif'
import es from 'vuejs-datepicker/dist/locale/translations/es'
import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import axios from "axios";

export default {
  components: {
    BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
    BBadge,BForm,BFormInput,BSpinner
  },
  directives: {
    'b-tooltip': VBTooltip,
    Ripple
  },
  data() {
    return {
      loading: false,
      url: loadingImage,   //It is important to import the loading image then use here
      form:{
        format: 'pdf',
        fecha_inicial : moment(new Date()).format("YYYY-MM-DD"),
        fecha_final : moment(new Date()).format("YYYY-MM-DD")
      },
      formLeadsDescartados:{
        format: 'pdf',
        fecha_inicial : moment(new Date()).format("YYYY-MM-DD"),
        fecha_final : moment(new Date()).format("YYYY-MM-DD")
      },
      formLeadsSeguimiento:{
        format: 'pdf',
        fecha_inicial : moment(new Date()).format("YYYY-MM-DD"),
        fecha_final : moment(new Date()).format("YYYY-MM-DD")
      },
      servicios: [],
      total: 0,
      descargando : false
    }
  },
  methods: {

    onDateSelect(date) {
      this.form.fecha_inicial = moment(date).format("YYYY-MM-DD"); //
    },
    onDateSelect2(date) {
      this.form.fecha_final = moment(date).format("YYYY-MM-DD"); //
    },

    cleanInputs(){
      let self = this;
      self.form.format = "pdf";
      self.form.fecha_inicial = '';
      self.form.fecha_final = '';
    },

    cleanInputsDescartados(){
      let self = this;
      self.formLeadsDescartados.format = "pdf";
      self.formLeadsDescartados.fecha_inicial = '';
      self.formLeadsDescartados.fecha_final = '';
    },
    cleanInputsSeguimiento(){
      let self = this;
      self.formLeadsSeguimiento.format = "pdf";
      self.formLeadsSeguimiento.fecha_inicial = '';
      self.formLeadsSeguimiento.fecha_final = '';
    },

    downloadItem(extension,f_inicial, f_final){
      const self = this;
      if (!this.descargando) {
        self.loading = true;
        let url = 'crm/lead-seguimiento/reporte/' + extension +  '/' + f_inicial + '/' + f_final;
        this.descargando = true;
        axios.get(url, {responseType: 'blob'})
            .then(({data}) => {
                    let blob = new Blob([data], {type: 'application/pdf'})

                    extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'ReportesSeguimientosRangoFecha.' + extension;
                    link.click();
                    //this.descargando = false;
                    self.loading = false;
                    self.descargando = false;
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'Su archivo se ha descargado correctamente',
                          variant: 'success',
                        }
                      },{
                        position:'bottom-right'
                      });
            }).catch(function (error) {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Error descargando el archivo ' + error,
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
          self.descargando = false;
          self.loading = false;
        })
      } else {
        self.loading = false;
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'CheckIcon',
                text: 'Espere a que se complete la descarga anterior',
                variant: 'warning',
              }
            },
            {
              position: 'bottom-right'
            });
        self.descargando = false;
        self.loading = false;
      }
    },
    downloadReporteLeadsDescartados(extension,f_inicial, f_final){
      const self = this;
      if (!this.descargando) {
        self.loading = true;
        let url = 'crm/lead-seguimiento/reporte-leads-descartados/' + extension +  '/' + f_inicial + '/' + f_final;
        this.descargando = true;
        axios.get(url, {responseType: 'blob'})
            .then(({data}) => {
                    let blob = new Blob([data], {type: 'application/pdf'});

                    extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'ReporteLeadsDescartados.' + extension;
                    link.click()
                    //this.descargando = false;
                    self.loading = false;
                    self.descargando = false;
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'Su archivo se ha descargado correctamente',
                          variant: 'success',
                        }
                      },{
                        position:'bottom-right'
                      });
            }).catch(function (error) {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Error descargando el archivo ' + error,
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
          self.descargando = false;
          self.loading = false;
        })
      } else {
        self.loading = false;
        this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'CheckIcon',
                text: 'Espere a que se complete la descarga anterior',
                variant: 'warning',
              }
            },
            {
              position: 'bottom-right'
            });
        self.descargando = false;
        self.loading = false;
      }
    },
    onDateSelectDescartadosInicial(date) {
      this.formLeadsDescartados.fecha_inicial = moment(date).format("YYYY-MM-DD"); //
    },
    onDateSelectDescatadosFinal(date) {
      this.formLeadsDescartados.fecha_final = moment(date).format("YYYY-MM-DD"); //
    },

    downloadReporteLeadsSeguimiento(extension,f_inicial, f_final){
      const self = this;
      if (!this.descargando) {
        self.loading = true;
        let url = 'crm/lead-seguimiento/reporte-leads-seguimiento/' + extension +  '/' + f_inicial + '/' + f_final;
        this.descargando = true;
        axios.get(url, {responseType: 'blob'})
                .then(({data}) => {
                  let blob = new Blob([data], {type: 'application/pdf'});

                  extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                  let link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download = 'ReporteSeguimientoLeads.' + extension;
                  link.click();
                  //this.descargando = false;
                  self.loading = false;
                  self.descargando = false;
                  this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Su archivo se ha descargado correctamente',
                      variant: 'success',
                    }
                  },{
                    position:'bottom-right'
                  });
                }).catch(function (error) {
          this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Error descargando el archivo ' + error,
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
          self.descargando = false;
          self.loading = false;
        })
      } else {
        self.loading = false;
        this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'Espere a que se complete la descarga anterior',
                    variant: 'warning',
                  }
                },
                {
                  position: 'bottom-right'
                });
        self.descargando = false;
        self.loading = false;
      }
    },
    onDateSelectSeguimientoInicial(date) {
      this.formLeadsSeguimiento.fecha_inicial = moment(date).format("YYYY-MM-DD"); //
    },
    onDateSelectSeguimientoFinal(date) {
      this.formLeadsSeguimiento.fecha_final = moment(date).format("YYYY-MM-DD"); //
    },
  },
  /* components: {
     pagination: Pagination
   },*/
  mounted() {
  }
}
</script>
<style lang="scss" scoped>
@import '@core/scss/vue/libs/vue-select.scss';
@import '../../../@core/scss/vue/libs/vue-sweetalert';
.search-field {
  min-width: 120px;
}

.table {
  a {
    color: #2675dc;
  }

  .table-number {
    width: 60px;
  }

  .action {
    width: 180px;
  }

  .detail-link {
    width: 60px;
    position: relative;

    .link {
      width: 10px;
      height: 4px;
      margin-left: auto;
      margin-right: auto;
      cursor: pointer;
      margin-top: 8px;
      background-color: #595959;
      border: 1px solid #595959;

      &:before {
        content: "";
        width: 4px;
        height: 10px;
        left: 0px;
        right: 0px;
        cursor: pointer;
        margin: 0px auto;
        margin-top: -4px;
        position: absolute;
        background-color: #595959;
        border: 1px solid #595959;
      }
    }
  }
}
</style>
