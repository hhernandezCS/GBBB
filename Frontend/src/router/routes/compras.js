export default [
  {
    path: '/compras/clasificacion-compras',
    name: 'clasificacion-compras',
    component: () => import( /* webpackChunkName: "compras-clasificacion" */  '@/views/Compras/clasificacion_compra/Listado'),
    meta: {
      id_menu: 35,
      pageTitle: 'Clasificacion de compras',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Clasificacion de compras',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/clasificacion-compras/registrar',
    name: 'registrar-clasificacion-compras',
    component: () => import( /* webpackChunkName: "compras-clasificacion" */  '@/views/Compras/clasificacion_compra/Registrar'),
    meta: {
      id_menu: 35,
      pageTitle: 'Clasificacion de compras',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Clasificacion de compras',
          active: false,
          to:'/compras/clasificacion-compras'
        },        {
          text: 'Registrar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/clasificacion-compras/actualizar/:id_clasificacion_servicio',
    name: 'actualizar-clasificacion-compras',
    component: () => import( /* webpackChunkName: "compras-clasificacion" */  '@/views/Compras/clasificacion_compra/Actualizar'),
    meta: {
      id_menu: 35,
      pageTitle: 'Clasificacion de compras',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Clasificacion de compras',
          active: false,
          to:'/compras/clasificacion-compras'
        },        {
          text: 'Actualizar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/solicitudes-compra',
    name: 'solicitudes-compras',
    component: () => import( /* webpackChunkName: "compras-solicitudes" */  '@/views/Compras/solicitudes_compras/ListadoSolicitudesCompra'),
    meta: {
      id_menu: 35,
      pageTitle: 'Solicitudes de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Solicitudes de compra',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/solicitudes-compras/registrar',
    name: 'registrar-solicitudes-compras',
    component: () => import( /* webpackChunkName: "compras-solicitudes" */  '@/views/Compras/solicitudes_compras/RegistrarSolicitud'),
    meta: {
      id_menu: 35,
      pageTitle: 'Registrar solicitud de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Solicitudes de compra',
          active: false,
          to:'/compras/solicitudes-compra'
        },        {
          text: 'Registrar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/solicitudes-compras/actualizar/:id_solicitud_compra',
    name: 'actualizar-solicitudes-compras',
    component: () => import( /* webpackChunkName: "compras-solicitudes" */  '@/views/Compras/solicitudes_compras/ActualizarSolicitudCompra'),
    meta: {
      id_menu: 35,
      pageTitle: 'Actualizar solicitud de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Solicitudes de compra',
          active: false,
          to:'/compras/solicitudes-compra'
        },        {
          text: 'Actualizar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/solicitudes-compras/revisar/:id_solicitud_compra',
    name: 'revisar-solicitudes-compras',
    component: () => import( /* webpackChunkName: "compras-solicitudes" */  '@/views/Compras/solicitudes_compras/RevisarSolicitudCompra'),
    meta: {
      id_menu: 35,
      pageTitle: 'Revisar solicitud de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Solicitudes de compra',
          active: false,
          to:'/compras/solicitudes-compra'
        },        {
          text: 'Revisar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/solicitudes-compras/mostrar/:id_solicitud_compra',
    name: 'mostrar-solicitudes-compras',
    component: () => import( /* webpackChunkName: "compras-solicitudes" */  '@/views/Compras/solicitudes_compras/MostrarSolicitud'),
    meta: {
      id_menu: 35,
      pageTitle: 'Revisar solicitud de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Solicitudes de compra',
          active: false,
          to:'/compras/solicitudes-compra'
        },        {
          text: 'Revisar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/ordenes-compra',
    name: 'ordenes-compras',
    component: () => import( /* webpackChunkName: "compras-ordenes" */  '@/views/Compras/ordenes_compras/Listado'),
    meta: {
      id_menu: 35,
      pageTitle: 'Ordenes de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Ordenes de compra',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/ordenes-compra/registrar',
    name: 'ordenes-compras-registrar',
    component: () => import( /* webpackChunkName: "compras-ordenes" */  '@/views/Compras/ordenes_compras/Registrar'),
    meta: {
      id_menu: 35,
      pageTitle: 'Ordenes de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Ordenes de compra',
          active: false,
          to:'/compras/ordenes-compra'
        },
        {
          text: 'Registrar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/ordenes-compras/revisar/:id_orden_compra',
    name: 'revisar-ordenes-compras',
    component: () => import( /* webpackChunkName: "compras-ordenes" */  '@/views/Compras/ordenes_compras/RevisarOrdenCompra'),
    meta: {
      id_menu: 35,
      pageTitle: 'Revisar orden de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Ordenes de compra',
          active: false,
          to:'/compras/ordenes-compra'
        },        {
          text: 'Revisar',
          active: true,
        },
      ],
    },
  },
  {
    path: '/compras/ordenes-compras/mostrar/:id_orden_compra',
    name: 'mostrar-ordenes-compras',
    component: () => import( /* webpackChunkName: "compras-ordenes" */  '@/views/Compras/ordenes_compras/VistaPrevia'),
    meta: {
      id_menu: 35,
      pageTitle: 'Revisar orden de compra',
      requiresAuth: true,
      breadcrumb: [
        {
          text: 'Ordenes de compra',
          active: false,
          to:'/compras/ordenes-compra'
        },        {
          text: 'Vista previa',
          active: true,
        },
      ],
    },
  },
]
