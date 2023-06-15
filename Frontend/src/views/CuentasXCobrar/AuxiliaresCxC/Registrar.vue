<template>
    <b-card>
        <b-row>
            <b-col class="sm-text-center" cols="12" md="12">
                <form>
                    <b-row>
                        <b-col cols="12" md="4">
                            <div class="form-group">
                                <label for="">Tipo de auxiliar</label>
                                <select class="form-control" v-model="form.tipo_auxiliar_select"
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
                                <b-form-input class="form-control" placeholder="Dígite descripción"
                                       v-model="form.tipo_auxiliar"/>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.descripcion"
                                        v-text="message"></li>
                                </ul>
                            </div>
                        </b-col>

                        <b-col cols="12" md="4" v-if="form.tipo_auxiliar_select === '1'">
                            <div class="form-group">
                                <label for>Funcionarios y empleados</label>
                                <v-select :options="trabajadores"
                                             :searchable="true"
                                             :show-labels="false"
                                             deselect-label="No se puede eliminar este valor"
                                             label="nombre_completo"
                                             placeholder="Selecciona un empleado"
                                             ref="trabajador"
                                             track-by="id_trabajador"
                                             v-model="form.trabajadorx"
                                             v-on:input="getCode()"
                                ></v-select>
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.trabajadorx"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </div>
                        </b-col>
                        <b-col cols="12" md="4" v-else>
                            <div class="form-group">
                                <label for>Deudores diversos</label>
                                <v-select :allow-empty="false" :options="deudores"
                                             :searchable="true"
                                             :show-labels="false"
                                             deselect-label="No se puede eliminar este valor"
                                             label="nombre_completo"
                                             placeholder="Selecciona un deudor"
                                             ref="deudor"
                                             track-by="id_cliente"
                                             v-model="form.deudorx"
                                             v-on:input="getCode()"
                                ></v-select>
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
                                <b-form-input class="form-control" placeholder="Dígite descripción"
                                       v-model="form.descripcion"/>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.descripcion"
                                        v-text="message"></li>
                                </ul>
                            </div>
                        </b-col>
                        <b-col cols="12" md="2">
                            <div class="form-group">
                                <label> Código {{concatCode}}</label>
                                <b-form-input class="form-control" placeholder="Dígite descripción" v-model="form.codigo"/>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.descripcion"
                                        v-text="message"></li>
                                </ul>
                            </div>
                        </b-col>
                    </b-row>
                </form>
                <b-card-footer class="content-box-footer text-right mt-1">
                    <b-row>
                        <div class="col-sm-12 text-lg-right">
                            <router-link  :to="{name: 'cxc-cat-otras'}">
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
                <template v-if="loading">
                    <BlockUI :message="msg" :url="url"></BlockUI>
                </template>
            </b-col>
        </b-row>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import auxiliar from '../../../api/CuentasXCobrar/auxiliares_cxc'
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
        BFormInput
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
            BFormInput
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data() {
            return {
                loading: true,
                msg: 'Cargando los datos, espere un momento',
                url: '/public' + loadingImage,   //It is important to import the loading image then use here
                cuentas_contables: [],
                form: {
                    tipo_auxiliar: '',
                    tipo_auxiliar_select: '1',
                    codigo: '',
                    descripcion: '',
                    next_code: '',
                    trabajadorx: [],
                    deudorx: [],
                },
                deudores: [],
                trabajadores: [],
                btnAction: 'Registrar',
                errorMessages: []
            }
        },
        computed: {
            concatCode() {
                const self = this;

                if (self.form.tipo_auxiliar_select === '1') {
                    if (self.form.trabajadorx && self.form.tipo_auxiliar !== '') {
                        self.form.codigo = self.form.tipo_auxiliar + self.form.trabajadorx.simplify_code;
                    }
                    else{self.form.codigo = 'No disponible';}
                } else {
                    if (self.form.deudorx && self.form.tipo_auxiliar !== '') {
						self.form.codigo = self.form.tipo_auxiliar + self.form.deudorx.simplify_code;
                    }
					else{self.form.codigo = 'No disponible';}
                }
            }
        },
        methods: {
            limpiar() {
                const self = this;
                self.form.descripion = '';
                self.form.tipo_auxiliar = '';
                self.form.codigo = '';
                self.form.trabajadorx = [];
                self.form.deudorx = [];
            },
            getCode() {
                const self = this;
                if (self.form.tipo_auxiliar_select === '1') {
                    if (self.form.trabajadorx) {
                        self.form.descripcion = self.form.trabajadorx.nombre_completo
                    }
                } else {
                    if (self.form.deudorx) {
                        self.form.descripcion = self.form.deudorx.nombre_completo;
                    }
                }
            },
            nuevo() {
                var self = this;
                self.loading = true;
                auxiliar.nuevo({}, data => {
                        self.form.next_code = data.next_code;
                        self.form.codigo = self.form.next_code;

                        self.trabajadores = data.trabajadores;
                        self.deudores = data.deudores;
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
                        name: 'cxc-cat-otras'
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
