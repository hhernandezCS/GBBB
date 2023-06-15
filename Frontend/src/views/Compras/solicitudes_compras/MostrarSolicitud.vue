<template>
    <section class="invoice-preview-wrapper">
        <!-- Alert: No item found -->
        <b-alert
                :show="form === undefined"
                variant="danger"
        >
            <h4 class="alert-heading">
                Error fetching invoice data
            </h4>
            <div class="alert-body">
                No invoice found with this invoice id. Check
                <b-link
                        :to="{ name: 'apps-invoice-list'}"
                        class="alert-link"
                >
                    Invoice List
                </b-link>
                for other invoices.
            </div>
        </b-alert>
        <!--Main content-->
        <b-row
                class="invoice-preview"
                v-if="form"
        >
            <!-- Col: Left (Invoice Container) -->
            <b-col
                    cols="12"
                    md="8"
                    xl="9"
            >
                <b-card
                        class="invoice-preview-card"
                        no-body
                >
                    <!-- Header -->
                    <b-card-body class="invoice-padding pb-0">

                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">

                            <!-- Header: Left Content -->
                            <div>
                                <div class="logo-wrapper">
                                    <logo/>
                                    <h3 class="text-primary invoice-logo">
                                        {{form.nombre_empresa}}
                                    </h3>
                                </div>
                                <p class="card-text mb-25">
                                    {{form.direccion_empresa}}
                                </p>
                                <p class="card-text mb-0">
                                    {{form.telefono_empresa}}
                                </p>
                            </div>

                            <!-- Header: Right Content -->
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Solicitud
                                    <span class="invoice-number">#{{ form.id_solicitud_compra }}</span>
                                </h4>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">
                                        F.solicitud:
                                    </p>
                                    <p class="invoice-date">
                                        {{ formatDate(form.f_necesidad) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </b-card-body>

                    <!-- Spacer -->
                    <hr class="invoice-spacing">

                    <!-- Solicitado por -->
                    <b-card-body
                            class="invoice-padding pt-0"
                            v-if="form.solicitud_trabajador"
                    >
                        <b-row class="invoice-spacing">

                            <!-- Col: Invoice To -->
                            <b-col
                                    class="p-0"
                                    cols="12"
                                    xl="6"
                            >
                                <h6 class="mb-2">
                                    Datos de la solicitud:
                                </h6>
                                <h6 class="mb-0">
                                    Solicitado por:
                                </h6>
                                <p class="card-text mb-25">
                                    {{form.solicitud_trabajador.primer_nombre + '
                                    '+form.solicitud_trabajador.primer_apellido}}
                                </p>
                                <h6 class="mb-0">
                                    Bodega:
                                </h6>
                                <p class="card-text mb-25">
                                    {{form.solicitud_bodega.descripcion}}
                                </p>
                                <h6 class="mb-0">
                                    Area:
                                </h6>
                                <p class="card-text mb-25">
                                    {{form.solicitud_area.descripcion}}
                                </p>
                            </b-col>

                            <!-- Col: Payment Details -->
                            <b-col
                                    class="p-0 mt-xl-0 mt-2 d-flex justify-content-xl-end"
                                    cols="12"
                                    xl="6"
                            >
                                <div>
                                    <h6 class="mb-2">

                                    </h6>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td class="pr-1">
                                                Estado:
                                            </td>
                                            <td>
                                                <div v-if="form.estado===0">
                                                    <b-badge variant="danger">
                                                        Rechazada
                                                    </b-badge>
                                                </div>

                                                <div v-if="form.estado===1">
                                                    <b-badge variant="primary">Solicitada</b-badge>
                                                </div>

                                                <div v-if="form.estado===2">
                                                    <b-badge variant="success"
                                                    >Cotizada
                                                    </b-badge>
                                                </div>

                                                <div v-if="form.estado===3">
                                                    <b-badge variant="success"
                                                    >Autorizada
                                                    </b-badge>
                                                </div>
                                                <div v-if="form.estado===4">
                                                    <b-badge variant="success">Orden de Compra</b-badge>
                                                </div>
                                                <div v-if="form.estado===99">
                                                    <b-badge variant="secondary">Borrrador</b-badge>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pr-1">
                                                Moneda:
                                            </td>
                                            <td>{{form.solicitud_moneda.descripcion}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </b-col>
                        </b-row>
                    </b-card-body>

                    <!--Invoice Description: Table  -->
                    <b-table-lite
                            :fields="fields"
                            :items="form.solicitud_productos"
                            responsive
                    >
                        <template #cell(codigo_producto)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.solicitud_producto.codigo_sistema}}
                            </b-card-text>
                        </template>
                        <template #cell(descripcion)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.solicitud_producto.descripcion}}
                            </b-card-text>
                        </template>
                        <template #cell(um)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.solicitud_producto.unidad_medida.unidad_medida}}
                            </b-card-text>
                        </template>
                        <template #cell(proveedor)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.solicitud_proveedor.nombre_comercial}}
                            </b-card-text>
                        </template>
                        <template #cell(precio_unitario)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.precio_cotizado}}
                            </b-card-text>
                        </template>
                        <template #cell(descuento_%)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.descuento}}
                            </b-card-text>
                        </template>
                        <template #cell(total)="data">
                            <b-card-text class="text-nowrap">
                                {{data.item.subtotal}}
                            </b-card-text>
                        </template>
                    </b-table-lite>

                    <!-- Invoice Description: Total -->
                    <b-card-body class="invoice-padding pb-0">
                        <b-row>

                            <!-- Col: Sales Persion &ndash-->
                            <b-col
                                    class="mt-md-0 mt-3"
                                    cols="12"
                                    md="6"
                                    order="2"
                                    order-md="1"
                            >
                                <b-card-text class="mb-0">
                                    <span class="font-weight-bold"></span>
                                    <span class="ml-75"></span>
                                </b-card-text>
                            </b-col>

                            <!-- Col: Total -->
                            <b-col
                                    class="mt-md-6 d-flex justify-content-end"
                                    cols="12"
                                    md="6"
                                    order="1"
                                    order-md="2"
                            >
                                <div class="invoice-total-wrapper">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Subtotal:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{total_subt | formatMoney(2)}}
                                        </p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Descuento:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{total_descuento | formatMoney(2)}}
                                        </p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Tax:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{form.iva | formatMoney(2)}}
                                        </p>
                                    </div>
                                    <hr class="my-50">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Total:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{Number(form.total)+Number(form.iva) | formatMoney(2)}}
                                        </p>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </b-card-body>

                    <!-- Spacer -->
                    <hr class="invoice-spacing">

                    <!-- Note -->
                    <b-card-body class="invoice-padding pt-0">
                        <span class="font-weight-bold">Observaciones: </span>
                        <span>{{form.observacion}}</span>
                    </b-card-body>
                </b-card>
            </b-col>
            <!-- Right Col: Card Actions -->
            <b-col
                    class="invoice-actions"
                    cols="12"
                    md="4"
                    xl="3"
            >
                <b-card>


                    <!-- Button: Download -->
                    <b-button
                            block
                            class="mb-75"
                            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                            variant="outline-secondary"
                            @click="downloadItem('compras/solicitudes/reporte/'+form.id_solicitud_compra)"
                            :disabled="descargando"
                    >
                        Descargar
                    </b-button>

                    <!-- Button: Print -->
                    <b-button
                            @click="printInvoice"
                            block
                            class="mb-75"
                            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                            variant="outline-secondary"
                    >
                        Imprimir
                    </b-button>

                </b-card>
            </b-col>
        </b-row>
        <template v-if="loading">
            <BlockUI>
                <b-spinner label="loading..." variant="info"/>
            </BlockUI>
        </template>
    </section>

</template>
<script type="text/ecmascript-6">
  import solicitud from "../../../api/Compras/solicitudes_compras";
  import axios from 'axios'
  import {
    BAlert,
    BBadge,
    BButton,
    BButtonGroup,
    BCard,
    BCardBody,
    BCardFooter,
    BCardText,
    BCol,
    BContainer,
    BDropdown,
    BDropdownItem,
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
    BTableLite,
    VBToggle,
    VBTooltip
  } from 'bootstrap-vue'
  import vSelect from 'vue-select'
  import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
  import {ValidationObserver, ValidationProvider} from 'vee-validate';
  import Ripple from 'vue-ripple-directive'
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
      BBadge,
      BDropdown,
      BDropdownItem,
      BCardBody,
      BCol,
      BCardText,
      BTableLite
    },
    directives: {
      'b-tooltip': VBTooltip,
      Ripple,
      'b-toggle': VBToggle,
    },
    data() {
      return {
        format: "dd-MM-yyyy",
        descargando: false,
        loading: true,
        form: {
          solicitud_productos: [],
          solicitud_moneda: "",
          total: 0,
          sub_total: 0,
          nombre_empresa: '',
          telefono_empresa: '',
          direccion_empresa: '',
        },
        fields: ['codigo_producto', 'descripcion', 'um', 'proveedor', 'cantidad', 'precio_unitario', 'descuento_%', 'total'],
        btnAction: "Registrar",
        errorMessages: []
      };
    },
    methods: {

      sub_total(itemX) {

        if (this.form.estado !== 1) {
          itemX.subtotal = Number((Number(itemX.precio_cotizado) * Number(itemX.cantidad_cotizado)).toFixed(2));
        } else {
          itemX.subtotal = Number((Number(itemX.precio_info) * Number(itemX.cantidad)).toFixed(2));
        }


        itemX.monto_descuento = Number(Number(itemX.subtotal * Number(itemX.descuento / 100)).toFixed(2));
        itemX.total = itemX.subtotal - itemX.monto_descuento;
        if (!isNaN(itemX.total)) {
          return itemX.total;
        } else return 0;
      },

      regresar() {
        this.$router.push({
          name: "listado-solicitudes-compras"
        });
      },
      downloadItem(url) {
        if (!this.descargando) {
          this.descargando = true;
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Descargando archivo, espero un momento...',
                  variant: 'success',
                }
              },
              {
                position: 'bottom-right'
              });
          axios.get(url, {responseType: 'blob'})
              .then(({data}) => {
                let blob = new Blob([data], {type: 'application/pdf'});
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'ReporteSolicitudCompra-' + this.form.id_solicitud_compra + '.pdf';
                link.click();
                this.descargando = false;
              }).catch(function (error) {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'Error descargando el archivo',
                    variant: 'warning',
                  }
                },
                {
                  position: 'bottom-right'
                });
            this.descargando = false;
          })
        } else {
          this.descargando = false;
          this.$toast({
              component: ToastificationContent,
              props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Espere a que se complete la descarga anterior.',
                  variant: 'warning',
              }
          },
          {
              position: 'bottom-right'
          });
        }
      },
      formatDate(date) {
        return moment(date).format('YYYY-MM-DD')
      },
      obtenerSolicitud() {
        const self = this;
        self.loading = true;
        solicitud.obtenerSolicitud(
            {
              id_solicitud_compra: this.$route.params.id_solicitud_compra,
              cargar_dependencias: false
            },
            data => {
              self.form = data.solicitud;
              self.form.nombre_empresa = data.nombre_empresa;
              self.form.direccion_empresa = data.direccion_empresa;
              self.form.telefono_empresa = data.telefono_empresa;
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Ha ocurrido un error cargando los datos de la solicitud.',
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            }
        );
      },
      printInvoice: function () {
        window.print();
      },
    },
    computed: {

      total_cantidad() {
        return this.form.solicitud_productos.reduce((carry, item) => {

          if (this.form.estado !== 1) {
            return (carry + Number(item.cantidad_cotizado))
          } else {
            return (carry + Number(item.cantidad))
          }


        }, 0)
      },
      total_subt() {
        return this.form.solicitud_productos.reduce((carry, item) => {
              if (this.form.estado !== 1) {
                return (carry + Number((Number(item.cantidad_cotizado * item.precio_cotizado).toFixed(2))));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2))));
              }

            }
            , 0)
      },
      total_descuento() {
        return this.form.solicitud_productos.reduce((carry, item) => {

              if (this.form.estado !== 1) {
                return (carry + Number((Number(item.cantidad_cotizado * item.precio_cotizado).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
              } else {

                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
              }

            }
            , 0)
      },
      gran_total() {
        this.form.total = this.form.solicitud_productos.reduce((carry, item) => {

              if (this.form.estado !== 1) {
                return (carry + Number((Number(item.cantidad_cotizado * item.precio_cotizado).toFixed(2)) * Number(1 - (item.descuento / 100)).toFixed(2)));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio_info).toFixed(2)) * Number(1 - (item.descuento / 100)).toFixed(2)));
              }

            }
            , 0)

        return this.form.total;
      },
    },
    mounted() {
      this.obtenerSolicitud();
    }
  };
</script>
<style lang="scss" scoped>
    @import "../../../@core/scss/base/pages/app-invoice";
</style>
<style lang="scss">
    @media print {

        // Global Styles
        body {
            background-color: transparent !important;
        }
        nav.header-navbar {
            display: none;
        }
        .main-menu {
            display: none;
        }
        .header-navbar-shadow {
            display: none !important;
        }
        .content-header {
            display: none !important;
        }
        .content.app-content {
            margin-left: 0;
            padding-top: 2rem !important;
        }
        footer.footer {
            display: none;
        }
        .card {
            background-color: transparent;
            box-shadow: none;
        }
        .customizer-toggle {
            display: none !important;
        }

        // Invoice Specific Styles
        .invoice-preview-wrapper {
            .row.invoice-preview {
                .col-md-8 {
                    max-width: 100%;
                    flex-grow: 1;
                }

                .invoice-preview-card {
                    .card-body:nth-of-type(2) {
                        .row {
                            > .col-12 {
                                max-width: 50% !important;
                            }

                            .col-12:nth-child(2) {
                                display: flex;
                                align-items: flex-start;
                                justify-content: flex-end;
                                margin-top: 0 !important;
                            }
                        }
                    }
                }
            }

            // Action Right Col
            .invoice-actions {
                display: none;
            }
        }
    }
</style>
