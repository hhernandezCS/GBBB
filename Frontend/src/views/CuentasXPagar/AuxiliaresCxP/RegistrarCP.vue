<template>
    <div class="main">
        <div class="row">
            <div class="col-md-12">
                <div class="content-box">
                    <div class="content-box-header">
                        <div class="box-title">Crear otras cuentas por cobrar</div>
                        <div class="box-description">Formulario para registrar otras cuentas por cobrar</div>
                    </div>
                    <form>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tipo de auxiliar</label>
                                    <select class="form-control" v-model="form.tipo_auxiliar_select"
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

                            <div class="col-sm-4" v-if="form.tipo_auxiliar_select === '1'">

                                <div class="form-group">
                                    <label for>Proveedores</label>
                                    <multiselect :allow-empty="false" :options="proveedores"
                                                 :searchable="true"
                                                 :show-labels="false"
                                                 deselect-label="No se puede eliminar este valor"
                                                 label="nombre_comercial"
                                                 placeholder="Selecciona un proveedor"
                                                 ref="trabajador"
                                                 track-by="id_trabajador"
                                                 v-model="form.proveedorx"
                                                 v-on:input="getCode()"
                                    ></multiselect>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.proveedorx"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4" v-else-if="form.tipo_auxiliar_select === '2'">

                                <div class="form-group">
                                    <label for>Acreedores</label>
                                    <multiselect :allow-empty="false" :options="acreedores"
                                                 :searchable="true"
                                                 :show-labels="false"
                                                 deselect-label="No se puede eliminar este valor"
                                                 label="nombre_comercial"
                                                 placeholder="Selecciona un acreedor"
                                                 ref="deudor"
                                                 track-by="id_cliente"
                                                 v-model="form.acreedorx"
                                                 v-on:input="getCode()"
                                    ></multiselect>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.acreedorx"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4" v-else-if="form.tipo_auxiliar_select === '3'">

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
                                                 v-model="form.pasivox"
                                                 v-on:input="getCode()"
                                    ></multiselect>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.acreedorx"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4" v-else>

                                <div class="form-group">
                                    <label for>Prestamos bancarios y otros</label>
                                    <multiselect :allow-empty="false" :options="prestamos_bancarios"
                                                 :searchable="true"
                                                 :show-labels="false"
                                                 deselect-label="No se puede eliminar este valor"
                                                 label="descripcion"
                                                 placeholder="Selecciona un banco"
                                                 ref="prestamo"
                                                 track-by="id_banco"
                                                 v-model="form.prestamosx"
                                                 v-on:input="getCode()"
                                    ></multiselect>
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.acreedorx"
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
                    <div class="content-box-footer text-right">
                        <router-link tag="button" :to="{ name: 'auxiliares-cxp' }">
                            <button type="button" class="btn btn-default">Cancelar</button>
                        </router-link>
                        <button :disabled="btnAction !== 'Registrar' ? true : false" @click="registrar"
                                class="btn btn-primary" type="button">{{ btnAction }}
                        </button>
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
    import auxiliar from '../../api/auxiliares_cxp'
    import loadingImage from "../../assets/images/block50.gif";

    export default {
        data() {
            return {
                loading: true,
                msg: 'Cargando los datos, espere un momento',
                url: '/public' + loadingImage,   //It is important to import the loading image then use here
                //cuentas_contables: [],
                form: {
                    tipo_auxiliar: '',
                    tipo_auxiliar_select: '1',
                    codigo: '',
                    descripcion: '',
                    next_code: '',
                    proveedorx: [],
                    acreedorx: [],
                    pasivox: [],
                    prestamosx: [],
                },
                acreedores: [],
                proveedores: [],
                pasivos_laborales: [],
                prestamos_bancarios: [],
                btnAction: 'Registrar',
                errorMessages: []
            }
        },
        computed: {
            concatCode() {
                const self = this;

                if (self.form.tipo_auxiliar_select === '1') {
                    if (self.form.proveedorx && self.form.tipo_auxiliar !== '') {
                        self.form.codigo = self.form.tipo_auxiliar + self.form.proveedorx.extCodigo;
                    } else {
                        self.form.codigo = 'No disponible';
                    }
                } else if (self.form.tipo_auxiliar_select === '2') {
                    if (self.form.acreedorx && self.form.tipo_auxiliar !== '') {
                        self.form.codigo = self.form.tipo_auxiliar + self.form.acreedorx.extCodigo;
                    } else {
                        self.form.codigo = 'No disponible';
                    }
                } else if (self.form.tipo_auxiliar_select === '3') {
                    if (self.form.pasivox && self.form.tipo_auxiliar !== '') {
                        self.form.codigo = self.form.tipo_auxiliar + self.form.pasivox.extCodigo;
                    } else {
                        self.form.codigo = 'No disponible';
                    }
                } else if (self.form.tipo_auxiliar_select === '4') {
                    if (self.form.prestamosx && self.form.tipo_auxiliar !== '') {
                        self.form.codigo = self.form.tipo_auxiliar + self.form.prestamosx.extCodigo;
                    } else {
                        self.form.codigo = 'No disponible';
                    }
                }
            }
        },
        methods: {
            limpiar() {
                const self = this;
                self.form.descripion = '';
                self.form.tipo_auxiliar = '';
                self.form.codigo = '';
                self.form.proveedorx = [];
                self.form.acreedorx = [];
                self.form.pasivox = [];
                self.form.prestamosx = [];
            },
            getCode() {
                const self = this;
                if (self.form.tipo_auxiliar_select === '1') {
                    if (self.form.proveedorx) {
                        self.form.descripcion = self.form.proveedorx.nombre_comercial
                    }
                } else if (self.form.tipo_auxiliar_select === '2') {
                    if (self.form.acreedorx) {
                        self.form.descripcion = self.form.acreedorx.nombre_comercial;
                    }
                } else if (self.form.tipo_auxiliar_select === '3') {
                    if (self.form.pasivox) {
                        self.form.descripcion = self.form.pasivox.nombre_cuenta;
                    }
                } else if (self.form.tipo_auxiliar_select === '4') {
                    if (self.form.prestamosx) {
                        self.form.descripcion = self.form.prestamosx.descripcion;
                    }
                }
            },
            nuevo() {
                var self = this;
                self.loading = true;
                auxiliar.nuevo({}, data => {
                        self.form.next_code = data.next_code;
                        self.form.codigo = self.form.next_code;

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
            registrar() {
                var self = this;
                self.loading = true;
                self.btnAction = 'Registrando, espere....'

                auxiliar.registrar(self.form, data => {
                    this.$router.push({
                        name: 'auxiliares-cxp'
                    })
                    self.loading = false;
                }, err => {
                    self.loading = false;
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
