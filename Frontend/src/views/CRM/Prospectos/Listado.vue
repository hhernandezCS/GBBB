<template>
    <b-card>
        <b-row>
            <div class="col-lg-4 col-md-12 col-sm-12 sm-text-center">
                <b-button
                        class="mx-1"
                        v-b-toggle.sidebar-right
                        variant="success"
                >
                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                    Registrar
                </b-button>
                <b-sidebar
                        backdrop
                        bg-variant="white"
                        id="sidebar-right"
                        right
                        shadow
                >
                    <SidebarContent/>
                </b-sidebar>
                <!--Llamada a componente de reasignar leads-->
                <router-link :to="{ name: 'crm-leads-reasignar' }" class="btn btn-success"
                             tag="button"
                             v-b-tooltip.hover.top="'Reasignar leads a otro usuario responsable'">
                    <feather-icon icon="RefreshCwIcon"></feather-icon>
                    Reasignar
                </router-link>
            </div>
            <div @keyup.enter="filter.page = 1;obtener();"
                 class="col-lg-8 col-md-12 col-sm-12 sm-text-center form-inline justify-content-end ">
                <b-button class="mx-1"
                          v-b-modal.filterModal
                          v-b-tooltip.hover.top="'Filtrar registros!'"
                          variant="info"
                >
                    <feather-icon icon="FilterIcon"></feather-icon>
                </b-button>
                <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field" v-model="filter.search.field">
                    <option value="nombre_compania">Compañia</option>
                    <option value="codigo_prospecto">Código lead</option>
                    <option value="descripcion_lead">Tema lead</option>
                    <option value="u_responsable">Usuario Responsable</option>
                    <option value="telefono_compania">Teléfono compañía</option>
                    <option value="telefono_trabajo">Teléfono contacto</option>
                    <option value="telefono_movil">Teléfono movil</option>
                </select>
                <input class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text"
                       v-model="filter.search.value">
                <b-button @click="filter.page = 1;obtener();" v-b-tooltip.hover.top="'Buscar!'"
                          variant="info">
                    <feather-icon icon="SearchIcon"></feather-icon>
                </b-button>
            </div>


            <!--<a :disabled="descargando" @click.prevent="downloadItem('pdf')" style="color: #ffffff;padding-left: 2px"><b-button :disabled="descargando" variant="danger" v-b-tooltip.hover.top="'PDF'"><feather-icon icon="DownloadCloudIcon"></feather-icon></b-button></a>
            <a :disabled="descargando" @click.prevent="downloadItem('xls')" style="color: #ffffff;padding-left: 2px"><b-button :disabled="descargando" variant="success" v-b-tooltip.hover.top="'XLS'"><feather-icon icon="DownloadCloudIcon"></feather-icon></b-button></a>-->


        </b-row>


        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>

                    <th>Código lead</th>
                    <th>Nombre compañia</th>
                    <th>Fecha creación</th>
                    <th>Creado por</th>
                    <th>Fecha de modificacion</th>
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
                                <router-link :to="{name: 'crm-leads-actualizar',params: {id_lead: lead.id_lead}}"
                                             tag="a"
                                             v-b-tooltip="'Abrir lead'"
                                >
                                    {{lead.codigo_prospecto}}
                                </router-link>
                            </template>
                        </td>
                        <td>{{lead.nombre_compania}}</td>
                        <td class="text-center">{{formatDate(lead.f_creacion)}}</td>
                        <td>{{lead.u_creacion}}</td>
                        <td class="text-center">{{formatDate(lead.f_modificacion)}}</td>
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
                                <b-dropdown id="dropdown-buttons" no-caret right style="position: inherit !important;"
                                            text="Right align"
                                            toggle-class="text-decoration-none" variant="link">
                                    <template v-slot:button-content>
                                        <feather-icon class="text-body align-middle mr-25" icon="MoreVerticalIcon"
                                                      size="16"/>
                                    </template>
                                    <b-dropdown-item>
                                        <router-link
                                                :to="{name: 'crm-leads-actualizar', params: {id_lead: lead.id_lead}}"
                                                tag="a"
                                                v-b-tooltip="'Actualizar Lead'">
                                            <feather-icon class="mr-50"
                                                          icon="EditIcon"></feather-icon><span>Editar Lead</span>
                                        </router-link>
                                    </b-dropdown-item>
                                    <b-dropdown-item v-if="lead.estado===1 || lead.estado===2">
                                        <router-link
                                                :to="{name: 'crm-leads-aprobar', params: {id_lead: lead.id_lead}}"
                                                tag="a"
                                                v-b-tooltip="'Actualizar Lead'">
                                            <feather-icon class="mr-50"
                                                          color="#28c76f"
                                                          icon="CheckCircleIcon"></feather-icon>
                                            <span style="color:#28c76f">Aprobar Lead</span>
                                        </router-link>
                                    </b-dropdown-item>
                                    <b-dropdown-item>
                                        <template>
                                           <a @click="desarrollo"><feather-icon class="mr-50" color="#ea5455"
                                                                                icon="XCircleIcon"></feather-icon><span
                                                   style="color:#ea5455">Descartar Lead</span></a>
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

        <!--Modal para filtrar registros scrollable-->
        <b-modal id="filterModal" no-close-on-backdrop size="lg" title="Filtrar">
            <b-container fluid>
                <!--Filtrar por estados-->
                <b-row>
                    <b-col>
                        <!--Selector de estados-->
                        <b-form-group
                                id="fieldset-estado"
                                label="Estados"
                                label-for="input-estado"
                                label-size="default"
                        >
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="filter.estados"
                                    @input="mapEstadosSeleccionados"
                                    label="text"
                                    multiple
                                    placeholder="Seleccione un estado"
                                    v-model="filter.estados_seleccionados"
                            >
                                <template slot="no-options">
                                    No se han encontrado registros
                                </template>
                            </v-select>
                        </b-form-group>
                    </b-col>

                </b-row>
                <!--Filtrar por fecha de creacion-->
                <b-row>
                    <b-col md="6">
                        <!--Selector de fechas para filtrar-->
                        <b-form-group
                                id="fieldset-fdesde"
                                label="Fecha de creacion"
                                label-for="input-fdesde"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fdesde"
                                               placeholder="Escoge una fecha inicial" reset-button today-button
                                               v-model="filter.f_desde"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                                id="fieldset-fhasta"
                                label="-"
                                label-for="input-fhasta"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fhasta"
                                               placeholder="Escoge una fecha final" reset-button today-button
                                               v-model="filter.f_hasta"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                </b-row>
                <!--Filtrar por fecha de modificacion-->
                <b-row>
                    <b-col md="6">
                        <!--Selector de fechas para filtrar-->
                        <b-form-group
                                id="fieldset-fdesde_modificacion"
                                label="Fecha de modificacion"
                                label-for="input-fdesde_modificacion"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fdesde_modificacion"
                                               placeholder="Escoge una fecha inicial" reset-button today-button
                                               v-model="filter.f_desde_modificacion"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                                id="fieldset-fhasta_modificacion"
                                label="-"
                                label-for="input-fhasta_modificacion"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fhasta_modificacion"
                                               placeholder="Escoge una fecha final" reset-button today-button
                                               v-model="filter.f_hasta_modificacion"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                </b-row>
                <!--Filtrar por usuario responsable-->
                <b-row>
                    <b-col>
                        <!--Selector de usuario responsable-->
                        <b-form-group
                                id="fieldset-u_responsable"
                                label="Creado por"
                                label-for="input-u_responsable"
                                label-size="default"
                        >
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="usuarios"
                                    @search="onSearchUser"
                                    label="name"
                                    placeholder="Selecciona un responsable"
                                    v-model="filter.u_responsable"
                            >
                                <template slot="no-options">
                                    Escriba para buscar un usuario
                                </template>
                            </v-select>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>

            <template #modal-footer="{ok,cancel}">
                <b-button @click="cancel()" size="sm" variant="danger"> Cancelar</b-button>
                <b-button @click="obtener" ref="btnHide" size="sm" variant="info"> Aplicar</b-button>
            </template>
        </b-modal>
        <b-card-footer>
            <pagination :items="leads" :limit="filter.limit" :limitOptions="filter.limitOptions" :page="filter.page"
                        :total="total" @changeLimit="changeLimit" @changePage="changePage"></pagination>

            <template v-if="loading">
                <BlockUI>
                    <b-spinner label="loading..." variant="info"/>
                </BlockUI>
            </template>
        </b-card-footer>
    </b-card>
</template>

<script type="text/ecmascript-6">
  import {
    BBadge,
    BButton,
    BCard,
    BCardFooter,
    BCol,
    BContainer,
    BDropdown,
    BDropdownItem,
    BFormCheckbox,
    BFormDatepicker,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BPagination,
    BPaginationNav,
    BRow,
    BSidebar,
    BSpinner,
    BTab,
    BTable,
    BTabs,
    VBToggle,
    VBTooltip,
  } from 'bootstrap-vue'
  import SidebarContent from './Registrar.vue'
  import lead from '../../../api/CRM/Leads'
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import Ripple from 'vue-ripple-directive';
  import vSelect from "vue-select";
  import axios from "axios";

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
      BSpinner,
      BContainer,
      vSelect
    },
    directives: {
      'b-tooltip': VBTooltip,
      'b-toggle': VBToggle,
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
            field: 'nombre_compania',
            value: '',
            status: 1
          },
          // campos para filtrar desde modal
          estados: [
            {value: 0, text: 'Todos los estados'},
            {value: 1, text: 'Nuevos'},
            {value: 2, text: 'Seguimiento'},
            {value: 3, text: 'Aceptado'},
            {value: 4, text: 'Rechazados'},
          ],
          estados_seleccionados: [
            {value: 1, text: 'Nuevos'}
          ],
          map_estados_seleccionados: '1',
          //campos para filtrar por fecha de creacion
          f_desde: '',
          f_hasta: '',
          f_desde_modificacion: '',
          f_hasta_modificacion: '',
          u_responsable: ''
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
        lead: [],
        usuarios: [],
        total: 0,
        descargando: false
      }
    },
    methods: {

      obtener() {
        const self = this;
        self.loading = true;
        //Cerrar modal luego de aplicar la busqueda
        this.$root.$emit('bv::hide::modal', 'filterModal', '#btnHide');
        lead.obtener(
            self.filter,
            data => {
              self.leads = data.rows;
              self.total = data.total;
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: 'Ha ocurrido un problema al cargar los datos',
                  variant: 'warning',
                  position: 'bottom-right'
                }
              }, {
                position: 'bottom-right'
              })
            }
        )
      },
      onSearchUser(searchU, loading) {
        if (searchU.length) {
          loading(true);
          this.searchU(loading, searchU, this)
        }
      },
      searchU: _.debounce((loading, searchU, vm) => {
        const self = this;
        axios.get(`admon/usuarios/buscar?q=${escape(searchU)}`).then(res => {
          vm.usuarios = res.data.result;
          loading(false)
        })
      }, 10),

      changeLimit(limit) {
        this.filter.page = 1;
        this.filter.limit = limit;
        this.obtener();
      },
      changePage(page) {
        this.filter.page = page;
        this.obtener();
      },
      desarrollo() {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Notificación',
            icon: 'XIcon',
            text: 'Esta sección se encuentra en desarrollo',
            variant: 'danger',
          }
        }, {
          position: 'bottom-right'
        })
      },
      downloadItem(ext) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Notificación',
            icon: 'XIcon',
            text: 'Esta sección se encuentra en desarrollo',
            variant: 'danger',
          }
        }, {
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
      /**
       * Metodo para recorrer los objetos de los estados seleccionados y extraer los "id's"
       * */
      mapEstadosSeleccionados() {
        let self = this;
        if (self.filter.estados_seleccionados) {
          return self.filter.map_estados_seleccionados = self.filter.estados_seleccionados.map((estado) => [estado.value]).join(",");
        }
        return self.filter.map_estados_seleccionados = '';
      },
    },
    /* components: {
       pagination: Pagination
     },*/
    mounted() {
      this.obtener();
      this.mapEstadosSeleccionados();
    }
  }
</script>
<style lang="scss" scoped>
    @import '../../../@core/scss/vue/libs/vue-select';
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
