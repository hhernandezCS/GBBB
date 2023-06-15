<template>
  <b-card>
    <b-row>
      <div class="col-md-3 sm-text-center">
        <router-link class="btn btn-success" tag="button"
                     :to="{ name: 'nomina-areas-registrar' }">
          <feather-icon icon="PlusCircleIcon"></feather-icon>
          Registrar
        </router-link>
      </div>

      <div @keyup.enter="filter.page = 1;obtenerAreas();"
           class="col-md-9 sm-text-center form-inline justify-content-end">
        <b-form-checkbox v-model="filter.search.status" class="custom-control-primary mr-1">
          Mostrar Todos
        </b-form-checkbox>
        <select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
          <option value="descripcion">Areas</option>
        </select>
        <input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar"
               type="text">
        <b-button variant="info" @click="filter.page = 1;obtenerAreas();"
                  v-b-tooltip.hover.top="'Buscar!'">
          <feather-icon icon="SearchIcon"></feather-icon>
        </b-button>
      </div>

    </b-row>

    <div class="table-responsive mt-3">
      <table class="table table-striped table-bordered">
        <thead>
        <tr>
          <th>Código</th>
          <th>Descripción</th>
          <th>Sucursal</th>
          <th>Dirección</th>
          <th class="text-center table-number">Estado</th>
          <th class="text-center action">Opciones</th>
        </tr>
        </thead>
        <tbody>
        <template  v-for="area in areas">
          <tr :key="area.id_area">
            <td>{{ area.codigo }}</td>
            <td>{{ area.descripcion }}</td>
            <td>{{ area.direccion_area.direccion_sucursal.descripcion}}</td>
            <td>{{ area.direccion_area.descripcion + '('+area.direccion_area.codigo + ')'}}</td>
            <!--<td>{{ area.cuenta_contable_area.nombre_cuenta_completo }}</td>-->
            <td class="text-center">
              <div v-if="area.estado">
                <b-badge variant="success"> Activo</b-badge>
              </div>
              <div v-else>
                <b-badge variant="danger"> Desactivado</b-badge>
              </div>
            </td>
            <td class="text-center">
              <template>
                <router-link v-b-tooltip="'Actualizar Area'"
                             :to="{name: 'nomina-areas-actualizar', params: {id_area: area.id_area}}"
                             tag="a">
                  <feather-icon icon="EditIcon"></feather-icon>
                </router-link>
              </template>
            </td>
          </tr>
        </template>

        <tr v-if="!areas.length">
          <td class="text-center" colspan="6">Sin datos</td>
        </tr>
        </tbody>
      </table>
    </div>
    <b-card-footer>
      <pagination @changePage="changePage" @changeLimit="changeLimit" :items="areas" :total="total"
                  :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>

      <template v-if="loading">
        <BlockUI :url="url"></BlockUI>
      </template>
    </b-card-footer>
  </b-card>


</template>

<script type="text/ecmascript-6">
	import area from '../../../api/Nomina/areas';

/*	import loadingImage from '../../assets/images/block50.gif'*/
/*	import Pagination from '../layout/Pagination'*/
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
    BTable
  } from 'bootstrap-vue';
  import loadingImage from '../../../assets/images/loader/block50.gif'

	export default {
    components: {
      BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
      BBadge,
      BTab,
      BTabs,
      BSpinner,
      BTable
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
		data() {
			return {
				loading:true,
				msg: 'Cargando el contenido espere un momento',
        url: loadingImage,   //It is important to import the loading image then use here
				filter: {
					page: 1,
					limit: 20,
					limitOptions: [5, 10, 15, 20],
					search: {
						field: 'codigo',
						value: '',
						status:0
					}
				},
				areas: [],
				total: 0
			}
		},
		methods: {
			obtenerAreas() {
				 self = this;
				self.loading = true;
				area.obtener(self.filter, data => {
					self.areas = data.rows;
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
				this.obtenerAreas()
			},
			changePage(page) {
				this.filter.page = page
				this.obtenerAreas()
            },

		},
		/*components: {
			'pagination': Pagination
		},*/
		mounted() {
			this.obtenerAreas()
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