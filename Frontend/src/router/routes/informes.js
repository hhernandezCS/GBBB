export default [
    // Informes de facturación
    {
        path: '/informes/facturacion',
        name: 'informes-facturacion',
        component: () => import( /* webpackChunkName: "informes" */  '@/views/Informes/InformesFacturacion'),
        meta: {
            id_menu : 161,
            pageTitle: 'Informes',
            requiresAuth: true,
            breadcrumb: [
                {
                    text: 'Ventas',
                    active: true,
                },
            ],
        },
    },
]