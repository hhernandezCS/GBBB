<template>
		<b-card>
			<b-row>
				<div @keyup.enter="filter.page = 1;obtener();" class="col-md-12 sm-text-center form-inline justify-content-end">
					<b-button class="mx-1"
							  v-b-tooltip.hover.top="'Filtrar registros!'"
							  variant="info"
							  v-b-modal.filterModal
					>
						<feather-icon icon="FilterIcon"></feather-icon>
					</b-button>
					<b-form-select v-model="filter.search.field" class="mb-1 mr-sm-1 mb-sm-0 search-field">
						<option value="descripcion_movimiento">Descripción</option>
						<option value="no_documento_origen">No.Documento</option>
					</b-form-select>
					<input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text">
					<b-button @click="filter.page = 1;obtener();" class="btn btn-info"><feather-icon icon="SearchIcon"></feather-icon> Buscar</b-button>
				</div>
			</b-row>
<!--			<b-row>
				<b-col cols="12" md="6" class="mt-2">
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
									<option value="">&#45;&#45; ninguno &#45;&#45;</option>
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
				</b-col>
			</b-row>-->
			<div class="table-responsive mt-3">
				<b-table class="text-center table table-striped" striped hover bordered	:fields="fields" :sort-by.sync="sortBy"
						 :sort-desc.sync="sortDesc" :sort-direction="sortDirection" sort-icon-left :items="cuentas_por_cobrar" show-empty responsive="sm">
					<!-- Botón para mostrar los detalles de las cuentas por cobrar -->
					<template #cell(detalles)="data">
						<template v-if="data.detailsShowing">
							<b-button size="sm" variant="secondary" @click="data.toggleDetails" class="mt-1 mb-1">
								<feather-icon icon="MinusCircleIcon"></feather-icon>
							</b-button>
						</template>
						<template v-else>
							<b-button size="sm" variant="info" @click="data.toggleDetails" class="mt-1 mb-1">
								<feather-icon icon="PlusCircleIcon"></feather-icon>
							</b-button>
						</template>
					</template>
					<template #row-details="data">
						<b-card>
							<table class="table table-striped table-bordered">
								<thead>
								<tr>
									<th>Fecha pago</th>
									<th>Descripción</th>
									<th>Monto C$</th>
									<th>Monto $</th>
								</tr>
								</thead>
								<tbody>
								<tr v-for="recibosDetalle in data.item.cuentas_detalles" :key="recibosDetalle.id_recibo_detalle">
									<td>{{ formatDate(recibosDetalle.fecha_pago) }}</td>
									<td>{{ recibosDetalle.descripcion_pago }}</td>
									<td>{{ recibosDetalle.monto | formatMoney(2)}}</td>
									<td>{{ recibosDetalle.monto_me | formatMoney(2)}}</td>
								</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</b-card>
					</template>
					<template #cell(id_tipo_documento)="data">
						<div class="text-center mr-1 ml-1">
							{{ data.item.id_tipo_documento === 1? 'Factura Crédito':data.item.id_tipo_documento === 2?'Recibo':data.item.id_tipo_documento === 3?'Nota de Crédito':data.item.id_tipo_documento === 4?'Nota de Débito':'Otro' }}
						</div>
					</template>
					<template #cell(fecha_movimiento)="data">
						<div class="text-center mr-1 ml-1">
							{{ formatDate(data.item.fecha_movimiento) }}
						</div>
					</template>
					<template #cell(monto_movimiento_me)="data">
						<div class="text-center">
							<template v-if="currency_id === 1">
								C$ {{ data.item.monto_movimiento | formatMoney(2) }}
							</template>
							<template v-else>
								$ {{ data.item.monto_movimiento_me | formatMoney(2) }}
							</template>
						</div>
					</template>
					<template #cell(saldo_actual_me)="data">
						<div class="text-center">
							<template v-if="currency_id === 1">
								C$ {{ data.item.saldo_actual | formatMoney(2) }}
							</template>
							<template v-else>
								$ {{ data.item.saldo_actual_me | formatMoney(2) }}
							</template>
						</div>
					</template>
					<template #cell(fecha_vencimiento)="data">
						<div class="text-center">
							{{ formatDate(data.item.fecha_vencimiento) }}
						</div>
					</template>
					<template #cell(estado)="data">
						<div v-if="data.item.estado" class="text-center">
							<span class="badge badge-success">Abierto</span>
						</div>
						<div v-else>
							<span class="badge badge-danger">Cerrado</span>
						</div>
					</template>
					<template #empty="">
						<h6>{{ "No se encontraron datos" }}</h6>
					</template>
					<template #emptyfiltered="scope">
						<h4>{{ scope.emptyFilteredText }}</h4>
					</template>
				</b-table>
			</div>

			<!-- Filtro múltiple que incluye mostrar otras cuentas por cobrar -->
			<b-modal id="filterModal" title="Filtrar"  size="lg" no-close-on-backdrop>
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
										label="text"
										multiple
										placeholder="Seleccione un estado"
										v-model="filter.estados_seleccionados"
										@input="mapEstadosSeleccionados"
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
								<b-form-datepicker id="input-fdesde" v-model="filter.f_desde" placeholder="Escoge una fecha inicial" today-button reset-button class="mb-2"></b-form-datepicker>
							</b-form-group>
						</b-col>
						<b-col md="6">
							<b-form-group
									id="fieldset-fhasta"
									label="-"
									label-for="input-fhasta"
									label-size="default"
							>
								<b-form-datepicker id="input-fhasta" v-model="filter.f_hasta" placeholder="Escoge una fecha final" today-button reset-button class="mb-2"></b-form-datepicker>
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
								<b-form-datepicker id="input-fdesde_modificacion" v-model="filter.f_desde_modificacion" placeholder="Escoge una fecha inicial" today-button reset-button class="mb-2"></b-form-datepicker>
							</b-form-group>
						</b-col>
						<b-col md="6">
							<b-form-group
									id="fieldset-fhasta_modificacion"
									label="-"
									label-for="input-fhasta_modificacion"
									label-size="default"
							>
								<b-form-datepicker id="input-fhasta_modificacion" v-model="filter.f_hasta_modificacion" placeholder="Escoge una fecha final" today-button reset-button class="mb-2"></b-form-datepicker>
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
					<b-button size="sm" variant="danger" @click="cancel()"> Cancelar</b-button>
					<b-button size="sm" variant="info" @click="obtener" ref="btnHide"> Aplicar</b-button>
				</template>
			</b-modal>

			<b-card-footer>
				<pagination @changePage="changePage" @changeLimit="changeLimit" :items="cuentas_por_cobrar" :total="total" :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>
			</b-card-footer>

			<template v-if="loading">
				<BlockUI :url="url"></BlockUI>
			</template>
		</b-card>
</template>

<script type="text/ecmascript-6">
	import cuentas_cobrar from '../../../api/CuentasXCobrar/cuentas_por_cobrar'
	import es from 'vuejs-datepicker/dist/locale/translations/es'
	import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
	import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
	import {
		BPaginationNav,
		BFormCheckbox,
		BFormGroup,
		BCard,
		BCardFooter,
		VBTooltip,
		BRow,
		BButton,
		BFormCheckboxGroup,
		BFormDatepicker,
		BAlert,
		BFormSelect,
		BTable,
	} from 'bootstrap-vue'
	import loadingImage from '../../../assets/images/loader/block50.gif'
	import vSelect from 'vue-select'

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
			BTable
		},
		directives: {
			'b-tooltip': VBTooltip,
		},
		data() {
			return {
				loading:true,
				msg: 'Cargando el contenido espere un momento',
				url : loadingImage,   //It is important to import the loading image then use here
				filter: {
					page: 1,
					limit: 5,
					type:'cliente',
					limitOptions: [5, 10, 15, 20],
					search: {
						field: 'descripcion_movimiento',
						value: ''
					}
				},
				cuentas_por_cobrar: [],
				total: 0,
				currency_id:'',
				sortBy: '',
				sortDesc: false,
				sortDirection: 'asc',
				fields: [
					{key: 'detalles', sortable:false},
					{key: 'id_tipo_documento', sortable: true, label: 'Tipo Documento'},
					{key: 'no_documento_origen', sortable: true, label: 'No. Documento'},
					{key: 'fecha_movimiento', sortable: true, label: 'Fecha Emisión'},
					{key: 'descripcion_movimiento', sortable: true, label: 'Descripción'},
					{key: 'cuenta_cliente.nombre_comercial', sortable: true, label: 'Cliente'},
					{key: 'monto_movimiento_me', sortable: true, label: 'Monto'},
					{key: 'saldo_actual_me', sortable: true, label: 'Saldo Actual'},
					{key: 'fecha_vencimiento', sortable: true, label: 'Fecha Vencimiento'},
					{key: 'estado', sortable: true, label: 'Estado'}
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
			formatDate(date) {
				return moment(date).format('YYYY-MM-DD')
			},
			mostrarProductos(key) {
				if (this.cuentas_por_cobrar[key].mostrar) {
					this.cuentas_por_cobrar[key].mostrar = 0;
				} else {
					this.cuentas_por_cobrar[key].mostrar = 1;
				}
			},
			obtener() {
				var self = this;
				self.loading = true;
				cuentas_cobrar.obtener(self.filter, data => {
					data.rows.forEach((cuentas_por_cobrar, key) => {
						data.rows[key].mostrar = 0;
					});
					self.cuentas_por_cobrar = data.rows;
					self.total = data.total;
					self.currency_id = Number(data.currency_id);
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

<style lang="scss" >
	@import 'src/@core/scss/vue/libs/vue-select';
	.search-field {
		min-width: 120px;
	}

	.table {
		white-space: nowrap;
		overflow-x: auto;
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
