<template>
    <section class="invoice-preview-wrapper">

        <!-- Alert: No item found -->
        <!--<b-alert
                variant="danger"
                :show="invoiceData === undefined"
        >
            <h4 class="alert-heading">
                Error fetching invoice data
            </h4>
            <div class="alert-body">
                No invoice found with this invoice id. Check
                <b-link
                        class="alert-link"
                        :to="{ name: 'apps-invoice-list'}"
                >
                    Invoice List
                </b-link>
                for other invoices.
            </div>
        </b-alert>-->

        <b-row class="invoice-preview">

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
                                <div class="logo-wrapper" style="margin-bottom: 0.9rem !important;">

                                    <h3 class="text-primary invoice-logo ml-0">
                                        {{nombre_empresa}}
                                    </h3>
                                </div>
                                <p class="card-text mb-25 ml-0">
                                    {{'Dirección: '+direccion_empresa}}
                                </p>
                                <p class="card-text mb-25 ml-0">
                                    {{'Telefono: '+telefono_empresa}}
                                </p>

                            </div>

                            <!-- Header: Right Content -->
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Orden de compra
                                    <span class="invoice-number">#{{ form.no_orden}}</span>
                                </h4>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">
                                        F. orden:
                                    </p>
                                    <p class="invoice-date">
                                        {{ formatDate(form.f_orden_compra)}}
                                    </p>
                                </div>
                                <!--<div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">
                                        Due Date:
                                    </p>
                                    <p class="invoice-date">
                                        {{ 'invoiceData.dueDate' }}
                                    </p>
                                </div>-->
                            </div>
                        </div>
                    </b-card-body>

                    <!-- Spacer -->
                    <hr class="invoice-spacing">

                    <!-- Invoice Client & Payment Details -->
                    <b-card-body

                            class="invoice-padding pt-0"
                    >
                        <b-row class="invoice-spacing">

                            <!-- Col: Invoice To -->
                            <b-col class="p-0" cols="12" xl="4">
                                <h6 class="mb-25">
                                    {{ 'Número documento:' }}
                                </h6>
                                <p class="card-text mb-25">
                                    {{form.numero_factura ? form.numero_factura : 'N/D'}}
                                </p>
                                <h6 class="mb-25">
                                    {{ 'Observaciones:' }}
                                </h6>
                                <p class="card-text mb-25">
                                    {{form.nota}}
                                </p>
                            </b-col>

                            <!-- Col: Payment Details   xl="6" cols="12" class="p-0 mt-xl-0 mt-2 d-flex justify-content-xl-end"-->
                            <b-col class="p-0 justify-content-xl-center" cols="12" xl="4">
                                <h6 class="mb-25">
                                    {{ 'Bodega entrante:' }}
                                </h6>
                                <router-link
                                        :to="{ name: 'inventario-bodegas-actualizar', params: { id_bodega: form.id_bodega } }"
                                        target="_blank">
                                    <p class="card-text mb-25">
                                        {{ form.orden_compra_bodega.descripcion}}
                                    </p>
                                </router-link>
                                <h6 class="mb-25 mt-1">
                                    {{ 'Proveedor:' }}
                                </h6>
                                <p class="card-text mb-25">
                                    {{form.orden_compra_proveedor.nombre_comercial}}
                                </p>
                                <h6 class="mb-25">
                                    {{ 'Usuario registra:' }}
                                </h6>
                                <p class="card-text mb-25">
                                    {{ form.u_creacion}}
                                </p>

                            </b-col>
                            <b-col class="p-0 justify-content-xl-end" cols="12" xl="4">
                                <h6 class="mb-25">
                                    {{ 'Estado actual:' }}
                                </h6>
                                <p class="card-text mb-25">
                                <div v-if="form.estado===0">
                                    <span class="badge badge-danger" style="font-size: 100%;">Anulada</span>
                                </div>

                                <div v-if="form.estado===99">
                                    <span class="badge badge-primary" style="font-size: 100%;">Borrador</span>
                                </div>

                                <div v-if="form.estado===4">
                                    <span class="badge badge-success" style="font-size: 100%;">Procesada</span>
                                </div>
                                </p>
                                <h6 class="mb-25">
                                    {{ 'Fecha emisión:' }}
                                </h6>
                                <p class="card-text mb-25">
                                    {{ formatDate(form.f_orden_compra)}}
                                </p>


                            </b-col>
                        </b-row>
                    </b-card-body>

                    <!-- Invoice Description: Table -->
                    <b-table-lite
                            :fields="fields"
                            :items="items"
                            foot-clone
                            responsive

                    >
                        <template #cell(codigo_barra)="data">
                            <b-card-text class="font-weight-bold mb-25">
                                {{data.item.orden_compra_producto.codigo_barra}}
                            </b-card-text>
                        </template>
                        <template #cell(descripcion)="data">
                            <b-card-text class="font-weight-bold mb-25">
                                {{data.item.orden_compra_producto.descripcion}}
                            </b-card-text>
                        </template>
                        <template #cell(cantidad)="data">
                            <b-card-text class="font-weight-bold mb-25">
                                {{data.item.cantidad}}
                            </b-card-text>
                        </template>
                        <template #cell(precio_dol)="data">
                            <b-card-text class="font-weight-bold mb-25">
                                {{data.item.precio_dol}}
                            </b-card-text>
                        </template>
                        <template #cell(precio_cord)="data">
                            <b-card-text class="font-weight-bold mb-25">
                                {{data.item.precio_cord}}
                            </b-card-text>
                        </template>
                        <template #cell(subtotal)="data">
                            <b-card-text class="font-weight-bold mb-25">
                                {{data.item.subtotal}}
                            </b-card-text>
                        </template>

                        <!--Custo footer - summary-->
                        <template #foot(codigo_barra)="data">
                            <span class="text-bold">{{ 'Totales:' }}</span>
                        </template>
                        <template #foot(descripcion)="data">
                            <span class="text-bold">{{ '' }}</span>
                        </template>
                        <template #foot(cantidad)="data">
                            <span class="text-bold">{{ total_cantidad }}</span>
                        </template>
                        <template #foot(precio_dol)="data">
                            <span class="text-bold">{{ total_precio_dol|formatMoney(2) }}</span>
                        </template>
                        <template #foot(precio_cord)="data">
                            <span class="text-bold">{{ total_precio_cord|formatMoney(2) }}</span>
                        </template>
                        <template #foot(subtotal)="data">
                            <span class="text-bold">{{ total_subt|formatMoney(2) }}</span>
                        </template>

                        <template #foot(descuento)="data">
                            <span class="text-bold">{{ 'Descuento:' }}</span>
                        </template>
                    </b-table-lite>

                    <!-- Invoice Description: Total -->
                    <b-card-body class="invoice-padding pb-0">
                        <b-row>

                            <!-- Col: Sales Persion -->
                            <b-col
                                    class="mt-md-0 mt-3"
                                    cols="12"
                                    md="6"
                                    order="2"
                                    order-md="1">
                                <b-card-text class="mb-0">

                                </b-card-text>
                            </b-col>

                            <!-- Col: Total -->
                            <!--<b-col
                                    cols="12"
                                    md="6"
                                    class="mt-md-6 d-flex justify-content-end"
                                    order="1"
                                    order-md="2">
                                <div class="invoice-total-wrapper">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Total unidades:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{total_cantidad_prods}}
                                        </p>
                                    </div>

                                </div>
                            </b-col>-->
                        </b-row>
                    </b-card-body>

                    <!-- Spacer -->
                    <hr class="invoice-spacing">

                    <!-- Note -->
                    <b-card-body class="invoice-padding pt-0">
                        <b-row>

                            <!-- Col: Sales Persion -->
                            <b-col
                                    class="mt-md-0 mt-3"
                                    cols="12"
                                    md="6"
                                    order="2"
                                    order-md="1">
                                <b-card-text class="mb-0">

                                </b-card-text>
                            </b-col>

                            <!-- Col: Total -->
                            <b-col
                                    class="mt-md-6 d-flex justify-content-end"
                                    cols="12"
                                    md="6"
                                    order="1"
                                    order-md="2">
                                <!-- Summary-->
                                <div class="invoice-total-wrapper">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Subtotal:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{total_subt}}
                                        </p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Descuento:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{form.total_descuento}}
                                        </p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">
                                            Total:
                                        </p>
                                        <p class="invoice-total-amount">
                                            {{form.total}}
                                        </p>
                                    </div>

                                </div>
                            </b-col>
                        </b-row>
                    </b-card-body>
                </b-card>
            </b-col>

            <!-- Right Col: Card -->
            <b-col
                    class="invoice-actions"
                    cols="12"
                    md="4"
                    xl="3"
            >
                <b-card>

                    <!-- Button: Send Invoice -->
                    <!--                    <b-button-->
                    <!--                            v-ripple.400="'rgba(255, 255, 255, 0.15)'"-->
                    <!--                            v-b-toggle.sidebar-send-invoice-->
                    <!--                            variant="primary"-->
                    <!--                            class="mb-75"-->
                    <!--                            block-->
                    <!--                    >-->
                    <!--                        Send Invoice-->
                    <!--                    </b-button>-->

                    <!-- Button: DOwnload -->
                    <!--<b-button
                            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                            variant="outline-secondary"
                            class="mb-75"
                            block
                            @click.prevent="downloadItem('pdf',form.id_salida)"
                    >
                        Download <feather-icon icon="DownloadIcon" aria-hidden="true" style="color: #0a91ff"></feather-icon>
                    </b-button>-->

                    <!-- Button: Print -->
                    <b-button
                            @click="printWindow"
                            block
                            class="mb-75"
                            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                            variant="outline-secondary"
                    >
                        Print
                    </b-button>

                    <!-- Button: Edit -->
                    <!--<b-button
                            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                            variant="outline-secondary"
                            class="mb-75"
                            block
                            :to="{ name: 'apps-invoice-edit', params: { id: $route.params.id } }"
                    >
                        Edit
                    </b-button>-->

                    <!-- Button: Add Payment -->
                    <!-- <b-button
                             v-b-toggle.sidebar-invoice-add-payment
                             v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                             variant="success"
                             class="mb-75"
                             block
                     >
                         Add Payment
                     </b-button>-->
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
  import orden from "../../../api/Compras/orden_compras";
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
    VBTooltip
  } from 'bootstrap-vue'
  import vSelect from 'vue-select'
  import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
  import {ValidationObserver, ValidationProvider} from 'vee-validate';
  import flatPickr from 'vue-flatpickr-component'
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import Ripple from 'vue-ripple-directive'

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
      flatPickr,
      BCol,
      BCardText,
      BTableLite,
      BCardBody
    },
    directives: {
      'b-tooltip': VBTooltip,
      Ripple
    },
    data() {
      return {
        loading: true,
        msg: 'Cargando el contenido espere un momento',
        nombre_empresa: '',
        direccion_empresa: '',
        telefono_empresa: '',
        format: "dd-MM-yyyy",
        descargando: false,
        form: {
          codigo_salida: "",
          fecha_salida: "",
          id_tipo_salida: "",
          id_proveedor: 0,
          id_bodega: 0,
          detalleProductos: [],
          orden_compra_proveedor: [],
          orden_compra_bodega: [],
          orden_compra_productos: [],
          salida_tipo: [],
          log_registro: [],
          total: 0,
          sub_total: 0,
        },
        btnAction: "Registrar",
        errorMessages: [],
        items: [],
        fields: [
          'codigo_barra',
          'descripcion',
          'cantidad',
          'precio_dol',
          'precio_cord',
          'subtotal',
          'descuento',
        ],
      };
    },
    methods: {
      downloadItem(extension, id_salida) {
        const self = this;
        if (!this.descargando) {
          self.loading = true;
          let url = 'salidas/reporte/' + extension + '/' + id_salida;
          this.descargando = true;
          axios.get(url, {responseType: 'blob'})
              .then(({data}) => {
                let blob = new Blob([data], {type: 'application/pdf'})

                extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob)
                link.download = 'FormatoControlSalida.' + extension;
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
                }, {
                  position: 'bottom-right'
                });
              }).catch(function (error) {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'AlertTriangleIcon',
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
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'AlertCircleIcon',
                  text: 'Espere a que se complete la descarga anterior',
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
        }
      },
      sub_total(itemX) {

        if (this.form.estado > 2) {
          itemX.subtotal = Number((Number(itemX.precio_facturado) * Number(itemX.cantidad_facturado)).toFixed(2));
        } else {
          itemX.subtotal = Number((Number(itemX.precio) * Number(itemX.cantidad)).toFixed(2));
        }


        itemX.monto_descuento = Number(Number(itemX.subtotal * Number(itemX.descuento / 100)).toFixed(2));
        itemX.total = itemX.subtotal - itemX.monto_descuento;
        if (!isNaN(itemX.total)) {
          return itemX.total;
        } else return 0;
      },
      formatDate(date) {
        return moment(date).format('YYYY-MM-DD')
      },
      obtenerOrdenCompra() {
        const self = this;
        self.loading = true;
        orden.obtenerOrdenCompra(
            {
              id_orden_compra: this.$route.params.id_orden_compra,
              cargar_dependencias: true
            },
            data => {
              self.form = data.orden;
              self.items = self.form.orden_compra_productos;
              self.nombre_empresa = data.nombre_empresa;
              self.direccion_empresa = data.direccion_empresa;
              self.telefono_empresa = data.telefono_empresa;
              self.loading = false;
            },
            err => {
              self.loading = false;
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: err,
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            }
        );
      },
      descargarReporteSalida() {
        if (!this.loading) {
          var self = this;
          self.loading = true;
          var extensionx = 'pdf';
          alertify.success("Descargando datos, espere un momento.....", 5);
          axios.post('salidas/reporte',
              {
                id_salida: self.form.id_salida,
                extension: extensionx
              }, {responseType: 'blob'})
              .then(({data}) => {
                let blob = new Blob([data], {type: 'application/pdf'})
                extensionx === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob)
                link.download = 'ReporteSalida.' + extensionx;
                link.click()
                this.loading = false;
              }).catch(function (error) {
            alertify.error("Error Descargando archivo.....", 5);
            self.loading = false;
          })
        } else {
          alertify.warning("Espere a que se complete la descarga anterior", 5);
        }
      },
      /*      obtenerSalida() {
              var self = this;
              salida.obtenerSalida(
                  {
                    id_salida: this.$route.params.id_salida
                  },
                  data => {
                    self.form = data.salida;
                    self.items = self.form.salida_productos;
                    self.nombre_empresa = data.nombre_empresa;
                    self.direccion_empresa = data.direccion_empresa;
                    self.telefono_empresa = data.telefono_empresa;
                    self.loading = false;
                  },
                  err => {
                    console.log(err);
                    self.loading = false;
                  }
              );
            },*/
      nueva() {
        const self = this;
        orden.nueva({}, data => {
              self.logo_empresa = data.logo_empresa;
            },
            err => {
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'err',
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            })

      },
      printWindow: function () {
        window.print();
      }
    },
    computed: {

      total_cantidad() {
        return this.form.orden_compra_productos.reduce((carry, item) => {

          if (this.form.estado > 2) {
            return (carry + Number(item.cantidad))
          } else {
            return (carry + Number(item.cantidad))
          }

        }, 0)
      },
      total_precio_dol() {
        return this.form.orden_compra_productos.reduce((carry, item) => {

          if (this.form.estado > 2) {
            return (carry + Number(item.precio_dol))
          } else {
            return (carry + Number(item.precio_dol))
          }

        }, 0)
      },
      total_precio_cord() {
        return this.form.orden_compra_productos.reduce((carry, item) => {

          if (this.form.estado > 2) {
            return (carry + Number(item.precio_cord))
          } else {
            return (carry + Number(item.precio_cord))
          }


        }, 0)
      },
      total_subt() {
        return this.form.orden_compra_productos.reduce((carry, item) => {
              if (this.form.estado > 2) {
                const subtotal = Number(item.subtotal).toFixed(2);
                this.form.subtotal = carry + Math.abs(subtotal);
                return this.form.subtotal;
              } else {
                const subtotal = Number(item.subtotal).toFixed(2);
                this.form.subtotal = carry + Math.abs(subtotal);
                return this.form.subtotal;
              }
            }
            , 0)
      },
      total_descuento() {
        return this.form.orden_compra_productos.reduce((carry, item) => {
              if (this.form.estado > 2) {
                return (carry + Number((Number(item.cantidad_facturado * item.precio_facturado).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
              } else {
                return (carry + Number((Number(item.cantidad * item.precio).toFixed(2)) * Number(item.descuento / 100).toFixed(2)));
              }

            }
            , 0)
      },
      gran_total() {
        this.form.total = this.form.orden_compra_productos.reduce((carry, item) => {
              return (carry + Number(this.form.subtotal - this.form.total_descuento));
            }
            , 0);

        return this.form.total;
      },
    },
    mounted() {
      this.obtenerOrdenCompra();
      this.nueva();
    },
  }
</script>

<style lang="scss" scoped>
    @import "~@core/scss/base/pages/app-invoice.scss";
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

