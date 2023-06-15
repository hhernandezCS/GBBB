<template>
	<div class="main">
		<div class="row">
			<div class="col-md-12">
				<div class="content-box">
					<div class="content-box-header">
						<div class="box-title">Crear Nuevo Producto</div>
						<div class="box-description">Formulario para registrar nuevo producto</div>
					</div>
					<form>
					<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Código</label>
							<input class="form-control" placeholder="Código producto" v-model="form.codigo">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.codigo" v-text="message"></li>
							</ul>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Nombre producto</label>
							<input class="form-control" placeholder="Nombre producto" v-model="form.nombre_producto">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.nombre_producto" v-text="message"></li>
							</ul>
						</div>
						</div>
						</div>

						<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Descripción</label>
							<input class="form-control" placeholder="Descripción producto" v-model="form.descripcion">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.descripcion" v-text="message"></li>
							</ul>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Categoría Producto</label>
								<select class="form-control" v-model="form.id_categoria">
								<option :key="categoria.id_categoria" :value="categoria.id_categoria" v-for="categoria in categorias">{{ categoria.nombre_categoria }}</option>
								</select>
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.id_categoria" v-text="message"></li>
							</ul>
						</div>
						</div>
						</div>

						<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Costo Estándar</label>
							<input class="form-control" placeholder="Costo Estándar" v-model="form.costo_estandar">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.costo_estandar" v-text="message"></li>
							</ul>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Precio sugerido de venta</label>
							<input class="form-control" placeholder="Precio de venta" v-model="form.precio_sugerido">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.precio_sugerido" v-text="message"></li>
							</ul>
						</div>
						</div>
						</div>

						<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Existencia Mínima</label>
							<input class="form-control" placeholder="Existencia Mínima" v-model="form.existencia_Min">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.existencia_Min" v-text="message"></li>
							</ul>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Existencia Máxima</label>
							<input class="form-control" placeholder="Existencia Máxima" v-model="form.existencia_Max">
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.existencia_Max" v-text="message"></li>
							</ul>
						</div>
						</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
						<div class="form-group">
							<label for=""> Imágenes</label>
							<div class="uploader">
								<button class="btn btn-primary" id="uploader-button" type="button">Escoger Imágenes</button>
							</div>
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.imagen" v-text="message"></li>
							</ul>
						</div>
						</div>
						</div>

						<div class="row">
						<div class="col-sm-6">
							<div class="form-group">		
               					<v-select :options="proveedores" label="nombre_proveedor" multiple v-model="provSelected"></v-select>
							<ul class="error-messages">
								<li :key="message" v-for="message in errorMessages.proveedores" v-text="message"></li>
							</ul>
							</div>
						</div>
							</div>

					</form>
					<div class="content-box-footer text-right">
						<router-link :to="{ name: 'productos' }" tag="button">
							<button class="btn btn-default" type="button">Cancelar</button>
						</router-link>
						<button :disabled="btnAction != 'Guardar' ? true : false" @click="actualizarProducto" class="btn btn-primary" type="button">{{ btnAction }}</button>
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
	import producto from '../../api/productos'
	import categoria from '../../api/categorias'
	import proveedor from '../../api/proveedores'
	import loadingImage from '../../assets/images/block50.gif'

	export default {
		data() {
			return {
				loading:true,
				msg: 'Cargando contenido, espere un momento',
				url : '/public'+loadingImage,   //It is important to import the loading image then use here
				categorias: [],
				proveedores: [],
				provSelected:[],
				form: {
					codigo: '',
					nombre_producto: '',
					descripcion: '',
					costo_estandar: '',
					precio_sugerido: '',
					existencia_Min: '',
					existencia_Max: '',
					imagen: '',
					id_categoria: '',
					proveedores_lista:[]
				},
				uploader: [],
				btnAction: 'Guardar',
				errorMessages: []
			}
		},
		methods: {
			ObtenerProductoEspecifico() {
				var self = this
				producto.obtenerProducto({
					id_producto: this.$route.params.id_producto
				}, data => {
					self.form = data
					self.uploader.set_files(data.imagen)

					var i;
			//console.log(self.form);
			for (i = 0; i < self.form.proveedores_lista.length; i++) { 
			//console.log(self.form.proveedores_lista[i]['id_proveedor']);
			self.provSelected.push({
						id_proveedor: self.form['proveedores_lista'][i]['id_proveedor'],
						nombre_proveedor:self.form['proveedores_lista'][i]['proveedores']['nombre_proveedor']
				
					})
			}



				}, err => {
					console.log(err)
				})
			},
			obtenerProveedoresProducto(){
			
			},
			obtenerTodosProveedores() {
				var self = this
				proveedor.obtenerTodosProveedores(data => {
					self.proveedores = data
				}, err => {
					console.log(err)
				})
			},
				obtenerTodasCategorias() {
				var self = this
				categoria.obtenerTodasCategorias(data => {
					self.categorias = data
				}, err => {
					console.log(err)
				})
			},
			getImages() {
				var imagen = $('input[name="imagen"]').val()
				if (JSON.parse(imagen).length) {
					return imagen
				} else {
					return ''
				}
			},
			actualizarProducto() {
				var self = this
				self.btnAction = 'Guardando, espere....'
				self.form.imagen = this.getImages()
				self.form.provSelected = this.provSelected;

				producto.actualizarProducto(self.form, data => {
					this.$router.push({
						name: 'productos'
					})
				}, err => {
					self.errorMessages = err
                   	self.btnAction = 'Registrar'
				})
			},
			initUploader() {
				var self = this
				this.uploader = $('#uploader-button').uploader({
					upload_url: window.location.origin + '/public/media/upload',
					file_picker_url: window.location.origin + '/public/media/get-files',
					input_name: 'imagen',
					maximum_total_files: 4,
					maximum_file_size: 5000000,
					file_types_allowed: ['image/jpeg', 'image/png'],
					on_before_upload: function() {
						self.btnAction = 'Subiendo imágenes...'
					},
					on_success_upload: function() {
						self.btnAction = 'Guardar'
					},
					on_error: function(err) {
						console.log(err)
					}
				})
			},
			/*initSelect2() {
				$('.select2').select2()
			}*/
			
		},
		mounted() {
		this.initUploader()	
		this.obtenerTodasCategorias() 
		this.obtenerTodosProveedores()
		this.ObtenerProductoEspecifico()
		this.obtenerProveedoresProducto()
		}
		
    }
</script>

