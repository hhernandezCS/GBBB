<template>
    <b-card>

        <div class="row">
            <div class="col-md-3 sm-text-center">
                <router-link :to="{ name: 'cajabanco-arqueos-registrar' }" class="btn btn-success" tag="button">
                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                    Registrar
                </router-link>
            </div>
            <div @keyup.enter="filter.page = 1;obtener();"
                 class="col-md-9 sm-text-center form-inline justify-content-end">
                <div class="row w-100 justify-content-end align-items-center">

                    <div class="col-md-3">
                        <div class="form-group check-label">
                            <label class="check-label mb-1 mr-sm-1 mb-sm-0">
                                <b-form-checkbox
                                        class="mb-1 mr-sm-1 mb-sm-0" size=""
                                        v-model="filter.search.status"></b-form-checkbox>
                                Mostrar Todos</label>
                        </div>
                    </div>
                    <!--                <div class="form-group check-label">-->
                    <!--                    <label class="check-label  mb-1 mr-sm-1 mb-sm-0">-->
                    <!--                        <b-form-checkbox @input="deseleccionar"-->
                    <!--                                         class="mb-1 mr-sm-1 mb-sm-0"-->
                    <!--                                         size=""-->
                    <!--                                         v-model="filter.search.vendedor_especifico"></b-form-checkbox>-->
                    <!--                        Vendedor Especifico</label>-->
                    <!--                </div>-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <!--                    <b-form-input class="form-control" readonly style="width: 100%"-->
                            <!--                                  v-if="!filter.search.vendedor_especifico"></b-form-input>-->
                            <v-select
                                    :filterable="false"
                                    :options="vendedores"
                                    @input="seleccionarvendedor"
                                    @search="onSearchVendedor"
                                    label="text"
                                    v-model="vendedor"
                                    :clearable="false"
                                    style="width: 100%"
                            >
                                <!--v-on:input="$emit('input', $event.target.value)" Emitir evento a v-model de vue-select-->
                                <template slot="no-options">
                                    Escriba para buscar un vendedor
                                </template>
                            </v-select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <b-button @click="filter.page = 1;obtener();" style="margin-left: 5px"
                                  variant="info">
                            <feather-icon icon="SearchIcon"></feather-icon>

                        </b-button>
                    </div>

                </div>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Fecha Arqueo</th>
                    <th>Vendedor</th>
                    <th class="text-center">Monto Ingresos C$</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center action" colspan="3">Opciones</th>
                </tr>
                </thead>
                <tbody>
                <tr :key="solicitud.id_solicitud_viatico" v-for="solicitud in solicitudes">
                    <td>{{ formatDate(solicitud.fecha_arqueo) }}</td>
                    <td>{{ solicitud.vendedor_arqueo?solicitud.vendedor_arqueo.nombre_completo : 'N/D' }}</td>
                    <td class="text-center">C$ {{ solicitud.monto_ingresos |formatMoney(2)}}</td>
                    <td class="text-center">
                        <div v-if="solicitud.estado === 1">
                            <span class="badge badge-success">Registrado</span>
                        </div>
                        <div v-if="solicitud.estado === 2">
                            <span class="badge badge-info">Depositado</span>
                        </div>
                        <div v-if="solicitud.estado === 0">
                            <span class="badge badge-danger">Anulado</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <a :disabled="descargando" @click.prevent="descargarReporte('xls',solicitud.id_arqueo)"
                           v-b-tooltip="'Descargar Reporte'">
                            <feather-icon icon="EyeIcon"></feather-icon>
                        </a>
                    </td>
                    <!--                    <td class="text-center" v-if="solicitud.estado === 1">-->
                    <!--                        <router-link-->
                    <!--                                :to="{ name: 'cajabanco-arqueos-actualizar', params: { id_arqueo: solicitud.id_arqueo } }"-->
                    <!--                                tag="a">-->
                    <!--                            <feather-icon icon="EditIcon"></feather-icon>-->
                    <!--                        </router-link>-->
                    <!--                    </td>-->
                    <td class="text-center">
                        <a @click.prevent="anular(solicitud.id_arqueo)" v-b-tooltip="'Anular Arqueo Diario'"
                           v-if="solicitud.estado === 1">
                            <feather-icon icon="TrashIcon" style="color:red;"></feather-icon>
                        </a>
                    </td>
                </tr>
                <tr v-if="!solicitudes.length">
                    <td class="text-center" colspan="5">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>

        <b-card-footer>
            <pagination :items="solicitudes" :limit="filter.limit" :limitOptions="filter.limitOptions"
                        :page="filter.page" :total="total" @changeLimit="changeLimit"
                        @changePage="changePage"></pagination>
        </b-card-footer>

        <!--Gif de pantalla de carga-->
        <template v-if="loading">
            <BlockUI>
                <b-spinner label="cargando..." variant="info"/>
            </BlockUI>
        </template>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import solicitud from '../../../api/CajaBanco/arqueos_cajas';
    import axios from 'axios'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import es from 'vuejs-datepicker/dist/locale/translations/es';
    import {
        BAlert,
        BButton,
        BCard,
        BCardFooter,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BPaginationNav,
        BRow,
        BSpinner,
        VBTooltip,
        BCol
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
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
            BFormInput,
            BSpinner,
            BCol
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
                        field: 'id_vendedor',
                        value: '',
                        status: 0,
                        vendedor_especifico: false
                    }
                },
                solicitudes: [],
                total: 0,
                descargando: false,
                vendedoresBusquedaURL: "/ventas/vendedores/buscar",
                vendedor: {},
                vendedores: [],
            }
        },
        methods: {
            obtener() {
                const self = this;
                self.loading = true;
                solicitud.obtener(self.filter, data => {
                    self.solicitudes = data.rows;
                    self.total = data.total;
                    self.loading = false;
                }, err => {
                    self.loading = false;
                })
            },
            changeLimit(limit) {
                this.filter.page = 1;
                this.filter.limit = limit;
                this.obtener()
            },
            changePage(page) {
                this.filter.page = page;
                this.obtener()
            },
            descargarReporte(ext, id_arqueo) {
                const self = this;
                const formato = 'xls';
                if (id_arqueo) {
                    self.loading = true;
                    axios.get('cajabanco/arqueo/reporte/' + ext + '/' + id_arqueo,
                        {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})
                            ext === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});
                            let link = document.createElement('a');
                            link.setAttribute('target', '_blank');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'ArqueoCaja.' + ext;
                            link.click()
                            self.loading = false;
                        }).catch(function (error) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: error,
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        self.loading = false;
                    });
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'No se ha seleccionado un arqueo',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.loading = false;
                }
            },
            seleccionarvendedor() {
                const self = this;

                self.filter.search.value = self.vendedor.id_vendedor;
            },
            deseleccionar() {
                this.vendedor = {};
                this.filter.search.value = ''
            },
            formatDate(date) {
                return moment(date).format('YYYY-MM-DD')
            },
            anular(id_arqueo) {


                this.$swal.fire({
                    title: 'Esta seguro de anular este arqueo?',
                    text: "Digite la causa de la anulación del arqueo",
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, anular arqueo diario'
                }).then((result) => {
                    if (result.value) {
                        solicitud.anular(
                            {
                                id_arqueo: id_arqueo,
                                causa_anulacion: result.value
                            },
                            data => {
                                this.$swal.fire(
                                    'Anulado',
                                    'El registro del arqueo diario ha sido anulado',
                                    'success'
                                )
                                this.obtener();
                            },
                            err => {
                                this.$swal.fire(
                                    'No se puede anular arqueo!',
                                    err,
                                    'warning'
                                )
                            }
                        );


                    }
                })
            },
            // Livesearch vendedores
            onSearchVendedor(searchVendedor, loading) {
                if (searchVendedor.length) {
                    loading(true);
                    this.searchVendedor(loading, searchVendedor, this)
                }
            },
            searchVendedor: _.debounce((loading, search, vm) => { // /ventas/clientes/buscar
                const self = this;
                axios.get(`/ventas/vendedores/buscar?q=${escape(search)}`).then(res => {
                    vm.options = res.data.results;
                    vm.vendedores = res.data.results;
                    loading(false)
                })
            }, 50),
        },
        /*components: {
            'pagination': Pagination
        },*/
        mounted() {
            this.obtener()
        }
    }
</script>

<style lang="scss" scoped>

    @import '@core/scss/vue/libs/vue-select.scss';

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
            width: 100px;
        }
    }
</style>
