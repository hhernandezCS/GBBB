<template>
	<div class="main">
		<div class="content-box">
			<div class="content-box-header">
				<div class="box-title">Administrar Tipos de Notas de Crédito</div>
				<div class="box-description">Listado de tipos de Notas de Crédito</div>
				<div class="box-description"><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal' }">Módulos</router-link>><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal-cuentas-pagar' }"> Módulo cuentas por pagar</router-link>> Tipos Notas de Crédito</div>

			</div>
			<div class="row">
				<div class="col-md-6 sm-text-center">
					<router-link class="btn btn-success" tag="button" :to="{ name: 'registrar-tipo-nota-credito-cp' }">
						<i class="pe-7s-plus"></i> Registrar
					</router-link>
				</div>
				<div @keyup.enter="filter.page = 1;obtener();" class="col-md-6 sm-text-center form-inline justify-content-end">
					<select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
						<option value="descripcion">Tipo Nota</option>
					</select>
					<input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar" type="text">
					<button @click="filter.page = 1;obtener();" class="btn btn-info"><i class="pe-7s-search"></i> Buscar</button>
				</div>
			</div>
			<div class="table-responsive mt-3">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Descripción</th>
                            <th>Cuenta Contable</th>
							<th class="text-center table-number">Estado</th>
							<th class="text-center action">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="tipo_nota_credito in tipos_nota_credito" :key="tipo_nota_credito.id_tipo_nota_credito">
							<td>{{ tipo_nota_credito.descripcion }}</td>
                            <td>{{tipo_nota_credito.tipo_cuenta_contable?tipo_nota_credito.tipo_cuenta_contable.nombre_cuenta_completo:'N/D'}}</td>
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
								<router-link tag="a" :to="{ name: 'actualizar-tipo-nota-credito-cp', params: { id_tipo_nota_credito: tipo_nota_credito.id_tipo_nota_credito } }"><i class="icon-pencil"></i></router-link>
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
		</div>
	</div>
</template>

<script type="text/ecmascript-6">
	import tipos_notas from '../../api/tipos_notas_credito_cp'
	//import Pagination from '../layout/Pagination'

	export default {
		data() {
			return {
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
				var self = this
				tipos_notas.obtener(self.filter, data => {
					self.tipos_nota_credito = data.rows
					self.total = data.total
				}, err => {
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