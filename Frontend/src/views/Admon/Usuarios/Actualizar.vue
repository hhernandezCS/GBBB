<template>
    <b-card>
        <b-row>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">* Usuario</label>
                    <input type="text" class="form-control" placeholder="Nombre de usuario" v-model="form.name">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.name">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">* Email</label>
                    <input type="text" class="form-control" placeholder="Correo electronico" v-model="form.email">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.email">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">* Rol</label>
                    <v-select
                            v-model="form.rol"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            label="descripcion"
                            :options="roles"
                    />
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.id_rol">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">* Nueva Contraseña</label>
                    <input class="form-control" placeholder="Nueva Contraseña" type="password"
                           v-model="form.password">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.password">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">* Confirmar Nueva Contraseña</label>
                    <input class="form-control" placeholder="Confirmar Nueva Contraseña" type="password"
                           v-model="form.password_confirmation">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.password_confirmation">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
        </b-row>
        <b-card-footer class="content-box-footer text-right mt-1">
            <b-row>
                <div class="col-sm-6 text-left p-0">
                    <template v-if="form.estado">
                        <b-button @click="desactivar(form.id)" type="submit" variant="danger">
                            <feather-icon icon="Trash2Icon"></feather-icon> Eliminar
                        </b-button>
                    </template>
                    <template v-else>
                        <b-button @click="activar(form.id)" type="submit" variant="success">
                            <feather-icon icon="CheckIcon"></feather-icon> Habilitar
                        </b-button>
                    </template>
                </div>

                <b-col cols="12" md="6">
                    <router-link :to="{name: 'admon-usuarios'}">
                        <b-button class="btn btn-default mx-1" variant="secondary">Cancelar</b-button>
                    </router-link>
                    <b-button :disabled="btnAction !== 'Guardar' ? true : false" @click="actualizarUsuario" variant="primary">{{btnAction}}</b-button>
                </b-col>
            </b-row>
        </b-card-footer>
        <template v-if="loading">
            <BlockUI  :url="url"></BlockUI>
        </template>
    </b-card>
</template>
<script type="text/ecmascript-6">
    import {BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, VBTooltip,BAlert} from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import usuarios from '../../../api/admon/usuarios'
    import rol from '../../../api/admon/roles'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import es from 'vuejs-datepicker/dist/locale/translations/es'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";

    export default {
        components: {
            BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BAlert,
            vSelect
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data(){
            return{
                loading:false,
                url: loadingImage,
                roles:[],
                form:{
                    name:'',
                    email:'',
                    password:'',
                    password_confirmation:'',
                    rol:[],
                },
                btnAction: 'Guardar',
                errorMessages:[]
            }
        },
        methods: {
            obtenerTodosRoles() {
                var self = this;
                self.loading = true;
                rol.obtenerTodosRoles(data => {
                    self.roles = data
                    self.loading = false;
                }, err => {
                    self.loading = false;
                    console.log(err)
                })
            },
            actualizarUsuario() {
                var self = this
                self.loading = true;
                self.btnAction = 'Actualizando, espere...';

                if(self.form.password !== '' || self.form.password_confirmation !== ''){
                    self.form.cambiar_contrasena=true;
                }else{
                    self.form.cambiar_contrasena=false;
                }

                usuarios.cambiarContrasena(self.form, data => {
                    this.$toast({
                        component: ToastificationContent,
                        props:{
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Registros actualizado correctamente',
                            variant: 'success',
                            position: 'bottom-right'
                        }
                    });
                    self.btnAction = 'Guardar'
                    this.$router.push({name: 'admon-usuarios'})
                }, err => {
                    self.loading = false;
                    this.$toast({
                        component: ToastificationContent,
                        props:{
                            title: 'Notificación',
                            icon: 'BellIcon',
                            text: 'Ha ocurrido un error, favor revisar sus datos.!',
                            variant: 'warning',
                            position: 'bottom-right'
                        }
                    });
                    self.errorMessages = err
                    self.btnAction = 'Guardar'
                })
            },
            obtenerUsuario() {
                var self = this;
                self.loading = true;
                usuarios.obtenerUsuario({
                    id: this.$route.params.id
                }, data => {
                    self.form = data.usuario;
                    self.roles = data.roles;
                    self.sucursales = data.sucursales;
                    self.form.password= '';
                    self.form.password_confirmation='';
                    self.form.cambiar_contrasena=false;
                    self.loading = false;
                }, err => {
                    //console.log(err)
                    this.$toast({
                        component: ToastificationContent,
                        props:{
                            title: 'Notificación',
                            icon: 'BellIcon',
                            text: err.id[0],
                            variant: 'warning',
                            position: 'bottom-right'
                        }
                    });
                    this.$router.push({
                        name: 'admon-usuarios'
                    });
                })
            },
            desactivar(id) {
                let self = this;
                self.$swal.fire({
                    title: '¿Está seguro de cambiar el estado del usuario?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText:'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        usuarios.desactivar({
                            id: id
                        }, data => {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'El usuario ha sido desactivado correctamente.',
                                        variant: 'success',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });

                            this.$router.push({
                                name: 'admon-usuarios'
                            })
                        }, err => {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'No se ha podido desactivar el usuario.',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                        })
                    }else{
                        self.btnAction = "Guardar";
                    }
                })
            },
            activar(id) {
                let self = this;
                self.$swal.fire({
                    title: '¿Esta seguro de cambiar el estado del usuario?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText:'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        let self = this
                        usuarios.activar({
                            id: id
                        }, data => {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'El usuario ha sido activado correctamente.',
                                        variant: 'success',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            this.$router.push({
                                name: 'admon-usuarios'
                            })
                        }, err => {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'No se ha podido activar el usuario.',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                        })
                    }else{
                        self.btnAction = "Guardar";
                    }
                })
            }
        },
        mounted() {
            this.obtenerUsuario();
        }
    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
</style>
