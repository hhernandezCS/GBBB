<template>
  <b-card
          class="card-statistics"
          no-body
  >
    <b-card-header>
      <b-card-title>Estadisticas</b-card-title>
      <b-card-text class="mr-25 mb-0">
        Actualizado a las <b>{{ ultima_actualizacion }}</b>
        <b-button class="ml-1" variant="outline-info" v-b-tooltip.hover.top="'Actualizar estadísticas'" @click="actualizarEstadisticas()">
          <feather-icon
                  icon="RefreshCwIcon"
                  size="16"
          />
        </b-button>
      </b-card-text>
    </b-card-header>
    <b-card-body class="statistics-body">
      <b-row>
        <b-col
                :class="item.customClass"
                :key="item.icon"
                class="mb-2 mb-md-0"
                md="4"
                sm="6"
                v-for="item in statisticsItems"
        >

        <b-media no-body>
          <b-media-aside
              class="mr-2"
          >
            <b-avatar
                :variant="item.color"
                size="48"
            >
              <feather-icon
                  :icon="item.icon"
                  size="24"
              />
            </b-avatar>
          </b-media-aside>
          <b-media-body class="my-auto">
            <h4 class="font-weight-bolder mb-0">
              {{ item.title }}
            </h4>
            <b-card-text class="font-small-3 mb-0">
              {{ item.subtitle }}
            </b-card-text>
          </b-media-body>
        </b-media>

        </b-col>

        <b-col cols="12" md="6" sm="6" class="mt-3 mb-1">
          <h4 class="text-left">Ventas totales de la semana</h4>
        </b-col>

        <b-col cols="12" md="12" sm="12">
          <vue-apex-charts
                  type="bar"
                  height="350"
                  :options="optionsventaspordia"
                  :series="seriesventaspordia"
                  :key="key_ventaspordia"
          />
        </b-col>
      </b-row>
      <template v-if="loading">
        <BlockUI>
          <b-spinner label="Cargando..." variant="info"/>
        </BlockUI>
      </template>
    </b-card-body>
  </b-card>

</template>

<script>
  import {
    BAvatar,
    BCard,
    BCardBody,
    BCardHeader,
    BCardText,
    BCardTitle,
    BCardSubTitle,
    BCol,
    BMedia,
    BMediaAside,
    BMediaBody,
    BRow,
    BButton,
    BSpinner,
    VBTooltip,
  } from 'bootstrap-vue'
  import ajustes_generales from "../../api/admon/ajustes_generales";
  import {$themeColors} from '@themeConfig'
  import VueApexCharts from 'vue-apexcharts'
  import moment from '../../../../Backend/resources/app/assets/plugins/moment/moment'
  import informes from '../../api/Informes/informes'
  import ToastificationContent from "../../@core/components/toastification/ToastificationContent";

  export default {
    components: {
      VueApexCharts,
      BRow,
      BCol,
      BCard,
      BCardHeader,
      BCardTitle,
      BCardSubTitle,
      BCardText,
      BCardBody,
      BMedia,
      BAvatar,
      BMediaAside,
      BMediaBody,
      BButton,
      BSpinner,
      VBTooltip,
    },

    directives: {
      'b-tooltip': VBTooltip,
    },

    data() {
      return {
        loading: true,
        ultima_actualizacion: '',
        countFacturas: 0,
        statisticsItems: [],
        userRole:'',
        resultado: {},
        // Ventas por mes
        rangoventaspordia: null,
        key_ventaspordia: 0,
        seriesventaspordia: [
          {
            data: [],
          },
        ],
        optionsventaspordia: {
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
      //
      getDataDashboard() {
        let self = this;
        ajustes_generales.obtenerEstadisticas(response => {
          self.countFacturas = response;
          this.statisticsItems = [];
          this.statisticsItems.push(self.countFacturas.facturas, self.countFacturas.clientes, self.countFacturas.productos);
        })
      },

      obtenerVentasPorDia() {
        this.loading = true;
        informes.obtenerVentasPorDia(data => {
          if (data.length > 0) {
            this.seriesventaspordia[0].data = [];
            this.optionsventaspordia.xaxis.categories = [];
              data.forEach((monto, key) => {
                  if(monto.numero_dia === 1 || monto.numero_dia === '1') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Lunes');
                  } else if (monto.numero_dia === 2 || monto.numero_dia === '2') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Martes');
                  } else if (monto.numero_dia === 3 || monto.numero_dia === '3') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Miércoles');
                  } else if (monto.numero_dia === 4 || monto.numero_dia === '4') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Jueves');
                  } else if (monto.numero_dia === 5 || monto.numero_dia === '5') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Viernes');
                  } else if (monto.numero_dia === 6 || monto.numero_dia === '6') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Sábado');
                  } else if (monto.numero_dia === 7 || monto.numero_dia === '0') {
                    this.seriesventaspordia[0].data.push(monto.total_por_dia);
                    this.optionsventaspordia.xaxis.categories.push('Domingo');
                  }
                });
          }
          this.key_ventaspordia += 1;

          let fecha = new Date();
          let hora = fecha.getHours();
          hora = hora.toString().padStart(2, '0');
          let minutos = fecha.getMinutes();
          minutos = minutos.toString().padStart(2, '0');
          let segundos = fecha.getSeconds();
          segundos = segundos.toString().padStart(2, '0');
          this.ultima_actualizacion = hora + ':' + minutos + ':' + segundos;
          this.loading = false;
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
      },

      actualizarEstadisticas() {
        this.getDataDashboard();
        this.obtenerVentasPorDia();
      }
    },
    mounted() {
      this.getDataDashboard();
      this.obtenerVentasPorDia();
    }
  }
</script>
