<template>
    <b-card>
        <b-row>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">* Estado</label>
                    <input type="text" class="form-control" placeholder="Nombre del estado" v-model="form.descripcion">
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.descripcion">{{ message }}</li>
                        </ul>
                    </b-alert>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name"> Pais:</label>
                    <v-select v-model="form.pais"
                              label="descripcion"
                              :options="paises"

                    ></v-select>
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.pais">{{ message }}</li>
                        </ul>
                    </b-alert>

                </div>
            </div>
        </b-row>
        <b-card-footer class="text-right">
            <router-link :to="{name: 'admon-estados'}">
                <b-button class="btn btn-default mx-1" variant="secondary">Cancelar</b-button>
            </router-link>

            <b-button :disabled="btnAction !== 'Registrar' ? true : false" @click="registrar" variant="primary">{{btnAction}}</b-button>
        </b-card-footer>
        <template v-if="loading">
            <BlockUI  :url="url"></BlockUI>
        </template>
    </b-card>
</template>
<script type="text/ecmascript-6">
    import {BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, VBTooltip,BAlert} from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import estado from '../../../api/admon/estados'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import es from 'vuejs-datepicker/dist/locale/translations/es'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import lead from "../../../api/CRM/Leads";

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
                url : loadingImage,   //It is important to import the loading image then use here
                form: {
                    descripcion: '',
                    codigo: '',
                },
                paises:[],
                btnAction: 'Registrar',
                errorMessages: []
            }
        },
        methods:{
            nuevo(){
                var self = this;
                self.loading = false;
                estado.nuevo({},data =>{
                    self.paises = data.paises;
                }, err=>{
                    console.log(err);
                })
            },
            registrar() {
                var self = this
                self.btnAction = 'Registrando, espere....'
                self.loading = true;
                estado.registrar(self.form, data => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props:{
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Estado registrado correctamente',
                                variant: 'success',
                                position: 'bottom-right'
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.$router.push({
                        name: 'admon-estados'
                    })
                }, err => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props:{
                                title: 'Notificación',
                                icon: 'BellIcon',
                                text: 'Ha ocurrido un error',
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
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
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
</style>

