<template>
	<div class="main">
		<div class="row">
			<div class="col-md-12">
				<div class="content-box">
					<div class="content-box-header">
						<div class="box-title">Crear Nuevo Tipo de Nota de Débito</div>
						<div class="box-description">Formulario para registrar nuevo tipo de Nota de Débito</div>
					</div>
					<form>
						<div class="form-group">
							<label > Descripción</label>
							<input class="form-control" placeholder="Dígite descripción" v-model="form.descripcion">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
							</ul>
						</div>
						<div class="form-group">
							<label > Cuenta Contable</label>
							<v-select :options="cuentas_contables" label="nombre_cuenta_completo" v-model="form.tipo_nota_debito_cuenta_contable"  ></v-select>
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.tipo_nota_debito_cuenta_contable" v-text="message"></li>
							</ul>
						</div>
						
					</form>
					<div class="content-box-footer text-right">
						<router-link tag="button" :to="{ name: 'tipos-notas-debito-cp' }">
							<button type="button" class="btn btn-default">Cancelar</button>
						</router-link>
						<button :disabled="btnAction != 'Registrar' ? true : false" @click="registrar" class="btn btn-primary" type="button">{{ btnAction }}</button>
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
				loading:true,
				cuentas_contables:[],
				form: {
					descripcion: '',
					tipo_nota_debito_cuenta_contable:[],
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
							console.log(err);
						})
			},
			registrar() {
				var self = this
				self.btnAction = 'Registrando, espere....'

				tipos_notas.registrar(self.form, data => {
					this.$router.push({
						name: 'tipos-notas-debito-cp'
					})
				}, err => {
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