<template>
    <div class="main">

        <div class="content-box">
            <div class="content-box-header">
                <div class="box-title">Modificar otras cuentas por pagar</div>
                <div class="box-description">Formulario para modificar otras cuentas por pagar</div>
            </div>
            <form>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Tipo de auxiliar</label>
                            <select class="form-control" v-model="form.tipo_auxiliar_clasificacion"
                                    v-on:change="limpiar()">
                                <option value="1">Proveedores</option>
                                <option value="2">Acreedores</option>
                                <option value="3">Pasivos laborales</option>
                                <option value="4">Prestamos bancarios y otros</option>
                            </select>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.medio_contacto"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label> Abreviatura</label>
                            <input class="form-control" placeholder="Dígite descripción"
                                   v-model="form.tipo_auxiliar">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-4"
                         v-if="form.tipo_auxiliar_clasificacion === '1' || form.tipo_auxiliar_clasificacion === 1 ">

                        <div class="form-group">
                            <label for>Proveedores</label>
                            <multiselect :allow-empty="false" :options="proveedores"
                                         :searchable="true"
                                         :show-labels="false"
                                         deselect-label="No se puede eliminar este valor"
                                         label="nombre_comercial"
                                         placeholder="Selecciona un proveedor"
                                         ref="proveedor"
                                         track-by="id_proveedor"
                                         v-model="form.proveedor_auxiliar"
                                         v-on:input="getCode()"
                            ></multiselect>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.proveedor"
                                        v-text="message"
                                ></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4"
                         v-if="form.tipo_auxiliar_clasificacion === '2' || form.tipo_auxiliar_clasificacion === 2 ">

                        <div class="form-group">
                            <label for>Acreedores</label>
                            <multiselect :allow-empty="false" :options="acreedores"
                                         :searchable="true"
                                         :show-labels="false"
                                         deselect-label="No se puede eliminar este valor"
                                         label="nombre_comercial"
                                         placeholder="Selecciona un acreedo"
                                         ref="acreedor"
                                         track-by="id_proveedor"
                                         v-model="form.proveedor_auxiliar"
                                         v-on:input="getCode()"
                            ></multiselect>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.proveedor"
                                        v-text="message"
                                ></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4"
                         v-if="form.tipo_auxiliar_clasificacion === '3' || form.tipo_auxiliar_clasificacion === 3 ">

                        <div class="form-group">
                            <label for>Pasivos laborales</label>
                            <multiselect :allow-empty="false" :options="pasivos_laborales"
                                         :searchable="true"
                                         :show-labels="false"
                                         deselect-label="No se puede eliminar este valor"
                                         label="nombre_cuenta"
                                         placeholder="Selecciona un pasivo laboral"
                                         ref="pasivo"
                                         track-by="id_cuenta_contable"
                                         v-model="form.pasivo_auxiliar"
                                         v-on:input="getCode()"
                            ></multiselect>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.proveedor"
                                        v-text="message"
                                ></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4"
                         v-if="form.tipo_auxiliar_clasificacion === '4' || form.tipo_auxiliar_clasificacion === 4 ">

                        <div class="form-group">
                            <label for>Prestamos Bancarios</label>
                            <multiselect :allow-empty="false" :options="prestamos_bancarios"
                                         :searchable="true"
                                         :show-labels="false"
                                         deselect-label="No se puede eliminar este valor"
                                         label="descripcion"
                                         placeholder="Selecciona un banco"
                                         ref="prestamos"
                                         track-by="id_banco"
                                         v-model="form.prestamos_auxiliar"
                                         v-on:input="getCode()"
                            ></multiselect>
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.proveedor"
                                        v-text="message"
                                ></li>
                            </ul>
                        </div>
                    </div>


                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> Descripción</label>
                            <input class="form-control" placeholder="Dígite descripción"
                                   v-model="form.descripcion">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label> Código {{concatCode}}</label>
                            <input class="form-control" placeholder="Dígite descripción" v-model="form.codigo">
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-6">
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
                </div>

                <div class="col-md-6">
                    <div class="content-box-footer text-right">
                        <router-link :to="{ name: 'auxiliares-cxp' }" tag="button">
                            <button class="btn btn-default" type="button">Cancelar</button>
                        </router-link>
                        <button :disabled="btnAction !== 'Guardar' ? true : false" @click="actualizar"
                                class="btn btn-primary" type="button">{{ btnAction }}
                        </button>
                    </div>
                </div>

                <template v-if="loading">
                    <BlockUI :message="msg" :url="url"></BlockUI>
                </template>
            </div>
        </div>
    </div>

</template>

<script type="text/ecmascript-6">
    import auxiliares from '../../api/auxiliares_cxp'
    import loadingImage from "../../assets/images/block50.gif";

    export default {
        data() {
            return {
                loading: true,
                msg: 'Cargando los datos, espere un momento',
                url: '/public' + loadingImage,   //It is important to import the loading image then use here
                cuentas_contables: [],
                form: {
                    tipo_auxiliar: '',
                    codigo: '',
                    descripcion: '',
                    next_code: '',
                    proveedor_auxiliar: [],
                    trabajador_auxiliar: [],
                    pasivo_auxiliar: [],
                    prestamos_auxiliar: [],
                    tipo_auxiliar_clasificacion: '',
                    auxiliar_actual_trabajador: '',
                    auxiliar_actual_deudor: '',
                    codigo_actual: '',
                },
                acreedores: [],
                proveedores: [],
                prestamos_bancarios: [],
                pasivos_laborales: [],
                btnAction: 'Guardar',
                errorMessages: []
            }
        },
        computed: {
            concatCode() {
                const self = this;

                if (self.form.auxiliar_actual_trabajador !== self.form.proveedor_auxiliar.id_proveedor) {
                    if (self.form.tipo_auxiliar_clasificacion === '1' || self.form.tipo_auxiliar_clasificacion === 1) {
                        if (self.form.proveedor_auxiliar && self.form.tipo_auxiliar !== '') {
                            self.form.codigo = self.form.tipo_auxiliar + self.form.proveedor_auxiliar.extcodigo;
                        } else {
                            self.form.codigo = 'No disponible';
                        }
                    } else if (self.form.tipo_auxiliar_clasificacion === '2' || self.form.tipo_auxiliar_clasificacion === 2) {
                        if (self.form.proveedor_auxiliar && self.form.tipo_auxiliar !== '') {
                            self.form.codigo = self.form.tipo_auxiliar + self.form.proveedor_auxiliar.extcodigo;
                        } else {
                            self.form.codigo = 'No disponible';
                        }
                    }
                } else if (self.form.auxiliar_actual_trabajador !== self.form.pasivo_auxiliar.id_cuenta_contable) {
                    if (self.form.tipo_auxiliar_clasificacion === '3' || self.form.tipo_auxiliar_clasificacion === 3) {
                        if (self.form.pasivo_auxiliar && self.form.tipo_auxiliar !== '') {
                            self.form.codigo = self.form.tipo_auxiliar + self.form.pasivo_auxiliar.extcodigo;
                        } else {
                            self.form.codigo = 'No disponible';
                        }
                    }
                } else if (self.form.auxiliar_actual_trabajador !== self.form.prestamos_auxiliar.id_banco) {
                    if (self.form.tipo_auxiliar_clasificacion === '4' || self.form.tipo_auxiliar_clasificacion === 4) {
                        if (self.form.prestamos_auxiliar && self.form.tipo_auxiliar !== '') {
                            self.form.codigo = self.form.tipo_auxiliar + self.form.prestamos_auxiliar.extcodigo;
                        } else {
                            self.form.codigo = 'No disponible';
                        }
                    }
                } else {
                    self.form.codigo = self.form.codigo_actual;
                }


            }
        },
        methods: {
            obtenerTipoNC() {
                var self = this;
                self.loading = true;
                auxiliares.obtenerAuxiliarCxp({
                    id_cat_auxiliar_cxc: self.$route.params.id_cat_auxiliar_cxc
                }, data => {
                    self.form = data.cat_auxiliar;
                    if (self.form.proveedor_auxiliar) {
                        self.form.auxiliar_actual_trabajador = self.form.proveedor_auxiliar.id_proveedor; //get id of employee saved previously
                    }
                    if (self.form.pasivo_auxiliar) {
                        self.form.auxiliar_actual_trabajador = self.form.pasivo_auxiliar.id_cuenta_contable; //get id of accounting saved previously
                    }
                    if (self.form.prestamos_auxiliar) {
                        self.form.auxiliar_actual_trabajador = self.form.prestamos_auxiliar.id_banco; //get id of bank saved previously
                    }
                    self.form.codigo_actual = data.cat_auxiliar.codigo;
                    self.loading = false;
                }, err => {
                    alertify.error(err.id_cat_auxiliar_cxc, 5);
                    this.$router.push({
                        name: 'auxiliares-cxp'
                    });
                })


            },
            nuevo() {
                var self = this;
                self.loading = true;
                auxiliares.nuevo({}, data => {

                        self.proveedores = data.proveedores;
                        self.acreedores = data.acreedores;
                        self.pasivos_laborales = data.pasivos_laborales;
                        self.prestamos_bancarios = data.prestamos_bancarios;
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
                self.form.proveedor_auxiliar = [];
                self.form.pasivo_auxiliar = [];
                self.form.prestamos_auxiliar = [];
            },
            getCode() {
                const self = this;
                if (self.form.tipo_auxiliar_clasificacion === '1' || self.form.tipo_auxiliar_clasificacion === 1) {
                    if (self.form.proveedor_auxiliar) {
                        self.form.descripcion = self.form.proveedor_auxiliar.nombre_comercial
                    }
                } else if (self.form.tipo_auxiliar_clasificacion === '2' || self.form.tipo_auxiliar_clasificacion === 2) {
                    if (self.form.proveedor_auxiliar) {
                        self.form.descripcion = self.form.proveedor_auxiliar.nombre_comercial;
                    }
                } else if (self.form.tipo_auxiliar_clasificacion === '3' || self.form.tipo_auxiliar_clasificacion === 3) {
                    if (self.form.pasivo_auxiliar) {
                        self.form.descripcion = self.form.pasivo_auxiliar.nombre_cuenta;
                    }
                } else if (self.form.tipo_auxiliar_clasificacion === '4' || self.form.tipo_auxiliar_clasificacion === 4) {
                    if (self.form.prestamos_auxiliar) {
                        self.form.descripcion = self.form.prestamos_auxiliar.descripcion;
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
                        name: 'auxiliares-cxp'
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
                        name: 'auxiliares-cxp'
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
                        name: 'auxiliares-cxp'
                    })
                }, err => {
                    self.loading = false;
                    self.errorMessages = err;
                    self.btnAction = 'Guardar'
                })
            }
        },
        mounted() {
            this.obtenerTipoNC();
            this.nuevo();
        }
    }
</script>
