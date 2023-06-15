<template>
    <!--  HEADER ACTIONS-->
    <div>
        <div class="text-center">
            <b-button variant="secondary" @click="print()">
                <i class="fas fa-print"></i> Imprimir
            </b-button>
            <router-link :to="{ name: 'cajabanco-facturas' }" class="pl-1">
                <b-button
                        class="mx-lg-1 mx-0"
                        type="button"
                        variant="danger"
                >
                    Cancelar
                </b-button>
            </router-link>
        </div>
        <br>
        <!--    BODY DE LA FACTURA-->
        <div id="printarea" class="print-only"
             style="display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: center; width: 400px; margin: auto">
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: center;margin-bottom: 3rem; width: 400px">
                <div class="justify-content-center" style="width: 100%">
                    <!--            HEADER LOGO DE LA EMPRESA-->
                    <div class="text-center col_logo"
                         style="font-size:20px;font-weight:bold;justify-content: center;width:400px;display:flex">
                        <img class="logo_empresa" :src="appLogo" alt="Logo de la empresa" height="80" width="218">
                    </div>
                    <br>
                </div>
                <br>
                <!--        HEADER DATOS DE LA EMPRESA-->
                <div class="d-flex flex-column justify-content-center align-items-center"
                     style="width: 100%; margin-left: 30px">
                    <template>
                        <h1 class="info_header_h1">
                            {{ ajustes && ajustes.length > 2 ? ajustes[0].valor : '' }}
                        </h1>
                        <p class="info_header_paraph">
                            {{ ajustes && ajustes.length > 2 ? ajustes[1].valor : '' }}
                        </p>
                        <p class="info_header_paraph">
                            RUC: {{ ajustes && ajustes.length > 2 ? ajustes[3].valor : '' }}
                        </p>
                        <p class="info_header_paraph">
                            Teléfono: {{ ajustes && ajustes.length > 2 ? ajustes[2].valor : '' }}
                        </p>
                        <p class="info_header_paraph">
                            WhatsApp: 78863669
                        </p>
                    </template>
                </div>
                <br>
                <!--       HEADER DATOS GENERALES DE LA FACTURA-->
                <div class="d-flex justify-content-left" style="width: 100%; text-align: left; margin-left: 30px">
                    <template>
                        <ul style="width: 100%; padding: 0">
                            <li class="info_dato_factura">Fecha Hora: {{ fecha_factura }}</li>
                            <li class="info_dato_factura">Factura: {{ form.no_documento }}</li>
                            <li class="info_dato_factura">Moneda: {{ form.factura_moneda.descripcion }}</li>
                            <li class="info_dato_factura">Cliente: {{ form.factura_cliente.nombre_comercial }}</li>
                            <li class="info_dato_factura">{{ (form.factura_cliente.tipo_persona &&
                                form.factura_cliente.tipo_persona === 1)? `RUC-C: ${form.factura_cliente.numero_ruc}` :
                                `CÉDULA: ${form.factura_cliente.numero_cedula}`}}
                            </li>
                            <li class="info_dato_factura">Vendedor: {{ form.factura_vendedor.nombre_completo }}</li>
                            <li class="info_dato_factura">Condición: Contado</li>
                            <li class="info_dato_factura mt-1">T/C: {{ form.t_cambio }}</li>
                        </ul>
                    </template>
                </div>
                <br>
                <div class="d-flex justify-content-left">
                    <table class="text-center tabla_factura">
                        <thead class="table_head_detalle_facura">
                        <tr>
                            <th class="col-3">Descripción</th>
                            <th class="col-3">cantidad</th>
                            <th class="col-3" style="text-align: left;">Precio/U</th>
                            <th class="col-3" style="text-align: right;">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(producto, index) in form.factura_productos">
                            <tr :key="`producto-${producto.id}-${index}-desc`" class="align-middle">
                                <td colspan="4" class="pl-1 word_break"
                                    style="text-align: left; width: 180px; padding-top:2px; padding-bottom: 2px;">{{
                                    producto.descripcion_producto }}
                                </td>
                            </tr>
                            <tr :key="`producto-${producto.id}-${index}-cant-precio`" class="align-middle"
                                style="color:#000000; -webkit-print-color-adjust: exact;">
                                <td colspan="2" class="datos_detalle_factura" style="text-align:right;">{{
                                    producto.cantidad }}
                                </td>
                                <td class="datos_detalle_factura" style="text-align: center; width: 120px">{{
                                    producto.precio }}
                                </td>
                                <td class="datos_detalle_factura word_break" colspan="2"
                                    style="text-align: right; width: 120px">{{ producto.cantidad * producto.precio |
                                    formatMoney(2) }}
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="d-flex justify-content-end container_tabla_totales_factura"
                     style="width: 430px; margin-left: 20px">
                    <table class="tabla_totales_factura">
                        <tbody>
                        <template>
                            <tr class="align-middle montos_totales">
                                <td>MONTO TOTAL(C$):</td>
                                <td class="montos_totales word_break">
                                    {{ form.mt_total | formatMoney(2) }}
                                    <!--                  {{ monto_viapago | formatMoney(2) }}-->
                                </td>
                            </tr>
                            <tr class="align-middle montos_totales">
                                <td class="montos_totales word_break">MONTO TOTAL($):</td>
                                <td>
                                    {{ form.mt_total_me | formatMoney(2) }}
                                   <!--   {{ monto_viapago_dolares | formatMoney(2) }}-->
                                </td>
                            </tr>
                            <template v-for="(via_pago, index) in factura_via_pago_formatted">
                                <tr :key="`via_pago-${via_pago.id}-${index}`" class="align-middle">
                                    <td style="width: 200px">{{ `${via_pago.descripcion.toUpperCase()} :` }}</td>
                                    <td class="word_break">{{ via_pago.id_moneda === 1? `C$ ${via_pago.monto}`: `$
                                        ${via_pago.monto_me}`}}
                                    </td>
                                </tr>
                            </template>
                            <tr class="align-middle">
                                <td>CAMBIO(C$):</td>
                                <td>
                                    {{ form.pago_vuelto | formatMoney(2) }}
                                </td>
                            </tr>
                            <br>
                            <br>
                            <tr class="align-middle">
                                <td>TOTAL ITEMS:</td>
                                <td>
                                    {{ form.factura_productos && form.factura_productos.length ?
                                    form.factura_productos.length : '-' }}
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="d-flex justify-content-left" style="width: 100%; text-align: left; margin-left: 20px">
                    <template>
                        <ul style="width: 100%; padding: 0">
                            <li class="info_dato_factura">AUT.MIFIN N<small><b>o</b></small>. 9005020-0-0</li>
                        </ul>
                    </template>
                </div>
                <br>
                <div style="display: flex; justify-content: center;">
                    <div style="font-weight: bold;">
                        <div>
                            <div class="row text-center align-content-center mt-3 mb-3" style="font-size:18px">
                                <p><i>Gracias por su compra.</i></p>
                            </div>
                            <div class="row">
                                <hr class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import factura from "../../../api/CajaBanco/facturas";
    import axios from "axios";
    import Logo from "@core/layouts/components/Logo";
    import {
        BRow, BCol, BCard, BCardBody, BTableLite, BCardText, BButton, BAlert, BLink, VBToggle,
    } from 'bootstrap-vue'
    import ajustes_generales from "@/api/admon/ajustes_generales";

    export default {
        data() {
            return {
                print() {
                    const printContent = document.getElementById("printarea").innerHTML;
                    const originalContent = document.body.innerHTML;
                    document.body.innerHTML = printContent;
                    window.print();
                    document.body.innerHTML = originalContent;
                    this.goBack();
                },
                goBack() {
                    window.location.reload();
                },
                loading: true,
                msg: 'Cargando el contenido espere un momento',
                url: '/public' + loadingImage,
                format: "dd-MM-yyyy",
                descargando: false,
                ajustes: {
                    valor: "",
                },
                form: {
                    mt_total: 0,
                    mt_total_me: 0,
                    f_creacion: "",
                    codigo_factura: "",
                    f_factura: "",
                    id_tipo_factura: "",
                    id_cliente: 0,
                    id_bodega: 0,
                    detalleProductos: [],
                    factura_cliente: [],
                    factura_bodega: [],
                    factura_productos: [],
                    factura_sucursal: [],
                    factura_via_pago: [],
                    factura_moneda: [],
                    factura_vendedor: [],
                    factura_tipo: [],
                    log_registro: [],
                    total: 0,
                    sub_total: 0,

                },
                totalMonto: 0,
                btnAction: "Registrar",
                cambio: 0,
                appLogo: '',
                appLogopla: require('/public/logotipo.png'),
                errorMessages: []
            };
        },

        components: {
            Logo,
            BRow,
            BCol,
            BCard,
            BCardBody,
            BTableLite,
            BCardText,
            BButton,
            BAlert,
            BLink,
        },
        watch: {
            totalMonto: function () {
                this.total_cambio();
            },
            'form.mt_total': function () {
                this.total_cambio();
            },
            'form.f_creacion': function () {
                this.handle_date_creation_format()
            },
        },
        methods: {
            covertToFixed(value) {
                return value.toFixed(2)
            },
            cargarAjustes() {
                let self = this;
                ajustes_generales.cargarRecursos(data => {

                        this.recursos = data;
                        this.appLogo = this.recursos.host + this.recursos.logo_factura.valor;
                    },
                    err => {
                    });
            },
            downloadItem(url) {
                if (!this.descargando) {
                    this.descargando = true;
                    alertify.success("Descargando datos, espere un momento.....", 5);
                    axios.get(url, {responseType: 'blob'})
                        .then(({data}) => {
                            let blob = new Blob([data], {type: 'application/pdf'})
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'ReporteFactura.pdf';
                            link.click()
                            this.descargando = false;
                        }).catch(function (error) {
                        alertify.error("Error Descargando archivo.....", 5);
                        this.descargando = false;
                    })
                } else {
                    alertify.warning("Espere a que se complete la descarga anterior", 5);
                }
            },
            formatDate(date) {
                return moment(date).format('YYYY-MM-DD H:i')
            },
            obtenerFactura() {
                var self = this;
                factura.obtenerFactura(
                    {
                        id_factura: this.$route.params.id_factura,
                        cargar_dependencias: true
                    },
                    data => {
                        self.loading = false;
                        self.form = data.factura;
                        self.ajustes = data.ajustes_basicos;
                        console.log(data.factura, 'factura data')
                        console.log(data, 'data')
                    },
                    err => {
                        self.loading = false;
                    }
                );
            },
            total_cambio() {
                this.cambio = Math.abs(this.totalMonto - this.form.mt_total).toFixed(2);
            },
            handle_date_creation_format() {
                const fechaCreacion = new Date(this.form.f_creacion);
                this.form.f_creacion = moment(fechaCreacion).format('YYYY-MM-DD h:mm a');
            },
            configurarZoom() {
                document.body.style.zoom = '100%';
            }
        },
        computed: {
            factura_via_pago_formatted() {
                return this.form.factura_via_pago.map(value => {
                    return {
                        id_moneda: value.id_moneda,
                        descripcion: value.descripcion,
                        monto: Number(value.monto).toFixed(2),
                        monto_me: Number(value.monto_me).toFixed(2)
                    }
                })
            },
            total_cantidad_despachada() {
                return this.form.factura_productos.reduce((carry, item) => {
                    return (carry + Number(item.cantidad_despachada))
                }, 0)
            },
            fecha_factura() {
                return this.form.f_creacion
            },
            total_subt() {
                return this.form.factura_productos.reduce((carry, item) => {
                        return (carry + Number((Number(item.cantidad_despachada * item.precio_unitario).toFixed(2))));
                    }
                    , 0)
            },
            gran_total() {
                return this.form.factura_productos.reduce((carry, item) => {
                        return (carry + Number((Number(item.cantidad_despachada * item.precio_unitario).toFixed(2))));
                    }
                    , 0)
            },

            monto_viapago() {
                this.totalMonto = 0;
                for (let i = 0; i < this.form.factura_via_pago.length; i++) {
                    this.totalMonto += parseFloat(this.form.factura_via_pago[i].monto);
                }
                return this.totalMonto.toFixed(2).replace(/^0+/, '');
            },
            monto_viapago_dolares() {
                this.totalMonto_me = 0;
                for (let i = 0; i < this.form.factura_via_pago.length; i++) {
                    this.totalMonto_me += parseFloat(this.form.factura_via_pago[i].monto_me);
                }
                return this.totalMonto_me.toFixed(2).replace(/^0+/, '');
            },

        },
        mounted() {
            this.configurarZoom();
            this.obtenerFactura();
            this.cargarAjustes();
        }
    };
</script>

<style lang="scss" scoped>
    @import "src/@core/scss/base/pages/app-invoice";
</style>

<style lang="scss">
    .info_header_paraph {
        margin: 0;
        font-size: 16px;
        line-height: 1.5;
        font-weight: 500;
        text-align: center;
        width: 410px;
    }

    .info_header_h1 {
        text-align: center;
        font-weight: bold;
        font-size: 16px
    }

    .info_dato_factura {
        color: #1b1b27;
        font-weight: 500;
        font-size: 16px;
        list-style-type: none;
        padding-left: 8px;
        text-align: left;
        line-height: 1.5
    }

    .tabla_factura {
        width: 400px;
        margin-left: 20px;
        padding-bottom: 4px;
        font-size: 16px;
    }

    .table_head_detalle_facura {
        margin-top: 2px;
        border-top: 1px dotted gray;
        border-bottom: 1px dotted gray;
        padding-top: 8px;
    }

    .container_tabla_totales_factura {
        border-top: 1px dotted gray;
    }

    .tabla_totales_factura {
        color: #000000;
        -webkit-print-color-adjust: exact;
        text-align: right;
        font-size: 14px;
        margin-top: 16px;
    }

    .montos_totales {
        width: 180px;
        font-weight: bold;
    }

    .info_footer_detalles {
        color: #000000;
        -webkit-print-color-adjust: exact;
        font-weight: bold;
        list-style-type: none;
        font-size: 14px;
        padding-left: 12px;
        text-align: left;
        line-height: 1.5
    }

    .word_break {
        //display: block;
        box-sizing: border-box;
        white-space: normal;
        word-break: break-word;
    }

    @media print {
        body {
            background-color: white !important;
            width: 80mm;
        }
        .content_row {
            display: flex;
            justify-content: center;
        }
        .col_logo {
            display: flex;
            justify-content: right;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-top: 2rem;
        }
        .logo_empresa {
            width: 250px;
            height: 100px;
        }
        #fin {
            page-break-after: always;
        }
        .page-number {
            display: none;
        }
        #page-number-container {
            display: none;
        }
        nav.header-navbar {
            display: none;
        }
        .main-menu {
            display: none;
        }
        .header-navbar-shadow {
            display: none !important;
        }
        .content.app-content {
            margin-left: 0;
            padding-top: 2rem !important;
        }
        footer.footer {
            display: none;
        }
        .card {
            background-color: transparent;
            box-shadow: none;
        }
        .customizer-toggle {
            display: block !important;
        }
        .center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        @media print {
            #printarea {
                display: none; //display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: center;margin-bottom: 3rem; width: 230px
            }
            @page {
                size: 80mm 297mm;
                //margin: 0 -4cm 2cm -4.90cm ;
                //
                //margin-right: -20mm;
                //margin-left: 20mm;
                //margin-top: -95mm;
            }
            // Agregar salto de página después del contenido
            .invoice-preview-wrapper {
                page-break-after: always;
            }
            /* Ocultar encabezado */
            #header {
                display: none;
            }
        }
        // Estilos específicos para la factura
        .invoice-preview-wrapper {
            .row.invoice-preview {
                .col-md-8 {
                    max-width: 100%;
                    flex-grow: 1;
                }

                .invoice-preview-card {
                    .card-body:nth-of-type(2) {
                        .row {
                            > .col-12 {
                                max-width: 50% !important;
                            }

                            .col-12:nth-child(2) {
                                display: flex;
                                align-items: flex-start;
                                justify-content: flex-end;
                                margin-top: 0 !important;
                            }
                        }
                    }
                }
            }

            // Columna derecha de acciones
            .invoice-actions {
                display: none;
            }
        }
    }
</style>