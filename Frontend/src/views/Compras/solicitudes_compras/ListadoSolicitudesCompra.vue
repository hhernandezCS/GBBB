<template>
    <b-card>
        <b-row>
            <div class="col-lg-4 col-md-12 col-sm-12 mb-0 mb-sm-2 mt-1 sm-text-center">
                <router-link :to="{ name: 'registrar-solicitudes-compras' }" class="btn btn-success" tag="button">
                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                    Registrar
                </router-link>
            </div>
            <div
                    @keyup.enter="filter.page = 1;obtenerSolicitudesCompra();"
                    class="col-lg-8 col-md-12 col-sm-12 sm-text-center form-inline justify-content-end"
            >
                <b-form-select
                        class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                        v-model="filter.search.field"
                >
                    <option value="observacion">observacion</option>
                </b-form-select>
                <b-form-input
                        class="form-control mb-1 mr-sm-1 mb-sm-0"
                        placeholder="Buscar"
                        type="text"
                        v-model="filter.search.value"
                />
                <b-button @click="filter.page = 1;obtenerSolicitudesCompra();" variant="info">
                    <feather-icon icon="SearchIcon"></feather-icon>
                    Buscar
                </b-button>
            </div>
        </b-row>
        <div class="table-responsive mt-3">
            <b-table :fields="fields" :items="solicitudes_compras" striped responsive="sm" :sort-by-sync="sortBy" :sort-desc-sync="sortDesc" sort-icon-left>
                <template #cell(id)="data">
                    {{data.item.id_solicitud_compra}}
                </template>
                <template #cell(moneda)="data">
                    {{data.item.solicitud_moneda? data.item.solicitud_moneda.descripcion : 'N/D' }}
                </template>
                <template #cell(area)="data">
                    {{data.item.solicitud_area? data.item.solicitud_area.descripcion : 'N/D' }}
                </template>
                <template #cell(trabajador)="data">
                    {{data.item.solicitud_trabajador? data.item.solicitud_trabajador.primer_nombre + ' ' +
                    data.item.solicitud_trabajador.primer_apellido : 'N/A' }}
                </template>
                <template #cell(f_necesidad)="data">
                    {{formatDate(data.item.f_necesidad)}}
                </template>
                <template #cell(estado)="data">
                    <div v-if="data.item.estado===0">
                        <b-badge variant="danger">Rechazado</b-badge>
                    </div>
                    <div v-if="data.item.estado===1">
                        <b-badge variant="info">Solicitado</b-badge>
                    </div>
                    <div v-if="data.item.estado===2">
                        <b-badge variant="warning">Cotizado</b-badge>
                    </div>
                    <div v-if="data.item.estado===3">
                        <b-badge variant="success">Autorizado</b-badge>
                    </div>
                    <div v-if="data.item.estado===4">
                        <b-badge variant="primary">Orden Compra</b-badge>
                    </div>
                    <div v-if="data.item.estado===99">
                        <b-badge variant="dark">Borrador</b-badge>
                    </div>
                </template>
                <!--Scope para dropdown de opciones para cada item-->
                <template #cell(opciones)="data">
                    <b-dropdown id="dropdown-buttons" no-caret right style="position: inherit !important;"
                                text="Right align"
                                toggle-class="text-decoration-none" variant="link">
                        <!--Icono de opciones -->
                        <template v-slot:button-content>
                            <feather-icon class="text-body align-middle mr-25" icon="MoreVerticalIcon"
                                          size="16"/>
                        </template>

                        <!--Mostrar detalle de compras-->
                        <b-dropdown-item>
                            <router-link
                                    :to="{ name: 'mostrar-solicitudes-compras', params: { id_solicitud_compra: data.item.id_solicitud_compra } }"
                                    tag="a"
                                    v-b-tooltip="'Mostrar Detalles de Solicitud de Compra'"
                            >
                                <feather-icon aria-hidden="true" icon="EyeIcon"
                                              style="color: #0a91ff" class="mr-50"></feather-icon><span>Mostrar detalle</span>
                            </router-link>
                        </b-dropdown-item>
                        <!--Actualizar compra-->
                        <template v-if="data.item.estado===99">
                            <b-dropdown-item>
                                <router-link
                                        :to="{ name: 'actualizar-solicitudes-compras', params: { id_solicitud_compra: data.item.id_solicitud_compra } }"
                                        tag="a"
                                        v-b-tooltip="'Actualizar de Solicitud de Compra'"><feather-icon icon="EditIcon"></feather-icon><span> Actualizar</span>
                                </router-link>
                            </b-dropdown-item>
                        </template>
                        <!--Revisar solicitud de compra-->
                        <template v-if="[1,2,3].indexOf(Number(data.item.estado)) >= 0">
                            <b-dropdown-item>
                                <router-link
                                        :to="{ name: 'revisar-solicitudes-compras', params: { id_solicitud_compra: data.item.id_solicitud_compra } }"
                                        tag="a"
                                        v-b-tooltip="data.item.estado===1?'Confirmar Cotización de Solicitud': data.item.estado===2?'Autorizar Solicitud de Compra':'Revisar Estado de Solicitud de Compra'">
                                    <feather-icon icon="CheckCircleIcon"></feather-icon><span> Revisar solicitud</span>
                                </router-link>
                            </b-dropdown-item>
                        </template>
                    </b-dropdown>
                </template>
                <!--Scope para mostrar detalles en cada fila de resultados-->
                <template #cell(detalles)="data">
                    <b-button size="sm" variant="secondary" @click="data.toggleDetails" class="mr-2">
                        {{data.detailsShowing ? 'Ocultar' : 'Mostrar'}}
                    </b-button>
                </template>
                <template #row-details="data">
                    <b-card>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center table-number">Código producto</th>
                                <th>Descripción producto</th>
                                <th>Unidad de medida</th>
                                <th>Cantidad</th>
                                <th>Precio de compra</th>
                                <th>Descuento %</th>
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                    :key="productosDetalle.id_solicitud_compra_producto"
                                    v-for="productosDetalle in data.item.solicitud_productos">
                                <td>{{ productosDetalle.solicitud_producto.codigo_sistema }}</td>
                                <td>{{ productosDetalle.solicitud_producto.descripcion }}</td>
                                <td>{{ productosDetalle.solicitud_producto.unidad_medida.siglas }}</td>
                                <td>{{ productosDetalle.cantidad }}</td>
                                <td>{{ productosDetalle.precio_info }}</td>
                                <td>{{ productosDetalle.descuento }}</td>
                                <td>{{productosDetalle.subtotal}}</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td>Subtotal</td>
                                <td><strong>{{data.item.solicitud_moneda ? data.item.solicitud_moneda.codigo :
                                    ''}} {{data.item.subtotal | formatMoney(2)}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td>Descuento</td>
                                <td><strong>{{data.item.solicitud_moneda ? data.item.solicitud_moneda.codigo :
                                    ''}} {{data.item.total_descuento | formatMoney(2)}}</strong></td>
                            </tr>
                            <tr>
                                <td class="item-footer" colspan="2"> Total Unidades</td>
                                <td></td>
                                <td class="item-footer">
                                    <strong>{{data.item.tot_unidades}}</strong>
                                </td>
                                <td></td>
                                <td>Total</td>
                                <td><strong>{{data.item.solicitud_moneda ? data.item.solicitud_moneda.codigo :
                                    ''}} {{data.item.total | formatMoney(2)}}</strong></td>
                            </tr>
                            </tfoot>
                        </table>
                    </b-card>
                </template>
            </b-table>
        </div>
        <pagination
                :items="solicitudes_compras"
                :limit="filter.limit"
                :limitOptions="filter.limitOptions"
                :page="filter.page"
                :total="total"
                @changeLimit="changeLimit"
                @changePage="changePage"
        ></pagination>
        <!--Gif de pantalla de carga-->
        <template v-if="loading">
            <BlockUI>
                <b-spinner label="cargando..." variant="info"/>
            </BlockUI>
        </template>
    </b-card>
</template>

<script type="text/ecmascript-6">
  import solicitud from "../../../api/Compras/solicitudes_compras";
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
      BDropdown,
      BDropdownItem
  } from 'bootstrap-vue'
  import vSelect from 'vue-select'
  import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
  import {ValidationObserver, ValidationProvider} from 'vee-validate';

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
      BDropdownItem
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: true,
        filter: {
          page: 1,
          limit: 5,
          limitOptions: [5, 10, 15, 20],
          search: {
            field: "observacion",
            value: ""
          }
        },
        solicitudes_compras: [],
        fields: [
          {key:'detalles', sortable: false},
          {key: 'id', sortable: false},
          {key: 'moneda', sortable: false},
          {key: 'area', sortable: false},
          {key: 'trabajador', sortable: false},
          {key: 'f_necesidad', sortable: true},
          {key: 'estado', sortable: false},
          {key: 'opciones', sortable: false}
        ],
        sortBy: 'f_necesidad',
        sortDesc: false,
        total: 0
      };
    },
    methods: {
      mostrarProductos(key) {
        if (this.solicitudes_compras[key].mostrar) {
          this.solicitudes_compras[key].mostrar = 0;
        } else {
          this.solicitudes_compras[key].mostrar = 1;
        }
      },
      obtenerSolicitudesCompra() {
        var self = this;
        self.loading = true;
        solicitud.obtenerSolicitudesCompra(
            self.filter,
            data => {
              data.rows.forEach((solicitudes_compras, key) => {
                data.rows[key].mostrar = 0;
              });
              self.solicitudes_compras = data.rows;
              self.total = data.total;
              self.loading = false;
            },
            err => {
              self.loading = false;
              // console.log(err);
            }
        );
      },
      changeLimit(limit) {
        this.filter.page = 1;
        this.filter.limit = limit;
        this.obtenerSolicitudesCompra();
      },
      changePage(page) {
        this.filter.page = page;
        this.obtenerSolicitudesCompra();
      },
      formatDate(date) {
        return moment(date).format('YYYY-MM-DD')
      },
    },
    /*components: {
      pagination: Pagination
    },*/
    mounted() {
      this.obtenerSolicitudesCompra();
    }
  };
</script>
<style lang="scss" scoped>
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
