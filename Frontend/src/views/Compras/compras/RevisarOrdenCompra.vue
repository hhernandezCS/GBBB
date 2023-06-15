<template>
	<div class="main">
		<div class="row">
			<div class="col-md-12">
				<div class="content-box">
					<div class="content-box-header">
						<div class="box-title">Revisión de Orden de Compra</div>
						<div class="box-description">Formulario para revisión de Orden de compra</div>
					</div>

					<div class="form-wizard-nav">
						<template v-if="form.estado===0">
							<div class="step complete">Rechazado</div>
							<div class="step">Borrador</div>
							<div class="step">Registrado</div>
							<div class="step">Ordenado</div>
							<div class="step">Facturado</div>
							<div class="step">Enviado</div>
							<div class="step">Recibido</div>
						</template>

						<template v-if="form.estado===99">
							<div class="step complete">Borrador</div>
							<div class="step">Registrado</div>
							<div class="step">Ordenado</div>
							<div class="step">Facturado</div>
							<div class="step">Enviado</div>
							<div class="step">Recibido</div>
						</template>

						<template v-if="form.estado===1">
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
							<div class="step">Ordenado</div>
							<div class="step">Facturado</div>
							<div class="step">Enviado</div>
							<div class="step">Recibido</div>
						</template>

						<template v-if="form.estado===2">
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
							<div class="step">Facturado</div>
							<div class="step">Enviado</div>
							<div class="step">Recibido</div>
						</template>

						<template v-if="form.estado===3">
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Facturado</div>
							<div class="step">Enviado</div>
							<div class="step">Recibido</div>
						</template>

						<template v-if="form.estado===4">
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Facturado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Enviado</div>
							<div class="step">Recibido</div>
						</template>

						<template v-if="form.estado===5">
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Facturado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Enviado</div>
							<div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Recibido</div>
						</template>

					</div>

					<div class="row">

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Proveedor</label>
								<v-select disabled
										label="nombre_comercial"
										v-model="form.orden_compra_proveedor"
										:options="proveedores"

								></v-select>
								<!--v-on:input="cargar_detalles_proveedor()"-->
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.orden_compra_proveedor" v-text="message"></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>RUC Proveedor</label>
								<template v-if="form.orden_compra_proveedor">
									<input class="form-control" type="text"  disabled v-model="form.orden_compra_proveedor.numero_ruc">
								</template>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Atención:</label>
								<input disabled class="form-control" type="text" v-model="form.atencion">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.atencion"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Email Proveedor:</label>
								<input disabled class="form-control" type="text" v-model="form.email_proveedor">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.email_proveedor"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>


						<div class="col-sm-4">
							<div class="form-group">
								<label for>Télefono Proveedor:</label>
								<input disabled class="form-control" type="text" v-model="form.telefono_proveedor">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.telefono_proveedor"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Referencia:</label>
								<input disabled class="form-control" type="text" v-model="form.referencia_solicitud">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.referencia_solicitud"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Area / Departamento:</label>
								<input disabled class="form-control" type="text" v-model="form.area_proveedor">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.area_proveedor"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>



						<div class="col-sm-4">
							<div class="form-group">
								<label for>Solicitud de Compra:</label>
								<input class="form-control" type="text" disabled v-model="form.id_solicitud_compra">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.id_solicitud_compra"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Fecha Orden Compra</label>
								<datepicker :disabled="true" :format="format" :language="es" :value="new Date()" @selected="onDateSelect"></datepicker>
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.f_orden_compra"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>No. Factura</label>
									<input :disabled="form.estado !== 2" class="form-control" type="text" v-model="form.numero_factura">
									<ul class="error-messages">
										<li
												:key="message"
												v-for="message in errorMessages.numero_factura"
												v-text="message"
										></li>
									</ul>
							</div>
						</div>


						<div class="col-sm-4">
							<div class="form-group">
								<label for>Bodega</label>
								<v-select
										:disabled="true"
										label="descripcion"
										v-model="form.orden_compra_bodega"
										:options="bodegas"
								></v-select>
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.orden_compra_bodega" v-text="message"></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for>Moneda</label>
								<v-select
										:disabled="true"
										label="descripcion"
										v-model="form.orden_compra_moneda"
										:options="monedas"
								></v-select>
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.orden_compra_moneda" v-text="message"></li>
								</ul>
							</div>
						</div>


					</div>

					<div class="alert alert-success">
						<span><strong>DETALLES CONSOLIDADOR</strong></span>
					</div>

					<div class="row">

						<div class="col-sm-12">
							<div class="form-group">
								<label for>Dirección</label>
								<input disabled class="form-control" type="text" v-model="form.direccion">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.direccion"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
								<label > Condición Pago:</label>
								<select  @change="cambiarTipoPago"  :disabled="form.estado !== 2" class="form-control" v-model.number="form.id_condicion_pago">
									<option value="1">Contado</option>
									<option value="2">Crédito</option>
									<option value="3">Consignación</option>
								</select>
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.id_condicion_pago"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
								<label for=""> Días Crédito</label>
								<select :disabled="form.estado !== 2"class="form-control" v-model.number="form.plazo_credito">
									<option :disabled="form.id_condicion_pago!==1" value=0>Contado</option>
									<option :disabled="form.id_condicion_pago===1" value=8>Trámite de Cheque (8 días)</option>
									<option :disabled="form.id_condicion_pago===1" value=15>Crédito (15 días)</option>
									<option :disabled="form.id_condicion_pago===1" value=30>Crédito (30 días)</option>
									<option :disabled="form.id_condicion_pago===1" value=45>Crédito (45 días)</option>
								</select>

								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.plazo_credito" v-text="message"></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
								<label for=""> Medio Pago:</label>
								<select disabled class="form-control" v-model.number="form.id_medio_pago">
									<option value="1">Crédito</option>
									<option value="2">Transferencia</option>
									<option value="3">Cheque</option>
									<option value="4">Minuta Depósito</option>
									<option value="5">Efectivo</option>
									<option value="6">Tarjeta</option>
								</select>
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.id_medio_pago"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
								<label for>Condición envío</label>
								<select disabled class="form-control" v-model.number="form.condicion_envio">
									<option value="Envío">Envío</option>
									<option value="Retiro en tienda"> Retiro en tienda</option>
								</select>
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.condicion_envio"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>


						<div class="col-sm-3">
							<div class="form-group">
								<label for>Tiempo de Entrega</label>
								<input disabled class="form-control" type="text" v-model.number="form.tiempo_entrega">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.tiempo_entrega"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>


						<div class="col-sm-9">
							<div class="form-group">
								<label for>Nota</label>
								<input disabled class="form-control" type="text" v-model="form.nota">
								<ul class="error-messages">
									<li
											:key="message"
											v-for="message in errorMessages.nota"
											v-text="message"
									></li>
								</ul>
							</div>
						</div>



					</div>

					<div class="alert alert-success">
						<span><strong>Detalle de productos</strong></span>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<ul class="error-messages">
								<li
										:key="message"
										v-for="message in errorMessages.orden_compra_productos"
										v-text="message"
								></li>
							</ul>
							<ul class="error-messages">
								<li
										:key="message"
										v-for="message in errorMessages.fecha_emision"
										v-text="message"
								></li>
							</ul>
							<table class="table table-bordered"  >
								<thead>
								<tr>
									<th></th>
									<th >Producto</th>
									<th >Cantidad {{form.estado === 2||form.estado === 3? 'Facturada': ''}}</th>
									<th >Precio Unitario {{form.estado === 2 || form.estado === 3? 'Facturada': ''}}</th>
									<th >Descuento</th>
									<th >SubTotal</th>


								</tr>
								</thead>
								<tbody>
								<template v-for="(item, index) in form.orden_compra_productos">
									<tr>
										<td style="width: 2%">
											<button disabled @click="eliminarLinea(item, index)">
												<i class="fa fa-trash"></i>
											</button>
										</td>
										<td>
											<input class="form-control" disabled v-model="item.orden_compra_producto.descripcion">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`orden_compra_productos.${index}.orden_compra_producto.id_producto`]"
														v-text="message">
												</li>
											</ul>
										</td>


										<template v-if="form.estado > 2">

											<td>
												<input :disabled="form.estado !== 2" class="form-control" type="number" v-model.number="item.cantidad_facturado">
												<ul class="error-messages">
													<li
															:key="message"
															v-for="message in errorMessages[`solicitud_productos.${index}.cantidad`]"
															v-text="message">
													</li>
												</ul>
											</td>


											<td>
												<input :disabled="form.estado !== 2" class="form-control" type="number" v-model.number="item.precio_facturado">
												<ul class="error-messages">
													<li
															:key="message"
															v-for="message in errorMessages[`solicitud_productos.${index}.precio`]"
															v-text="message">
													</li>
												</ul>
											</td>



										</template>
										<template v-else>

											<td>
												<input :disabled="form.estado !== 2" class="form-control" type="number" v-model.number="item.cantidad">
												<ul class="error-messages">
													<li
															:key="message"
															v-for="message in errorMessages[`orden_compra_productos.${index}.cantidad`]"
															v-text="message">
													</li>
												</ul>
											</td>


											<td>
												<input :disabled="form.estado !== 2" class="form-control" type="number" v-model.number="item.precio">
												<ul class="error-messages">
													<li
															:key="message"
															v-for="message in errorMessages[`orden_compra_productos.${index}.precio`]"
															v-text="message">
													</li>
												</ul>
											</td>

										</template>





										<td>
											<input :disabled="form.estado !== 2" class="form-control" type="number" v-model.number="item.descuento">
											<ul class="error-messages">
												<li
														:key="message"
														v-for="message in errorMessages[`orden_compra_productos.${index}.descuento`]"
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
									<td colspan="4"></td>
									<td>Subtotal</td>
									<td> <strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} {{total_subt | formatMoney(2)}}</strong></td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td>Descuento</td>
									<td> <strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} {{total_descuento | formatMoney(2)}}</strong></td>
								</tr>
								<tr>
									<td colspan="3"></td>
									<td>I.V.A.</td>
									<td><input :disabled="form.estado !== 2" class="form-control" min="0" max="99" v-model.number="form.porcentaje_iva"></td>
									<td> <strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} {{calcular_impuesto | formatMoney(2)}}</strong></td>
								</tr>
								<tr>
									<td class="item-footer" colspan="2"> Total Unidades</td>
									<td  class="item-footer">
										<strong>{{total_cantidad}}</strong>
									</td>
									<td></td>
									<td>Total</td>
									<td> <strong>{{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} {{gran_total | formatMoney(2)}}</strong></td>
								</tr>

								</tfoot>
							</table>
						</div>
					</div>

					<div class="content-box-footer text-right">
						<router-link tag="button" :to="{ name: 'listado-ordenes-compras' }">
							<button type="button" class="btn btn-default">Cancelar</button>
						</router-link>

						<template v-if="form.estado === 1">
							<button
									:disabled="btnAction !== 'Confirmar'"
									@click="confirmar(2)"
									class="btn btn-primary"
									type="button"
							>Cambiar a Ordenado</button>
							<button
									:disabled="btnAction !== 'Confirmar'"
									@click="confirmar(0)"
									class="btn btn-danger"
									type="button"
							>Rechazar</button>

						</template>

						<template v-if="form.estado === 2">
							<button
									:disabled="btnAction !== 'Confirmar'"
									@click="confirmar(3)"
									class="btn btn-primary"
									type="button"
							>Confirmar Facturación</button>
							<button
									:disabled="btnAction !== 'Confirmar'"
									@click="confirmar(0)"
									class="btn btn-danger"
									type="button"
							>Rechazar</button>

						</template>

						<template v-if="form.estado === 3">
							<button
									:disabled="btnAction !== 'Confirmar'"
									@click="confirmar(4)"
									class="btn btn-primary"
									type="button"
							>Confirmar Envío</button>
							<button
									:disabled="btnAction !== 'Confirmar'"
									@click="confirmar(0)"
									class="btn btn-danger"
									type="button"
							>Rechazar</button>

						</template>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>


<script type="text/ecmascript-6">
	import orden from "../../api/orden_compras";
	import es from 'vuejs-datepicker/dist/locale/translations/es'

	export default {
		data() {
			return {
				productosBusqueda: {},
				productosBusquedaURL: "/inventario/productos/buscarPS",
				es: es,
				format: "dd-MM-yyyy",
				areas: [],
				proveedores: [],
				productos: [],
				trabajadores: [],
				//sucursales:[],
				monedas: [],
				bodegas: [],

				/*detalleForm:{
					productox: {},
					proveedorx: "",
					cantidad: 1,
					monto_descuento:0,
					precio: 0,
					subtotal: 0,
					total: 0,
					f_necesidad_producto: moment(new Date()).format("YYYY-MM-DD"),
				},*/

				form: {
					numero_factura:"",
					observacion: "",
					f_orden_compra: moment(new Date()).format("YYYY-MM-DD"),
					proveedor: "",
					referencia_solicitud: "",
					telefono_proveedor: "",
					email_proveedor: "",
					atencion: "",
					area_proveedor: "",
					total: 0,
					orden_compra_productos: [],
					es_borrador:false
				},
				btnAction: "Confirmar",
				errorMessages: []
			};
		},
			computed: {
				total_cantidad() {
					return this.form.orden_compra_productos.reduce((carry, item) => {

						if(this.form.estado > 2){
							return (carry + Number(item.cantidad_facturado))
						}
						else{
							return (carry + Number(item.cantidad))
						}


					}, 0)
				},
				total_subt() {
					return this.form.orden_compra_productos.reduce((carry, item) => {
								if(this.form.estado > 2){
									return(carry + Number( (Number(item.cantidad_facturado * item.precio_facturado).toFixed(2))));
								}
								else{
									return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))));
								}
							}
							, 0)
				},
				total_descuento() {
					return this.form.orden_compra_productos.reduce((carry, item) => {
								if(this.form.estado > 2){
									return(carry + Number( (Number(item.cantidad_facturado * item.precio_facturado).toFixed(2))* Number(item.descuento/100).toFixed(2)));
								}
								else{
									return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))* Number(item.descuento/100).toFixed(2)));
								}

							}
							, 0)
				},

				calcular_impuesto() {
					//console.log(this.form.estado);
					this.form.iva = Number((this.form.orden_compra_productos.reduce((carry, item) => {
								//return(carry + Number(Number(item.subtotal*0.15).toFixed(2)));

								if(this.form.estado > 2){
									return(carry + Number(Number(item.subtotal) * Number(this.form.porcentaje_iva/100)));
								}
								else{
									return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))* (Number(this.form.porcentaje_iva/100))* Number( 1- (item.descuento/100)).toFixed(2)));
								}
							}
							, 0)).toFixed(2));

					return Number((this.form.iva).toFixed(2));
				},

				gran_total() {
					this.form.total =  Number(this.form.orden_compra_productos.reduce((carry, item) => {
								if(this.form.estado > 2){
									return(carry + Number( (Number(item.cantidad_facturado * item.precio_facturado).toFixed(2))* Number( 1- (item.descuento/100)).toFixed(2)));
								}
								else{
									return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))* Number( 1- (item.descuento/100)).toFixed(2)));
								}
							}
							, 0).toFixed(2))

					return this.form.total+this.form.iva;
				},
			},
			methods: {
				cambiarTipoPago(){
					let self = this;
					if(self.form.id_condicion_pago === 1){
						self.form.plazo_credito = 0;
					}else{
						self.form.plazo_credito = 8;
					}
				},
				onDateSelect(date) {
					//console.log(date); //
					this.form.f_orden_compra = moment(date).format("YYYY-MM-DD"); //
				},
				confirmar(estadox){
					let titulox = '';
					if(estadox===0){
						titulox='Esta seguro de rechazar ésta orden de compra?';
					}else if(estadox===2){
						titulox='Esta seguro de cambiar el estado a Ordenado?';
					}else if(estadox===3) {
						titulox = 'Esta seguro de cambiar el estado a Facturado?';
					}else if(estadox===4) {
						titulox = 'Esta seguro de cambiar el estado a Enviado?';
					}

					this.$swal.fire({
						title: titulox,
						text: "Revise bien los datos, esta acción no se puede deshacer.",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si, confirmar',
						cancelButtonText:'Cancelar'
					}).then((result) => {
						if (result.value) {
							this.cambiarEstado(estadox);
						}
					})
				},

				seleccionarProducto(e) {
					const productoP = e.target.value;
					var self = this;
					self.detalleForm.productox = productoP;

					if(self.detalleForm.productox)
						self.detalleForm.precio = self.detalleForm.productox.costo_estandar;

				},
				obtenerOrdenCompra() {
					var self = this
					orden.obtenerOrdenCompra({
						id_orden_compra: this.$route.params.id_orden_compra,
						cargar_dependencias: true,
					}, data => {

						self.proveedores= data.proveedores;
						self.form = data.orden;
						self.monedas = data.monedas;
						self.bodegas = data.bodegas;

						if(self.form.estado === 99 || self.form.estado === 4 || self.form.estado === 0){
							alertify.error("La orden ya no puede ser actualizada",5);
							this.$router.push({
								name: "listado-ordenes-compras"
							});
						}
					})
				},
				sub_total(itemX) {

					if(this.form.estado > 2){
						itemX.subtotal= Number((Number(itemX.precio_facturado)* Number(itemX.cantidad_facturado)).toFixed(2));
					}
					else{
						itemX.subtotal= Number((Number(itemX.precio)* Number(itemX.cantidad)).toFixed(2));
					}


					itemX.monto_descuento= Number(Number(itemX.subtotal* Number(itemX.descuento/100)).toFixed(2));
					itemX.total =itemX.subtotal - itemX.monto_descuento;
					if(!isNaN(itemX.total)){
						return itemX.total;
					}
					else return 0;
				},
				eliminarLinea(item, index) {
					if (this.form.orden_compra_productos.length >= 1) {
						this.form.orden_compra_productos.splice(index, 1);

					}else{
						this.form.orden_compra_productos=[];
					}
				},


				cambiarEstado(estadox) {
					var self = this;
					if( (self.form.estado === 1 && (estadox === 2|| estadox === 0)) ||
							(self.form.estado === 2 && (estadox === 3|| estadox === 0) ||
									(self.form.estado === 3 && (estadox === 4|| estadox === 0)))){
					self.btnAction = "Registrando, espere....";

						orden.cambiarEstado({
									id_orden_compra:this.$route.params.id_orden_compra,
									productos: self.form.orden_compra_productos,
									porcentaje_iva: Number(self.form.porcentaje_iva),
									total: Number(self.form.total),
									plazo_credito: Number(self.form.plazo_credito),
									id_condicion_pago: Number(self.form.id_condicion_pago),
									iva: Number(self.form.iva.toFixed(2)),
									numero_factura: self.form.numero_factura,
									estado: estadox},
							data => {
								if(estadox === 2){
									alertify.success("El estado de la orden de compra ha cambiado a Ordenado",5);
								}else if(estadox === 3){
									alertify.success("El estado de la orden de compra ha cambiado a Facturado",5);
								}else if(estadox === 4){
									alertify.success("El estado de la orden de compra ha cambiado a Enviado",5);
								}
								this.$router.push({
									name: "mostrar-orden-compra",
									params: { id_orden_compra: self.form.id_orden_compra }
								});
							},
							err => {
								self.errorMessages = err;
								self.btnAction = "Confirmar";
							}
					);
					}else{
						alertify.error("La orden de compra no puede ser actualizada",5);
					}
				}
			},
			mounted() {
				this.obtenerOrdenCompra();
			}
		};
</script>
<style>
	.btn-agregar {
		margin-top:33px;
	}
</style>