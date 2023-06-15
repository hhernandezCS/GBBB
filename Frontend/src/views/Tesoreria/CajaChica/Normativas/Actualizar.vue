<template>
    <b-card>
        <div class="main">
            <div class="content-box">
                <form>
                    <div class="form-group">
                        <label for=""> Concepto</label>
                        <input class="form-control" placeholder="Dígite un concepto" v-model="form.concepto">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.concepto" v-text="message"></li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for=""> Monto mínimo C$</label>
                        <input class="form-control" type="number" placeholder="Dígite un monto minimo"
                               v-model="form.monto_minimo">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.monto_minimo" v-text="message"></li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for=""> Monto máximo C$</label>
                        <input class="form-control" type="number" placeholder="Dígite un monto maximo"
                               v-model="form.monto_maximo">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.monto_maximo" v-text="message"></li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for=""> Regulación de tiempo</label>
                        <input class="form-control" placeholder="Dígite un tiempo especifico" v-model="form.tiempo">
                        <ul class="error-messages">
                            <li :key="message" v-for="message in errorMessages.tiempo" v-text="message"></li>
                        </ul>
                    </div>
                </form>

                <b-card-footer class="content-box-footer text-right mt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="content-box-footer text-left">
                                <template v-if="form.estado">
                                    <button @click="desactivar(form.id_normativa)" class="btn btn-danger">
                                        <feather-icon icon="Trash2Icon"></feather-icon>
                                        Eliminar
                                    </button>
                                </template>
                                <template v-else>
                                    <button @click="activar(form.id_normativa)" class="btn btn-success">
                                        <feather-icon icon="CheckIcon"></feather-icon>
                                        Habilitar
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="content-box-footer text-right">
                                <router-link :to="{ name: 'cajachica-normativas' }">
                                    <b-button type="submit" variant="secondary" class="mt-1 mr-1">
                                        Cancelar
                                    </b-button>
                                </router-link>
                                <b-button :disabled="btnAction !== 'Guardar'" class="mt-1" type="submit"
                                          variant="primary" @click="actualizar">
                                    {{ btnAction }}
                                </b-button>
                            </div>
                        </div>
                    </div>
                </b-card-footer>
                <template v-if="loading">
                    <BlockUI>
                        <b-spinner variant="info" label="loading..."/>
                    </BlockUI>
                </template>
            </div>
        </div>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import normativa from '../../../../api/Tesoreria/normativas';
    import loadingImage from '../../../../assets/images/loader/block50.gif'
    import {
        BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow, BSpinner,
        BModal, VBToggle, VBTooltip, BFormGroup, BListGroup, BListGroupItem, BDropdown, VBModal,
        BDropdownDivider, BDropdownItem, BFormInput
    } from 'bootstrap-vue';
    import vSelect from "vue-select";
    import {ValidationObserver, ValidationProvider} from "vee-validate";
    import Ripple from "vue-ripple-directive";
    import ToastificationContent from "../../../../@core/components/toastification/ToastificationContent";
    import contacto from "../../../../api/CRM/Contactos";

    export default {
        components: {
            BButton,
            BAlert,
            BFormCheckbox,
            vSelect,
            BFormSelect,
            BCard,
            BCardFooter,
            BRow,
            BSpinner,
            BModal,
            VBTooltip,
            VBToggle,
            BFormGroup,
            BListGroup,
            BListGroupItem,
            BDropdown,
            BDropdownItem,
            BDropdownDivider,
            BFormInput,
            ValidationProvider,
            ValidationObserver,
        },
        directives: {
            Ripple,
            'b-tooltip': VBTooltip,
            'b-toggle': VBToggle,
            'b-modal': VBModal
        },
        data() {
            return {
                loading: true,
                msg: 'Cargando contenido, espere un momento',
                url: loadingImage,   //It is important to import the loading image then use here
                form: {
                    concepto: '',
                    monto_maximo: 0,
                    monto_minimo: 0,
                    tiempo: '',
                },
                btnAction: 'Guardar',
                errorMessages: []
            }
        },
        methods: {
            obtenerNormativa() {
                var self = this
                //Array(1,2,3).includes(Number(self.$route.params.id_zona)) ? self.SoloLectura = true : self.SoloLectura = false
                normativa.obtenerNormativa({
                    id_normativa: this.$route.params.id_normativa
                }, data => {
                    self.form = data;
                    self.loading = false;
                }, err => {
                    alertify.error(err.id_normativa[0], 5);
                    this.$router.push({
                        name: 'cajachica-normativas'
                    });
                })
            },
            actualizar() {
                var self = this
                self.btnAction = 'Guardando, espere......';
                self.loading = true;
                normativa.actualizar(self.form, data => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Datos actualizados correctamente.',
                                variant: 'success',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.$router.push({
                        name: 'cajachica-normativas'
                    })
                }, err => {
                    self.loading = false;
                    self.errorMessages = err;
                    self.btnAction = 'Guardar';
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ha ocurrido un error, intentelo de nuevo',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                })
            },
            desactivar(zonaId) {
                var self = this;
                self.$swal.fire({
                    title: 'Esta seguro de cambiar el estado?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        self.$swal({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: '¡Normativa eliminada!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        normativa.desactivar({
                                id_normativa: zonaId
                            },
                            data => {
                                this.$router.push({
                                    name: 'cajachica-normativas'
                                });
                            }, err => {
                                console.log(err);
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Ha ocurrido un error, intentelo de nuevo',
                                            variant: 'warning',
                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                                this.$router.push({
                                    name: 'cajachica-normativas'
                                });
                            })
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El contacto no ha sido eliminado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }
                })
            },
            activar(zonaId) {
                var self = this;
                self.$swal.fire({
                    title: 'Esta seguro de cambiar el estado?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        self.$swal({
                            icon: 'success',
                            title: '¡Habilitado!',
                            text: '¡Normativa habilitada!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        normativa.activar({
                            id_normativa: zonaId
                        }, data => {
                            this.$router.push({
                                name: 'cajachica-normativas'
                            })
                        }, err => {
                            console.log(err);
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Ha ocurrido un error, intentelo de nuevo',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            this.$router.push({
                                name: 'cajachica-normativas'
                            })
                        })
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'La normativa no ha sido habilitado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            }
        },
        mounted() {
            this.obtenerNormativa()
        }
    }
</script>