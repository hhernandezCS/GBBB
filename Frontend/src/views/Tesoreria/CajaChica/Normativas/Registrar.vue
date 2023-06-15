<template>
    <b-card>
        <div class="main">
            <div class="row">
                <div class="col-md-12">
                    <div class="content-box">
                        <form>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for=""> Concepto</label>
                                        <input class="form-control" placeholder="Dígite un concepto"
                                               v-model="form.concepto">
                                        <ul class="error-messages">
                                            <li :key="message" v-for="message in errorMessages.concepto"
                                                v-text="message"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for=""> Monto mínimo</label>
                                        <input class="form-control" type="number" placeholder="Dígite un monto minimo"
                                               v-model="form.monto_minimo">
                                        <ul class="error-messages">
                                            <li :key="message" v-for="message in errorMessages.monto_minimo"
                                                v-text="message"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for=""> Monto máximo</label>
                                        <input class="form-control" type="number" placeholder="Dígite un monto maximo"
                                               v-model="form.monto_maximo">
                                        <ul class="error-messages">
                                            <li :key="message" v-for="message in errorMessages.monto_maximo"
                                                v-text="message"></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for=""> Regulación de tiempo</label>
                                        <input class="form-control"
                                               placeholder="Describir regulación de tiempo para la normativa"
                                               v-model="form.tiempo">
                                        <ul class="error-messages">
                                            <li :key="message" v-for="message in errorMessages.tiempo"
                                                v-text="message"></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <b-card-footer class="content-box-footer text-right mt-1">
                            <div class="content-box-footer text-right">
                                <router-link :to="{name: 'cajachica-normativas'}">
                                    <b-button type="submit" variant="secondary" class="mt-1 mr-1">
                                        Cancelar
                                    </b-button>
                                </router-link>
                                <b-button class="mt-1" type="submit" variant="primary" @click="registrar"
                                          :disabled="btnAction !== 'Registrar'">
                                    {{btnAction}}
                                </b-button>
                            </div>
                        </b-card-footer>
                        <template v-if="loading">
                            <BlockUI :message="msg" :url="url"></BlockUI>
                        </template>
                    </div>
                </div>
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
    } from 'bootstrap-vue'
    import vSelect from "vue-select";
    import {ValidationObserver, ValidationProvider} from "vee-validate";
    import Ripple from "vue-ripple-directive";
    import ToastificationContent from "../../../../@core/components/toastification/ToastificationContent";

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
                loading: false,
                msg: 'Guardando los datos, espere un momento',
                url: '/public' + loadingImage,   //It is important to import the loading image then use here
                form: {
                    concepto: '',
                    monto_maximo: 0,
                    monto_minimo: 0,
                    tiempo: '',
                },
                btnAction: 'Registrar',
                errorMessages: []
            }
        },
        methods: {
            registrar() {
                var self = this
                self.btnAction = 'Registrando, espere....'
                self.loading = true;
                normativa.registrar(self.form, data => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Datos registrados correctamente.',
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
                    self.btnAction = 'Registrar';
                })
            }
        }
    }
</script>