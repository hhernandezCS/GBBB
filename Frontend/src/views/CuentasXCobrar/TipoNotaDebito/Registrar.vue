<template>
	<b-card>
		<b-row>
			<b-col class="sm-text-center" cols="12" md="12">
				<form>
					<div class="form-group">
						<label > Descripción</label>
						<input class="form-control" placeholder="Dígite descripción" v-model="form.descripcion">
						<b-alert show variant="danger">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
							</ul>
						</b-alert>
					</div>
					<div class="form-group">
						<label > Cuenta Contable</label>
						<v-select :options="cuentas_contables" label="nombre_cuenta_completo" v-model="form.clasificacion_compra_cuenta_contable"  ></v-select>
						<b-alert show variant="danger">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.tipo_nota_debito_cuenta_contable" v-text="message"></li>
							</ul>
						</b-alert>
					</div>
					<template v-if="form.clasificacion_compra_cuenta_contable">
						<b-row>
							<div class="col-md-6" v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 0">
								<label for=""> Centro / Código</label>
								<input class="form-control" placeholder="No requiere codigo auxiliar"
									   v-model="form.notRequiered" disabled>
							</div>
						</b-row>
						<div v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 1">
							<div class="row">
								<div class="col-md-6">
									<label for=""> Auxiliares</label>
									<v-select :options="auxiliares" label="descripcion"
											  v-model="form.clasificacion_auxiliar"></v-select>
									<b-alert show variant="danger">
										<ul class="error-messages">
											<li :key="message" v-for="message in errorMessages.clasificacion_auxiliar"
												v-text="message"></li>
										</ul>
									</b-alert>
								</div>
								<div  class="col-md-6">
									<label for=""> Centro / Código</label>
									<input class="form-control" placeholder="Dígite nombre de la clasificacion"
										   v-model="form.clasificacion_auxiliar.codigo">
								</div>
							</div>

						</div>
						<div v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 2">
							<div class="row">
								<div class="col-md-6">
									<label for=""> Centros de costo</label>
									<v-select :options="centros_costos" label="descripcion"
											  v-model="form.clasificacion_centro_costo"></v-select>
									<b-alert show variant="danger">
										<ul class="error-messages">
											<li :key="message" v-for="message in errorMessages.clasificacion_centro_costo"
												v-text="message"></li>
										</ul>
									</b-alert>
								</div>
								<div class="col-md-6">
									<label for=""> Centro / Código</label>
									<input class="form-control" placeholder="Dígite nombre de la clasificacion"
										   v-model="form.clasificacion_centro_costo.codigo">
								</div>
							</div>

						</div>
						<div v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 3">
							<div class="row">
								<div class="col-md-6">
									<label for=""> Centros de costo</label>
									<v-select :options="centros_ingresos" label="descripcion"
											  v-model="form.clasificacion_centro_costo"></v-select>
									<b-alert show variant="danger">
										<ul class="error-messages">
											<li :key="message" v-for="message in errorMessages.clasificacion_centro_costo"
												v-text="message"></li>
										</ul>
									</b-alert>

								</div>
								<div class="col-md-6">
									<label for=""> Centro / Código</label>
									<input class="form-control" placeholder="Dígite nombre de la clasificacion"
										   v-model="form.clasificacion_centro_costo.codigo">
								</div>
							</div>

						</div>
						<div class="form-group">
							<label for=""> Concepto comprobante</label>
							<input class="form-control" placeholder="Dígite el concepto para el comprobante contable"
								   v-model="form.concepto_comprobante">
							<b-alert show variant="danger">
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.concepto_comprobante" v-text="message"></li>
								</ul>
							</b-alert>

						</div>
						<div class="form-group">
							<label for=""> Comisión bancaria</label>
							<input class="form-control" placeholder="Dígite la comisión bancaria" type="number" min="0"
								   v-model="form.comision">
							<b-alert show variant="danger">
								<ul class="error-messages">
									<li :key="message" v-for="message in errorMessages.comision" v-text="message"></li>
								</ul>
							</b-alert>
						</div>
					</template>
				</form>
				<b-card-footer class="content-box-footer text-right mt-1">
					<b-row>
						<div class="col-sm-12 text-lg-right">
							<router-link :to="{ name: 'cxc-tipos-notas-debito' }">
								<b-button type="submit" variant="secondary" class="mt-1 mr-1">Cancelar</b-button>
							</router-link>
							<b-button :disabled="btnAction != 'Registrar' ? true : false" @click="registrar" class="mt-1" type="submit" variant="primary" >
								{{ btnAction }}
							</b-button>
						</div>
					</b-row>
				</b-card-footer>
			</b-col>
		</b-row>
	</b-card>
</template>

<script type="text/ecmascript-6">
	import tipos_notas from '../../../api/CuentasXCobrar/tipos_notas_debito'
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
	} from 'bootstrap-vue'
	import loadingImage from '../../../assets/images/loader/block50.gif'
	import vSelect from 'vue-select'
	import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
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
		},
		directives: {
			'b-tooltip': VBTooltip,
		},
		data() {
			return {
				loading:true,
				cuentas_contables:[],
				auxiliares: [],
				centros_costos: [],
				centros_ingresos: [],
				form: {
					descripcion: '',
					clasificacion_compra_cuenta_contable:[],
					activo:'',
					comision:''
				},
				btnAction: 'Registrar',
				errorMessages: []
			}
		},
		methods: {
			nuevo() {
				var self = this;
				tipos_notas.nuevo({}, data => {
							self.cuentas_contables = data.cuentas_contables;
							self.centros_costos = data.centros_costos;
							self.centros_ingresos = data.centros_ingresos;
							self.auxiliares = data.auxiliares;
							self.loading = false;
						},
						err => {
							this.$toast({
										component: ToastificationContent,
										props: {
											title: 'Notificación',
											icon: 'InfoIcon',
											text: 'Ha ocurrido un error. ',
											variant: 'warning',

										}
									},
									{
										position: 'bottom-right'
									});
						})
			},
			registrar() {
				var self = this
				self.btnAction = 'Registrando, espere....'

				tipos_notas.registrar(self.form, data => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'Nota de débito registrada correctamente.',
									variant: 'success',
								}
							},
							{
								position: 'bottom-right'
							});
					this.$router.push({
						name: 'cxc-tipos-notas-debito'
					})
				}, err => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'Ha ocurrido un error. ',
									variant: 'warning',

								}
							},
							{
								position: 'bottom-right'
							});
					self.errorMessages = err
                   	self.btnAction = 'Registrar'
				})
			}
		},
		mounted() {
			this.nuevo();
		}
    }
</script>
