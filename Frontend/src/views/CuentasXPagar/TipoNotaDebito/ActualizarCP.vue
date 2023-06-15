<template>
	<div class="main">
				<div class="content-box">
					<div class="content-box-header">
						<div class="box-title">Modificar Tipo de Notas de Débito</div>
						<div class="box-description">Formulario para modificar Tipo de Notas de Débito</div>
					</div>
					<form>
						<div class="form-group">
							<label for=""> Descripción</label>
							<input class="form-control" placeholder="Dígite descripción" v-model="form.descripcion">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
							</ul>
						</div>
						<div class="form-group">
							<label for=""> Cuenta Contable</label>
							<v-select :options="cuentas_contables" label="nombre_cuenta_completo" v-model="form.tipo_cuenta_contable"  ></v-select>
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.tipo_cuenta_contable" v-text="message"></li>
							</ul>
						</div>
					</form>

					<div class="row">
						<div class="col-md-6">
							<div class="content-box-footer text-left">
								<template v-if="form.estado">
									<button @click="desactivar(form.id_tipo_nota_debito)" class="btn btn-danger"><i class="fa fa-trash-o">	Eliminar</i></button>
								</template>
								<template v-else>
									<button @click="activar(form.id_tipo_nota_debito)" class="btn btn-success"><i class="fa fa-check-square">	Habilitar</i></button>
								</template>
							</div>
						</div>

						<div class="col-md-6">
							<div class="content-box-footer text-right">
								<router-link :to="{ name: 'tipos-notas-debito-cp' }" tag="button">
									<button class="btn btn-default" type="button">Cancelar</button>
								</router-link>
								<button :disabled="btnAction != 'Guardar' ? true : false" @click="actualizar" class="btn btn-primary" type="button">{{ btnAction }}</button>
							</div>
						</div>

					</div>
				</div>
			</div>
	
</template>

<script type="text/ecmascript-6">
	import tipos_notas from '../../api/tipos_notas_debito_cp'

	export default {
		data() {
			return {
				cuentas_contables:[],
				form: {
					descripcion: '',
					tipo_nota_debito_cuenta_contable:[],
					estado:''
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
					self.form = data.tipos_nc
					self.cuentas_contables = data.cuentas_contables
				}, err => {
					alertify.error(err.id_tipo_nota_debito[0],5);
					this.$router.push({
						name: 'tipos-notas-debito-cp'
					});
				})

				
			},

			desactivar(tipoId) {
				var self = this
				tipos_notas.desactivar({
						id_tipo_nota_debito: tipoId
					}, data => {
						alertify.warning("Tipo Nota Débito desactivado correctamente",5);
						this.$router.push({
							name: 'tipos-notas-debito-cp'
						})
					}, err => {
						console.log(err)
					})
			},
			activar(tipoId) {
				var self = this
				tipos_notas.activar({
					id_tipo_nota_debito: tipoId
				}, data => {
					alertify.success("Tipo Nota Débito activado correctamente",5);
					this.$router.push({
						name: 'tipos-notas-debito-cp'
					})
				}, err => {
					console.log(err)
				})
			},
			
			actualizar() {
				var self = this
				self.btnAction = 'Guardando, espere......'
				tipos_notas.actualizar(self.form, data => {
					alertify.success("Datos actualizados correctamente",5);
					this.$router.push({
						name: 'tipos-notas-debito-cp'
					})
				}, err => {
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