<template>
	<b-card>
		<b-row>
			<b-col class="sm-text-center" cols="12" md="12">
				<form>
					<div class="form-group">
						<label for=""> Descripción</label>
						<input class="form-control" placeholder="Dígite descripción" v-model="form.descripcion">
						<b-alert show variant="danger">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
							</ul>
						</b-alert>
					</div>
					<div class="form-group">
						<label for=""> Cuenta Contable</label>
						<v-select :options="cuentas_contables" label="nombre_cuenta_completo" v-model="form.tipo_cuenta_contable"  ></v-select>
						<b-alert show variant="danger">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.tipo_cuenta_contable" v-text="message"></li>
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
						<div class="col-sm-6 text-left p-0">
							<template v-if="form.estado">
								<b-button @click="desactivar(form.id_tipo_nota_credito)" type="submit" variant="danger">
									<feather-icon icon="Trash2Icon"></feather-icon> Eliminar
								</b-button>
							</template>
							<template v-else>
								<button @click="activar(form.id_tipo_nota_credito)" type="submit" variant="success">
									<feather-icon icon="CheckIcon"></feather-icon> Habilitar
								</button>
							</template>
						</div>

						<div class="col-sm-6">
							<router-link :to="{ name: 'cxc-tipos-notas-credito' }">
								<b-button  type="submit" variant="secondary" class="mx-1">Cancelar</b-button>
							</router-link>
							<b-button type="submit" variant="primary" :disabled="btnAction != 'Guardar' ? true : false" @click="actualizar">{{ btnAction }}</b-button>
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
				cuentas_contables:[],
				form: {
					descripcion: '',
					aplica_comision:false,
					tipo_nota_credito_cuenta_contable:[],
					estado:''
				},
				btnAction: 'Guardar',
				errorMessages: []
			}
		},
		methods: {
			obtenerTipoNC() {
				var self = this
				tipos_notas.obtenerTipoNC({
					id_tipo_nota_credito: self.$route.params.id_tipo_nota_credito
				}, data => {
					self.form = data.tipos_nc
					self.cuentas_contables = data.cuentas_contables
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
					this.$router.push({
						name: 'cxc-tipos-notas-credito'
					});
				})

				
			},

			desactivar(tipoId) {
				var self = this
				tipos_notas.desactivar({
						id_tipo_nota_credito: tipoId
					}, data => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'El tipo de nota de crédito fue desactivado correctamente.',
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
									text: 'No se ha podido activar el tipo de nota de crédito.',
									variant: 'warning',
								}
							},
							{
								position: 'bottom-right'
							});
					})
			},
			activar(tipoId) {
				var self = this
				tipos_notas.activar({
					id_tipo_nota_credito: tipoId
				}, data => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'El tipo de nota de crédito fue activado correctamente.',
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
									text: 'No se ha podido activar el tipo de nota de crédito.',
									variant: 'warning',
								}
							},
							{
								position: 'bottom-right'
							});
				})
			},
			
			actualizar() {
				var self = this
				self.btnAction = 'Guardando, espere......'
				tipos_notas.actualizar(self.form, data => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'El tipo de nota de crédito fue actualizada correctamente.',
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
                   	self.btnAction = 'Guardar'
				})
			}
		},
		mounted() {
			this.obtenerTipoNC()
		}
    }
</script>