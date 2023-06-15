<template>
	<div class="main">
		<div class="row">
			<div class="col-md-12">
				<div class="content-box">
					<div class="content-box-header">
						<div class="box-title">Recibir Entrada</div>
						<div class="box-description">Formulario para recibir entrada de productos</div>
					</div>
					<div class="row">

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Tipo entrada</label>
								<input class="form-control" readonly type="text" v-model="form.entrada_tipo.descripcion">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.entrada_tipo"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Código entrada <small>(Automático)</small></label>
								<input class="form-control" readonly type="text" v-model="form.codigo_entrada">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.codigo_entrada"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Bodega</label>
								<input class="form-control" readonly type="text" v-model="form.entrada_bodega.descripcion">
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.entrada_bodega" v-text="message"></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Proveedor</label>
								<template v-if="form.entrada_proveedor">
								<input class="form-control" readonly type="text" v-model="form.entrada_proveedor.nombre_comercial">
								</template>
								<template v-else>
									<input class="form-control" readonly type="text" v-text="N/A">
								</template>
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.entrada_proveedor" v-text="message"></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Tipo Pago</label>
								<input class="form-control" readonly type="text" v-model="form.entrada_tipo_pago.descripcion">
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.entrada_tipo_pago" v-text="message"></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Fecha entrada</label>
								<input class="form-control" readonly type="text" v-model="form.fecha_entrada">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.fecha_entrada"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<ul class="error-messages">
								<li
										:key="message"
										v-for="message in errorMessages.entrada_productos"
										v-text="message"
								></li>
							</ul>
							<table class="table table-bordered"  >
								<thead>
								<tr>
									<th></th>
									<th >Producto</th>
									<th >Cantidad Solicitada</th>
									<th >Cantidad Recibida</th>
									<th >Diferencia</th>
									<th >Precio Unitario</th>
									<th >Impuesto %</th>
									<th >SubTotal</th>

								</tr>
								</thead>
								<tbody>
								<template v-for="(item, index) in form.entrada_productos">
									<tr>
										<td style="width: 2%">
											<button @click="eliminarLinea(item, index)">
												<i class="fa fa-trash"></i>
											</button>
										</td>
										<td>
											<input class="form-control" disabled v-model="item.descripcion_producto">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`entrada_productos.${index}.id_producto`]"
														v-text="message">
												</li>
											</ul>
										</td>


										<td>
											<input class="form-control" disabled type="number" v-model.number="item.cantidad_solicitada">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`entrada_productos.${index}.cantidad_solicitada`]"
														v-text="message">
												</li>
											</ul>
										</td>

										<td>
											<input class="form-control" type="number" v-model.number="item.cantidad_recibida">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`entrada_productos.${index}.cantidad_recibida`]"
														v-text="message">
												</li>
											</ul>
										</td>

										<td>
											{{item.cantidad_solicitada-item.cantidad_recibida}}
										</td>


										<td>
											<input class="form-control" disabled type="number" v-model.number="item.precio_unitario">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`entrada_productos.${index}.precio_unitario`]"
														v-text="message">
												</li>
											</ul>
										</td>

										<td>
											<input class="form-control" disabled type="number" v-model.number="item.impuesto">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`entrada_productos.${index}.impuesto`]"
														v-text="message">
												</li>
											</ul>
										</td>

										<td>
											<strong>C$ {{sub_total(item)| formatMoney(2)}}</strong>
										</td>


									</tr>
									<tr></tr>
								</template>
								</tbody>
								<tfoot>

								<tr>
									<td colspan="6"></td>
									<td>Subtotal</td>
									<td> <strong>C$ {{total_subt | formatMoney(2)}}</strong></td>
								</tr>
								<tr>
									<td colspan="6"></td>
									<td >Impuesto</td>
									<td> <strong>C$ {{total_impuesto | formatMoney(2)}}</strong></td>
								</tr>
								<tr>
									<td class="item-footer" colspan="2"> Total Unidades</td>
									<td  class="item-footer">
										<strong>{{total_cantidad_solicitada}}</strong>
									</td>
									<td  class="item-footer">
										<strong>{{total_cantidad_recibida}}</strong>
									</td>
                                    <td colspan="2"></td>
									<td>Total</td>
									<td> <strong>C$ {{gran_total | formatMoney(2)}}</strong></td>
								</tr>

								</tfoot>
							</table>
						</div>
					</div>

					<div class="content-box-footer text-right">
						<router-link :to="{ name: 'entradas' }" tag="button">
							<button class="btn btn-default" type="button">Cancelar</button>
						</router-link>
						<button
								:disabled="btnAction != 'Registrar' ? true : false"
								@click="recibir"
								class="btn btn-primary"
								type="button"
						>{{ btnAction }}</button>
					</div>


				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/ecmascript-6">
	import producto from "../../api/productos";
	import bodega from "../../api/bodegas";
	import tipo from "../../api/tipo_entrada";
	import tipo_pago from "../../api/tipos_pagos";
	import proveedor from "../../api/proveedores";
	import entrada from "../../api/entradas";
	//import Datepicker from "vuejs-datepicker";
	import es from 'vuejs-datepicker/dist/locale/translations/es'

	export default {
		data() {
			return {
				es: es,
				format: "dd-MM-yyyy",

				form: {
					codigo_entrada: "",
					fecha_entrada: moment(new Date()).format("YYYY-MM-DD"),
					entrada_tipo: "",
					entrada_proveedor: "",
					entrada_bodega: "",
					entrada_tipo_pago: "",
					entrada_productos: []
				},
				btnAction: "Registrar",
				errorMessages: []
			};
		},
			computed: {

				total_cantidad_solicitada() {
					return this.form.entrada_productos.reduce((carry, item) => {
						return (carry + Number(item.cantidad_solicitada))
					}, 0)
				},
				total_cantidad_recibida() {
					return this.form.entrada_productos.reduce((carry, item) => {
						return (carry + Number(item.cantidad_recibida))
					}, 0)
				},
				total_impuesto() {
					return this.form.entrada_productos.reduce((carry, item) => {
								return(carry + Number(item.total_impuesto.toFixed(2)));
							}
							, 0)
				},
				total_subt() {
					return this.form.entrada_productos.reduce((carry, item) => {
								return(carry + Number(item.subtotal.toFixed(2)));
							}
							, 0)
				},
				gran_total() {
					return this.form.entrada_productos.reduce((carry, item) => {
								return (carry + Number(item.total.toFixed(2)));
							}
							, 0)
				},
			},
			methods: {
				obtenerEntrada() {
					var self = this
					entrada.obtenerEntrada({
						id_entrada: this.$route.params.id_entrada
					}, data => {
						self.form = data
					})
				},
				sub_total(itemX) {
					itemX.subtotal= Number((Number(itemX.precio_unitario)* Number(itemX.cantidad_solicitada)).toFixed(2));
					itemX.total_impuesto = Number((Number(itemX.subtotal)* Number(itemX.impuesto/100)).toFixed(2)) ;
					itemX.total =itemX.subtotal + itemX.total_impuesto ;
					if(!isNaN(itemX.total)){
						return itemX.total;
					}
					else return 0;
				},

				recibir() {
					var self = this;
					self.btnAction = "Registrando, espere....";

					entrada.recibir(
							self.form,
							data => {
								this.$router.push({
									name: "entradas"
								});
							},
							err => {
								self.errorMessages = err;
								self.btnAction = "Registrar";
							}
					);
				}
			},
			mounted() {
				this.obtenerEntrada();
			}
		};
</script>
<style>
	.btn-agregar {
		margin-top:33px;
	}
</style>