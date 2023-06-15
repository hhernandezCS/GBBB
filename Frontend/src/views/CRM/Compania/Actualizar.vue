<template>
    <b-card>
        <validation-observer ref="companiaValidations">
            <form>
                <div class="row">
                    <div class="col-sm-4">

                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Nombre de la compañia"
                                    rules="required"
                            >
                                <label for="nombreCompania"><span style="color: red">*</span> Nombre Compañia:</label>
                                <input id="nombreCompania" :class="errors.length > 0 ? 'is-invalid':null" type="text"
                                       class="form-control"
                                       v-model="form.nombre_compania" placeholder="Dígite un nombre">
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.nombre_compania">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">

                            <validation-provider
                                    #default="{ errors }"
                                    name="Giro de negocio"
                                    rules="required"
                            >

                                <label for="giroNegocio"><span style="color: red">*</span> Giro de negocio:</label>
                                <v-select id="giroNegocio" :class="errors.length > 0 ? 'is-invalid':null"
                                          v-model="form.giro_negocio"
                                          label="descripcion"
                                          :options="giro_negocios"
                                          placeholder="Seleccione un giro de negocio"

                                >
                                    <template slot="no-options">
                                        No se han encontrado registros
                                    </template>
                                </v-select>

                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.giro_negocio">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Contacto Principal"
                                    rules="required"
                            >
                                <label for="contactoPrincipal">Contacto Principal</label>
                                <v-select id="contactoPrincipal" :class="errors.length > 0 ? 'is-invalid':null"
                                          v-model="form.compania_contactos"
                                          label="nombrecompleto"
                                          @search="onSearch"
                                          :options="contactosMap"
                                          placeholder="Seleccione contacto principal"

                                >
                                    <template slot="no-options">
                                        No se han encontrado registros
                                    </template>
                                </v-select>
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.cargo">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="contactos">Contactos:</label>
                            <v-select id="contactos"
                                      v-model="mapContacto"
                                      label="nombrecompleto"
                                      @search="onSearch"
                                      @input="mapContactos"
                                      multiple
                                      :options="contactosMap"
                                      placeholder="Puede tener múltiples contactos"

                            >
                                <template slot="no-options">
                                    Escriba para buscar un contacto
                                </template>
                            </v-select>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.mapContacto">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website">Sitio Web</label>
                            <input id="website" class="form-control"
                                   type="url"
                                   v-model="form.web_site" placeholder="Dígite el sitio web">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Dirección"
                                    rules="required"
                            >
                                <label for="direccion"><span style="color: red">*</span>Dirección</label>
                                <input id="direccion" type="text" :class="errors.length > 0 ? 'is-invalid':null"
                                       class="form-control"
                                       v-model="form.direccion_compania" placeholder="Colocar coordenadas">
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Código postal"
                                    rules="required"
                            >
                                <label for="codigoPostal"><span style="color: red">*</span>Codigo Postal</label>
                                <input id="codigoPostal" class="form-control"
                                       :class="errors.length > 0 ? 'is-invalid':null"
                                       type="number" placeholder="Dígite el codigo postal"
                                       v-model="form.codigo_postal">
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Pais"
                                    rules="required"
                            >
                                <label for="paises"><span style="color: red">*</span> Pais:</label>
                                <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                          v-model="form.pais_compania"
                                          label="descripcion"
                                          @search="onSearchPais"
                                          :options="paises"
                                          id="paises"
                                          :clearable="false"

                                ></v-select>
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.estado">{{ message }}</li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                    <template v-if="this.form.pais_compania.id_pais === 21"> <!--Mostrar solo para USA-->
                        <div class="col-md-2">
                            <div class="form-group">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Estado"
                                        rules="required"
                                >
                                    <label for="estados"><span style="color: red">*</span> Estado:</label>
                                    <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                              v-model="form.estado_compania"
                                              @search="onSearchEstado"
                                              label="descripcion"
                                              :options="estados"
                                              id="estados"

                                    ></v-select>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.estado">{{ message }}</li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Ciudades"
                                        rules="required"
                                >
                                    <label for="name"><span style="color: red">*</span> Ciudades:</label>
                                    <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                              v-model="form.ciudad_compania"
                                              label="descripcion"
                                              @search="onSearchCiudades"
                                              :options="ciudades"

                                    ></v-select>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.ciudad">{{ message }}</li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                    </template>
                    <template v-if="this.form.pais_compania.id_pais === 42"> <!--Mostrar solo para Nicaragua-->
                        <div class="col-md-2">
                            <div class="form-group">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Departamentos"
                                        rules="required"
                                >
                                    <label for="departamentos"><span style="color: red">*</span> Departamento:</label>
                                    <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                              v-model="form.departamento_compania"
                                              label="descripcion"
                                              :options="departamentos"
                                              @search="onSearchDepa"
                                              id="departamentos"

                                    ></v-select>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.departamento">{{ message }}</li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Municipios"
                                        rules="required"
                                >
                                    <label for="municipios"><span style="color: red">*</span> Municipios:</label>
                                    <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                              v-model="form.municipio_compania"
                                              label="descripcion"
                                              :options="municipios"
                                              @search="onSearchMunicipio"
                                              id="municipios"

                                    ></v-select>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.municipios">{{ message }}</li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                    </template>
                    <!--                    <div class="col-md-4">-->
                    <!--                        <div class="form-group">-->
                    <!--                            <validation-provider-->
                    <!--                                    #default="{ errors }"-->
                    <!--                                    name="Ubicacion de la empresa"-->
                    <!--                                    rules="required"-->
                    <!--                            >-->
                    <!--                                <label for="ubicacionCompania"><span style="color: red">*</span>Ubicación de la empresa</label>-->
                    <!--                                <input id="ubicacionCompania" :class="errors.length > 0 ? 'is-invalid':null" type="tel"-->
                    <!--                                       class="form-control"-->
                    <!--                                       v-model="form.ubicacion_compania" placeholder="Digite coordenadas">-->
                    <!--                                <small class="text-danger">{{ errors[0] }}</small>-->
                    <!--                            </validation-provider>-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="zonaServicios">Zona de servicios</label>
                            <textarea id="zonaServicios" type="text"
                                      class="form-control"
                                      v-model="form.zona_servicio"
                                      placeholder="Digite zonas de servicios"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="servicios">Servicio destacado</label>
                            <input id="servicios" type="text"
                                   class="form-control"
                                   v-model="form.servicio_destacado" placeholder="Digite servicio destacado">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="horario">Horario de servicios</label>
                            <input id="horario" type="text"
                                   class="form-control"
                                   v-model="form.horario_servicio" placeholder="Digite horario de servicio">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Reponsable"
                                    rules="required"
                            >
                                <label for="responsable"><span style="color: red">*</span>Responsable</label>
                                <v-select
                                        id="responsable"
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        label="name"
                                        @search="onSearchUser"
                                        v-model="form.responsable_prospecto"
                                        :options="users"
                                        placeholder="Selecciona un usuario"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                >
                                    <template slot="no-options">
                                        No se han encontrado registros
                                    </template>
                                </v-select>
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Teléfono compañía:</label>
                            <div v-if="submitteNumbers.length === 0">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Teléfono compañia"
                                        rules="required"
                                >
                                    <b-button id="toggle-btn" variant="outline-primary" class="w-100"
                                              v-b-modal.modal-prevent-closing
                                              :class="errors.length > 0 ? 'is-invalid':null">
                                        <feather-icon icon="PlusCircleIcon"></feather-icon>
                                        Agregar teléfono
                                    </b-button>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </div>
                            <b-list-group v-else>
                                <b-list-group-item id="callNum" v-for="(data, index) in submitteNumbers" class="py-0"
                                                   :key="index">
                                    {{data}}
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-primary"
                                            class="btn-icon rounded-circle ml-5"
                                            v-b-tooltip="'Llamar'"
                                            v-bind:href="'tel:'+ data"
                                    >
                                        <feather-icon icon="PhoneIcon"/>
                                    </b-button>
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-danger"
                                            class="btn-icon rounded-circle"
                                            v-b-tooltip="'Eliminar número'"
                                            @click="deleteInput(index)"
                                    >
                                        <feather-icon icon="XCircleIcon"/>
                                    </b-button>
                                </b-list-group-item>
                            </b-list-group>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.telefono_compania">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">E-mail companía:</label>
                            <div v-if="submitteMailcomp.length === 0">

                                <b-button id="toggle-btn5" variant="outline-primary" class="w-100"
                                          v-b-modal.modal-mail-company>
                                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                                    Agregar E-mail
                                </b-button>
                            </div>
                            <b-list-group v-else>
                                <b-list-group-item v-for="(data, index) in submitteMailcomp" class="py-0"
                                                   :key="index">
                                    {{data}}
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-primary"
                                            class="btn-icon rounded-circle ml-5"
                                            v-b-tooltip="'Enviar correo'"
                                            v-bind:href="'mailto:'+ data"
                                    >
                                        <feather-icon icon="MailIcon"/>
                                    </b-button>
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-danger"
                                            class="btn-icon rounded-circle"
                                            v-b-tooltip="'Eliminar número'"
                                            @click="deleteMailCompany(index)"
                                    >
                                        <feather-icon icon="XCircleIcon"/>
                                    </b-button>
                                </b-list-group-item>
                            </b-list-group>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.email_compania">{{ message }}
                                    </li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                </div>
                <!--Botón del modal-->
                <div class="col-sm-12">
                    <b-dropdown v-ripple="'rgba(186, 191, 199, 0.15)'" text="+ Agregar elementos"
                                class="mt-1" variant="flat-dark">
                        <b-dropdown-item id="toggle-btn" v-b-modal.modal-prevent-closing>
                            <feather-icon icon="PlusCircleIcon"></feather-icon>
                            agregar teléfono compañía
                        </b-dropdown-item>
                        <b-dropdown-item id="toggle-btn5" v-b-modal.modal-mail-company>
                            <feather-icon icon="PlusCircleIcon"></feather-icon>
                            agregar E-mail compañía
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
                <!--Fin botón del modal-->
            </form>
            <!--Modal numero teléfono compania-->
            <div>
                <b-modal
                        id="modal-prevent-closing"
                        ref="my-modal"
                        title="Agregar número telefono compañía"
                        ok-tittle="Agregar"
                        @show="resetModal"
                        @hidden="resetModal"
                        @ok="handleOk"
                        cancel-variant="outline-secundary"
                >
                    <form
                            ref="form"
                            @submit.stop.prevent="handleSubmit"
                    >
                        <b-form-group
                                :state="numberState"
                                label="Número de teléfono"
                                label-for="name-input"
                                invalid-feedback="El número es requerido"
                        >
                            <template v-if="this.form.pais_compania.id_pais === 42">
                                <b-form-input
                                        id="name-input"
                                        v-model="tel_number"
                                        :state="numberState"
                                        required
                                        placeholder="Dígite un número"
                                        v-mask="formatNCA"
                                />
                            </template>
                            <template v-else>
                                <b-form-input
                                        id="name-input"
                                        v-model="tel_number"
                                        :state="numberState"
                                        required
                                        placeholder="Dígite un número"
                                        v-mask="formatUSA"
                                />
                            </template>
                        </b-form-group>
                    </form>
                </b-modal>
            </div>
            <!--Fin modal numero teléfono compania-->
            <!--Modal mail compania-->
            <div>
                <b-modal
                        id="modal-mail-company"
                        ref="my-modal5"
                        title="Agregar E-mail compañia"
                        ok-tittle="Agregar"
                        @show="resetModal5"
                        @hidden="resetModal5"
                        @ok="handleOk5"
                        cancel-variant="outline-secundary"
                >
                    <form
                            ref="form5"
                            @submit.stop.prevent="handleSubmit5"
                    >
                        <b-form-group
                                :state="mailState5"
                                label="E-mail compañia"
                                label-for="name-input"
                                invalid-feedback="El E-mail es requerido"
                        >
                            <b-form-input
                                    id="name-input5"
                                    v-model="mail_company"
                                    :state="mailState5"
                                    required
                                    placeholder="Dígite un E-mail"
                            />
                        </b-form-group>
                    </form>
                </b-modal>
            </div>
            <!--Fin modal mail compania-->
            <b-card-footer class="content-box-footer text-right mt-1">
                <b-row class="justify-content-end">
                    <div class="col-sm-8 text-lg-right">
                        <template v-if="form.estado">
                            <b-button class="mt-1 mr-1" type="submit" variant="danger"
                                      @click="desactivar(form.id_compania)">
                                <feather-icon icon="Trash2Icon"></feather-icon>
                                Deshabilitar
                            </b-button>
                        </template>
                        <template v-else>
                            <b-button class="mt-1 mr-1" type="submit" variant="success"
                                      @click="activar(form.id_compania)">
                                <feather-icon icon="CheckIcon"></feather-icon>
                                Habilitar
                            </b-button>
                        </template>
                        <router-link :to="{name: 'crm-companias'}">
                            <b-button type="submit" variant="secondary" class="mt-1 mr-1">
                                Cancelar
                            </b-button>
                        </router-link>
                        <b-button class="mt-1" type="submit" variant="primary" @click="actualizar"
                                  :disabled="btnAction !== 'Actualizar'">
                            {{btnAction}}
                        </b-button>

                    </div>
                </b-row>
            </b-card-footer>
            <template v-if="loading">
                <BlockUI>
                    <b-spinner variant="info" label="loading..."/>
                </BlockUI>
            </template>
        </validation-observer>
    </b-card>
</template>

<script type="text/ecmascript-6">
    import {ValidationObserver, ValidationProvider} from 'vee-validate';
    import {required} from '@validations';
    import compania from '../../../api/CRM/Companias';
    import loadingImage from '../../../assets/images/loader/block50.gif';
    import {
        BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow, BSpinner,
        BModal, VBToggle, VBTooltip, BFormGroup, BListGroup, BListGroupItem, BDropdown, VBModal,
        BDropdownDivider, BDropdownItem, BFormInput
    } from 'bootstrap-vue';
    import vSelect from 'vue-select';
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import axios from 'axios';
    import Ripple from "vue-ripple-directive";
    import contacto from "../../../api/CRM/Contactos";

    export default {
        required,
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
                url: loadingImage,   //It is important to import the loading image then use here
                form: {
                    nombre_compania: '',
                    telefono_compania: '',
                    email: '',
                    web_site: '',
                    direccion_compania: '',
                    codigo_postal: '',
                    facebook: '',
                    twitter: '',
                    instagram: '',
                    googe_mb: '',
                    yelp: '',
                    id_giro_negocio: '',
                    informacion_empresa: '',
                    detalle_servicio: '',
                    zona_servicio: '',
                    horario_servicio: '',
                    id_origen_lead: '',
                    url_origen: '',
                    id_servicios_necesitados: '',
                    id_servicios_ofertados: '',
                    id_contacto: '',
                    mapContactos: '',
                    ubicacion_compania: '',
                    servicio_destacado: '',
                    pais_compania: '',
                    departamento_compania: '',
                    municipio_compania: '',
                    estado_compania: '',
                    ciudad_compania: '',
                    ciudad: '',
                    estado: '',
                    departamento: '',
                    municipio: '',
                    pais: []
                },
                formatUSA: '+1(###)### ####',
                formatNCA: '+505 #### ####',
                mapContacto: '',
                giro_negocios: [],
                btnAction: 'Actualizar',
                errorMessages: [],
                contactos: [],
                contactosMap: [],
                users: [],
                paises: [],
                estados: [],
                ciudades: [],
                departamentos: [],
                municipios: [],
                tel_number: '',
                mail_company: '',
                numberState: null,
                mailState5: null,
                submitteNumbers: [],
                submitteMailcomp: [],
            }
        },
        methods: {
            onSearch(search, loading) {
                if (search.length) {
                    loading(true);
                    this.search(loading, search, this)
                }
            },
            onSearchUser(searchU, loading) {
                if (searchU.length) {
                    loading(true);
                    this.searchU(loading, searchU, this)
                }
            },
            onSearchPais(searchP, loading) {
                if (searchP.length) {
                    loading(true);
                    this.searchP(loading, searchP, this)
                }
            },
            onSearchDepa(searchD, loading) {
                if (searchD.length) {
                    loading(true);
                    this.searchD(loading, searchD, this)
                }
            },
            onSearchEstado(searchE, loading) {
                if (searchE.length) {
                    loading(true);
                    this.searchE(loading, searchE, this)
                }
            },
            onSearchMunicipio(searchM, loading) {
                if (searchM.length) {
                    loading(true);
                    this.searchM(loading, searchM, this)
                }
            },
            onSearchCiudades(searchC, loading) {
                if (searchC.length) {
                    loading(true);
                    this.searchC(loading, searchC, this)
                }
            },
            select(e) {
                this.$emit('input', {
                    target: {
                        value: result,
                    }
                });
                this.onEsc()
            },

            search: _.debounce((loading, search, vm) => {
                const self = this;
                axios.get(`crm/contacto/buscar?q=${escape(search)}`).then(res => {
                    vm.contactosMap = res.data.result;
                    console.log(res.data.result);
                    loading(false);
                })
            }, 10),

            searchU: _.debounce((loading, searchU, vm) => {
                const self = this;
                axios.get(`admon/usuarios/buscar?q=${escape(searchU)}`).then(res => {
                    vm.options = res.data.result;
                    vm.users = res.data.result;
                    loading(false)
                })
            }, 10),
            searchP: _.debounce((loading, searchP, vm) => {
                const self = this;
                vm.submitteNumbers.splice(0, vm.submitteNumbers.length);
                vm.form.telefono_compania = '';
                axios.get(`admon/paises/buscar?q=${escape(searchP)}`).then(res => {
                    vm.options = res.data.result;
                    vm.paises = res.data.result;
                    loading(false)
                })
            }, 10),
            searchD: _.debounce((loading, searchD, vm) => {
                const self = this;
                vm.form.municipio_compania = {id_municpio: 0, descripcion: ''};
                axios.get(`admon/departamentos/buscar?q=${escape(searchD)}`).then(res => {
                    vm.options = res.data.result;
                    vm.departamentos = res.data.result;
                    loading(false)
                })
            }, 10),
            searchE: _.debounce((loading, searchE, vm) => {
                const self = this;
                vm.form.ciudad_compania = {id_ciudad: 0, descripcion: ''};
                axios.get(`admon/estados/buscar?q=${escape(searchE)}`).then(res => {
                    vm.options = res.data.result;
                    vm.estados = res.data.result;
                    loading(false)
                })
            }, 10),
            searchM: _.debounce((loading, searchM, vm) => {
                const self = this;
                axios.get(`admon/municipios/buscar?q=${escape(searchM)}&r=${vm.form.departamento_compania.id_departamento}`).then(res => {
                    vm.options = res.data.result;
                    vm.municipios = res.data.result;
                    loading(false)
                })
            }, 10),
            searchC: _.debounce((loading, searchC, vm) => {
                const self = this;
                axios.get(`admon/ciudades/buscar?q=${escape(searchC)}&r=${vm.form.estado_compania.id_estado}`).then(res => {
                    vm.options = res.data.result;
                    vm.ciudades = res.data.result;
                    loading(false)
                })
            }, 10),
            mapContactos() {
                if (this.contactosMap) {
                    return this.form.mapContactos = this.mapContacto.map((obj) => [obj.id_contacto]).join(",");
                }
                return this.form.mapContac = '';
            },
            obtenerCompanias() {
                const self = this;
                self.loading = true;
                compania.obtenerCompania({
                        id_compania: this.$route.params.id_compania
                    },
                    data => {
                        self.form = data.compania;
                        if (self.form.telefono_compania) {
                            let str = self.form.telefono_compania;
                            console.log(str);
                            self.submitteNumbers = str.split(',');
                        } else {
                            self.form.telefono_compania = '';
                        }
                        if (self.form.email) {
                            let str4 = self.form.email;
                            self.submitteMailcomp = str4.split(',');
                        } else {
                            self.form.email = '';
                        }
                    })
            },
            obtenerContactos() {
                let self = this;
                self.loading = true;
                compania.obtenerContactos({
                        id_compania: this.$route.params.id_compania
                    }, data => {
                        self.loading = false;
                        self.mapContacto = data.contactos;
                        if (this.mapContacto) {
                            return this.form.mapContactos = this.mapContacto.map((contacto) => [contacto.id_contacto]).join(",");
                        }
                    },
                    err => {
                        self.loading = false;
                        console.log(err);
                    }
                )
            },
            activar(CompaniaID) {
                const self = this;
                self.$swal({
                    title: '¿Esta seguro?',
                    text: '¿ Desea activar la compañia?',
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
                        self.$swal({
                            icon: 'success',
                            title: '¡Habilitado!',
                            text: '¡Servicio habilitado!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        compania.activar({
                            id_compania: CompaniaID
                        }, data => {
                            this.$router.push({
                                name: 'crm-companias'
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
                        })
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'La compañia no ha sido habilitada',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            },
            desactivar(id_compania) {
                const self = this;
                self.$swal({
                    title: 'Info!',
                    text: '¿Esta seguro de desactivar la compañia?',
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
                        self.$swal({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: '¡Compañia desactivada!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        compania.desactivar({
                                id_compania: id_compania
                            },
                            data => {
                                this.$router.push({
                                    name: 'crm-companias'
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
                                    name: 'crm-companias'
                                });
                            })
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'La compañia no ha sido eliminado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }
                })
            },
            nuevo() {
                const self = this;
                self.loading = false;
                compania.nuevo({}, data => {
                    self.giro_negocios = data.giro_negocios;
                    self.contactos = data.contactos;
                    self.paises = data.paises;
                    self.estados = data.estados;
                }, err => {
                    console.log(err);
                })
            },
            actualizar() {
                this.$refs.companiaValidations.validate().then(succes => {
                    if (succes) {
                        const self = this;
                        self.btnAction = 'Actualizando, espere....';
                        self.loading = true;
                        compania.actualizar(self.form, data => {
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
                                    name: 'crm-companias'
                                })
                            },
                            err => {
                                self.loading = false;
                                self.errorMessages = err;
                                self.btnAction = 'Actualizar';
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
                    } else {
                        //Ejecutar en caso de que existan campos sin llenar
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Revise los campos faltantes',
                                    variant: 'danger',
                                    position: 'bottom-right'
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        this.btnAction = 'Actualizar';
                    }

                });

            },

            // integraciones de funcionalidades de leads a compania
            llamar_telefono_compania(numCompania) {
                let num = document.getElementById('callNum');
                num.style.color = numCompania;
            },

            checkFormValidity() {
                let self = this;
                const valid = self.$refs.form.checkValidity();
                self.numberState = valid;
                return valid;
            },
            checkFormValidity5() {
                let self = this;
                const valid = self.$refs.form5.checkValidity();
                self.mailState5 = valid;
                return valid;
            },
            resetModal() {
                let self = this;
                self.tel_number = '';
                self.numberState = null;
            },
            resetModal5() {
                let self = this;
                self.mail_company = '';
                self.mailState5 = null;
            },
            handleOk(bvModalEvent) {
                let self = this;
                bvModalEvent.preventDefault();
                self.handleSubmit()
            },
            handleOk5(bvModalEvent) {
                let self = this;
                bvModalEvent.preventDefault();
                self.handleSubmit5()
            },
            handleSubmit() {
                if (!this.checkFormValidity()) {
                    return
                }
                this.submitteNumbers.push(this.tel_number);
                this.$nextTick(() => {
                    this.$refs['my-modal'].toggle('#toggle-btn')
                });
                let tel_compania;
                tel_compania = this.submitteNumbers;
                console.log(tel_compania);
                this.form.telefono_compania = tel_compania.join(",");
                console.log(this.form.telefono_compania);
            },
            handleSubmit5() {
                if (!this.checkFormValidity5()) {
                    return
                }
                this.submitteMailcomp.push(this.mail_company);
                this.$nextTick(() => {
                    this.$refs['my-modal5'].toggle('#toggle-btn5')
                });
                let mail_compania;
                mail_compania = this.submitteMailcomp;
                this.form.email = mail_compania.join(",");
                console.log(this.form.email);
            },
            deleteInput(index) {
                let self = this;
                self.$swal({
                    title: '¿Esta seguro?',
                    text: '¿ Desea Eliminar el número de teléfono?',
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
                        self.$swal({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: '¡Número eliminado!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        if (self.submitteNumbers.length >= 1) {
                            self.submitteNumbers.splice(index, 1);
                            let str;
                            str = self.submitteNumbers;
                            self.form.telefono_compania = '';
                            self.form.telefono_compania = str.join(',');
                            console.log(self.form.telefono_compania);
                        } else {
                            self.submitteNumbers = []
                        }
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El número no ha sido eliminado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            },
            deleteMailCompany(index) {
                let self = this;
                self.$swal({
                    title: '¿Esta seguro?',
                    text: '¿ Desea Eliminar el E-mail?',
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
                        self.$swal({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: '¡E-mail eliminado!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        if (self.submitteMailcomp.length >= 1) {
                            self.submitteMailcomp.splice(index, 1);
                            let str;
                            str = self.submitteMailcomp;
                            self.form.email_compania = '';
                            self.form.email_compania = str.join(',');
                            console.log(self.form.email_compania);
                        } else {
                            self.submitteMailcomp = []
                        }
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El número no ha sido eliminado',
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
            this.obtenerCompanias();
            this.obtenerContactos();
            this.nuevo();
        }

    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
</style>
