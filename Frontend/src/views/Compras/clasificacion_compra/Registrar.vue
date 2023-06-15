<template>
    <b-card>
        <b-row>

            <div class="col-12">
                <div class="form-group">
                    <label for="descripcion-input"> Clasificación</label>
                    <b-form-input class="form-control" id="descripcion-input"
                                  placeholder="Dígite nombre de la clasificacion"
                                  v-model="form.descripcion"></b-form-input>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="cuenta-contable-input"> Cuenta Contable</label>
                    <v-select :options="cuentas_contables" id="cuenta-contable-input" label="nombre_cuenta_completo"
                              v-model="form.clasificacion_compra_cuenta_contable"></v-select>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.clasificacion_compra_cuenta_contable"
                                v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <template v-if="form.clasificacion_compra_cuenta_contable">
                <div class="col-md-12" v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 0">
                    <label for="no-auxiliar-input"> Centro / Código</label>
                    <b-form-input class="form-control" id="no-auxiliar-input"
                                  placeholder="Dígite nombre de la clasificacion"
                                  readonly v-model="form.notRequiered"></b-form-input>
                </div>
                <div v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 1">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="auxiliar-input"> Auxiliares</label>
                            <v-select :options="auxiliares" id="auxiliar-input" label="descripcion"
                                      v-model="form.auxiliar"></v-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.auxiliar"
                                        v-text="message"></li>
                                </ul>
                            </b-alert>
                        </div>
                        <div class="col-md-12">
                            <label for="clasificacion-input"> Centro / Código</label>
                            <b-form-input class="form-control" id="clasificacion-input"
                                          placeholder="Dígite nombre de la clasificacion"
                                          v-model="form.auxiliar.codigo"></b-form-input>

                        </div>
                    </div>

                </div>
                <div v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 2">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="centro-costo-input"> Centros de costo</label>
                            <v-select :options="centros_costos" id="centro-costo-input" label="descripcion"
                                      v-model="form.centro_costo"></v-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.centro_costo"
                                        v-text="message"></li>
                                </ul>
                            </b-alert>
                        </div>
                        <div class="col-md-12">
                            <label for="centro-codigo-input"> Centro / Código</label>
                            <b-form-input class="form-control" id="centro-codigo-input"
                                          placeholder="Dígite nombre de la clasificacion"
                                          v-model="form.centro_costo.codigo"></b-form-input>

                        </div>
                    </div>

                </div>
                <div v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 3">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="centro-ingreso-input"> Centros de costo</label>
                            <v-select :options="centros_ingresos" id="centro-ingreso-input" label="descripcion"
                                      v-model="form.centro_ingreso"></v-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.centro_ingreso"
                                        v-text="message"></li>
                                </ul>
                            </b-alert>
                        </div>
                        <div class="col-md-12">
                            <label for=""> Centro / Código</label>
                            <b-form-input class="form-control" placeholder="Dígite nombre de la clasificacion"
                                          v-model="form.centro_ingreso.codigo"></b-form-input>

                        </div>
                    </div>

                </div>
                <div class="col-12 form-group">
                    <label for="concepto-comprobante"> Concepto comprobante</label>
                    <b-form-input class="form-control" id="concepto-comprobante"
                                  placeholder="Dígite el concepto para el comprobante contable"
                                  v-model="form.concepto_comprobante"></b-form-input>
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.concepto_comprobante"
                                v-text="message"></li>
                        </ul>
                    </b-alert>
                </div>
            </template>
        </b-row>
        <b-card-footer class="row mt-2">
            <div class="col-12 text-md-right text-lg-right text-center">
                 <router-link :to="{ name: 'clasificacion-compras' }">
                    <b-button class="mb-md-0 mb-lg-0  mr-1" type="button" variant="secondary">Cancelar</b-button>
                </router-link>
                <b-button :disabled="btnAction !== 'Registrar'" @click="registrar"
                          variant ="primary" type="button">{{ btnAction }}
                </b-button>
            </div>

            <!--Gif de pantalla de carga-->
            <template v-if="loading">
                <BlockUI>
                    <b-spinner label="cargando..." variant="info"/>
                </BlockUI>
            </template>
        </b-card-footer>
    </b-card>
</template>

<script type="text/ecmascript-6">
  import clasificacion from '../../../api/Compras/clasificacion_compra'
  import {
    BAlert,
    BBadge,
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
  } from 'bootstrap-vue'
  import vSelect from 'vue-select'
  import {ValidationObserver, ValidationProvider} from 'vee-validate';
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
        loading: false,
        form: {
          descripcion: '',
          tipo_afectacion: '',
          clasificacion_compra_cuenta_contable: [],
          auxiliar: [],
          centro_costo: [],
          concepto_comprobante: '',
          activo: '',
          notRequiered: 'No requiere codigo auxiliar'
        },
        cuentas_contables: [],
        auxiliares: [],
        centros_costos: [],
        centros_ingresos: [],
        btnAction: 'Registrar',
        errorMessages: []
      }
    },
    methods: {
      nuevo() {
        const self = this;
        clasificacion.nuevo({}, data => {
              self.cuentas_contables = data.cuentas_contables;
              self.centros_costos = data.centros_costos;
              self.auxiliares = data.auxiliares;
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                  component: ToastificationContent,
                  props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Ha ocurrido un error inesperado',
                      variant: 'warning',
                  }
              },
              {
                  position: 'bottom-right'
              });
            })

      },
      registrar() {
        const self = this;
        self.btnAction = 'Registrando, espere....';
        self.loading = true;
        clasificacion.registrar(self.form, data => {
          self.loading = false;
          this.$router.push({
            name: 'clasificacion-compras'
          })
        }, err => {
          self.loading = false;
          self.errorMessages = err;
          self.btnAction = 'Registrar'
        })
      },
    },
    mounted() {
      this.nuevo();
    }
  }
</script>
