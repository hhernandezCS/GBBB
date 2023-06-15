<template>
  <b-card>
    <b-row>

      <div  @keyup.enter="filter.page = 1;obtener();"
           class="col-md-12 sm-text-center form-inline justify-content-end">
        <b-form-checkbox v-model="filter.search.status" class="custom-control-primary mr-1">
          Mostrar Todos
        </b-form-checkbox>
        <select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
          <option value="beneficiario">Benficiario</option>
        </select>
        <input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar"
               type="text">
        <b-button variant="info" @click="filter.page = 1;obtener();"
                  v-b-tooltip.hover.top="'Buscar!'">
          <feather-icon icon="SearchIcon"></feather-icon>
        </b-button>
      </div>

    </b-row>
    <div class="table-responsive mt-3">
      <table class="table table-striped table-bordered">
        <thead>
        <tr>

          <th>Beneficiario</th>
          <th>Concepto</th>
          <th>Fecha Solicitud</th>
          <th>Moneda Solicitada</th>
          <th>Monto Solicitado</th>
          <th style="min-width:300px">Cuenta Bancaria</th>
          <th colspan="2" class="text-center action">Opciones</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="solicitud in solicitudes">
          <tr :key="solicitud.id_solicitud_pago">
            <td>{{ solicitud.beneficiario }}</td>
            <td>{{ solicitud.concepto}}</td>
            <td>{{ solicitud.fecha_solicitud | formatDate}}</td>
            <td>{{ solicitud.moneda_solicitud.descripcion }}</td>
            <td>{{ solicitud.monto }}</td>
            <td style="width: 30%">
              <select :disabled="solicitud.estado!==1" class="form-control" v-model="solicitud.id_cuenta_bancaria">
                <option :key="0" :value="null">{{ 'Ninguna'}} </option>
                <option :key="cuenta.id_cuenta_bancaria" :value="cuenta.id_cuenta_bancaria" v-for="cuenta in cuentas_bancarias">{{ cuenta.numero_cuenta}}
                </option>
              </select>
            </td>
            <td class="text-center">
              <template v-if="solicitud.estado === 1">
                <a class="mr-1"  @click="cambiarEstadoSolicitudPago(0,solicitud.id_solicitud_pago,solicitud.id_cuenta_bancaria)"
                          v-b-tooltip.hover.top="'Reachar solicitud'">
                  <feather-icon color="#ea5455" icon="Trash2Icon"></feather-icon>
                </a>
              </template>
              <template v-else>
                <a class="mr-1"  @click="cambiarEstadoSolicitudPago(0,solicitud.id_solicitud_pago,solicitud.id_cuenta_bancaria)"
                          v-b-tooltip.hover.top="'Anular solicitud'">
                  <feather-icon color="#ea5455" icon="Trash2Icon"></feather-icon>
                </a>
              </template>

              <template v-if="solicitud.estado===1">
                <a  @click="cambiarEstadoSolicitudPago(2,solicitud.id_solicitud_pago,solicitud.id_cuenta_bancaria)"
                          v-b-tooltip.hover.top="'Autorizar solicitud'">
                  <feather-icon color="#28c76f" icon="CheckCircleIcon"></feather-icon>
                </a>
              </template>
              <template v-else>

              </template>
            </td>
          </tr>

        </template>
        <tr v-if="!solicitudes.length">
          <td class="text-center" colspan="7">Sin datos</td>
        </tr>
        </tbody>
      </table>
    </div>
    <b-card-footer>
      <pagination @changePage="changePage" @changeLimit="changeLimit" :items="solicitudes" :total="total"
                  :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>

      <template v-if="loading">
        <BlockUI>
          <b-spinner variant="info" label="loading..."/>
        </BlockUI>
      </template>
    </b-card-footer>
  </b-card>
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
  BTab,
  BTabs,
  BSpinner
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import solicitud from '../../../api/Tesoreria/SolicitudesPagos'
import loadingImage from '../../../assets/images/loader/block50.gif'
import es from 'vuejs-datepicker/dist/locale/translations/es'
import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";

export default {
  components: {
    BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
    BBadge,
    BTab,
    BTabs,
    BSpinner,vSelect
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      loading: true,
      url: loadingImage,   //It is important to import the loading image then use here
      filter: {
        page: 1,
        limit: 20,
        limitOptions: [5, 10, 15, 20],
        search: {
          field: 'beneficiario',
          value: '',
          status: 0
        }
      },
      solicitudes: [],
      cuentas_bancarias:[],
      total: 0,
      descargando : false
    }
  },
  methods: {
    formatDate(date) {
      return moment(date).format('YYYY-MM-DD')
    },
    obtener() {
      var self = this;
      self.loading = true;
      solicitud.obtenerSolicitudesAprobacion(
          self.filter,
          data => {
            self.solicitudes = data.rows;
            self.cuentas_bancarias = data.cuentas_bancarias
            self.total = data.total;
            self.loading = false;
          },
          err => {
            self.loading = false;
            console.log(err);
            this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Notificación',
                icon: 'InfoIcon',
                text: 'Ha ocurrido un problema al cargar los datos',
                variant: 'warning',
                position: 'bottom-right'
              }
            },{
              position:'bottom-right'
            })
          }
      )
    },

    changeLimit(limit) {
      this.filter.page = 1;
      this.filter.limit = limit;
      this.obtener();
    },
    changePage(page) {
      this.filter.page = page;
      this.obtener();
    },
    cambiarEstadoSolicitudPago(estado,id_solicitud_pago,id_cuenta_bancaria){

      var txtAprobar = 'Está seguro de autorizar esta solicitud de pago?';
      var txtRechazar ='Está seguro de rechazar la solicitud de pago?';
      //var txtAnular ='Está seguro de revocar el permiso de consignación para este cliente?';

      var self = this;
      let validacion = true;
      let id_cuenta_bancariax = id_cuenta_bancaria;

      if(estado === 2 && id_cuenta_bancaria ===null){
        validacion=false;
        id_cuenta_bancariax=0;
      }

      if(validacion){
        self.$swal.fire({
          title: 'Confirmación de cambio del estado de solicitud de pago',
          text: estado===2?txtAprobar:estado===0?txtRechazar:txtRechazar,
          icon:'info',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, confirmar',
          cancelButtonText:'Cancelar'
        }).then((result) => {
          self.loading=true;
          if (result.value) {
            //var self = this
            solicitud.cambiarEstadoSolicitudPago({
              id_solicitud_pago: id_solicitud_pago,
              id_cuenta_bancaria:id_cuenta_bancariax,
              estado: estado
            }, data => {
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckCircleIcon',
                  text: 'El estado de la solicitud de pago ha cambiado correctamente',
                  variant: 'success',
                }
              },{
                position: 'bottom-right'
              });
              self.loading = false;
            }, err => {
              self.loading=false;
              console.log(err)
            })
          }else{
            self.loading=false;
          }
        })
      }else{
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Notificación',
            icon: 'AlertCircleIcon',
            text: 'Debe seleccionar una cuenta bancaria para aprobar la solicitud de pago',
            variant: 'warning',
          }
        },{
          position: 'bottom-right'
        })
      }
    },
    downloadItem (ext) {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: 'Notificación',
          icon: 'XIcon',
          text: 'Esta sección se encuentra en desarrollo',
          variant: 'danger',
        }
      },{
        position: 'bottom-right'
      })
      /*var self = this;
      self.msg= 'Descargando datos, espere un momento.....'
      self.loading = true;
      //if(!this.descargando){
      //	this.descargando=true;
      //alertify.success("Descargando datos, espere un momento.....",5);

      axios.get('unidad-medida/reporte/'+ext, { responseType: 'blob' })
          .then(({ data }) => {
              let blob = new Blob([data], { type: 'application/pdf' })
              ext === 'xls' ? blob = new Blob([data], { type: 'application/octet-stream' }) : blob = new Blob([data], { type: 'application/pdf' });
              let link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob)
              link.download = 'Reporte_UnidadMedida.'+ext;
              link.click()
              //this.descargando=false;
              self.loading = false;
              self.msg= 'Cargando el contenido espere un momento'
          }).catch(function (error) {
          alertify.error("Error Descargando archivo.....", 5);
          //self.descargando = false;
          self.loading = false;
          self.msg= 'Cargando el contenido espere un momento'
      })*/
      /*}else{
          alertify.warning("Espere a que se complete la descarga anterior",5);
      }*/
    },
  },
  /* components: {
     pagination: Pagination
   },*/
  mounted() {
    this.obtener();
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
