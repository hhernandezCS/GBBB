<template>
  <b-card>
    <b-row>
      <div class="col-md-3 sm-text-center">
        <b-button
            v-b-toggle.sidebar-right
            variant="success"
        >
          <feather-icon icon="PlusCircleIcon"></feather-icon>
          Registrar
        </b-button>
        <b-sidebar
            id="sidebar-right"
            bg-variant="white"
            right
            backdrop
            shadow
        >
          <SidebarContent />
        </b-sidebar>
      </div>

      <div @keyup.enter="filter.page = 1;obtener();"
           class="col-md-9 sm-text-center form-inline justify-content-end">
        <b-form-select v-model.number="filter.search.status" class=" mb-1 mr-sm-1 mb-sm-0 search-field">
          <option value="100">Todos</option>
          <option value="1">Nuevo</option>
          <option value="2">Seguimiento</option>
          <option value="3">Aceptado</option>
          <option value="4">Rechazado</option>
        </b-form-select>
        <select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
          <option value="nombre_compañia">Compañia</option>
          <option value="codigo_prospecto">Código lead</option>
          <option value="descripcion_lead">Tema lead</option>
          <option value="u_responsable">Usuario Responsable</option>
        </select>
        <input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar"
               type="text">
        <b-button variant="info" @click="filter.page = 1;obtener();"
                  v-b-tooltip.hover.top="'Buscar!'">
          <feather-icon icon="SearchIcon"></feather-icon>
        </b-button>
        <!--<a :disabled="descargando" @click.prevent="downloadItem('pdf')" style="color: #ffffff;padding-left: 2px"><b-button :disabled="descargando" variant="danger" v-b-tooltip.hover.top="'PDF'"><feather-icon icon="DownloadCloudIcon"></feather-icon></b-button></a>
        <a :disabled="descargando" @click.prevent="downloadItem('xls')" style="color: #ffffff;padding-left: 2px"><b-button :disabled="descargando" variant="success" v-b-tooltip.hover.top="'XLS'"><feather-icon icon="DownloadCloudIcon"></feather-icon></b-button></a>-->
      </div>


    </b-row>


    <div class="table-responsive mt-3">
      <table class="table table-striped table-bordered">
        <thead>
        <tr>

          <th>Código lead</th>
          <th>Nombre compañia</th>
          <th>Fecha creación</th>
          <th>Creado por</th>
          <th class="text-center table-number">Estado</th>
          <th>Responsable</th>
          <th class="text-center table-number">Opciones</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="lead in leads">
          <tr :key="lead.id_lead">
            <td>
              <template>
                <router-link v-b-tooltip="'Abrir lead'"
                             :to="{name: 'crm-leads-actualizar',params: {id_lead: lead.id_lead}}"
                             tag="a"
                >
                  {{lead.codigo_prospecto}}
                </router-link>
              </template>
            </td>
            <td>{{lead.nombre_compañia}}</td>
            <td class="text-center">{{formatDate(lead.f_creacion)}}</td>
            <td>{{lead.u_creacion}}</td>
            <td class="text-center">
              <div v-if="lead.estado===1">
                <b-badge variant="primary">Nuevo</b-badge>
              </div>
              <div v-if="lead.estado===2">
                <b-badge variant="info">Seguimiento</b-badge>
              </div>
              <div v-if="lead.estado===3">
                <b-badge variant="success">Aceptado</b-badge>
              </div>
              <div v-if="lead.estado===4">
                <b-badge variant="danger">Rechazado</b-badge>
              </div>
            </td>
            <td>{{lead.u_responsable}}</td>
            <td class="text-center">
                            <span>
                                <b-dropdown variant="link" toggle-class="text-decoration-none" right text="Right align"
                                            id="dropdown-buttons"
                                            style="position: inherit !important;" no-caret>
                                    <template v-slot:button-content>
                                        <feather-icon icon="MoreVerticalIcon" size="16"
                                                      class="text-body align-middle mr-25"/>
                                    </template>
                                    <b-dropdown-item>
                                        <router-link v-b-tooltip="'Actualizar Lead'"
                                                     :to="{name: 'crm-leads-actualizar', params: {id_lead: lead.id_lead}}"
                                                     tag="a">
                                            <feather-icon icon="EditIcon" class="mr-50"></feather-icon><span>Editar Lead</span>
                                        </router-link>
                                    </b-dropdown-item>
                                    <b-dropdown-item>
                                        <template>
                                           <a @click="desarrollo"><feather-icon icon="CheckCircleIcon" color="#28c76f" class="mr-50"></feather-icon><span style="color:#28c76f">Crear cliente</span></a>
                                    </template>
                                    </b-dropdown-item>
                                    <b-dropdown-item>
                                        <template>
                                           <a @click="desarrollo"><feather-icon icon="XCircleIcon" color="#ea5455" class="mr-50"></feather-icon><span style="color:#ea5455">Descartar Lead</span></a>
                                    </template>
                                    </b-dropdown-item>
                                </b-dropdown>
                            </span>
            </td>

          </tr>

        </template>
        <tr v-if="!leads.length">
          <td class="text-center" colspan="7">Sin datos</td>
        </tr>
        </tbody>
      </table>
    </div>
    <b-card-footer>
      <pagination @changePage="changePage" @changeLimit="changeLimit" :items="leads" :total="total"
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
  BTable,
  BSidebar,
  VBToggle,
  BDropdown,
  BDropdownItem,
  BPagination,
  BFormInput,
  BFormSelect,
  BSpinner
} from 'bootstrap-vue'
import SidebarContent from './Registrar.vue'
import lead from '../../../api/CRM/Leads'
import loadingImage from '../../../assets/images/loader/block50.gif'
import es from 'vuejs-datepicker/dist/locale/translations/es'
import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import Ripple from 'vue-ripple-directive';
import Vue from 'vue';


export default {
  components: {
    BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
    BBadge,
    BTab,
    BTabs,
    BTable,
    BSidebar,
    SidebarContent,
    BDropdownItem,
    BDropdown,
    /*VueGoodTable,*/
    BPagination,
    BFormInput,
    BFormSelect,
    BSpinner
  },
  directives: {
    'b-tooltip': VBTooltip,
    'b-toggle' : VBToggle,
    Ripple
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
          field: 'nombre_compañia',
          value: '',
          status: 1
        }
      },
      field: [
        {
          label: 'Avatar'
        },
        {
          key: 'name',
          label: 'Nombre'
        }
      ],
      leads: [],
      lead : [],
      total: 0,
      descargando : false
    }
  },
  methods: {

    obtener() {
      var self = this;
      self.loading = true;
      lead.obtener(
          self.filter,
          data => {
            self.leads = data.rows;
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
    desarrollo(){
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
    formatDate(date) {
      return moment(date).format('YYYY-MM-DD')
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
