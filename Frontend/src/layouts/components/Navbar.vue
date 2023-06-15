<template>
  <div class="navbar-container d-flex content align-items-center">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="21"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">
      <dark-Toggler class="d-none d-lg-block" />
    </div>

    <b-navbar-nav class="nav align-items-center ml-auto">
      <b-nav-item-dropdown
        right
        toggle-class="d-flex align-items-center dropdown-user-link"
        class="dropdown-user"
      >
        <template #button-content>
          <div class="d-sm-flex d-none user-nav">
            <p class="user-name font-weight-bolder mb-0">
              {{form.userData.name}}
            </p>
            <span class="user-status">{{form.userData.rol}}</span>
          </div>
          <b-avatar
                  class="mr-1"
                  badge
                  variant="light-success"
                  badge-variant="success"
          />
        </template>

        <!--<b-dropdown-item link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="UserIcon"
            class="mr-50"
          />
          <span>Profile</span>
        </b-dropdown-item>

        <b-dropdown-item link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="MailIcon"
            class="mr-50"
          />
          <span>Inbox</span>
        </b-dropdown-item>

        <b-dropdown-item link-class="d-flex align-items-center"
                         :to="{name: 'admon-tasks'}"
        >
          <feather-icon
            size="16"
            icon="CheckSquareIcon"
            class="mr-50"
          />
          <span>Task</span>
        </b-dropdown-item>-->

        <b-dropdown-item v-b-modal.modal-no-backdrop link-class="d-flex align-items-center">
          <feather-icon
            size="16"t
            icon="CameraIcon"
            class="mr-50"
          />
          <span>Código QR</span>
        </b-dropdown-item>

        <b-dropdown-divider />

        <b-dropdown-item link-class="d-flex align-items-center" @click="logout">
          <feather-icon
            size="16"
            icon="LogOutIcon"
            class="mr-50"
          />
          <span>Cerrar sesion</span>
        </b-dropdown-item>
      </b-nav-item-dropdown>
    </b-navbar-nav>

    <!-- Generador de código QR -->
    <!-- Autores: Octavio Morales y Rommel Betancourt -->
    <b-modal
            id="modal-no-backdrop"
            hide-backdrop
            ok-only
            no-close-on-backdrop
            content-class="shadow"
            title="Generador de Código QR"
            ok-title="Cerrar"
    >
      <b-row>
        <b-col md="7">
          <div class="form-label-group">
            <!-- Campo para ingresar la URL con la que se va a generar el código -->
            <!-- Al hacer clic en el campo se selecciona el texto-->
            <b-form-input onClick="select()" id="floating-label" style="margin-top: 10px; margin-bottom: 10px;" placeholder="Pegue su URL aquí" v-model="url"/>
            <label for="floating-label">Guarde la imagen del código generado</label>

            <!-- Si el campo está vacío, se crea por defecto un código QR con texto pidiendo que se ingrese una URL -->
            <template v-if="!url">
              <qr-code id="qrcode_vacio" :text="'Ingrese una URL'" error-level="M" class="d-flex justify-content-center" style="margin-bottom: 10px;"></qr-code>
            </template>
            <template v-else>
              <qr-code id="qrcode" :text="url" error-level="M" class="d-flex justify-content-center" style="margin-bottom: 10px;"></qr-code>
            </template>

            <!-- Botón para descargar la imagen del código -->
            <div class="text-center">
              <b-button :disabled="!url" v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success" @click="downloadURI(getdata, 'qrcode.png')">
                <feather-icon size="14" icon="ArrowDownCircleIcon"/> Descargar
              </b-button>
            </div>
          </div>
        </b-col>
        <b-col md="5">
          <b-img src="../../assets/images/pages/codigoqr.png" fluid alt="Código QR"/>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import {
  BLink, BNavbarNav, BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar, BModal, VBModal, BAlert, BImg, BFormInput, BRow, BCol, BButton
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import DarkToggler from '@core/layouts/components/app-navbar/components/DarkToggler.vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '../../store/index'
import VueRouter from 'vue-router'
const { isNavigationFailure, NavigationFailureType } = VueRouter;
import user from '../../api/admon/usuarios';

export default {
  components: {
    BLink,
    BNavbarNav,
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,
    BModal,
    BAlert,
    BImg,
    BFormInput,
    BRow,
    BCol,
    BButton,
    // Navbar Components
    DarkToggler,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      url: '',
      dataURL: '',
      form:{
        userData:{}
      },
      userData2: {},
    }
  },
  computed: {
    getdata(){
      this.dataURL = document.querySelector('#qrcode').querySelector('img').src;
      return this.dataURL;
    },
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  methods:{
    getUserData() {
      const self = this;
      user.me(data=>{
        self.form.userData = data.userData[0];
      },
      err=>{
        // console.log('Error detectado' + err);
      })
    },
    downloadURI(uri, name) {
      const link = document.createElement("a");
      link.href = uri;
      link.download = name;
      link.click();
    },
     logout() {
       this.$store.dispatch('logout');
        if (this.$route.path !== '/login'){ this.$router.push('/login',()=>{}); }
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Good Bye',
            icon: 'CoffeeIcon',
            variant: 'success',
            text: 'Sesion cerrada correctamente.!',
          },
        })
    },
  },
  mounted() {
    this.getUserData();
  }
}
</script>
