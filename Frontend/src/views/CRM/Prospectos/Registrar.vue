<template>
    <b-card>
        <form>
            <div class="row">

              <!-- <b-badge
                       class="d-block col-md-12 mb-1"
                       pill
                       variant="primary"
               >
                 Detalles
               </b-badge>
               <div class="col-sm-12">
                   <div class="form-group">
                       <label for="name"><span style="color: red">*</span> Tema lead:</label>
                       <input disabled="true" type="text" class="form-control" v-model="form.descripcion_lead" placeholder="Dígite el tema ">
                       <b-alert variant="danger" show>
                           <ul class="error-messages">
                               <li v-for="message in errorMessages.descripcion_lead">{{ message }}</li>
                           </ul>
                       </b-alert>

                   </div>
               </div>-->
                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"> Origen Lead:</label>
                        <v-select v-model="form.origen"
                                  label="descripcion"
                                  :options="origenes"

                        ></v-select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.origen">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>-->
                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"> Tipo Prospecto:</label>
                        <select v-model="form.extranjero" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                            <option value="1"  >Extranjero</option>
                            <option value="0" >Nacional</option>
                        </select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.origen">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>-->
                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"><span style="color: red">*</span> Giro de negocio:</label>
                        <v-select v-model="form.giro_negocio"
                                  label="descripcion"
                                  :options="giros_negocios"

                        ></v-select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.origen">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>-->
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="paises"><span style="color: red">*</span> Pais:</label>
                        <v-select v-model="form.pais"
                                  label="descripcion"
                                  :options="paises"
                                  @input="obtenerDepartamentos"
                                  id="paises"

                        ></v-select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.estado">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <template v-if="this.form.pais.id_pais === 21"> <!--Mostrar solo para USA-->
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="estados"><span style="color: red">*</span> Estado:</label>
                            <v-select v-model="form.estado"
                                      @input="obtenerCiudad()"
                                      label="descripcion"
                                      :options="estados"
                                      id="estados"

                            ></v-select>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.estado">{{ message }}</li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name"><span style="color: red">*</span> Ciudades:</label>
                            <v-select v-model="form.ciudad"
                                      label="descripcion"
                                      :options="ciudades"

                            ></v-select>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.ciudad">{{ message }}</li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                </template>
                <template v-if="this.form.pais.id_pais === 42"> <!--Mostrar solo para Nicaragua-->
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="departamentos"><span style="color: red">*</span> Departamento:</label>
                            <v-select v-model="form.departamento"
                                      label="descripcion"
                                      :options="departamentos"
                                      id="departamentos"
                                      @input="obtenerMunicipios"

                            ></v-select>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.departamento">{{ message }}</li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="municipios"><span style="color: red">*</span> Municipios:</label>
                            <v-select v-model="form.municipio"
                                      label="descripcion"
                                      :options="municipios"
                                      id="municipios"

                            ></v-select>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.municipios">{{ message }}</li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                </template>

                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"> Código Postal:</label>
                        <input type="text" class="form-control" v-model="form.codigo_postal" placeholder="Dígite el código ">
                    </div>
                </div>-->
                <b-badge
                        class="d-block col-md-12 mb-1 mt-1"
                        pill
                        variant="primary"
                >
                  Información lead
                </b-badge>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"><span style="color: red">*</span> Nombre companía:</label>
                        <input type="text" class="form-control" v-model="form.nombre_compania" placeholder="Dígite un nombre">
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.nombre_compania">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"> Cargo prospecto:</label>
                        <input type="text" class="form-control" v-model="form.cargo" placeholder="Dígite un nombre">
                    </div>
                </div>-->
                <template v-if="this.form.pais.id_pais === 21"> <!--Mostrar solo para USA-->
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"><span class="span">*</span> Teléfono companía:</label>
                        <input type="text" class="form-control" v-mask="form.formatUSA" v-model="form.telefono_compania" placeholder="Dígite un número telefonico">
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.telefono_compania">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>
                </template>
                <template v-else>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name"><span class="span">*</span> Teléfono companía:</label>
                            <input type="text" class="form-control" v-mask="form.formatNCA" v-model="form.telefono_compania" placeholder="Dígite un número telefonico">
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.telefono_compania">{{ message }}</li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                </template>
                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"> Celular de contacto:</label>
                        <input type="text" class="form-control" v-mask="'+1 ### ###-####'" v-model="form.telefono_movil" placeholder="Dígite un número telefonico">
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li v-for="message in errorMessages.telefono_movil">{{ message }}</li>
                            </ul>
                        </b-alert>

                    </div>
                </div>-->
                <!--<div class="col-sm-12">
                    <div class="form-group">
                        <label for="name"> Correo:</label>
                        <input type="text" class="form-control" v-model="form.email_compania" placeholder="Dígite un número telefonico">
                    </div>
                </div>-->
            </div>
        </form>
        <b-card-footer class="content-box-footer align-items-center mt-1">
            <b-row>
                <div class="col-md-6 pl-0">
                    <router-link  :to="{name: 'crm-leads'}">
                        <b-button aria-label="Close" type="submit" variant="secondary" class="mx-1" >
                            Cancelar
                        </b-button>
                    </router-link>
                </div>
                <div class="col-md-6 pl-0">
                    <!--<b-button type="submit" variant="primary" @click="registrar" :disabled="btnAction !== 'Registrar'">
                        {{btnAction}}
                    </b-button>-->
                    <b-dropdown
                            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                            split
                            text="Guardar"
                            variant="primary"
                            right
                            @click="registrar_salir"
                            :disabled="btnAction !== 'Registrar'"
                    >

                        <b-dropdown-item @click="registrar_sin_salir">
                            Guardar y crear nuevo
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
            </b-row>

        </b-card-footer>
      <template v-if="loading">
        <BlockUI>
          <b-spinner variant="info" label="loading..."/>
        </BlockUI>
      </template>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import listado from './Listado'
    import {BButton,BAlert,BFormCheckbox,BFormSelect,BCard,BCardFooter, BRow,BDropdown,BDropdownItem,BDropdownDivider,BBadge,BSpinner} from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import lead from "../../../api/CRM/Leads";
    import origen from "../../../api/CRM/OrigenesProspectos";
    import axios from 'axios'
    import Ripple from 'vue-ripple-directive'
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";

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
            BDropdown,
            BDropdownItem,
            BDropdownDivider,
            BBadge,
            BSpinner
        },
        directives: {
            Ripple,
        },
        data() {
            return {
                loading:false,
                msg: 'Guardando los datos, espere un momento',
                url : loadingImage,   //It is important to import the loading image then use here
                form: {
                    formatUSA: '+1(###)### ####',
                    formatNCA: '+505 #### ####',
                    extranjero: 1,
                    ciudad: '',
                    estado: '',
                    nombre_compania: '',
                    telefono_trabajo:'',
                    origen : '',
                    localidad: '',
                    sublocalidad: '',
                    departamento: '',
                    municipio: '',
                    email_compania: '',
                    datos_lead: '',
                    id_lead: '',
                    pais:[]
                },
                lead: [],
                origenes : [],
                paises: [],
                estados: [],
                ciudades: [],
                departamentos: [],
                municipios:[],
                giros_negocios: [],
                localidades: [],
                sublocalidades: [],
                btnAction: 'Registrar',
                errorMessages: [],

            }
        },
        methods: {

            nuevo(){
              var self = this;
              self.loading = false;
              lead.nuevo({},data =>{
                  self.paises = data.paises;
                  self.origenes = data.origenes;
                  self.estados = data.estados;
                  self.giros_negocios = data.giros_negocios;
                  /*self.leads = data.lead;*/

              }, err=>{
                  console.log(err);
              })
            },
            obtenerDepartamentos(){
              let self = this;
              self.departamentos = null;
              self.municipios = [];
              self.form.departamento = null;
              self.form.municipio = [];
              if(self.form.pais.departamentos){
                self.departamentos = self.form.pais.departamentos;
              }
            },
            obtenerMunicipios(){
              let self = this;
              if(self.form.departamento.municipios_departamento){
                self.municipios = self.form.departamento.municipios_departamento;
              }
            },
            obtenerCiudad(){
              var self = this;
              self.ciudades = null;
              if(self.form.estado.ciudades_estado){
                  self.ciudades = self.form.estado.ciudades_estado;
              }
            },
            obtener() {
                var self = this;
                self.loading = true;
                lead.obtener(
                    self.filter,
                    data => {
                        self.leads = data.rows;
                        self.total = data.total;
                        self.loading = false;
                    },
                    err => {
                        self.loading = false;
                        console.log(err);
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ha ocurrido un problema al cargar los datos',
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        },{
                            position:'bottom-right'
                        })
                    }
                )
            },
            obtenerIdLead(){
                let self = this;
                let lead;
                lead = JSON.parse(sessionStorage.getItem('lead'));
                self.form.datos_lead = lead;
                self.loading = false;
                console.log(lead);
                /*sessionStorage.removeItem('lead');*/
            },

            registrar_salir(){
                var self = this;
                self.btnAction = 'Registrando, espere....'
                self.loading = true;
                lead.registrar(self.form, data =>{
                        self.lead = data.lead;
                        this.$toast({
                                component : ToastificationContent,
                                props: {
                                    title : 'Notificación',
                                    icon : 'CheckIcon',
                                    text : 'Datos registrados correctamente.',
                                    variant : 'success',
                                }
                            },
                            {
                                position : 'bottom-right'
                            });
                            this.$router.push({
                                name : 'crm-leads-actualizar' ,params: {id_lead: self.lead.id_lead}
                            });

                    },
                    err =>{
                        self.loading = false;
                        self.errorMessages = err
                        self.btnAction = 'Registrar'
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
                        /*this.$router.push({
                            name : 'inventario-tipos-proveedores'Fe
                        })*/
                    })
            },

            registrar_sin_salir(){
                var self = this;
                self.btnAction = 'Registrando, espere....';
                self.loading = true;
                lead.registrar(self.form, data =>{
                        self.loading = false;
                        this.$toast({
                                component : ToastificationContent,
                                props: {
                                    title : 'Notificación',
                                    icon : 'CheckIcon',
                                    text : 'Datos registrados correctamente.',
                                    variant : 'success',
                                }
                            },
                            {
                                position : 'bottom-right'
                            });
                            self.form.descripcion_lead = '';
                            self.form.ciudad = '';
                            self.form.estado = '';
                            self.form.nombre = '';
                            self.form.apellido = '';
                            self.form.nombre_compania = '';
                            self.form.telefono_compania ='';
                            self.btnAction = 'Registrar';
                    },
                    err =>{
                        self.loading = false;
                        self.errorMessages = err
                        self.btnAction = 'Registrar'
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
                        /*this.$router.push({
                            name : 'inventario-tipos-proveedores'Fe
                        })*/
                    })
            },


        },
        mounted() {
            this.nuevo();
        }
    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
    .span{
        color: red;
    }
</style>
