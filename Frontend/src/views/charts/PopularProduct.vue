<template>
  <b-card
      no-body
      class="card-browser-states"
  >
<!--    <b-card-header>


            <b-dropdown
                variant="link"
                no-caret
                class="chart-dropdown"
                toggle-class="p-0"
            >
              <template #button-content>
                <feather-icon
                    icon="MoreVerticalIcon"
                    size="18"
                    class="text-body cursor-pointer"
                />
              </template>

              <b-dropdown-item @click="actualizarDatos('last28Days')">
                Ultimos 7 dias
              </b-dropdown-item>
              <b-dropdown-item @click="actualizarDatos('last28Days')">
                Ultimos 28 días
              </b-dropdown-item>
            </b-dropdown>
    </b-card-header>-->

    <!-- body -->
    <b-card-body>

      <div
          v-for="(browser,index) in browserData"
          :key="browser.name"
          class="browser-states"
      >
        <b-media no-body>
          <b-media-aside class="mr-1">
            <!--            <feather-icon
                            :icon="browser.browserImg"
                            size="24"
                        />-->
          </b-media-aside>
          <b-media-body>
            <h6 class="align-self-center my-auto">
              {{ browser.name }}
            </h6>
          </b-media-body>
        </b-media>
        <div class="d-flex align-items-center">
          <span class="font-weight-bold text-body-heading mr-1">{{ browser.usage }}</span>
          <vue-apex-charts
              type="radialBar"
              height="30"
              width="30"
              :options="chartData[index].options"
              :series="chartData[index].series"
          />
        </div>
      </div>
    </b-card-body>
    <!--/ body -->
  </b-card>
</template>

<script>
import {
  BCard, BCardHeader, BCardTitle, BCardText, BCardBody, BMedia, BMediaAside, BMediaBody, BImg, BDropdown, BDropdownItem,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import {$themeColors} from '@themeConfig'
import ajustes_generales from "@/api/admon/ajustes_generales";
/* eslint-disable global-require */
const $trackBgColor = '#e9ecef'
export default {
  components: {
    BCard,
    BCardHeader,
    BCardTitle,
    BCardText,
    BCardBody,
    BMedia,
    BMediaAside,
    BMediaBody,
    BImg,
    BDropdown,
    BDropdownItem,
    VueApexCharts,
  },
  data() {
    return {
      chartData: [],
      chartClone: {},
      chartColor: [$themeColors.primary, $themeColors.warning, $themeColors.secondary, $themeColors.info, $themeColors.danger,$themeColors.primary, $themeColors.warning, $themeColors.secondary, $themeColors.info, $themeColors.danger],
      chartSeries: [],
      browserData: [],
      chart: {
        series: [65],
        options: {
          grid: {
            show: false,
            padding: {
              left: -15,
              right: -15,
              top: -12,
              bottom: -15,
            },
          },
          colors: [$themeColors.primary],
          plotOptions: {
            radialBar: {
              hollow: {
                size: '22%',
              },
              track: {
                background: $trackBgColor,
              },
              dataLabels: {
                showOn: 'always',
                name: {
                  show: false,
                },
                value: {
                  show: false,
                },
              },
            },
          },
          stroke: {
            lineCap: 'round',
          },
        },
      },
    }
  },
  created() {

  },
  methods: {

    getDataDashboard() {
      let self = this;
      ajustes_generales.cantidadPorProducto(data => {
        const total = data.productos.reduce((acc, producto) => acc + producto.cantidad, 0);

        self.browserData = data.productos.map((producto, index) => {
          const percentage = ((producto.cantidad / total) * 100).toFixed(0);
          const chartClone = JSON.parse(JSON.stringify(this.chart));
          chartClone.options.colors[0] = this.chartColor[index];
          chartClone.series[0] = percentage;
          self.chartData.push(chartClone);

          return {
            browserImg: producto.icon,
            name: producto.descripcion,
            usage: `${percentage}%`
          }
        });

        // update chartSeries
        self.chartSeries = self.chartData.map(chart => chart.series[0]);
      });

    }


  },

  mounted() {
    this.getDataDashboard();
  }
}
</script>
