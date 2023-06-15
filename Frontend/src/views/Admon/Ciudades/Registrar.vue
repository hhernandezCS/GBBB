<template>
    <b-card>
        <b-row>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">* Ciudad</label>
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
                    <v-select v-model="form.estado"
                              label="descripcion"
                              :options="estados"

                    ></v-select>
                    <b-alert variant="danger" show>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.estado">{{ message }}</li>
                        </ul>
                    </b-alert>

                </div>
            </div>
        </b-row>
        <b-card-footer class="text-right">
            <router-link :to="{name: 'admon-ciudades'}">
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
    import ciudad from '../../../api/admon/ciudades'
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
                estados:[],
                btnAction: 'Registrar',
                errorMessages: []
            }
        },
        methods:{
            nuevo(){
                var self = this;
                self.loading = false;
                ciudad.nuevo({},data =>{
                    self.estados = data.estados;
                }, err=>{
                    console.log(err);
                })
            },
            registrar() {
                var self = this
                self.btnAction = 'Registrando, espere....'
                self.loading = true;
                ciudad.registrar(self.form, data => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props:{
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ciudad registrado correctamente',
                                variant: 'success',
                                position: 'bottom-right'
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.$router.push({
                        name: 'admon-ciudades'
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

