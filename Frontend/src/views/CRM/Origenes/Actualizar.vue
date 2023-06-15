<template>
    <b-card>
        <form>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name">* Nombre Origen:</label>
                        <input type="text" class="form-control" v-model="form.descripcion" placeholder="Dígite el origen">
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.descripcion">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>

            </div>
        </form>
        <b-card-footer class="content-box-footer text-right mt-1">

            <b-row>
                <div class="col-md-6 text-left p-0">
                    <template v-if="form.estado">
                        <b-button type="submit" variant="danger" @click="desactivar(form.id_origen_lead)">
                            <feather-icon icon="Trash2Icon"></feather-icon> Eliminar
                        </b-button>
                    </template>
                    <template v-else>
                        <b-button type="submit" variant="success" @click="activar(form.id_origen_lead)">
                            <feather-icon icon="CheckIcon"></feather-icon> Habilitar
                        </b-button>
                    </template>

                </div>
                <div class="col-md-6">
                    <router-link  :to="{name: 'crm-origenes'}">
                        <b-button type="submit" variant="secondary" class="mx-1">
                            Cancelar
                        </b-button>
                    </router-link>


                    <b-button type="submit" variant="primary" @click="actualizar" :disabled="btnAction !== 'Actualizar'">
                        {{btnAction}}
                    </b-button>
                </div>
            </b-row>

        </b-card-footer>
        <template v-if="loading">
            <BlockUI :url="url"></BlockUI>
        </template>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import {BButton,BAlert,BFormCheckbox,BFormSelect,BCard,BCardFooter, BRow} from 'bootstrap-vue'
    import Ripple from 'vue-ripple-directive'
    import vSelect from 'vue-select'
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import origen from "../../../api/CRM/OrigenesProspectos";
    import axios from 'axios'

    export default {
        components:{
            BButton,
            BAlert,
            BFormCheckbox,
            vSelect,
            BFormSelect,
            BCard,
            BCardFooter,
            BRow,
        },
        directives : {
            Ripple,
        },
        data() {
            return {
                loading:false,
                msg: 'Guardando los datos, espere un momento',
                url : loadingImage,   //It is important to import the loading image then use here
                form: {
                    descripcion : ''

                },
                btnAction: 'Actualizar',
                errorMessages: [],

            }
        },
        methods: {

            obtenerOrigen(){
                var self = this;
                origen.obtenerOrigenLead({
                    id_origen_lead: this.$route.params.id_origen_lead
                }, data =>{
                    self.form = data;
                    self.loading = false;
                }, err =>{
                    this.$toast({
                            component : ToastificationContent,
                            props : {
                                title : 'Notificación',
                                icon : 'InfoIcon',
                                text : 'Ha ocurrido un error al cargar los datos',
                                variant : 'warning',
                            }
                        },
                        {
                            position : 'bottom-rigth'
                        });
                    this.$router.push({
                        name : 'crm-origenes'
                    })
                })
            },

            actualizar(){
                var self = this;
                self.btnAction = 'Guardando, espere....'
                self.loading = true;
                origen.actualizar(self.form, data =>{
                        self.loading = false;
                        this.$toast({
                                component : ToastificationContent,
                                props: {
                                    title : 'Notificación',
                                    icon : 'CheckIcon',
                                    text : 'Datos actualizados correctamente.',
                                    variant : 'success',
                                }
                            },
                            {
                                position : 'bottom-right'
                            });
                        this.$router.push({
                            name : 'crm-origenes'
                        })
                    },
                    err =>{
                        self.loading = false;
                        self.errorMessages = err
                        self.btnAction = 'Guardar'
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title : 'Notificación',
                                icon : 'InfoIcon',
                                text : 'Ha ocurrido un error, intentelo de nuevo',
                                variant : 'warning',
                                position : 'bottom-right'
                            }
                        });
                        /*this.$router.push({
                            name : 'inventario-unidades-medida'
                        })*/
                    })
            },

            activar(origenID){
                var self = this;
                self.$swal({
                    title: '¿Esta seguro?',
                    text: '¿ Desea activar el origen?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText : 'Si, confirmar',
                    cancelButtonText : 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton : 'btn btn-danger ml-1',
                    },
                    buttonsStyling : false,
                }).then((result) => {
                    if (result.value){
                        self.$swal({
                            icon :'success',
                            title : '¡Habilitado!',
                            text : '¡Origen habilitado!',
                            customClass: {
                                confirmButton : 'btn btn-success',
                            }
                        });
                        origen.activar({
                            id_origen_lead: origenID
                        }, data =>{
                            this.$router.push({
                                name : 'crm-origenes'
                            })
                        }, err =>{
                            console.log(err);
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title : 'Notificación',
                                        icon : 'InfoIcon',
                                        text : 'Ha ocurrido un error, intentelo de nuevo',
                                        variant : 'warning',
                                    }
                                },
                                {
                                    position : 'bottom-right'
                                });
                            this.$router.push({
                                name : 'crm-origenes'
                            })
                        })
                    }else if (result.dismiss === 'cancel'){
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El origen no ha sido habilitada',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            },

            desactivar(origenID){
                var self = this;
                self.$swal({
                    title: 'Info!',
                    text: '¿Esta seguro de desactivar el origen?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText : 'Si, confirmar',
                    cancelButtonText : 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton : 'btn btn-danger ml-1',
                    },
                    buttonsStyling : false,
                }).then((result) => {
                    if (result.value){
                        self.$swal({
                            icon :'success',
                            title : '¡Eliminado!',
                            text : '¡Origen eliminado!',
                            customClass: {
                                confirmButton : 'btn btn-success',
                            }
                        });
                        origen.desactivar({id_origen_lead: origenID
                            },
                            data =>{
                                this.$router.push({
                                    name : 'crm-origenes'
                                })
                            }, err =>{
                                console.log(err);
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title : 'Notificación',
                                            icon : 'InfoIcon',
                                            text : 'Ha ocurrido un error, intentelo de nuevo',
                                            variant : 'warning',
                                        }
                                    },
                                    {
                                        position : 'bottom-right'
                                    });
                                this.$router.push({
                                    name : 'crm-origenes'
                                })
                            })
                    }else if (result.dismiss === 'cancel'){
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El origen no ha sido eliminado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }
                })
            },

        },
        mounted() {
            this.obtenerOrigen();
        }

    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
    @import '../../../@core/scss/vue/libs/vue-sweetalert';
</style>
