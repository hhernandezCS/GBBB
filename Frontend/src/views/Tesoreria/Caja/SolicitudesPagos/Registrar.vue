<template>
	<div class="main">
		<div class="row">
			<div class="col-md-12">
				<div class="content-box">
					<div class="content-box-header">
						<div class="box-title">Registrar solicitud de pago</div>
						<div class="box-description">Formulario para registrar solicitud de pago</div>
					</div>
					<form>

						<div class="row">
							<div class="col-sm-5">
								<div class="form-group">
									<label for=""> Beneficiario</label>
									<div class="form-group">
										<typeahead :initial="form.beneficiario" :trim="80" :url="proveedoresBusquedaURL" @input="seleccionarBeneficiario" style="width: 100%;"></typeahead>

									</div>
									<ul class="error-messages">
										<li :key="message" v-for="message in errorMessages.beneficiario" v-text="message"></li>
									</ul>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group">
									<label > Listado de Documentos por pagar</label>
									<div class="form-group">
										<multiselect :allow-empty="false" :options="cuentas"
													 :searchable="true"
													 :show-labels="false"
													 deselect-label="No se puede eliminar este valor"
													 label="text"
													 placeholder="Selecciona una cuenta para pagar"
													 ref="cuenta"
													 track-by="id_cuentaxcobrar"
													 v-model="detalleForm.cuentax"
													 v-on:input="cargar_detalles_cuenta()"
										></multiselect>
									</div>
									<ul class="error-messages">
										<li :key="message" v-for="message in errorMessages.cuentax" v-text="message"></li>
									</ul>
								</div>
							</div>


							<div class="col-sm-5">
								<div class="form-group">
									<label for=""> Concepto</label>
									<input class="form-control" placeholder="Dígite el concepto" v-model="form.concepto">
									<ul class="error-messages">
										<li :key="message" v-for="message in errorMessages.concepto" v-text="message"></li>
									</ul>
								</div>
							</div>


							<div class="col-sm-2">
								<div class="form-group">
									<label for>Fecha Solicitud</label>
									<datepicker :format="format" :language="es" @selected="onDateSelect" v-model="fechax"></datepicker>
									<ul class="error-messages">
										<li
												:key="message"
												v-for="message in errorMessages.fecha_solicitud"
												v-text="message"
										></li>
									</ul>
								</div>
							</div>


							<div class="col-sm-2">
								<div class="form-group">
									<label for="">Moneda</label>
									<v-select
											:options="monedas"
											label="descripcion"
											v-model="form.moneda"
											v-on:input="monto_letras"
									></v-select>
									<ul class="error-messages">
										<li :key="message" v-for="message in errorMessages.moneda" v-text="message"></li>
									</ul>
								</div>
							</div>

							<div class="col-sm-2">
								<div class="form-group">
									<label for=""> Monto</label>
									<input @change="monto_letras" class="form-control"  placeholder="Monto del cheque"  type="number" v-model="form.monto_solicitado">
									<ul class="error-messages">
										<li :key="message" v-for="message in errorMessages.monto_solicitado" v-text="message"></li>
									</ul>
								</div>
							</div>


							<div class="col-sm-8">
								<div class="form-group">
									<label for=""> Monto en letras</label>
									<input class="form-control" placeholder="Dígite el monto" readonly v-model="form.monto_letras">
									<ul class="error-messages">
										<li :key="message" v-for="message in errorMessages.monto_letras" v-text="message"></li>
									</ul>
								</div>
							</div>

						</div>

					</form>
					<div class="content-box-footer text-right">
						<router-link :to="{ name: 'listado-solicitudes-pago' }" tag="button">
							<button class="btn btn-default" type="button">Cancelar</button>
						</router-link>
						<button :disabled="btnAction !=='Registrar'" @click="registrar" class="btn btn-primary" type="button">{{ btnAction }}</button>
					</div>
					<template v-if="loading">
						<BlockUI :message="msg" :url="url"></BlockUI>
					</template>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/ecmascript-6">
	//import Datepicker from "vuejs-datepicker";
	import es from 'vuejs-datepicker/dist/locale/translations/es'
	import numberasstring from '../../assets/custom-scripts/NumberAsString'
	import monedas from "../../api/monedas";
	import solicitud_pago from "../../api/solicitudes_pago";
	import loadingImage from '../../assets/images/block50.gif'
	import cuenta from "../../api/cuentas_por_pagar";
	//import Typeahead from '../layout/Typeahead'

	export default {
		/*components: {
			//Datepicker,
			//'typeahead':Typeahead
		},*/
		data() {
			return {
				loading:false,
				msg: 'Guardando los datos, espere un momento',
				url : '/public'+loadingImage,   //It is important to import the loading image then use here
				es: es,
				format: "d MMMM yyyy",
				proveedoresBusqueda: {},
				proveedoresBusquedaURL: "/proveedores/buscar",
				monedas: [],
				cuentas: [],
				fechax:new Date(),
				form: {
					beneficiario : {},
					concepto: '',
					lugar:'',
					moneda : '',
					monto_solicitado : 0,
					fecha_solicitud : moment(new Date()).format("YYYY-MM-DD"),
					monto_letras: '',
		},

				detalleForm: {
					cuentax: '',
					fecha_pago: '',
					descripcion_pago: '',
					monto: 0,
					monto_me: 0,
				},

				btnAction: 'Registrar',
				errorMessages: []
			}
		},
		methods: {

			cargar_detalles_cuenta() {
				var self = this;
				if(self.detalleForm.cuentax)
				//self.detalleForm.moneda_x = self.monedas[1];
					self.detalleForm.monto_me = 0;
				self.detalleForm.monto = Number(Number(self.detalleForm.cuentax.saldo_actual).toFixed(2));
			},

			seleccionarBeneficiario(e) {
				const beneficiario = e.target.value;
				var self = this;
				self.form.beneficiario = beneficiario;


				self.loading = true;
				self.form.detalleCuentasxCobrar = [];
				self.form.detallePago = [];
				self.detalleForm.cuentax = '';

				cuenta.obtenerCuentasProveedor({
					id_proveedor: self.form.beneficiario.id_proveedor
				}, data => {
					if (data !== null) {
						self.cuentas = data;
						self.detalleForm.cuentax = '';
					} else {
						alertify.warning("No se encuentran cuentas para este cliente.", 5);
						self.detalleForm.cuentax = '';
					}
					self.loading = false;
				}, err => {
					/*if(err.codigo_bateria){
                      self.detalleForm.bateria_busqueda = '';
                      self.$refs.bateria.focus();
                      alertify.warning("No se encuentra esta batería.",5);
                      self.detalleForm.cuentax = '';
                    }*/
					self.loading = false;
				})


			},
			monto_letras(){
				if(this.form.moneda && this.form.monto_solicitado >0){
				this.form.monto_letras = numberasstring.numberasstring(this.form.monto_solicitado,this.form.moneda.descripcion_singular.toUpperCase(),this.form.moneda.descripcion.toUpperCase(),true);
				}else{
					this.form.monto_letras ='Seleccione un tipo de moneda';
				}
				return this.form.monto_letras;
			},
			obtenerMonedas() {
				var self = this;
				monedas.cargarMonedas(
						data => {
							self.monedas = data;
							self.form.moneda = self.monedas[0];
						},
						err => {
							console.log(err);
						}
				);
			},
			onDateSelect(date) {
				this.form.fecha_solicitud = moment(date).format("YYYY-MM-DD"); //
			},

			registrar() {
				var self = this
				self.monto_letras();
				self.btnAction = 'Registrando, espere....'
				self.loading = true;

				solicitud_pago.registrar(self.form, data => {
					self.loading = false;
					alertify.success("Solicitud de pago registrada correctamente",5);
					this.$router.push({
						name: 'listado-solicitudes-pago'
					})
				}, err => {
					self.loading = false;
					self.errorMessages = err
					self.btnAction = 'Registrar'
				})
			}
		},
		mounted() {
			this.obtenerMonedas();
		}
	}
</script>