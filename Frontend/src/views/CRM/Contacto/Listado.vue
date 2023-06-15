<template>
    <b-container fluid>
        <b-row>
            <b-card class="col-12">
                <b-row>
                    <div class="col-md-3 sm-text-center">
                        <router-link class="btn btn-success" tag="button"
                                     :to="{ name: 'crm-contactos-registrar' }">
                            <feather-icon icon="PlusCircleIcon"></feather-icon>
                            Registrar
                        </router-link>
                    </div>

                    <div @keyup.enter="filter.page = 1;obtener();"
                         class="col-md-9 sm-text-center form-inline justify-content-end">
                        <b-form-checkbox v-model="filter.search.status" class="custom-control-primary mr-1">
                            Mostrar Todos
                        </b-form-checkbox>
                        <select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                            <option value="nombre">Nombre</option>
                        </select>
                        <input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0"
                               placeholder="Buscar"
                               type="text">
                        <b-button variant="info" @click="filter.page = 1;obtener();"
                                  v-b-tooltip.hover.top="'Buscar!'">
                            <feather-icon icon="SearchIcon"></feather-icon>
                        </b-button>
                        <a hidden :disabled="descargando" @click.prevent="downloadItem('pdf')"
                           style="color: #ffffff;padding-left: 2px">
                            <b-button :disabled="descargando" variant="danger" v-b-tooltip.hover.top="'PDF'">
                                <feather-icon icon="DownloadCloudIcon"></feather-icon>
                            </b-button>
                        </a>
                        <a hidden :disabled="descargando" @click.prevent="downloadItem('xls')"
                           style="color: #ffffff;padding-left: 2px">
                            <b-button :disabled="descargando" variant="success" v-b-tooltip.hover.top="'XLS'">
                                <feather-icon icon="DownloadCloudIcon"></feather-icon>
                            </b-button>
                        </a>
                    </div>


                </b-row>

                <b-row>
                    <b-col md="4" class="">
                        <div class="mt-2">
                            <b-form-group
                                    label="Ordenar por"
                                    label-for="sort-by-select"
                                    label-cols-sm="3"
                                    label-align-sm="left"
                                    label-size="md"
                                    class="m-0"
                                    v-slot="{ ariaDescribedby }"
                            >
                                <b-input-group size="md">
                                    <b-form-select
                                            id="sort-by-select"
                                            v-model="sortBy"
                                            :options="sortOptions"
                                            :aria-describedby="ariaDescribedby"
                                            class="w-70"
                                    >
                                        <template #first>
                                            <option value="">-- ninguno --</option>
                                        </template>
                                    </b-form-select>

                                    <b-form-select
                                            v-model="sortDesc"
                                            :disabled="!sortBy"
                                            :aria-describedby="ariaDescribedby"
                                            class="w-30"
                                    >
                                        <option :value="false">Ascendente</option>
                                        <option :value="true">Descendente</option>
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                        </div>
                    </b-col>
                </b-row>

                <div class="table table-responsive mt-1">
                    <b-table
                            class="tbsearch"
                            striped
                            responsive="sm"
                            hover
                            bordered
                            small
                            :per-page="filter.limit"
                            :fields="fields"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            sort-icon-left
                            :items="contactos"
                            show-empty>
                        <!--Customo rendering data-->
                        <template #cell(nombre)="data">
                            {{data.item.nombre + ' ' + data.item.apellido}}
                        </template>
                        <template #cell(compania_contactos.nombre_compania)="data">
                            {{data.item.compania_contactos === null ? 'Mútliples compañias asociadas' :
                            data.item.compania_contactos.nombre_compania}}
                        </template>
                        <template #cell(estado)="data">
                            <div v-if="data.item.estado" class="text-center">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Desactivado</span>
                            </div>
                        </template>
                        <template #cell(actions)="data" >
                            <router-link v-b-tooltip="'Actualizar Contactos'"
                                         :to="{name: 'crm-contactos-actualizar', params: {id_contacto: data.item.id_contacto}}"
                                         tag="a">
                                <feather-icon icon="EditIcon"></feather-icon>
                            </router-link>
                        </template>
                        <template #empty="">
                            <h6 class="text-center">{{ "No se encontraron datos" }}</h6>
                        </template>
                        <template #emptyfiltered="scope">
                            <h4>{{ scope.emptyFilteredText }}</h4>
                        </template>

                        <template #row-details="data">
                            <b-table
                                    striped
                                    responsive="sm"
                                    hover
                                    bordered
                                    small
                                    :items="detalles"
                                    show-empty>

                            </b-table>
                        </template>
                    </b-table>
                </div>

                <b-card-footer>
                    <b-row>
                        <div class="col-md-12">
                            <pagination @changePage="changePage" @changeLimit="changeLimit" :items="contactos"
                                        :total="total"
                                        :page="filter.page" :limitOptions="filter.limitOptions"
                                        :limit="filter.limit"></pagination>
                        </div>
                    </b-row>
                </b-card-footer>

                <template v-if="loading">
                    <BlockUI>
                        <b-spinner variant="info" label="loading..."/>
                    </BlockUI>
                </template>
            </b-card>
        </b-row>
    </b-container>
</template>

<script type="text/ecmascript-6">
    import vSelect from 'vue-select'
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
        BSpinner,
        BTable,
        BFormSelect,
        BInputGroup,
        BContainer,
        BPagination
    } from 'bootstrap-vue'
    import contacto from '../../../api/CRM/Contactos'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import es from 'vuejs-datepicker/dist/locale/translations/es'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import factura from "../../../api/CajaBanco/facturas";
    import tipoContacto from "../../../api/CRM/TipoContactos";

    export default {
        components: {
            BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
            BBadge,
            BTab,
            BTabs,
            BSpinner,
            BTable,
            vSelect,
            BFormSelect,
            BInputGroup,
            BContainer,
            BPagination
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
                    limit: 5,
                    limitOptions: [5, 10, 15, 20],
                    search: {
                        field: 'nombre',
                        value: '',
                        status: 0
                    }
                },
                contactos: [],
                detalles: [],
                companiasDet: [],
                total: 0,
                descargando: false,
                companias: [],
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                fields: [
                    {key: 'nombre', sortable: true, sortDirection: 'asc', label: 'Nombre Completo', thStyle:{width: "25%"}, thClass:'text-center'},
                    {key: 'compania_contactos.nombre_compania', sortable: true, label: 'Compañias', thStyle:{width: "20%"}, thClass:'text-center'},
                    {key: 'telefono_trabajo', sortable: true, label: 'Teléfono trabajo', thStyle:{width: "20%"}, thClass:'text-center'},
                    {key: 'pais_contacto.descripcion', sortable: true, label: 'Pais', thStyle:{width: "20%"}, thClass:'text-center'},
                    {key: 'estado', sortable: true, label: 'Estado', thStyle:{width: "10%"}, thClass:'text-center'},
                    {key: 'actions', label: 'Opciones', thStyle:{width: "auto"}, thClass:'text-center', tdClass:'text-center'}
                ],
            }
        },
        computed: {
            sortOptions() {
                // Create an options list from our fields
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => {
                        return {text: f.label, value: f.key}
                    })
            }
        },
        methods: {
            obtener() {
                const self = this;
                self.loading = true;
                contacto.obtener(
                    self.filter,
                    data => {
                        self.contactos = data.rows;
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
                        }, {
                            position: 'bottom-right'
                        })
                    }
                )
            },
            obtenerDetalles(index) {
                const self = this;
                self.loading = true;
                contacto.obtenerDetalles({
                        id_contacto: index
                    },
                    data => {
                        self.detalles = data.detalles;
                        self.companiaDet = data.companias;
                        self.loading = false;
                    })
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
        },
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
                cursor: pointer;
            }
        }
    }

</style>
