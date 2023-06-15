<template>
    <b-tabs content-class="pt-1" fill>
        <b-tab
                active
                title="Ventas Brutas"
        >
            <b-card>
                    <!-- title and subtitle -->
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-card-title class="mb-1 d-flex align-items-center">
                                Informe de ventas brutas
                            </b-card-title>
                            <b-card-sub-title>
                                Actualizado hace unos momentos
                            </b-card-sub-title>
                        </b-col>

                        <b-col cols="12" md="6" class="d-flex align-items-center">
                            <feather-icon
                                    icon="CalendarIcon"
                                    size="24"
                                    class="mr-1"
                            />
                            <flat-pickr
                                    v-model="rangoventasbrutas"
                                    class="form-control flat-picker bg-transparent shadow-none text-center"
                                    :config="{ mode: 'range'}"
                                    placeholder="YYYY-MM-DD"
                            />

                            <b-button class="ml-1" variant="info" @click="obtenerVentasBrutas(rangoventasbrutas)">
                                Generar
                            </b-button>
                        </b-col>
                    </b-row>

                    <vue-apex-charts
                            type="line"
                            height="400"
                            :options="optionsventasbrutas"
                            :series="seriesventasbrutas"
                            :key="key_ventasbrutas"
                    />
            </b-card>
        </b-tab>

        <b-tab
                title="Ventas Netas"
        >
            <b-card>
                <b-row>
                    <b-col cols="12" md="6">
                        <b-card-title class="mb-1 d-flex align-items-center">
                            Informe de ventas netas
                        </b-card-title>
                        <b-card-sub-title>
                            Actualizado hace unos momentos
                        </b-card-sub-title>
                    </b-col>

                    <b-col cols="12" md="6" class="d-flex align-items-center">
                        <feather-icon
                                icon="CalendarIcon"
                                size="24"
                                class="mr-1"
                        />
                        <flat-pickr
                                v-model="rangoventasnetas"
                                class="form-control flat-picker bg-transparent shadow-none text-center"
                                :config="{ mode: 'range'}"
                                placeholder="YYYY-MM-DD"
                        />
                        <b-button class="ml-1" variant="info" @click="obtenerVentasNetas(rangoventasnetas)">
                            Generar
                        </b-button>
                    </b-col>
                </b-row>

                <vue-apex-charts
                        type="line"
                        height="400"
                        :options="optionsventasnetas"
                        :series="seriesventasnetas"
                        :key="key_ventasnetas"
                />
            </b-card>
        </b-tab>

        <b-tab title="Beneficio Bruto">
            <b-card>
                <b-row>
                    <b-col cols="12" md="6">
                        <b-card-title class="mb-1 d-flex align-items-center">
                            Informe de beneficio bruto
                        </b-card-title>
                        <b-card-sub-title>
                            Actualizado hace unos momentos
                        </b-card-sub-title>
                    </b-col>

                    <b-col cols="12" md="6" class="d-flex align-items-center">
                        <feather-icon
                                icon="CalendarIcon"
                                size="24"
                                class="mr-1"
                        />
                        <flat-pickr
                                v-model="rangobeneficiobruto"
                                class="form-control flat-picker bg-transparent shadow-none text-center"
                                :config="{ mode: 'range'}"
                                placeholder="YYYY-MM-DD"
                        />
                        <b-button class="ml-1" variant="info" @click="obtenerBeneficioBruto(rangobeneficiobruto)">
                            Generar
                        </b-button>
                    </b-col>
                </b-row>

                <vue-apex-charts
                        type="line"
                        height="400"
                        :options="optionsbeneficiobruto"
                        :series="seriesbeneficiobruto"
                        :key="key_beneficiobruto"
                />
            </b-card>
        </b-tab>

        <b-tab
                title="Ventas por mes"
        >
            <b-card>
                <b-row>
                    <b-col cols="12" md="12">
                        <b-card-title class="mb-1 d-flex align-items-center">
                            Informe de ventas por mes
                        </b-card-title>
                        <b-card-sub-title>
                            Actualizado hace unos momentos
                        </b-card-sub-title>
                    </b-col>
                </b-row>

                <vue-apex-charts
                        type="bar"
                        height="350"
                        :options="optionsventaspormes"
                        :series="seriesventaspormes"
                        :key="key_ventaspormes"
                />
            </b-card>
        </b-tab>

        <b-tab title="Productos más populares">
            <b-card>
                <b-row>
                    <b-col cols="12" md="12">
                        <b-card-title class="mb-1 d-flex align-items-center">
                            Informe de productos más populares
                        </b-card-title>
                        <b-card-sub-title>
                            Actualizado hace unos momentos
                        </b-card-sub-title>
                    </b-col>
                </b-row>

              <producto-popular/>
            </b-card>
        </b-tab>
        <template v-if="loading">
            <BlockUI>
                <b-spinner label="Cargando..." variant="info"/>
            </BlockUI>
        </template>
    </b-tabs>

</template>

<script>
    import {
        BCard, BCardBody, BCardHeader, BCardTitle, BCardSubTitle, BBadge, BTabs, BTab, BRow, BCol, BButton, BSpinner
    } from 'bootstrap-vue'
    import {$themeColors} from '@themeConfig'
    import VueApexCharts from 'vue-apexcharts'
    import ProductoPopular from '@/views/charts/PopularProduct.vue'
    import flatPickr from 'vue-flatpickr-component'
    import moment from '../../../../Backend/resources/app/assets/plugins/moment/moment'
    import informes from '../../api/Informes/informes'
    import ToastificationContent from "../../@core/components/toastification/ToastificationContent";


    export default {
        components: {
            VueApexCharts,
            ProductoPopular,
            BCardHeader,
            BCard,
            BBadge,
            BCardBody,
            BCardTitle,
            BCardSubTitle,
            BTabs,
            BTab,
            BRow,
            BCol,
            BButton,
            BSpinner,
            flatPickr
        },

        data(){
            return {
                loading: true,
                // variable para precargar los últimos 15 días
                ultimos15dias: [],

                // Arreglo de fechas usado para comparar las que vienen en las consultas
                listado_fechas: [],

                // Datos para el informe de ventas brutas
                rangoventasbrutas: null,
                key_ventasbrutas: 0,
                seriesventasbrutas: [
                    {
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    }
                ],
                optionsventasbrutas: {
                    chart: {
                        zoom: {
                            enabled: false,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    markers: {
                        strokeWidth: 7,
                        strokeOpacity: 1,
                        strokeColors: [$themeColors.success],
                        colors: [$themeColors.success],
                    },
                    colors: [$themeColors.success],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: 'straight',
                    },
                    grid: {
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                    },
                    tooltip: {
                        custom(data) {
                            return `${'<div class="px-1 py-50"><span>'}${
                                data.series[data.seriesIndex][data.dataPointIndex]
                            } C$</span></div>`
                        },
                    },
                    xaxis: {
                        categories: [],
                    },
                    yaxis: {
                        // opposite: isRtl,
                    },
                },

                // Datos para el informe de ventas netas
                rangoventasnetas: null,
                key_ventasnetas: 0,
                seriesventasnetas: [
                    {
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    }
                ],
                optionsventasnetas: {
                    chart: {
                        zoom: {
                            enabled: false,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    markers: {
                        strokeWidth: 7,
                        strokeOpacity: 1,
                        strokeColors: [$themeColors.success],
                        colors: [$themeColors.success],
                    },
                    colors: [$themeColors.success],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: 'straight',
                    },
                    grid: {
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                    },
                    tooltip: {
                        custom(data) {
                            return `${'<div class="px-1 py-50"><span>'}${
                                data.series[data.seriesIndex][data.dataPointIndex]
                            } C$</span></div>`
                        },
                    },
                    xaxis: {
                        categories: [],
                    },
                    yaxis: {
                        // opposite: isRtl,
                    },
                },

                // Datos para el informe de beneficio bruto
                rangobeneficiobruto: null,
                key_beneficiobruto: 0,
                seriesbeneficiobruto: [
                    {
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    }
                ],
                optionsbeneficiobruto: {
                    chart: {
                        zoom: {
                            enabled: false,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    markers: {
                        strokeWidth: 7,
                        strokeOpacity: 1,
                        strokeColors: [$themeColors.success],
                        colors: [$themeColors.success],
                    },
                    colors: [$themeColors.success],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: 'straight',
                    },
                    grid: {
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                    },
                    tooltip: {
                        custom(data) {
                            return `${'<div class="px-1 py-50"><span>'}${
                                data.series[data.seriesIndex][data.dataPointIndex]
                            } C$</span></div>`
                        },
                    },
                    xaxis: {
                        categories: [],
                    },
                    yaxis: {
                        // opposite: isRtl,
                    },
                },

                //Ventas por mes
                rangoventaspormes: null,
                key_ventaspormes: 0,
                seriesventaspormes: [
                    {
                        data: [],
                    },
                ],
                optionsventaspormes: {
                    chart: {
                        toolbar: {
                            show: false,
                        },
                    },
                    colors: $themeColors.info,
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            barHeight: '60%',
                            endingShape: 'rounded',
                        },
                    },
                    grid: {
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                    },
                    tooltip: {
                        custom(data) {
                            return `${'<div class="px-1 py-50"><span>'}${
                                data.series[data.seriesIndex][data.dataPointIndex]
                            } C$</span></div>`
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    xaxis: {
                        categories: [],
                    },
                    yaxis: {
                        // opposite: isRtl,
                    },
                },
            }
        },

        methods: {
            ultimos15Dias() {
                // Calcular los últimos 15 días
                let hoy = new Date();
                let rango = '';
                for (let i = 0; i < 15; i++) {
                    let date = new Date(hoy);
                    date.setDate(hoy.getDate() - i);
                    let fecha = moment(date).format('MM-DD');

                    if(i === 0) {
                        rango = moment(date).format('YYYY-MM-DD');
                    }
                    if (i === 14) {
                        rango = moment(date).format('YYYY-MM-DD') + " to " + rango;
                    }
                    this.ultimos15dias.unshift(fecha);
                }

                // Agregar las fechas al eje x
                this.optionsventasbrutas.xaxis.categories = this.ultimos15dias;
                this.optionsventasnetas.xaxis.categories = this.ultimos15dias;
                this.optionsbeneficiobruto.xaxis.categories = this.ultimos15dias;

                // Actualizar los keys para volver a renderizar los componentes
                this.key_beneficiobruto += 1;
                this.key_ventasbrutas += 1;
                this.key_ventasnetas += 1;
                this.key_ventaspormes += 1;

                // Asigna los campo de rango de fecha de los últimos 15 días
                this.rangoventasbrutas = rango;
                this.rangoventasnetas = rango;
                this.rangobeneficiobruto = rango;

                this.loading = false;
            },

            obtenerRango(rango) {
                // Dividir el rango en las fechas inicial y final
                const [fechaInicial, fechaFinal] = rango.split(' to ');

                // Retornar las fechas
                return {
                    fecha_inicial: fechaInicial.trim(),
                    fecha_final: fechaFinal.trim()
                };
            },

            obtenerFechas(fecha_inicial, fecha_final) {
                let fechas = [];
                let f_inicial = new Date("'" + fecha_inicial + "'");
                let f_final = new Date("'" + fecha_final + "'");
                this.listado_fechas = [];

                while(f_inicial <= f_final) {
                    this.listado_fechas.push(moment(f_inicial).format('YYYY-MM-DD'))
                    fechas.push(moment(f_inicial).format('MM-DD'));
                    f_inicial.setDate(f_inicial.getDate() + 1);
                }

                return fechas;
            },

            obtenerVentasBrutas(rango){
                // Si solo se elige una fecha no se muestra el rango
                if(this.rangoventasbrutas.length > 10){
                    let { fecha_inicial, fecha_final } = this.obtenerRango(rango);

                    // Por defecto vacía los datos del eje y para dejarlos en 0. Luego se reemplazan por los datos que encuentre.
                    this.seriesventasbrutas[0].data = [];
                    let cantidad_series = this.obtenerFechas(fecha_inicial, fecha_final).length;
                    for (let i = 0; i < cantidad_series; i++) {
                        this.seriesventasbrutas[0].data.push(0);
                    }

                    // carga los datos del eje x con las fechas
                    this.optionsventasbrutas.xaxis.categories = this.obtenerFechas(fecha_inicial, fecha_final);
                    this.loading = true;
                    informes.obtenerVentasBrutas({
                        fecha_inicial: fecha_inicial,
                        fecha_final: fecha_final,
                    }, data => {

                        if (data.length > 0) {
                            for (let i = 0; i < cantidad_series; i++) {
                                data.forEach((monto, key) => {
                                    if(this.listado_fechas[i] === monto.f_factura) {
                                        this.seriesventasbrutas[0].data[i] = monto.mt_total;
                                    }
                                });
                            }
                        }
                        this.key_ventasbrutas += 1;
                    }, err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error inesperado.',
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un rango de fechas.',
                                variant: 'info',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
                this.loading = false;
            },

            obtenerVentasNetas(rango){
                // Si solo se elige una fecha no se muestra el rango
                if(this.rangoventasnetas.length > 10){
                    let { fecha_inicial, fecha_final } = this.obtenerRango(rango);

                    // Por defecto vacía los datos del eje y para dejarlos en 0. Luego se reemplazan por los datos que encuentre.
                    this.seriesventasnetas[0].data = [];
                    let cantidad_series = this.obtenerFechas(fecha_inicial, fecha_final).length;
                    for (let i = 0; i < cantidad_series; i++) {
                        this.seriesventasnetas[0].data.push(0);
                    }

                    // carga los datos del eje x con las fechas
                    this.optionsventasnetas.xaxis.categories = this.obtenerFechas(fecha_inicial, fecha_final);
                    this.loading = true;
                    informes.obtenerVentasNetas({
                        fecha_inicial: fecha_inicial,
                        fecha_final: fecha_final,
                    }, data => {

                        if (data.length > 0) {
                            for (let i = 0; i < cantidad_series; i++) {
                                data.forEach((monto, key) => {
                                    if(this.listado_fechas[i] === monto.f_factura) {
                                        this.seriesventasnetas[0].data[i] = monto.mt_neto;
                                    }
                                });
                            }
                        }
                        this.key_ventasnetas += 1;
                    }, err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error inesperado.',
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        //self.loading = false
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un rango de fechas.',
                                variant: 'info',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
                this.loading = false;
            },

            obtenerBeneficioBruto(rango){
                // Si solo se elige una fecha no se muestra el rango
                if(this.rangobeneficiobruto.length > 10){
                    let { fecha_inicial, fecha_final } = this.obtenerRango(rango);

                    // Por defecto vacía los datos del eje y para dejarlos en 0. Luego se reemplazan por los datos que encuentre.
                    this.seriesbeneficiobruto[0].data = [];
                    let cantidad_series = this.obtenerFechas(fecha_inicial, fecha_final).length;
                    for (let i = 0; i < cantidad_series; i++) {
                        this.seriesbeneficiobruto[0].data.push(0);
                    }

                    // carga los datos del eje x con las fechas
                    this.optionsbeneficiobruto.xaxis.categories = this.obtenerFechas(fecha_inicial, fecha_final);
                    this.loading = true;
                    informes.obtenerBeneficioBruto({
                        fecha_inicial: fecha_inicial,
                        fecha_final: fecha_final,
                    }, data => {

                        if (data.length > 0) {
                            for (let i = 0; i < cantidad_series; i++) {
                                data.forEach((monto, key) => {
                                    if(this.listado_fechas[i] === monto.f_factura) {
                                        this.seriesbeneficiobruto[0].data[i] = monto.mt_total;
                                    }
                                });
                            }
                        }
                        this.key_beneficiobruto += 1;
                        //self.loading = false
                    }, err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error inesperado.',
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un rango de fechas.',
                                variant: 'info',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
                this.loading = false;
            },

            obtenerVentasPorMes() {
                const fecha_actual = new Date();
                const anio_actual = fecha_actual.getFullYear();
                const mes_actual = fecha_actual.getMonth();

                const meses = [];
                let lista_meses = [];
                for (let mes = 0; mes <= mes_actual; mes++) {
                    let fecha_mes = new Date(anio_actual, mes);
                    let nombre_mes = fecha_mes.toLocaleString('default', { month: 'long' });
                    let numero_mes = fecha_mes.getMonth() + 1;
                    meses.unshift(nombre_mes);
                    lista_meses.unshift(numero_mes);
                }

                this.optionsventaspormes.xaxis.categories = meses;
                let date = new Date();
                let fecha_final = moment(date).format('YYYY-MM-DD');
                let fecha_inicial = '2023-01-01';
                let cantidad_series = meses.length;
                for (let i = 0; i < cantidad_series; i++) {
                    this.seriesventaspormes[0].data.push(0);
                }

                informes.obtenerVentasPorMes({
                    fecha_inicial: fecha_inicial,
                    fecha_final: fecha_final,
                }, data => {

                    if (data.length > 0) {
                        for (let i = 0; i < cantidad_series; i++) {
                            data.forEach((monto, key) => {
                                if(lista_meses[i] === parseInt(monto.mes)) {
                                    this.seriesventaspormes[0].data[i] = monto.total_ventas;
                                }
                            });
                        }

                    }
                    this.key_ventaspormes += 1;
                }, err => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ha ocurrido un error inesperado.',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                })
            }
        },

        mounted() {
            this.ultimos15Dias();
            this.obtenerVentasPorMes();
        }
    }
</script>

<style lang="scss">
    @import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>