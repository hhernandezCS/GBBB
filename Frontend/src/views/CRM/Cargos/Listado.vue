<template>
    <b-card>
        <b-row>
            <div class="col-md-3 sm-text-center">
                <router-link class="btn btn-success" tag="button"
                             :to="{ name: 'crm-cargos-registrar' }">
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
                    <option value="descripcion">Descripción</option>
                </select>
                <input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar"
                       type="text">
                <b-button variant="info" @click="filter.page = 1;obtener();"
                          v-b-tooltip.hover.top="'Buscar!'">
                    <feather-icon icon="SearchIcon"></feather-icon>
                </b-button>
            </div>


        </b-row>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>

                    <th>Descripción</th>
                    <th class="text-center table-number">Estado</th>
                    <th class="text-center table-number">Opciones</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="cargo in cargos">
                    <tr :key="cargo.id_cargo">
                        <td>{{ cargo.descripcion }}</td>
                        <td class="text-center">
                            <div v-if="cargo.estado===1">
                                <b-badge variant="success"> Activo</b-badge>
                            </div>
                            <div v-else>
                                <b-badge variant="danger"> Desactivado</b-badge>
                            </div>
                        </td>
                        <td class="text-center">
                            <template>
                                <router-link v-b-tooltip="'Actualizar cargo de negocio'"
                                             :to="{name: 'crm-cargos-actualizar', params: {id_cargo: cargo.id_cargo}}"
                                             tag="a">
                                    <feather-icon icon="EditIcon"></feather-icon>
                                </router-link>
                            </template>
                        </td>

                    </tr>

                </template>
                <tr v-if="!cargos.length">
                    <td class="text-center" colspan="7">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>
        <b-card-footer>
            <pagination @changePage="changePage" @changeLimit="changeLimit" :items="cargos" :total="total"
                        :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>

            <template v-if="loading">
                <BlockUI :url="url"></BlockUI>
            </template>
        </b-card-footer>
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
    } from 'bootstrap-vue'
    import cargo from '../../../api/CRM/Cargos'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import es from 'vuejs-datepicker/dist/locale/translations/es'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";

    export default {
        components: {
            BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
            BBadge,
            BTab,
            BTabs
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
                    limit: 20,
                    limitOptions: [5, 10, 15, 20],
                    search: {
                        field: 'descripcion',
                        value: '',
                        status: 0
                    }
                },
                cargos: [],
                total: 0,
                descargando : false
            }
        },
        methods: {

            obtener() {
                const self = this;
                self.loading = true;
                cargo.obtener(
                    self.filter,
                    data => {
                        self.cargos = data.rows;
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
                        },{
                            position:'bottom-right'
                        })
                    }
                )
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
