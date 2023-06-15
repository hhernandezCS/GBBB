<template>
    <b-card>
        <b-row>
            <div class="col-lg-4 col-md-12 sm-text-center">
                <router-link :to="{ name: 'inventario-productos-registrar' }" class="btn btn-success" tag="button">
                    <i class="pe-7s-plus"></i> Registrar
                </router-link>
            </div>
            <div @keyup.enter="filter.page = 1;obtenerProductos();"
                 class="col-lg-8 col-md-12 sm-text-center form-inline justify-content-end">
                <b-button class="mx-1 mb-sm-2 mb-lg-0 mb-md-0"
                          v-b-modal.filterModal
                          v-b-tooltip.hover.top="'Filtrar registros!'"
                          variant="info"
                >
                    <feather-icon icon="FilterIcon"></feather-icon>
                </b-button>
                <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field" v-model="filter.search.field">
                    <option value="descripcion">Descripcion</option>
                    <option value="codigo_barra">Código Barras</option>
                    <option value="codigo_sistema">Código de producto</option>
                </select>
                <input class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text"
                       v-model="filter.search.value">
                <p class="mb-1 mr-sm-1 mb-sm-0">
                    <b-button @click="filter.page = 1;obtenerProductos();" v-b-tooltip.hover.top="'Buscar!'"
                              variant="info">
                        <feather-icon icon="SearchIcon"></feather-icon>
                    </b-button>
                    <!--<a  :disabled="descargando" @click.prevent="downloadItem('pdf')" style="color: #ffffff;padding-left: 2px" ><b-button v-b-tooltip.hover.top="'PDF!'" :disabled="descargando" variant="danger"><feather-icon icon="ArrowDownCircleIcon"></feather-icon></b-button></a>
                    <a  :disabled="descargando" @click.prevent="downloadItem('xls')" style="color: #ffffff;padding-left: 2px" ><b-button v-b-tooltip.hover.top="'XLS!'" :disabled="descargando" variant="success"><feather-icon icon="ArrowDownCircleIcon"></feather-icon></b-button></a>-->
                </p>
            </div>
        </b-row>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Código de barra</th>
                    <th>Grupo</th>
                    <th>Descripción</th>
                    <th>Unidad de medida</th>
                    <th>Marca</th>
                    <th class="text-center table-number">Existencia</th>
                    <th class="text-center table-number">Estado</th>
                    <th class="text-center action">Opciones</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(producto,key) in productos">
                    <tr>
                        <td>{{ producto.id_producto}}</td>
                        <td>{{ producto.codigo_barra ?  producto.codigo_barra : 'N/D'}}</td>
                        <td>{{ producto.grupo.descripcion}}</td>
                        <td>{{ producto.descripcion }}</td>
                        <td>{{ producto.unidad_medida.descripcion+' ('+producto.unidad_medida.siglas+')' }}</td>
                        <td>{{producto.marca ? producto.marca.descripcion : 'N/A'}}</td>
                          <td :style="{ color: producto.productos_en_bodega ? Number(producto.productos_en_bodega.cantidad) <= producto.existencia_min ? 'red'  : 'green' :'red'}">
                            {{ producto.productos_en_bodega ? Number(producto.productos_en_bodega.cantidad) <= producto.existencia_min ? 'Existencia Baja' : 'En stock' : 'Sin Existencias' }}
                          </td>
                      <!--<td>{{ producto.tipo_producto ===1 ? 'Producto' : producto.tipo_producto ===2? 'Servicio':producto.tipo_producto ===4? 'Bienes':'Otro'}}</td>-->
                        <td class="text-center">
                            <div v-if="producto.estado === 1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Desactivado</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <router-link :to="{ name: 'inventario-productos-actualizar', params: { id_producto: producto.id_producto } }"
                                         tag="a">
                                <feather-icon icon="EditIcon"></feather-icon>
                            </router-link>
                        </td>
                    </tr>
                </template>
                <tr v-if="!productos.length">
                    <td class="text-center" colspan="8">Sin datos</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!--Modal para filtrar registros scrollable-->
        <b-modal id="filterModal" no-close-on-backdrop size="lg" title="Filtrar">
            <b-container fluid>
                <!--Filtrar por estados-->
                <b-row>
                    <b-col>
                        <!--Selector de estados-->
                        <b-form-group
                                id="fieldset-estado"
                                label="Estados"
                                label-for="input-estado"
                                label-size="default"
                        >
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="filter.estados"
                                    @input="mapEstadosSeleccionados"
                                    label="text"
                                    multiple
                                    placeholder="Seleccione un estado"
                                    v-model="filter.estados_seleccionados"
                            >
                                <template slot="no-options">
                                    No se han encontrado registros
                                </template>
                            </v-select>
                        </b-form-group>
                    </b-col>

                </b-row>
                <!--Filtrar por fecha de creacion-->
                <b-row>
                    <b-col md="6">
                        <!--Selector de fechas para filtrar-->
                        <b-form-group
                                id="fieldset-fdesde"
                                label="Fecha de creacion"
                                label-for="input-fdesde"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fdesde"
                                               placeholder="Escoge una fecha inicial" reset-button today-button
                                               v-model="filter.f_desde_creacion"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                                id="fieldset-fhasta"
                                label="-"
                                label-for="input-fhasta"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fhasta"
                                               placeholder="Escoge una fecha final" reset-button today-button
                                               v-model="filter.f_hasta_creacion"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                </b-row>
                <!--Filtrar por fecha de modificacion-->
                <b-row>
                    <b-col md="6">
                        <!--Selector de fechas para filtrar-->
                        <b-form-group
                                id="fieldset-fdesde_modificacion"
                                label="Fecha de modificacion"
                                label-for="input-fdesde_modificacion"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fdesde_modificacion"
                                               placeholder="Escoge una fecha inicial" reset-button today-button
                                               v-model="filter.f_desde_modificacion"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group
                                id="fieldset-fhasta_modificacion"
                                label="-"
                                label-for="input-fhasta_modificacion"
                                label-size="default"
                        >
                            <b-form-datepicker class="mb-2" id="input-fhasta_modificacion"
                                               placeholder="Escoge una fecha final" reset-button today-button
                                               v-model="filter.f_hasta_modificacion"></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                </b-row>
                <!--Filtrar por usuario responsable-->
                <b-row>
                    <b-col>
                        <!--Selector de usuario responsable-->
                        <b-form-group
                                id="fieldset-u_responsable"
                                label="Creado por"
                                label-for="input-u_responsable"
                                label-size="default"
                        >
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="usuarios"
                                    @search="onSearchUser"
                                    label="name"
                                    placeholder="Selecciona un responsable"
                                    v-model="filter.u_responsable"
                            >
                                <template slot="no-options">
                                    Escriba para buscar un usuario
                                </template>
                            </v-select>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-container>

            <template #modal-footer="{ok,cancel}">
                <b-button @click="cancel()" size="sm" variant="danger"> Cancelar</b-button>
                <b-button @click="obtenerProductos" ref="btnHide" size="sm" variant="info"> Aplicar</b-button>
            </template>
        </b-modal>

        <b-card-footer>
            <pagination :items="productos" :limit="filter.limit" :limitOptions="filter.limitOptions" :page="filter.page"
                        :total="total" @changeLimit="changeLimit" @changePage="changePage"></pagination>
        </b-card-footer>


        <template v-if="loading">
            <BlockUI :url="url"></BlockUI>
        </template>
    </b-card>
</template>
<script type="text/ecmascript-6">
  import loadingImage from '../../../assets/images/loader/block50.gif'
  //import Pagination from '../layout/Pagination'
  import {
    BButton,
    BCard,
    BCardFooter,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormGroup,
    BPaginationNav,
    BRow,
    VBTooltip,
    BContainer,
      BCol,
      BFormDatepicker
  } from 'bootstrap-vue'
  //import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import vSelect from 'vue-select'
  import producto from "../../../api/Inventario/productos";
  import axios from "axios";

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
      BContainer,
      BFormDatepicker,
      BCol
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: true,
        msg: 'Cargando el contenido espere un momento',
        url: loadingImage,   //It is important to import the loading image then use here
        filter: {
          page: 1,
          limit: 5,
          limitOptions: [5, 10, 15, 20],
          search: {
            field: 'descripcion',
            value: '',
            status: false
          },
          // campos para filtrar desde modal
          estados: [
            {value: 99, text: 'Todos los registros'},
            {value: 0, text: 'Desactivados'},
            {value: 1, text: 'Activos'},
          ],
          estados_seleccionados: [
            {value: 1, text: 'Activos'}
          ],
          map_estados_seleccionados: '1',
          //campos para filtrar por fecha de creacion
          f_desde_creacion: '',
          f_hasta_creacion: '',
          f_desde_modificacion: '',
          f_hasta_modificacion: '',
          u_registra: '',
          u_modifica: ''
        },
        productos: [],
        usuarios: [],

        total: 0,
        descargando: false,
      }
    },
    methods: {
      downloadItem(extension) {

        var self = this;
        console.log(self.descargando);
        if (!self.descargando) {
          let url = 'productos/ps/reporte/' + extension;
          self.descargando = true;
          self.loading = true;
          alertify.success("Descargando datos, espere un momento.....", 5);
          axios.get(url, {responseType: 'blob'})
              .then(({data}) => {
                let blob = new Blob([data], {type: 'application/pdf'});

                extension === 'xls' ? blob = new Blob([data], {type: 'application/octet-stream'}) : blob = new Blob([data], {type: 'application/pdf'});

                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob)
                link.download = 'ReporteProductosServicios.' + extension;
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
      obtenerProductos() {
        const self = this;
        self.loading = true;
        //Cerrar modal luego de aplicar la busqueda
        this.$root.$emit('bv::hide::modal', 'filterModal', '#btnHide');
        producto.obtenerProductos(self.filter, data => {
          self.productos = data.rows;
          self.total = data.total;
          self.loading = false;
        }, err => {
          self.loading = false;

        })
      },


      /**
       * Metodo para recorrer los objetos de los estados seleccionados y extraer los "id's"
       * */
      mapEstadosSeleccionados() {
        let self = this;
        if (self.filter.estados_seleccionados) {
          return self.filter.map_estados_seleccionados = self.filter.estados_seleccionados.map((estado) => [estado.value]).join(",");
        }
        return self.filter.map_estados_seleccionados = '';
      },
      onSearchUser(searchU, loading) {
        if (searchU.length) {
          loading(true);
          this.searchU(loading, searchU, this)
        }
      },
      // Live search de usuarios
      searchU: _.debounce((loading, searchU, vm) => {
        const self = this;
        axios.get(`admon/usuarios/buscar?q=${escape(searchU)}`).then(res => {
          vm.usuarios = res.data.result;
          loading(false)
        })
      }, 10),
      changeLimit(limit) {
        this.filter.page = 1;
        this.filter.limit = limit;
        this.obtenerProductos()
      },
      changePage(page) {
        this.filter.page = page;
        this.obtenerProductos()
      },
    },
    mounted() {
      this.obtenerProductos();
      this.mapEstadosSeleccionados();
    }
  }
</script>

<style lang="scss" scoped>
    @import '../../../@core/scss/vue/libs/vue-select';
    @import '../../../@core/scss/vue/libs/vue-sweetalert';

    .search-field {
        min-width: 125px;
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
