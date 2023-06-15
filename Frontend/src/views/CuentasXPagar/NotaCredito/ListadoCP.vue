<template>
  <div class="main">
    <div class="content-box">
      <div class="content-box-header">
        <div class="box-title">Administrar Notas de Crédito</div>
        <div class="box-description">Listado Notas de Crédito</div>
      </div>
      <div class="row">
        <div class="col-md-6 sm-text-center">
          <router-link class="btn btn-success" tag="button" :to="{ name: 'registrar-nota-credito-cp' }">
            <i class="pe-7s-plus"></i> Registrar N/C
          </router-link>
        </div>
        <div
          @keyup.enter="filter.page = 1;obtenerNotasCredito();"
          class="col-md-6 sm-text-center form-inline justify-content-end"
        >
          <select
            v-model="filter.search.field"
            class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
          >
            <option value="no_documento">No Documento</option>
          </select>
          <input
            v-model="filter.search.value"
            class="form-control mb-1 mr-sm-1 mb-sm-0"
            placeholder="Buscar"
            type="text"
          >
          <button class="btn btn-info" @click="filter.page = 1;obtenerNotasCredito();">
            <i class="pe-7s-search"></i> Buscar
          </button>
        </div>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>No. Recibo</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Monto</th>
              <th>Concepto</th>
              <th class="text-center action">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(nota_credito,key) in notas_credito">
              <tr :key="nota_credito.id_nota_credito">
                <td>{{ nota_credito.no_documento }}</td>
                <td>{{ formatDate(nota_credito.fecha_emision)}}</td>
                <td>{{ nota_credito.nota_credito_proveedor.nombre_comercial }}</td>
                <td>C$ {{ Number(nota_credito.monto_total) | formatMoney(2) }}</td>
                <td>{{ nota_credito.concepto }}</td>

                <td class="text-center">
                  <router-link
                          v-tooltip="'Mostrar detalles de recibo'"
                          :to="{ name: 'mostrar-nota-credito-cp', params: { id_nota_credito: nota_credito.id_nota_credito } }"
                          tag="a"
                  >
                    <i aria-hidden="true" class="fa fa-eye"></i>
                  </router-link>
                </td>
              </tr>
            </template>
            <tr v-if="!notas_credito.length">
              <td class="text-center" colspan="6">Sin datos</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        @changePage="changePage"
        @changeLimit="changeLimit"
        :items="notas_credito"
        :total="total"
        :page="filter.page"
        :limitOptions="filter.limitOptions"
        :limit="filter.limit"
      ></pagination>
      <template v-if="loading">
        <BlockUI :message="msg" :url="url"></BlockUI>
      </template>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
import nota_credito from "../../api/notas_credito_cp";
import loadingImage from '../../assets/images/block50.gif'

export default {
  data() {
    return {
      loading:true,
      msg: 'Cargando el contenido espere un momento',
      url : '/public'+loadingImage,   //It is important to import the loading image then use here
      filter: {
        page: 1,
        limit: 5,
        limitOptions: [5, 10, 15, 20],
        search: {
          field: "no_documento",
          value: ""
        }
      },
      notas_credito: [],
      total: 0
    };
  },
  methods: {
    formatDate(date) {
      return moment(date).format('YYYY-MM-DD')
    },
    mostrarPagos(key) {
      if (this.notas_credito[key].mostrar) {
        this.notas_credito[key].mostrar = 0;
      } else {
        this.notas_credito[key].mostrar = 1;
      }
    },
    obtenerNotasCredito() {
      var self = this;
      nota_credito.obtener(
        self.filter,
        data => {
          data.rows.forEach((notas_credito, key) => {
            data.rows[key].mostrar = 0;
          });
          self.notas_credito = data.rows;
          self.total = data.total;
          self.loading = false;
        },
        err => {
          console.log(err);
          self.loading = false;
        }
      );
    },
    changeLimit(limit) {
      this.filter.page = 1;
      this.filter.limit = limit;
      this.obtenerNotasCredito();
    },
    changePage(page) {
      this.filter.page = page;
      this.obtenerNotasCredito();
    },

  },
  /*components: {
    pagination: Pagination
  },*/
  mounted() {
    this.obtenerNotasCredito();
  }
};
</script>
<style lang="scss" scoped>
.search-field {
  min-width: 120px;
}
.table {
  a {
    color: #2675dc;
  }
  .table-number {
    width: 60px;
  }
  .action {
    width: 180px;
  }
  .detail-link {
    width: 60px;
    position: relative;
    .link {
      width: 10px;
      height: 4px;
      margin-left: auto;
      margin-right: auto;
      cursor: pointer;
      margin-top: 8px;
      background-color: #595959;
      border: 1px solid #595959;
      &:before {
        content: "";
        width: 4px;
        height: 10px;
        left: 0px;
        right: 0px;
        cursor: pointer;
        margin: 0px auto;
        margin-top: -4px;
        position: absolute;
        background-color: #595959;
        border: 1px solid #595959;
      }
    }
  }
}
</style>