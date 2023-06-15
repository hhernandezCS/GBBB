<template>
	<b-card>
			<b-row>
				<div class="col-lg-4 col-md-3 col-sm-12 sm-text-center mb-2 mb-lg-0 mb-md-0">
					<router-link class="btn btn-success" tag="button" :to="{ name: 'registrar-clasificacion-compras' }">
						<feather-icon icon="PlusCircleIcon"></feather-icon> Registrar
					</router-link>
				</div>
				<div class="col-lg-8 col-md-9 col-sm-12 sm-text-center form-inline justify-content-end">
					<b-form-select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0  search-field">
						<option value="descripcion">Descripcion</option>
					</b-form-select>
					<b-form-input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text"/>
					<b-button @click="filter.page = 1;obtener();" variant="info"><feather-icon icon="SearchIcon"></feather-icon> Buscar</b-button>
				</div>
			</b-row>
			<div class="table-responsive mt-3">
				<b-table striped hover :fields="fields" :items="clasificaciones" responsive="sm" :sort-by-sync="sortBy" :sort-desc-sync="sortDesc" sort-icon-left>
					<!--Scope a campo de cuentas contables para cargar informacion de el objeto de relacion-->
					<template #cell(cuenta_contable)="data">
						{{data.item.clasificacion_compra_cuenta_contable?data.item.clasificacion_compra_cuenta_contable.nombre_cuenta_completo:'N/D'}}
					</template>
					<!--Scope a campo estado para renderizar etiqueta-->
					<template #cell(estado)="data">
						<div v-if="data.item.activo">
							<b-badge variant="success">Activo</b-badge>
						</div>
						<div v-else>
							<b-badge variant="danger">Inactivo</b-badge>
						</div>
					</template>
					<!--Scope a campo opciones para renderizar icono-->
					<template #cell(opciones)="data">
						<router-link tag="a" :to="{ name: 'actualizar-clasificacion-compras', params: { id_clasificacion_servicio: data.item.id_clasificacion_servicio } }" v-b-tooltip.hover.top="'Editar registro.'">
							<feather-icon icon="EditIcon"></feather-icon>
						</router-link>
					</template>
				</b-table>

			</div>

		<b-card-footer>
			<pagination @changePage="changePage" @changeLimit="changeLimit" :items="clasificaciones" :total="total" :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>
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
	import clasificacion from '../../../api/Compras/clasificacion_compra'
	import {
		BAlert,
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
		BBadge,
	} from 'bootstrap-vue'
	import vSelect from 'vue-select'
	import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
	import {ValidationObserver, ValidationProvider} from 'vee-validate';
	import {mimes, required} from '@validations';

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
				loading:true,
				filter: {
					page: 1,
					limit: 5,
					limitOptions: [5, 10, 15, 20],
					search: {
						field: 'descripcion',
						value: '',
						status:0,
					}
				},
				clasificaciones: [],
				fields:[
					{key:'descripcion', sortable: true},
					{key: 'cuenta_contable', sortable: false} ,
					{key: 'estado', sortable: false},
					{key: 'opciones', sortable: false}
					],
				sortBy: 'descripcion',
				sortDesc: false,
				total: 0
			}
		},
		methods: {
			obtener() {
				var self = this
				clasificacion.obtenerClasificaciones(self.filter, data => {
					//console.log(data)
					self.clasificaciones = data.rows
					self.total = data.total
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
