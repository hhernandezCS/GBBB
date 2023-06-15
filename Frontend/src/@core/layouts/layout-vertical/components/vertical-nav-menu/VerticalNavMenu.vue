<template>
    <div
            :class="[
      { 'expanded': !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered) },
      skin === 'semi-dark' ? 'menu-dark' : 'menu-light'
    ]"
            @mouseenter="updateMouseHovered(true)"
            @mouseleave="updateMouseHovered(false)"
            class="main-menu menu-fixed menu-accordion menu-shadow"
    >
        <!-- main menu header-->
        <div class="navbar-header expanded" style="height: 70px !important;">
            <slot
                    :collapseTogglerIcon="collapseTogglerIcon"
                    :toggleCollapsed="toggleCollapsed"
                    :toggleVerticalMenuActive="toggleVerticalMenuActive"
                    name="header"
            >
                <ul class="nav navbar-nav flex-row">

                    <!-- Logo & Text -->
                    <li class="nav-item mr-auto">
                        <b-link
                                class="navbar-brand"
                                to="/"
                        >
              <span class="brand-logo">
                               <template v-if="!isVerticalMenuCollapsed || isMouseHovered">
                  <b-img
                          :src="logoImage"
                          alt="logo"
                          style="max-width: 180px"
                  />
                </template>
               <template v-else-if="isVerticalMenuCollapsed || !isMouseHovered">
                  <b-img
                          :src="logoCollapsed"
                          alt="logo"
                          style="max-width: 180px"
                  />
                </template>
              </span>
                            <!--<h2 class="brand-text">
                              {{ appName }}
                            </h2>-->
                        </b-link>
                    </li>

                    <!-- Toggler Button -->
                    <li class="nav-item nav-toggle">
                        <b-link class="nav-link modern-nav-toggle">
                            <feather-icon
                                    @click="toggleVerticalMenuActive"
                                    class="d-block d-xl-none"
                                    icon="XIcon"
                                    size="20"
                            />
                            <feather-icon
                                    :icon="collapseTogglerIconFeather"
                                    @click="toggleCollapsed"
                                    class="d-none d-xl-block collapse-toggle-icon"
                                    size="20"
                            />
                        </b-link>
                    </li>
                </ul>
            </slot>
        </div>
        <!-- / main menu header-->

        <!-- Shadow -->
        <div
                :class="{'d-block': shallShadowBottom}"
                class="shadow-bottom"
        />

        <!-- main menu content-->
        <vue-perfect-scrollbar
                :settings="perfectScrollbarSettings"
                @ps-scroll-y="evt => { shallShadowBottom = evt.srcElement.scrollTop > 0 }"
                class="main-menu-content scroll-area"
                tagname="ul"
        >
            <vertical-nav-menu-items
                    :items="navMenuItems"
                    class="navigation navigation-main"
            />
        </vue-perfect-scrollbar>
        <!-- /main menu content-->
    </div>
</template>

<script>
  import navMenuItems from '@/navigation/vertical'
  import VuePerfectScrollbar from 'vue-perfect-scrollbar'
  import {BImg, BLink} from 'bootstrap-vue'
  import {computed, provide, ref} from '@vue/composition-api'
  import useAppConfig from '@core/app-config/useAppConfig'
  import {$themeConfig} from '@themeConfig'
  import VerticalNavMenuItems from './components/vertical-nav-menu-items/VerticalNavMenuItems.vue'
  import useVerticalNavMenu from './useVerticalNavMenu'
  import ajustes_generales from "../../../../../api/admon/ajustes_generales";
  import ToastificationContent from "../../../../components/toastification/ToastificationContent";

  export default {
    components: {
      VuePerfectScrollbar,
      VerticalNavMenuItems,
      BLink,
      BImg,
      logo: require('/public/logo.png')
    },
    props: {
      isVerticalMenuActive: {
        type: Boolean,
        required: true,
      },
      toggleVerticalMenuActive: {
        type: Function,
        required: true,
      },
    },
    setup(props) {
      const {
        isMouseHovered,
        isVerticalMenuCollapsed,
        collapseTogglerIcon,
        toggleCollapsed,
        updateMouseHovered,
      } = useVerticalNavMenu(props)

      const {skin} = useAppConfig()

      // Shadow bottom is UI specific and can be removed by user => It's not in `useVerticalNavMenu`
      const shallShadowBottom = ref(false)

      provide('isMouseHovered', isMouseHovered)

      const perfectScrollbarSettings = {
        maxScrollbarLength: 60,
        wheelPropagation: false,
      }

      const collapseTogglerIconFeather = computed(() => (collapseTogglerIcon.value === 'unpinned' ? 'CircleIcon' : 'DiscIcon'))

      // App Name
      const {appName, appLogoImage, appLogoCollapsed} = $themeConfig.app

      return {
        navMenuItems,
        perfectScrollbarSettings,
        isVerticalMenuCollapsed,
        collapseTogglerIcon,
        toggleCollapsed,
        isMouseHovered,
        updateMouseHovered,
        collapseTogglerIconFeather,

        // Shadow Bottom
        shallShadowBottom,

        // Skin
        skin,

        // App Name
        appName,
        appLogoImage,
      }
    },
    data() {
      return {
        recursos: [],
        logoImage: '',
        logoCollapsed: '',
      }
    },
    methods: {
      cargarAjustes() {
        let self = this;

        ajustes_generales.cargar_basico(data => {

              this.recursos = data;
              this.logoImage = this.recursos.host + this.recursos.logo_login.valor;
              this.logoCollapsed = this.recursos.host + this.recursos.logo_icon.valor;
            },
            err => {
              this.$toast({
                  component: ToastificationContent,
                  props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Hubo un problema cargando los recursos.',
                      variant: 'warning',
                  }
              },
              {
                  position: 'bottom-right'
              });
            });
      },
    },
    mounted() {
      this.cargarAjustes();
    }
  }
</script>

<style lang="scss">
    @import "~@core/scss/base/core/menu/menu-types/vertical-menu.scss";
</style>
