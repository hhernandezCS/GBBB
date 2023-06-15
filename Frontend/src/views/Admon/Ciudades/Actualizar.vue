<template>
    <b-card>
        <b-row>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">* Descripción</label>
                    <input type="text" class="form-control" placeholder="Nombre de la ciudad" v-model="form.descripcion">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.descripcion">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name"> Estado:</label>
                    <v-select v-model="form.estado_ciudades"
                              label="descripcion"
                              :options="estados"

                    ></v-select>
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.estado_ciudades">{{ message }}</li>
                        </ul>
                    </b-alert>

                </div>
            </div>
        </b-row>
        <b-card-footer>
            <b-row>
                <div class="col-md-12 col-12 text-lg-right text-center mt-1">
                    <router-link :to="{name: 'admon-ciudades'}">
                        <b-button class="btn btn-default mx-1" variant="secondary">Cancelar</b-button>
                    </router-link>

                    <b-button :disabled="btnAction !== 'Guardar' ? true : false" @click="actualizar"
                              variant="primary">
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
    import {
        BFormDatepicker,
        BRow,
        BCol,
        BCard,
        BCardFooter,
        BPaginationNav,
        BButton,
        VBTooltip,
        BAlert
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import ciudad from '../../../api/admon/ciudades'
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
        data() {
            return {
                loading: true,
                url: loadingImage,   //It is important to import the loading image then use here
                form: {
                    descripcion: '',
                    Estados_pais: '',
                    codigo: ''
                },
                estados: [],
                btnAction: 'Guardar',
                errorMessages: []
            }
        },
        methods: {
            nuevo(){
                var self = this;
                self.loading = false;
                ciudad.nuevo({},data =>{
                    self.estados = data.estados;
                }, err=>{
                    console.log(err);
                })
            },
            obtenerCiudad() {
                var self = this;
                self.loading = true;
                ciudad.obtenerCiudad({
                    id_ciudad: self.$route.params.id_ciudad
                }, data => {
                    self.form = data;
                    self.loading = false;
                }, err => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'BellIcon',
                                text: 'Ha ocurrido un error',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.$router.push({
                        name: 'admon-ciudades'
                    });
                })


            },
            actualizar() {
                var self = this;
                self.loading = true;
                self.btnAction = 'Guardando, espere......';
                estado.actualizar(self.form, data => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ciudad actualizada correctamente',
                                variant: 'success',
                                position: 'bottom-right'
                            }
                        },
                        {
                            position: 'bottom-right'
                        }
                    );
                    this.$router.push({
                        name: 'admon-ciudades'
                    })
                }, err => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'BellIcon',
                                text: 'Ha ocurrido un error',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    self.errorMessages = err
                    self.btnAction = 'Guardar'
                })
            },
        },
        mounted() {
            this.obtenerCiudad();
            this.nuevo();
        }
    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
    @import '~sweetalert2/dist/sweetalert2.css';
</style>

