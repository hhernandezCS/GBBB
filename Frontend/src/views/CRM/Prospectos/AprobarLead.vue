<template>
    <b-container fluid>
        <b-row class="justify-content-center">
            <b-card class="col-12 pt-3">
                <validation-observer ref="AprobarValidations">
                    <div class="d-flex justify-content-center text-center">
                        <b-card border-variant="primary" header="Elija la opción de registro"
                                header-border-variant="primary">

                            <b-form-group class="pb-3" v-slot="{ ariaDescribedby }">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Opciones de registro"
                                        rules="required"
                                >
                                <b-form-radio-group class="py-3" v-model="selected" :options="options" :class="errors.length > 0 ? 'is-invalid':null"
                                                    @change="imprimirItem(selected)" :aria-describedby="ariaDescribedby"
                                ></b-form-radio-group>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-card>
                    </div>
                    <b-card-footer class="content-box-footer text-right mt-1">
                        <b-row class="justify-content-end">
                            <div class="col-sm-8 text-lg-right">
                                <router-link :to="{name: 'crm-leads'}">
                                    <b-button type="submit" variant="secondary" class="mt-1 mr-1">
                                        Cancelar
                                    </b-button>
                                </router-link>
                                <b-button class="mt-1" type="submit" variant="primary" @click="registrar"
                                          :disabled="btnAction !== 'Registrar'">
                                    {{btnAction}}
                                </b-button>
                            </div>
                        </b-row>
                    </b-card-footer>
                </validation-observer>
            </b-card>
            <!--Gif de pantalla de carga-->
            <template v-if="loading">
                <BlockUI>
                    <b-spinner label="cargando..." variant="info"/>
                </BlockUI>
            </template>
        </b-row>
    </b-container>
</template>
<script>
    import {
        BAlert,
        BButton,
        BButtonGroup,
        BCard,
        BCardFooter,
        BContainer,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BPagination,
        BPaginationNav,
        BRow,
        BSpinner,
        BTable,
        VBTooltip,
        BBadge,
        BFormRadio,
        BFormRadioGroup
    } from 'bootstrap-vue'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import vSelect from 'vue-select'
    import lead from "../../../api/CRM/Leads";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import axios from "axios";
    import {ValidationObserver, ValidationProvider} from 'vee-validate';
    import {mimes, required} from '@validations';

    export default {
        components: {
            BCard,
            BCardFooter,
            BPaginationNav,
            BFormCheckbox,
            BFormGroup,
            vSelect,
            BRow,
            BButton,
            BFormCheckboxGroup,
            BFormDatepicker,
            BAlert,
            BFormSelect,
            BContainer,
            BFormInput,
            BButtonGroup,
            BSpinner,
            BTable,
            BPagination,
            ValidationProvider,
            ValidationObserver,
            BBadge,
            BFormRadio,
            BFormRadioGroup
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data() {
            return {
                // Datos necesarios para la paginacion de registros
                loading: false,
                descargando: false,
                url: loadingImage,
                selected: '',
                options: [
                    {text: 'Registrar Contacto', value: 'radio1'},
                    {text: 'Registrar Compañía', value: 'radio2'},
                    {text: 'Registrar Contacto y Compañía', value: 'radio3'},
                ],
                errorMessages: [],
                btnAction: 'Registrar',
            }
        },
        methods: {
            imprimirItem(selected) {
                let self = this;
                if (selected === 'radio1') {
                    console.log(selected + ' opcion 1 ' + self.$route.params.id_lead);
                    // self.crearContacto(self.$route.params.id_lead);
                }
                if (selected === 'radio2') {
                    console.log(selected + 'opcion 2');
                }
                if (selected === 'radio3') {
                    console.log(selected + 'opcion 3');
                }
            },
            registrar() {
                this.$refs.AprobarValidations.validate().then(succes => {
                    const self = this;
                    if (self.selected === 'radio1') {
                        self.crearContacto(self.$route.params.id_lead);
                    }
                    if (self.selected === 'radio2') {
                        self.crearCompania(self.$route.params.id_lead);
                    }
                    if (self.selected === 'radio3') {
                        self.crearContactoyCompania(self.$route.params.id_lead);
                    }
                });
            },
            crearContacto(IdLead) {
                const self = this;
                self.$swal({
                    title: '¿Desea crear contacto?',
                    text: 'Este proceso es irreversible, por favor verificar antes de hacerlo.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger ml-1',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.value) {
                        lead.registroContacto({
                            id_lead: IdLead
                        }, data => {
                            self.$swal({
                                icon: 'success',
                                title: '¡Creado!',
                                text: '¡Contacto creado!',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            });
                            this.$router.push({
                                name: 'crm-leads'
                            });
                        }, err => {
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: err.result,
                                    variant: 'warning',
                                    position: 'bottom-right'
                                }
                            });
                            self.$swal({
                                title: 'Cancelado',
                                text: 'El contacto no ha podido ser creado',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                },
                            })
                        })
                    } else {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El contacto no ha sido creado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            }
            ,
            crearCompania(IdLead) {
                const self = this;
                self.$swal({
                    title: '¿Desea crear compañia?',
                    text: 'Este proceso es irreversible, por favor verificar antes de hacerlo.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger ml-1',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.value) {
                        lead.registroCompania({
                            id_lead: IdLead
                        }, data => {
                            self.$swal({
                                icon: 'success',
                                title: '¡Creado!',
                                text: '¡Compañia creada!',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            });
                            this.$router.push({
                                name: 'crm-leads'
                            });
                        }, err => {
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: err.result,
                                    variant: 'warning',
                                    position: 'bottom-right'
                                }
                            });
                            self.$swal({
                                title: 'Cancelado',
                                text: 'La compañia no pudo ser creada',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                },
                            })
                        })
                    } else {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'La compañia no ha sido creada',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            }
            ,
            crearContactoyCompania(IdLead) {
                const self = this;
                self.$swal({
                    title: '¿Desea crear contacto y compañia?',
                    text: 'Este proceso es irreversible, por favor verificar antes de hacerlo.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger ml-1',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.value) {
                        lead.registroContactoyCompania({
                            id_lead: IdLead
                        }, data => {
                            self.$swal({
                                icon: 'success',
                                title: '¡Creado!',
                                text: '¡Contacto y Compañia creada!',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            });
                            this.$router.push({
                                name: 'crm-leads'
                            });
                        }, err => {
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: err.result,
                                    variant: 'warning',
                                    position: 'bottom-right'
                                }
                            });
                            self.$swal({
                                title: 'Cancelado',
                                text: 'El contacto y compañia no pudo ser creados',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                },
                            })
                        })
                    } else {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El contacto y compañia no pudo ser creados no han sido creados',
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
            // this.imprimirItem();
        }
    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
</style>
