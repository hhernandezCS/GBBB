import { ref } from '@vue/composition-api'
import useAppConfig from '@core/app-config/useAppConfig'

export default function useAppCustomizer() {
  // Customizer
  const isCustomizerOpen = ref(false)

  // Skin
  const skinOptions = [
    { text: 'Claro', value: 'light' },
    { text: 'Bordes', value: 'bordered' },
    { text: 'Oscuro', value: 'dark' },
    { text: 'Semi oscuro', value: 'semi-dark' },
  ]

  // Content Width Options
  const contentWidthOptions = [
    { text: 'Ancho total', value: 'full' },
    { text: 'En caja', value: 'boxed' },
  ]

  // Router Transition
  const routerTransitionOptions = [
    { title: 'Zoom Fade', value: 'zoom-fade' },
    { title: 'Fade', value: 'fade' },
    { title: 'Fade Bottom', value: 'fade-bottom' },
    { title: 'Slide Fade', value: 'slide-fade' },
    { title: 'Zoom Out', value: 'zoom-out' },
    { title: 'None', value: 'none' },
  ]

  // Router Transition
  const layoutTypeOptions = [
    { text: 'Vertical', value: 'vertical' },
/*    { text: 'Horizontal', value: 'horizontal' },*/
  ]

  // Navbar
  const navbarColors = ['', 'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark']

  // Navbar Types
  const navbarTypes = [
    { text: 'Flotante', value: 'floating' },
    { text: 'Fijo', value: 'sticky' },
    { text: 'Estatico', value: 'static' },
    { text: 'Oculto', value: 'hidden' },
  ]

  // Footer Types
  const footerTypes = [
    { text: 'Fijo', value: 'sticky' },
    { text: 'Estatico', value: 'static' },
    { text: 'Oculto', value: 'hidden' },
  ]

  // eslint-disable-next-line object-curly-newline
  const {
    isRTL,
    skin,
    contentWidth,
    routerTransition,
    layoutType,
    isNavMenuHidden,
    isVerticalMenuCollapsed,
    navbarBackgroundColor,
    navbarType,
    footerType,
  } = useAppConfig()

  return {
    // Customizer
    isCustomizerOpen,

    // Vertical Menu
    isVerticalMenuCollapsed,

    // Skin
    skin,
    skinOptions,

    // Content Width
    contentWidth,
    contentWidthOptions,

    // RTL
    isRTL,

    // routerTransition
    routerTransition,
    routerTransitionOptions,

    // Layout Type
    layoutType,
    layoutTypeOptions,

    // NavMenu Hidden
    isNavMenuHidden,

    // Navbar
    navbarColors,
    navbarTypes,
    navbarBackgroundColor,
    navbarType,

    // Footer
    footerTypes,
    footerType,
  }
}
