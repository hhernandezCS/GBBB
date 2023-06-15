<template>
    <b-card>
        <validation-observer ref="ContactoValidations">
            <form>
                <div class="row">
                    <div class="col-sm-4">

                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Nombre"
                                    rules="required"
                            >
                                <label for="name"><span style="color: red">*</span> Nombre:</label>
                                <input :class="errors.length > 0 ? 'is-invalid':null" type="text" class="form-control"
                                       v-model="form.nombre" placeholder="Dígite un nombre">
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.nombre">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">

                            <validation-provider
                                    #default="{ errors }"
                                    name="Apellido"
                                    rules="required"
                            >

                                <label for="name"><span style="color: red">*</span> Apellido:</label>
                                <input :class="errors.length > 0 ? 'is-invalid':null" type="text" class="form-control"
                                       v-model="form.apellido" placeholder="Dígite un apellido">

                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>

                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.apellido">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Cargo"
                                    rules="required"
                            >
                                <label for="Cargo"><span style="color: red">*</span> Cargo</label>
                                <v-select
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        label="descripcion"
                                        v-model="form.cargo"
                                        :options="cargos"
                                        placeholder="Selecciona un cargo"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
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

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Pais"
                                    rules="required"
                            >
                                <label for="paises"><span style="color: red">*</span> Pais:</label>
                                <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                          v-model="form.pais"
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
                    <template v-if="this.form.pais.id_pais === 21"> <!--Mostrar solo para USA-->
                        <div class="col-md-2">
                            <div class="form-group">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Estado"
                                        rules="required"
                                >
                                    <label for="estados"><span style="color: red">*</span> Estado:</label>
                                    <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                              v-model="form.estado"
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
                                              v-model="form.ciudad"
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
                    <template v-if="this.form.pais.id_pais === 42"> <!--Mostrar solo para Nicaragua-->
                        <div class="col-md-2">
                            <div class="form-group">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Departamentos"
                                        rules="required"
                                >
                                    <label for="departamentos"><span style="color: red">*</span> Departamento:</label>
                                    <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                              v-model="form.departamento"
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
                                              v-model="form.municipio"
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

                    <div class="col-sm-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="compania"
                                    rules="required"
                            >
                                <label for="name"><span style="color: red">*</span> Compañia:</label>
                                <v-select :class="errors.length > 0 ? 'is-invalid':null"
                                          v-model="form.compania"
                                          label="nombre_compania"
                                          @search="onSearch"
                                          @input="mapCompanies"
                                          multiple
                                          :options="companias"
                                          placeholder="Selecciona 'Ninguno' en caso de no tener compañia"

                                >
                                    <template slot="no-options">
                                        No se han encontrado registros
                                    </template>
                                </v-select>
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.compania">{{ message }}</li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <validation-provider
                                    #default="{ errors }"
                                    name="Dirección"
                                    rules="required"
                            >
                                <label for="">Dirección</label>
                                <input type="text" :class="errors.length > 0 ? 'is-invalid':null" class="form-control"
                                       v-model="form.direccion" placeholder="">
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
                                <label for="">Codigo Postal</label>
                                <input class="form-control" :class="errors.length > 0 ? 'is-invalid':null"
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
                                    name="Medio de contacto"
                                    rules="required"
                            >
                                <label for="">Medio de contacto</label>
                                <v-select
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        label="descripcion"
                                        v-model="form.medio_contacto"
                                        :options="medio_contactos"
                                        placeholder="Selecciona un medio de contacto"
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
                            <validation-provider
                                    #default="{ errors }"
                                    name="Tipo contacto"
                                    rules="required"
                            >
                                <label for="">Tipo contacto</label>
                                <v-select
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        label="descripcion"
                                        v-model="form.tipo_contacto"
                                        :options="tipo_contactos"
                                        placeholder="Selecciona un tipo de contacto"
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
                            <validation-provider
                                    #default="{ errors }"
                                    name="Clasificacion del contacto"
                                    rules="required"
                            >
                                <label for="">Clasificación del contacto</label>
                                <v-select
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        label="descripcion"
                                        v-model="form.clasificacion_contacto"
                                        :options="clasificacion_contactos"
                                        placeholder="Selecciona una clasificación"
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
                            <validation-provider
                                    #default="{ errors }"
                                    name="Reponsable"
                                    rules="required"
                            >
                                <label for="">Responsable</label>
                                <v-select
                                        :class="errors.length > 0 ? 'is-invalid':null"
                                        label="name"
                                        @search="onSearchUser"
                                        v-model="form.u_responsable"
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

                    <!--************************************ Aqui viene lo de leads-->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name">Teléfono Trabajo:</label>
                            <div v-if="submitteTelcont.length === 0">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Teléfono trabajo"
                                        rules="required"
                                >
                                    <b-button id="toggle-btn2" class="w-100" variant="outline-primary"
                                              v-b-modal.modal-tel-contac
                                              :class="errors.length > 0 ? 'is-invalid':null">
                                        <feather-icon icon="PlusCircleIcon"></feather-icon>
                                        Agregar teléfono trabajo
                                    </b-button>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </div>
                            <b-list-group v-else>

                                <b-list-group-item v-for="(data, index) in submitteTelcont" class="py-0"
                                                   :key="index">
                                    {{data}}
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-primary"
                                            class="btn-icon rounded-circle ml-5"
                                            v-b-tooltip="'Llamar'"
                                            v-bind:href="'tel:' + data"
                                    >
                                        <feather-icon icon="PhoneIcon"/>
                                    </b-button>
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-danger"
                                            class="btn-icon rounded-circle"
                                            v-b-tooltip="'Eliminar número'"
                                            @click="deleteTelcontact(index)"
                                    >
                                        <feather-icon icon="XCircleIcon"/>
                                    </b-button>
                                </b-list-group-item>

                            </b-list-group>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name">Teléfono móvil:</label>
                            <div v-if="submitteTelmov.length === 0">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Teléfono móvil"
                                        rules="required"
                                >
                                    <b-button id="toggle-btn3" class="w-100" variant="outline-primary"
                                              v-b-modal.modal-tel-movil :class="errors.length > 0 ? 'is-invalid':null">
                                        <feather-icon icon="PlusCircleIcon"></feather-icon>
                                        Agregar teléfono móvil
                                    </b-button>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </div>
                            <b-list-group v-else>
                                <b-list-group-item v-for="(data, index) in submitteTelmov" :key="index" class="py-0">
                                    {{data}}
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-primary"
                                            class="btn-icon rounded-circle ml-5"
                                            v-b-tooltip="'Llamar'"
                                            v-bind:href="'tel:' + data"
                                    >
                                        <feather-icon icon="PhoneIcon"/>
                                    </b-button>
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-danger"
                                            class="btn-icon rounded-circle"
                                            v-b-tooltip="'Eliminar número'"
                                            @click="deleteTelmovil(index)"
                                    >
                                        <feather-icon icon="XCircleIcon"/>
                                    </b-button>
                                </b-list-group-item>
                            </b-list-group>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.telefono_movil">{{ message }}
                                    </li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name">Correo electrónico:</label>
                            <div v-if="submitteMailcont.length === 0">
                                <validation-provider
                                        #default="{ errors }"
                                        name="Correo eléctronico"
                                        rules="required"
                                >
                                    <b-button id="toggle-btn4" class="w-100" variant="outline-primary"
                                              v-b-modal.modal-mail :class="errors.length > 0 ? 'is-invalid':null">
                                        <feather-icon icon="PlusCircleIcon"></feather-icon>
                                        Agregar correo electrónico
                                    </b-button>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </div>
                            <b-list-group v-else>
                                <b-list-group-item v-for="(data, index) in submitteMailcont"
                                                   :key="index" class="py-0">
                                    {{data}}
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-primary"
                                            class="btn-icon rounded-circle ml-1"
                                            v-b-tooltip="'Enviar correo'"
                                            v-bind:href="'mailto:' + data"
                                    >
                                        <feather-icon icon="MailIcon"/>
                                    </b-button>
                                    <b-button
                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                            variant="flat-danger"
                                            class="btn-icon rounded-circle"
                                            v-b-tooltip="'Eliminar correo'"
                                            @click="deleteMail(index)"
                                    >
                                        <feather-icon icon="XCircleIcon"/>
                                    </b-button>
                                </b-list-group-item>
                            </b-list-group>
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li v-for="message in errorMessages.email">{{ message }}
                                    </li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <b-dropdown v-ripple="'rgba(186, 191, 199, 0.15)'" text="+ Agregar elementos"
                                    class="mt-1" variant="flat-dark">
                            <b-dropdown-item id="toggle-btn2" v-b-modal.modal-tel-contac>
                                <feather-icon icon="PlusCircleIcon"></feather-icon>
                                add teléfono trabajo
                            </b-dropdown-item>
                            <b-dropdown-item id="toggle-btn3" v-b-modal.modal-tel-movil>
                                <feather-icon icon="PlusCircleIcon"></feather-icon>
                                add teléfono movil
                            </b-dropdown-item>
                            <b-dropdown-item id="toggle-btn4" v-b-modal.modal-mail>
                                <feather-icon icon="PlusCircleIcon"></feather-icon>
                                add correo electrónico
                            </b-dropdown-item>
                        </b-dropdown>
                    </div>

                    <!--Sección de modales para agregar numeros y correos de contactos-->
                    <div>
                        <b-modal
                                id="modal-tel-contac"
                                ref="my-modal2"
                                title="Agregar número telefono contacto"
                                ok-tittle="Agregar"
                                @show="resetModal2"
                                @hidden="resetModal2"
                                @ok="handleOk2"
                                cancel-variant="outline-secundary"
                        >
                            <form
                                    ref="form2"
                                    @submit.stop.prevent="handleSubmit2"
                            >
                                <b-form-group
                                        :state="numberState2"
                                        label="Número de teléfono"
                                        label-for="name-input"
                                        invalid-feedback="El número es requerido"
                                >
                                    <template v-if="this.form.pais.id_pais === 42">
                                        <b-form-input
                                                id="name-input2"
                                                v-model="tel_number2"
                                                :state="numberState2"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="form.formatNCA"
                                        />
                                    </template>
                                    <template v-else>
                                        <b-form-input
                                                id="name-input2"
                                                v-model="tel_number2"
                                                :state="numberState2"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="form.formatUSA"
                                        />
                                    </template>

                                </b-form-group>
                            </form>
                        </b-modal>
                    </div>
                    <div>
                        <b-modal
                                id="modal-tel-movil"
                                ref="my-modal3"
                                title="Agregar número telefono movil"
                                ok-tittle="Agregar"
                                @show="resetModal3"
                                @hidden="resetModal3"
                                @ok="handleOk3"
                                cancel-variant="outline-secundary"
                        >
                            <form
                                    ref="form3"
                                    @submit.stop.prevent="handleSubmit3"
                            >
                                <b-form-group
                                        :state="numberState3"
                                        label="Número de teléfono"
                                        label-for="name-input"
                                        invalid-feedback="El número es requerido"
                                >
                                    <template v-if="this.form.pais.id_pais === 42">
                                        <b-form-input
                                                id="name-input3"
                                                v-model="tel_number3"
                                                :state="numberState3"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="form.formatNCA"
                                        />
                                    </template>
                                    <template v-else>
                                        <b-form-input
                                                id="name-input3"
                                                v-model="tel_number3"
                                                :state="numberState3"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="form.formatUSA"
                                        />
                                    </template>
                                </b-form-group>
                            </form>
                        </b-modal>
                    </div>
                    <div>
                        <b-modal
                                id="modal-mail"
                                ref="my-modal4"
                                title="Agregar E-mail de contacto"
                                ok-tittle="Agregar"
                                @show="resetModal4"
                                @hidden="resetModal4"
                                @ok="handleOk4"
                                cancel-variant="outline-secundary"
                        >
                            <form
                                    ref="form4"
                                    @submit.stop.prevent="handleSubmit4"
                            >
                                <b-form-group
                                        :state="numberState4"
                                        label="E-mail de contacto"
                                        label-for="name-input"
                                        invalid-feedback="El E-mail es requerido"
                                >
                                    <b-form-input
                                            id="name-input4"
                                            v-model="mail_contacto"
                                            :state="numberState4"
                                            required
                                            placeholder="Dígite un E-mail"
                                    />
                                </b-form-group>
                            </form>
                        </b-modal>
                    </div>
                    <!-- *********************************** Aqui termina lo de leads-->

                </div>
            </form>
            <b-card-footer class="content-box-footer text-right mt-1">
                <b-row class="justify-content-end">
                    <div class="col-sm-8 text-lg-right">
                        <router-link :to="{name: 'crm-contactos'}">
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
            <template v-if="loading">
                <BlockUI>
                    <b-spinner variant="info" label="loading..."/>
                </BlockUI>
            </template>
        </validation-observer>
    </b-card>

</template>

<script type="text/ecmascript-6">
    import {ValidationObserver, ValidationProvider} from 'vee-validate'
    import {required} from '@validations'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import {
        BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow, BSpinner,
        BModal, VBToggle, VBTooltip, BFormGroup, BListGroup, BListGroupItem, BDropdown, VBModal,
        BDropdownDivider, BDropdownItem, BFormInput
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import contacto from "../../../api/CRM/Contactos";
    import axios from 'axios'
    import lead from "@/api/CRM/Leads";
    import Ripple from "vue-ripple-directive";

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
                url: loadingImage,   //It is important to import the loading image then use here
                form: {
                    medio_contacto: '',
                    clasificacion_contacto: '',
                    tipo_contacto: '',
                    u_responsable: '',
                    formatUSA: '+1(###)### ####',
                    formatNCA: '+505 #### ####',
                    nombre: '',
                    apellido: '',
                    compania: 'Ninguno',
                    direccion: '',
                    codigo_postal: '',
                    telefono_trabajo: '',
                    telefono_movil: '',
                    email: '',
                    mapCompanies: '',
                    ciudad: '',
                    estado: '',
                    departamento: '',
                    municipio: '',
                    pais: []
                },
                companias: [],
                users: [],
                medio_contactos: [],
                tipo_contactos: [],
                clasificacion_contactos: [],
                btnAction: 'Registrar',
                errorMessages: [],
                paises: [],
                estados: [],
                ciudades: [],
                departamentos: [],
                municipios: [],
                cargos: [],
                submitteTelcont: [],
                submitteTelmov: [],
                submitteMailcont: [],
                submitteMailcomp: [],
                numberState2: null,
                numberState3: null,
                numberState4: null,
                tel_number2: '',
                tel_number3: '',
                mail_contacto: '',
            }
        },
        methods: {
            nuevo() {
                const self = this;
                self.loading = false;
                contacto.nuevo({}, data => {
                    self.medio_contactos = data.medio_contacto;
                    self.tipo_contactos = data.tipo_contacto;
                    self.clasificacion_contactos = data.clasificacion_contacto;
                    self.cargos = data.cargos;
                }, err => {
                    console.log(err);
                })
            },
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
                axios.get(`crm/compania/buscar?q=${escape(search)}`).then(res => {
                    vm.options = res.data.result;
                    vm.companias = res.data.result;
                    loading(false)
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
                vm.submitteTelcont.splice(0, vm.submitteTelcont.length);
                vm.submitteTelmov.splice(0, vm.submitteTelmov.length);
                vm.form.telefono_movil = '';
                vm.form.telefono_trabajo = '';
                axios.get(`admon/paises/buscar?q=${escape(searchP)}`).then(res => {
                    vm.options = res.data.result;
                    vm.paises = res.data.result;
                    loading(false)
                })
            }, 10),
            searchD: _.debounce((loading, searchD, vm) => {
                const self = this;
                vm.form.municipio = {id_municpio: 0, descripcion: ''};
                axios.get(`admon/departamentos/buscar?q=${escape(searchD)}`).then(res => {
                    vm.options = res.data.result;
                    vm.departamentos = res.data.result;
                    loading(false)
                })
            }, 10),
            searchE: _.debounce((loading, searchE, vm) => {
                const self = this;
                vm.form.ciudad = {id_ciudad: 0, descripcion: ''};
                axios.get(`admon/estados/buscar?q=${escape(searchE)}`).then(res => {
                    vm.options = res.data.result;
                    vm.estados = res.data.result;
                    loading(false)
                })
            }, 10),
            searchM: _.debounce((loading, searchM, vm) => {
                const self = this;
                axios.get(`admon/municipios/buscar?q=${escape(searchM)}&r=${vm.form.departamento.id_departamento}`).then(res => {
                    vm.options = res.data.result;
                    vm.municipios = res.data.result;
                    loading(false)
                })
            }, 10),
            searchC: _.debounce((loading, searchC, vm) => {
                const self = this;
                axios.get(`admon/ciudades/buscar?q=${escape(searchC)}&r=${vm.form.estado.id_estado}`).then(res => {
                    vm.options = res.data.result;
                    vm.ciudades = res.data.result;
                    loading(false)
                })
            }, 10),

            mapCompanies() {
                return this.form.mapCompanies = this.form.compania.map((obj) => [obj.id_compania]).join(",");
            },
            registrar() {
                this.$refs.ContactoValidations.validate().then(succes => {
                    if (succes) {
                        var self = this;
                        self.btnAction = 'Registrando, espere....';
                        self.loading = true;
                        contacto.registrar(self.form, data => {
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
                                    name: 'crm-contactos'
                                })
                            },
                            err => {
                                self.loading = false;
                                self.errorMessages = err;
                                self.btnAction = 'Registrar';
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
                        this.btnAction = 'Registrar';
                    }

                });

            },

            deleteTelcontact(index) {
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
                        if (self.submitteTelcont.length >= 1) {
                            self.submitteTelcont.splice(index, 1);
                            let str;
                            str = self.submitteTelcont;
                            self.form.telefono_trabajo = '';
                            self.form.telefono_trabajo = str.join(',');
                            console.log(self.form.telefono_trabajo);
                        } else {
                            self.submitteTelcont = []
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
            deleteTelmovil(index) {
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
                        if (self.submitteTelmov.length >= 1) {
                            self.submitteTelmov.splice(index, 1);
                            let str;
                            str = self.submitteTelmov;
                            self.form.telefono_movil = '';
                            self.form.telefono_movil = str.join(',');
                            console.log(self.form.telefono_movil);
                        } else {
                            self.submitteTelmov = []
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
            deleteMail(index) {
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
                        if (self.submitteMailcont.length >= 1) {
                            self.submitteMailcont.splice(index, 1);
                            let str;
                            str = self.submitteMailcont;
                            self.form.email = '';
                            self.form.email = str.join(',');
                            console.log(self.form.email);
                        } else {
                            self.submitteMailcont = []
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
            resetModal2() {
                let self = this;
                self.tel_number2 = '';
                self.numberState2 = null;
            },
            resetModal3() {
                let self = this;
                self.tel_number3 = '';
                self.numberState3 = null;
            },
            resetModal4() {
                let self = this;
                self.mail_contacto= '';
                self.numberState4 = null;
            },
            handleOk2(bvModalEvent) {
                let self = this;
                bvModalEvent.preventDefault();
                self.handleSubmit2()
            },
            handleOk3(bvModalEvent) {
                let self = this;
                bvModalEvent.preventDefault();
                self.handleSubmit3()
            },
            handleOk4(bvModalEvent) {
                let self = this;
                bvModalEvent.preventDefault();
                self.handleSubmit4()
            },
            handleSubmit2() {
                if (!this.checkFormValidity2()) {
                    return
                }
                this.submitteTelcont.push(this.tel_number2);
                this.$nextTick(() => {
                    this.$refs['my-modal2'].toggle('#toggle-btn2')
                });
                let tel_contacto;
                tel_contacto = this.submitteTelcont;
                console.log(tel_contacto);
                this.form.telefono_trabajo = tel_contacto.join(",");
                console.log(this.form.telefono_trabajo);
            },
            handleSubmit3() {
                if (!this.checkFormValidity3()) {
                    return
                }
                this.submitteTelmov.push(this.tel_number3);
                this.$nextTick(() => {
                    this.$refs['my-modal3'].toggle('#toggle-btn3')
                });
                let tel_movil;
                tel_movil = this.submitteTelmov;
                console.log(tel_movil);
                this.form.telefono_movil = tel_movil.join(",");
                console.log(this.form.telefono_movil);
            },
            handleSubmit4() {
                if (!this.checkFormValidity4()) {
                    return
                }
                this.submitteMailcont.push(this.mail_contacto);
                this.$nextTick(() => {
                    this.$refs['my-modal4'].toggle('#toggle-btn4')
                });
                let mail_contact;
                mail_contact = this.submitteMailcont;
                console.log(mail_contact);
                this.form.email = mail_contact.join(",");
                console.log(this.form.email);
            },
            checkFormValidity2() {
                let self = this;
                const valid = self.$refs.form2.checkValidity();
                self.numberState2 = valid;
                return valid;
            },
            checkFormValidity3() {
                let self = this;
                const valid = self.$refs.form3.checkValidity();
                self.numberState3 = valid;
                return valid;
            },
            checkFormValidity4() {
                let self = this;
                const valid = self.$refs.form4.checkValidity();
                self.numberState4 = valid;
                return valid;
            },

        },
        mounted() {
            this.nuevo();
        }

    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
</style>
