<template>
	<b-card>
		<b-row>
			<b-col class="sm-text-center" cols="12" md="6">
				<router-link class="btn btn-success" tag="button" :to="{ name: 'cxc-tipos-notas-credito-registrar' }">
					<feather-icon icon="PlusCircleIcon"></feather-icon> Registrar
				</router-link>
			</b-col>
			<b-col @keyup.enter="filter.page = 1;obtener();" class="sm-text-center form-inline justify-content-end" cols="12" md="6">
				<b-form-select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
					<option value="descripcion">Tipo Nota</option>
				</b-form-select>
				<b-form-input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text"/>
				<b-button @click="filter.page = 1;obtener();" variant="info"><feather-icon icon="SearchIcon"></feather-icon> Buscar</b-button>
			</b-col>
		</b-row>
		<div class="table table-responsive mt-3">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Descripción</th>
						<th>Cuenta Contable</th>
						<th class="text-center table-number">Genera comisión</th>
						<th class="text-center table-number">Estado</th>
						<th class="text-center action">Opciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="tipo_nota_credito in tipos_nota_credito" :key="tipo_nota_credito.id_tipo_nota_credito">
						<td>{{ tipo_nota_credito.descripcion }}</td>
						<td>{{tipo_nota_credito.tipo_cuenta_contable?tipo_nota_credito.tipo_cuenta_contable.nombre_cuenta_completo:'N/D'}}</td>
						<td class="text-center">
							<div v-if="tipo_nota_credito.aplica_comision">
								<span class="badge badge-success">Aplica</span>
							</div>
							<div v-else>
								<span class="badge badge-danger">NO Aplica</span>
							</div>
						</td>
						<td class="text-center">
						 <div v-if="tipo_nota_credito.estado">
						  <span class="badge badge-success">Activo</span>
						 </div>
							 <div v-else>
						  <span class="badge badge-danger">Desactivado</span>
							 </div>
						</td>
						<td class="text-center">
							<!--<template v-if="[1,2,3,4].indexOf(tipo.id_tipo_entrada) < 0">-->
							<router-link tag="a" :to="{ name: 'cxc-tipos-notas-credito-actualizar', params: { id_tipo_nota_credito: tipo_nota_credito.id_tipo_nota_credito } }"><feather-icon icon="EditIcon"></feather-icon></router-link>
							<!--</template>-->
						</td>
					</tr>
					<tr v-if="!tipos_nota_credito.length">
						<td class="text-center" colspan="5">Sin datos</td>
					</tr>
				</tbody>
			</table>
		</div>
		<pagination @changePage="changePage" @changeLimit="changeLimit" :items="tipos_nota_credito" :total="total" :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>
	</b-card>
</template>

<script type="text/ecmascript-6">
	import tipos_notas from '../../../api/CuentasXCobrar/tipos_notas_credito';
	import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
	import {
		BAlert,
		BButton,
		BCard,
		BCardFooter,
		BFormCheckbox,
		BFormCheckboxGroup,
		BFormDatepicker,
		BFormGroup,
		BFormSelect,
		BPaginationNav,
		BRow,
		BCol,
		VBTooltip,
		BBadge,
		BFormInput
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
			BCol,
			BButton,
			BFormCheckboxGroup,
			BFormDatepicker,
			BAlert,
			BFormSelect,
			BBadge,
			BFormInput
		},
		directives: {
			'b-tooltip': VBTooltip,
		},
		data() {
			return {
				loading: true,
				msg: 'Cargando el contenido espere un momento',
				url: loadingImage,
				filter: {
					page: 1,
					limit: 5,
					limitOptions: [5, 10, 15, 20],
					search: {
						field: 'descripcion',
						value: '',
						status:0
					}
				},
				tipos_nota_credito: [],
				total: 0
			}
		},
		methods: {
			obtener() {
				let self = this
				tipos_notas.obtener(self.filter, data => {
					self.tipos_nota_credito = data.rows
					self.total = data.total
				}, err => {
					this.$toast({
						component : ToastificationContent,
						props : {
							title : 'Notificación',
							icon : 'InfoIcon',
							text : 'Ha ocurrido un error al cargar los datos',
							variant : 'warning',
						}
					}, {
						position: 'bottom-right'
					});
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