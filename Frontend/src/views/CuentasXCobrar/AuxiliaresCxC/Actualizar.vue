<template>
    <b-card>
            <form>
                <b-row>
                    <b-col cols="12" md="4">
                        <div class="form-group">
                            <label for="">Tipo de auxiliar</label>
                            <select class="form-control" v-model="form.tipo_auxiliar_clasificacion"
                                    v-on:change="limpiar()">
                                <option value="1">Funcionarios y empleados</option>
                                <option value="2">Otras cuentas por cobrar</option>
                            </select>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.medio_contacto"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </b-col>
                    <b-col cols="12" md="4">
                        <div class="form-group">
                            <label> Abreviatura</label>
                            <input class="form-control" placeholder="Dígite descripción"
                                   v-model="form.tipo_auxiliar">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </b-col>

                    <b-col cols="12" md="4" v-if="form.tipo_auxiliar_clasificacion === '1' || form.tipo_auxiliar_clasificacion === 1 ">

                        <div class="form-group">
                            <label for>Funcionarios y empleados</label>
                            <multiselect :allow-empty="false" :options="trabajadores"
                                         :searchable="true"
                                         :show-labels="false"
                                         deselect-label="No se puede eliminar este valor"
                                         label="nombre_completo"
                                         placeholder="Selecciona un empleado"
                                         ref="trabajador"
                                         track-by="id_trabajador"
                                         v-model="form.trabajador_auxiliar"
                                         v-on:input="getCode()"
                            ></multiselect>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.trabajador_auxiliar"
                                        v-text="message"
                                ></li>
                            </ul>
                        </div>
                    </b-col>
                    <b-col cols="12" md="4" v-else>
                        <div class="form-group">
                            <label for>Deudores diversos</label>
                            <multiselect :allow-empty="false" :options="deudores"
                                         :searchable="true"
                                         :show-labels="false"
                                         deselect-label="No se puede eliminar este valor"
                                         label="nombre_completo"
                                         placeholder="Selecciona un deudor"
                                         ref="deudor"
                                         track-by="id_cliente"
                                         v-model="form.deudor_auxiliar"
                                         v-on:input="getCode()"
                            ></multiselect>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.deudorx"
                                        v-text="message"
                                ></li>
                            </ul>
                        </div>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col cols="12" md="4">
                        <div class="form-group">
                            <label> Descripción</label>
                            <input class="form-control" placeholder="Dígite descripción"
                                   v-model="form.descripcion">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </b-col>
                    <b-col cols="12" md="2">
                        <div class="form-group">
                            <label> Código {{concatCode}}</label>
                            <input class="form-control" placeholder="Dígite descripción" v-model="form.codigo">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </b-col>
                </b-row>
            </form>

            <b-row>
                <b-col cols="12" md="6">
                    <div class="content-box-footer text-left">
                        <template v-if="form.estado">
                            <button @click="desactivar(form.id_cat_auxiliar_cxc)" class="btn btn-danger"><i
                                    class="fa fa-trash-o"> Deshabilitar</i></button>
                        </template>
                        <template v-else>
                            <button @click="activar(form.id_cat_auxiliar_cxc)" class="btn btn-success"><i
                                    class="fa fa-check-square"> Habilitar</i></button>
                        </template>
                    </div>
                </b-col>

                <b-col cols="12" md="6">
                    <div class="content-box-footer text-right">
                        <router-link :to="{ name: 'cxc-cat-otras' }">
                            <button  type="submit" variant="secondary" class="mx-1">Cancelar</button>
                        </router-link>
                        <button type="submit" variant="primary" :disabled="btnAction != 'Guardar' ? true : false" @click="actualizar">{{ btnAction }}</button>
                    </div>
                </b-col>
            </b-row>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import auxiliares from '../../../api/CuentasXCobrar/auxiliares_cxc'
	import loadingImage from "../../../assets/images/loader/block50.gif";
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
				msg: 'Cargando los datos, espere un momento',
				url : '/public'+loadingImage,   //It is important to import the loading image then use here
                cuentas_contables: [],
                form: {
					tipo_auxiliar: '',
					codigo:'',
					descripcion:'',
					next_code:'',
                    deudor_auxiliar: [],
                    trabajador_auxiliar: [],
                    tipo_auxiliar_clasificacion: '',
                    auxiliar_actual_trabajador: '',
                    auxiliar_actual_deudor: '',
                    codigo_actual: '',
                    siglas_actual: '',
                },
                deudores: [],
                trabajadores: [],
                btnAction: 'Guardar',
                errorMessages: []
            }
        },
        computed: {
            concatCode() {
                const self = this;

                if(self.form.auxiliar_actual_trabajador !== self.form.trabajador_auxiliar.id_trabajador)
                {
                    if (self.form.tipo_auxiliar_clasificacion === '1' || self.form.tipo_auxiliar_clasificacion === 1) {
                        if (self.form.trabajador_auxiliar && self.form.tipo_auxiliar !== '') {
                            self.form.codigo = self.form.tipo_auxiliar + self.form.trabajador_auxiliar.simplify_code;
                        }
                        else{self.form.codigo = 'No disponible';}
                    } else {
                        if (self.form.deudor_auxiliar && self.form.tipo_auxiliar !== '') {
                            self.form.codigo = self.form.tipo_auxiliar + self.form.deudor_auxiliar.simplify_code;
                        }
                        else{self.form.codigo = 'No disponible';}
                    }
                }else{
                    self.form.codigo = self.form.codigo_actual;
                }


            }
        },
        methods: {
            obtenerTipoNC() {
                var self = this;
                self.loading = true;
                auxiliares.obtenerAuxiliarCxc({
                    id_cat_auxiliar_cxc: self.$route.params.id_cat_auxiliar_cxc
                }, data => {
                    self.form = data.cat_auxiliar;
                    if(self.form.trabajador_auxiliar){
                        self.form.auxiliar_actual_trabajador = self.form.trabajador_auxiliar.id_trabajador; //get id of employee saved previously
                        self.form.siglas_actual = self.form.tipo_auxiliar;
                    }else if (self.form.trabajador_auxiliar)
                    {
                        self.form.auxiliar_actual_deudor = self.form.deudor_auxiliar.id_cliente; //get id of deudor saved previously
                        self.form.siglas_actual = self.form.tipo_auxiliar;
                    }
                    self.form.codigo_actual = data.cat_auxiliar.codigo;
					self.loading = false;
                }, err => {
                    alertify.error(err.id_cat_auxiliar_cxc, 5);
                    this.$router.push({
                        name: 'cxc-cat-otras'
                    });
                })


            },
            nuevo() {
                var self = this;
                self.loading = true;
                auxiliares.nuevo({}, data => {

                        self.trabajadores = data.trabajadores;
                        self.deudores = data.deudores;
                        self.loading = false;
                    },
                    err => {
                        self.loading = false;
                        console.log(err);
                    })
            },
            limpiar() {
                const self = this;
                self.form.descripion = '';
                self.form.tipo_auxiliar = '';
                self.form.codigo = '';
                self.form.trabajador_auxiliar = [];
                self.form.deudor_auxiliar = [];
            },
            getCode() {
                const self = this;
                if (self.form.tipo_auxiliar_clasificacion === '1' || self.form.tipo_auxiliar_clasificacion === 1) {
                    if (self.form.trabajador_auxiliar) {
                        self.form.descripcion = self.form.trabajador_auxiliar.nombre_completo
                    }
                } else {
                    if (self.form.deudor_auxiliar) {
                        self.form.descripcion = self.form.deudor_auxiliar.nombre_completo;
                    }
                }
            },

            desactivar(tipoId) {
                var self = this;
				self.loading = true;
                auxiliares.desactivar({
                    id_cat_auxiliar_cxc: tipoId
                }, data => {
                    alertify.warning("auxiliar desactivado correctamente", 5);
                    this.$router.push({
                        name: 'cxc-cat-otras'
                    });
                }, err => {
                    console.log(err)
                })
            },
            activar(tipoId) {
                var self = this
                auxiliares.activar({
                    id_cat_auxiliar_cxc: tipoId
                }, data => {
                    alertify.success("Auxiliar activado correctamente", 5);
                    this.$router.push({
                        name: 'cxc-cat-otras'
                    })
                }, err => {
                    console.log(err)
                })
            },

            actualizar() {
                var self = this;
				self.loading = true;
                self.btnAction = 'Guardando, espere......'
                auxiliares.actualizar(self.form, data => {
                    alertify.success("Datos actualizados correctamente", 5);
                    this.$router.push({
                        name: 'cxc-cat-otras'
                    })
                }, err => {
					self.loading = false;
                    self.errorMessages = err;
                    self.btnAction = 'Guardar'
                })
            }
        },
        mounted() {
            this.obtenerTipoNC()
            this.nuevo()
        }
    }
</script>
