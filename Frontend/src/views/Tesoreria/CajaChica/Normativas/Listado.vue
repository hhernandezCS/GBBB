<template>
    <b-card>
        <div class="main">
            <div class="content-box">
                <b-row>
                    <div class="col-md-6 sm-text-center">
                        <router-link :to="{ name: 'cajachica-normativas-registrar' }" class="btn btn-success" tag="button">
                            <feather-icon icon="PlusCircleIcon"></feather-icon>
                            Registrar
                        </router-link>
                    </div>
                    <div @keyup.enter="filter.page = 1;obtener();"
                         class="col-md-6 sm-text-center form-inline justify-content-end">
                        <div class="form-group check-label">
                            <b-form-checkbox v-model="filter.search.status" class="custom-control-primary mr-1">
                                Mostrar Todos
                            </b-form-checkbox>
                        </div>
                        <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field" v-model="filter.search.field">
                            <option value="concepto">Concepto</option>
                        </select>
                        <input class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text"
                               v-model="filter.search.value">
                        <b-button variant="info" @click="filter.page = 1;obtener();"
                                  v-b-tooltip.hover.top="'Buscar!'">
                            <feather-icon icon="SearchIcon"></feather-icon>
                        </b-button>
                    </div>
                </b-row>
                <template>
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
                                class="text-center"
                                striped
                                hover
                                bordered
                                :fields="fields"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"
                                sort-icon-left
                                :items="normativas">
                            <!--Custom formated columns-->
                            <template #head(tiempo)="data">
                                <span>{{ 'Regulacion de tiempo' }}</span>
                            </template>
                            <!--Customo rendering data-->
                            <template #cell(estado)="data">
                                <div v-if="data.item.estado" class="text-center">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivado</span>
                                </div>
                            </template>
                            <template #cell(actions)="data">
                                <router-link
                                        :to="{ name: 'cajachica-normativas-actualizar', params: { id_normativa: data.item.id_normativa } }"
                                        tag="a">
                                    <feather-icon icon="EditIcon"></feather-icon>
                                </router-link>
                            </template>
                        </b-table>
                    </div>
                </template>

                <pagination :items="normativas" :limit="filter.limit" :limitOptions="filter.limitOptions"
                            :page="filter.page" :total="total" @changeLimit="changeLimit"
                            @changePage="changePage"></pagination>

                <template v-if="loading">
                    <BlockUI>
                        <b-spinner variant="info" label="loading..."/>
                    </BlockUI>
                </template>
            </div>
        </div>
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
        BSpinner,
        BTable, BFormSelect, BInputGroup
    } from 'bootstrap-vue'
    import normativa from '../../../../api/Tesoreria/normativas';
    import loadingImage from '../../../../assets/images/loader/block50.gif';

    export default {
        components: {
            BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
            BBadge,
            BTab,
            BTabs,
            BSpinner,
            BTable,
            BFormSelect,
            BInputGroup
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
                        field: 'concepto',
                        value: '',
                        status: 0
                    }
                },
                normativas: [],
                total: 0,
                descargando: false,
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                fields: [
                    {key: 'concepto', sortable: true, label:'Concepto'},
                    {key: 'monto_minimo', sortable: true, label:'Monto mínimo'},
                    {key: 'monto_maximo', sortable: true, label:'Monto máximo'},
                    {key: 'tiempo', sortable: true, label:'Regulación de tiempo'},
                    {key: 'estado', sortable: true, label:'Estado'},
                    {key: 'actions', label: 'Opciones'}
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
                normativa.obtener(self.filter, data => {
                    self.normativas = data.rows;
                    self.total = data.total;
                    self.loading = false;
                }, err => {
                    self.loading = false;
                    console.log(err)
                })
            },
            changeLimit(limit) {
                this.filter.page = 1
                this.filter.limit = limit
                this.obtener()
            },
            changePage(page) {
                this.filter.page = page
                this.obtener()
            },
            downloadItem(ext) {
                const self = this;
                if (!this.descargando) {
                    this.descargando = true;
                    self.loading = true;
                    alertify.success("Descargando datos, espere un momento.....", 5);
                    axios.get('Normativas/reporte/' + ext, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})
                            ext === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'Reporte_normativas.' + ext;
                            link.click()
                            this.descargando = false;
                            self.loading = false;
                        }).catch(function (error) {
                        alertify.error("Error Descargando archivo.....", 5);
                        self.descargando = false;
                        self.loading = false;
                    })
                } else {
                    alertify.warning("Espere a que se complete la descarga anterior", 5);
                }
            },
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