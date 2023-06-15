<template>

    <b-tabs>
        <b-tab title="Información del lead"> <!--Inico del tab para detalle de lead-->
            <b-tabs content-class="content-class">
                <b-row>
                    <div class="col-md-4">
                        <b-card> <!--Card para información de companía-->
                            <h2>Compañía</h2>
                            <form>
                                <b-row>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"><span style="color: red">*</span> Empresa:</label>
                                            <input type="text" placeholder="Digite el nombre de la empresa"
                                                   class="form-control" v-model="form.nombre_compania">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.nombre_compania">{{ message }}
                                                    </li>
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Giro de negocio:</label>
                                            <v-select v-model="form.giro_prospecto"
                                                      label="descripcion"
                                                      :options="giros_negocios"
                                            ></v-select>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.giro_prospecto">{{ message }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Dirección:</label>
                                            <input type="text" placeholder="Digite la dirección" class="form-control"
                                                   v-model="form.direccion_compania">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.direccion_compania">{{ message
                                                        }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <template v-if="this.form.id_pais === 21">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name"><span style="color: red">*</span> Estado:</label>
                                                <v-select v-model="form.estado_lead"
                                                          @input="obtenerCiudad"
                                                          label="descripcion"
                                                          :options="estados"
                                                ></v-select>
                                                <b-alert variant="danger" show>
                                                    <ul class="error-messages">
                                                        <li v-for="message in errorMessages.estado_lead">{{ message }}
                                                        </li>
                                                    </ul>
                                                </b-alert>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name"> Ciudad:</label>
                                                <v-select v-model="form.ciudad_prospecto"
                                                          label="descripcion"
                                                          :options="ciudades"
                                                ></v-select>
                                                <b-alert variant="danger" show>
                                                    <ul class="error-messages">
                                                        <li v-for="message in errorMessages.ciudad_prospecto">{{ message
                                                            }}
                                                        </li>
                                                    </ul>
                                                </b-alert>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name"><span style="color: red">*</span>
                                                    Departamento:</label>
                                                <v-select v-model="form.departamento_prospecto"
                                                          @input="obtenerMunicipio"
                                                          label="descripcion"
                                                          :options="departamentos"
                                                ></v-select>
                                                <b-alert variant="danger" show>
                                                    <ul class="error-messages">
                                                        <li v-for="message in errorMessages.departamento">{{ message
                                                            }}
                                                        </li>
                                                    </ul>
                                                </b-alert>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="name"> Municipio:</label>
                                                <v-select v-model="form.municipio_prospecto"
                                                          label="descripcion"
                                                          :options="municipios"
                                                ></v-select>
                                                <b-alert variant="danger" show>
                                                    <ul class="error-messages">
                                                        <li v-for="message in errorMessages.municipio">{{ message }}
                                                        </li>
                                                    </ul>
                                                </b-alert>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Código postal:</label>
                                            <input class="form-control" type="number"
                                                   placeholder="Ingrese el código postal" v-model="form.codigo_postal">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.codigo_postal">{{ message }}
                                                    </li>
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Teléfono compañía:</label>
                                            <div v-if="submitteNumbers.length === 0">
                                                <b-button id="toggle-btn" variant="outline-primary" v-b-modal.modal-prevent-closing>
                                                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                                                    Agregar teléfono
                                                </b-button>
                                            </div>
                                            <b-list-group v-else>
                                                <b-list-group-item id="callNum" v-for="(data, index) in submitteNumbers"
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
                                                    <li v-for="message in errorMessages.tel_compania">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">E-mail companía:</label>
                                            <div v-if="submitteMailcomp.length === 0">
                                                <b-button id="toggle-btn5" variant="outline-primary" v-b-modal.modal-mail-company>
                                                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                                                    Agregar E-mail
                                                </b-button>
                                            </div>
                                            <b-list-group v-else>
                                                <b-list-group-item v-for="(data, index) in submitteMailcomp"
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
                                    <!--Botón del modal-->
                                    <div class="col-sm-12">
                                        <b-dropdown v-ripple="'rgba(186, 191, 199, 0.15)'" text="+ Agregar campo"
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
                                </b-row>
                            </form>
                        </b-card> <!--Fin del card para información de companía-->
                    </div>
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
                                    <!--                                            <b-form-input
                                                                                id="name-input"
                                                                                v-model="tel_number"
                                                                                :state="numberState"
                                                                                required
                                                                                placeholder="Dígite un número"
                                                                                v-mask="'+1(###)### ####'"
                                                                                />-->

                                    <template v-if="this.form.id_pais === 42">
                                        <b-form-input
                                                id="name-input"
                                                v-model="tel_number"
                                                :state="numberState"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="'+505 #### ####'"
                                        />
                                    </template>
                                    <template v-else>
                                        <b-form-input
                                                id="name-input"
                                                v-model="tel_number"
                                                :state="numberState"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="'+1(###)### ####'"
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
                                            type="mail"
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
                    <div class="col-md-4">
                        <b-card> <!--Card para información de contacto-->
                            <h2>Contacto</h2>
                            <form>
                                <b-row>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">* Tema de lead:</label>
                                            <input disabled class="form-control" type="text"
                                                   v-model="form.descripcion_lead"
                                                   placeholder="Ingrese el tema del lead">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.descripcion_lead">{{ message
                                                        }}
                                                    </li>
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Nombre:</label>
                                            <input type="text" placeholder="Ingrese un nombre" class="form-control"
                                                   v-model="form.nombre">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Apellido:</label>
                                            <input type="text" placeholder="Ingrese un apellido" class="form-control"
                                                   v-model="form.apellido">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.apellido">{{ message }}</li>
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                                <label for="Cargo"><span style="color: red">*</span> Cargo</label>
                                                <v-select
                                                        label="descripcion"
                                                        v-model="form.cargos"
                                                        :options="cargoslst"
                                                        placeholder="Selecciona un cargo"
                                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                                >
                                                    <template slot="no-options">
                                                        No se han encontrado registros
                                                    </template>
                                                </v-select>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.cargo">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Teléfono Contacto:</label>
                                            <div v-if="submitteTelcont.length === 0">
                                                <b-button id="toggle-btn2" variant="outline-primary" v-b-modal.modal-tel-contac>
                                                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                                                    Agregar teléfono contacto
                                                </b-button>
                                            </div>
                                            <b-list-group v-else>
                                                <b-list-group-item v-for="(data, index) in submitteTelcont"
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
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Teléfono móvil:</label>
                                            <div v-if="submitteTelmov.length === 0">
                                                <b-button id="toggle-btn3" variant="outline-primary" v-b-modal.modal-tel-movil>
                                                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                                                    Agregar teléfono móvil
                                                </b-button>
                                            </div>
                                            <b-list-group v-else>
                                                <b-list-group-item v-for="(data, index) in submitteTelmov" :key="index">
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
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Correo electrónico:</label>
                                            <div v-if="submitteMailcont.length === 0">
                                                <b-button id="toggle-btn4" variant="outline-primary" v-b-modal.modal-mail>
                                                    <feather-icon icon="PlusCircleIcon"></feather-icon>
                                                    Agregar correo electrónico
                                                </b-button>
                                            </div>
                                            <b-list-group v-else>
                                                <b-list-group-item v-for="(data, index) in submitteMailcont"
                                                                   :key="index">
                                                    {{data}}
                                                    <b-button
                                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                                            variant="flat-primary"
                                                            class="btn-icon rounded-circle ml-5"
                                                            v-b-tooltip="'Enviar correo'"
                                                            v-bind:href="'mailto:' + data"
                                                    >
                                                        <feather-icon icon="MailIcon"/>
                                                    </b-button>
                                                    <b-button
                                                            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                                            variant="flat-danger"
                                                            class="btn-icon rounded-circle"
                                                            v-b-tooltip="'Eliminar número'"
                                                            @click="deleteMail(index)"
                                                    >
                                                        <feather-icon icon="XCircleIcon"/>
                                                    </b-button>
                                                </b-list-group-item>
                                            </b-list-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.email_contacto">{{ message }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <b-dropdown v-ripple="'rgba(186, 191, 199, 0.15)'" text="+ add número"
                                                    class="mt-1" variant="flat-dark">
                                            <b-dropdown-item id="toggle-btn2" v-b-modal.modal-tel-contac>
                                                <feather-icon icon="PlusCircleIcon"></feather-icon>
                                                add teléfono contacto
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
                                </b-row>
                            </form>
                        </b-card> <!-- Fin del card para información de contacto-->
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
                                    <template v-if="this.form.id_pais === 42">
                                        <b-form-input
                                                id="name-input2"
                                                v-model="tel_number2"
                                                :state="numberState2"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="'+505 #### ####'"
                                        />
                                    </template>
                                    <template v-else>
                                        <b-form-input
                                                id="name-input2"
                                                v-model="tel_number2"
                                                :state="numberState2"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="'+1(###)### ####'"
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
                                    <template v-if="this.form.id_pais === 42">
                                        <b-form-input
                                                id="name-input3"
                                                v-model="tel_number3"
                                                :state="numberState3"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="'+505 #### ####'"
                                        />
                                    </template>
                                    <template v-else>
                                        <b-form-input
                                                id="name-input3"
                                                v-model="tel_number3"
                                                :state="numberState3"
                                                required
                                                placeholder="Dígite un número"
                                                v-mask="'+1(###)### ####'"
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
                                            v-model="tel_number4"
                                            :state="numberState4"
                                            required
                                            placeholder="Dígite un E-mail"
                                    />
                                </b-form-group>
                            </form>
                        </b-modal>
                    </div>
                    <!--Fin sección de modales para agregar numeros y correos de contactos-->
                    <div class="col-md-4">
                        <b-card>
                            <h2>Detalles</h2>
                            <form>
                                <b-row>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Origen del lead:</label>
                                            <v-select v-model="form.origen_prospecto"
                                                      label="descripcion"
                                                      :options="origenes"
                                            ></v-select>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.origen_prospecto">{{ message
                                                        }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Url de origen:</label>
                                            <input class="form-control" type="text" v-model="form.url_origen">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.url_origen">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="name"> Servicios necesitados:</label>
                                        <v-select label="descripcion" v-model="servi"
                                                  @input="crearArray"
                                                  multiple
                                                  :options="servicios">
                                        </v-select>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Detalle de servicio:</label>
                                            <b-form-textarea type="text" v-model="form.detalle_servicio"
                                                             id="textarea-default"
                                                             placeholder="Detalle de servicio"
                                                             row="3"
                                            />
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.detalle_servicio">{{ message
                                                        }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Zona de servicio:</label>
                                            <input class="form-control" type="text" v-model="form.zona_servicio">
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.zona_servicio">{{ message }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name"> Comentarios :</label>
                                            <b-form-textarea type="text" v-model="form.comentario_lead"
                                                             id="textarea-default"
                                                             placeholder="Comentarios"
                                                             row="3"
                                            />
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.comentario_lead">{{ message }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for>Usuario Responsable:</label>
                                            <v-select label="name" v-model="form.responsable_prospecto"
                                                      :options="vendedores"></v-select>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message" v-for="message in errorMessages.vendedor"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>
                                </b-row>
                            </form>
                        </b-card>
                    </div>
                    <div class="col-md-4">
                        <b-card>
                            <h2>Sitios web</h2>
                            <form>
                                <b-row>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Sitio Web cooporativo:</label>
                                            <b-input-group>
                                                <b-form-input placeholder="Ingrese el link del sitio web"
                                                              v-model="form.web_site"/>
                                                <b-input-group-append>
                                                    <b-button v-bind:href="form.web_site" v-bind:target="'_blank'"
                                                              v-b-tooltip="form.web_site ? 'Ir a: ' + form.web_site : 'Ingrese un sitio web'"
                                                              variant="outline-primary">
                                                        <feather-icon icon="NavigationIcon"></feather-icon>
                                                    </b-button>
                                                </b-input-group-append>
                                            </b-input-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.web_site">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Facebook:</label>
                                            <b-input-group>
                                                <b-form-input placeholder="Ingrese el link de facebook"
                                                              v-model="form.facebook"/>
                                                <b-input-group-append>
                                                    <b-button v-bind:href="form.facebook" v-bind:target="'_blank'"
                                                              v-b-tooltip="form.facebook ? 'Ir a: ' + form.facebook : 'Ingrese un link de Facebook'"
                                                              variant="outline-primary">
                                                        <feather-icon icon="NavigationIcon"></feather-icon>
                                                    </b-button>
                                                </b-input-group-append>
                                            </b-input-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.facebook">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Instagram:</label>
                                            <b-input-group>
                                                <b-form-input placeholder="Ingrese el link de facebook"
                                                              v-model="form.instagram"/>
                                                <b-input-group-append>
                                                    <b-button v-bind:href="form.instagram" v-bind:target="'_blank'"
                                                              v-b-tooltip="form.instragram ? 'Ir a: ' + form.instragram : 'Ingrese un link de Instagram'"
                                                              variant="outline-primary">
                                                        <feather-icon icon="NavigationIcon"></feather-icon>
                                                    </b-button>
                                                </b-input-group-append>
                                            </b-input-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.instagram">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Twitter:</label>
                                            <b-input-group>
                                                <b-form-input placeholder="Ingrese el link de facebook"
                                                              v-model="form.twitter"/>
                                                <b-input-group-append>
                                                    <b-button v-bind:href="form.twitter" v-bind:target="'_blank'"
                                                              v-b-tooltip="form.twitter ? 'Ir a: ' + form.twitter : 'Ingrese un link de Twitter'"
                                                              variant="outline-primary">
                                                        <feather-icon icon="NavigationIcon"></feather-icon>
                                                    </b-button>
                                                </b-input-group-append>
                                            </b-input-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.twitter">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Google my business:</label>
                                            <b-input-group>
                                                <b-form-input placeholder="Ingrese el link de facebook"
                                                              v-model="form.google_my_business"/>
                                                <b-input-group-append>
                                                    <b-button v-bind:href="form.google_my_business"
                                                              v-bind:target="'_blank'"
                                                              v-b-tooltip="form.google_my_business ? 'Ir a: ' + form.google_my_business : 'Ingrese un link de una ficha de google'"
                                                              variant="outline-primary">
                                                        <feather-icon icon="NavigationIcon"></feather-icon>
                                                    </b-button>
                                                </b-input-group-append>
                                            </b-input-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.google_my_business">{{ message
                                                        }}
                                                    </li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Yelp:</label>
                                            <b-input-group>
                                                <b-form-input placeholder="Ingrese el link de facebook"
                                                              v-model="form.yelp"/>
                                                <b-input-group-append>
                                                    <b-button v-bind:href="form.yelp" v-bind:target="'_blank'"
                                                              v-b-tooltip="form.yelp ? 'Ir a: ' + form.yelp : 'Ingrese un link de yelp'"
                                                              variant="outline-primary">
                                                        <feather-icon icon="NavigationIcon"></feather-icon>
                                                    </b-button>
                                                </b-input-group-append>
                                            </b-input-group>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li v-for="message in errorMessages.yelp">{{ message }}</li>
                                                </ul>
                                            </b-alert>
                                        </div>
                                    </div>
                                </b-row>
                            </form>
                        </b-card>
                    </div>
                    <!--                        <div class="col-md-4">
                                                <b-card> &lt;!&ndash;Card Metodo de contacto&ndash;&gt;
                                                    <h2>Datos del seguimiento</h2>
                                                    <form>
                                                        <b-row>
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="name"> Preferencia de contacto:</label>
                                                                    <select v-model="form.preferencia_contacto"
                                                                            class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                                                                        <option value="0">Seleccionar</option>
                                                                        <option value="1">Correo electrónico</option>
                                                                        <option value="2">Teléfono</option>
                                                                        <option value="3">Whatsapp</option>
                                                                    </select>
                                                                    <b-alert variant="danger" show>
                                                                        <ul class="error-messages">
                                                                            <li v-for="message in errorMessages.origen">{{ message }}</li>
                                                                        </ul>
                                                                    </b-alert>
                                                                </div>
                                                            </div>
                                                        </b-row>
                                                    </form>
                                                </b-card>&lt;!&ndash;Fin card metodo de contacto&ndash;&gt;
                                            </div>-->
                </b-row>

                <b-card-footer class="content-box-footer text-right mt-1">

                    <b-row>
                        <div class="col-md-6 text-left p-0">
                            <template v-if="form.estado===1 || form.estado===2">
                                <b-button type="submit" variant="danger" @click="desactivar(form.id_lead)">
                                    <feather-icon icon="Trash2Icon"></feather-icon>
                                    Descartar lead
                                </b-button>
                            </template>
                            <template v-else>
                                <b-button type="submit" variant="success" @click="activar(form.id_lead)">
                                    <feather-icon icon="CheckIcon"></feather-icon>
                                    Habilitar Lead
                                </b-button>
                            </template>
                        </div>
                        <div class="col-md-6">
                            <router-link :to="{name: 'crm-leads'}">
                                <b-button type="submit" variant="secondary" class="mx-1">
                                    Cancelar
                                </b-button>
                            </router-link>

                            <b-dropdown
                                    type="submit"
                                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                    split
                                    text="Actualizar"
                                    variant="primary"
                                    right
                                    @click="actualizar"
                                    :disabled="btnAction !== 'Actualizar'"
                            >

                                <b-dropdown-item @click="actualizar_sin_salir"
                                                 :disabled="btnActionSinSalir !== 'Actualizar sin salir'">
                                    Actualizar sin salir
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

            </b-tabs>
        </b-tab> <!--Fin tab detalle de pospecto-->
        <b-tab title="Seguimiento"> <!--Inicio tab seguimiento de lead-->
            <b-tabs>
                <b-card>
                    <b-row>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for=""> No. Lead</label>
                                <input disabled class="form-control" :disabled="true" placeholder=""
                                       v-model="form.codigo_prospecto">
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.codigo_prospecto"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for>Usuario Responsable:</label>
                                <v-select label="name" v-model="form.responsable_prospecto"
                                          :options="vendedores"></v-select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.vendedor"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for=""><span style="color: red">*</span> Medio de contacto</label>
                                <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                                        v-model="seguimientoForm.tipo_contacto">
                                    <option value="1">Llamada</option>
                                    <option value="2">Mensaje</option>
                                    <option value="3">Correo</option>
                                </select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.tipo_contacto"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for=""><span style="color: red">*</span> Estado</label>
                                <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                                        v-model="seguimientoForm.estado_comunicacion">
                                    <option value="1">Efectivo</option>
                                    <option value="2">No efectivo</option>
                                </select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.estado_comunicacion"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name"><span style="color: red">*</span> Estado del seguimiento:</label>
                                <select v-model="seguimientoForm.estado_seguimiento"
                                        class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                                    <option value="0">Efectivo</option>
                                    <option value="1">No efectivo</option>
                                    <option value="2">Descartado</option>
                                    <option value="3">No interesado</option>
                                </select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li v-for="message in errorMessages.estado_segumiento">{{ message }}</li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for><span style="color: red">*</span> Clasifiación llamada:</label>
                                <v-select label="descripcion" v-model="seguimientoForm.clasificacion_llamada"
                                          :options="clasificaciones_llamadas"></v-select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.clasificacion_llamada"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Valoración Contacto</label>
                                <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                                        v-model="seguimientoForm.valoracion_contacto">
                                    <option value="1">Ocupado</option>
                                    <option value="2">Interesado</option>
                                    <option value="3">Muy interesado</option>
                                    <option value="4">No interesado</option>
                                </select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.valoracion_contacto"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""><span style="color: red">*</span> Idioma Contacto</label>
                                <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                                        v-model="seguimientoForm.idioma_contacto">
                                    <option value="1">Espanol</option>
                                    <option value="2">Ingles</option>
                                    <option value="3">Otro</option>
                                </select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.idioma_contacto"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>


                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for>Servicios Ofertados:</label>
                                <v-select label="descripcion" v-model="seguimientoForm.servicios_ofertados"
                                          @input="arrayServiciosOfertados"
                                          multiple
                                          :options="servicios">
                                </v-select>
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.servicios_ofertados"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for>Comentario de la comunicación:</label>

                                <b-form-textarea type="text" v-model="seguimientoForm.comentario_comunicacion"
                                                 id="textarea-default2"
                                                 placeholder="Comentario llamada"
                                                 row="3"
                                />
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.comentario_comunicacion"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                        <b-badge class="col-md-12 d-block mb-1 mt-1" pill variant="primary">
                            Agendar de activiad
                        </b-badge>
                        <b-col md="4">
                            <b-form-group>
                                <label>Fecha de contacto</label>
                                <flat-pickr
                                        v-model="seguimientoForm.proximo_contacto"
                                        class="form-control"
                                        :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                                />
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.proximo_contacto"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </b-form-group>
                        </b-col>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for>Asunto actividad:</label>
                                <input type="text" class="form-control" v-model="seguimientoForm.asunto_actividad">
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.asunto_actividad"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for>Descripcion de la actividad:</label>
                                <b-form-textarea type="text" v-model="seguimientoForm.descripcion_actividad"
                                                 id="textarea-default3"
                                                 placeholder="Descripción de la actividad"
                                                 row="3"
                                />
                                <b-alert variant="danger" show>
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.descripcion_actividad"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for>&nbsp;</label>
                                <b-button @click="agregarDetalle" class="btn-agregar" variant="info" ref="agregar">
                                    Agregar
                                    detalle
                                </b-button>
                            </div>
                        </div>
                    </b-row>

                    <!--Modal para editar tabla de segumimiento-->
                    <b-modal
                            id="modal-select2"
                            title="Editar seguimiento"
                            ok-title="Editar"
                            @ok="editarSeguimiento"
                            cancel-variant="outline-secondary"
                    >
                        <label for="">No. Lead</label>
                        <input disabled v-model="form.codigo_prospecto" type="text" class="form-control"
                               placeholder="Escriba el estado del seguimiento">

                        <label for=""><span style="color: red">*</span> Medio de contacto</label>
                        <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                                v-model="seguimientoForm.editSeguimiento.tipo_contacto">
                            <option value="1">Llamada</option>
                            <option value="2">Mensaje</option>
                            <option value="3">Correo</option>
                        </select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.tipo_contacto" v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for=""><span style="color: red">*</span> Estado</label>
                        <select class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                                v-model="seguimientoForm.editSeguimiento.estado_comunicacion">
                            <option value="1">Efectivo</option>
                            <option value="2">No efectivo</option>
                        </select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.estado_comunicacion"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for=""><span style="color: red">*</span> Estado del seguimiento</label>
                        <select v-model="seguimientoForm.editSeguimiento.estado_seguimiento"
                                class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                            <option value="0">Efectivo</option>
                            <option value="1">No efectivo</option>
                            <option value="2">Descartado</option>
                            <option value="3">No interesado</option>
                        </select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.estado_seguimiento"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for=""><span style="color: red">*</span> Clasificación llamada</label>
                        <v-select
                                v-model="seguimientoForm.editSeguimiento.seguimiento_clasificacion_llamada"
                                label="descripcion"
                                :options="clasificaciones_llamadas"
                        ></v-select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.clasifiacion_llamada"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for=""><span style="color: red">*</span> Valoración contacto</label>
                        <select v-model="seguimientoForm.editSeguimiento.valoracion_contacto"
                                class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                            <option value="1">Ocupado</option>
                            <option value="2">Interesado</option>
                            <option value="3">Muy interesado</option>
                            <option value="4">No interesado</option>
                        </select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.valoracion_contacto"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for=""><span style="color: red">*</span> Idioma Contacto</label>
                        <select v-model="seguimientoForm.editSeguimiento.idioma_llamada"
                                class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
                            <option value="1">Espanol</option>
                            <option value="2">Ingles</option>
                            <option value="3">Otro</option>
                        </select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.idioma_contacto"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for>Servicios Ofertados:</label>
                        <v-select label="descripcion" v-model="seguimientoForm.editSeguimiento.servicios_ofertados"
                                  @input="arrayEditserviciosOfer"
                                  multiple
                                  :options="servicios">
                        </v-select>
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.servicios_ofertados"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for>Comentario de la comunicación:</label>

                        <b-form-textarea type="text" v-model="seguimientoForm.editSeguimiento.comentario_seguimiento"
                                         id="textarea-default2"
                                         placeholder="Comentario llamada"
                                         row="3"
                        />
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.comentario_seguimiento"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <b-badge class="col-md-12 d-block mb-1 mt-1" pill variant="primary">
                            Agendar de activiad
                        </b-badge>

                        <label>Fecha de contacto</label>
                        <flat-pickr
                                v-model="seguimientoForm.editSeguimiento.f_actividad"
                                class="form-control"
                                :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                        />
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.f_actividad" v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for>Asunto actividad:</label>
                        <input type="text" class="form-control"
                               v-model="seguimientoForm.editSeguimiento.asunto_actividad">
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.asunto_actividad"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                        <label for>Descripcion de la actividad:</label>
                        <b-form-textarea type="text" v-model="seguimientoForm.editSeguimiento.descripcion_actividad"
                                         id="textarea-default3"
                                         placeholder="Descripción de la actividad"
                                         row="3"
                        />
                        <b-alert variant="danger" show>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.descripcion_actividad"
                                    v-text="message"></li>
                            </ul>
                        </b-alert>

                    </b-modal>
                    <!--Fin modal para editar tabla de segumimiento-->

                    <div class="row">
                        <div class="col-sm-12">
                            <b-alert variant="danger" show>
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.proforma" v-text="message"></li>
                                </ul>
                            </b-alert>


                            <table class="table table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center" style="min-width:190px">Responsable</th>
                                    <th class="text-center" style="min-width:190px">Medio Contacto</th>
                                    <th class="text-center" style="min-width:190px">Est. Comunición</th>
                                    <th class="text-center" style="min-width:190px">Est. Seguimiento</th>
                                    <th class="text-center" style="min-width:190px">Clasif. llamada</th>
                                    <th class="text-center" style="min-width:150px">Valoración</th>
                                    <th class="text-center" style="min-width:150px">Idioma de contacto</th>
                                    <th class="text-center" style="min-width:190px">Servicios Ofer.</th>
                                    <th class="text-center" style="min-width:190px">Comentario</th>
                                    <th class="text-center" style="min-width:200px">Fecha de contacto</th>
                                    <th class="text-center" style="min-width:170px">Asunto actividad</th>
                                    <th class="text-center" style="min-width:170px">Descrip. actividad</th>

                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(item, index) in seguimientoForm.seguimiento">
                                    <tr>
                                        <td style="width: 2%">
                                            <div class="d-flex">
                                                <b-button
                                                        v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                                        variant="flat-primary"
                                                        class="btn-icon rounded-circle"
                                                        v-b-modal.modal-select2
                                                        v-b-tooltip="'Editar'"
                                                        @click="obtenerSeguimientoEspecifico(item, index)"
                                                >
                                                    <feather-icon icon="Edit3Icon"/>
                                                </b-button>
                                                <b-button v-b-tooltip.hover.top="'eliminar línea'"
                                                          v-ripple.400="'rgba(255, 159, 67, 0.15)'"
                                                          class="btn-icon rounded-circle"
                                                          variant="flat-danger"
                                                          @click="eliminarLinea(item, index)">
                                                    <feather-icon icon="TrashIcon"></feather-icon>
                                                </b-button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <!--<v-select  label="text" :disabled="true" v-model="item.proforma_vendedor" :options="vendedores"></v-select>-->
                                            {{item.responsable_prospecto.name}}
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.vendedor`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            <template v-if="item.tipo_contacto == 1">
                                                <p>Llamada</p>
                                            </template>
                                            <template v-else-if="item.tipo_contacto ==2 ">
                                                <p>Mensaje</p>
                                            </template>
                                            <template v-else-if="item.tipo_contacto == 3">
                                                <p>Correo</p>
                                            </template>
                                            <template v-else>
                                                <p>otro</p>
                                            </template>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.tipo_contacto`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            <template v-if="item.estado_comunicacion == 1">
                                                <p>Efectivo</p>
                                            </template>
                                            <template v-else>
                                                <p>No efectivo</p>
                                            </template>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.estado_comunicacion`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            <template v-if="item.estado_seguimiento == 0">
                                                <p>Efectivo</p>
                                            </template>
                                            <template v-else-if="item.estado_seguimiento ==1 ">
                                                <p>No efectivo</p>
                                            </template>
                                            <template v-else-if="item.estado_seguimiento == 2">
                                                <p>Descartado</p>
                                            </template>
                                            <template v-else-if="item.estado_seguimiento == 3">
                                                <p>No interesado</p>
                                            </template>
                                            <template v-else>
                                                <p>otro</p>
                                            </template>
                                            <!--<b-form-select  disabled="true"
                                                            v-model.text="item.medio_contacto">
                                                <option value="1">Llamada</option>
                                                <option value="2">Correo</option>
                                                <option value="3">App Mensajería</option>
                                                <option value="4">Visita personal</option>
                                            </b-form-select>-->
                                            <!--<input  class="form-control" type="text" :disabled="true" v-model.text="item.medio_contacto">-->
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.estado_seguimiento`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            {{item.seguimiento_clasificacion_llamada.descripcion}}
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.clasificacion_llamada`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            <template v-if="item.valoracion_contacto == 1">
                                                <p>Ocupado</p>
                                            </template>
                                            <template v-else-if="item.valoracion_contacto ==2 ">
                                                <p>Interesado</p>
                                            </template>
                                            <template v-else-if="item.valoracion_contacto == 3">
                                                <p>Muy interesado</p>
                                            </template>
                                            <template v-else-if="item.valoracion_contacto == 4">
                                                <p>No interesado</p>
                                            </template>
                                            <template v-else>
                                                <p>otro</p>
                                            </template>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`detalleSeguimiento.${index}.telefono`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            <template v-if="item.idioma_llamada == 1">
                                                <p>Espanol</p>
                                            </template>
                                            <template v-else-if="item.idioma_llamada ==2 ">
                                                <p>Ingles</p>
                                            </template>
                                            <template v-else>
                                                <p>otro</p>
                                            </template>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.idioma_contacto`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            <ul v-for="servicio in item.descripcion_servicios">
                                                <li>{{servicio}}</li>
                                            </ul>
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.servicios_acordado`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            {{item.comentario_seguimiento}}
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.comentario_comunicion`]"
                                                        v-text="message"></li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center">
                                            {{formatDate(item.f_actividad)}}
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.fecha_actividad`]"
                                                    ></li>
                                                </ul>
                                            </b-alert>
                                        </td>
                                        <td class="text-center">
                                            {{item.asunto_actividad}}
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.asunto_actividad`]"
                                                    ></li>
                                                </ul>
                                            </b-alert>
                                        </td>
                                        <td class="text-center">
                                            {{item.descripcion_actividad}}
                                            <b-alert variant="danger" show>
                                                <ul class="error-messages">
                                                    <li :key="message"
                                                        v-for="message in errorMessages[`seguimiento.${index}.descripcion_actividad`]"
                                                    ></li>
                                                </ul>
                                            </b-alert>
                                        </td>
                                        <!--<td>
                                        <strong> {{sub_total(item) | formatMoney(2)}}</strong>
                                        </td>-->

                                    </tr>
                                    <tr></tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <!--<td colspan="4"></td>
                                    <td>Total</td>
                                    <td> <strong> {{calcular_total | formatMoney(2)}}</strong></td>-->
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <b-card-footer class="text-lg-right text-center">
                        <router-link :to="{ name: 'crm-leads' }">
                            <b-button variant="secondary" type="button" class="mx-lg-1">Cancelar</b-button>
                        </router-link>
                        <b-button :disabled="btnActionAcp != 'Aceptar lead' ? true : false" @click="archivar(5)"
                                  variant="success" type="button" class="mx-lg-1">
                            <feather-icon icon="CheckIcon" class="mr-50"></feather-icon>
                            {{ btnActionAcp }}
                        </b-button>
                        <b-button :disabled="btnActionDes != 'Descartar lead' ? true : false"
                                  @click="desactivar(form.id_lead)"
                                  variant="danger" type="button" class="mx-lg-1">
                            <feather-icon icon="Trash2Icon" class="mr-50"></feather-icon>
                            {{ btnActionDes }}
                        </b-button>
                    </b-card-footer>
                    <template v-if="loading">
                        <BlockUI>
                            <b-spinner variant="info" label="loading..."/>
                        </BlockUI>
                    </template>
                </b-card>

            </b-tabs>
        </b-tab> <!--Fin tab seguimiento de lead-->
    </b-tabs>
</template>

<script type="text/ecmascript-6">
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import flatPickr from 'vue-flatpickr-component';
    import {
        BAlert,
        BBadge,
        BButton,
        BCard,
        BCardFooter,
        BCardText,
        BDropdown,
        BDropdownDivider,
        BDropdownItem,
        BForm,
        BFormCheckbox,
        BFormDatepicker,
        BFormInput,
        BFormSelect,
        BFormTextarea,
        BInputGroup,
        BInputGroupAppend,
        BInputGroupPrepend,
        BListGroup,
        BListGroupItem,
        BModal,
        BPaginationNav,
        BRow,
        BTab,
        BTabs,
        VBModal,
        VBToggle,
        VBTooltip,
        BFormGroup,
        BSpinner,
        BCol,

    } from 'bootstrap-vue'
    import Ripple from 'vue-ripple-directive'
    import vSelect from 'vue-select'
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import lead from "../../../api/CRM/Leads";
    import seguimiento from "../../../api/CRM/SeguimientosLeads";
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import seguimientosLeads from "../../../api/CRM/SeguimientosLeads";

    export default {
        components: {
            BButton,
            BAlert,
            BFormCheckbox,
            vSelect,
            VBTooltip,
            VBToggle,
            BFormSelect,
            BCard,
            BCardFooter,
            BRow,
            BTabs,
            BTab,
            BCardText,
            BInputGroupAppend,
            BInputGroupPrepend,
            BInputGroup,
            BFormInput,
            BDropdown,
            BDropdownItem,
            BDropdownDivider,
            BBadge,
            BForm,
            BListGroup,
            BListGroupItem,
            BPaginationNav,
            BFormDatepicker,
            BFormTextarea,
            BModal,
            BFormGroup,
            BSpinner,
            BCol,
            flatPickr,
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
                municipios: [],
                form: {
                    descripcion_lead: '',
                    estado_lead: '',
                    nombre_compania: '',
                    direccion_compania: '',
                    contacto_proveedor: '',
                    razon_social: '',
                    numero_ruc: '',
                    proveedor_tipo: '',
                    clasificacion_contable: '',
                    preferencia_contacto: '',
                    codigo_prospecto: '',
                    ciudad_lead: '',
                    origen: '',
                    giro_negocio: '',
                    web_site: '',
                    facebook: '',
                    instagram: '',
                    twitter: '',
                    google_my_business: '',
                    yelp: '',
                    detalle_servicio: '',
                    zona_servicio: '',
                    comentario_lead: '',
                    id_servicio: '',
                    servicios_necesitados: '',
                    telefono_compania: '',
                    estado: '',
                    cargos: ''
                },
                seguimientoForm: {
                    seguimiento: [],
                    seguimientoDetalle: [],
                    nuevoSeguimiento: [],
                    editSeguimiento: '',
                    vendedor: '',
                    tipo_contacto: '',
                    estado_comunicacion: '',
                    estado_seguimiento: '',
                    clasificacion_llamada: '',
                    valoracion_contacto: '',
                    idioma_contacto: '',
                    servicios_ofertados: '',
                    comentario_comunicacion: '',
                    proximo_contacto: '',
                    asunto_actividad: '',
                    descripcion_actividad: '',
                    id_servicios_ofertados: '',
                    descripcion_servicios: '',
                    servicio: '',
                    u_responsable: ''
                },
                cargoslst: [],
                estados: [],
                ciudades: [],
                departamentos: [],
                origenes: [],
                giros_negocios: [],
                servicios: [],
                servicios_necesitado: [],
                servicios_ofertado: [],
                vendedores: [],
                clasificaciones_llamadas: [],
                btnAction: 'Actualizar',
                btnActionSinSalir: 'Actualizar sin salir',
                btnGuardar: 'Guardar',
                btnActionDes: 'Descartar lead',
                btnActionAcp: 'Aceptar lead',
                btnEditSeguimiento: 'Editar seguimiento',
                errorMessages: [],
                serv: {},
                inputs: [1],
                inputCounter: 1,
                tel_number: '',
                tel_number2: '',
                tel_number3: '',
                tel_number4: '',
                mail_company: '',
                numberState: null,
                numberState2: null,
                numberState3: null,
                numberState4: null,
                mailState5: null,
                submitteNumbers: [],
                submitteTelcont: [],
                submitteTelmov: [],
                submitteMailcont: [],
                submitteMailcomp: [],
                servi: ''
            }
        },
        methods: {
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
                self.tel_number4 = '';
                self.numberState4 = null;
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
                this.submitteMailcont.push(this.tel_number4);
                this.$nextTick(() => {
                    this.$refs['my-modal4'].toggle('#toggle-btn4')
                });
                let mail_contact;
                mail_contact = this.submitteMailcont;
                console.log(mail_contact);
                this.form.email_contacto = mail_contact.join(",");
                console.log(this.form.email_contacto);
            },
            handleSubmit5() {
                if (!this.checkFormValidity5()) {
                    return
                }
                this.submitteMailcomp.push(this.mail_company);
                this.$nextTick(() => {
                    this.$refs['my-modal5'].toggle('#toggle-btn5')
                });
                let mail_company;
                mail_company = this.submitteMailcomp;
                console.log(mail_company);
                this.form.email_compania = mail_company.join(",");
                console.log(this.form.email_compania);
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
                            self.form.email_contacto = '';
                            self.form.email_contacto = str.join(',');
                            console.log(self.form.email_contacto);
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
            crearArray() {
                let self = this;
                self.form.servicios_necesitados = self.servi;
                let id_array;
                id_array = this.form.servicios_necesitados.map(function (obj) {
                    return obj.id_servicio_ofr_acor;
                });
                self.form.servicio_necesitado = id_array.join(",");
                console.log(self.form.servicio_necesitado);
            },

            arrayServiciosOfertados() {
                var self = this;
                let id_array;
                let desc_array;
                id_array = this.seguimientoForm.servicios_ofertados.map(function (obj) {
                    return obj.id_servicio_ofr_acor;
                });
                desc_array = this.seguimientoForm.servicios_ofertados.map(function (obj) {
                    return obj.descripcion;
                });
                self.seguimientoForm.id_servicios_ofertados = id_array.join(",");
                self.seguimientoForm.descripcion_servicios = desc_array;
                console.log(desc_array);
                /*self.seguimientoForm.descripcion_servicios = desc_array.join(",");*/
            },
            arrayEditserviciosOfer() {
                let self = this;
                let id_array;
                let desc_array;
                id_array = this.seguimientoForm.editSeguimiento.servicios_ofertados.map(function (obj) {
                    return obj.id_servicio_ofr_acor;
                });
                desc_array = this.seguimientoForm.editSeguimiento.servicios_ofertados.map(function (obj) {
                    return obj.descripcion;
                });
                self.seguimientoForm.editSeguimiento.id_servicios_ofertados = id_array.join(",");
                self.seguimientoForm.editSeguimiento.descripcion_servicios_arr = desc_array;
                self.seguimientoForm.editSeguimiento.descripcion_servicios = desc_array.join(",");
                console.log(desc_array);
                /*self.seguimientoForm.descripcion_servicios = desc_array.join(",");*/
            },

            agregarDetalle() {
                let self = this;
                if (this.seguimientoForm.proximo_contacto && this.seguimientoForm.servicios_ofertados && this.seguimientoForm.tipo_contacto
                    && this.seguimientoForm.estado_comunicacion && this.seguimientoForm.estado_seguimiento && this.seguimientoForm.clasificacion_llamada
                    && this.seguimientoForm.idioma_contacto) {
                    this.seguimientoForm.seguimientoDetalle.push({
                        responsable_prospecto: this.form.responsable_prospecto,
                        tipo_contacto: this.seguimientoForm.tipo_contacto,
                        estado_comunicacion: this.seguimientoForm.estado_comunicacion,
                        estado_seguimiento: this.seguimientoForm.estado_seguimiento,
                        clasificacion_llamada: this.seguimientoForm.clasificacion_llamada,
                        valoracion_contacto: this.seguimientoForm.valoracion_contacto,
                        idioma_contacto: this.seguimientoForm.idioma_contacto,
                        servicios_ofertados: this.seguimientoForm.id_servicios_ofertados,
                        descripcion_servicios: this.seguimientoForm.descripcion_servicios.join(","),
                        proximo_contacto: this.seguimientoForm.proximo_contacto,
                        comentario_comunicacion: this.seguimientoForm.comentario_comunicacion,
                        fecha_actividad: this.seguimientoForm.fecha_actividad,
                        asunto_actividad: this.seguimientoForm.asunto_actividad,
                        descripcion_actividad: this.seguimientoForm.descripcion_actividad,
                        id_lead: this.form.id_lead
                    });
                    seguimiento.registrar(self.seguimientoForm, data => {
                        self.seguimientoForm.nuevoSeguimiento = data.new_seguimiento;
                        let i = 0;
                        let keyx = 0;
                        if (self.seguimientoForm.seguimiento) {
                            self.seguimientoForm.seguimiento.forEach((fila, key) => {
                                if (self.seguimientoForm.proximo_contacto === fila.proximo_contacto) {
                                    i++;
                                    keyx = key;
                                }
                            });
                        }
                        if (i === 0) {
                            this.seguimientoForm.seguimiento.unshift({
                                responsable_prospecto: this.form.responsable_prospecto,
                                tipo_contacto: this.seguimientoForm.nuevoSeguimiento.tipo_contacto,
                                estado_comunicacion: this.seguimientoForm.estado_comunicacion,
                                estado_seguimiento: this.seguimientoForm.estado_seguimiento,
                                seguimiento_clasificacion_llamada: this.seguimientoForm.clasificacion_llamada,
                                valoracion_contacto: this.seguimientoForm.valoracion_contacto,
                                idioma_llamada: this.seguimientoForm.idioma_contacto,
                                descripcion_servicios: this.seguimientoForm.nuevoSeguimiento.descripcion_servicios.split(","),
                                f_actividad: this.seguimientoForm.nuevoSeguimiento.f_actividad,
                                comentario_seguimiento: this.seguimientoForm.nuevoSeguimiento.comentario_seguimiento,
                                asunto_actividad: this.seguimientoForm.nuevoSeguimiento.asunto_actividad,
                                descripcion_actividad: this.seguimientoForm.nuevoSeguimiento.descripcion_actividad,
                                id_lead: this.form.id_lead,
                                id_seguimiento_lead: this.seguimientoForm.nuevoSeguimiento.id_seguimiento_lead,
                            });
                            this.seguimientoForm.tipo_contacto = '';
                            this.seguimientoForm.estado_comunicacion = '';
                            this.seguimientoForm.estado_seguimiento = '';
                            this.seguimientoForm.clasificacion_llamada = '';
                            this.seguimientoForm.valoracion_contacto = '';
                            this.seguimientoForm.idioma_contacto = '';
                            this.seguimientoForm.servicios_ofertados = '';
                            this.seguimientoForm.comentario_comunicacion = '';
                            let tiempoActual = Date.now();
                            this.seguimientoForm.proximo_contacto = new Date(tiempoActual);
                            this.seguimientoForm.asunto_actividad = '';
                            this.seguimientoForm.descripcion_actividad = '';

                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Ya existe una actividad con la fecha seleccionada. ',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                }
                            );
                        }
                    }, err => {
                        self.errorMessages = err;
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'XCircleIcon',
                                    text: 'Ha ocurrido un error al registrar el seguimiento. ',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    });
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ingrese información en los campos requeridos',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        }
                    );
                }
            },
            formatDate(date) {
                return moment(date).format('YYYY-MM-DD HH:MM')
            },
            eliminarLinea(item, index) {
                let self = this;
                self.$swal({
                    title: '¿Esta seguro?',
                    text: '¿ Desea Eliminar el seguimiento del lead?',
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
                            text: '¡Seguimiento eliminado!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        if (this.seguimientoForm.seguimiento.length >= 1) {
                            seguimiento.desactivar({
                                    id_seguimiento_lead: item.id_seguimiento_lead
                                },
                                data => {
                                }, err => {
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Ha ocurrido un error, intentelo de nuevo',
                                                variant: 'warning',
                                            }
                                        },
                                        {position: 'bottom-right'}
                                    );
                                    this.$router.push({
                                        name: 'crm-leads'
                                    })
                                });
                            this.seguimientoForm.seguimiento.splice(index, 1);
                        } else {
                            this.seguimientoForm.seguimiento = []
                        }
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El seguimiento no ha sido eliminado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            },
            onDateSelect2(date) {
                //console.log(date); //
                this.seguimientoForm.proximo_contacto = moment(date).format("YYYY-MM-DD HH:MM"); //
            },
            obtenerSeguimiento() {
                let self = this;
                //Array(1,2,3).includes(Number(self.$route.params.id_zona)) ? self.SoloLectura = true : self.SoloLectura = false
                seguimiento.obtenerSeguimiento({
                    id_lead: this.$route.params.id_lead
                }, data => {
                    self.seguimientoForm.seguimiento = data.seguimiento;
                    if (self.seguimientoForm.seguimiento) {
                        self.seguimientoForm.seguimiento.forEach((item) => {
                            let str = item.descripcion_servicios;
                            item.descripcion_servicios = str.split(",");
                        });
                    } else {
                        self.seguimientoForm.seguimiento.descripcion_servicios = '';
                    }
                    self.seguimientoForm.u_responsable = self.seguimientoForm.responsable_prospecto;
                    self.loading = false;
                }, err => {
                    /*console.log(err);
                    this.$toast({
                            component : ToastificationContent,
                            props : {
                                title : 'Notificación',
                                icon : 'InfoIcon',
                                text : 'Ocurrio un error al cargar datos del seguimiento',
                                variant : 'warning',
                            }
                        },
                        {
                            position : 'bottom-right'
                        }
                    );*/
                })
            },
            nuevo() {
                let self = this;
                self.loading = false;
                lead.nuevo({}, data => {
                    self.origenes = data.origenes;
                    self.departamentos = data.departamentos;
                    self.giros_negocios = data.giros_negocios;
                    self.servicios = data.servicios;
                    self.vendedores = data.vendedores;
                    self.clasificaciones_llamadas = data.clasificacion_llamada;
                    self.cargoslst = data.cargoslst;
                }, err => {
                    console.log(err);
                })
            },
            obtenerCiudad() {
                var self = this;
                self.form.ciudad_prospecto = null;
                if (self.form.estado_lead.ciudades_estado)
                    self.ciudades = self.form.estado_lead.ciudades_estado;
            },
            obtenerMunicipio() {
                var self = this;
                self.form.municipio_prospecto = null;
                if (self.form.departamento_prospecto.municipios_departamento)
                    self.municipios = self.form.departamento_prospecto.municipios_departamento;
            },
            obtenerLead() {
                let self = this;
                self.loading = true;
                lead.obtenerLead({
                    id_lead: this.$route.params.id_lead
                }, data => {
                    self.servicios_obtenidos = self.servicios_necesitado;
                    self.form = data.lead;
                    self.form.servicios_necesitados = self.servicios_obtenidos;
                    if (self.form.telefono_compania) {
                        let str = self.form.telefono_compania;
                        self.submitteNumbers = str.split(',');
                    } else {
                        self.form.telefono_compania = '';
                    }
                    if (self.form.telefono_trabajo) {
                        let str2 = self.form.telefono_trabajo;
                        self.submitteTelcont = str2.split(',');
                    } else {
                        self.form.telefono_trabajo = '';
                    }
                    if (self.form.telefono_movil) {
                        let str3 = self.form.telefono_movil;
                        self.submitteTelmov = str3.split(',');
                    } else {
                        self.form.telefono_movil = '';
                    }
                    if (self.form.email_contacto) {
                        let str4 = self.form.email_contacto;
                        self.submitteMailcont = str4.split(',');
                    } else {
                        self.form.email_contacto = '';
                    }
                    if (self.form.email_compania) {
                        let str5 = self.form.email_compania;
                        self.submitteMailcomp = str5.split(',');
                    } else {
                        self.form.email_compania = '';
                    }
                    self.estados = data.estado;
                    self.loading = false;
                    if (self.form.ciudad_prospecto) {
                        self.estados.forEach((estadox, indice) => {
                            if (estadox.id_estado === self.form.ciudad_prospecto.id_estado) {
                                self.form.estado_lead = self.estados[indice];
                                self.ciudades = self.form.estado_lead.ciudades_estado;
                            }
                        });
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error al cargar las localidades',
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-rigth'
                            });
                    }
                    if (self.form.departamento_prospecto) {
                        self.form.departamento = self.form.departamento_prospecto;
                    }
                }, err => {
                    self.loading = false;
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: 'Notificación',
                            icon: 'InfoIcon',
                            text: 'Ha ocurrido un error al cargar los datos',
                            variant: 'warning',
                            position: 'bottom-rigth'
                        }
                    });
                    console.log(err);
                    /*this.$router.push({
                        name : 'inventario-bodegas'
                    })*/
                })
            },

            obtenerServicios() {
                let self = this;
                lead.obtenerServicios({
                    id_lead: this.$route.params.id_lead
                }, data => {
                    self.servi = data.servNecesitados;
                }, err => {
                    console.log(err);
                })
            },

            registrar() {
                var self = this;
                self.btnAction = 'Guardando, espere...';
                self.loading = true;
                seguimiento.registrar(self.seguimientoForm, data => {
                    self.loading = true;
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckCircleIcon',
                                text: 'Seguimiento registrado correctamente. ',
                                variant: 'success',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.$router.push({
                        name: 'crm-leads'
                    })
                }, err => {
                    self.loading = false;
                    self.errorMessages = err;
                    self.btnAction = 'Registrar'
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'XCircleIcon',
                                text: 'Ha ocurrido un error al registrar el seguimiento. ',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                })
            },
            obtenerFechaActual() {
                let self = this;
                let tiempoActual = Date.now();
                self.seguimientoForm.proximo_contacto = new Date(tiempoActual);
            },
            obtenerSeguimientoEspecifico(item, index) {
                let self = this;
                seguimiento.obtenerSeguimientoEspecifico({
                    id_seguimiento_lead: item.id_seguimiento_lead
                }, data => {
                    self.seguimientoForm.editSeguimiento = data.seguimientoLead;
                    self.seguimientoForm.editServiciosOfertados = data.serviciosOfertados;
                    self.seguimientoForm.editSeguimiento.servicios_ofertados = self.seguimientoForm.editServiciosOfertados;
                    let id_array;
                    let desc_array;
                    id_array = this.seguimientoForm.editSeguimiento.servicios_ofertados.map(function (obj) {
                        return obj.id_servicio_ofr_acor;
                    });
                    desc_array = this.seguimientoForm.editSeguimiento.servicios_ofertados.map(function (obj) {
                        return obj.descripcion;
                    });
                    self.seguimientoForm.editSeguimiento.id_servicios_ofertados = id_array.join(",");
                    self.seguimientoForm.editSeguimiento.descripcion_servicios_arr = desc_array;
                    self.seguimientoForm.editSeguimiento.descripcion_servicios = desc_array.join(",");
                }, err => {
                    console.log(err);
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ocurrio un error al cargar datos del seguimiento',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        }
                    );
                })
            },


            editarSeguimiento() {
                let self = this;
                self.loading = true;
                self.seguimientoForm.seguimiento.forEach((item, index) => {
                    if (item.id_seguimiento_lead === self.seguimientoForm.editSeguimiento.id_seguimiento_lead) {
                        self.seguimientoForm.seguimiento.splice(index, 1, ({
                            responsable_prospecto: this.seguimientoForm.editSeguimiento.responsable_prospecto,
                            tipo_contacto: this.seguimientoForm.editSeguimiento.tipo_contacto,
                            estado_comunicacion: this.seguimientoForm.editSeguimiento.estado_comunicacion,
                            estado_seguimiento: this.seguimientoForm.editSeguimiento.estado_seguimiento,
                            seguimiento_clasificacion_llamada: this.seguimientoForm.editSeguimiento.seguimiento_clasificacion_llamada,
                            valoracion_contacto: this.seguimientoForm.editSeguimiento.valoracion_contacto,
                            idioma_llamada: this.seguimientoForm.editSeguimiento.idioma_llamada,
                            descripcion_servicios: this.seguimientoForm.editSeguimiento.descripcion_servicios_arr,
                            f_actividad: this.seguimientoForm.editSeguimiento.f_actividad,
                            comentario_seguimiento: this.seguimientoForm.editSeguimiento.comentario_seguimiento,
                            asunto_actividad: this.seguimientoForm.editSeguimiento.asunto_actividad,
                            descripcion_actividad: this.seguimientoForm.editSeguimiento.descripcion_actividad,
                            id_lead: this.seguimientoForm.editSeguimiento.id_lead,
                            id_seguimiento_lead: this.seguimientoForm.editSeguimiento.id_seguimiento_lead,
                        }));
                    } else {
                        console.log("Ocurrio un error en el push de seguimiento");
                    }
                });
                seguimiento.actualizar(self.seguimientoForm.editSeguimiento, data => {
                    self.loading = false;
                }, err => {
                    self.loading = false;
                    self.errorMessages = err
                    console.log(err);
                })
            },

            actualizar() {
                var self = this
                self.btnAction = 'Guardando, espere....'
                self.loading = true;
                lead.actualizar(self.form, data => {
                        self.loading = false;
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Datos actualizados correctamente.',
                                variant: 'success',
                                position: 'bottom-right'
                            }
                        });
                        this.$router.push({
                            name: 'crm-leads'
                        })
                    },
                    err => {
                        self.loading = false;
                        self.errorMessages = err
                        self.btnAction = 'Actualizar'
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ha ocurrido un error, intentelo de nuevo',
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        });
                        /*this.$router.push({
                            name : 'inventario-unidades-medida'
                        })*/
                    })
            },
            actualizar_sin_salir() {
                let self = this
                self.btnActionSinSalir = 'Guardando, espere....'
                self.loading = true;
                lead.actualizar(self.form, data => {
                        self.loading = false;
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Datos actualizados correctamente.',
                                variant: 'success',
                                position: 'bottom-right'
                            }
                        });
                        self.btnAction = 'Actualizar'
                    },
                    err => {
                        self.loading = false;
                        self.errorMessages = err
                        self.btnActionSinSalir = 'Actualizar sin salir'
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ha ocurrido un error, intentelo de nuevo',
                                variant: 'warning',
                                position: 'bottom-right'
                            }
                        });
                    })
            },

            activar(leadID) {
                var self = this;
                self.$swal({
                    title: '¿Esta seguro?',
                    text: '¿ Desea activar el lead?',
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
                            text: '¡Lead habilitado!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        lead.activar({
                            id_lead: leadID
                        }, data => {
                            this.$router.push({
                                name: 'crm-leads'
                            })
                        }, err => {
                            this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error, intentelo de nuevo',
                                    variant: 'warning',
                                    position: 'bottom-right'
                                }
                            });
                            this.$router.push({
                                name: 'crm-leads'
                            })
                        })
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El lead no ha sido habilitado',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        })
                    }

                })
            },

            desactivar(leadID) {
                var self = this;
                self.$swal({
                    title: 'Info!',
                    text: '¿Esta seguro de descartar el lead?',
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
                            title: '¡Descartado!',
                            text: '¡El lead ha sido eliminado!',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            }
                        });
                        lead.desactivar({
                                id_lead: leadID
                            },
                            data => {
                                this.$router.push({
                                    name: 'crm-leads'
                                })
                            }, err => {
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Ha ocurrido un error, intentelo de nuevo',
                                            variant: 'warning',
                                        }
                                    },
                                    {position: 'bottom-right'}
                                );
                                this.$router.push({
                                    name: 'inventario-proveedores'
                                })
                            })
                    } else if (result.dismiss === 'cancel') {
                        self.$swal({
                            title: 'Cancelado',
                            text: 'El lead no ha sido descartado',
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
            this.obtenerSeguimiento();
            this.obtenerServicios();
            this.obtenerLead();
            this.obtenerFechaActual();
            this.nuevo();
        }

    }
</script>
<style lang="scss">
    @import '@core/scss/vue/libs/vue-select.scss';
    @import '../../../@core/scss/vue/libs/vue-sweetalert';
    @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>
