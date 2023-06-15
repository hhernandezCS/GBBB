<template>
    <b-container fluid>
        <div>
            <b-card>
                <!--Componente para validacion en capa frontend-->
                <validation-observer ref="inicializacionValidation">
                    <!--Input para seleccionar archivos -->
                    <b-row>
                        <div class="col-md-12 mb-lg-2 mb-md-2 mb-2">
                            <h5>Seleccione el archivo que desea importar</h5>
                        </div>
                        <div class="form-group col-md-8 mt-lg-0 mt-md-0 mt-2">
                            <validation-provider
                                    #default="{ errors }"
                                    name="seleccionar archivo"
                                    rules="required"
                            >
                                <b-form-file
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        :state="Boolean(file1)"
                                        drop-placeholder="Arrastra el archivo aqui...."
                                        placeholder="Elije y arrastra el archivo aqui...."
                                        ref="file"
                                        v-model="file1"
                                >

                                </b-form-file>
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.file" v-text="message"></li>
                                </ul>
                            </b-alert>
                        </div>
                        <div class="col-md-4 text-center mt-lg-0 mt-md-0 mt-2">
                            <b-button @click="uploadFile" variant="success">Cargar archivo</b-button>
                        </div>

                    </b-row>
                    <!--Mostrar nombre del archivo seleccionado en pantalla-->
                    <template v-if="file1">
                        <div class="mt-3"><strong>Archivo seleccionado: </strong> {{ file1 ? file1.name : '' }}</div>
                    </template>


                    <template v-if="file_data">
                        <b-row>
                            <!---Etiqueta de notificacion sobre datos cargados-->
                            <div class="col-12">
                                <b-alert
                                        class="mb-2 mt-2"
                                        show
                                        variant="success"
                                >
                                    <div class="alert-body">
                                        <span><strong>Resumen de archivo cargado</strong></span>
                                    </div>
                                </b-alert>
                            </div>
                            <!--Div con clase responsive para evitar que la tabla desborde la pagina-->
                            <div class="col-12 table table-responsive">
                                <b-table
                                        :current-page="currentPage"
                                        :items="elementosPaginados"
                                        :per-page="perPage"
                                        hover
                                        id="btable"
                                ></b-table>
                            </div>
                        </b-row>
                    </template>

                    <b-card-footer class="row mt-2">

                        <template v-if="!file_data">
                            <div class="col-md-12">
                                <div class="text-lg-left text-md-left text-center">
                                    <router-link :to="{name: 'admon-formatos-importacion'}">
                                        <b-button class="mx-1" type="submit" variant="secondary">
                                            Cancelar
                                        </b-button>
                                    </router-link>

                                </div>
                            </div>
                        </template>
                        <template v-if="file_data">
                            <div class="col-md-6">
                                <div class="text-left">
                                    <!--Paginar registros en la tabla-->
                                    <b-pagination
                                            :per-page="perPage"
                                            :total-rows="file_resume.length"
                                            :v-model="currentPage"
                                            @change="onPageChanged"
                                            aria-controls="btable"
                                            class="mx-1"
                                    ></b-pagination>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">

                                    <router-link :to="{name: 'admon-formatos-importacion'}">
                                        <b-button class="mx-1" type="submit" variant="secondary">
                                            Cancelar
                                        </b-button>
                                    </router-link>


                                    <b-button :disabled="btnAction !== 'Registrar'" @click="importFile" type="submit"
                                              variant="primary">
                                        {{btnAction}}
                                    </b-button>


                                </div>
                            </div>
                        </template>
                    </b-card-footer>

                    <!--Gif de pantalla de carga-->
                    <template v-if="loading">
                        <BlockUI>
                            <b-spinner label="cargando..." variant="info"/>
                        </BlockUI>
                    </template>

                </validation-observer>
            </b-card>
        </div>
    </b-container>
</template>
<script type="text/ecmascript-6">
  import {ValidationObserver, ValidationProvider} from 'vee-validate'
  import {mimes, required} from '@validations'
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
    BFormFile,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BPagination,
    BPaginationNav,
    BRow,
    BSpinner,
    BTable,
    VBTooltip
  } from 'bootstrap-vue'
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import vSelect from 'vue-select'
  import ajustes_generales from "../../../api/admon/ajustes_generales";
  import marcas from "../../../api/Inventario/marcas";
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";

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
      BFormFile,
      BTable,
      ValidationProvider,
      ValidationObserver,
      BPagination,
      BSpinner

    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        required,
        mimes,
        loading: false,
        descargando: false,
        url: loadingImage,
        file1: null,
        file_data: null,
        file_headings: null,
        file_resume: [],
        imported_rows: 0,
        btnAction: 'Registrar',
        errorMessages: [],
        //variables para paginacion
        currentPage: 1,
        perPage: 10,
        elementosPaginados: []
      }
    },
    computed: {},
    methods: {
      uploadFile() {
        // Llamado al componente validador mediante uso de referencias y ejecutamos sentencia validate, para obtener el estado de validacion de cada campo
        this.$refs.inicializacionValidation.validate().then(success => {
          // en caso de ser success se realiza la operacion normal de lo contrario, se omite la ejecucion de api para retorna un mensaje de advertencia al usuario
          if (success) {
            let self = this;
            self.file1 = self.$refs.file.files[0];
            // console.log(self.file1);
            const formData = new FormData();
            self.loading = true;
            self.file_data = [];
            self.file_resume = [];
            self.imported_rows = 0;

            formData.append('file', self.file1);
            ajustes_generales.procesarExcel(formData, data => {
              self.file_headings = data.datos_.shift();
              self.file_resume = data.datos_;
              self.file_data = data.datos_;
              self.paginate(this.perPage, 0);
              self.loading = false;
            }, err => {
              self.loading = false;
              self.errorMessages = err;

              this.$toast.success({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: err.messages,
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            })

          } else {
            //Ejecutar en caso de que existan campos sin llenar
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Verifique si ha seleccionado un archivo en formato XLS o XLSX',
                    variant: 'danger',
                    position: 'bottom-right',
                  }
                },
                {
                  position: 'bottom-right',
                });
          }
        });
      },
      importFile() {
        // Llamado al componente validador mediante uso de referencias y ejecutamos sentencia validate, para obtener el estado de validacion de cada campo
        this.$refs.inicializacionValidation.validate().then(success => {
          // en caso de ser success se realiza la operacion normal de lo contrario, se omite la ejecucion de api para retorna un mensaje de advertencia al usuario
          if (success) {
            let self = this;
            self.file1 = self.$refs.file.files[0];

            const formData = new FormData();
            self.loading = true;
            self.file_data = [];
            self.file_resume = [];
            self.imported_rows = 0;
            self.btnAction = 'Cargando archivo';

            formData.append('file', self.file1);
            ajustes_generales.importarDatosExcel(formData, data => {

            this.$toast({
                   component: ToastificationContent,
                   props: {
                       title: 'Notificación',
                       icon: 'CheckIcon',
                       text: 'Se han importado ' + data.total_rows + ' registros correctamente.',
                       variant: 'success',
                   }
               },
               {
                   position: 'bottom-right'
               });


              this.$router.push('admon-formatos-importacion');
              self.loading = false;
            }, err => {
              self.loading = false;
              self.errorMessages = err;
              self.btnAction = 'Registrar';

              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: err.messages,
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            })

          } else {
            //Ejecutar en caso de que existan campos sin llenar
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'InfoIcon',
                    text: 'Verifique si ha seleccionado un archivo en formato XLS o XLSX',
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
      paginate(page_size, page_number) {
        let elementosSinPaginar = this.file_data;
        if (elementosSinPaginar) {
          this.elementosPaginados = elementosSinPaginar.slice(
              page_number * page_size,
              (page_number + 1) * page_size
          );
        }
      },
      onPageChanged(page) {
        this.paginate(this.perPage, page - 1);
      }
    },
    mounted() {

    }
  };
</script>
<style>

</style>
