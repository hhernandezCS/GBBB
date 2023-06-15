<template>
	<b-card>
		<b-row>
			<b-col class="sm-text-center" cols="12" md="12">
				<form>
					<div class="form-group">
						<label > Descripción</label>
						<input class="form-control" placeholder="Dígite una descripción" v-model="form.descripcion">
						<b-alert show variant="danger">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
							</ul>
						</b-alert>
					</div>
					<div class="form-group">
						<label > Cuenta Contable</label>
						<v-select :options="cuentas_contables" label="nombre_cuenta_completo" v-model="form.tipo_nota_credito_cuenta_contable"  ></v-select>
						<b-alert show variant="danger">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.tipo_nota_credito_cuenta_contable" v-text="message"></li>
							</ul>
						</b-alert>
					</div>
					<div class="form-group">
						<b-form-checkbox v-model="form.aplica_comision">
							Aplica comisión
						</b-form-checkbox>
					</div>
				</form>
				<b-card-footer class="content-box-footer text-right mt-1">
					<b-row>
						<div class="col-sm-12 text-lg-right">
							<router-link  :to="{name: 'cxc-tipos-notas-credito'}">
								<b-button type="submit" variant="secondary" class="mt-1 mr-1">
									Cancelar
								</b-button>
							</router-link>
							<b-button class="mt-1" type="submit" variant="primary" @click="registrar" :disabled="btnAction !== 'Registrar'">
								{{btnAction}}
							</b-button>
						</div>
					</b-row>
				</b-card-footer>
			</b-col>
		</b-row>
	</b-card>
</template>

<script type="text/ecmascript-6">
	import tipos_notas from '../../../api/CuentasXCobrar/tipos_notas_credito'
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
				form: {
					descripcion: '',
					aplica_comision:false,
					tipo_nota_credito_cuenta_contable:[],
					activo:''
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
				// self.errorMessages.serie = Array('Error serie');

				tipos_notas.registrar(self.form, data => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'Nota de crédito registrada correctamente.',
									variant: 'success',
								}
							},
							{
								position: 'bottom-right'
							});
					this.$router.push({
						name: 'cxc-tipos-notas-credito'
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