<script>
import {Bar} from 'vue-chartjs'

import ajustes_generales from "@/api/admon/ajustes_generales";

export default {
  extends: Bar,

  data() {
    return {
      count: 0,
      labelFactura: '',
      chartData: {
        labels: [],

        datasets: [
          {
            label: ['C$'],
            borderWidth: 1,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            pointBorderColor: '#2554FF',
            data: [],

          }

        ]

      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            }
          }],
          xAxes: [{
            gridLines: {
              display: false
            }
          }]
        },
        legend: {
          display: false,
        },
        responsive: true,
        maintainAspectRatio: false
      }
    }
  },
  methods: {
    //
    getDataDashboard() {
      let self = this;
      ajustes_generales.obtenerEstadisticasDashboard(data => {
        self.count = data.facturas;
        self.labelFactura = data.facturas;

        // Actualiza el conjunto de datos con los datos recibidos de la API
        for (let i = 0; i < self.count.length; i++) {
          this.chartData.datasets[0].data.push(self.count[i].cantidad.toLocaleString());

        }

        for (let i = 0; i < self.count.length; i++) {
          this.chartData.labels.push(self.labelFactura[i].fecha_factura);
        }

        this.renderChart(this.chartData, this.options);
      });
    }

  },
  mounted() {
    this.renderChart(this.chartData, this.options);
    this.getDataDashboard();
  }
}
</script>