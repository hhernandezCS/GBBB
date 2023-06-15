<template>
	<div class="main">
		<div class="content-box">
			<div class="content-box-header">
				<div class="box-title">Administrar otras cuentas por pagar</div>
				<div class="box-description">Listado de otras cuentas por pagar</div>
				<div class="box-description"><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal' }">Módulos</router-link>><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal-cuentas-pagar' }"> Módulo cuentas por pagar</router-link>> Otras cuentas por pagar</div>

			</div>
			<div class="row">
				<div class="col-md-6 sm-text-center">
					<router-link class="btn btn-success" tag="button" :to="{ name: 'registrar-auxiliares-cxp' }">
						<i class="pe-7s-plus"></i> Registrar
					</router-link>
				</div>
				<div @keyup.enter="filter.page = 1;obtener();" class="col-md-6 sm-text-center form-inline justify-content-end">
					<select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
						<option value="descripcion">Descripcion</option>
					</select>
					<input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text">
					<button @click="filter.page = 1;obtener();" class="btn btn-info"><i class="pe-7s-search"></i> Buscar</button>
				</div>
			</div>
			<div class="table-responsive mt-3">
				<table class="table table-striped table-bordered">
					<thead>
					<tr>
						<th class="text-center table-number">Abreviatura</th>
						<th class="text-center table-number">Codigo</th>
						<th>Descripción</th>
						<th class="text-center table-number">Estado</th>
						<th class="text-center action">Opciones</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="auxiliar_cxp in auxiliares_cxp" :key="auxiliar_cxp.id_cat_auxiliar_cxc">
						<td>{{ auxiliar_cxp.tipo_auxiliar }}</td>
						<td>{{auxiliar_cxp.codigo}}</td>
						<td>{{auxiliar_cxp.descripcion}}</td>
						<td class="text-center">
							<div v-if="auxiliar_cxp.estado">
								<span class="badge badge-success">Activo</span>
							</div>
							<div v-else>
								<span class="badge badge-danger">Desactivado</span>
							</div>
						</td>
						<td class="text-center">
							<!--<template v-if="[1,2,3,4].indexOf(tipo.id_tipo_entrada) < 0">-->
							<router-link tag="a" :to="{ name: 'actualizar-auxiliares-cxp', params: { id_cat_auxiliar_cxc: auxiliar_cxp.id_cat_auxiliar_cxc } }"><i class="icon-pencil"></i></router-link>
							<!--</template>-->
						</td>
					</tr>
					<tr v-if="!auxiliares_cxp.length">
						<td class="text-center" colspan="5">Sin datos</td>
					</tr>
					</tbody>
				</table>
			</div>
			<pagination @changePage="changePage" @changeLimit="changeLimit" :items="auxiliares_cxp" :total="total" :page="filter.page" :limitOptions="filter.limitOptions" :limit="filter.limit"></pagination>

			<template v-if="loading">
				<BlockUI :message="msg" :url="url"></BlockUI>
			</template>
		</div>
	</div>
</template>

<script type="text/ecmascript-6">
	import auxiliares from '../../api/auxiliares_cxp'
	import loadingImage from "../../assets/images/block50.gif";
	//import Pagination from '../layout/Pagination'

	export default {
		data() {

			return {
				loading:true,
				msg: 'Cargando los datos, espere un momento',
				url : '/public'+loadingImage,   //It is important to import the loading image then use here
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
				auxiliares_cxp: [],
				total: 0
			}
		},
		methods: {
			obtener() {
				var self = this;
				self.loading = true;
				auxiliares.obtener(self.filter, data => {
					self.auxiliares_cxp = data.rows
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
