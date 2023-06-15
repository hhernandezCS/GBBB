<template>
    <b-container fluid>
        <b-row>
            <b-card class="col-12">
                <!--Componente para validacion en capa frontend-->
                <validation-observer ref="reasignarForm">
                    <!--Seccion para obtener leads de un usuario origen-->
                    <b-form-group
                            label="Selecciona usuario origen"
                            label-cols-md="4"
                            label-for="usuario"
                    >
                        <v-select
                                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                :options="usuarios"
                                @input="obtenerLeadPorUsuario"
                                @search="onSearchUser"
                                label="name"
                                placeholder="Selecciona un usuario"
                                v-model="form.usuario_origen"
                        >
                            <template slot="no-options">
                                Escriba para buscar un usuario
                            </template>
                        </v-select>
                    </b-form-group>
                    <!--Seccion para seleccionar usuario destino / usuario a asignar los leads-->
                    <b-form-group
                            label="Selecciona usuario destino"
                            label-cols-md="4"
                            label-for="usuario"
                    >
                        <validation-provider
                                #default="{ errors }"
                                name="Usuario destino"
                                rules="required"
                        >
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="usuarios"
                                    @search="onSearchUser"
                                    label="name"
                                    placeholder="Selecciona en usuario"
                                    v-model="form.usuario_destino"
                            >
                                <template slot="no-options">
                                    Escriba para buscar un usuario
                                </template>
                            </v-select>
                            <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                        <b-alert show variant="danger">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.usuario_destino"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>
                    </b-form-group>
                    <!--Seccion para reasignacion de leads-->
                    <template v-if="form.leads.length > 0">
                        <b-form-group
                                label="Modo de seleccion de filas"
                                label-cols-md="4"
                                label-for="table-select-mode-select"
                        >
                            <b-form-select
                                    :options="modes"
                                    class="mb-3"
                                    id="table-select-mode-select"
                                    v-model="selectMode"
                            >
                            </b-form-select>
                        </b-form-group>

                        <!--Botones de seleccion de registros-->
                        <b-row>
                            <div class="col-md-9 col-lg-9 col-sm-12">
                                <b-button @click="selectAllRows" class="mx-1" size="sm" variant="info">Seleccionar todos
                                </b-button>
                                <b-button @click="clearSelected" class="mx-1 mt-lg-0 mt-md-0 mt-sm-2" size="sm"
                                          variant="info">
                                    Limpiar seleccion
                                </b-button>
                                <b-button :disabled="btnAction !== 'Reasignar'" @click="reasignar"
                                          class="mt-lg-0 mt-md-0 mt-sm-2" size="sm"
                                          variant="success">
                                    {{btnAction}}
                                </b-button>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12 mt-lg-0 mt-md-0 mt-sm-2">
                                <p>Total de registros: <strong>{{total}}</strong></p>
                            </div>
                        </b-row>

                        <div class="table table-responsive mt-1">
                            <b-table
                                    :current-page="form.filter.page"
                                    :fields="fields"
                                    :items="form.elementosPaginados"
                                    :per-page="form.filter.limit"
                                    :select-mode="selectMode"
                                    @row-selected="onRowSelected"
                                    ref="selectableTable"
                                    responsive="sm"
                                    selectable
                            >
                                <!-- scoped slot para la columna de seleccion, mostrar simbolo *check* cuando esta marcada una fila-->
                                <template #cell(seleccionado)="{ rowSelected }">
                                    <template v-if="rowSelected">
                                        <span aria-hidden="false">&check;</span>
                                        <span class="sr-only">Seleccionado</span>
                                    </template>
                                    <template v-else>
                                        <span aria-hidden="true">&nbsp;</span>
                                        <span class="sr-only">No seleccionado</span>
                                    </template>
                                </template>
                                <!-- Scopde a columna estado para mostrar un label en vez de el valor numerico obtenido de la tabla -->
                                <template #cell(estado)="data">
                                    <div v-if="data.item.estado===1" class="text-center">
                                        <b-badge variant="primary">Nuevo</b-badge>
                                    </div>
                                    <div v-if="data.item.estado===2" class="text-center">
                                        <b-badge variant="info">Seguimiento</b-badge>
                                    </div>
                                    <div v-if="data.item.estado===3" class="text-center">
                                        <b-badge variant="success">Aceptado</b-badge>
                                    </div>
                                    <div v-if="data.item.estado===4" class="text-center">
                                        <b-badge variant="danger">Rechazado</b-badge>
                                    </div>
                                </template>

                            </b-table>
                            <!--Seccion footer del componente CARD-->
                            <b-card-footer>
                                <b-row>
                                    <div class="col-lg-1 col-md-6 col-sm-12 d-flex justify-content-start">
                                        <b-form-select :options="form.filter.limitOptions"
                                                       v-model="form.filter.limit"></b-form-select>
                                    </div>
                                    <div class="col-lg-11 col-md-6 col-sm-12 d-flex justify-content-end">
                                        <!-- :per-page="form.filter.limit"  habilitar en caso de problemas en paginacion-->
                                        <b-pagination
                                                :per-page="form.filter.limit"
                                                :total-rows="total"
                                                :v-model="form.filter.page"
                                                @change="onPageChanged"
                                                aria-controls="btable"
                                                class="mx-1"
                                                first-text="Primero"
                                                last-text="Ultimo"
                                                next-text="Siguiente"
                                                prev-text="Anterior"
                                        ></b-pagination>
                                    </div>
                                </b-row>


                                <!--                    <pagination :items="leads" :limit="form.filter.limit" :limitOptions="form.filter.limitOptions" :page="form.filter.page"
                                                                :total="total" @changeLimit="changeLimit"
                                                                @changePage="changePage"></pagination>-->
                            </b-card-footer>
                        </div>

                    </template>


                    <!--Gif de pantalla de carga-->
                    <template v-if="loading">
                        <BlockUI>
                            <b-spinner label="cargando..." variant="info"/>
                        </BlockUI>
                    </template>
                </validation-observer>
            </b-card>
        </b-row>
    </b-container>
</template>
<script>
  import {
    BAlert,
    BButton,
    BButtonGroup,
    BCard,
    BCardFooter,
    BContainer,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BPagination,
    BPaginationNav,
    BRow,
    BSpinner,
    BTable,
    VBTooltip,
      BBadge
  } from 'bootstrap-vue'
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import vSelect from 'vue-select'
  import lead from "../../../api/CRM/Leads";
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import axios from "axios";
  import {ValidationObserver, ValidationProvider} from 'vee-validate';
  import {mimes, required} from '@validations';

  export default {
    components: {
      BCard,
      BCardFooter,
      BPaginationNav,
      BFormCheckbox,
      BFormGroup,
      vSelect,
      BRow,
      BButton,
      BFormCheckboxGroup,
      BFormDatepicker,
      BAlert,
      BFormSelect,
      BContainer,
      BFormInput,
      BButtonGroup,
      BSpinner,
      BTable,
      BPagination,
      ValidationProvider,
      ValidationObserver,
      BBadge
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        // Datos necesarios para la paginacion de registros
        loading: false,
        descargando: false,
        url: loadingImage,
        required,
        modes: ['multi', 'single', 'range'],
        fields: ['seleccionado', 'codigo_prospecto', 'nombre_compañia', 'telefono_compañia', 'usuario_responsable','estado'],
        selectMode: 'multi',
        usuarios: [],
        total: 0,
        form: {
          filter: {
            page: 1,
            limit: 10,
            limitOptions: [5, 10, 15, 20],
          },
          usuario_origen: '',
          usuario_destino: '',
          elementosPaginados: [],
          selected: [],
          leads: [],
          all_leads: false,
        },
        errorMessages: [],
        btnAction: 'Reasignar',
      }
    },
    methods: {
      obtenerLeadPorUsuario() {
        let self = this;
        self.loading = true;
        lead.obtenerLeadUsuario(self.form, data => {
              self.form.leads = data.leads;
              self.total = self.form.leads.length;
              self.paginate(self.form.filter.limit, 0);
              self.loading = false;
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Leads obtenidos correctamente.',
                  variant: 'success',
                }
              },{
                position: 'bottom-right'
              });
            },
            err => {
              self.loading = false;
              self.errorMessages = err;
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: 'No se ha podido obtener los leads de este usuario',
                  variant: 'warning',
                  position: 'bottom-right'
                }
              },{
                position: 'bottom-right'
              });
              /*this.$router.push({
                  name : 'inventario-unidades-medida'
              })*/
            })
      },
      // Metodo para reasignar leads
      reasignar() {
        this.$refs.reasignarForm.validate().then(success => {
          if(this.form.usuario_origen.id !== this.form.usuario_destino.id){
              if (success) {
                let self = this;
                self.btnAction = 'Procesando, por favor espere....';
                self.loading = true;
                lead.reasignar(self.form, data => {
                  //desactivamos la pantalla de carga
                  self.loading = false;
                  //Se ejeucta la alerta notificando al usuario que se reasignaron los registros
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: data.messages,
                          variant: 'success',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });

                  //Ejecutamos alerta, para validar si el usuario seguira trasladando leads o regresar al listado de leads principal
                  self.$swal.fire({
                    title: '¿Desea seguir reasignando leads?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'No, volver al listado'
                  }).then((result) => {
                    //Si la respuesta fue si, restauramos el estado del boton y actualizamos la lista de leads
                    if (result.value) {
                      self.btnAction = 'Reasignar';
                      this.obtenerLeadPorUsuario();
                      // si a respuesta es no, retornamos al listado principal de leads
                    } else {
                      this.$router.push({name: 'crm-leads'})
                    }
                  });


                }, err => {
                  self.loading = false;
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: err.messages,
                          variant: 'danger',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                  self.errorMessages = err;
                  self.btnAction = 'Reasignar'
                })
              } else {
                //Ejecutar en caso de que existan campos sin llenar
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'InfoIcon',
                        text: 'Verifique que ha seleccionado un usuario destino para la reasginacion de leads',
                        variant: 'danger',
                        position: 'bottom-right'
                      }
                    },
                    {
                      position: 'bottom-right'
                    });
              }
          }else{
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'No se pueden reasignar los leads al mismo usuario',
                    variant: 'danger',
                    position: 'bottom-right'
                  }
                },
                {
                  position: 'bottom-right'
                });
          }
        });
      },
      //Metodos para funcionamiento de la paginacion
      paginate(page_size, page_number) {
        let elementosSinPaginar = this.form.leads;
        if (elementosSinPaginar) {
          this.form.elementosPaginados = elementosSinPaginar.slice(
              page_number * page_size,
              (page_number + 1) * page_size
          );
        }
      },
      onPageChanged(page) {
        let self = this;
        self.paginate(self.form.filter.limit, page - 1);
      },
      // Metodos para funcionamiento de seleccion multiples de registros
      onRowSelected(items) {
        this.form.selected = items
      },
      selectAllRows() {
        let self = this;
        self.form.all_leads = true; //si all_leads es true, se realizara el traslado de todos los leads asignados al usario al nuevo responsable
        this.$refs.selectableTable.selectAllRows();
      },
      clearSelected() {
        let self = this;
        self.form.all_leads = false; // si all_leads es false, se hara la reasignacion solamente de los leads marcados en el listado
        this.$refs.selectableTable.clearSelected();
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
    },
    mounted() {

    }
  }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
</style>
