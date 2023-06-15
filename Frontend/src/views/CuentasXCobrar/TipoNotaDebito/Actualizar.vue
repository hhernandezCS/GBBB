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

					<template v-if="form.clasificacion_compra_cuenta_contable">
						<div class="col-md-6" v-if="form.clasificacion_compra_cuenta_contable.requiere_aux === 0">
							<label for=""> Centro / Código</label>
							<input class="form-control" placeholder="No requiere codigo auxiliar"
								   v-model="form.notRequiered" readonly>
						</div>
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
						<div class="col-sm-6 text-left p-0">
							<template v-if="form.estado">
								<b-button @click="desactivar(form.id_tipo_nota_debito)" type="submit" variant="danger">
									<feather-icon icon="Trash2Icon"></feather-icon> Eliminar
								</b-button>
							</template>
							<template v-else>
								<button @click="activar(form.id_tipo_nota_debito)" type="submit" variant="success">
									<feather-icon icon="CheckIcon"></feather-icon> Habilitar
								</button>
							</template>
						</div>

						<div class="col-sm-6">
							<router-link :to="{ name: 'cxc-tipos-notas-debito' }">
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
				cuentas_contables:[],
				auxiliares: [],
				centros_costos: [],
				centros_ingresos: [],
				form: {
					descripcion: '',
					tipo_nota_debito_cuenta_contable:[],
					estado:'',
					notRequiered: 'No requiere codigo auxiliar',
					notAvailable: 'N/A',
					comision:'',
				},
				btnAction: 'Guardar',
				errorMessages: []
			}
		},
		methods: {
			obtenerTipoND() {
				var self = this
				tipos_notas.obtenerTipoND({
					id_tipo_nota_debito: self.$route.params.id_tipo_nota_debito
				}, data => {
					self.form = data.tipos_nc;
					self.cuentas_contables = data.cuentas_contables;
					self.centros_costos = data.centros_costos;
					self.centros_ingresos = data.centros_ingresos;
					self.auxiliares = data.auxiliares;
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
						name: 'cxc-tipos-notas-debito'
					});
				})


			},

			desactivar(tipoId) {
				var self = this
				tipos_notas.desactivar({
						id_tipo_nota_debito: tipoId
					}, data => {
						alertify.warning("El tipo de nota de débito fue desactivado correctamente",5);
						this.$router.push({
							name: 'cxc-tipos-notas-debito'
						})
					}, err => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'No se ha podido desactivar el tipo de nota de débito.',
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
					id_tipo_nota_debito: tipoId
				}, data => {
					alertify.success("El tipo de nota de débito fue activado correctamente.",5);
					this.$router.push({
						name: 'cxc-tipos-notas-debito'
					})
				}, err => {
					this.$toast({
								component: ToastificationContent,
								props: {
									title: 'Notificación',
									icon: 'InfoIcon',
									text: 'No se ha podido activar la nota de débito.',
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
									text: 'El tipo de nota de débito fue actualizado correctamente.',
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
                   	self.btnAction = 'Guardar'
				})
			}
		},
		mounted() {
			this.obtenerTipoND()
		}
    }
</script>
