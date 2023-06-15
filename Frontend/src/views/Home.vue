<template>
  <div>
    <template v-if="this.userRole === 3">
      <!-- Miscellaneous Charts -->
      <b-row class="match-height">
        <b-col cols="12" lg="12">
          <b-card class="text-center">
            <b-img class="logo_inicio" :src="imgHome"/>
          </b-card>
        </b-col>

        <b-col
            lg="12"
            cols="12"
        >
          <card-statistics-group :loading="loading"/>

        </b-col>
      </b-row>
      <!--/ Miscellaneous Charts -->

<!--      <b-row class="match-height">
        <b-col
            xl="6"
            md="10"
            sm="12"
        >
          <bar-chart
              icon="EyeIcon"
              statistic="36.9k"
              statistic-title="Views"
              color="info"
          />
        </b-col>
        <b-col
            xl="6"
            md="10"
            sm="12"
        >
          <producto-popular
              color="warning"
              icon="MessageSquareIcon"
              statistic="12k"
              statistic-title="Comments"
          />
        </b-col>

      </b-row>-->



      <b-row class="math-height">
        <b-col xl="12" md="12" sm="12">
          <!-- Invoice Description: Table -->
          <b-card>
            <h6 class="font-weight-bolder mb-1">Usuarios en línea</h6>
            <b-table-lite
                responsive
                :items="usuarios_activos"
                :fields="fields"
            > <!--                  :tbody-tr-class="rowClass"-->
              <template #cell(Nombre)="data">
                <b-card-text class="font-weight-bold mb-25">
                  {{data.item.name}}
                </b-card-text>
              </template>
              <template #cell(Correo)="data">
                <b-card-text class="font-weight-bold mb-25">
                  {{data.item.email}}
                </b-card-text>
              </template>
              <template #cell(Estado)="data">
                <!--              <b-card-text class="font-weight-bold mb-25" style="color: green">
                                {{data.item.estado}}
                              </b-card-text>--> <div class="text-center">
                <span v-b-tooltip="'En Línea'" class="bullet bullet-sm mr-1 bullet-success"></span>
              </div>
              </template>
              <template #cell(ultima_vez_visto)="data">
                <b-card-text class="font-weight-bold mb-25">
                  {{formatDate(data.item.last_seen)}}
                </b-card-text>
              </template>

              <!--Header customization-->
              <template #head(Estado)="data">
                <div class="text-center">
                  <span class="text-bold">{{ 'Estado' }}</span>
                </div>
              </template>
            </b-table-lite>
          </b-card>
        </b-col>
      </b-row>
    </template>
    <template v-else>
      <b-row class="match-height">
        <b-col class="mb-2 mb-xl-0">
          <b-card>


            <b-card-body class="statistics-body">
              <!-- Logo & Text -->
              <b-media-body class="my-auto">
<!--                <template>
                  <v-lazy-image class="show-on-mobile"
                                :src="appLogo"
                                :src-placeholder="appLogopla"
                                style="max-width: 31%; display: block; margin-left: auto; margin-right: auto;"
                  />
                </template>-->


                <h2 class="font-weight-bolder mb-0 show-on-mobile-font" style="font-size: xx-large; ;text-align: center">
                  {{this.bienvenido}}
                </h2>


              </b-media-body>
            </b-card-body>
          </b-card>
        </b-col>
        <!--      <b-col
                      xl="2"
                      md="4"
                      sm="6"
              >
                <statistic-card-vertical
                        icon="EyeIcon"
                        statistic="36.9k"
                        statistic-title="Views"
                        color="info"
                />
              </b-col>
              <b-col
                      xl="2"
                      md="4"
                      sm="6"
              >
                <statistic-card-vertical
                        color="warning"
                        icon="MessageSquareIcon"
                        statistic="12k"
                        statistic-title="Comments"
                />
              </b-col>
              <b-col
                      xl="2"
                      md="4"
                      sm="6"
              >
                <statistic-card-vertical
                        color="danger"
                        icon="ShoppingBagIcon"
                        statistic="97.8k"
                        statistic-title="Orders"
                />
              </b-col>
              <b-col
                      xl="2"
                      md="4"
                      sm="6"
              >
                <statistic-card-vertical
                        color="primary"
                        icon="HeartIcon"
                        statistic="26.8"
                        statistic-title="Favorited"
                />
              </b-col>
              <b-col
                      xl="2"
                      md="4"
                      sm="6"
              >
                <statistic-card-vertical
                        color="success"
                        icon="AwardIcon"
                        statistic="689"
                        statistic-title="Reviews"
                />
              </b-col>
              <b-col
                      xl="2"
                      md="4"
                      sm="6"
              >
                <statistic-card-vertical
                        hide-chart
                        color="danger"
                        icon="TruckIcon"
                        statistic="2.1k"
                        statistic-title="Returns"
                />
              </b-col>-->
      </b-row>
    </template>
    <template v-if="loading">
      <BlockUI>
        <b-spinner label="Cargando..." variant="info"/>
      </BlockUI>
    </template>
  </div>
</template>

<script>
import BarChart from '@/views/charts/BarChart.vue';
import {BRow, BCol, BTableLite, BCardText, BCard, BLink, BImg, BCardBody, BMediaBody, BSpinner, VBTooltip} from 'bootstrap-vue'
import StatisticCardVertical from '@core/components/statistics-cards/StatisticCardVertical.vue'
import StatisticCardHorizontal from '@core/components/statistics-cards/StatisticCardHorizontal.vue'
import StatisticCardWithAreaChart from '@core/components/statistics-cards/StatisticCardWithAreaChart.vue'
import StatisticCardWithLineChart from '@core/components/statistics-cards/StatisticCardWithLineChart.vue'
import {kFormatter} from '@core/utils/filter'
import CardStatisticOrderChart from './card/CardStatisticOrderChart.vue'
import CardStatisticProfitChart from './card/CardStatisticProfitChart.vue'
import CardStatisticsGroup from './card/CardStatisticsGroup.vue'
import usuarios from '../api/admon/usuarios'
import ToastificationContent from "../@core/components/toastification/ToastificationContent";
import moment from "../../../Backend/resources/app/assets/plugins/moment/moment";
import {$themeConfig} from '@themeConfig'
import ajustes_generales from "@/api/admon/ajustes_generales";
import VLazyImage from "v-lazy-image/v2";
import user from "@/api/admon/usuarios";

export default {
  components: {
    BMediaBody,
    BCardBody,
    BLink,
    BImg,
    BRow,
    BCol,
    BTableLite,
    BCardText,
    BCard,
    VBTooltip,
    StatisticCardVertical,
    StatisticCardHorizontal,
    StatisticCardWithAreaChart,
    StatisticCardWithLineChart,
    CardStatisticOrderChart,
    CardStatisticProfitChart,
    CardStatisticsGroup,
    VLazyImage,
    BarChart,
    BSpinner
  },
  directives: {
    'b-tooltip': VBTooltip,
  },

  data() {
    return {
      bienvenido: '',
      appLogo: '',
      imgHome: '',
      loading: true,
      msg: 'Cargando el contenido espere un momento',
      // Area charts
      subscribersGained: {},
      revenueGenerated: {},
      quarterlySales: {},
      ordersRecevied: {},
      appLogopla: require('/public/logotipo.png'),
      // Line Charts
      siteTraffic: {},
      activeUsers: {},
      newsletter: {},
      usuarios: [],
      usuarios_activos: [],
      fields: [
        'Nombre',
        'Correo',
        'Estado',
        'ultima_vez_visto',

      ],
      userRole: '',
      form: {
        userData: {}
      },
    }
  },
  /* created() {
     // Subscribers gained
     this.$http.get('/card/card-statistics/subscribers')
             .then(response => { this.subscribersGained = response.data })

     // Revenue Generated
     this.$http.get('/card/card-statistics/revenue')
             .then(response => { this.revenueGenerated = response.data })

     // Sales
     this.$http.get('/card/card-statistics/sales')
             .then(response => { this.quarterlySales = response.data })

     // Orders
     this.$http.get('/card/card-statistics/orders')
             .then(response => { this.ordersRecevied = response.data })

     // Site Traffic gained
     this.$http.get('/card/card-statistics/site-traffic')
             .then(response => { this.siteTraffic = response.data })

     // Active Users
     this.$http.get('/card/card-statistics/active-users')
             .then(response => { this.activeUsers = response.data })

     // Newsletter
     this.$http.get('/card/card-statistics/newsletter')
             .then(response => { this.newsletter = response.data })
   },*/
  methods: {
    kFormatter,

    getUserData() {
      const self = this;
      user.me(data => {
            self.form.userData = data.userData[0];
            self.bienvenido = 'BIENVENIDO ' + self.form.userData.name.toUpperCase() + ' AL SISTEMA'
          },
          err => {
            console.log('Error detectado' + err);
          })
    },

    obtenerUsuarios() {
      let self = this;
      self.loading = true;
      usuarios.userActivity(data => {
        self.usuarios = data.users;
        self.userRole = data.userRole;
        self.usuarios_activos = data.active_users;
        self.loading = false;
      })
    },
    formatDate(date) {
      return moment(date).format('DD-MM-YYYY, h:mm:ss a')
    },
    rowClass(item, type) {
      if (!item || type !== 'row') return
      if (item.estado === 'En Línea.') return 'text-center'
    },
    //carga la imagen del logo desde BD
    cargarAjustes() {
      let self = this;

      ajustes_generales.cargarRecursos(data => {

            this.recursos = data;
            this.appLogo = this.recursos.host + this.recursos.logo_login.valor;
            this.imgHome =this.recursos.host + this.recursos.logo_home.valor;

          },
          err => {

            // console.log(err);
          });
    },

  },
  setup() {


    // App Name
    const {appName} = $themeConfig.app

    return {

      // App Name
      appName,
    }
  },
  mounted() {

    this.obtenerUsuarios();
    this.cargarAjustes();
    this.getUserData();

  }
}
</script>

<style>
@media (max-width: 767px) {
  .show-on-mobile {
    max-width: 100% !important;
    display: block !important;
    margin-left: auto !important;
    margin-right: auto !important;
  }

  .show-on-mobile-font {
    font-size: x-large !important;
  }

}

.logo_inicio {
  width: 50%;
  height: auto;
}

.v-lazy-image {
  filter: blur(10px);
  transition: filter 0.1s;
}

.v-lazy-image-loaded {
  filter: blur(0);
}
</style>
